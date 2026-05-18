@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="mx-auto space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Laporan Keuangan</h1>
                <nav class="text-sm text-slate-500 mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-slate-700 font-medium">Laporan</li>
                    </ol>
                </nav>
            </div>
            <div class="flex gap-2 no-print">
                <a href="{{ route('laporan.cetak') }}?{{ http_build_query(request()->query()) }}"
                    class="px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-800 transition text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                </a>
                <a href="{{ route('laporan.export') }}?{{ http_build_query(request()->query()) }}"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow border px-5 py-4 no-print">
            <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[140px]">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari', now()->startOfMonth()->toDateString()) }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1">Sampai
                        Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai', now()->toDateString()) }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-semibold">
                        <i class="fa-solid fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('laporan.index') }}"
                        class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition text-sm font-semibold">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ── ROW 1: 5 kartu ringkasan utama ── --}}


        {{-- Kas --}}
        <div class="bg-white rounded-xl shadow border p-5 flex items-start gap-4">
            <div
                class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-lg">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Saldo Kas</p>
                <p class="text-xl font-bold text-slate-800 mt-0.5 truncate">Rp
                    {{ number_format($saldo['kas'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 mt-0.5">Tunai + pencairan − pengeluaran</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            {{-- Penjualan --}}
            <div class="bg-white rounded-xl shadow border p-5 flex items-start gap-4">
                <div
                    class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-lg">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Penjualan Periode</p>
                    <p class="text-xl font-bold text-slate-800 mt-0.5 truncate">Rp
                        {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $ringkasan['jumlah_transaksi'] }} transaksi</p>
                </div>
            </div>

            {{-- Piutang pelanggan --}}
            <div class="bg-white rounded-xl shadow border p-5 flex items-start gap-4">
                <div
                    class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 text-lg">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Piutang Pelanggan</p>
                    <p class="text-xl font-bold text-slate-800 mt-0.5 truncate">Rp
                        {{ number_format($saldo['piutang'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $ringkasan['jumlah_piutang'] }} pelanggan</p>
                </div>
            </div>

            {{-- Hutang modal --}}
            <div class="bg-white rounded-xl shadow border p-5 flex items-start gap-4">
                <div
                    class="w-11 h-11 rounded-xl bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0 text-lg">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Hutang Modal</p>
                    <p class="text-xl font-bold text-slate-800 mt-0.5 truncate">Rp
                        {{ number_format($saldo['hutang_modal'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $ringkasan['jumlah_modal_aktif'] }} pinjaman aktif</p>
                </div>
            </div>

            {{-- Hutang supplier — BARU --}}
            <div class="bg-white rounded-xl shadow border p-5 flex items-start gap-4
                            {{ $saldo['hutang_supplier_jt'] > 0 ? 'border-l-4 border-l-red-400' : '' }}">
                <div
                    class="w-11 h-11 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0 text-lg">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Hutang Supplier</p>
                    <p class="text-xl font-bold text-slate-800 mt-0.5 truncate">Rp
                        {{ number_format($saldo['hutang_supplier'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs mt-0.5">
                        <span class="text-slate-400">{{ $saldo['jumlah_hutang_supplier'] }} transaksi</span>
                        @if($saldo['hutang_supplier_jt'] > 0)
                            <span class="ml-1 text-red-500 font-semibold">
                                · <i class="fa-solid fa-triangle-exclamation"></i> {{ $saldo['hutang_supplier_jt'] }} jatuh
                                tempo
                            </span>
                        @endif
                    </p>
                </div>
            </div>

        </div>

        {{-- ── ROW 2: HPP, laba, margin ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow border p-5 flex items-start gap-4">
                <div
                    class="w-11 h-11 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center flex-shrink-0 text-lg">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total HPP (FIFO)</p>
                    <p class="text-xl font-bold text-slate-800 mt-0.5 truncate">Rp
                        {{ number_format($ringkasan['total_hpp'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">Harga pokok penjualan</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow border p-5 flex items-start gap-4">
                <div
                    class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-lg">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Laba Kotor</p>
                    <p
                        class="text-xl font-bold {{ $ringkasan['laba_kotor'] >= 0 ? 'text-emerald-600' : 'text-red-500' }} mt-0.5 truncate">
                        Rp {{ number_format($ringkasan['laba_kotor'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">Penjualan − HPP</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow border p-5 flex items-start gap-4">
                <div
                    class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 text-lg">
                    <i class="fa-solid fa-percent"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Margin Laba</p>
                    <p class="text-xl font-bold {{ $ringkasan['margin'] >= 0 ? 'text-slate-800' : 'text-red-500' }} mt-0.5">
                        {{ number_format($ringkasan['margin'], 1) }}%
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">Laba / Penjualan × 100</p>
                </div>
            </div>
        </div>

        {{-- ── REKAP POSISI KEUANGAN ── --}}
        <div class="bg-white rounded-xl shadow border overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b bg-slate-50">
                <i class="fa-solid fa-scale-balanced text-emerald-600"></i>
                <h2 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Rekap Posisi Keuangan</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x">

                {{-- Kolom Aset --}}
                <div class="p-5">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Aset</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Kas</span>
                            <span class="font-semibold text-slate-800">Rp
                                {{ number_format($saldo['kas'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Piutang Pelanggan</span>
                            <span class="font-semibold text-slate-800">Rp
                                {{ number_format($saldo['piutang'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Nilai Stok Barang</span>
                            <span class="font-semibold text-slate-800">Rp
                                {{ number_format($saldo['nilai_stok'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t pt-2 mt-2">
                            <span class="font-bold text-slate-700">Total Aset</span>
                            <span class="font-bold text-emerald-600">
                                Rp
                                {{ number_format($saldo['kas'] + $saldo['piutang'] + $saldo['nilai_stok'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kewajiban & Ekuitas --}}
                <div class="p-5">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Kewajiban & Ekuitas</p>
                    <div class="space-y-2 text-sm">

                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Sisa Hutang Modal</span>
                            <span class="font-semibold text-red-500">Rp
                                {{ number_format($saldo['hutang_modal'], 0, ',', '.') }}</span>
                        </div>

                        {{-- Hutang supplier — BARU --}}
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600 flex items-center gap-1.5">
                                Hutang Supplier
                                @if($saldo['jumlah_hutang_supplier'] > 0)
                                    <span
                                        class="text-[10px] font-semibold bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded-full">
                                        {{ $saldo['jumlah_hutang_supplier'] }} transaksi
                                    </span>
                                @endif
                                @if($saldo['hutang_supplier_jt'] > 0)
                                    <span class="text-[10px] font-semibold bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full">
                                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $saldo['hutang_supplier_jt'] }}
                                        jatuh tempo
                                    </span>
                                @endif
                            </span>
                            <span class="font-semibold text-orange-500">Rp
                                {{ number_format($saldo['hutang_supplier'], 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center text-xs text-slate-400 pl-1">
                            <span>Total Pencairan Modal</span>
                            <span>Rp {{ number_format($saldo['total_pencairan_modal'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-slate-400 pl-1">
                            <span>Total Cicilan Terbayar</span>
                            <span>Rp {{ number_format($saldo['total_cicilan_terbayar'], 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center border-t pt-2 mt-2">
                            <span class="font-bold text-slate-700">Total Kewajiban</span>
                            <span class="font-bold text-red-500">
                                Rp {{ number_format($saldo['hutang_modal'] + $saldo['hutang_supplier'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-slate-700">Ekuitas Bersih</span>
                            <span class="font-bold {{ $saldo['ekuitas'] >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                Rp {{ number_format($saldo['ekuitas'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 12px;
            }

            .shadow {
                box-shadow: none !important;
            }
        }
    </style>
@endsection