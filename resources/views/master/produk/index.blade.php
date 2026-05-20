@extends('layouts.app')

@section('title', 'Produk')@section('page-title', 'Produk')

@push('styles')
    <style>
        .input-icon-wrap {
            position: relative;
        }

        .input-icon-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 13px;
        }

        .input-icon-wrap input,
        .input-icon-wrap select {
            padding-left: 36px !important;
        }

        .input-icon-wrap.prefix-rp span {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
        }

        .input-icon-wrap.prefix-rp input {
            padding-left: 36px !important;
        }

        .section-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 8px 0 4px;
        }

        .section-divider span {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #059669;
            white-space: nowrap;
        }

        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #d1fae5;
        }

        .stok-awal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .filter-bar select {
            border: 1.5px solid #d1fae5;
            border-radius: 8px;
            padding: 6px 32px 6px 10px;
            font-size: 13px;
            color: #374151;
            background: white;
            outline: none;
            cursor: pointer;
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
                <h1 class="text-2xl font-semibold text-slate-800">Produk</h1>
                <nav class="text-sm text-slate-500 mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-slate-700 font-medium">Produk</li>
                    </ol>
                </nav>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="hapusSemua()"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition flex items-center gap-2 text-sm font-semibold">
                    <i class="fa-solid fa-trash-can"></i> Hapus Semua
                </button>
                <button onclick="openCreateModal()"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center gap-2 text-sm font-semibold">
                    <i class="fa-solid fa-plus"></i> Tambah Produk
                </button>
                <button onclick="openImportModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 text-sm font-semibold">
                    <i class="fa-solid fa-file-import"></i> Import Produk
                </button>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div
            class="filter-bar flex flex-wrap items-center gap-3 mb-4 p-4 bg-emerald-50/60 rounded-xl border border-emerald-100">
            <div class="flex items-center gap-2 text-sm font-semibold text-emerald-700">
                <i class="fa-solid fa-filter"></i> Filter:
            </div>

            <select id="filterKategori">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $kat)
                    <option value="{{ $kat->nama }}">{{ $kat->nama }}</option>
                @endforeach
            </select>

            <select id="filterSupplier">
                <option value="">Semua Supplier</option>
                @foreach ($supplier as $sup)
                    <option value="{{ $sup->nama }}">{{ $sup->nama }}</option>
                @endforeach
            </select>

            <select id="filterStatus">
                <option value="">Semua Status</option>
                <option value="Aktif">Aktif</option>
                <option value="Non-aktif">Non-aktif</option>
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
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Nama Produk</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Supplier</th>
                        <th class="px-4 py-3 text-left">Satuan</th>
                        <th class="px-4 py-3 text-right">Harga Jual</th>
                        <th class="px-4 py-3 text-center">Stok</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════════ MODAL ═══════════════════════════════ --}}
    <div id="produkModal"
        class="fixed inset-0 hidden bg-black/40 modal-blur-overlay flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[92vh] flex flex-col">

            {{-- MODAL HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <div>
                        <h2 id="modalTitle" class="text-base font-bold text-slate-800">Tambah Produk</h2>
                        <p id="modalSubtitle" class="text-xs text-slate-400">Isi semua informasi produk dengan lengkap</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- MODAL BODY --}}
            <form id="produkForm" method="POST" enctype="multipart/form-data" class="overflow-y-auto flex-1">
                @csrf
                <input type="hidden" name="_method" id="methodField">

                <div class="p-6 space-y-5">

                    {{-- ── SEKSI: INFO DASAR ── --}}
                    <div class="section-divider"><span><i class="fa-solid fa-box mr-1"></i> Informasi Dasar</span></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- NAMA --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-box-open"></i>
                                <input type="text" name="nama" id="inputNama" required placeholder="Contoh: Pupuk Urea 50kg"
                                    class="w-full py-2.5 pr-4 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                        </div>

                        {{-- KATEGORI --}}
                        <div>
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-layer-group"></i>
                                <select name="kategori_id" id="inputKategori" required
                                    class="w-full py-2.5 pr-4 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none bg-white">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategori as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- SUPPLIER --}}
                        <div>
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                                Supplier
                            </label>
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-truck-field"></i>
                                <select name="supplier_id" id="inputSupplier"
                                    class="w-full py-2.5 pr-4 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none bg-white">
                                    <option value="">-- Tidak Ada --</option>
                                    @foreach ($supplier as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- SATUAN --}}
                        <div>
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                                Satuan <span class="text-red-500">*</span>
                            </label>
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-ruler"></i>
                                <input type="text" name="satuan" id="inputSatuan" required
                                    placeholder="kg, liter, botol, karung..."
                                    class="w-full py-2.5 pr-4 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                        </div>

                        {{-- HARGA JUAL --}}
                        <div>
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                                Harga Jual <span class="text-red-500">*</span>
                            </label>
                            <div class="input-icon-wrap prefix-rp">
                                <span>Rp</span>
                                <input type="number" name="harga_jual" id="inputHarga" required min="0" placeholder="0"
                                    class="w-full py-2.5 pr-4 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                        </div>
                    </div>

                    {{-- ── SEKSI: STOK ── --}}
                    <div class="section-divider"><span><i class="fa-solid fa-cubes mr-1"></i> Pengaturan Stok</span></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- STOK MINIMUM --}}
                        <div>
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                                Stok Minimum <span class="text-red-500">*</span>
                            </label>
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-arrow-down-to-line text-amber-500"></i>
                                <input type="number" name="stok_minimum" id="inputStokMin" required min="0" value="5"
                                    class="w-full py-2.5 pr-4 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                Sistem akan memberi peringatan jika stok di bawah angka ini.
                            </p>
                        </div>

                        {{-- STOK AWAL (hanya saat tambah) --}}
                        <div id="stokAwalField">
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                                Stok Awal
                                <span class="stok-awal-badge ml-2">
                                    <i class="fa-solid fa-bolt"></i> Input Sekali
                                </span>
                            </label>
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-warehouse text-emerald-500"></i>
                                <input type="number" name="stok_awal" id="inputStokAwal" min="0" value="0"
                                    class="w-full py-2.5 pr-4 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                Jumlah stok yang sudah ada sebelum sistem digunakan. Setelah disimpan, tambah stok lewat
                                menu <strong>Barang Masuk</strong>.
                            </p>
                        </div>

                        {{-- HARGA MODAL AWAL (hanya saat tambah) --}}
                        <div id="hargaModalAwalField" class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                                Harga Modal Stok Awal
                            </label>
                            <div class="input-icon-wrap prefix-rp">
                                <span>Rp</span>
                                <input type="number" name="harga_modal_awal" id="inputHargaModalAwal" min="0" value="0"
                                    class="w-full py-2.5 pr-4 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                Estimasi harga beli per satuan untuk stok awal ini. Digunakan untuk perhitungan HPP (FIFO).
                            </p>
                        </div>

                        {{-- STATUS (hanya saat edit) --}}
                        <div id="statusField" class="hidden">
                            <label
                                class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">Status</label>
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-toggle-on text-emerald-500"></i>
                                <select name="is_aktif" id="inputStatus"
                                    class="w-full py-2.5 pr-4 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none bg-white">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ── SEKSI: FOTO & CATATAN ── --}}
                    <div class="section-divider"><span><i class="fa-solid fa-image mr-1"></i> Foto & Catatan</span></div>

                    <div class="grid grid-cols-1 gap-4">
                        {{-- FOTO --}}
                        <div>
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">
                                Foto Produk
                                <span class="text-slate-400 font-normal normal-case tracking-normal ml-1">(jpg, png, webp ·
                                    maks 2MB)</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <div id="fotoWrapper"
                                    class="hidden w-16 h-16 rounded-xl border-2 border-emerald-200 overflow-hidden flex-shrink-0">
                                    <img id="fotoPreview" src="" alt="" class="w-full h-full object-cover">
                                </div>
                                <div
                                    class="flex-1 border-2 border-dashed border-slate-200 rounded-xl p-3 hover:border-emerald-400 transition">
                                    <input type="file" name="foto" id="inputFoto"
                                        accept="image/jpg,image/jpeg,image/png,image/webp"
                                        class="text-sm text-slate-500 w-full
                                                                                           file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0
                                                                                           file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700
                                                                                           hover:file:bg-emerald-100 cursor-pointer">
                                </div>
                            </div>
                        </div>

                        {{-- CATATAN --}}
                        <div>
                            <label
                                class="text-xs font-bold text-slate-600 uppercase tracking-wide block mb-1">Catatan</label>
                            <div class="relative">
                                <i class="fa-solid fa-note-sticky absolute left-3 top-3 text-slate-400 text-xs"></i>
                                <textarea name="catatan" id="inputCatatan" rows="2"
                                    placeholder="Keterangan tambahan tentang produk ini (opsional)..."
                                    class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                                                                       focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL FOOTER --}}
                <div class="px-6 py-4 border-t bg-slate-50/80 rounded-b-2xl flex justify-end gap-2 flex-shrink-0">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                        <i class="fa-solid fa-xmark mr-1"></i> Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="barcodeModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl border w-full max-w-sm">

            <div class="bg-slate-700 px-5 py-3 rounded-t-2xl flex justify-between items-center text-white">
                <h3 class="font-bold flex items-center gap-2">
                    <i class="fa-solid fa-barcode"></i> Cetak Label Barcode
                </h3>
                <button onclick="closeBarcodeModal()" class="hover:rotate-90 transition-transform">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <label class="text-xs font-semibold text-slate-600">Jumlah Cetak:</label>
                    <input type="number" id="printQty" value="1" min="1" max="99"
                        class="w-20 border border-slate-300 rounded-lg px-3 py-1.5 text-sm text-center">
                </div>

                <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 bg-slate-50 flex justify-center">
                    <div id="barcodePreview" class="text-center"></div>
                </div>

                <div class="flex gap-2 mt-4">
                    <button onclick="closeBarcodeModal()"
                        class="flex-1 py-2 border border-slate-300 rounded-xl text-sm font-semibold hover:bg-slate-100">
                        Batal
                    </button>
                    <button onclick="doPrint()"
                        class="flex-1 py-2 bg-slate-700 text-white rounded-xl text-sm font-semibold hover:bg-slate-800 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="importModal"
        class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl flex flex-col overflow-hidden">

            {{-- HEADER --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3 text-white">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-file-import"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-base">Import Produk</h2>
                        <p class="text-xs text-blue-100">Upload file Excel untuk menambah produk sekaligus</p>
                    </div>
                </div>
                <button onclick="closeImportModal()" class="text-white/70 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-5">

                {{-- STEP 1 --}}
                <div class="flex gap-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <div
                        class="flex-shrink-0 w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                        1
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-sm">Download Template</p>
                        <a href="{{ route('produk.template') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition">
                            <i class="fa-solid fa-download"></i> Download Template (.xlsx)
                        </a>
                    </div>
                </div>

                {{-- DIVIDER --}}
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <span class="text-xs text-slate-400 font-medium">lalu</span>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>

                {{-- STEP 2 --}}
                <div class="flex gap-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div
                        class="flex-shrink-0 w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                        2
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-sm">Upload File yang Sudah Diisi</p>
                        <p class="text-xs text-slate-500 mt-0.5 mb-3">
                            Format: <strong>.xlsx</strong> atau <strong>.xls</strong>. Maksimal <strong>5MB</strong>.
                            Kategori baru akan dibuat otomatis.
                        </p>

                        <form id="importForm" method="POST" action="{{ route('produk.import') }}"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- Drop zone --}}
                            <label for="file_import"
                                class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-blue-300 hover:border-blue-500 bg-white rounded-xl p-5 cursor-pointer transition group">
                                <div id="importIconWrap"
                                    class="w-12 h-12 rounded-full bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center transition">
                                    <i class="fa-solid fa-cloud-arrow-up text-blue-500 text-xl"></i>
                                </div>
                                <div id="importFileLabel" class="text-center">
                                    <p class="text-sm font-semibold text-slate-700">Klik untuk pilih file</p>
                                    <p class="text-xs text-slate-400 mt-0.5">atau seret file ke sini</p>
                                </div>
                                <input type="file" name="file_import" id="file_import" accept=".xlsx,.xls,.csv"
                                    class="hidden">
                            </label>

                            {{-- Info tips --}}
                            <div
                                class="mt-3 flex items-start gap-2 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                                <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0"></i>
                                <span>Pastikan nama supplier sudah terdaftar di sistem. Jika kosong, produk tetap diimpor
                                    tanpa supplier.</span>
                            </div>

                            <div class="flex gap-2 mt-4">
                                <button type="button" onclick="closeImportModal()"
                                    class="flex-1 py-2.5 border border-slate-300 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                                    Batal
                                </button>
                                <button type="submit" id="importSubmitBtn"
                                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 disabled:opacity-60"
                                    disabled>
                                    <i class="fa-solid fa-file-import"></i> Import Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
        <script>
            $(document).ready(function () {

                // ── DataTable ──────────────────────────────────────────────────
                let productCache = {};

                const table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route("produk.datatable") }}',
                        data: function (d) {
                            // kirim filter custom ke server
                            d.kategori = $('#filterKategori').val();
                            d.supplier = $('#filterSupplier').val();
                            d.status = $('#filterStatus').val();
                        }
                    },
                    columns: [
                        {
                            data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false,
                            className: 'px-4 py-3 text-slate-400 text-xs'
                        },
                        {
                            data: 'kode', name: 'kode',
                            render: d => `<span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded">${d}</span>`
                        },
                        { data: 'foto_nama', name: 'nama', orderable: true },
                        { data: 'kategori_nama', name: 'kategori_nama' },
                        { data: 'supplier_nama', name: 'supplier_nama' },
                        { data: 'satuan', name: 'satuan' },
                        { data: 'harga_jual_fmt', name: 'harga_jual', className: 'text-right font-semibold text-slate-800' },
                        { data: 'stok_badge', name: 'stok_saat_ini', className: 'text-center' },
                        { data: 'status_badge', name: 'is_aktif', className: 'text-center' },
                        {
                            data: 'aksi', name: 'aksi', orderable: false, searchable: false,
                            className: 'text-center'
                        },
                    ],
                    language: {
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        paginate: { previous: 'Sebelumnya', next: 'Selanjutnya' },
                        zeroRecords: 'Data tidak ditemukan',
                        processing: 'Memuat...',
                    },
                    drawCallback: function () {
                        this.api().rows().data().each(function (row) {
                            productCache[row.id] = row;
                        });
                    }
                });

                $('#filterKategori, #filterSupplier, #filterStatus').on('change', function () {
                    table.draw();
                });

                window.resetFilter = function () {
                    $('#filterKategori, #filterSupplier, #filterStatus').val('');
                    table.draw();
                };

                window.hapusSemua = function () {
                    Swal.fire({
                        title: 'Hapus Semua Produk?',
                        html: `<span class="text-red-600 font-semibold">Semua produk, stok batch, dan barang masuk akan dihapus permanen.</span><br><small class="text-slate-500">Aksi ini tidak dapat dibatalkan.</small>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonText: 'Batal',
                        confirmButtonText: 'Ya, hapus semua',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const f = document.createElement('form')
                            f.method = 'POST'
                            f.action = `/produk/hapus-semua`
                            f.innerHTML = `
                                                                                                                    <input type="hidden" name="_token" value="${$('meta[name=csrf-token]').attr('content')}">
                                                                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                                                                `
                            document.body.appendChild(f)
                            f.submit()
                        }
                    })
                }

                // ── Modal helpers ──────────────────────────────────────────────
                const modal = $('#produkModal')
                const form = $('#produkForm')
                const title = $('#modalTitle')
                const subtitle = $('#modalSubtitle')
                const method = $('#methodField')
                const btn = $('#submitBtn')
                const statusField = $('#statusField')
                const stokAwalField = $('#stokAwalField')
                const hargaModalField = $('#hargaModalAwalField')
                const fotoWrapper = $('#fotoWrapper')
                const fotoPreview = $('#fotoPreview')

                window.openCreateModal = function () {
                    title.text('Tambah Produk')
                    subtitle.text('Isi semua informasi produk dengan lengkap')
                    form.attr('action', '/produk')
                    method.val('')
                    form[0].reset()
                    fotoWrapper.addClass('hidden')
                    fotoPreview.attr('src', '')
                    statusField.addClass('hidden')
                    stokAwalField.removeClass('hidden')
                    hargaModalField.removeClass('hidden')
                    modal.removeClass('hidden')
                    $('#inputNama').focus()
                }

                window.openEditModal = function (data) {
                    title.text('Edit Produk')
                    subtitle.text('Kode: ' + data.kode)
                    form.attr('action', `/produk/${data.id}`)
                    method.val('PUT')
                    form[0].reset()

                    $('#inputNama').val(data.nama)
                    $('#inputKategori').val(data.kategori_id)
                    $('#inputSupplier').val(data.supplier_id ?? '')
                    $('#inputSatuan').val(data.satuan)
                    $('#inputHarga').val(data.harga_jual)
                    $('#inputStokMin').val(data.stok_minimum)
                    $('#inputStatus').val(data.is_aktif ? '1' : '0')
                    $('#inputCatatan').val(data.catatan ?? '')

                    if (data.foto) {
                        fotoPreview.attr('src', '/storage/' + data.foto)
                        fotoWrapper.removeClass('hidden')
                    } else {
                        fotoWrapper.addClass('hidden')
                    }

                    // Sembunyikan field stok awal saat edit
                    stokAwalField.addClass('hidden')
                    hargaModalField.addClass('hidden')
                    statusField.removeClass('hidden')
                    modal.removeClass('hidden')
                    $('#inputNama').focus()
                }

                window.closeModal = function () {
                    modal.addClass('hidden')
                }

                // Preview foto
                $('#inputFoto').on('change', function () {
                    const file = this.files[0]
                    if (file) {
                        const reader = new FileReader()
                        reader.onload = e => {
                            fotoPreview.attr('src', e.target.result)
                            fotoWrapper.removeClass('hidden')
                        }
                        reader.readAsDataURL(file)
                    }
                })

                // Tutup modal saat klik backdrop
                modal.on('click', function (e) {
                    if ($(e.target).is(modal)) closeModal()
                })

                form.on('submit', function () {
                    btn.prop('disabled', true).html(
                        '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...')
                })

                // ── Delete ─────────────────────────────────────────────────────
                window.deleteProduk = function (id, nama) {
                    Swal.fire({
                        title: 'Hapus Produk?',
                        html: `Produk <strong>${nama}</strong> akan dihapus permanen.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonText: 'Batal',
                        confirmButtonText: 'Ya, hapus',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const f = document.createElement('form')
                            f.method = 'POST'
                            f.action = `/produk/${id}`
                            f.innerHTML = `
                                                                                                                    <input type="hidden" name="_token" value="${$('meta[name=csrf-token]').attr('content')}">
                                                                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                                                                `
                            document.body.appendChild(f)
                            f.submit()
                        }
                    })
                }

                let currentBarcodeProduct = null;

                window.printBarcode = function (id) {
                    const p = productData[id];
                    currentBarcodeProduct = p;

                    const preview = document.getElementById('barcodePreview');
                    preview.innerHTML = '';

                    document.getElementById('barcodeModal').classList.remove('hidden');

                    const svg = document.createElement('svg');
                    svg.id = 'svgPreview';
                    svg.style.width = '100%';
                    svg.style.display = 'block';
                    svg.style.margin = '0 auto';
                    preview.appendChild(svg);

                    preview.insertAdjacentHTML('beforeend',
                        '<p style="font-size:11px;font-weight:bold;margin-top:4px;">' + p.kode + '</p>' +
                        '<p style="font-size:11px;margin-top:2px;">' + p.nama + '</p>' +
                        '<p style="font-size:12px;font-weight:bol;margin-top:4px;">Rp ' +
                        new Intl.NumberFormat('id-ID').format(p.harga_jual) +
                        '</p>'
                    );

                    setTimeout(function () {
                        JsBarcode(document.getElementById('svgPreview'), p.kode, {
                            format: "CODE128",
                            width: 1,
                            height: 40,
                            displayValue: false,
                            margin: 2
                        });
                    }, 100);
                };

                window.closeBarcodeModal = function () {
                    document.getElementById('barcodeModal').classList.add('hidden');
                    currentBarcodeProduct = null;
                };

                window.doPrint = function () {
                    const qty = parseInt(document.getElementById('printQty').value) || 1;
                    const p = currentBarcodeProduct;

                    const tmpSvg = document.createElement('svg');
                    JsBarcode(tmpSvg, p.kode, {
                        format: "CODE128",
                        width: 1,
                        height: 45,
                        displayValue: false,
                        margin: 2
                    });

                    const labelHtml =
                        '<div class="label">' +
                        tmpSvg.outerHTML +
                        '<div class="code">' + p.kode + '</div>' +
                        '<div class="name">' + p.nama + '</div>' +
                        '<div class="price">Rp ' + new Intl.NumberFormat('id-ID').format(p.harga_jual) + '</div>' +
                        '</div>';

                    const win = window.open('', '_blank');
                    win.document.write(
                        '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Label Barcode</title>' +
                        '<style>' +
                        '* { margin:0; padding:0; box-sizing:border-box; }' +
                        '@page { size: 58mm auto; margin: 2mm; }' +
                        'body { font-family: Arial, sans-serif; width: 54mm; }' +
                        '.label { width:54mm; padding:1mm; text-align:center; page-break-after:always; }' +
                        'svg { width:50mm; height:auto; display:block; margin:4px auto 0; }' +
                        '.code  { font-size:8pt; font-weight:bold; margin-top:1mm; letter-spacing:1px; }' +
                        '.name  { font-size:8pt; font-weight:bold; margin-top:1mm; }' +
                        '.price { font-size:10pt; font-weight:bold; margin-top:1mm; }' +
                        '</style></head><body>' +
                        labelHtml.repeat(qty) +
                        '<script>window.onload=function(){ window.print(); window.close(); }<\/script>' +
                        '</body></html>'
                    );
                    win.document.close();
                };
            })

            // ── Import Modal ───────────────────────────────────────────
            window.openImportModal = function () {
                document.getElementById('importModal').classList.remove('hidden');
            }
            window.closeImportModal = function () {
                document.getElementById('importModal').classList.add('hidden');
                document.getElementById('importForm').reset();
                document.getElementById('importFileLabel').innerHTML =
                    '<p class="text-sm font-semibold text-slate-700">Klik untuk pilih file</p>' +
                    '<p class="text-xs text-slate-400 mt-0.5">atau seret file ke sini</p>';
                document.getElementById('importIconWrap').innerHTML =
                    '<i class="fa-solid fa-cloud-arrow-up text-blue-500 text-xl"></i>';
                document.getElementById('importSubmitBtn').disabled = true;
            }

            document.getElementById('file_import').addEventListener('change', function () {
                const file = this.files[0];
                const btn = document.getElementById('importSubmitBtn');
                if (file) {
                    document.getElementById('importFileLabel').innerHTML =
                        '<p class="text-sm font-semibold text-blue-700">' + file.name + '</p>' +
                        '<p class="text-xs text-slate-400 mt-0.5">' + (file.size / 1024).toFixed(1) + ' KB</p>';
                    document.getElementById('importIconWrap').innerHTML =
                        '<i class="fa-solid fa-file-excel text-emerald-500 text-xl"></i>';
                    btn.disabled = false;
                } else {
                    btn.disabled = true;
                }
            });

            document.getElementById('importForm').addEventListener('submit', function () {
                const btn = document.getElementById('importSubmitBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengimpor...';
            });

            // Tutup modal import saat klik backdrop
            document.getElementById('importModal').addEventListener('click', function (e) {
                if (e.target === this) closeImportModal();
            });
        </script>
    @endpush
@endsection