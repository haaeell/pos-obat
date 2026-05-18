<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Piutang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\Modal;
use App\Models\ModalCicilan;
use App\Models\PengaturanToko;
use App\Models\PiutangPembayaran;
use App\Models\StokBatch;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari   = $request->input('dari',   now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        $transaksi = Transaksi::with(['pelanggan', 'user'])
            ->whereBetween('tanggal', [$dari, $sampai])
            ->latest('tanggal')
            ->get();

        $aktifTrx        = $transaksi->where('status', 'aktif');
        $totalPenjualan  = $aktifTrx->sum('total');
        $totalHPP        = $aktifTrx->sum('total_hpp');
        $totalSubtotal   = $aktifTrx->sum('subtotal');
        $totalDiskon     = $aktifTrx->sum('diskon_nominal');
        $labaKotor       = $totalPenjualan - $totalHPP;
        $margin          = $totalPenjualan > 0 ? ($labaKotor / $totalPenjualan) * 100 : 0;
        $jumlahTransaksi = $aktifTrx->count();

        $saldoPiutang  = Piutang::whereIn('status', ['belum_bayar', 'sebagian'])->sum('sisa_tagihan');
        $jumlahPiutang = Piutang::whereIn('status', ['belum_bayar', 'sebagian'])->distinct('pelanggan_id')->count('pelanggan_id');

        $modals = Modal::orderBy('tanggal_pinjaman', 'desc')->get();

        $sisaHutangModal      = $modals->where('status', 'aktif')->sum('sisa_kewajiban');
        $totalModalMasuk      = $modals->sum('nominal_pencairan');
        $totalCicilanTerbayar = $modals->sum('total_terbayar');
        $jumlahModalAktif     = $modals->where('status', 'aktif')->count();

        // ── Hutang supplier ───────────────────────────────────────────────
        $hutangSupplier = BarangMasuk::with('detail')
            ->where('jenis', 'masuk_normal')
            ->whereIn('status_bayar', ['hutang', 'sebagian'])
            ->get();

        $nilaiHutangSupplier   = $hutangSupplier->sum('sisa_hutang');
        $jumlahHutangSupplier  = $hutangSupplier->count();
        $hutangJatuhTempo      = $hutangSupplier
            ->filter(fn($b) => $b->tanggal_jatuh_tempo && $b->tanggal_jatuh_tempo->isPast())
            ->count();
        // ─────────────────────────────────────────────────────────────────

        $totalLunas = Transaksi::where('status', 'aktif')->where('status_bayar', 'lunas')->sum('total');
        $totalPembayaranPiutang = PiutangPembayaran::sum('jumlah');

        $totalPencairanModal = Modal::where('tanggal_pencairan', '>=', $dari)->sum('nominal_pencairan');
        $totalBeliBarang     = BarangMasukDetail::whereHas('barangMasuk', function ($q) {
            $q->where('jenis', 'masuk_normal');
        })->sum('subtotal');

        $totalCicilanDibayar = ModalCicilan::where('status', 'sudah_bayar')->sum('total_bayar');

        $kas = ($totalLunas + $totalPembayaranPiutang + $totalPencairanModal) - ($totalBeliBarang + $totalCicilanDibayar);

        $nilaiStok = StokBatch::selectRaw('SUM(jumlah_tersisa * harga_modal) as nilai')
            ->value('nilai') ?? 0;

        $totalAset = $kas + $saldoPiutang + $nilaiStok;
        // Hutang supplier ikut mengurangi ekuitas bersama hutang modal
        $totalHutang = $sisaHutangModal + $nilaiHutangSupplier;
        $ekuitas     = $totalAset - $totalHutang;

        $ringkasan = [
            'total_penjualan'    => $totalPenjualan,
            'total_subtotal'     => $totalSubtotal,
            'total_diskon'       => $totalDiskon,
            'total_hpp'          => $totalHPP,
            'laba_kotor'         => $labaKotor,
            'margin'             => $margin,
            'jumlah_transaksi'   => $jumlahTransaksi,
            'jumlah_piutang'     => $jumlahPiutang,
            'jumlah_modal_aktif' => $jumlahModalAktif,
        ];

        $saldo = [
            'kas'                    => $kas,
            'piutang'                => $saldoPiutang,
            'nilai_stok'             => $nilaiStok,
            'hutang_modal'           => $sisaHutangModal,
            'total_modal_masuk'      => $totalModalMasuk,
            'total_pencairan_modal'  => $totalPencairanModal,
            'total_cicilan_terbayar' => $totalCicilanTerbayar,
            // ── baru ──
            'hutang_supplier'          => $nilaiHutangSupplier,
            'jumlah_hutang_supplier'   => $jumlahHutangSupplier,
            'hutang_supplier_jt'       => $hutangJatuhTempo,
            // ─────────
            'ekuitas'                => $ekuitas,
        ];

        return view('laporan.index', compact(
            'ringkasan',
            'saldo',
            'dari',
            'sampai'
        ));
    }

    public function cetak(Request $request)
    {
        $dari   = $request->input('dari',   now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        [$ringkasan, $saldo] = $this->hitungData($dari, $sampai);

        $toko = PengaturanToko::instance();

        $pdf = Pdf::loadView('laporan.cetak', compact('ringkasan', 'saldo', 'dari', 'sampai', 'toko'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-keuangan-' . $dari . '-sd-' . $sampai . '.pdf');
    }

    public function export(Request $request)
    {
        $dari   = $request->input('dari',   now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        [$ringkasan, $saldo] = $this->hitungData($dari, $sampai);

        return Excel::download(
            new LaporanExport($ringkasan, $saldo, $dari, $sampai),
            'laporan-keuangan-' . $dari . '-sd-' . $sampai . '.xlsx'
        );
    }

    private function hitungData(string $dari, string $sampai): array
    {
        $transaksi       = Transaksi::whereBetween('tanggal', [$dari, $sampai])->get();
        $aktifTrx        = $transaksi->where('status', 'aktif');
        $totalPenjualan  = $aktifTrx->sum('total');
        $totalHPP        = $aktifTrx->sum('total_hpp');
        $labaKotor       = $totalPenjualan - $totalHPP;
        $margin          = $totalPenjualan > 0 ? ($labaKotor / $totalPenjualan) * 100 : 0;

        $saldoPiutang  = Piutang::whereIn('status', ['belum_bayar', 'sebagian'])->sum('sisa_tagihan');
        $jumlahPiutang = Piutang::whereIn('status', ['belum_bayar', 'sebagian'])->distinct('pelanggan_id')->count('pelanggan_id');

        $modals               = Modal::all();
        $sisaHutangModal      = $modals->where('status', 'aktif')->sum('sisa_kewajiban');
        $totalModalMasuk      = $modals->sum('nominal_pencairan');
        $totalCicilanTerbayar = $modals->sum('total_terbayar');
        $jumlahModalAktif     = $modals->where('status', 'aktif')->count();

        // ── Hutang supplier ───────────────────────────────────────────────
        $hutangSupplier = BarangMasuk::with('detail')
            ->where('jenis', 'masuk_normal')
            ->whereIn('status_bayar', ['hutang', 'sebagian'])
            ->get();

        $nilaiHutangSupplier  = $hutangSupplier->sum('sisa_hutang');
        $jumlahHutangSupplier = $hutangSupplier->count();
        $hutangJatuhTempo     = $hutangSupplier
            ->filter(fn($b) => $b->tanggal_jatuh_tempo && $b->tanggal_jatuh_tempo->isPast())
            ->count();
        // ─────────────────────────────────────────────────────────────────

        $totalLunas             = Transaksi::where('status', 'aktif')->where('status_bayar', 'lunas')->sum('total');
        $totalPembayaranPiutang = PiutangPembayaran::sum('jumlah');
        $totalPencairanModal    = Modal::where('sudah_dicairkan', true)->sum('nominal_pencairan');
        $totalBeliBarang        = BarangMasukDetail::whereHas('barangMasuk', fn($q) => $q->where('jenis', 'masuk_normal'))->sum('subtotal');
        $totalCicilanDibayar    = ModalCicilan::where('status', 'sudah_bayar')->sum('total_bayar');

        $kas       = ($totalLunas + $totalPembayaranPiutang + $totalPencairanModal) - ($totalBeliBarang + $totalCicilanDibayar);
        $nilaiStok = StokBatch::selectRaw('SUM(jumlah_tersisa * harga_modal) as nilai')->value('nilai') ?? 0;
        $totalAset = $kas + $saldoPiutang + $nilaiStok;
        $totalHutang = $sisaHutangModal + $nilaiHutangSupplier;
        $ekuitas   = $totalAset - $totalHutang;

        $ringkasan = [
            'total_penjualan'    => $totalPenjualan,
            'total_subtotal'     => $aktifTrx->sum('subtotal'),
            'total_diskon'       => $aktifTrx->sum('diskon_nominal'),
            'total_hpp'          => $totalHPP,
            'laba_kotor'         => $labaKotor,
            'margin'             => $margin,
            'jumlah_transaksi'   => $aktifTrx->count(),
            'jumlah_piutang'     => $jumlahPiutang,
            'jumlah_modal_aktif' => $jumlahModalAktif,
        ];

        $saldo = [
            'kas'                    => $kas,
            'piutang'                => $saldoPiutang,
            'nilai_stok'             => $nilaiStok,
            'hutang_modal'           => $sisaHutangModal,
            'total_modal_masuk'      => $totalModalMasuk,
            'total_pencairan_modal'  => $totalPencairanModal,
            'total_cicilan_terbayar' => $totalCicilanTerbayar,
            // ── baru ──
            'hutang_supplier'        => $nilaiHutangSupplier,
            'jumlah_hutang_supplier' => $jumlahHutangSupplier,
            'hutang_supplier_jt'     => $hutangJatuhTempo,
            // ─────────
            'ekuitas'                => $ekuitas,
        ];

        return [$ringkasan, $saldo];
    }
}
