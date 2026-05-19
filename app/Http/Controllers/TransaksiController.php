<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\StokBatch;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\TransaksiFifoLog;
use App\Models\Piutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // ─────────────────────────────────────────────
    // 1. DAFTAR PENJUALAN
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'user'])
            ->latest('tanggal')
            ->latest('id');

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        // Filter status bayar
        if ($request->filled('status_bayar')) {
            $query->where('status_bayar', $request->status_bayar);
        }

        // Filter status transaksi (aktif / dibatalkan)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan semua (aktif & dibatalkan)
        }

        $transaksi = $query->paginate(20)->withQueryString();

        // Statistik hari ini
        $today = now()->toDateString();
        $statsHariIni = Transaksi::whereDate('tanggal', $today)
            ->where('status', 'aktif')
            ->selectRaw('
                COUNT(*) as total_trx,
                COALESCE(SUM(total), 0) as omset,
                COALESCE(SUM(total_hpp), 0) as hpp,
                COUNT(CASE WHEN status_bayar != "lunas" THEN 1 END) as piutang_count
            ')
            ->first();

        $piutangTotal = Piutang::where('status', '!=', 'lunas')->sum('sisa_tagihan');

        return view('transaksi.index', compact(
            'transaksi',
            'statsHariIni',
            'piutangTotal'
        ));
    }

    // ─────────────────────────────────────────────
    // 2. HALAMAN POS KASIR
    // ─────────────────────────────────────────────
    public function pos()
    {
        $produk    = Produk::with('kategori')
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        $pelanggan = Pelanggan::where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        $toko = \App\Models\PengaturanToko::instance();

        return view('transaksi.pos', compact('produk', 'pelanggan', 'toko'));
    }

    // ─────────────────────────────────────────────
    // 3. SIMPAN TRANSAKSI
    // ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'          => 'nullable|exists:pelanggan,id',
            'items'                 => 'required|array|min:1',
            'items.*.produk_id'     => 'required|exists:produk,id',
            'items.*.jumlah'        => 'required|integer|min:1',
            'items.*.harga_jual'    => 'required|numeric|min:0',
            'items.*.diskon_item'   => 'nullable|numeric|min:0',
            'diskon_nominal'        => 'nullable|numeric|min:0',
            'status_bayar'          => 'required|in:lunas,sebagian,belum_bayar',
            'jumlah_bayar'          => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {


            // ── Hitung subtotal ─────────────────────────────
            $subtotal = 0;
            foreach ($request->items as $item) {
                $dis       = $item['diskon_item'] ?? 0;
                $subtotal += ($item['harga_jual'] - $dis) * $item['jumlah'];
            }

            $diskon  = $request->diskon_nominal ?? 0;
            $total   = max(0, $subtotal - $diskon);
            $bayar   = $request->jumlah_bayar ?? ($request->status_bayar === 'lunas' ? $total : 0);
            $sisa    = max(0, $total - $bayar);
            $kembali = max(0, $bayar - $total);

            // ── Buat header transaksi ───────────────────────
            $transaksi = Transaksi::create([
                'nomor' => $this->generateNomorPiutang(),
                'pelanggan_id'    => $request->pelanggan_id,
                'user_id'         => Auth::user()->id,
                'tanggal'         => now()->toDateString(),
                'subtotal'        => $subtotal,
                'diskon_nominal'  => $diskon,
                'total'           => $total,
                'total_hpp'       => 0, // diupdate setelah FIFO
                'status_bayar'    => $request->status_bayar,
                'jumlah_bayar'    => $bayar,
                'sisa_tagihan'    => $sisa,
                'kembalian'       => $kembali,
                'status'          => 'aktif',
            ]);

            // ── Proses setiap item + FIFO ───────────────────
            $totalHpp = 0;
            foreach ($request->items as $item) {
                $produk   = Produk::findOrFail($item['produk_id']);
                $disItem  = $item['diskon_item'] ?? 0;
                $hargaEfektif = $item['harga_jual'] - $disItem;
                $subtotalItem = $hargaEfektif * $item['jumlah'];

                // Validasi stok
                if ($produk->stok_saat_ini < $item['jumlah']) {
                    throw new \Exception("Stok {$produk->nama} tidak mencukupi. Tersedia: {$produk->stok_saat_ini}");
                }

                // Hitung HPP via FIFO
                [$hppItem, $fifoLogs] = $this->hitungFifo($produk->id, $item['jumlah']);

                $detail = $transaksi->detail()->create([
                    'produk_id'    => $item['produk_id'],
                    'jumlah'       => $item['jumlah'],
                    'harga_jual'   => $item['harga_jual'],
                    'diskon_item'  => $disItem,
                    'subtotal'     => $subtotalItem,
                    'hpp'          => $hppItem,
                ]);

                // Simpan FIFO log & kurangi batch
                foreach ($fifoLogs as $log) {
                    TransaksiFifoLog::create([
                        'transaksi_detail_id' => $detail->id,
                        'stok_batch_id'       => $log['batch_id'],
                        'jumlah_dipakai'      => $log['jumlah'],
                        'harga_modal'         => $log['harga_modal'],
                        'subtotal_hpp'        => $log['subtotal'],
                    ]);

                    StokBatch::where('id', $log['batch_id'])
                        ->decrement('jumlah_tersisa', $log['jumlah']);
                }

                // Update stok produk
                $produk->decrement('stok_saat_ini', $item['jumlah']);
                $totalHpp += $hppItem;
            }

            // Update total HPP transaksi
            $transaksi->update(['total_hpp' => $totalHpp]);

            // ── Buat piutang jika belum lunas ───────────────
            if ($request->status_bayar !== 'lunas') {
                $prefixPiu = 'PIU-' . now()->format('Ymd') . '-';
                $lastPiu   = Piutang::where('nomor', 'like', $prefixPiu . '%')
                    ->orderByDesc('nomor')->value('nomor');
                $urutPiu   = $lastPiu ? (int) substr($lastPiu, -3) + 1 : 1;

                Piutang::create([
                    'nomor'             => $prefixPiu . str_pad($urutPiu, 3, '0', STR_PAD_LEFT),
                    'transaksi_id'      => $transaksi->id,
                    'pelanggan_id'      => $request->pelanggan_id,
                    'total_tagihan'     => $total,
                    'sudah_dibayar'     => $bayar,
                    'sisa_tagihan'      => $sisa,
                    'status'            => $request->status_bayar,
                    'tanggal_transaksi' => now()->toDateString(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Transaksi berhasil disimpan.',
                'nomor' => $this->generateNomorPiutang(),
                'transaksi_id' => $transaksi->id,
                'total'    => $total,
                'kembalian' => $kembali,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    private function generateNomorPiutang()
    {
        $prefix = 'PIU-' . now()->format('Ymd') . '-';

        do {
            $last = Piutang::where('nomor', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderByDesc('nomor')
                ->value('nomor');

            $next = $last
                ? ((int) substr($last, -3)) + 1
                : 1;

            $nomor = $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
        } while (Piutang::where('nomor', $nomor)->exists());

        return $nomor;
    }

    // ─────────────────────────────────────────────
    // 4. DETAIL TRANSAKSI (JSON untuk modal)
    // ─────────────────────────────────────────────
    public function show($id)
    {
        $transaksi = Transaksi::with([
            'pelanggan',
            'user',
            'detail.produk',
        ])->findOrFail($id);

        return response()->json($transaksi);
    }

    // ─────────────────────────────────────────────
    // 5. STRUK (view untuk cetak)
    // ─────────────────────────────────────────────
    public function struk($id)
    {
        $transaksi = Transaksi::with([
            'pelanggan',
            'user',
            'detail.produk',
        ])->findOrFail($id);

        // Ambil pengaturan toko
        $toko = \App\Models\PengaturanToko::first();

        return view('transaksi.struk', compact('transaksi', 'toko'));
    }

    // ─────────────────────────────────────────────
    // 6. BATALKAN TRANSAKSI
    // ─────────────────────────────────────────────
    public function batal(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string|max:500',
        ]);

        $transaksi = Transaksi::with('detail')->findOrFail($id);

        if ($transaksi->status === 'dibatalkan') {
            return back()->with('error', 'Transaksi sudah dibatalkan.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok dari FIFO log (reverse)
            foreach ($transaksi->detail as $detail) {
                $detail->fifoLog()->each(function ($log) {
                    StokBatch::where('id', $log->stok_batch_id)
                        ->increment('jumlah_tersisa', $log->jumlah_dipakai);
                });
                Produk::where('id', $detail->produk_id)
                    ->increment('stok_saat_ini', $detail->jumlah);
            }

            $transaksi->update([
                'status'           => 'dibatalkan',
                'dibatalkan_pada'  => now(),
                'dibatalkan_oleh'  => Auth::user()->id,
                'alasan_batal'     => $request->alasan_batal,
            ]);

            // Batalkan piutang terkait
            Piutang::where('transaksi_id', $id)->delete();

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────
    // PRIVATE: Hitung HPP dengan FIFO
    // ─────────────────────────────────────────────
    private function hitungFifo(int $produkId, int $jumlahDibutuhkan): array
    {
        $batches = StokBatch::where('produk_id', $produkId)
            ->where('jumlah_tersisa', '>', 0)
            ->orderBy('tanggal_masuk')
            ->orderBy('id')
            ->get();

        $totalHpp = 0;
        $logs     = [];
        $sisa     = $jumlahDibutuhkan;

        foreach ($batches as $batch) {
            if ($sisa <= 0) break;

            $pakai     = min($sisa, $batch->jumlah_tersisa);
            $subtotal  = $pakai * $batch->harga_modal;

            $logs[] = [
                'batch_id'    => $batch->id,
                'jumlah'      => $pakai,
                'harga_modal' => $batch->harga_modal,
                'subtotal'    => $subtotal,
            ];

            $totalHpp += $subtotal;
            $sisa     -= $pakai;
        }

        if ($sisa > 0) {
            throw new \Exception("Stok batch tidak mencukupi untuk FIFO.");
        }

        return [$totalHpp, $logs];
    }
}
