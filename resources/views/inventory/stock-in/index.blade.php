@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')

@push('styles')
    <style>
        #datatable td {
            white-space: nowrap;
        }

        /* ── Select2 custom ── */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border-radius: 10px !important;
            border: 1.5px solid #d1d5db !important;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 12px !important;
            font-size: 13px;
            color: #374151;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .12) !important;
            outline: none !important;
        }

        .select2-dropdown {
            border-radius: 12px !important;
            border: 1.5px solid #d1fae5 !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12) !important;
            overflow: hidden;
        }

        .select2-search--dropdown input {
            border-radius: 8px !important;
            border: 1.5px solid #d1d5db !important;
            font-size: 13px;
            padding: 6px 10px;
        }

        .select2-results__option {
            font-size: 13px;
            padding: 8px 12px;
        }

        .select2-results__option--highlighted {
            background: #ecfdf5 !important;
            color: #065f46 !important;
        }

        .produk-option-kode {
            font-size: 10px;
            font-family: monospace;
            background: #f1f5f9;
            color: #475569;
            padding: 1px 6px;
            border-radius: 4px;
            margin-right: 6px;
        }

        .produk-option-stok {
            font-size: 10px;
            color: #6b7280;
            margin-left: auto;
        }

        /* ── Input rupiah ── */
        .rupiah-wrap {
            position: relative;
        }

        .rupiah-wrap .rp-prefix {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            padding: 0 10px;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            background: #f8fafc;
            border-right: 1.5px solid #e2e8f0;
            border-radius: 10px 0 0 10px;
            pointer-events: none;
        }

        .rupiah-wrap input {
            padding-left: 40px !important;
        }

        .rupiah-display {
            font-size: 12px;
            color: #059669;
            font-weight: 600;
            margin-top: 3px;
            min-height: 16px;
        }

        /* ── Item row — compact table style ── */
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 6px;
        }

        .items-table thead th {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6b7280;
            padding: 0 8px 4px;
            background: transparent;
        }

        .items-table tbody tr {
            background: #f8fafc;
        }

        .items-table tbody tr td {
            padding: 8px 6px;
            border-top: 1.5px solid #e2e8f0;
            border-bottom: 1.5px solid #e2e8f0;
        }

        .items-table tbody tr td:first-child {
            border-left: 1.5px solid #e2e8f0;
            border-radius: 10px 0 0 10px;
            padding-left: 10px;
        }

        .items-table tbody tr td:last-child {
            border-right: 1.5px solid #e2e8f0;
            border-radius: 0 10px 10px 0;
            padding-right: 10px;
        }

        .items-table tbody tr:hover td {
            background: #f0fdf4;
            border-color: #a7f3d0;
        }

        .items-table input,
        .items-table select {
            width: 100%;
            padding: 7px 10px;
            font-size: 13px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            outline: none;
            transition: border .15s;
        }

        .items-table input:focus,
        .items-table select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, .1);
        }

        .section-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 6px 0 2px;
        }

        .section-divider span {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #059669;
            white-space: nowrap;
        }

        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #d1fae5;
        }

        .filter-bar select {
            border: 1.5px solid #d1fae5;
            border-radius: 8px;
            padding: 6px 32px 6px 10px;
            font-size: 13px;
            color: #374151;
            background: white;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .filter-bar select:focus {
            border-color: #10b981;
        }
    </style>
@endpush

@section('content')
    <div class="mx-auto bg-white rounded-xl">

        {{-- HEADER --}}
        <div class="flex flex-wrap justify-between items-start gap-3 mb-5">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Barang Masuk</h1>
                <nav class="text-sm text-slate-500 mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-slate-700 font-medium">Barang Masuk</li>
                    </ol>
                </nav>
            </div>
            <button onclick="openModal()"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center gap-2 text-sm font-semibold">
                <i class="fa-solid fa-plus"></i> Catat Barang Masuk
            </button>
        </div>

        {{-- FILTER --}}
        <div
            class="filter-bar flex flex-wrap items-center gap-3 mb-4 p-4 bg-emerald-50/60 rounded-xl border border-emerald-100">
            <div class="flex items-center gap-2 text-sm font-semibold text-emerald-700">
                <i class="fa-solid fa-filter"></i> Filter:
            </div>
            <select id="filterSupplier">
                <option value="">Semua Supplier</option>
                @foreach ($supplier as $sup)
                    <option value="{{ $sup->nama }}">{{ $sup->nama }}</option>
                @endforeach
            </select>
            <button onclick="resetFilter()"
                class="px-3 py-1.5 text-xs font-semibold text-slate-500 hover:text-red-500 border border-slate-200 rounded-lg hover:border-red-300 transition bg-white">
                <i class="fa-solid fa-xmark mr-1"></i> Reset
            </button>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow border overflow-x-auto px-4 py-5">
            <table id="datatable" class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">No</th>
                        <th class="px-4 py-3 text-left">Nomor</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Supplier</th>
                        <th class="px-4 py-3 text-left">No. Faktur</th>
                        <th class="px-4 py-3 text-center">Jml Item</th>
                        <th class="px-4 py-3 text-right">Total Nilai</th>
                        <th class="px-4 py-3 text-left">Dicatat Oleh</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangMasuk as $i => $item)
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-mono text-xs bg-emerald-50 text-emerald-700 px-2 py-1 rounded font-semibold">
                                    {{ $item->nomor }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->tanggal->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $item->supplier->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $item->nomor_faktur_supplier ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700">
                                    {{ $item->detail->count() }} produk
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-800">
                                Rp {{ number_format($item->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $item->user->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button onclick="lihatDetail({{ $item->id }})"
                                        class="px-3 py-1.5 bg-blue-500 text-white hover:bg-blue-600 rounded-lg transition text-xs font-semibold">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button onclick="deleteBarangMasuk({{ $item->id }}, '{{ $item->nomor }}')"
                                        class="px-3 py-1.5 bg-red-500 text-white hover:bg-red-600 rounded-lg transition text-xs font-semibold">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════ MODAL TAMBAH ═══════════════════ --}}
    <div id="stockInModal"
        class="fixed inset-0 hidden bg-black/40 modal-blur-overlay flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-5xl rounded-2xl shadow-2xl max-h-[94vh] flex flex-col">

            {{-- Header --}}
            <div
                class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0 bg-gradient-to-r from-emerald-50 to-green-50 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow">
                        <i class="fa-solid fa-box-archive"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Catat Barang Masuk</h2>
                        <p class="text-xs text-slate-500">Penerimaan barang dari supplier · stok akan otomatis bertambah</p>
                    </div>
                </div>
                <button onclick="closeModal()"
                    class="text-slate-400 hover:text-red-500 transition p-1.5 rounded-lg hover:bg-red-50">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="stockInForm" method="POST" action="/stock-in" class="overflow-y-auto flex-1 flex flex-col">
                @csrf

                <div class="p-6 flex-1 overflow-y-auto space-y-5">

                    {{-- ── INFO PENGIRIMAN ── --}}
                    <div class="section-divider"><span><i class="fa-solid fa-truck mr-1"></i> Info Pengiriman</span></div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Tanggal --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-calendar-days text-emerald-500 mr-1"></i>
                                Tanggal Masuk <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" required value="{{ now()->toDateString() }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                       focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                        </div>

                        {{-- Supplier --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-truck-field text-emerald-500 mr-1"></i>
                                Supplier
                            </label>
                            <select name="supplier_id" id="inputSupplierForm"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                       focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white">
                                <option value="">-- Tanpa Supplier --</option>
                                @foreach ($supplier as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- No. Faktur --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-file-invoice text-emerald-500 mr-1"></i>
                                No. Faktur Supplier
                            </label>
                            <input type="text" name="nomor_faktur_supplier" placeholder="INV-2025-0001 (opsional)"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                       focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                        </div>
                    </div>

                    {{-- ── DAFTAR PRODUK ── --}}
                    <div class="section-divider"><span><i class="fa-solid fa-boxes-stacked mr-1"></i> Daftar Produk yang
                            Masuk</span></div>

                    {{-- Table header --}}
                    <div class="overflow-x-auto">
                        <table class="items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width:32px">#</th>
                                    <th style="min-width:260px">Produk *</th>
                                    <th style="width:110px">Jumlah *</th>
                                    <th style="width:180px">Harga Modal/Satuan *</th>
                                    <th style="width:150px">Subtotal</th>
                                    <th style="width:36px"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                {{-- rows injected by JS --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Tambah baris --}}
                    <button type="button" onclick="addItem()" class="flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-800
                                               px-4 py-2 border-2 border-dashed border-emerald-300 rounded-xl w-full justify-center
                                               hover:border-emerald-500 hover:bg-emerald-50 transition">
                        <i class="fa-solid fa-plus"></i> Tambah Baris Produk
                    </button>

                    {{-- Grand total + catatan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-note-sticky text-slate-400 mr-1"></i> Catatan
                            </label>
                            <textarea name="catatan" rows="2" placeholder="Keterangan tambahan (opsional)..."
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                       focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none resize-none"></textarea>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-5 text-white shadow-lg">
                            <div class="text-xs font-semibold opacity-80 uppercase tracking-wide mb-1">Total Nilai Barang
                                Masuk</div>
                            <div id="grandTotal" class="text-3xl font-bold">Rp 0</div>
                            <div id="grandTotalInfo" class="text-xs opacity-70 mt-1">0 produk · 0 item</div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="px-6 py-4 border-t bg-slate-50 rounded-b-2xl flex justify-between items-center gap-3 flex-shrink-0">
                    <div class="text-xs text-slate-400">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Stok produk akan otomatis bertambah setelah disimpan
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="closeModal()"
                            class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                            <i class="fa-solid fa-xmark mr-1"></i> Batal
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 transition flex items-center gap-2 shadow">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Barang Masuk
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════ MODAL DETAIL ═══════════════════ --}}
    <div id="detailModal"
        class="fixed inset-0 hidden bg-black/40 modal-blur-overlay flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <div>
                        <h2 id="detailNomor" class="text-base font-bold text-slate-800">Detail Barang Masuk</h2>
                        <p id="detailMeta" class="text-xs text-slate-400"></p>
                    </div>
                </div>
                <button onclick="closeDetail()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div id="detailBody" class="overflow-y-auto flex-1 p-6"></div>
        </div>
    </div>

    <script>
        const barangMasukData = @json($barangMasuk->load('detail.produk'));
        const produkList = @json($produk);
    </script>

    @push('scripts')
        <script>
            $(document).ready(function () {

                // ── DataTable ──────────────────────────────────────────────────
                const table = $('#datatable').DataTable({
                    language: {
                        search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        paginate: { previous: 'Sebelumnya', next: 'Selanjutnya' },
                        zeroRecords: 'Data tidak ditemukan',
                    },
                    columnDefs: [{ orderable: false, targets: [0, 8] }]
                })

                $('#filterSupplier').on('change', function () { table.column(3).search(this.value).draw() })
                window.resetFilter = function () { $('#filterSupplier').val(''); table.column(3).search('').draw() }

                // ── Rupiah formatter ───────────────────────────────────────────
                function formatRupiah(angka) {
                    return 'Rp ' + parseInt(angka || 0).toLocaleString('id-ID')
                }

                function parseRupiah(str) {
                    return parseInt(str.replace(/\D/g, '')) || 0
                }

                // Konversi input angka → tampilan rupiah di bawah input
                function bindRupiahDisplay(input, displayEl) {
                    $(input).on('input', function () {
                        const val = parseFloat($(this).val()) || 0
                        $(displayEl).text(val > 0 ? formatRupiah(val) : '')
                        hitungTotal()
                    })
                }

                // ── Select2 template produk ────────────────────────────────────
                function templateProduk(item) {
                    if (!item.id) return item.text
                    const p = produkList.find(p => p.id == item.id)
                    if (!p) return item.text
                    return $(`
                                                    <span style="display:flex;align-items:center;gap:6px;width:100%">
                                                        <span class="produk-option-kode">${p.kode}</span>
                                                        <span style="flex:1;font-size:13px">${p.nama}</span>
                                                        <span class="produk-option-stok">
                                                            <i class="fa-solid fa-cubes" style="font-size:10px"></i> ${p.stok_saat_ini} ${p.satuan}
                                                        </span>
                                                    </span>`)
                }

                function initSelect2(selector) {
                    $(selector).select2({
                        dropdownParent: $('#stockInModal'),
                        placeholder: 'Cari produk...',
                        allowClear: true,
                        templateResult: templateProduk,
                        templateSelection: function (item) {
                            if (!item.id) return item.text
                            const p = produkList.find(p => p.id == item.id)
                            return p ? `${p.kode} — ${p.nama}` : item.text
                        },
                        width: '100%',
                    }).on('change', function () { hitungTotal() })
                }

                // ── Modal ──────────────────────────────────────────────────────
                window.openModal = function () {
                    $('#itemsBody').empty()
                    itemIndex = 0
                    addItem()
                    hitungTotal()
                    $('#stockInModal').removeClass('hidden')
                }

                window.closeModal = function () { $('#stockInModal').addClass('hidden') }

                $('#stockInModal').on('click', function (e) {
                    if ($(e.target).is($('#stockInModal'))) closeModal()
                })

                $('#stockInForm').on('submit', function () {
                    $('#submitBtn').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...')
                })

                // ── Item rows ──────────────────────────────────────────────────
                let itemIndex = 0

                window.addItem = function () {
                    const idx = itemIndex++
                    const num = $('#itemsBody tr').length + 1

                    const options = produkList.map(p =>
                        `<option value="${p.id}">${p.kode} — ${p.nama} (${p.satuan})</option>`
                    ).join('')

                    const row = `
                                                <tr id="item-row-${idx}">
                                                    <td>
                                                        <span class="text-xs text-slate-400 font-semibold" id="row-num-${idx}">${num}</span>
                                                    </td>
                                                    <td>
                                                        <select name="items[${idx}][produk_id]" id="sel-produk-${idx}" required class="item-produk-select">
                                                            <option value=""></option>
                                                            ${options}
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="items[${idx}][jumlah]" id="jml-${idx}"
                                                            min="1" placeholder="0" required
                                                            oninput="hitungTotal()"
                                                            class="text-center">
                                                        <div class="text-[10px] text-slate-400 mt-0.5 text-center" id="satuan-${idx}"></div>
                                                    </td>
                                                    <td>
                                                        <div class="rupiah-wrap">
                                                            <span class="rp-prefix">Rp</span>
                                                            <input type="number" name="items[${idx}][harga_modal]" id="hm-${idx}"
                                                                min="0" placeholder="0" required
                                                                oninput="hitungTotal()">
                                                        </div>
                                                        <div class="rupiah-display" id="hm-display-${idx}"></div>
                                                    </td>
                                                    <td>
                                                        <div class="text-sm font-bold text-emerald-700 px-2" id="subtotal-${idx}">Rp 0</div>
                                                    </td>
                                                    <td>
                                                        <button type="button" onclick="removeItem(${idx})"
                                                            class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400
                                                                   hover:bg-red-100 hover:text-red-500 transition">
                                                            <i class="fa-solid fa-xmark text-xs"></i>
                                                        </button>
                                                    </td>
                                                </tr>`

                    $('#itemsBody').append(row)

                    // Init select2 untuk row ini
                    initSelect2(`#sel-produk-${idx}`)

                    // Update satuan saat produk dipilih
                    $(`#sel-produk-${idx}`).on('change', function () {
                        const p = produkList.find(p => p.id == $(this).val())
                        $(`#satuan-${idx}`).text(p ? p.satuan : '')
                        hitungTotal()
                    })

                    // Bind rupiah display
                    bindRupiahDisplay(`#hm-${idx}`, `#hm-display-${idx}`)
                }

                window.removeItem = function (idx) {
                    if ($('#itemsBody tr').length <= 1) {
                        Swal.fire({ icon: 'warning', title: 'Minimal 1 produk', confirmButtonColor: '#10b981' })
                        return
                    }
                    $(`#item-row-${idx}`).remove()
                    renumberRows()
                    hitungTotal()
                }

                function renumberRows() {
                    $('#itemsBody tr').each(function (i) {
                        const idx = $(this).attr('id').replace('item-row-', '')
                        $(`#row-num-${idx}`).text(i + 1)
                    })
                }

                window.hitungTotal = function () {
                    let grand = 0, totalItem = 0, totalProduk = 0

                    $('#itemsBody tr').each(function () {
                        const idx = $(this).attr('id').replace('item-row-', '')
                        const jumlah = parseFloat($(`#jml-${idx}`).val()) || 0
                        const harga = parseFloat($(`#hm-${idx}`).val()) || 0
                        const subtotal = jumlah * harga

                        grand += subtotal
                        if (jumlah > 0) totalItem += jumlah
                        if ($(`#sel-produk-${idx}`).val()) totalProduk++

                        $(`#subtotal-${idx}`).text(formatRupiah(subtotal))
                    })

                    $('#grandTotal').text(formatRupiah(grand))
                    $('#grandTotalInfo').text(`${totalProduk} produk · ${totalItem} item`)
                }

                // ── Modal Detail ───────────────────────────────────────────────
                window.lihatDetail = function (id) {
                    const data = barangMasukData.find(d => d.id === id)
                    if (!data) return

                    $('#detailNomor').text(data.nomor)
                    $('#detailMeta').text(`${data.tanggal} · ${data.user?.name ?? '-'} · ${data.supplier?.nama ?? 'Tanpa Supplier'}`)

                    const rows = data.detail.map((d, i) => `
                                                    <tr class="border-t">
                                                        <td class="py-2.5 px-3 text-slate-400 text-xs">${i + 1}</td>
                                                        <td class="py-2.5 px-3">
                                                            <span class="font-mono text-[10px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded mr-1">${d.produk?.kode ?? ''}</span>
                                                            <span class="font-medium text-slate-800 text-sm">${d.produk?.nama ?? '-'}</span>
                                                        </td>
                                                        <td class="py-2.5 px-3 text-center text-sm">${d.jumlah} <span class="text-xs text-slate-400">${d.produk?.satuan ?? ''}</span></td>
                                                        <td class="py-2.5 px-3 text-right text-sm">${formatRupiah(d.harga_modal)}</td>
                                                        <td class="py-2.5 px-3 text-right font-semibold text-emerald-700 text-sm">${formatRupiah(d.subtotal)}</td>
                                                    </tr>`).join('')

                    const total = data.detail.reduce((s, d) => s + parseFloat(d.subtotal), 0)

                    $('#detailBody').html(`
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-sm">
                                                            <thead>
                                                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                                                                    <th class="py-2.5 px-3 text-left w-8">No</th>
                                                                    <th class="py-2.5 px-3 text-left">Produk</th>
                                                                    <th class="py-2.5 px-3 text-center">Jumlah</th>
                                                                    <th class="py-2.5 px-3 text-right">Harga Modal</th>
                                                                    <th class="py-2.5 px-3 text-right">Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>${rows}</tbody>
                                                        </table>
                                                    </div>
                                                    <div class="mt-4 flex justify-end">
                                                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-5 py-3 text-right">
                                                            <div class="text-xs text-slate-500 mb-1">Total Nilai</div>
                                                            <div class="text-2xl font-bold text-emerald-700">${formatRupiah(total)}</div>
                                                        </div>
                                                    </div>
                                                    ${data.catatan ? `<div class="mt-3 p-3 bg-amber-50 border border-amber-100 rounded-xl text-sm text-slate-600"><i class="fa-solid fa-note-sticky text-amber-500 mr-2"></i>${data.catatan}</div>` : ''}
                                                `)

                    $('#detailModal').removeClass('hidden')
                }

                window.closeDetail = function () { $('#detailModal').addClass('hidden') }
                $('#detailModal').on('click', function (e) { if ($(e.target).is($('#detailModal'))) closeDetail() })

                // ── Delete ─────────────────────────────────────────────────────
                window.deleteBarangMasuk = function (id, nomor) {
                    Swal.fire({
                        title: 'Hapus Data?',
                        html: `Barang masuk <strong>${nomor}</strong> akan dihapus dan stok akan dikurangi kembali.`,
                        icon: 'warning', showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonText: 'Batal', confirmButtonText: 'Ya, hapus',
                    }).then(r => {
                        if (r.isConfirmed) {
                            const f = document.createElement('form')
                            f.method = 'POST'; f.action = `/stock-in/${id}`
                            f.innerHTML = `<input type="hidden" name="_token" value="${$('meta[name=csrf-token]').attr('content')}"><input type="hidden" name="_method" value="DELETE">`
                            document.body.appendChild(f); f.submit()
                        }
                    })
                }
            })
        </script>
    @endpush
@endsection