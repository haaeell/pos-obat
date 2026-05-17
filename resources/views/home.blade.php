@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Dashboard
                </h1>

                <p class="text-slate-500 mt-1">
                    Ringkasan aktivitas toko hari ini.
                </p>
            </div>

            <div class="bg-white border rounded-2xl px-5 py-3 shadow-sm">
                <div class="text-xs uppercase font-semibold text-slate-500">
                    Hari Ini
                </div>

                <div class="text-lg font-bold text-slate-800 mt-1">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>

        {{-- STATISTIC --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

            <div class="bg-white border rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-500">
                            Total Produk
                        </div>

                        <div class="text-3xl font-bold text-slate-800 mt-2">
                            {{ number_format($totalProduk) }}
                        </div>
                    </div>

                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-box"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-500">
                            Total Supplier
                        </div>

                        <div class="text-3xl font-bold text-slate-800 mt-2">
                            {{ number_format($totalSupplier) }}
                        </div>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-500">
                            Total Pelanggan
                        </div>

                        <div class="text-3xl font-bold text-slate-800 mt-2">
                            {{ number_format($totalPelanggan) }}
                        </div>
                    </div>

                    <div
                        class="w-14 h-14 rounded-2xl bg-violet-100 text-violet-600 flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-500">
                            Total Piutang
                        </div>

                        <div class="text-2xl font-bold text-red-500 mt-2">
                            Rp {{ number_format($totalPiutang, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- SUMMARY --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <div class="bg-white border rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-500">
                            Penjualan Hari Ini
                        </div>

                        <div class="text-3xl font-bold text-emerald-600 mt-2">
                            Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-500">
                            Transaksi Hari Ini
                        </div>

                        <div class="text-3xl font-bold text-slate-800 mt-2">
                            {{ number_format($transaksiHariIni) }}
                        </div>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-500">
                            Stok Menipis
                        </div>

                        <div class="text-3xl font-bold text-amber-500 mt-2">
                            {{ number_format($produkStokMenipis) }}
                        </div>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-slate-800">
                            Transaksi Terbaru
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            5 transaksi terakhir
                        </p>
                    </div>

                    <div class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-xs font-semibold">
                        {{ $piutangBelumLunas }} Piutang Belum Lunas
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                Nomor
                            </th>

                            <th class="px-4 py-3 text-left">
                                Pelanggan
                            </th>

                            <th class="px-4 py-3 text-left">
                                Tanggal
                            </th>

                            <th class="px-4 py-3 text-right">
                                Total
                            </th>

                            <th class="px-4 py-3 text-center">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($transaksiTerbaru as $item)
                            <tr class="border-t hover:bg-slate-50 transition">

                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-700">
                                        {{ $item->nomor }}
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->pelanggan->nama ?? 'Umum' }}
                                </td>

                                <td class="px-4 py-3 text-slate-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </td>

                                <td class="px-4 py-3 text-right font-bold text-slate-700">
                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-center">

                                    @if ($item->status_bayar == 'lunas')
                                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                            Lunas
                                        </span>
                                    @elseif($item->status_bayar == 'sebagian')
                                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                            Sebagian
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                            Belum Bayar
                                        </span>
                                    @endif

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-500">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection

@push('scripts')

@endpush