@extends('layouts.app')

@section('title', 'Pelanggan')@section('content')<div class="mx-auto bg-white rounded-xl">

        {{-- HEADER --}}
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Pelanggan</h1>
                <nav class="text-sm text-slate-500 mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-slate-700 font-medium">Pelanggan</li>
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
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Telepon</th>
                        <th class="px-4 py-3 text-left">Alamat</th>
                        <th class="px-4 py-3 text-center">Transaksi</th>
                        <th class="px-4 py-3 text-right">Piutang</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelanggan as $i => $item)
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>

                            <td class="px-4 py-3 font-medium text-slate-800">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($item->nama, 0, 1) }}
                                    </div>
                                    {{ $item->nama }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-slate-500">
                                @if ($item->telepon)
                                    <a href="tel:{{ $item->telepon }}" class="hover:text-emerald-600 transition">
                                        <i class="fa-solid fa-phone mr-1 text-xs"></i>{{ $item->telepon }}
                                    </a>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-slate-500 max-w-[180px] truncate">
                                {{ $item->alamat ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $item->transaksi_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }}">
                                    {{ $item->transaksi_count }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                @if ($item->total_piutang > 0)
                                    <span class="text-red-600 font-semibold">
                                        Rp {{ number_format($item->total_piutang, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
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

                                <button onclick="deletePelanggan({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->transaksi_count }})"
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
    <div id="pelangganModal"
        class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl">

            {{-- MODAL HEADER --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h2 id="modalTitle" class="text-base font-bold text-slate-800">Tambah Pelanggan</h2>
            </div>

            {{-- MODAL BODY --}}
            <form id="pelangganForm" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="methodField">

                {{-- NAMA --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                        Nama Pelanggan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="nama" id="inputNama" required
                            placeholder="Contoh: Budi Santoso"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                {{-- TELEPON --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Telepon</label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="telepon" id="inputTelepon"
                            placeholder="Contoh: 08123456789"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                {{-- ALAMAT --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Alamat</label>
                    <div class="relative mt-1">
                        <i class="fa-solid fa-location-dot absolute left-3 top-3 text-slate-400 text-sm"></i>
                        <textarea name="alamat" id="inputAlamat" rows="2"
                            placeholder="Alamat lengkap (opsional)"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none resize-none"></textarea>
                    </div>
                </div>

                {{-- CATATAN --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Catatan</label>
                    <textarea name="catatan" id="inputCatatan" rows="2"
                        placeholder="Keterangan tambahan (opsional)"
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                               focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none resize-none"></textarea>
                </div>

                {{-- STATUS (hanya tampil saat edit) --}}
                <div id="statusField" class="hidden">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Status</label>
                    <div class="mt-1 flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_aktif" value="1" id="statusAktif" class="accent-emerald-600">
                            <span class="text-sm text-slate-700">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_aktif" value="0" id="statusNonaktif" class="accent-slate-400">
                            <span class="text-sm text-slate-700">Non-aktif</span>
                        </label>
                    </div>
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

                const modal   = $('#pelangganModal')
                const form    = $('#pelangganForm')
                const title   = $('#modalTitle')
                const nama    = $('#inputNama')
                const telepon = $('#inputTelepon')
                const alamat  = $('#inputAlamat')
                const catatan = $('#inputCatatan')
                const method  = $('#methodField')
                const btn     = $('#submitBtn')
                const statusField = $('#statusField')

                window.openCreateModal = function () {
                    title.text('Tambah Pelanggan')
                    form.attr('action', '{{ route('pelanggan.store') }}')
                    method.val('')
                    nama.val('')
                    telepon.val('')
                    alamat.val('')
                    catatan.val('')
                    statusField.addClass('hidden')
                    modal.removeClass('hidden')
                    nama.focus()
                }

                window.openEditModal = function (data) {
                    title.text('Edit Pelanggan')
                    form.attr('action', `/pelanggan/${data.id}`)
                    method.val('PUT')
                    nama.val(data.nama)
                    telepon.val(data.telepon ?? '')
                    alamat.val(data.alamat ?? '')
                    catatan.val(data.catatan ?? '')
                    statusField.removeClass('hidden')

                    if (data.is_aktif) {
                        $('#statusAktif').prop('checked', true)
                    } else {
                        $('#statusNonaktif').prop('checked', true)
                    }

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

                window.deletePelanggan = function (id, nama, transaksiCount) {
                    if (transaksiCount > 0) {
                        Swal.fire({
                            title: 'Tidak Dapat Dihapus',
                            html: `Pelanggan <strong>${nama}</strong> memiliki <strong>${transaksiCount} transaksi</strong> dan tidak dapat dihapus.`,
                            icon: 'warning',
                            confirmButtonColor: '#059669',
                            confirmButtonText: 'Mengerti',
                        })
                        return
                    }

                    Swal.fire({
                        title: 'Hapus Pelanggan?',
                        html: `Pelanggan <strong>${nama}</strong> akan dihapus permanen.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonText: 'Batal',
                        confirmButtonText: 'Ya, hapus',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const f = document.createElement('form')
                            f.method = 'POST'
                            f.action = `/pelanggan/${id}`
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