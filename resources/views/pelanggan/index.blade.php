@extends('layouts.app')

@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')

@section('content')
    <div>
        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-users text-emerald-600"></i> Data Pelanggan
                </h1>
                <nav class="text-sm text-slate-400 mt-0.5 flex items-center gap-1">
                    <a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Pelanggan</span>
                </nav>
            </div>
            <button onclick="bukaModal()"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition">
                <i class="fa-solid fa-plus"></i> Tambah Pelanggan
            </button>
        </div>

        {{-- FLASH --}}
        @if (session('success'))
            <div
                class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl flex items-center gap-2">
                <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
            </div>
        @endif

        {{-- FILTER --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-4">
            <form method="GET" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-48">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, telepon, alamat..."
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg outline-none focus:border-emerald-400 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Status</label>
                    <select name="status"
                        class="px-3 py-2 text-sm border border-slate-200 rounded-lg outline-none focus:border-emerald-400 bg-slate-50">
                        <option value="">Semua</option>
                        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Non-aktif</option>
                    </select>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-search mr-1"></i> Cari
                </button>
                <a href="{{ route('pelanggan.index') }}"
                    class="px-4 py-2 bg-slate-100 text-slate-600 text-sm font-semibold rounded-lg hover:bg-slate-200 transition">
                    Reset
                </a>
            </form>
        </div>

        {{-- TABEL --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wide">
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Telepon</th>
                            <th class="px-4 py-3 text-left">Alamat</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pelanggan as $i => $p)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 text-slate-400 text-xs">{{ $pelanggan->firstItem() + $i }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $p->nama }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $p->telepon ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-500 max-w-xs truncate">{{ $p->alamat ?? '—' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($p->is_aktif)
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-500 text-xs font-semibold rounded-full">
                                            Non-aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            onclick="bukaEdit({{ $p->id }}, '{{ addslashes($p->nama) }}', '{{ $p->telepon }}', '{{ addslashes($p->alamat) }}', '{{ addslashes($p->catatan) }}')"
                                            class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 font-semibold rounded-lg hover:bg-blue-100 transition">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form method="POST" action="{{ route('pelanggan.destroy', $p->id) }}"
                                            onsubmit="return confirm('Hapus pelanggan {{ $p->nama }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-xs px-3 py-1.5 bg-red-50 text-red-600 font-semibold rounded-lg hover:bg-red-100 transition">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-users text-3xl mb-2 opacity-20 block"></i>
                                    Belum ada data pelanggan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pelanggan->hasPages())
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $pelanggan->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div class="fixed inset-0 bg-black/50 z-50 items-center justify-center hidden" id="modalTambah" style="display:none;">
        <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                <div class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-emerald-500"></i>
                    <span id="modalTitle">Tambah Pelanggan</span>
                </div>
                <button onclick="tutupModal()" class="text-slate-400 hover:text-slate-600 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="formPelanggan" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="inputNama" required
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl outline-none focus:border-emerald-400 bg-slate-50 focus:bg-white transition"
                            placeholder="Nama lengkap pelanggan">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Telepon</label>
                        <input type="text" name="telepon" id="inputTelepon"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl outline-none focus:border-emerald-400 bg-slate-50 focus:bg-white transition"
                            placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Alamat</label>
                        <input type="text" name="alamat" id="inputAlamat"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl outline-none focus:border-emerald-400 bg-slate-50 focus:bg-white transition"
                            placeholder="Alamat pelanggan">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Catatan</label>
                        <input type="text" name="catatan" id="inputCatatan"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl outline-none focus:border-emerald-400 bg-slate-50 focus:bg-white transition"
                            placeholder="Catatan tambahan (opsional)">
                    </div>
                </div>
                <div class="flex gap-3 px-5 pb-5">
                    <button type="button" onclick="tutupModal()"
                        class="flex-1 py-2.5 border border-slate-200 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition">
                        <i class="fa-solid fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const modal = document.getElementById('modalTambah');

            function bukaModal() {
                document.getElementById('modalTitle').textContent = 'Tambah Pelanggan';
                document.getElementById('formPelanggan').action = '{{ route('pelanggan.store') }}';
                document.getElementById('methodField').innerHTML = '';
                document.getElementById('inputNama').value = '';
                document.getElementById('inputTelepon').value = '';
                document.getElementById('inputAlamat').value = '';
                document.getElementById('inputCatatan').value = '';
                modal.style.display = 'flex';
                setTimeout(() => document.getElementById('inputNama').focus(), 50);
            }

            function bukaEdit(id, nama, telepon, alamat, catatan) {
                document.getElementById('modalTitle').textContent = 'Edit Pelanggan';
                document.getElementById('formPelanggan').action = `/pelanggan/${id}`;
                document.getElementById('methodField').innerHTML = '@csrf @method("PUT")'.replace(
                    '@csrf', '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
                ).replace('@method("PUT")', '<input type="hidden" name="_method" value="PUT">');
                document.getElementById('inputNama').value = nama;
                document.getElementById('inputTelepon').value = telepon || '';
                document.getElementById('inputAlamat').value = alamat || '';
                document.getElementById('inputCatatan').value = catatan || '';
                modal.style.display = 'flex';
                setTimeout(() => document.getElementById('inputNama').focus(), 50);
            }

            function tutupModal() {
                modal.style.display = 'none';
            }

            modal.addEventListener('click', function (e) {
                if (e.target === this) tutupModal();
            });
        </script>
    @endpush
@endsection