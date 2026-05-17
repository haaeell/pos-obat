<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Piutang;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Transaksi;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalProduk = Produk::count();

        $totalSupplier = Supplier::count();

        $totalPelanggan = Pelanggan::count();

        $totalPiutang = Piutang::sum('sisa_tagihan');

        $transaksiHariIni = Transaksi::whereDate('tanggal', today())->count();

        $penjualanHariIni = Transaksi::whereDate('tanggal', today())
            ->where('status', 'aktif')
            ->sum('total');

        $produkStokMenipis = Produk::whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->count();

        $piutangBelumLunas = Piutang::whereIn('status', ['belum_bayar', 'sebagian'])
            ->count();

        $transaksiTerbaru = Transaksi::with('pelanggan')
            ->latest()
            ->take(5)
            ->get();

        return view('home', compact(
            'totalProduk',
            'totalSupplier',
            'totalPelanggan',
            'totalPiutang',
            'transaksiHariIni',
            'penjualanHariIni',
            'produkStokMenipis',
            'piutangBelumLunas',
            'transaksiTerbaru'
        ));
    }
}
