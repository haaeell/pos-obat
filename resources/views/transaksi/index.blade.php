@extends('layouts.app')

@section('title', 'Daftar Penjualan')
@section('page-title', 'Penjualan')

@push('styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 18px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 4px 0 0 4px;
        }

        .stat-card.green::before {
            background: #059669;
        }

        .stat-card.blue::before {
            background: #2563eb;
        }

        .stat-card.amber::before {
            background: #d97706;
        }

        .stat-card.red::before {
            background: #dc2626;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 4px;
        }

        .stat-val {
            font-size: 22px;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.1;
        }

        .stat-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 3px;
        }

        .filter-card {
            background: #f0fdf4;
            border: 1px solid #d1fae5;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 16px;
        }

        .filter-card select,
        .filter-card input[type=date] {
            border: 1.5px solid #d1fae5;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 13px;
            color: #374151;
            background: #fff;
            outline: none;
        }

        .filter-card select:focus,
        .filter-card input:focus {
            border-color: #10b981;
        }

        .table-wrap {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
        }

        #datatable {
            width: 100%;
            font-size: 13px;
            border-collapse: collapse;
        }

        #datatable thead th {
            background: #f8fafc;
            padding: 10px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        #datatable tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background .12s;
        }

        #datatable tbody tr:hover {
            background: #f8fafc;
        }

        #datatable td {
            padding: 11px 14px;
            vertical-align: middle;
        }

        #datatable tbody tr:last-child {
            border-bottom: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-lunas {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-sebagian {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-belum {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-batal {
            background: #f1f5f9;
            color: #64748b;
        }

        .act-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .12s;
        }

        .act-btn.detail {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .act-btn.detail:hover {
            background: #dbeafe;
        }

        .act-btn.struk {
            background: #ecfdf5;
            color: #065f46;
        }

        .act-btn.struk:hover {
            background: #d1fae5;
        }

        .act-btn.batal {
            background: #fff1f2;
            color: #be123c;
        }

        .act-btn.batal:hover {
            background: #ffe4e6;
        }

        /* Modal Detail */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 50;
            align-items: center;
            justify-content: center;
        }

        .modal-backdrop.open {
            display: flex;
        }

        .modal-box {
            background: #fff;
            border-radius: 16px;
            width: 540px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 5px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .modal-row:last-child {
            border: none;
        }

        .modal-label {
            color: #64748b;
        }

        .modal-val {
            font-weight: 500;
            color: #0f172a;
            text-align: right;
        }

        .detail-table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .detail-table th {
            background: #f8fafc;
            padding: 7px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .detail-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-table tr:last-child td {
            border: none;
        }

        /* Batal form */
        .batal-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            resize: none;
            outline: none;
        }

        .batal-textarea:focus {
            border-color: #dc2626;
        }
    </style>
@endpush

@section('content')
    <div class="mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Daftar Penjualan</h1>
                <nav class="text-sm text-slate-400 mt-1 flex items-center gap-2">
                    <a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Penjualan</span>
                </nav>
            </div>
            <a href="{{ route('transactions.pos') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition text-sm font-semibold">
                <i class="fa-solid fa-cash-register"></i> Buat Transaksi (POS)
            </a>
        </div>

        {{-- STATISTIK --}}
        <div class="stats-grid">
            <div class="stat-card green">
                <div class="stat-icon" style="background:#d1fae5;">
                    <i class="fa-solid fa-receipt" style="color:#059669;"></i>
                </div>
                <div class="stat-label">Transaksi Hari Ini</div>
                <div class="stat-val">{{ $statsHariIni->total_trx ?? 0 }}</div>
                <div class="stat-sub">transaksi aktif</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon" style="background:#dbeafe;">
                    <i class="fa-solid fa-money-bill-wave" style="color:#2563eb;"></i>
                </div>
                <div class="stat-label">Omset Hari Ini</div>
                <div class="stat-val" style="font-size:15px;">
                    Rp {{ number_format($statsHariIni->omset ?? 0, 0, ',', '.') }}
                </div>
                <div class="stat-sub">total penjualan</div>
            </div>
            <div class="stat-card amber">
                <div class="stat-icon" style="background:#fef3c7;">
                    <i class="fa-solid fa-file-invoice-dollar" style="color:#d97706;"></i>
                </div>
                <div class="stat-label">Total Piutang</div>
                <div class="stat-val" style="font-size:15px;">
                    Rp {{ number_format($piutangTotal ?? 0, 0, ',', '.') }}
                </div>
                <div class="stat-sub">belum & sebagian</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon" style="background:#fee2e2;">
                    <i class="fa-solid fa-chart-line" style="color:#dc2626;"></i>
                </div>
                <div class="stat-label">Laba Kotor Hari Ini</div>
                <div class="stat-val" style="font-size:15px;">
                    Rp {{ number_format(($statsHariIni->omset ?? 0) - ($statsHariIni->hpp ?? 0), 0, ',', '.') }}
                </div>
                <div class="stat-sub">omset − HPP</div>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="filter-card flex flex-wrap items-center gap-3">
            <i class="fa-solid fa-filter text-emerald-600 text-sm"></i>
            <span class="text-sm font-semibold text-emerald-700">Filter:</span>

            <input type="date" id="filterDari" placeholder="Dari">
            <span class="text-slate-400 text-sm">–</span>
            <input type="date" id="filterSampai" placeholder="Sampai">

            <select id="filterStatus">
                <option value="">Semua Status Bayar</option>
                <option value="Lunas">Lunas</option>
                <option value="Sebagian">Sebagian</option>
                <option value="Belum Bayar">Belum Bayar</option>
            </select>

            <select id="filterStatusTrx">
                <option value="">Semua Transaksi</option>
                <option value="aktif">Aktif</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>

            <button onclick="resetFilter()"
                class="px-3 py-1.5 text-xs font-semibold text-slate-500 hover:text-red-500 border border-slate-200 rounded-lg hover:border-red-300 bg-white transition">
                <i class="fa-solid fa-xmark mr-1"></i> Reset
            </button>
        </div>

        {{-- TABEL --}}
        <div class="table-wrap">
            <table id="datatable">
                <thead>
                    <tr>
                        <th class="w-10">No</th>
                        <th>Nomor</th>
                        <th>Pelanggan</th>
                        <th>Dicatat Oleh</th>
                        <th>Tanggal</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Status Bayar</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $i => $trx)
                        <tr>
                            <td class="text-slate-400 text-xs">{{ $transaksi->firstItem() + $i }}</td>
                            <td>
                                <span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded">{{ $trx->nomor }}</span>
                            </td>
                            <td class="font-medium text-slate-800">
                                {{ $trx->pelanggan->nama ?? 'Umum' }}
                            </td>
                            <td class="text-slate-500 text-xs">{{ $trx->user->nama ?? '-' }}</td>
                            <td class="text-slate-500 text-xs">
                                {{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y') }}
                            </td>
                            <td class="text-right font-semibold text-slate-800">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </td>
                            <td class="text-center" data-status="{{ ucfirst(str_replace('_', ' ', $trx->status_bayar)) }}">
                                @if ($trx->status_bayar === 'lunas')
                                    <span class="badge badge-lunas"><i class="fa-solid fa-check text-[9px]"></i> Lunas</span>
                                @elseif ($trx->status_bayar === 'sebagian')
                                    <span class="badge badge-sebagian"><i class="fa-solid fa-clock text-[9px]"></i> Sebagian</span>
                                @else
                                    <span class="badge badge-belum"><i class="fa-solid fa-xmark text-[9px]"></i> Belum Bayar</span>
                                @endif
                            </td>
                            <td class="text-center" data-status-trx="{{ $trx->status }}">
                                @if ($trx->status === 'aktif')
                                    <span class="badge badge-lunas">Aktif</span>
                                @else
                                    <span class="badge badge-batal"><i class="fa-solid fa-ban text-[9px]"></i> Dibatalkan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button class="act-btn detail" onclick="lihatDetail({{ $trx->id }})" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <a href="{{ route('transactions.struk', $trx->id) }}" target="_blank" class="act-btn struk"
                                        title="Cetak Struk">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    @if ($trx->status === 'aktif')
                                        <button class="act-btn batal" onclick="batalkanTrx({{ $trx->id }}, '{{ $trx->nomor }}')"
                                            title="Batalkan">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $transaksi->links() }}
        </div>
    </div>

    {{-- ═══════════ MODAL DETAIL ═══════════ --}}
    <div class="modal-backdrop" id="detailModal">
        <div class="modal-box">
            <div class="modal-head">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-800" id="detailNomor">Detail Transaksi</div>
                        <div class="text-xs text-slate-400" id="detailTanggal"></div>
                    </div>
                </div>
                <button onclick="document.getElementById('detailModal').classList.remove('open')"
                    class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body" id="detailBody">
                <div class="text-center py-10 text-slate-400">
                    <i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i>
                    <div>Memuat data...</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ MODAL BATALKAN ═══════════ --}}
    <div class="modal-backdrop" id="batalModal">
        <div class="modal-box" style="width:400px;">
            <div class="modal-head">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-800">Batalkan Transaksi</div>
                        <div class="text-xs text-slate-400" id="batalNomor"></div>
                    </div>
                </div>
                <button onclick="document.getElementById('batalModal').classList.remove('open')"
                    class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="batalForm" method="POST" class="modal-body">
                @csrf
                @method('PATCH')
                <p class="text-sm text-slate-600 mb-3">Stok produk akan dikembalikan secara otomatis. Tindakan ini tidak
                    dapat diurungkan.</p>
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                    Alasan Pembatalan <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan_batal" class="batal-textarea" rows="3" required
                    placeholder="Tuliskan alasan pembatalan..."></textarea>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="document.getElementById('batalModal').classList.remove('open')"
                        class="px-4 py-2 text-sm border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold">
                        <i class="fa-solid fa-ban mr-1"></i> Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                const table = $('#datatable').DataTable({
                    language: {
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        paginate: { previous: 'Sebelumnya', next: 'Selanjutnya' },
                        zeroRecords: 'Data tidak ditemukan',
                    },
                    order: [[4, 'desc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 8] }
                    ],
                    pageLength: 25,
                })

                // Filter status bayar (kolom 6)
                $.fn.dataTable.ext.search.push(function (settings, data) {
                    const f = $('#filterStatus').val()
                    if (!f) return true
                    const cell = data[6] ? data[6].replace(/<[^>]+>/g, '').trim() : ''
                    return cell.toLowerCase().includes(f.toLowerCase())
                })

                // Filter status transaksi (kolom 7)
                $.fn.dataTable.ext.search.push(function (settings, data) {
                    const f = $('#filterStatusTrx').val()
                    if (!f) return true
                    const cell = data[7] ? data[7].replace(/<[^>]+>/g, '').trim() : ''
                    return cell.toLowerCase().includes(f.toLowerCase())
                })

                $('#filterStatus, #filterStatusTrx').on('change', () => table.draw())

                // Filter tanggal
                $('#filterDari, #filterSampai').on('change', function () {
                    $.fn.dataTable.ext.search.push(function (settings, data) {
                        const dari = $('#filterDari').val()
                        const sampai = $('#filterSampai').val()
                        const tglText = data[4] ? data[4].trim() : ''
                        if (!dari && !sampai) return true
                        // Hanya sebagai search tambahan; untuk produksi gunakan server-side
                        return true
                    })
                    table.draw()
                })

                window.resetFilter = function () {
                    $('#filterDari, #filterSampai, #filterStatus, #filterStatusTrx').val('')
                    table.search('').draw()
                }
            })

            // ── Detail Modal ──────────────────────────────────────────────
            function lihatDetail(id) {
                document.getElementById('detailModal').classList.add('open')
                document.getElementById('detailBody').innerHTML = `
                                <div class="text-center py-10 text-slate-400">
                                    <i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i><div>Memuat data...</div>
                                </div>`

                fetch(`/transactions/${id}`)
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('detailNomor').textContent = data.nomor
                        document.getElementById('detailTanggal').textContent = data.tanggal

                        const statusMap = { lunas: 'Lunas', sebagian: 'Sebagian', belum_bayar: 'Belum Bayar' }

                        const rows = data.detail.map(d => `
                                        <tr>
                                            <td>${d.produk?.nama ?? '-'}</td>
                                            <td class="text-right">${d.jumlah}</td>
                                            <td class="text-right">Rp ${Number(d.harga_jual).toLocaleString('id-ID')}</td>
                                            <td class="text-right">${d.diskon_item > 0 ? 'Rp ' + Number(d.diskon_item).toLocaleString('id-ID') : '-'}</td>
                                            <td class="text-right font-semibold">Rp ${Number(d.subtotal).toLocaleString('id-ID')}</td>
                                        </tr>
                                    `).join('')

                        document.getElementById('detailBody').innerHTML = `
                                        <div class="space-y-1 mb-4">
                                            <div class="modal-row"><span class="modal-label">Pelanggan</span><span class="modal-val">${data.pelanggan?.nama ?? 'Umum'}</span></div>
                                            <div class="modal-row"><span class="modal-label">Kasir</span><span class="modal-val">${data.user?.nama ?? '-'}</span></div>
                                            <div class="modal-row"><span class="modal-label">Status Bayar</span><span class="modal-val">${statusMap[data.status_bayar] ?? data.status_bayar}</span></div>
                                        </div>
                                        <table class="detail-table">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th><th class="text-right">Qty</th>
                                                    <th class="text-right">Harga</th><th class="text-right">Diskon</th>
                                                    <th class="text-right">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>${rows}</tbody>
                                        </table>
                                        <div class="mt-4 space-y-1 pt-3 border-t border-slate-100">
                                            <div class="modal-row"><span class="modal-label">Subtotal</span><span class="modal-val">Rp ${Number(data.subtotal).toLocaleString('id-ID')}</span></div>
                                            ${data.diskon_nominal > 0 ? `<div class="modal-row"><span class="modal-label">Diskon</span><span class="modal-val text-red-500">- Rp ${Number(data.diskon_nominal).toLocaleString('id-ID')}</span></div>` : ''}
                                            <div class="modal-row" style="font-size:15px;font-weight:600;"><span>Total</span><span class="text-emerald-700">Rp ${Number(data.total).toLocaleString('id-ID')}</span></div>
                                            <div class="modal-row"><span class="modal-label">Bayar</span><span class="modal-val">Rp ${Number(data.jumlah_bayar).toLocaleString('id-ID')}</span></div>
                                            ${data.kembalian > 0 ? `<div class="modal-row"><span class="modal-label">Kembalian</span><span class="modal-val text-emerald-600">Rp ${Number(data.kembalian).toLocaleString('id-ID')}</span></div>` : ''}
                                            ${data.sisa_tagihan > 0 ? `<div class="modal-row"><span class="modal-label">Sisa Tagihan</span><span class="modal-val text-amber-600">Rp ${Number(data.sisa_tagihan).toLocaleString('id-ID')}</span></div>` : ''}
                                        </div>
                                        <div class="flex justify-end mt-4">
                                            <a href="/transactions/${data.id}/struk" target="_blank"
                                                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700">
                                                <i class="fa-solid fa-print"></i> Cetak Struk
                                            </a>
                                        </div>
                                    `
                    })
            }

            // ── Batalkan ─────────────────────────────────────────────────
            function batalkanTrx(id, nomor) {
                document.getElementById('batalNomor').textContent = nomor
                document.getElementById('batalForm').action = `/transactions/${id}/batal`
                document.getElementById('batalModal').classList.add('open')
            }

            // Tutup modal saat klik backdrop
            ['detailModal', 'batalModal'].forEach(id => {
                document.getElementById(id).addEventListener('click', function (e) {
                    if (e.target === this) this.classList.remove('open')
                })
            })
        </script>
    @endpush
@endsection