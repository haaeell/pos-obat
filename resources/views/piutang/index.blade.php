@extends('layouts.app')

@section('title', 'Kelola Piutang')

@section('content')
    <div class="mx-auto bg-white rounded-xl">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-5">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Kelola Piutang</h1>

                <nav class="text-sm text-slate-500 mt-1">
                    <ol class="flex items-center gap-2">
                        <li>
                            <a href="{{ route('home') }}" class="hover:text-emerald-600">
                                Dashboard
                            </a>
                        </li>
                        <li>/</li>
                        <li class="text-slate-700 font-medium">
                            Piutang
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="flex gap-3">
                <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 min-w-[180px]">
                    <div class="text-xs uppercase text-amber-600 font-semibold">
                        Total Piutang
                    </div>

                    <div class="text-xl font-bold text-amber-700 mt-1">
                        Rp {{ number_format($totalPiutang ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 min-w-[180px]">
                    <div class="text-xs uppercase text-emerald-600 font-semibold">
                        Sudah Dibayar
                    </div>

                    <div class="text-xl font-bold text-emerald-700 mt-1">
                        Rp {{ number_format($totalDibayar ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <i class="fa-solid fa-circle-xmark mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- FILTER --}}
        <div class="bg-white border rounded-2xl shadow-sm p-4 mb-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">
                        Status
                    </label>

                    <select name="status"
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar" @selected(request('status') == 'belum_bayar')>
                            Belum Bayar
                        </option>
                        <option value="sebagian" @selected(request('status') == 'sebagian')>
                            Sebagian
                        </option>
                        <option value="lunas" @selected(request('status') == 'lunas')>
                            Lunas
                        </option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">
                        Dari Tanggal
                    </label>

                    <input type="date" name="dari" value="{{ request('dari') }}"
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">
                        Sampai
                    </label>

                    <input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                </div>

                <div class="md:col-span-4 flex justify-end gap-2">
                    <a href="{{ route('piutang.index') }}" class="px-4 py-2 rounded-xl border text-sm hover:bg-slate-50">
                        Reset
                    </a>

                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow border overflow-x-auto">
            <table id="datatable" class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">No</th>
                        <th class="px-4 py-3 text-left">Nomor</th>
                        <th class="px-4 py-3 text-left">Pelanggan</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right">Dibayar</th>
                        <th class="px-4 py-3 text-right">Sisa</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($piutang as $i => $item)
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-500">
                                {{ $i + 1 }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-700">
                                    {{ $item->nomor }}
                                </div>

                                <div class="text-xs text-slate-400">
                                    {{ $item->transaksi->nomor }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-700">
                                    {{ $item->pelanggan->nama }}
                                </div>

                                <div class="text-xs text-slate-400">
                                    {{ $item->pelanggan->telepon ?? '-' }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-slate-600">
                                {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->translatedFormat('d M Y') }}
                            </td>

                            <td class="px-4 py-3 text-right font-semibold text-slate-700">
                                Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right text-emerald-600 font-semibold">
                                Rp {{ number_format($item->sudah_dibayar, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right text-red-500 font-bold">
                                Rp {{ number_format($item->sisa_tagihan, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($item->status == 'lunas')
                                    <span class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                                        Lunas
                                    </span>
                                @elseif($item->status == 'sebagian')
                                    <span class="px-3 py-1 text-xs rounded-full bg-amber-100 text-amber-700 font-semibold">
                                        Sebagian
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">

                                    <button onclick='openDetailModal(@json($item))'
                                        class="px-3 py-1 bg-slate-200 text-slate-700 rounded hover:bg-slate-300 transition">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>

                                    @if ($item->status != 'lunas')
                                        <button onclick='openBayarModal(@json($item))'
                                            class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">
                                            <i class="fa-solid fa-money-bill-wave text-xs"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-slate-500">
                                Belum ada data piutang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL BAYAR --}}
    <div id="bayarModal" class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl">

            <div class="flex items-center gap-3 px-6 py-4 border-b">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>

                <div>
                    <h2 class="font-bold text-slate-800">
                        Pembayaran Piutang
                    </h2>

                    <p class="text-sm text-slate-500">
                        Input pembayaran pelanggan
                    </p>
                </div>
            </div>

            <form id="bayarForm" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">
                        Nomor Piutang
                    </label>

                    <input type="text" id="bayarNomor" readonly
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border-slate-300 bg-slate-100 text-sm">
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">
                        Sisa Tagihan
                    </label>

                    <input type="text" id="bayarSisa" readonly
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border-slate-300 bg-slate-100 text-sm font-bold text-red-600">
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">
                        Jumlah Bayar
                    </label>

                    <input type="number" name="jumlah" min="1" required
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-xs font-bold uppercase text-slate-500">
                            Tanggal
                        </label>

                        <input type="date" name="tanggal" value="{{ now()->toDateString() }}" required
                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase text-slate-500">
                            Metode Bayar
                        </label>

                        <select name="metode_bayar"
                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">
                        Catatan
                    </label>

                    <textarea name="catatan" rows="2"
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none resize-none"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeBayarModal()"
                        class="px-4 py-2 rounded-xl border text-sm hover:bg-slate-50">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">
                        Detail Piutang
                    </h2>

                    <p class="text-sm text-slate-500">
                        Informasi piutang & riwayat pembayaran
                    </p>
                </div>

                <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="p-6 space-y-6">

                {{-- INFO --}}
                <div class="grid grid-cols-2 gap-4 text-sm">

                    <div>
                        <div class="text-slate-500 mb-1">
                            Nomor Piutang
                        </div>

                        <div id="detailNomor" class="font-semibold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 mb-1">
                            Nomor Transaksi
                        </div>

                        <div id="detailTransaksi" class="font-semibold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 mb-1">
                            Pelanggan
                        </div>

                        <div id="detailPelanggan" class="font-semibold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 mb-1">
                            Status
                        </div>

                        <div id="detailStatus"></div>
                    </div>

                    <div>
                        <div class="text-slate-500 mb-1">
                            Total Tagihan
                        </div>

                        <div id="detailTotal" class="font-bold text-slate-700">
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 mb-1">
                            Sudah Dibayar
                        </div>

                        <div id="detailDibayar" class="font-bold text-emerald-600">
                        </div>
                    </div>

                    <div class="col-span-2">
                        <div class="text-slate-500 mb-1">
                            Sisa Tagihan
                        </div>

                        <div id="detailSisa" class="font-bold text-red-500 text-lg">
                        </div>
                    </div>

                </div>

                {{-- RIWAYAT --}}
                <div>

                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-slate-800">
                            Riwayat Pembayaran
                        </h3>

                        <span id="detailJumlahPembayaran"
                            class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                            0 Pembayaran
                        </span>
                    </div>

                    <div class="border rounded-2xl overflow-hidden">

                        <table class="w-full text-sm">
                            <thead class="bg-slate-100 text-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left">
                                        Tanggal
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Metode
                                    </th>

                                    <th class="px-4 py-3 text-right">
                                        Jumlah
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Kasir
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="detailPembayaranBody">

                            </tbody>
                        </table>

                    </div>

                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {

                $('#datatable').DataTable({
                    language: {
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        paginate: {
                            previous: 'Sebelumnya',
                            next: 'Selanjutnya'
                        },
                        zeroRecords: 'Data tidak ditemukan',
                    }
                })

                window.openBayarModal = function (data) {

                    $('#bayarForm').attr('action', `/piutang/${data.id}/bayar`)

                    $('#bayarNomor').val(data.nomor)

                    $('#bayarSisa').val(
                        'Rp ' + Number(data.sisa_tagihan).toLocaleString('id-ID')
                    )

                    $('#bayarModal').removeClass('hidden')
                }

                window.closeBayarModal = function () {
                    $('#bayarModal').addClass('hidden')
                }

                window.openDetailModal = function (data) {

                    $('#detailNomor').text(data.nomor)

                    $('#detailTransaksi').text(data.transaksi?.nomor ?? '-')

                    $('#detailPelanggan').text(data.pelanggan?.nama ?? '-')

                    $('#detailTotal').text(
                        'Rp ' + Number(data.total_tagihan).toLocaleString('id-ID')
                    )

                    $('#detailDibayar').text(
                        'Rp ' + Number(data.sudah_dibayar).toLocaleString('id-ID')
                    )

                    $('#detailSisa').text(
                        'Rp ' + Number(data.sisa_tagihan).toLocaleString('id-ID')
                    )

                    let statusBadge = ''

                    if (data.status === 'lunas') {
                        statusBadge =
                            '<span class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700 font-semibold">Lunas</span>'
                    } else if (data.status === 'sebagian') {
                        statusBadge =
                            '<span class="px-3 py-1 text-xs rounded-full bg-amber-100 text-amber-700 font-semibold">Sebagian</span>'
                    } else {
                        statusBadge =
                            '<span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">Belum Bayar</span>'
                    }

                    $('#detailStatus').html(statusBadge)

                    let rows = ''
                    if (data.pembayaran?.length > 0) {

                        data.pembayaran.forEach(function (item) {

                            rows += `
                                            <tr class="border-t">
                                                <td class="px-4 py-3">
                                                    ${new Date(item.tanggal).toLocaleDateString('id-ID')}
                                                </td>

                                                <td class="px-4 py-3 capitalize">
                                                    ${item.metode_bayar}
                                                </td>

                                                <td class="px-4 py-3 text-right font-semibold text-emerald-600">
                                                    Rp ${Number(item.jumlah).toLocaleString('id-ID')}
                                                </td>

                                                <td class="px-4 py-3">
                                                    ${item.user?.nama ?? '-'}
                                                </td>
                                            </tr>
                                            `
                        })

                    } else {

                        rows = `
                                                <tr>
                                                    <td colspan="4"
                                                        class="px-4 py-6 text-center text-slate-500">
                                                        Belum ada pembayaran.
                                                    </td>
                                                </tr>
                                            `
                    }

                    $('#detailPembayaranBody').html(rows)

                    $('#detailJumlahPembayaran').text(
                        `${data.pembayaran?.length ?? 0} Pembayaran`
                    )

                    $('#detailModal').removeClass('hidden')
                }

                window.closeDetailModal = function () {
                    $('#detailModal').addClass('hidden')
                }

                $('#bayarModal').on('click', function (e) {
                    if ($(e.target).is($('#bayarModal'))) {
                        closeBayarModal()
                    }
                })

                $('#detailModal').on('click', function (e) {
                    if ($(e.target).is($('#detailModal'))) {
                        closeDetailModal()
                    }
                })

            })
        </script>
    @endpush
@endsection