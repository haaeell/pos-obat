@extends('layouts.app')

@section('title', 'Supplier')@section('content')<div class="mx-auto bg-white rounded-xl">

        {{-- HEADER --}}
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Supplier</h1>
                <nav class="text-sm text-slate-500 mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-slate-700 font-medium">Supplier</li>
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
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Nama Supplier</th>
                        <th class="px-4 py-3 text-left">Kontak Person</th>
                        <th class="px-4 py-3 text-left">Telepon</th>
                        <th class="px-4 py-3 text-center">Produk</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($supplier as $i => $item)
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>

                            <td class="px-4 py-3">
                                <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded text-slate-700">
                                    {{ $item->kode }}
                                </span>
                            </td>

                            <td class="px-4 py-3 font-medium text-slate-800">{{ $item->nama }}</td>

                            <td class="px-4 py-3 text-slate-600">{{ $item->kontak_person ?? '-' }}</td>

                            <td class="px-4 py-3 text-slate-600">
                                @if ($item->telepon)
                                    <a href="tel:{{ $item->telepon }}"
                                        class="text-emerald-600 hover:underline">{{ $item->telepon }}</a>
                                @else
                                    -
                                @endif
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
                                <button onclick='openDetailModal(@json($item))'
                                    class="px-3 py-1 bg-slate-200 text-slate-700 rounded hover:bg-slate-300 transition"
                                    title="Detail">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>

                                <button onclick='openEditModal(@json($item))'
                                    class="px-3 py-1 bg-yellow-400 rounded hover:bg-yellow-500 transition"
                                    title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>

                                <button onclick="deleteSupplier({{ $item->id }}, '{{ addslashes($item->nama) }}')"
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

    {{-- MODAL FORM (Tambah / Edit) --}}
    <div id="supplierModal"
        class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl max-h-[90vh] overflow-y-auto">

            {{-- MODAL HEADER --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b sticky top-0 bg-white z-10">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-truck"></i>
                </div>
                <h2 id="modalTitle" class="text-base font-bold text-slate-800">Tambah Supplier</h2>
            </div>

            {{-- MODAL BODY --}}
            <form id="supplierForm" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="methodField">

                <div class="grid grid-cols-2 gap-4">
                    {{-- KODE --}}
                    <div>
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode" id="inputKode" required
                            placeholder="SUP-001"
                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none uppercase">
                    </div>

                    {{-- NAMA --}}
                    <div>
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                            Nama Supplier <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="inputNama" required
                            placeholder="CV. Maju Jaya"
                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- KONTAK PERSON --}}
                    <div>
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                            Kontak Person
                        </label>
                        <input type="text" name="kontak_person" id="inputKontakPerson"
                            placeholder="Nama PIC"
                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                    </div>

                    {{-- TELEPON --}}
                    <div>
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                            Telepon
                        </label>
                        <input type="text" name="telepon" id="inputTelepon"
                            placeholder="08xx-xxxx-xxxx"
                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                        Email
                    </label>
                    <input type="email" name="email" id="inputEmail"
                        placeholder="supplier@email.com"
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                               focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                </div>

                {{-- ALAMAT --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                        Alamat
                    </label>
                    <textarea name="alamat" id="inputAlamat" rows="2"
                        placeholder="Jl. ..."
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                               focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none resize-none"></textarea>
                </div>

                {{-- CATATAN --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                        Catatan
                    </label>
                    <textarea name="catatan" id="inputCatatan" rows="2"
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

    {{-- MODAL DETAIL --}}
    <div id="detailModal"
        class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl">

            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <h2 class="text-base font-bold text-slate-800">Detail Supplier</h2>
                </div>
                <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-3 text-sm">
                <div class="flex gap-2">
                    <span class="w-32 text-slate-500 shrink-0">Kode</span>
                    <span class="font-mono font-semibold text-slate-800" id="detailKode">-</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-32 text-slate-500 shrink-0">Nama</span>
                    <span class="font-medium text-slate-800" id="detailNama">-</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-32 text-slate-500 shrink-0">Kontak Person</span>
                    <span id="detailKontakPerson">-</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-32 text-slate-500 shrink-0">Telepon</span>
                    <span id="detailTelepon">-</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-32 text-slate-500 shrink-0">Email</span>
                    <span id="detailEmail">-</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-32 text-slate-500 shrink-0">Alamat</span>
                    <span id="detailAlamat" class="text-slate-700 leading-relaxed">-</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-32 text-slate-500 shrink-0">Catatan</span>
                    <span id="detailCatatan" class="text-slate-700">-</span>
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
                        paginate: { previous: 'Sebelumnya', next: 'Selanjutnya' },
                        zeroRecords: 'Data tidak ditemukan',
                    }
                })

                const modal  = $('#supplierModal')
                const form   = $('#supplierForm')
                const title  = $('#modalTitle')
                const method = $('#methodField')
                const btn    = $('#submitBtn')

                window.openCreateModal = function () {
                    title.text('Tambah Supplier')
                    form.attr('action', '/supplier')
                    method.val('')
                    form[0].reset()
                    modal.removeClass('hidden')
                    $('#inputKode').focus()
                }

                window.openEditModal = function (data) {
                    title.text('Edit Supplier')
                    form.attr('action', `/supplier/${data.id}`)
                    method.val('PUT')

                    $('#inputKode').val(data.kode)
                    $('#inputNama').val(data.nama)
                    $('#inputKontakPerson').val(data.kontak_person ?? '')
                    $('#inputTelepon').val(data.telepon ?? '')
                    $('#inputEmail').val(data.email ?? '')
                    $('#inputAlamat').val(data.alamat ?? '')
                    $('#inputCatatan').val(data.catatan ?? '')

                    modal.removeClass('hidden')
                    $('#inputNama').focus()
                }

                window.openDetailModal = function (data) {
                    $('#detailKode').text(data.kode)
                    $('#detailNama').text(data.nama)
                    $('#detailKontakPerson').text(data.kontak_person || '-')
                    $('#detailTelepon').text(data.telepon || '-')
                    $('#detailEmail').text(data.email || '-')
                    $('#detailAlamat').text(data.alamat || '-')
                    $('#detailCatatan').text(data.catatan || '-')
                    $('#detailModal').removeClass('hidden')
                }

                window.closeModal = function () {
                    modal.addClass('hidden')
                }

                window.closeDetailModal = function () {
                    $('#detailModal').addClass('hidden')
                }

                // Tutup saat klik backdrop
                modal.on('click', function (e) {
                    if ($(e.target).is(modal)) closeModal()
                })
                $('#detailModal').on('click', function (e) {
                    if ($(e.target).is($('#detailModal'))) closeDetailModal()
                })

                form.on('submit', function () {
                    btn.prop('disabled', true).text('Menyimpan...')
                })

                window.deleteSupplier = function (id, nama) {
                    Swal.fire({
                        title: 'Hapus Supplier?',
                        html: `Supplier <strong>${nama}</strong> akan dihapus permanen.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonText: 'Batal',
                        confirmButtonText: 'Ya, hapus',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const f = document.createElement('form')
                            f.method = 'POST'
                            f.action = `/supplier/${id}`
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