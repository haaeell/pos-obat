@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="mx-auto bg-white rounded-xl">

        {{-- HEADER --}}
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Kategori</h1>
                <nav class="text-sm text-slate-500 mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-slate-700 font-medium">Kategori</li>
                    </ol>
                </nav>
            </div>

            <button onclick="openCreateModal()"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                + Tambah
            </button>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <i class="fa-solid fa-circle-xmark mr-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow border overflow-x-auto px-4 py-5">
            <table id="datatable" class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">No</th>
                        <th class="px-4 py-3 text-left">Nama Kategori</th>
                        <th class="px-4 py-3 text-left">Deskripsi</th>
                        <th class="px-4 py-3 text-center">Produk</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori as $i => $item)
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>

                            <td class="px-4 py-3 font-medium text-slate-800">{{ $item->nama }}</td>

                            <td class="px-4 py-3 text-slate-500">
                                {{ $item->deskripsi ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $item->produk_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }}">
                                    {{ $item->produk_count }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($item->is_aktif)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-slate-200 text-slate-500">Non-aktif</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center space-x-1">
                                <button onclick='openEditModal(@json($item))'
                                    class="px-3 py-1 bg-yellow-400 rounded hover:bg-yellow-500 transition"
                                    title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>

                                <button onclick="deleteKategori({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
                                    title="Hapus">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="kategoriModal"
        class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl">

            {{-- MODAL HEADER --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <h2 id="modalTitle" class="text-base font-bold text-slate-800">Tambah Kategori</h2>
            </div>

            {{-- MODAL BODY --}}
            <form id="kategoriForm" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="methodField">

                {{-- NAMA --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="nama" id="inputNama" required
                            placeholder="Contoh: Sembako"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="inputDeskripsi" rows="3"
                        placeholder="Keterangan tambahan (opsional)"
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                               focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none resize-none"></textarea>
                </div>

                {{-- FOOTER --}}
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 rounded-xl border text-sm font-semibold hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
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
                        paginate: { previous: 'Sebelumnya', next: 'Selanjutnya' },
                        zeroRecords: 'Data tidak ditemukan',
                    }
                })

                const modal  = $('#kategoriModal')
                const form   = $('#kategoriForm')
                const title  = $('#modalTitle')
                const nama   = $('#inputNama')
                const desc   = $('#inputDeskripsi')
                const method = $('#methodField')
                const btn    = $('#submitBtn')

                window.openCreateModal = function () {
                    title.text('Tambah Kategori')
                    form.attr('action', '/kategori')
                    method.val('')
                    nama.val('')
                    desc.val('')
                    modal.removeClass('hidden')
                    nama.focus()
                }

                window.openEditModal = function (data) {
                    title.text('Edit Kategori')
                    form.attr('action', `/kategori/${data.id}`)
                    method.val('PUT')
                    nama.val(data.nama)
                    desc.val(data.deskripsi ?? '')
                    modal.removeClass('hidden')
                    nama.focus()
                }

                window.closeModal = function () {
                    modal.addClass('hidden')
                }

                // Tutup modal saat klik backdrop
                modal.on('click', function (e) {
                    if ($(e.target).is(modal)) closeModal()
                })

                form.on('submit', function () {
                    btn.prop('disabled', true).text('Menyimpan...')
                })

                window.deleteKategori = function (id, nama) {
                    Swal.fire({
                        title: 'Hapus Kategori?',
                        html: `Kategori <strong>${nama}</strong> akan dihapus permanen.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonText: 'Batal',
                        confirmButtonText: 'Ya, hapus',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const f = document.createElement('form')
                            f.method = 'POST'
                            f.action = `/kategori/${id}`
                            f.innerHTML = `
                                <input type="hidden" name="_token" value="${$('meta[name=csrf-token]').attr('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                            `
                            document.body.appendChild(f)
                            f.submit()
                        }
                    })
                }
            })
        </script>
    @endpush
@endsection