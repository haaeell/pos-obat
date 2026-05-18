@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')

@push('styles')
    <style>
        #datatable td {
            white-space: nowrap;
        }

        /* ── Select2 ── */
            .select2-container .select2-selection--single {
                height: 38px !important; border-radius: 10px !important;
                border: 1.5px solid #d1d5db !important;
                display: flex; align-items: center;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                padding-left: 12px !important; font-size: 13px; color: #374151;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; }
            .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #10b981 !important;
                box-shadow: 0 0 0 3px rgba(16,185,129,.12) !important; outline: none !important;
            }
            .select2-dropdown {
                border-radius: 12px !important; border: 1.5px solid #d1fae5 !important;
                box-shadow: 0 8px 24px rgba(0,0,0,.12) !important; overflow: hidden;
            }
            .select2-search--dropdown input { border-radius: 8px !important; border: 1.5px solid #d1d5db !important; font-size: 13px; padding: 6px 10px; }
            .select2-results__option { font-size: 13px; padding: 8px 12px; }
            .select2-results__option--highlighted { background: #ecfdf5 !important; color: #065f46 !important; }
            .produk-option-kode { font-size: 10px; font-family: monospace; background: #f1f5f9; color: #475569; padding: 1px 6px; border-radius: 4px; margin-right: 6px; }
            .produk-option-stok { font-size: 10px; color: #6b7280; margin-left: auto; }

            /* ── Rupiah input ── */
            .rupiah-wrap { position: relative; }
            .rupiah-wrap .rp-prefix {
                position: absolute; left: 0; top: 0; bottom: 0;
                display: flex; align-items: center; padding: 0 10px;
                font-size: 12px; font-weight: 700; color: #6b7280;
                background: #f8fafc; border-right: 1.5px solid #e2e8f0;
                border-radius: 10px 0 0 10px; pointer-events: none;
            }
            .rupiah-wrap input { padding-left: 40px !important; }
            .rupiah-display { font-size: 12px; color: #059669; font-weight: 600; margin-top: 3px; min-height: 16px; }

            /* ── Items table ── */
            .items-table { width: 100%; border-collapse: separate; border-spacing: 0 6px; }
            .items-table thead th { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; padding: 0 8px 4px; }
            .items-table tbody tr { background: #f8fafc; }
            .items-table tbody tr td { padding: 8px 6px; border-top: 1.5px solid #e2e8f0; border-bottom: 1.5px solid #e2e8f0; }
            .items-table tbody tr td:first-child { border-left: 1.5px solid #e2e8f0; border-radius: 10px 0 0 10px; padding-left: 10px; }
            .items-table tbody tr td:last-child { border-right: 1.5px solid #e2e8f0; border-radius: 0 10px 10px 0; padding-right: 10px; }
            .items-table tbody tr:hover td { background: #f0fdf4; border-color: #a7f3d0; }
            .items-table input, .items-table select { width: 100%; padding: 7px 10px; font-size: 13px; border: 1.5px solid #e2e8f0; border-radius: 8px; background: white; outline: none; transition: border .15s; }
            .items-table input:focus, .items-table select:focus { border-color: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,.1); }

            /* ── Divider ── */
            .section-divider { display: flex; align-items: center; gap: 10px; margin: 6px 0 2px; }
            .section-divider span { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #059669; white-space: nowrap; }
            .section-divider::after { content: ''; flex: 1; height: 1px; background: #d1fae5; }

            /* ── Filter bar ── */
            .filter-bar select {
                border: 1.5px solid #d1fae5; border-radius: 8px; padding: 6px 32px 6px 10px;
                font-size: 13px; color: #374151; background: white; outline: none;
                appearance: none; -webkit-appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                background-repeat: no-repeat; background-position: right 10px center;
            }
            .filter-bar select:focus { border-color: #10b981; }

            /* ── Payment section ── */
            .payment-panel {
                border: 1.5px solid #e2e8f0; border-radius: 14px;
                overflow: hidden; transition: border-color .2s;
            }
            .payment-panel.hutang  { border-color: #fca5a5; background: #fff5f5; }
            .payment-panel.sebagian { border-color: #fcd34d; background: #fffbeb; }
            .payment-panel.lunas   { border-color: #6ee7b7; background: #f0fdf4; }

            .status-btn {
                flex: 1; padding: 10px 8px; border-radius: 10px; font-size: 13px;
                font-weight: 700; border: 2px solid #e2e8f0; background: white;
                cursor: pointer; transition: all .15s; text-align: center;
            }
            .status-btn.active-lunas    { background: #ecfdf5; border-color: #10b981; color: #065f46; }
            .status-btn.active-sebagian { background: #fffbeb; border-color: #f59e0b; color: #92400e; }
            .status-btn.active-hutang   { background: #fef2f2; border-color: #ef4444; color: #991b1b; }

            /* ── Cicilan modal ── */
            .cicilan-row { transition: background .15s; }
            .cicilan-row:hover { background: #f8fafc; }
            .progress-bar { height: 8px; background: #e2e8f0; border-radius: 99px; overflow: hidden; }
            .progress-fill { height: 100%; border-radius: 99px; transition: width .4s ease; }
        </style>
@endpush

@section('content')
    <div class="mx-auto bg-white rounded-xl">

        {{-- ── HEADER ── --}}
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

        {{-- ── RINGKASAN HUTANG ── --}}
        @if($ringkasanHutang['total_hutang'] > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">
                <div class="bg-red-50 border border-red-100 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 text-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <div class="text-xs text-red-500 font-semibold uppercase tracking-wide">Total Transaksi Hutang</div>
                        <div class="text-xl font-bold text-red-700">{{ $ringkasanHutang['total_hutang'] }} transaksi</div>
                    </div>
                </div>
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <div class="text-xs text-amber-600 font-semibold uppercase tracking-wide">Total Sisa Hutang</div>
                        <div class="text-xl font-bold text-amber-700">Rp {{ number_format($ringkasanHutang['nilai_hutang'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-xs text-orange-500 font-semibold uppercase tracking-wide">Jatuh Tempo ≤ 7 Hari</div>
                        <div class="text-xl font-bold text-orange-700">{{ $ringkasanHutang['jatuh_tempo_7'] }} transaksi</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── FILTER ── --}}
        <div class="filter-bar flex flex-wrap items-center gap-3 mb-4 p-4 bg-emerald-50/60 rounded-xl border border-emerald-100">
            <div class="flex items-center gap-2 text-sm font-semibold text-emerald-700">
                <i class="fa-solid fa-filter"></i> Filter:
            </div>
            <select id="filterSupplier">
                <option value="">Semua Supplier</option>
                @foreach ($supplier as $sup)
                    <option value="{{ $sup->nama }}">{{ $sup->nama }}</option>
                @endforeach
            </select>
            <select id="filterStatus">
                <option value="">Semua Status Bayar</option>
                <option value="Lunas">Lunas</option>
                <option value="Sebagian">Sebagian</option>
                <option value="Hutang">Hutang</option>
            </select>
            <button onclick="resetFilter()"
                class="px-3 py-1.5 text-xs font-semibold text-slate-500 hover:text-red-500 border border-slate-200 rounded-lg hover:border-red-300 transition bg-white">
                <i class="fa-solid fa-xmark mr-1"></i> Reset
            </button>
        </div>

        {{-- ── TABLE ── --}}
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
                        <th class="px-4 py-3 text-center">Status Bayar</th>
                        <th class="px-4 py-3 text-left">Jatuh Tempo</th>
                        <th class="px-4 py-3 text-left">Dicatat Oleh</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangMasuk as $i => $item)
                        @php
                            $sisaHutang = $item->sisa_hutang;
                            $isJatuhTempo = $item->tanggal_jatuh_tempo && $item->tanggal_jatuh_tempo->isPast() && $item->isHutang();
                            $dekatJt = $item->tanggal_jatuh_tempo && $item->tanggal_jatuh_tempo->diffInDays(now()) <= 7 && $item->isHutang() && !$isJatuhTempo;
                        @endphp
                        <tr class="border-t hover:bg-slate-50 transition {{ $isJatuhTempo ? 'bg-red-50/40' : '' }}">
                            <td class="px-4 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-mono text-xs bg-emerald-50 text-emerald-700 px-2 py-1 rounded font-semibold">
                                    {{ $item->nomor }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $item->supplier->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $item->nomor_faktur_supplier ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700">
                                    {{ $item->detail->count() }} produk
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="font-semibold text-slate-800">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                                @if($item->isHutang())
                                    <div class="text-xs text-red-500 mt-0.5">Sisa: Rp {{ number_format($sisaHutang, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{-- badge status --}}
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $item->badge_status_bayar }}">
                                    {{ $item->label_status_bayar }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @if($item->tanggal_jatuh_tempo && $item->isHutang())
                                    <span class="{{ $isJatuhTempo ? 'text-red-600 font-bold' : ($dekatJt ? 'text-amber-600 font-semibold' : 'text-slate-500') }}">
                                        @if($isJatuhTempo)<i class="fa-solid fa-triangle-exclamation mr-1"></i>@endif
                                        {{ $item->tanggal_jatuh_tempo->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $item->user->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button onclick="lihatDetail({{ $item->id }})"
                                        class="px-3 py-1.5 bg-blue-500 text-white hover:bg-blue-600 rounded-lg transition text-xs font-semibold"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    @if($item->isHutang())
                                        <button onclick="openCicilanModal({{ $item->id }}, '{{ $item->nomor }}', {{ $item->sisa_hutang }})"
                                            class="px-3 py-1.5 bg-amber-500 text-white hover:bg-amber-600 rounded-lg transition text-xs font-semibold"
                                            title="Bayar Cicilan">
                                            <i class="fa-solid fa-money-bill-wave"></i>
                                        </button>
                                    @endif
                                    <button onclick="deleteBarangMasuk({{ $item->id }}, '{{ $item->nomor }}')"
                                        class="px-3 py-1.5 bg-red-500 text-white hover:bg-red-600 rounded-lg transition text-xs font-semibold"
                                        title="Hapus">
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
    <div id="stockInModal" class="fixed inset-0 hidden bg-black/40 modal-blur-overlay flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-5xl rounded-2xl shadow-2xl max-h-[94vh] flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0 bg-gradient-to-r from-emerald-50 to-green-50 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow">
                        <i class="fa-solid fa-box-archive"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Catat Barang Masuk</h2>
                        <p class="text-xs text-slate-500">Penerimaan barang dari supplier · stok akan otomatis bertambah</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition p-1.5 rounded-lg hover:bg-red-50">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="stockInForm" method="POST" action="/stock-in" class="overflow-y-auto flex-1 flex flex-col">
                @csrf

                <div class="p-6 flex-1 overflow-y-auto space-y-5">

                    {{-- ── INFO PENGIRIMAN ── --}}
                    <div class="section-divider"><span><i class="fa-solid fa-truck mr-1"></i> Info Pengiriman</span></div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-calendar-days text-emerald-500 mr-1"></i>
                                Tanggal Masuk <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="inputTanggal" required value="{{ now()->toDateString() }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-truck-field text-emerald-500 mr-1"></i> Supplier
                            </label>
                            <select name="supplier_id" id="inputSupplierForm"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white">
                                <option value="">-- Tanpa Supplier --</option>
                                @foreach ($supplier as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-file-invoice text-emerald-500 mr-1"></i> No. Faktur Supplier
                            </label>
                            <input type="text" name="nomor_faktur_supplier" placeholder="INV-2025-0001 (opsional)"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                        </div>
                    </div>

                    {{-- ── DAFTAR PRODUK ── --}}
                    <div class="section-divider"><span><i class="fa-solid fa-boxes-stacked mr-1"></i> Daftar Produk yang Masuk</span></div>

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
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>

                    <button type="button" onclick="addItem()"
                        class="flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-800 px-4 py-2 border-2 border-dashed border-emerald-300 rounded-xl w-full justify-center hover:border-emerald-500 hover:bg-emerald-50 transition">
                        <i class="fa-solid fa-plus"></i> Tambah Baris Produk
                    </button>

                    {{-- ── PEMBAYARAN ── --}}
                    <div class="section-divider"><span><i class="fa-solid fa-wallet mr-1"></i> Pembayaran</span></div>

                    <div id="paymentPanel" class="payment-panel p-4 space-y-4">

                        {{-- Pilihan status --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-2">
                                Status Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <button type="button" class="status-btn active-lunas" data-status="lunas" onclick="setStatusBayar('lunas')">
                                    <i class="fa-solid fa-circle-check mr-1"></i> Lunas
                                </button>
                                <button type="button" class="status-btn" data-status="sebagian" onclick="setStatusBayar('sebagian')">
                                    <i class="fa-solid fa-circle-half-stroke mr-1"></i> Sebagian / DP
                                </button>
                                <button type="button" class="status-btn" data-status="hutang" onclick="setStatusBayar('hutang')">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i> Hutang Penuh
                                </button>
                            </div>
                            <input type="hidden" name="status_bayar" id="inputStatusBayar" value="lunas">
                        </div>

                        {{-- Metode bayar (tampil jika bukan hutang penuh) --}}
                        <div id="metodeBayarWrap" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                    <i class="fa-solid fa-credit-card text-emerald-500 mr-1"></i>
                                    Metode Bayar <span class="text-red-500">*</span>
                                </label>
                                <select name="metode_bayar" id="inputMetodeBayar"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-emerald-500 outline-none bg-white">
                                    <option value="tunai"><i class="fa-solid fa-cash-register mr-1"></i> Tunai</option>
                                    <option value="transfer"><i class="fa-solid fa-bank mr-1"></i> Transfer Bank</option>
                                    <option value="lainnya"> Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                    <i class="fa-solid fa-receipt text-slate-400 mr-1"></i> No. Referensi
                                </label>
                                <input type="text" name="nomor_referensi" placeholder="No. transfer / cek (opsional)"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-emerald-500 outline-none">
                            </div>
                        </div>

                        {{-- Bayar awal (hanya jika sebagian) --}}
                        <div id="bayarAwalWrap" class="hidden">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-money-bill text-amber-500 mr-1"></i>
                                Nominal Bayar Awal (DP) <span class="text-red-500">*</span>
                            </label>
                            <div class="rupiah-wrap">
                                <span class="rp-prefix">Rp</span>
                                <input type="number" name="bayar_awal" id="inputBayarAwal" min="0" placeholder="0"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-amber-400 outline-none">
                            </div>
                            <div class="rupiah-display" id="bayarAwalDisplay"></div>
                        </div>

                        {{-- Tanggal jatuh tempo (tampil jika hutang / sebagian) --}}
                        <div id="jatuhTempoWrap" class="hidden">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-calendar-xmark text-red-500 mr-1"></i>
                                Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_jatuh_tempo" id="inputJatuhTempo"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-emerald-500 outline-none">
                        </div>

                        {{-- Catatan pembayaran --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-note-sticky text-slate-400 mr-1"></i> Catatan Pembayaran
                            </label>
                            <input type="text" name="catatan_pembayaran" placeholder="Keterangan pembayaran (opsional)"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-emerald-500 outline-none">
                        </div>
                    </div>

                    {{-- Grand total + catatan umum --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1.5">
                                <i class="fa-solid fa-note-sticky text-slate-400 mr-1"></i> Catatan Umum
                            </label>
                            <textarea name="catatan" rows="2" placeholder="Keterangan tambahan (opsional)..."
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none resize-none"></textarea>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-5 text-white shadow-lg">
                            <div class="text-xs font-semibold opacity-80 uppercase tracking-wide mb-1">Total Nilai Barang Masuk</div>
                            <div id="grandTotal" class="text-3xl font-bold">Rp 0</div>
                            <div id="grandTotalInfo" class="text-xs opacity-70 mt-1">0 produk · 0 item</div>
                            <div id="sisaBayarInfo" class="hidden mt-2 pt-2 border-t border-white/30">
                                <div class="text-xs opacity-80">Dibayar: <span id="dibayarAmt" class="font-bold">Rp 0</span></div>
                                <div class="text-xs opacity-80">Sisa hutang: <span id="sisaAmt" class="font-bold">Rp 0</span></div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t bg-slate-50 rounded-b-2xl flex justify-between items-center gap-3 flex-shrink-0">
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
    <div id="detailModal" class="fixed inset-0 hidden bg-black/40 modal-blur-overlay flex items-center justify-center z-50 p-4">
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

    {{-- ═══════════════════ MODAL CICILAN ═══════════════════ --}}
    <div id="cicilanModal" class="fixed inset-0 hidden bg-black/40 modal-blur-overlay flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl max-h-[90vh] flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Bayar Hutang Supplier</h2>
                        <p id="cicilanNomor" class="text-xs text-slate-500"></p>
                    </div>
                </div>
                <button onclick="closeCicilan()" class="text-slate-400 hover:text-red-500 transition p-1.5 rounded-lg hover:bg-red-50">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6 space-y-5">

                {{-- Progress bar sisa hutang --}}
                <div id="cicilanProgressWrap"></div>

                {{-- Riwayat cicilan --}}
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat Pembayaran
                    </div>
                    <div id="riwayatCicilan" class="space-y-1 text-sm text-slate-400 italic">
                        Belum ada pembayaran
                    </div>
                </div>

                {{-- Form tambah cicilan --}}
                <div class="pt-4 border-t space-y-3">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">
                        <i class="fa-solid fa-plus mr-1 text-amber-500"></i> Tambah Pembayaran
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 block mb-1">Tanggal Bayar *</label>
                            <input type="date" id="cicilanTanggal" value="{{ now()->toDateString() }}"
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm outline-none focus:border-amber-400">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 block mb-1">Metode *</label>
                            <select id="cicilanMetode" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm outline-none focus:border-amber-400 bg-white">
                                <option value="tunai"><i class="fa-solid fa-cash-register mr-1"></i> Tunai</option>
                                    <option value="transfer"><i class="fa-solid fa-bank mr-1"></i> Transfer Bank</option>
                                    <option value="lainnya">🔖 Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-500 block mb-1">Jumlah Bayar *
                            <span class="font-normal text-slate-400">(maks: <span id="sisaHutangLabel">Rp 0</span>)</span>
                        </label>
                        <div class="rupiah-wrap">
                            <span class="rp-prefix">Rp</span>
                            <input type="number" id="cicilanJumlah" min="1" placeholder="0"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none focus:border-amber-400"
                                oninput="updateSisaPreview()">
                        </div>
                        <div class="rupiah-display" id="cicilanJumlahDisplay"></div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-500 block mb-1">No. Referensi</label>
                        <input type="text" id="cicilanRef" placeholder="No. transfer / cek (opsional)"
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm outline-none focus:border-amber-400">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-500 block mb-1">Catatan</label>
                        <input type="text" id="cicilanCatatan" placeholder="Opsional"
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm outline-none focus:border-amber-400">
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t bg-slate-50 rounded-b-2xl flex justify-end gap-2 flex-shrink-0">
                <button onclick="closeCicilan()"
                    class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Tutup
                </button>
                <button onclick="simpanCicilan()"
                    class="px-6 py-2.5 rounded-xl bg-amber-500 text-white text-sm font-bold hover:bg-amber-600 transition flex items-center gap-2 shadow">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Pembayaran
                </button>
            </div>
        </div>
    </div>

    {{-- ── Data untuk JS ── --}}
    <script>
        const barangMasukData = @json($barangMasuk->load('detail.produk', 'pembayaran.user'));
        const produkList      = @json($produk);
        const csrfToken       = '{{ csrf_token() }}';
    </script>

    @push('scripts')
        <script>
        $(document).ready(function () {

            // ── DataTable ──────────────────────────────────────────────────────
            const table = $('#datatable').DataTable({
                language: {
                    search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    paginate: { previous: 'Sebelumnya', next: 'Selanjutnya' },
                    zeroRecords: 'Data tidak ditemukan',
                },
                columnDefs: [{ orderable: false, targets: [0, 10] }]
            })

            $('#filterSupplier').on('change', function () { table.column(3).search(this.value).draw() })
            $('#filterStatus').on('change', function () { table.column(7).search(this.value).draw() })
            window.resetFilter = function () {
                $('#filterSupplier, #filterStatus').val('')
                table.columns([3, 7]).search('').draw()
            }

            // ── Helpers ────────────────────────────────────────────────────────
            function formatRupiah(angka) {
                return 'Rp ' + parseInt(angka || 0).toLocaleString('id-ID')
            }

            function parseNum(str) {
                return parseFloat(String(str).replace(/\D/g, '')) || 0
            }

            function bindRupiahDisplay(input, displayEl) {
                $(input).on('input', function () {
                    const val = parseFloat($(this).val()) || 0
                    $(displayEl).text(val > 0 ? formatRupiah(val) : '')
                })
            }

            // ── Select2 produk ─────────────────────────────────────────────────
            function templateProduk(item) {
                if (!item.id) return item.text
                const p = produkList.find(p => p.id == item.id)
                if (!p) return item.text
                return $(`<span style="display:flex;align-items:center;gap:6px;width:100%">
                    <span class="produk-option-kode">${p.kode}</span>
                    <span style="flex:1;font-size:13px">${p.nama}</span>
                    <span class="produk-option-stok"><i class="fa-solid fa-cubes" style="font-size:10px"></i> ${p.stok_saat_ini} ${p.satuan}</span>
                </span>`)
            }

            function initSelect2(selector) {
                $(selector).select2({
                    dropdownParent: $('#stockInModal'),
                    placeholder: 'Cari produk...', allowClear: true,
                    templateResult: templateProduk,
                    templateSelection: function (item) {
                        if (!item.id) return item.text
                        const p = produkList.find(p => p.id == item.id)
                        return p ? `${p.kode} — ${p.nama}` : item.text
                    },
                    width: '100%',
                }).on('change', function () { hitungTotal() })
            }

            // ── Modal Tambah ───────────────────────────────────────────────────
            window.openModal = function () {
                $('#itemsBody').empty()
                itemIndex = 0
                addItem()
                setStatusBayar('lunas')
                hitungTotal()
                $('#stockInModal').removeClass('hidden')
            }
            window.closeModal = function () { $('#stockInModal').addClass('hidden') }
            $('#stockInModal').on('click', function (e) { if ($(e.target).is($('#stockInModal'))) closeModal() })
            $('#stockInForm').on('submit', function () {
                $('#submitBtn').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...')
            })

            // ── Status Bayar ───────────────────────────────────────────────────
            window.setStatusBayar = function (status) {
                $('#inputStatusBayar').val(status)

                // Update button style
                $('.status-btn').removeClass('active-lunas active-sebagian active-hutang')
                $(`[data-status="${status}"]`).addClass(`active-${status}`)

                // Panel border
                $('#paymentPanel').removeClass('lunas sebagian hutang').addClass(status)

                // Toggle fields
                if (status === 'lunas') {
                    $('#metodeBayarWrap').removeClass('hidden')
                    $('#bayarAwalWrap').addClass('hidden')
                    $('#jatuhTempoWrap').addClass('hidden')
                    $('#sisaBayarInfo').addClass('hidden')
                } else if (status === 'sebagian') {
                    $('#metodeBayarWrap').removeClass('hidden')
                    $('#bayarAwalWrap').removeClass('hidden')
                    $('#jatuhTempoWrap').removeClass('hidden')
                    $('#sisaBayarInfo').removeClass('hidden')
                } else { // hutang
                    $('#metodeBayarWrap').addClass('hidden')
                    $('#bayarAwalWrap').addClass('hidden')
                    $('#jatuhTempoWrap').removeClass('hidden')
                    $('#sisaBayarInfo').removeClass('hidden')
                }

                hitungTotal()
            }

            // Bind bayar awal input
            $('#inputBayarAwal').on('input', function () {
                const val = parseFloat($(this).val()) || 0
                $('#bayarAwalDisplay').text(val > 0 ? formatRupiah(val) : '')
                updateSisaPanel()
            })

            function updateSisaPanel() {
                const status   = $('#inputStatusBayar').val()
                const grand    = hitungGrandTotal()
                let dibayar    = 0

                if (status === 'lunas')    dibayar = grand
                if (status === 'sebagian') dibayar = parseFloat($('#inputBayarAwal').val()) || 0

                const sisa = Math.max(0, grand - dibayar)
                $('#dibayarAmt').text(formatRupiah(dibayar))
                $('#sisaAmt').text(formatRupiah(sisa))
            }

            // ── Item rows ──────────────────────────────────────────────────────
            let itemIndex = 0

            window.addItem = function () {
                const idx = itemIndex++
                const num = $('#itemsBody tr').length + 1

                const options = produkList.map(p =>
                    `<option value="${p.id}">${p.kode} — ${p.nama} (${p.satuan})</option>`
                ).join('')

                const row = `
                <tr id="item-row-${idx}">
                    <td><span class="text-xs text-slate-400 font-semibold" id="row-num-${idx}">${num}</span></td>
                    <td>
                        <select name="items[${idx}][produk_id]" id="sel-produk-${idx}" required class="item-produk-select">
                            <option value=""></option>${options}
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[${idx}][jumlah]" id="jml-${idx}" min="1" placeholder="0" required oninput="hitungTotal()" class="text-center">
                        <div class="text-[10px] text-slate-400 mt-0.5 text-center" id="satuan-${idx}"></div>
                    </td>
                    <td>
                        <div class="rupiah-wrap">
                            <span class="rp-prefix">Rp</span>
                            <input type="number" name="items[${idx}][harga_modal]" id="hm-${idx}" min="0" placeholder="0" required oninput="hitungTotal()">
                        </div>
                        <div class="rupiah-display" id="hm-display-${idx}"></div>
                    </td>
                    <td><div class="text-sm font-bold text-emerald-700 px-2" id="subtotal-${idx}">Rp 0</div></td>
                    <td>
                        <button type="button" onclick="removeItem(${idx})" class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-red-100 hover:text-red-500 transition">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    </td>
                </tr>`

                $('#itemsBody').append(row)
                initSelect2(`#sel-produk-${idx}`)

                $(`#sel-produk-${idx}`).on('change', function () {
                    const p = produkList.find(p => p.id == $(this).val())
                    $(`#satuan-${idx}`).text(p ? p.satuan : '')
                    hitungTotal()
                })

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

            function hitungGrandTotal() {
                let grand = 0
                $('#itemsBody tr').each(function () {
                    const idx     = $(this).attr('id').replace('item-row-', '')
                    const jumlah  = parseFloat($(`#jml-${idx}`).val()) || 0
                    const harga   = parseFloat($(`#hm-${idx}`).val()) || 0
                    grand += jumlah * harga
                })
                return grand
            }

            window.hitungTotal = function () {
                let grand = 0, totalItem = 0, totalProduk = 0

                $('#itemsBody tr').each(function () {
                    const idx      = $(this).attr('id').replace('item-row-', '')
                    const jumlah   = parseFloat($(`#jml-${idx}`).val()) || 0
                    const harga    = parseFloat($(`#hm-${idx}`).val()) || 0
                    const subtotal = jumlah * harga

                    grand += subtotal
                    if (jumlah > 0) totalItem += jumlah
                    if ($(`#sel-produk-${idx}`).val()) totalProduk++

                    $(`#subtotal-${idx}`).text(formatRupiah(subtotal))
                })

                $('#grandTotal').text(formatRupiah(grand))
                $('#grandTotalInfo').text(`${totalProduk} produk · ${totalItem} item`)
                updateSisaPanel()
            }

            // ── Modal Detail ───────────────────────────────────────────────────
            window.lihatDetail = function (id) {
                const data = barangMasukData.find(d => d.id === id)
                if (!data) return

                $('#detailNomor').text(data.nomor)

                const statusColors = { lunas: 'bg-emerald-100 text-emerald-700', sebagian: 'bg-amber-100 text-amber-700', hutang: 'bg-red-100 text-red-700' }
                const statusLabel  = { lunas: 'Lunas', sebagian: 'Sebagian', hutang: 'Hutang' }
                const sc = statusColors[data.status_bayar] || 'bg-slate-100 text-slate-500'
                const sl = statusLabel[data.status_bayar] || '-'

                $('#detailMeta').html(`${data.tanggal} · ${data.supplier?.nama ?? 'Tanpa Supplier'}
                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-bold ${sc}">${sl}</span>`)

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

                const total        = data.detail.reduce((s, d) => s + parseFloat(d.subtotal), 0)
                const totalDibayar = parseFloat(data.total_dibayar) || 0
                const sisa         = Math.max(0, total - totalDibayar)

                // Riwayat pembayaran
                let riwayatHtml = ''
                if (data.pembayaran && data.pembayaran.length > 0) {
                    const pRows = data.pembayaran.map((p, i) => `
                    <tr class="border-t text-xs">
                        <td class="py-2 px-3 text-slate-400">${i + 1}</td>
                        <td class="py-2 px-3">${p.tanggal_bayar}</td>
                        <td class="py-2 px-3">${p.label_metode ?? p.metode_bayar}</td>
                        <td class="py-2 px-3 text-right font-semibold text-emerald-700">${formatRupiah(p.jumlah_bayar)}</td>
                        <td class="py-2 px-3 text-slate-400">${p.catatan ?? ''}</td>
                    </tr>`).join('')

                    riwayatHtml = `
                    <div class="mt-4">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                            <i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat Pembayaran
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead><tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                                    <th class="py-2 px-3 text-left w-8">No</th>
                                    <th class="py-2 px-3 text-left">Tanggal</th>
                                    <th class="py-2 px-3 text-left">Metode</th>
                                    <th class="py-2 px-3 text-right">Jumlah</th>
                                    <th class="py-2 px-3 text-left">Catatan</th>
                                </tr></thead>
                                <tbody>${pRows}</tbody>
                            </table>
                        </div>
                    </div>`
                }

                $('#detailBody').html(`
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead><tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                                <th class="py-2.5 px-3 text-left w-8">No</th>
                                <th class="py-2.5 px-3 text-left">Produk</th>
                                <th class="py-2.5 px-3 text-center">Jumlah</th>
                                <th class="py-2.5 px-3 text-right">Harga Modal</th>
                                <th class="py-2.5 px-3 text-right">Subtotal</th>
                            </tr></thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end gap-3">
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-5 py-3 text-right flex-1">
                            <div class="text-xs text-slate-500 mb-1">Dibayar</div>
                            <div class="text-xl font-bold text-emerald-700">${formatRupiah(totalDibayar)}</div>
                        </div>
                        ${sisa > 0 ? `<div class="bg-red-50 border border-red-100 rounded-xl px-5 py-3 text-right flex-1">
                            <div class="text-xs text-red-400 mb-1">Sisa Hutang</div>
                            <div class="text-xl font-bold text-red-600">${formatRupiah(sisa)}</div>
                        </div>` : ''}
                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-right flex-1">
                            <div class="text-xs text-slate-500 mb-1">Total Nilai</div>
                            <div class="text-2xl font-bold text-slate-800">${formatRupiah(total)}</div>
                        </div>
                    </div>
                    ${riwayatHtml}
                    ${data.catatan ? `<div class="mt-3 p-3 bg-amber-50 border border-amber-100 rounded-xl text-sm text-slate-600"><i class="fa-solid fa-note-sticky text-amber-500 mr-2"></i>${data.catatan}</div>` : ''}
                `)

                $('#detailModal').removeClass('hidden')
            }

            window.closeDetail = function () { $('#detailModal').addClass('hidden') }
            $('#detailModal').on('click', function (e) { if ($(e.target).is($('#detailModal'))) closeDetail() })

            // ── Modal Cicilan ──────────────────────────────────────────────────
            let activeCicilanId = null

            window.openCicilanModal = function (id, nomor, sisaHutang) {
                activeCicilanId = id
                $('#cicilanNomor').text(nomor)
                $('#sisaHutangLabel').text(formatRupiah(sisaHutang))
                $('#cicilanJumlah').attr('max', sisaHutang).val('')
                $('#cicilanJumlahDisplay').text('')
                $('#cicilanRef').val('')
                $('#cicilanCatatan').val('')

                renderProgressBar(id)
                renderRiwayatCicilan(id)

                $('#cicilanModal').removeClass('hidden')
            }

            window.closeCicilan = function () { $('#cicilanModal').addClass('hidden') }
            $('#cicilanModal').on('click', function (e) { if ($(e.target).is($('#cicilanModal'))) closeCicilan() })

            $('#cicilanJumlah').on('input', function () {
                const val = parseFloat($(this).val()) || 0
                $('#cicilanJumlahDisplay').text(val > 0 ? formatRupiah(val) : '')
            })

            window.updateSisaPreview = function () {
                // alias agar oninput bisa memanggil
                $('#cicilanJumlah').trigger('input')
            }

            function renderProgressBar(id) {
                const data = barangMasukData.find(d => d.id === id)
                if (!data) return

                const total      = data.detail.reduce((s, d) => s + parseFloat(d.subtotal), 0)
                const dibayar    = parseFloat(data.total_dibayar) || 0
                const pct        = total > 0 ? Math.min(100, (dibayar / total) * 100) : 0
                const sisa       = Math.max(0, total - dibayar)

                $('#cicilanProgressWrap').html(`
                    <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                        <div class="flex justify-between text-xs font-semibold text-slate-500">
                            <span>Dibayar: <span class="text-emerald-600">${formatRupiah(dibayar)}</span></span>
                            <span>Total: <span class="text-slate-700">${formatRupiah(total)}</span></span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill bg-emerald-400" style="width:${pct}%"></div>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">${pct.toFixed(1)}% terbayar</span>
                            <span class="text-red-500 font-semibold">Sisa: ${formatRupiah(sisa)}</span>
                        </div>
                    </div>
                `)
            }

            function renderRiwayatCicilan(id) {
                const data = barangMasukData.find(d => d.id === id)
                if (!data || !data.pembayaran || data.pembayaran.length === 0) {
                    $('#riwayatCicilan').html('<p class="text-sm text-slate-400 italic">Belum ada pembayaran</p>')
                    return
                }

                const rows = data.pembayaran.map(p => `
                <div class="cicilan-row flex items-center justify-between gap-3 px-3 py-2 rounded-lg border border-slate-100">
                    <div>
                        <div class="text-xs font-semibold text-slate-700">${p.tanggal_bayar} · ${p.label_metode ?? p.metode_bayar}</div>
                        ${p.catatan ? `<div class="text-[11px] text-slate-400">${p.catatan}</div>` : ''}
                        ${p.nomor_referensi ? `<div class="text-[11px] text-slate-400 font-mono">${p.nomor_referensi}</div>` : ''}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-emerald-700">${formatRupiah(p.jumlah_bayar)}</span>
                        <button onclick="hapusCicilan(${id}, ${p.id})"
                            class="w-6 h-6 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded transition">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    </div>
                </div>`).join('')

                $('#riwayatCicilan').html(rows)
            }

            window.simpanCicilan = function () {
                const jumlah = parseFloat($('#cicilanJumlah').val()) || 0
                const data   = barangMasukData.find(d => d.id === activeCicilanId)
                if (!data) return

                const total   = data.detail.reduce((s, d) => s + parseFloat(d.subtotal), 0)
                const dibayar = parseFloat(data.total_dibayar) || 0
                const sisa    = Math.max(0, total - dibayar)

                if (jumlah <= 0) { Swal.fire({ icon: 'warning', title: 'Jumlah bayar harus lebih dari 0', confirmButtonColor: '#f59e0b' }); return }
                if (jumlah > sisa) { Swal.fire({ icon: 'warning', title: `Melebihi sisa hutang (${formatRupiah(sisa)})`, confirmButtonColor: '#f59e0b' }); return }

                $.ajax({
                    url: `/stock-in/${activeCicilanId}/bayar-cicilan`,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    data: {
                        tanggal_bayar:   $('#cicilanTanggal').val(),
                        jumlah_bayar:    jumlah,
                        metode_bayar:    $('#cicilanMetode').val(),
                        nomor_referensi: $('#cicilanRef').val(),
                        catatan:         $('#cicilanCatatan').val(),
                    },
                    success: function () {
                        Swal.fire({ icon: 'success', title: 'Pembayaran berhasil dicatat!', timer: 1500, showConfirmButton: false })
                            .then(() => location.reload())
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.message ?? 'Terjadi kesalahan.'
                        Swal.fire({ icon: 'error', title: 'Gagal', text: msg, confirmButtonColor: '#ef4444' })
                    }
                })
            }

            window.hapusCicilan = function (barangMasukId, cicilanId) {
                Swal.fire({
                    title: 'Hapus pembayaran ini?',
                    text: 'Status hutang akan diperbarui otomatis.',
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#dc2626', cancelButtonText: 'Batal', confirmButtonText: 'Ya, hapus',
                }).then(r => {
                    if (!r.isConfirmed) return
                    $.ajax({
                        url: `/stock-in/${barangMasukId}/cicilan/${cicilanId}`,
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        success: function () {
                            Swal.fire({ icon: 'success', title: 'Pembayaran dihapus', timer: 1200, showConfirmButton: false })
                                .then(() => location.reload())
                        },
                        error: function () {
                            Swal.fire({ icon: 'error', title: 'Gagal menghapus', confirmButtonColor: '#ef4444' })
                        }
                    })
                })
            }

            // ── Delete Barang Masuk ────────────────────────────────────────────
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
                        f.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}"><input type="hidden" name="_method" value="DELETE">`
                        document.body.appendChild(f); f.submit()
                    }
                })
            }
        })
        </script>
    @endpush
@endsection