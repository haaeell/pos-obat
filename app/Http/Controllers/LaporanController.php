<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Piutang;
use App\Models\BarangMasukDetail;
use App\Models\Modal;
use App\Models\ModalCicilan;
use App\Models\PiutangPembayaran;
use App\Models\StokBatch;

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

        $saldoPiutang = Piutang::whereIn('status', ['belum_bayar', 'sebagian'])->sum('sisa_tagihan');
        $jumlahPiutang = Piutang::whereIn('status', ['belum_bayar', 'sebagian'])->distinct('pelanggan_id')->count('pelanggan_id');

        $modals = Modal::orderBy('tanggal_pinjaman', 'desc')->get();

        $sisaHutangModal      = $modals->where('status', 'aktif')->sum('sisa_kewajiban');
        $totalModalMasuk      = $modals->sum('nominal_pencairan');
        $totalCicilanTerbayar = $modals->sum('total_terbayar');
        $jumlahModalAktif     = $modals->where('status', 'aktif')->count();

        $totalLunas = Transaksi::where('status', 'aktif')->where('status_bayar', 'lunas')->sum('total');
        $totalPembayaranPiutang = PiutangPembayaran::sum('jumlah');

        $totalPencairanModal = Modal::where('tanggal_pencairan', '>=', $dari)->sum('nominal_pencairan');
        $totalBeliBarang = BarangMasukDetail::whereHas('barangMasuk', function ($q) {
            $q->where('jenis', 'masuk_normal');
        })->sum('subtotal');

        $totalCicilanDibayar = ModalCicilan::where('status', 'sudah_bayar')->sum('total_bayar');

        $kas = ($totalLunas + $totalPembayaranPiutang + $totalPencairanModal) - ($totalBeliBarang + $totalCicilanDibayar);

        $nilaiStok = StokBatch::selectRaw('SUM(jumlah_tersisa * harga_modal) as nilai')
            ->value('nilai') ?? 0;

        $totalAset = $kas + $saldoPiutang + $nilaiStok;
        $ekuitas   = $totalAset - $sisaHutangModal;

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
            'ekuitas'                => $ekuitas,
        ];

        return view('laporan.index', compact(
            'ringkasan',
            'saldo',
            'dari',
            'sampai'
        ));
    }

    public function export(Request $request)
    {
        abort(501, 'Export belum diimplementasikan. Silakan integrasikan Maatwebsite\\Excel.');
    }
}
