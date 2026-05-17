@extends('layouts.app')

@section('title', 'Modal & Hutang')

@section('content')
    <div class="mx-auto bg-white rounded-xl">

        {{-- HEADER --}}
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Modal & Hutang</h1>
                <nav class="text-sm text-slate-500 mt-1">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-slate-700 font-medium">Modal & Hutang</li>
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
                        <th class="px-4 py-3 text-left">Nomor</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-left">Nama Sumber</th>
                        <th class="px-4 py-3 text-right">Jumlah Total</th>
                        <th class="px-4 py-3 text-center">Tanggal Diterima</th>
                        <th class="px-4 py-3 text-right">Sudah Dibayar</th>
                        <th class="px-4 py-3 text-right">Sisa Hutang</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modalHutang as $i => $item)
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>

                            <td class="px-4 py-3 font-medium text-slate-800">{{ $item->nomor }}</td>

                            <td class="px-4 py-3">
                                @if ($item->jenis === 'pinjaman')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                        <i class="fa-solid fa-hand-holding-dollar mr-1"></i>Pinjaman
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                        <i class="fa-solid fa-wallet mr-1"></i>Modal Sendiri
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-slate-800">
                                {{ $item->nama_sumber }}
                                @if ($item->isPinjaman() && $item->nama_kreditur)
                                    <div class="text-xs text-slate-500">{{ $item->nama_kreditur }}</div>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-right font-semibold text-slate-800">
                                Rp {{ number_format($item->jumlah_total, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-center text-slate-600">
                                {{ $item->tanggal_diterima->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3 text-right text-emerald-600 font-medium">
                                Rp {{ number_format($item->sudah_dibayar, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right text-red-600 font-medium">
                                @if ($item->isPinjaman())
                                    Rp {{ number_format($item->sisa_hutang, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($item->status === 'lunas')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                        <i class="fa-solid fa-check-circle mr-1"></i>Lunas
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                        <i class="fa-solid fa-clock mr-1"></i>Aktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center space-x-1">
                                <button onclick='openEditModal(@json($item))'
                                    class="px-3 py-1 bg-yellow-400 rounded hover:bg-yellow-500 transition"
                                    title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>

                                <button onclick="deleteModalHutang({{ $item->id }}, '{{ addslashes($item->nomor) }}')"
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
    <div id="modalHutangModal"
        class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl max-h-[90vh] overflow-y-auto">

            {{-- MODAL HEADER --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b sticky top-0 bg-white">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <h2 id="modalTitle" class="text-base font-bold text-slate-800">Tambah Modal/Hutang</h2>
            </div>

            {{-- MODAL BODY --}}
            <form id="modalHutangForm" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="methodField">

                {{-- JENIS --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                        Jenis <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="jenis" value="modal_sendiri" id="jenisModalSendiri" 
                                onchange="togglePinjamanFields()" required
                                class="peer sr-only">
                            <div class="px-4 py-3 rounded-xl border-2 border-slate-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-wallet text-blue-600"></i>
                                    <span class="font-semibold text-sm">Modal Sendiri</span>
                                </div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="jenis" value="pinjaman" id="jenisPinjaman" 
                                onchange="togglePinjamanFields()" required
                                class="peer sr-only">
                            <div class="px-4 py-3 rounded-xl border-2 border-slate-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-hand-holding-dollar text-red-600"></i>
                                    <span class="font-semibold text-sm">Pinjaman</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- NAMA SUMBER --}}
                    <div>
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                            Nama Sumber <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_sumber" id="inputNamaSumber" required
                            placeholder="Contoh: Modal Awal, Bank BRI"
                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                    </div>

                    {{-- JUMLAH TOTAL --}}
                    <div>
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                            Jumlah Total <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah_total" id="inputJumlahTotal" required min="0" step="0.01"
                            placeholder="0"
                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                   focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                {{-- TANGGAL DITERIMA --}}
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                        Tanggal Diterima <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_diterima" id="inputTanggalDiterima" required
                        class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                               focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                </div>

                {{-- FIELDS KHUSUS PINJAMAN --}}
                <div id="pinjamanFields" class="space-y-4 hidden">
                    <div class="border-t pt-4">
                        <h3 class="text-sm font-bold text-slate-700 mb-3">
                            <i class="fa-solid fa-file-invoice-dollar mr-1"></i> Detail Pinjaman
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- NAMA KREDITUR --}}
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                    Nama Kreditur <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_kreditur" id="inputNamaKreditur"
                                    placeholder="Nama pemberi pinjaman"
                                    class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                           focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>

                            {{-- TELEPON KREDITUR --}}
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                    Telepon Kreditur
                                </label>
                                <input type="text" name="telepon_kreditur" id="inputTeleponKreditur"
                                    placeholder="08xx-xxxx-xxxx"
                                    class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                           focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            {{-- JANGKA BULAN --}}
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                    Jangka (Bulan) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="jangka_bulan" id="inputJangkaBulan" min="1"
                                    placeholder="12"
                                    class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                           focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>

                            {{-- BUNGA PERSEN --}}
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                    Bunga (% per tahun)
                                </label>
                                <input type="number" name="bunga_persen" id="inputBungaPersen" min="0" max="100" step="0.01"
                                    placeholder="0"
                                    class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                           focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>

                            {{-- TANGGAL JATUH TEMPO --}}
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                    Jatuh Tempo
                                </label>
                                <input type="date" name="tanggal_jatuh_tempo" id="inputTanggalJatuhTempo"
                                    class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm
                                           focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                        </div>
                    </div>
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
                <div class="flex justify-end gap-2 pt-2 border-t">
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
                    },
                    order: [[0, 'desc']]
                })

                const modal  = $('#modalHutangModal')
                const form   = $('#modalHutangForm')
                const title  = $('#modalTitle')
                const method = $('#methodField')
                const btn    = $('#submitBtn')

                window.openCreateModal = function () {
                    title.text('Tambah Modal/Hutang')
                    form.attr('action', '/modal-hutang')
                    method.val('')
                    form[0].reset()
                    $('#pinjamanFields').addClass('hidden')
                    modal.removeClass('hidden')
                    $('#inputNamaSumber').focus()
                }

                window.openEditModal = function (data) {
                    title.text('Edit Modal/Hutang')
                    form.attr('action', `/modal-hutang/${data.id}`)
                    method.val('PUT')

                    // Set jenis
                    if (data.jenis === 'pinjaman') {
                        $('#jenisPinjaman').prop('checked', true)
                        $('#pinjamanFields').removeClass('hidden')
                    } else {
                        $('#jenisModalSendiri').prop('checked', true)
                        $('#pinjamanFields').addClass('hidden')
                    }

                    // Set common fields
                    $('#inputNamaSumber').val(data.nama_sumber)
                    $('#inputJumlahTotal').val(data.jumlah_total)
                    $('#inputTanggalDiterima').val(data.tanggal_diterima)
                    $('#inputCatatan').val(data.catatan ?? '')

                    // Set pinjaman fields if applicable
                    if (data.jenis === 'pinjaman') {
                        $('#inputNamaKreditur').val(data.nama_kreditur ?? '')
                        $('#inputTeleponKreditur').val(data.telepon_kreditur ?? '')
                        $('#inputJangkaBulan').val(data.jangka_bulan ?? '')
                        $('#inputBungaPersen').val(data.bunga_persen ?? '')
                        $('#inputTanggalJatuhTempo').val(data.tanggal_jatuh_tempo ?? '')
                    }

                    modal.removeClass('hidden')
                    $('#inputNamaSumber').focus()
                }

                window.closeModal = function () {
                    modal.addClass('hidden')
                }

                window.togglePinjamanFields = function () {
                    const isPinjaman = $('#jenisPinjaman').is(':checked')
                    if (isPinjaman) {
                        $('#pinjamanFields').removeClass('hidden')
                        $('#inputNamaKreditur').prop('required', true)
                        $('#inputJangkaBulan').prop('required', true)
                    } else {
                        $('#pinjamanFields').addClass('hidden')
                        $('#inputNamaKreditur').prop('required', false)
                        $('#inputJangkaBulan').prop('required', false)
                    }
                }

                // Tutup modal saat klik backdrop
                modal.on('click', function (e) {
                    if ($(e.target).is(modal)) closeModal()
                })

                form.on('submit', function () {
                    btn.prop('disabled', true).text('Menyimpan...')
                })

                window.deleteModalHutang = function (id, nomor) {
                    Swal.fire({
                        title: 'Hapus Data?',
                        html: `Data <strong>${nomor}</strong> akan dihapus permanen.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonText: 'Batal',
                        confirmButtonText: 'Ya, hapus',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const f = document.createElement('form')
                            f.method = 'POST'
                            f.action = `/modal-hutang/${id}`
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