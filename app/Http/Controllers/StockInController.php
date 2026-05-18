<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\HutangSupplierPembayaran;
use App\Models\Produk;
use App\Models\StokBatch;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────────

    public function index()
    {
        $barangMasuk = BarangMasuk::with(['supplier', 'user', 'detail.produk', 'pembayaran.user'])
            ->where('jenis', 'masuk_normal')
            ->latest()
            ->get();

        $produk   = Produk::where('is_aktif', true)->orderBy('nama')->get();
        $supplier = Supplier::where('is_aktif', true)->orderBy('nama')->get();

        // Ringkasan hutang untuk info panel
        $ringkasanHutang = [
            'total_hutang'  => BarangMasuk::masukNormal()->hutang()->count(),
            'nilai_hutang'  => BarangMasuk::masukNormal()->hutang()
                ->with('detail')
                ->get()
                ->sum('sisa_hutang'),
            'jatuh_tempo_7' => BarangMasuk::masukNormal()->jatuhTempo(7)->count(),
        ];

        return view('inventory.stock-in.index', compact(
            'barangMasuk',
            'produk',
            'supplier',
            'ringkasanHutang'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // STORE – Catat barang masuk baru
    // ─────────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'             => 'nullable|exists:supplier,id',
            'tanggal'                 => 'required|date',
            'nomor_faktur_supplier'   => 'nullable|string|max:255',
            'catatan'                 => 'nullable|string',
            'items'                   => 'required|array|min:1',
            'items.*.produk_id'       => 'required|exists:produk,id',
            'items.*.jumlah'          => 'required|integer|min:1',
            'items.*.harga_modal'     => 'required|numeric|min:0',

            // ── Pembayaran ──
            'status_bayar'            => 'required|in:lunas,sebagian,hutang',
            'bayar_awal'              => 'required_if:status_bayar,sebagian|nullable|numeric|min:0',
            'metode_bayar'            => 'required_unless:status_bayar,hutang|nullable|in:tunai,transfer,cek,lainnya',
            'nomor_referensi'         => 'nullable|string|max:255',
            'tanggal_jatuh_tempo'     => 'required_unless:status_bayar,lunas|nullable|date|after_or_equal:tanggal',
            'catatan_pembayaran'      => 'nullable|string',
        ], [
            'items.required'                       => 'Minimal satu produk harus diisi.',
            'items.*.produk_id.required'           => 'Produk wajib dipilih.',
            'items.*.jumlah.required'              => 'Jumlah wajib diisi.',
            'items.*.jumlah.min'                   => 'Jumlah minimal 1.',
            'items.*.harga_modal.required'         => 'Harga modal wajib diisi.',
            'bayar_awal.required_if'               => 'Nominal bayar wajib diisi untuk pembayaran sebagian.',
            'metode_bayar.required_unless'         => 'Metode bayar wajib dipilih.',
            'tanggal_jatuh_tempo.required_unless'  => 'Tanggal jatuh tempo wajib diisi untuk hutang / sebagian.',
            'tanggal_jatuh_tempo.after_or_equal'   => 'Jatuh tempo tidak boleh sebelum tanggal transaksi.',
        ]);

        DB::transaction(function () use ($request) {
            // Nomor otomatis: BM-20250101-001
            $prefix = 'BM-' . now()->format('Ymd') . '-';
            $last   = BarangMasuk::where('nomor', 'like', $prefix . '%')->count();
            $nomor  = $prefix . str_pad($last + 1, 3, '0', STR_PAD_LEFT);

            // Hitung bayar awal berdasarkan status
            $grandTotal = collect($request->items)->sum(fn($i) => $i['jumlah'] * $i['harga_modal']);

            $bayarAwal = match ($request->status_bayar) {
                'lunas'    => $grandTotal,
                'sebagian' => (float) $request->bayar_awal,
                'hutang'   => 0,
            };

            $barangMasuk = BarangMasuk::create([
                'nomor'                  => $nomor,
                'jenis'                  => 'masuk_normal',
                'supplier_id'            => $request->supplier_id,
                'user_id'                => auth()->id(),
                'tanggal'                => $request->tanggal,
                'nomor_faktur_supplier'  => $request->nomor_faktur_supplier,
                'catatan'                => $request->catatan,
                // pembayaran
                'status_bayar'           => $request->status_bayar,
                'total_dibayar'          => $bayarAwal,
                'tanggal_jatuh_tempo'    => in_array($request->status_bayar, ['sebagian', 'hutang'])
                    ? $request->tanggal_jatuh_tempo
                    : null,
                'catatan_pembayaran'     => $request->catatan_pembayaran,
            ]);

            // ── Detail produk & stok ──────────────────────────────────────
            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_modal'];

                $detail = BarangMasukDetail::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'produk_id'       => $item['produk_id'],
                    'jumlah'          => $item['jumlah'],
                    'harga_modal'     => $item['harga_modal'],
                    'subtotal'        => $subtotal,
                ]);

                StokBatch::create([
                    'produk_id'              => $item['produk_id'],
                    'barang_masuk_detail_id' => $detail->id,
                    'tanggal_masuk'          => $request->tanggal,
                    'harga_modal'            => $item['harga_modal'],
                    'jumlah_awal'            => $item['jumlah'],
                    'jumlah_tersisa'         => $item['jumlah'],
                ]);

                Produk::where('id', $item['produk_id'])
                    ->increment('stok_saat_ini', $item['jumlah']);
            }

            // ── Catat pembayaran awal jika ada ───────────────────────────
            if ($bayarAwal > 0) {
                HutangSupplierPembayaran::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'user_id'         => auth()->id(),
                    'tanggal_bayar'   => $request->tanggal,
                    'jumlah_bayar'    => $bayarAwal,
                    'metode_bayar'    => $request->metode_bayar ?? 'tunai',
                    'nomor_referensi' => $request->nomor_referensi,
                    'catatan'         => $request->status_bayar === 'lunas'
                        ? 'Pembayaran lunas saat terima barang'
                        : 'Pembayaran awal (DP)',
                ]);
            }
        });

        return redirect()->back()->with('success', 'Barang masuk berhasil dicatat.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // BAYAR CICILAN – Tambah cicilan hutang
    // ─────────────────────────────────────────────────────────────────────

    public function bayarCicilan(Request $request, $id)
    {
        $barangMasuk = BarangMasuk::with(['detail', 'pembayaran'])->findOrFail($id);

        if ($barangMasuk->isLunas()) {
            return response()->json(['message' => 'Transaksi ini sudah lunas.'], 422);
        }

        $sisaHutang = $barangMasuk->sisa_hutang;

        $request->validate([
            'tanggal_bayar'   => 'required|date',
            'jumlah_bayar'    => "required|numeric|min:1|max:{$sisaHutang}",
            'metode_bayar'    => 'required|in:tunai,transfer,cek,lainnya',
            'nomor_referensi' => 'nullable|string|max:255',
            'catatan'         => 'nullable|string',
        ], [
            'jumlah_bayar.max' => "Jumlah bayar tidak boleh melebihi sisa hutang (Rp " . number_format($sisaHutang, 0, ',', '.') . ").",
        ]);

        DB::transaction(function () use ($request, $barangMasuk) {
            HutangSupplierPembayaran::create([
                'barang_masuk_id' => $barangMasuk->id,
                'user_id'         => auth()->id(),
                'tanggal_bayar'   => $request->tanggal_bayar,
                'jumlah_bayar'    => $request->jumlah_bayar,
                'metode_bayar'    => $request->metode_bayar,
                'nomor_referensi' => $request->nomor_referensi,
                'catatan'         => $request->catatan,
            ]);

            // Refresh relasi sebelum recalculate
            $barangMasuk->load('detail', 'pembayaran');
            $barangMasuk->recalculatePayment();
        });

        return response()->json(['message' => 'Cicilan berhasil dicatat.']);
    }

    // ─────────────────────────────────────────────────────────────────────
    // HAPUS CICILAN
    // ─────────────────────────────────────────────────────────────────────

    public function hapusCicilan($barangMasukId, $cicilanId)
    {
        $barangMasuk = BarangMasuk::with(['detail', 'pembayaran'])->findOrFail($barangMasukId);
        $cicilan     = HutangSupplierPembayaran::where('barang_masuk_id', $barangMasukId)
            ->findOrFail($cicilanId);

        DB::transaction(function () use ($barangMasuk, $cicilan) {
            $cicilan->delete();
            $barangMasuk->load('detail', 'pembayaran');
            $barangMasuk->recalculatePayment();
        });

        return response()->json(['message' => 'Cicilan berhasil dihapus.']);
    }

    // ─────────────────────────────────────────────────────────────────────
    // DESTROY – Hapus barang masuk
    // ─────────────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::with('detail')->findOrFail($id);

        DB::transaction(function () use ($barangMasuk) {
            foreach ($barangMasuk->detail as $detail) {
                $batch = StokBatch::where('barang_masuk_detail_id', $detail->id)->first();

                if ($batch) {
                    $terpakai = $batch->jumlah_awal - $batch->jumlah_tersisa;
                    if ($terpakai > 0) {
                        throw new \Exception(
                            'Barang masuk tidak dapat dihapus karena sebagian stok sudah terjual.'
                        );
                    }
                    $batch->delete();
                }

                Produk::where('id', $detail->produk_id)
                    ->decrement('stok_saat_ini', $detail->jumlah);

                $detail->delete();
            }

            // Hapus semua riwayat pembayaran
            $barangMasuk->pembayaran()->delete();

            $barangMasuk->delete();
        });

        return redirect()->back()->with('success', 'Data barang masuk berhasil dihapus.');
    }
}
