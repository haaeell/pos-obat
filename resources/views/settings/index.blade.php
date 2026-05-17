{{-- resources/views/settings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Konfigurasi Toko')

@section('content')
    <div class="mx-auto">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Konfigurasi Toko</h1>
                <nav class="text-sm text-slate-500 mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-slate-700 font-medium">Konfigurasi Toko</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
            <div
                class="mb-5 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
                <i class="fa-solid fa-circle-check text-base"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <i class="fa-solid fa-circle-xmark text-base"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- ── LEFT: LOGO ── --}}
                <div class="lg:col-span-1 flex flex-col gap-5">

                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                <i class="fa-solid fa-image text-emerald-600 text-sm"></i>
                            </div>
                            <span class="font-semibold text-slate-700">Logo Toko</span>
                        </div>
                        <div class="p-5 flex flex-col items-center gap-4">

                            {{-- Preview --}}
                            <div id="logoPreviewWrapper"
                                class="w-36 h-36 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden">
                                @if($pengaturan->logo)
                                    <img id="logoPreview" src="{{ Storage::url($pengaturan->logo) }}" alt="Logo"
                                        class="w-full h-full object-contain p-2">
                                @else
                                    <div id="logoPlaceholder" class="flex flex-col items-center gap-2 text-slate-300">
                                        <i class="fa-solid fa-store text-4xl"></i>
                                        <span class="text-xs">Belum ada logo</span>
                                    </div>
                                @endif
                            </div>

                            <div class="w-full flex flex-col gap-2">
                                <label for="logo"
                                    class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
                                    <i class="fa-solid fa-upload text-xs"></i>
                                    Pilih Gambar
                                </label>
                                <input type="file" id="logo" name="logo" accept="image/*" class="hidden">

                                @if($pengaturan->logo)
                                    <button type="button" id="btnHapusLogo"
                                        class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                        Hapus Logo
                                    </button>
                                @endif
                            </div>

                            <p class="text-xs text-slate-400 text-center">JPG, PNG, WebP. Maks 2 MB.<br>Disarankan ukuran
                                256×256 px.</p>
                        </div>
                    </div>



                </div>

                {{-- ── RIGHT: DETAIL & STRUK ── --}}
                <div class="lg:col-span-2 flex flex-col gap-5">

                    {{-- Identitas Toko --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                <i class="fa-solid fa-store text-emerald-600 text-sm"></i>
                            </div>
                            <span class="font-semibold text-slate-700">Identitas Toko</span>
                        </div>
                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Nama Toko --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                                    Nama Toko <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-store absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                    <input type="text" name="nama_toko"
                                        value="{{ old('nama_toko', $pengaturan->nama_toko) }}"
                                        placeholder="Contoh: Warung Maju Jaya"
                                        class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition @error('nama_toko') border-red-400 @enderror">
                                </div>
                                @error('nama_toko')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Telepon --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                                    Telepon
                                </label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                    <input type="text" name="telepon" value="{{ old('telepon', $pengaturan->telepon) }}"
                                        placeholder="08xx-xxxx-xxxx"
                                        class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition @error('telepon') border-red-400 @enderror">
                                </div>
                                @error('telepon')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                                    Email
                                </label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                    <input type="email" name="email" value="{{ old('email', $pengaturan->email) }}"
                                        placeholder="toko@email.com"
                                        class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition @error('email') border-red-400 @enderror">
                                </div>
                                @error('email')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                                    Alamat
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-location-dot absolute left-3 top-3 text-slate-400 text-sm"></i>
                                    <textarea name="alamat" rows="3"
                                        placeholder="Jl. Contoh No. 1, Kelurahan, Kecamatan, Kota"
                                        class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition resize-none @error('alamat') border-red-400 @enderror">{{ old('alamat', $pengaturan->alamat) }}</textarea>
                                </div>
                                @error('alamat')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('home') }}"
                            class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-colors">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        const logoInput = document.getElementById('logo');
        const previewWrap = document.getElementById('logoPreviewWrapper');

        logoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({ icon: 'error', title: 'File terlalu besar', text: 'Ukuran logo maksimal 2 MB.', confirmButtonColor: '#059669' });
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                previewWrap.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain p-2" alt="Preview logo">`;
            };
            reader.readAsDataURL(file);
        });

        const btnHapus = document.getElementById('btnHapusLogo');
        if (btnHapus) {
            btnHapus.addEventListener('click', function () {
                Swal.fire({
                    title: 'Hapus logo?',
                    text: 'Logo toko akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, hapus',
                }).then(async result => {
                    if (!result.isConfirmed) return;

                    const res = await fetch('{{ route("settings.delete-logo") }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });
                    const json = await res.json();

                    if (json.success) {
                        previewWrap.innerHTML = `
                                        <div class="flex flex-col items-center gap-2 text-slate-300">
                                            <i class="fa-solid fa-store text-4xl"></i>
                                            <span class="text-xs">Belum ada logo</span>
                                        </div>`;
                        btnHapus.remove();
                        Swal.fire({ icon: 'success', title: 'Logo dihapus', timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: json.message });
                    }
                });
            });
        }

        function livePreview(inputId, previewId, fallback) {
            const el = document.querySelector(`[name="${inputId}"]`);
            const pv = document.getElementById(previewId);
            if (!el || !pv) return;
            el.addEventListener('input', () => { pv.textContent = el.value.trim() || fallback; });
        }

        livePreview('nama_toko', 'prev-nama', 'Nama Toko');
        livePreview('alamat', 'prev-alamat', 'Alamat toko');
        livePreview('telepon', 'prev-telp', 'No. Telepon');
        livePreview('header_struk', 'prev-header', '— header struk —');
        livePreview('footer_struk', 'prev-footer', '— footer struk —');
    </script>
@endpush