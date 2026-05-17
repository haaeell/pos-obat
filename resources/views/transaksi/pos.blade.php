@extends('layouts.app')

@section('title', ' POS')
@section('page-title', ' POS')

@push('styles')
    <style>
        /* ── Paksa konten utama tidak ada padding tambahan ── */
        .pos-page-wrap {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 80px);
            /* sesuaikan 80px = tinggi navbar/header app */
            overflow: hidden;
        }

        .pos-topbar {
            flex-shrink: 0;
            padding-bottom: 10px;
        }

        .pos-wrap {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 350px;
            min-height: 0;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
        }

        /* ── KIRI ── */
        .pos-left {
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 0;
            border-right: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .pos-left-bar {
            flex-shrink: 0;
            padding: 10px 12px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pos-search-wrap {
            position: relative;
            flex: 1;
        }

        .pos-search-wrap i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12px;
        }

        .pos-search {
            width: 100%;
            padding: 8px 10px 8px 30px;
            font-size: 13px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            background: #f8fafc;
            outline: none;
            color: #1e293b;
        }

        .pos-search:focus {
            border-color: #10b981;
            background: #fff;
        }

        /* Barcode */
        .barcode-bar {
            flex-shrink: 0;
            padding: 6px 12px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
        }

        .barcode-wrap {
            position: relative;
        }

        .barcode-wrap i {
            position: absolute;
            left: 9px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12px;
        }

        .barcode-input {
            width: 100%;
            padding: 7px 10px 7px 28px;
            font-size: 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            outline: none;
            color: #1e293b;
        }

        .barcode-input:focus {
            border-color: #10b981;
            background: #fff;
        }

        /* Kategori */
        .cat-scroll {
            flex-shrink: 0;
            display: flex;
            gap: 5px;
            overflow-x: auto;
            padding: 7px 12px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            cursor: grab;
            user-select: none;
            -webkit-overflow-scrolling: touch;
        }

        .cat-scroll:active {
            cursor: grabbing;
        }

        .cat-scroll::-webkit-scrollbar {
            height: 2px;
        }

        .cat-scroll::-webkit-scrollbar-thumb {
            background: #d1fae5;
            border-radius: 2px;
        }

        .cat-pill {
            flex-shrink: 0;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            cursor: pointer;
            white-space: nowrap;
            transition: all .12s;
        }

        .cat-pill.active {
            background: #059669;
            color: #fff;
            border-color: #059669;
        }

        .cat-pill:hover:not(.active) {
            border-color: #10b981;
            color: #059669;
        }

        /* Grid produk */
        .prod-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 10px 12px 6px;
            min-height: 0;
        }

        .prod-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .prod-scroll::-webkit-scrollbar-thumb {
            background: #d1fae5;
            border-radius: 4px;
        }

        .prod-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 8px;
        }

        .prod-card {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 11px;
            padding: 10px;
            cursor: pointer;
            transition: all .15s;
            position: relative;
            overflow: hidden;
        }

        .prod-card:hover:not(.no-stok) {
            border-color: #10b981;
            background: #f0fdf4;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(16, 185, 129, .12);
        }

        .prod-card:active:not(.no-stok) {
            transform: scale(.97);
        }

        .prod-card.no-stok {
            opacity: .45;
            cursor: not-allowed;
        }

        .prod-img {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #ecfdf5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 6px;
        }

        .prod-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .prod-name {
            font-size: 11px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.3;
            margin-bottom: 2px;
        }

        .prod-sat {
            font-size: 10px;
            color: #94a3b8;
            margin-bottom: 2px;
        }

        .prod-price {
            font-size: 12px;
            font-weight: 700;
            color: #059669;
        }

        .prod-stok {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 1px;
        }

        .prod-stok.low {
            color: #dc2626;
        }

        .cart-qty-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #059669;
            color: #fff;
            border-radius: 50%;
            width: 17px;
            height: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 700;
        }

        /* Pagination produk */
        .prod-pagination {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 12px;
            background: #fff;
            border-top: 1px solid #e2e8f0;
            gap: 8px;
        }

        .pag-info {
            font-size: 11px;
            color: #94a3b8;
        }

        .pag-btns {
            display: flex;
            gap: 4px;
        }

        .pag-btn {
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            border: 1.5px solid #e2e8f0;
            border-radius: 7px;
            background: #fff;
            color: #64748b;
            cursor: pointer;
            transition: all .12s;
        }

        .pag-btn:hover:not(:disabled) {
            border-color: #10b981;
            color: #059669;
        }

        .pag-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .pag-btn.active {
            background: #059669;
            color: #fff;
            border-color: #059669;
        }

        .pag-pages {
            display: flex;
            gap: 3px;
        }

        /* ── KANAN ── */
        .pos-right {
            display: flex;
            flex-direction: column;
            background: #fff;
            min-height: 0;
            overflow: hidden;
        }

        .cart-head {
            flex-shrink: 0;
            padding: 11px 14px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-head-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .cart-clear {
            font-size: 11px;
            color: #94a3b8;
            cursor: pointer;
            transition: color .1s;
        }

        .cart-clear:hover {
            color: #dc2626;
        }

        .pel-wrap {
            flex-shrink: 0;
            padding: 7px 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .pel-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #94a3b8;
            margin-bottom: 3px;
        }

        .pel-row {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .pel-select {
            flex: 1;
            min-width: 0;
            padding: 6px 8px;
            font-size: 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 7px;
            background: #f8fafc;
            color: #1e293b;
            outline: none;
        }

        .pel-select:focus {
            border-color: #10b981;
        }

        .btn-add-pel {
            flex-shrink: 0;
            padding: 6px 9px;
            background: #f0fdf4;
            border: 1.5px solid #10b981;
            border-radius: 7px;
            color: #059669;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all .12s;
        }

        .btn-add-pel:hover {
            background: #059669;
            color: #fff;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
        }

        .cart-items::-webkit-scrollbar {
            width: 3px;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: #d1fae5;
            border-radius: 3px;
        }

        .cart-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #94a3b8;
            font-size: 13px;
            gap: 7px;
            padding: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-bottom: 1px solid #f8fafc;
            transition: background .1s;
        }

        .cart-item:hover {
            background: #fafafa;
        }

        .ci-thumb {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: #ecfdf5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .ci-info {
            flex: 1;
            min-width: 0;
        }

        .ci-name {
            font-size: 11px;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ci-qty-row {
            display: flex;
            align-items: center;
            gap: 3px;
            margin-top: 2px;
        }

        .qty-btn {
            width: 19px;
            height: 19px;
            border-radius: 5px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #374151;
            transition: all .1s;
        }

        .qty-btn:hover {
            border-color: #10b981;
            color: #059669;
            background: #f0fdf4;
        }

        .ci-price-col {
            text-align: right;
            flex-shrink: 0;
        }

        .ci-subtotal {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
        }

        .ci-harga {
            font-size: 10px;
            color: #94a3b8;
        }

        .ci-del {
            border: none;
            background: transparent;
            cursor: pointer;
            color: #cbd5e1;
            padding: 3px;
            font-size: 11px;
            transition: color .1s;
            flex-shrink: 0;
        }

        .ci-del:hover {
            color: #dc2626;
        }

        /* Footer */
        .cart-footer {
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            flex-shrink: 0;
        }

        .footer-top {
            padding: 9px 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .f-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .f-row.total {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0;
        }

        .f-diskon-wrap {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 4px 0;
        }

        .f-diskon-label {
            font-size: 11px;
            color: #94a3b8;
            white-space: nowrap;
        }

        .f-diskon-input {
            flex: 1;
            padding: 4px 7px;
            font-size: 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            background: #fff;
            outline: none;
            text-align: right;
            color: #1e293b;
        }

        .f-diskon-input:focus {
            border-color: #10b981;
        }

        .footer-mid {
            padding: 7px 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .status-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .sb-pills {
            display: flex;
            gap: 4px;
        }

        .sb-pill {
            flex: 1;
            padding: 5px 3px;
            text-align: center;
            font-size: 10px;
            font-weight: 600;
            border: 1.5px solid #e2e8f0;
            border-radius: 7px;
            cursor: pointer;
            background: #fff;
            color: #64748b;
            transition: all .12s;
        }

        .sb-pill.active {
            background: #059669;
            color: #fff;
            border-color: #059669;
        }

        .sb-pill.amber.active {
            background: #d97706;
            border-color: #d97706;
        }

        .sb-pill.red.active {
            background: #dc2626;
            border-color: #dc2626;
        }

        .footer-bot {
            padding: 9px 14px;
        }

        .bayar-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #94a3b8;
            margin-bottom: 3px;
        }

        .bayar-input {
            width: 100%;
            padding: 8px 11px;
            font-size: 15px;
            font-weight: 700;
            border: 2px solid #10b981;
            border-radius: 9px;
            text-align: right;
            outline: none;
            background: #fff;
            color: #1e293b;
        }

        .bayar-input:focus {
            border-color: #059669;
        }

        .kem-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
        }

        .kem-label {
            font-size: 11px;
            color: #64748b;
        }

        .kem-val {
            font-size: 14px;
            font-weight: 700;
            color: #059669;
        }

        .kem-val.kurang {
            color: #dc2626;
        }

        .btn-bayar {
            width: 100%;
            margin-top: 7px;
            padding: 11px;
            border: none;
            border-radius: 10px;
            background: #059669;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background .15s;
        }

        .btn-bayar:hover:not(:disabled) {
            background: #047857;
        }

        .btn-bayar:disabled {
            background: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
        }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            background: #fff;
            border-radius: 16px;
            width: 420px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 18px;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-head-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .struk-paper {
            width: 216px;
            margin: 14px auto;
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.55;
            color: #111;
            background: #fff;
            padding: 12px 10px;
            border: 1px dashed #bbb;
            border-radius: 3px;
        }

        .sk-center {
            text-align: center;
        }

        .sk-bold {
            font-weight: 700;
        }

        .sk-muted {
            color: #555;
        }

        .sk-row {
            display: flex;
            justify-content: space-between;
        }

        .sk-divider {
            border: none;
            border-top: 1px dashed #999;
            margin: 4px 0;
        }

        .sk-total {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            font-size: 12px;
            border-top: 1px solid #000;
            padding-top: 3px;
            margin-top: 2px;
        }

        .sk-barcode {
            font-size: 26px;
            letter-spacing: -3px;
            text-align: center;
            margin: 3px 0;
            line-height: 1;
        }

        .sk-footer {
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .modal-actions {
            display: flex;
            gap: 8px;
            padding: 11px 18px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-print {
            flex: 1;
            padding: 9px;
            background: #059669;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background .12s;
        }

        .btn-print:hover {
            background: #047857;
        }

        .btn-modal-cancel {
            padding: 9px 13px;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            color: #64748b;
            cursor: pointer;
        }

        .btn-modal-cancel:hover {
            background: #f8fafc;
        }

        /* Konfirmasi */
        .confirm-box {
            background: #fff;
            border-radius: 16px;
            width: 370px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            padding: 22px;
        }

        .confirm-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #f0fdf4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 12px;
        }

        /* Form pelanggan */
        .form-group {
            margin-bottom: 11px;
        }

        .form-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            margin-bottom: 3px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 8px 10px;
            font-size: 13px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            outline: none;
            color: #1e293b;
        }

        .form-control:focus {
            border-color: #10b981;
            background: #fff;
        }

        .select2-container--default .select2-selection--single {
            height: 36px !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 7px !important;
            background: #f8fafc !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 8px !important;
            font-size: 12px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #10b981 !important;
            box-shadow: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b !important;
            line-height: normal !important;
            padding-left: 0 !important;
            padding-right: 20px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 8px !important;
        }

        .select2-dropdown {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            overflow: hidden;
        }

        .select2-search__field {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 6px !important;
            font-size: 12px !important;
        }

        .select2-results__option {
            font-size: 12px !important;
            padding: 8px 10px !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: #059669 !important;
        }
    </style>
@endpush

@section('content')
    <div class="pos-page-wrap">

        {{-- TOP BAR --}}
        <div class="pos-topbar flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-cash-register text-emerald-600"></i>  POS
                </h1>
                <nav class="text-xs text-slate-400 flex items-center gap-1">
                    <a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('transactions.index') }}" class="hover:text-emerald-600">Penjualan</a>
                    <span>/</span>
                </nav>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <i class="fa-regular fa-clock"></i>
                <span id="jamSekarang"></span>
                &nbsp;·&nbsp; {{ auth()->user()->nama }}
            </div>
        </div>

        <div class="pos-wrap">

            {{-- ══ KIRI: PRODUK ══ --}}
            <div class="pos-left">

                {{-- Search --}}
                <div class="pos-left-bar">
                    <div class="pos-search-wrap">
                        <i class="fa-solid fa-search"></i>
                        <input class="pos-search" type="text" id="produkSearch" placeholder="Cari nama / kode produk..."
                            oninput="onSearch()">
                    </div>
                    <span class="text-xs text-slate-400 whitespace-nowrap" id="produkCount"></span>
                </div>

                {{-- Barcode --}}
                <div class="barcode-bar">
                    <div class="barcode-wrap">
                        <i class="fa-solid fa-barcode"></i>
                        <input class="barcode-input" type="text" id="barcodeInput"
                            placeholder="Scan barcode produk di sini..." autocomplete="off">
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="cat-scroll" id="catContainer"></div>

                {{-- Grid --}}
                <div class="prod-scroll">
                    <div class="prod-grid" id="produkGrid"></div>
                    <div id="produkEmpty" class="hidden text-center py-10 text-slate-400 text-sm">
                        <i class="fa-solid fa-box-open text-3xl mb-2 opacity-20 block"></i>
                        Produk tidak ditemukan
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="prod-pagination" id="prodPagination">
                    <span class="pag-info" id="pagInfo"></span>
                    <div class="pag-btns">
                        <button class="pag-btn" id="pagPrev" onclick="changePage(currentPage-1)">‹ Prev</button>
                        <div class="pag-pages" id="pagPages"></div>
                        <button class="pag-btn" id="pagNext" onclick="changePage(currentPage+1)">Next ›</button>
                    </div>
                </div>
            </div>

            {{-- ══ KANAN: KERANJANG ══ --}}
            <div class="pos-right">
                <div class="cart-head">
                    <div class="cart-head-title">
                        <i class="fa-solid fa-shopping-basket text-emerald-500"></i>
                        Keranjang
                        <span id="cartBadge"
                            class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-0.5 rounded-full hidden"></span>
                    </div>
                    <span class="cart-clear" onclick="clearCart()" title="Kosongkan keranjang">
                        <i class="fa-solid fa-trash"></i>
                    </span>
                </div>

                {{-- Pelanggan --}}
                <div class="pel-wrap">
                    <div class="pel-label">
                        <i class="fa-solid fa-user"></i> Pelanggan
                    </div>

                    <div class="pel-row">
                        <select class="pel-select select2-pelanggan" id="pelangganSel">
                            <option value="">— Umum / Tanpa Pelanggan —</option>

                            @foreach ($pelanggan as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->nama }}{{ $p->telepon ? ' · ' . $p->telepon : '' }}
                                </option>
                            @endforeach
                        </select>

                        <button class="btn-add-pel" onclick="bukaModalPelanggan()">
                            <i class="fa-solid fa-plus"></i> Baru
                        </button>
                    </div>
                </div>
                {{-- Items --}}
                <div class="cart-items" id="cartItems">
                    <div class="cart-empty" id="cartEmpty">
                        <i class="fa-solid fa-cart-arrow-down" style="font-size:32px;opacity:.2;"></i>
                        <div>Pilih produk dari kiri</div>
                        <div style="font-size:11px;color:#cbd5e1;">Klik produk atau scan barcode</div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="cart-footer">
                    <div class="footer-top">
                        <div class="f-row"><span>Subtotal</span><span id="fSubtotal">Rp 0</span></div>
                        <div class="f-diskon-wrap">
                            <span class="f-diskon-label">Diskon (Rp)</span>
                            <input class="f-diskon-input" type="text" id="diskonInput" placeholder="0"
                                oninput="onDiskonInput(this)" autocomplete="off">
                        </div>
                        <div class="f-row total"><span>Total</span><span id="fTotal">Rp 0</span></div>
                    </div>

                    <div class="footer-mid">
                        <div class="status-label">Status Pembayaran</div>
                        <div class="sb-pills">
                            <div class="sb-pill active" onclick="setStatusBayar(this,'lunas')">Lunas</div>
                            <div class="sb-pill amber" onclick="setStatusBayar(this,'sebagian')">Sebagian</div>
                            <div class="sb-pill red" onclick="setStatusBayar(this,'belum_bayar')">Belum Bayar</div>
                        </div>
                    </div>

                    <div class="footer-bot">
                        <div id="bayarSection">
                            <div class="bayar-label">Jumlah Bayar (Rp)</div>
                            <input class="bayar-input" type="text" id="bayarInput" placeholder="0"
                                oninput="onBayarInput(this)" autocomplete="off">
                            <div class="kem-row">
                                <span class="kem-label">Kembalian</span>
                                <span class="kem-val" id="kembalianVal">Rp 0</span>
                            </div>
                        </div>
                        <button class="btn-bayar" id="btnBayar" disabled onclick="bukaKonfirmasi()">
                            <i class="fa-solid fa-cash-register"></i>
                            <span id="btnBayarText">Proses Pembayaran</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ MODAL KONFIRMASI ══ --}}
    <div class="modal-overlay" id="konfirmasiOverlay">
        <div class="confirm-box">
            <div class="confirm-icon"><i class="fa-solid fa-cash-register text-emerald-600"></i></div>
            <div class="text-center mb-4">
                <div class="text-base font-bold text-slate-800 mb-1">Konfirmasi Pembayaran</div>
                <div class="text-sm text-slate-500">Pastikan detail transaksi sudah benar</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-4 mb-4 text-sm space-y-2">
                <div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span class="font-semibold"
                        id="kfSubtotal">Rp 0</span></div>
                <div class="flex justify-between" id="kfDiskonRow"><span class="text-slate-500">Diskon</span><span
                        class="font-semibold text-red-500" id="kfDiskon">-</span></div>
                <div class="flex justify-between border-t pt-2"><span class="font-bold text-slate-800">Total</span><span
                        class="font-bold text-emerald-600 text-base" id="kfTotal">Rp 0</span></div>
                <div class="flex justify-between" id="kfBayarRow"><span class="text-slate-500">Bayar</span><span
                        class="font-semibold" id="kfBayar">-</span></div>
                <div class="flex justify-between" id="kfKemRow"><span class="text-slate-500">Kembalian</span><span
                        class="font-bold text-emerald-600" id="kfKem">-</span></div>
                <div class="flex justify-between border-t pt-2"><span class="text-slate-500">Status</span><span
                        class="font-semibold" id="kfStatus">-</span></div>
            </div>
            <div class="flex gap-3">
                <button class="btn-modal-cancel flex-1" onclick="tutupKonfirmasi()">Batal</button>
                <button class="btn-bayar" style="margin:0;flex:1;" onclick="prosesBayar()">
                    <i class="fa-solid fa-check"></i> Bayar Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- ══ MODAL STRUK ══ --}}
    <div class="modal-overlay" id="strukOverlay">
        <div class="modal-box">
            <div class="modal-head">
                <div class="modal-head-title"><i class="fa-solid fa-receipt text-emerald-500 mr-2"></i>Transaksi Berhasil
                </div>
                <button onclick="tutupStruk()" class="text-slate-400 hover:text-slate-600 text-xl"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div style="padding:0 18px;" id="strukWrapper"></div>
            <div class="modal-actions">
                <button class="btn-modal-cancel" onclick="tutupStruk()">Tutup</button>
                <button class="btn-print" onclick="cetakStruk()"><i class="fa-solid fa-print mr-1"></i> Cetak Struk</button>
            </div>
        </div>
    </div>

    {{-- ══ MODAL TAMBAH PELANGGAN ══ --}}
    <div class="modal-overlay" id="pelangganOverlay">
        <div class="modal-box" style="width:370px;">
            <div class="modal-head">
                <div class="modal-head-title"><i class="fa-solid fa-user-plus text-emerald-500 mr-2"></i>Tambah Pelanggan
                    Baru</div>
                <button onclick="tutupModalPelanggan()" class="text-slate-400 hover:text-slate-600 text-xl"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div style="padding:14px 18px;">
                <div class="form-group">
                    <label class="form-label">Nama <span class="text-red-500">*</span></label>
                    <input class="form-control" type="text" id="pelNama" placeholder="Nama lengkap pelanggan">
                </div>
                <div class="form-group">
                    <label class="form-label">Telepon</label>
                    <input class="form-control" type="text" id="pelTelepon" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <input class="form-control" type="text" id="pelAlamat" placeholder="Alamat (opsional)">
                </div>
                <div id="pelError" class="text-red-500 text-xs mb-2 hidden"></div>
            </div>
            <div class="modal-actions">
                <button class="btn-modal-cancel" onclick="tutupModalPelanggan()">Batal</button>
                <button class="btn-print" id="btnSimpanPel" onclick="simpanPelanggan()">
                    <i class="fa-solid fa-save mr-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>

            $(document).ready(function () {
                $('#pelangganSel').select2({
                    placeholder: '— Umum / Tanpa Pelanggan —',
                    allowClear: true,
                    dropdownAutoWidth: true
                });
            });
            /* ─── DATA ─────────────────────────────────────────────────── */
            const PRODUK_DATA = {!! json_encode(
            $produk->map(fn($p) => [
                'id' => $p->id,
                'nama' => $p->nama,
                'kode' => $p->kode,
                'satuan' => $p->satuan,
                'harga' => $p->harga_jual,
                'stok' => $p->stok_saat_ini,
                'min' => $p->stok_minimum,
                'kat' => optional($p->kategori)->nama ?? 'Lainnya',
                'foto' => $p->foto,
            ])->values()
        ) !!};

            const CSRF = '{{ csrf_token() }}';
            const KASIR = '{{ auth()->user()->nama }}';
            const PER_PAGE = 20;   // produk per halaman

            /* ─── STATE ─────────────────────────────────────────────────── */
            let cart = {};
            let statusBayar = 'lunas';
            let activeCat = 'Semua';
            let lastTrxId = null;
            let currentPage = 1;
            let filteredList = [];   // hasil filter saat ini
            let barcodeBuffer = '', barcodeTimer = null;

            /* ─── JAM ───────────────────────────────────────────────────── */
            (function tick() {
                const el = document.getElementById('jamSekarang');
                if (el) el.textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                setTimeout(tick, 1000);
            })();

            /* ─── FORMAT ─────────────────────────────────────────────────── */
            function fmt(n) { return Number(n).toLocaleString('id-ID'); }
            function parseRp(s) { return parseInt(String(s).replace(/\./g, '')) || 0; }
            function formatRpInput(el) {
                const raw = el.value.replace(/\D/g, '');
                el.value = raw ? Number(raw).toLocaleString('id-ID') : '';
            }
            function onDiskonInput(el) { formatRpInput(el); updateFooter(); }
            function onBayarInput(el) { formatRpInput(el); updateKembalian(); }

            /* ─── KATEGORI ───────────────────────────────────────────────── */
            function buildKategori() {
                const cats = ['Semua', ...new Set(PRODUK_DATA.map(p => p.kat))];
                document.getElementById('catContainer').innerHTML = cats.map(c =>
                    `<div class="cat-pill${c === activeCat ? ' active' : ''}" onclick="setCat('${c.replace(/'/g, "\\'")}')">
                                    ${c}
                                 </div>`
                ).join('');
            }

            function setCat(c) {
                activeCat = c;
                currentPage = 1;
                buildKategori();
                applyFilter();
            }

            /* ─── DRAG SCROLL KATEGORI ───────────────────────────────────── */
            (function () {
                const el = document.getElementById('catContainer');
                let down = false, startX, scrollLeft;
                el.addEventListener('mousedown', e => {
                    if (e.target.classList.contains('cat-pill')) {
                        down = true; startX = e.pageX - el.offsetLeft; scrollLeft = el.scrollLeft;
                    }
                });
                document.addEventListener('mouseup', () => down = false);
                el.addEventListener('mousemove', e => {
                    if (!down) return;
                    e.preventDefault();
                    el.scrollLeft = scrollLeft - (e.pageX - el.offsetLeft - startX);
                });
            })();

            /* ─── BARCODE ────────────────────────────────────────────────── */
            document.getElementById('barcodeInput').addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    const kode = this.value.trim();
                    this.value = '';
                    if (kode) scanByKode(kode);
                    e.preventDefault();
                }
            });

            /* Global hardware scanner */
            document.addEventListener('keydown', function (e) {
                const active = document.activeElement;
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(active.tagName) && active.id !== 'barcodeInput') return;
                if (active.id === 'barcodeInput') return;
                if (e.key === 'Enter') {
                    if (barcodeBuffer) { scanByKode(barcodeBuffer.trim()); barcodeBuffer = ''; }
                    return;
                }
                if (e.key.length === 1) {
                    clearTimeout(barcodeTimer);
                    barcodeBuffer += e.key;
                    barcodeTimer = setTimeout(() => { barcodeBuffer = ''; }, 300);
                }
            });

            function scanByKode(kode) {
                const p = PRODUK_DATA.find(x => x.kode === kode);
                if (!p) { showToast('Barcode tidak ditemukan: ' + kode, 'error'); return; }
                if (p.stok === 0) { showToast('Stok ' + p.nama + ' habis!', 'error'); return; }
                addToCart(p.id);
                showToast(p.nama + ' ditambahkan', 'success');
            }

            /* ─── FILTER + PAGINATION ────────────────────────────────────── */
            function onSearch() {
                currentPage = 1;
                applyFilter();
            }

            function applyFilter() {
                const q = document.getElementById('produkSearch').value.toLowerCase();
                filteredList = PRODUK_DATA.filter(p =>
                    (activeCat === 'Semua' || p.kat === activeCat) &&
                    (!q || p.nama.toLowerCase().includes(q) || p.kode.toLowerCase().includes(q))
                );
                renderPage();
            }

            function renderPage() {
                const total = filteredList.length;
                const totalPages = Math.max(1, Math.ceil(total / PER_PAGE));
                currentPage = Math.min(currentPage, totalPages);

                const start = (currentPage - 1) * PER_PAGE;
                const end = Math.min(start + PER_PAGE, total);
                const page = filteredList.slice(start, end);

                document.getElementById('produkCount').textContent = total + ' produk';

                /* Empty state */
                if (!total) {
                    document.getElementById('produkGrid').innerHTML = '';
                    document.getElementById('produkEmpty').classList.remove('hidden');
                    document.getElementById('prodPagination').style.display = 'none';
                    return;
                }
                document.getElementById('produkEmpty').classList.add('hidden');

                /* Render kartu */
                document.getElementById('produkGrid').innerHTML = page.map(p => {
                    const inCart = cart[p.id] ? cart[p.id].qty : 0;
                    const noStok = p.stok === 0;
                    const lowStok = p.stok > 0 && p.stok <= p.min;
                    const imgEl = p.foto ? `<img src="/storage/${p.foto}" alt="${p.nama}">` : `<span style="font-size:18px;">🌿</span>`;
                    return `
                                <div class="prod-card${noStok ? ' no-stok' : ''}"
                                     onclick="${noStok ? '' : 'addToCart(' + p.id + ')'}"
                                     title="${p.nama}">
                                    ${inCart > 0 ? `<div class="cart-qty-badge">${inCart}</div>` : ''}
                                    <div class="prod-img">${imgEl}</div>
                                    <div class="prod-name">${p.nama}</div>
                                    <div class="prod-sat">${p.satuan}</div>
                                    <div class="prod-price">Rp ${fmt(p.harga)}</div>
                                    <div class="prod-stok${noStok ? ' low' : lowStok ? ' low' : ''}">
                                        ${noStok ? '✗ Stok habis' : lowStok ? '⚠ Sisa ' + p.stok + ' ' + p.satuan : 'Stok: ' + p.stok + ' ' + p.satuan}
                                    </div>
                                </div>`;
                }).join('');

                /* Pagination bar */
                const pag = document.getElementById('prodPagination');
                if (totalPages <= 1) {
                    pag.style.display = 'none';
                    return;
                }
                pag.style.display = 'flex';
                document.getElementById('pagInfo').textContent =
                    `${start + 1}–${end} dari ${total} produk`;
                document.getElementById('pagPrev').disabled = currentPage === 1;
                document.getElementById('pagNext').disabled = currentPage === totalPages;

                /* Nomor halaman (max 5 tampil) */
                let pages = [], lo = Math.max(1, currentPage - 2), hi = Math.min(totalPages, lo + 4);
                lo = Math.max(1, hi - 4);
                for (let i = lo; i <= hi; i++) pages.push(i);
                document.getElementById('pagPages').innerHTML = pages.map(i =>
                    `<button class="pag-btn${i === currentPage ? ' active' : ''}" onclick="changePage(${i})">${i}</button>`
                ).join('');
            }

            function changePage(p) {
                const total = filteredList.length;
                const totalPages = Math.max(1, Math.ceil(total / PER_PAGE));
                if (p < 1 || p > totalPages) return;
                currentPage = p;
                renderPage();
                document.getElementById('produkGrid').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            /* ─── KERANJANG ──────────────────────────────────────────────── */
            function addToCart(id) {
                const p = PRODUK_DATA.find(x => x.id === id);
                if (!p || p.stok === 0) return;
                if (cart[id]) {
                    if (cart[id].qty >= p.stok) { showToast('Stok tidak mencukupi!', 'error'); return; }
                    cart[id].qty++;
                } else {
                    cart[id] = { ...p, qty: 1 };
                }
                renderCart();
                renderPage(); /* refresh badge di kartu */
            }

            function changeQty(id, delta) {
                if (!cart[id]) return;
                cart[id].qty += delta;
                if (cart[id].qty <= 0) delete cart[id];
                renderCart(); renderPage();
            }

            function setQty(id, val) {
                const n = parseInt(val);
                const p = PRODUK_DATA.find(x => x.id == id);
                if (!p) return;
                if (isNaN(n) || n <= 0) { delete cart[id]; }
                else { cart[id] = { ...p, qty: Math.min(n, p.stok) }; }
                renderCart(); renderPage();
            }

            function removeFromCart(id) {
                delete cart[id];
                renderCart(); renderPage();
            }

            function clearCart() {
                cart = {};
                renderCart(); renderPage();
                document.getElementById('diskonInput').value = '';
                document.getElementById('bayarInput').value = '';
                updateFooter();
            }

            function renderCart() {
                const items = Object.values(cart);
                const cartEl = document.getElementById('cartItems');
                const emptyEl = document.getElementById('cartEmpty');
                const badge = document.getElementById('cartBadge');
                const btnBayar = document.getElementById('btnBayar');
                const totalQty = items.reduce((s, c) => s + c.qty, 0);

                if (totalQty) { badge.textContent = totalQty; badge.classList.remove('hidden'); btnBayar.disabled = false; }
                else { badge.classList.add('hidden'); btnBayar.disabled = true; }

                if (!items.length) {
                    cartEl.innerHTML = ''; cartEl.appendChild(emptyEl); emptyEl.style.display = 'flex';
                    updateFooter(); return;
                }
                emptyEl.style.display = 'none';

                cartEl.innerHTML = items.map(c => `
                                <div class="cart-item">
                                    <div class="ci-thumb">
                                        ${c.foto ? `<img src="/storage/${c.foto}" style="width:28px;height:28px;object-fit:cover;border-radius:6px;" alt="">` : '🌿'}
                                    </div>
                                    <div class="ci-info">
                                        <div class="ci-name" title="${c.nama}">${c.nama}</div>
                                        <div class="ci-qty-row">
                                            <button class="qty-btn" onclick="changeQty(${c.id},-1)">−</button>
                                            <input type="number" value="${c.qty}" min="1" max="${c.stok}"
                                                style="width:34px;border:1.5px solid #e2e8f0;border-radius:5px;text-align:center;font-size:12px;font-weight:700;outline:none;padding:0;"
                                                onchange="setQty(${c.id},this.value)" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                            <button class="qty-btn" onclick="changeQty(${c.id},1)">+</button>
                                            <span style="font-size:10px;color:#94a3b8;">/${c.satuan}</span>
                                        </div>
                                    </div>
                                    <div class="ci-price-col">
                                        <div class="ci-subtotal">Rp ${fmt(c.harga * c.qty)}</div>
                                        <div class="ci-harga">@ Rp ${fmt(c.harga)}</div>
                                    </div>
                                    <button class="ci-del" onclick="removeFromCart(${c.id})"><i class="fa-solid fa-xmark"></i></button>
                                </div>`).join('');
                cartEl.appendChild(emptyEl);
                updateFooter();
            }

            function getTotal() {
                const items = Object.values(cart);
                const sub = items.reduce((s, c) => s + c.harga * c.qty, 0);
                const dis = parseRp(document.getElementById('diskonInput').value);
                const total = Math.max(0, sub - dis);
                return { sub, dis, total };
            }

            function updateFooter() {
                const { sub, dis, total } = getTotal();
                document.getElementById('fSubtotal').textContent = 'Rp ' + fmt(sub);
                document.getElementById('fTotal').textContent = 'Rp ' + fmt(total);
                updateKembalian();
            }

            function updateKembalian() {
                const { total } = getTotal();
                const bayar = Math.max(0, parseRp(document.getElementById('bayarInput').value));
                const kem = bayar - total;
                const el = document.getElementById('kembalianVal');
                el.textContent = kem >= 0 ? 'Rp ' + fmt(kem) : '− Rp ' + fmt(Math.abs(kem));
                el.className = 'kem-val' + (kem < 0 ? ' kurang' : '');
            }

            /* ─── STATUS BAYAR ───────────────────────────────────────────── */
            function setStatusBayar(el, val) {
                statusBayar = val;
                document.querySelectorAll('.sb-pill').forEach(p => p.classList.remove('active'));
                el.classList.add('active');
                document.getElementById('bayarSection').style.display = val === 'belum_bayar' ? 'none' : 'block';
            }

            /* ─── KONFIRMASI ─────────────────────────────────────────────── */
            function bukaKonfirmasi() {
                if (!Object.keys(cart).length) return;
                const {sub,dis,total}=getTotal();
                const bayar=statusBayar==='belum_bayar'?0:(parseRp(document.getElementById('bayarInput').value)||total);

                const pelangganId = document.getElementById('pelangganSel').value;

                if (
                    (statusBayar === 'sebagian' || statusBayar === 'belum_bayar') &&
                    !pelangganId
                ) {
                    showToast('Pelanggan wajib dipilih untuk transaksi piutang.', 'error');
                    return;
                }

                if (statusBayar === 'lunas' && bayar < total) {
                    showToast('Jumlah bayar kurang dari total transaksi.', 'error');
                    return;
                }

                if (statusBayar === 'sebagian' && bayar <= 0) {
                    showToast('Pembayaran sebagian harus lebih dari 0.', 'error');
                    return;
                }

                if (statusBayar === 'belum_bayar' && bayar > 0) {
                    showToast('Status belum bayar tidak boleh memiliki pembayaran.', 'error');
                    return;
                }
                const kem = Math.max(0, bayar - total);
                const sbLabel = { lunas: 'LUNAS', sebagian: 'SEBAGIAN', belum_bayar: 'BELUM DIBAYAR' };
                const sbColor = { lunas: '#059669', sebagian: '#d97706', belum_bayar: '#dc2626' };

                document.getElementById('kfSubtotal').textContent = 'Rp ' + fmt(sub);
                const kfDiskonRow = document.getElementById('kfDiskonRow');
                kfDiskonRow.style.display = dis > 0 ? 'flex' : 'none';
                if (dis > 0) document.getElementById('kfDiskon').textContent = '− Rp ' + fmt(dis);
                document.getElementById('kfTotal').textContent = 'Rp ' + fmt(total);
                document.getElementById('kfBayarRow').style.display = statusBayar === 'belum_bayar' ? 'none' : 'flex';
                document.getElementById('kfKemRow').style.display = statusBayar === 'belum_bayar' ? 'none' : 'flex';
                if (statusBayar !== 'belum_bayar') {
                    document.getElementById('kfBayar').textContent = 'Rp ' + fmt(bayar);
                    document.getElementById('kfKem').textContent = 'Rp ' + fmt(kem);
                }
                document.getElementById('kfStatus').textContent = sbLabel[statusBayar];
                document.getElementById('kfStatus').style.color = sbColor[statusBayar];
                document.getElementById('konfirmasiOverlay').classList.add('open');
            }

            function tutupKonfirmasi() { document.getElementById('konfirmasiOverlay').classList.remove('open'); }

            /* ─── PROSES BAYAR ───────────────────────────────────────────── */
            function prosesBayar() {
                tutupKonfirmasi();
                const items = Object.values(cart);
                if (!items.length) return;
                const { sub, dis, total } = getTotal();
                const bayar = statusBayar === 'belum_bayar' ? 0 : (parseRp(document.getElementById('bayarInput').value) || total);

                const btn = document.getElementById('btnBayar');
                btn.disabled = true;
                document.getElementById('btnBayarText').textContent = 'Memproses...';

                fetch('{{ route('transactions.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({
                        _token: CSRF,
                        pelanggan_id: document.getElementById('pelangganSel').value || null,
                        diskon_nominal: dis,
                        status_bayar: statusBayar,
                        jumlah_bayar: bayar,
                        items: items.map(c => ({ produk_id: c.id, jumlah: c.qty, harga_jual: c.harga, diskon_item: 0 })),
                    }),
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            lastTrxId = data.transaksi_id;
                            tampilkanStruk(data, items, sub, dis, total, bayar);
                            clearCart();
                            showToast('Transaksi berhasil! ' + data.nomor, 'success');
                        } else {
                            showToast(data.message || 'Terjadi kesalahan', 'error');
                            btn.disabled = false;
                            document.getElementById('btnBayarText').textContent = 'Proses Pembayaran';
                        }
                    })
                    .catch(() => {
                        showToast('Koneksi gagal, coba lagi.', 'error');
                        btn.disabled = false;
                        document.getElementById('btnBayarText').textContent = 'Proses Pembayaran';
                    });
            }

            const TOKO = {
                nama: "{{ $toko->nama_toko ?? 'TOKO' }}",
                 logo: "{{ $toko->logo ?? '' }}",
                alamat: "{{ $toko->alamat ?? '' }}",
                telepon: "{{ $toko->telepon ?? '' }}",
                header: `{{ $toko->header_struk ?? '' }}`,
                footer: `{{ $toko->footer_struk ?? '' }}`,
            };

            /* ─── STRUK ──────────────────────────────────────────────────── */
            function tampilkanStruk(data, items, sub, dis, total, bayar) {
                const kem = Math.max(0, bayar - total);
                const now = new Date();
                const tgl = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                const sbLabel = { lunas: 'LUNAS', sebagian: 'SEBAGIAN', belum_bayar: 'BELUM DIBAYAR' };

                const baris = items.map(c => {
                    const nama = c.nama.length > 18 ? c.nama.slice(0, 18) + '…' : c.nama;
                    return `<div style="margin-bottom:3px;">
                                <div style="font-weight:700;">${nama}</div>
                                <div class="sk-row"><span class="sk-muted">${c.qty} x ${fmt(c.harga)}</span><span>${fmt(c.harga * c.qty)}</span></div>
                            </div>`;
                }).join('');

                const headerToko = `
                    ${TOKO.logo ? `<div style="display:flex;justify-content:center;margin-bottom:4px;">
                        <img src="/storage/${TOKO.logo}" alt="Logo"
                            style="max-width:80px;max-height:60px;object-fit:contain;display:block;">
                    </div>` : ''}
                    <div class="sk-center sk-bold" style="font-size:13px;">${TOKO.nama}</div>
                    ${TOKO.alamat  ? `<div class="sk-center sk-muted" style="font-size:9px;">${TOKO.alamat}</div>`  : ''}
                    ${TOKO.telepon ? `<div class="sk-center sk-muted" style="font-size:9px;">Telp: ${TOKO.telepon}</div>` : ''}
                `;

                const footerToko = TOKO.footer
                    ? TOKO.footer.split('\n').map(l => `<div class="sk-footer">${l}</div>`).join('')
                    : `<div class="sk-footer">Barang yg dibeli tidak dpt dikembalikan</div>`;

                document.getElementById('strukWrapper').innerHTML = `
                    <div class="struk-paper" id="strukPaper">
                        ${headerToko}
                        <hr class="sk-divider">
                        <div class="sk-row"><span>Tgl:</span><span>${tgl}</span></div>
                        <div class="sk-row"><span>Pukul:</span><span>${jam}</span></div>
                        <div class="sk-row"><span>Kasir:</span><span>${KASIR}</span></div>
                        <div style="font-size:9px;">${data.nomor}</div>
                        <hr class="sk-divider">
                        ${baris}
                        <hr class="sk-divider">
                        <div class="sk-row"><span>Subtotal</span><span>${fmt(sub)}</span></div>
                        ${dis > 0 ? `<div class="sk-row"><span>Diskon</span><span>- ${fmt(dis)}</span></div>` : ''}
                        <div class="sk-total"><span>TOTAL</span><span>${fmt(total)}</span></div>
                        ${statusBayar !== 'belum_bayar' ? `<div class="sk-row"><span>Bayar</span><span>${fmt(bayar)}</span></div>` : ''}
                        ${statusBayar === 'lunas' && kem > 0 ? `<div class="sk-row sk-bold"><span>Kembali</span><span>${fmt(kem)}</span></div>` : ''}
                        <hr class="sk-divider">
                        <div class="sk-center sk-bold" style="letter-spacing:1px;">${sbLabel[statusBayar]}</div>
                        <hr class="sk-divider">
                        <div class="sk-footer">Terima kasih atas kepercayaan Anda!</div>
                        ${footerToko}
                        <div style="height:12px;"></div>
                    </div>`;

                document.getElementById('strukOverlay').classList.add('open');
            }

            function tutupStruk() {
                document.getElementById('strukOverlay').classList.remove('open');
                document.getElementById('btnBayar').disabled = true;
                document.getElementById('btnBayarText').textContent = 'Proses Pembayaran';
            }

            function cetakStruk() {
                const paper = document.getElementById('strukPaper');
                if (!paper) return;
                const w = window.open('', '_blank', 'width=320,height=720');
                w.document.write(`<!DOCTYPE html><html><head><title>Struk</title>
                            <style>
                                *{margin:0;padding:0;box-sizing:border-box;}
                                body{font-family:'Courier New',monospace;font-size:11px;width:58mm;margin:0 auto;padding:2mm;}
                                .sk-center{text-align:center;} .sk-bold{font-weight:700;} .sk-muted{color:#555;}
                                .sk-row{display:flex;justify-content:space-between;}
                                .sk-divider{border:none;border-top:1px dashed #999;margin:4px 0;}
                                .sk-total{display:flex;justify-content:space-between;font-weight:700;font-size:12px;border-top:1px solid #000;padding-top:3px;margin-top:2px;}
                                .sk-barcode{font-size:26px;letter-spacing:-3px;text-align:center;margin:3px 0;line-height:1;}
                                .sk-footer{text-align:center;font-size:10px;color:#666;}
                                @media print{body{width:58mm;}@page{size:58mm auto;margin:0;}}
                            </style></head><body>${paper.outerHTML}</body></html>`);
                w.document.close();
                setTimeout(() => { w.print(); }, 400);
            }

            /* ─── TAMBAH PELANGGAN ───────────────────────────────────────── */
            function bukaModalPelanggan() {
                document.getElementById('pelNama').value = '';
                document.getElementById('pelTelepon').value = '';
                document.getElementById('pelAlamat').value = '';
                document.getElementById('pelError').classList.add('hidden');
                document.getElementById('pelangganOverlay').classList.add('open');
                setTimeout(() => document.getElementById('pelNama').focus(), 100);
            }
            function tutupModalPelanggan() { document.getElementById('pelangganOverlay').classList.remove('open'); }

            function simpanPelanggan() {
                const nama = document.getElementById('pelNama').value.trim();
                const telepon = document.getElementById('pelTelepon').value.trim();
                const alamat = document.getElementById('pelAlamat').value.trim();
                const errEl = document.getElementById('pelError');
                if (!nama) { errEl.textContent = 'Nama pelanggan wajib diisi.'; errEl.classList.remove('hidden'); return; }

                const btn = document.getElementById('btnSimpanPel');
                btn.disabled = true; btn.textContent = 'Menyimpan...';

                fetch('{{ route('pelanggan.store') }}  ' ,{
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                            body: JSON.stringify({ nama, telepon, alamat }),
                        })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success || data.id) {
                                    const sel = document.getElementById('pelangganSel');
                                    const opt = new Option(data.nama + (data.telepon ? ' · ' + data.telepon : ''), data.id, true, true);
                                    sel.appendChild(opt); sel.value = data.id;
                                    tutupModalPelanggan();
                                    showToast('Pelanggan ' + data.nama + ' ditambahkan', 'success');
                                } else {
                                    errEl.textContent = data.message || 'Gagal menyimpan.'; errEl.classList.remove('hidden');
                                }
                            })
                            .catch(() => { errEl.textContent = 'Koneksi gagal.'; errEl.classList.remove('hidden'); })
                            .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-save mr-1"></i> Simpan'; });
                    }

                    /* ─── TOAST ──────────────────────────────────────────────────── */
                    function showToast(msg, type = 'success') {
                        const t = document.createElement('div');
                        t.style.cssText = `position:fixed;top:20px;right:20px;z-index:9999;
                                padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;
                                box-shadow:0 4px 20px rgba(0,0,0,.15);
                                background:${type === 'success' ? '#059669' : '#dc2626'};color:#fff;max-width:280px;`;
                        t.textContent = msg;
                        document.body.appendChild(t);
                        setTimeout(() => t.remove(), 3200);
                    }

                    /* ─── BACKDROP CLOSE ─────────────────────────────────────────── */
                    ['strukOverlay', 'konfirmasiOverlay', 'pelangganOverlay'].forEach(id => {
                        document.getElementById(id).addEventListener('click', function (e) {
                            if (e.target === this) this.classList.remove('open');
                        });
                    });

                    /* ─── INIT ───────────────────────────────────────────────────── */
                    buildKategori();
                    applyFilter();
                    renderCart();
                </script>
    @endpush
@endsection