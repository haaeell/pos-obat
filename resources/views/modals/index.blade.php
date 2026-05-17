{{-- resources/views/modals/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Modal & Hutang')

@section('content')
    <div class="mx-auto bg-white rounded-xl">

        {{-- HEADER --}}
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-hand-holding-dollar text-emerald-600"></i>
                    Modal & Hutang
                </h1>
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
                <i class="fa-solid fa-plus mr-1"></i> Tambah Pinjaman
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

        {{-- SUMMARY CARDS --}}
        @php
            $cards = [
                ['label' => 'Total Pinjaman', 'value' => 'Rp ' . number_format($summary['total_pinjaman'], 0, ',', '.'), 'icon' => 'fa-sack-dollar', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                ['label' => 'Total Pencairan', 'value' => 'Rp ' . number_format($summary['total_pencairan'], 0, ',', '.'), 'icon' => 'fa-money-bill-transfer', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                ['label' => 'Sisa Kewajiban', 'value' => 'Rp ' . number_format($summary['total_kewajiban'], 0, ',', '.'), 'icon' => 'fa-triangle-exclamation', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
                ['label' => 'Aktif', 'value' => $summary['total_aktif'], 'icon' => 'fa-circle-play', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
            ];
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
            @foreach($cards as $card)
                <div class="rounded-xl {{ $card['bg'] }} p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-600">{{ $card['label'] }}</span>
                        <i class="fa-solid {{ $card['icon'] }} {{ $card['text'] }} text-lg"></i>
                    </div>
                    <div class="text-sm font-bold {{ $card['text'] }} leading-tight">{{ $card['value'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- FILTER & SEARCH --}}
        <form method="GET" class="flex flex-wrap gap-3 mb-4">
            <select name="status" onchange="this.form.submit()"
                class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value="">Semua Status</option>
                @foreach(['aktif', 'lunas'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <div class="flex flex-1 min-w-52 rounded-lg overflow-hidden border border-slate-200 bg-white">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / pemberi pinjaman…"
                    class="flex-1 px-4 py-2 text-sm text-slate-600 focus:outline-none">
                <button type="submit" class="px-4 text-slate-400 hover:text-emerald-600 transition">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>
            @if(request()->hasAny(['status', 'search']))
                <a href="{{ route('modals.index') }}"
                    class="flex items-center gap-1 text-sm px-4 py-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-100 transition">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </form>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow border overflow-x-auto px-4 py-5">
            <table id="datatable" class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Pemberi Pinjaman</th>
                        <th class="px-4 py-3 text-right">Pinjaman</th>
                        <th class="px-4 py-3 text-right">Pencairan</th>
                        <th class="px-4 py-3 text-right">Total Bunga</th>
                        <th class="px-4 py-3 text-right">Total Kewajiban</th>
                        <th class="px-4 py-3 text-right">Sisa</th>
                        <th class="px-4 py-3 text-center">Progress</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modals as $m)
                                    <tr class="border-t hover:bg-slate-50 transition">
                                        <td class="px-4 py-3">
                                            <code
                                                class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-lg">{{ $m->kode_pinjaman }}</code>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-800">{{ $m->nama_pemberi_pinjaman }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">{{ ucfirst($m->jenis_pinjaman) }} · {{ $m->tenor }}
                                                {{ $m->satuan_tenor }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right">{{ number_format($m->jumlah_pinjaman, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($m->nominal_pencairan, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-amber-600 font-medium">
                                            {{ number_format($m->total_bunga, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($m->total_kewajiban, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-red-500">
                                            {{ number_format($m->sisa_kewajiban, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col items-center">
                                                <span class="text-xs text-slate-500 mb-1">{{ $m->cicilan_ke }}/{{ $m->tenor }}
                                                    ({{ $m->getProgressPersen() }}%)</span>
                                                <div class="w-full bg-slate-200 rounded-full h-2">
                                                    <div class="h-2 rounded-full bg-gradient-to-r from-emerald-500 to-green-400 transition-all"
                                                        style="width:{{ $m->getProgressPersen() }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @php
                                                $st = match ($m->status) {
                                                    'aktif' => 'bg-emerald-100 text-emerald-700',
                                                    'lunas' => 'bg-sky-100 text-sky-700',
                                                    'restrukturisasi' => 'bg-amber-100 text-amber-700',
                                                    default => 'bg-slate-100 text-slate-600',
                                                };
                                            @endphp
                        <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $st }}">{{ ucfirst($m->status) }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center space-x-1">
                                            <a href="{{ route('modals.show', $m->id) }}"
                                                class="px-3 py-1 bg-emerald-500 text-white rounded hover:bg-emerald-600 transition"
                                                title="Detail">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </a>
                                            <button onclick='openEditModal(@json($m))'
                                                class="px-3 py-1 bg-yellow-400 rounded hover:bg-yellow-500 transition" title="Edit">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                            <button
                                                onclick="deleteModal({{ $m->id }}, '{{ $m->kode_pinjaman }}', {{ $m->sudah_dicairkan ? 'true' : 'false' }})"
                                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition" title="Hapus">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </td>
                                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL FORM --}}
    <div id="modalForm"
        class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl my-8">

            {{-- MODAL HEADER --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <h2 id="modalTitle" class="text-base font-bold text-slate-800">Tambah Pinjaman</h2>
            </div>

            {{-- MODAL BODY --}}
            <form id="formModal" method="POST" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                @csrf
                <input type="hidden" name="_method" id="methodField">
                <input type="hidden" id="modalId">

                {{-- Info Pinjaman --}}
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-600 mb-3">Informasi Pinjaman</p>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                Nama Pemberi Pinjaman <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pemberi_pinjaman" required
                                placeholder="Bank BRI, Koperasi Sejahtera, …"
                                class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Jenis
                                    Pinjaman</label>
                                <select name="jenis_pinjaman"
                                    class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                                    <option value="bank">Bank</option>
                                    <option value="koperasi">Koperasi</option>
                                    <option value="perorangan">Perorangan</option>
                                    <option value="lembaga_keuangan">Lembaga Keuangan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Status</label>
                                <select name="status"
                                    class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                                    <option value="aktif">Aktif</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                    Jumlah Pinjaman (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="jumlah_pinjaman" id="inputJumlahPinjaman" required
                                    placeholder="100.000.000" oninput="hitungOtomatis()"
                                    class="rupiah-input mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                    Nominal Pencairan (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nominal_pencairan" required placeholder="98.500.000"
                                    class="rupiah-input mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                                <p class="text-xs text-slate-400 mt-1">Aktual diterima (setelah potongan admin)</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Tgl Pinjaman</label>
                                <input type="date" name="tanggal_pinjaman" required
                                    class="mt-1 w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Tgl
                                    Pencairan</label>
                                <input type="date" name="tanggal_pencairan" required
                                    class="mt-1 w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Jatuh Tempo</label>
                                <input type="date" name="tanggal_jatuh_tempo" required
                                    class="mt-1 w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Keterangan</label>
                            <textarea name="keterangan" rows="2" placeholder="Tujuan pinjaman, nomor kontrak, …"
                                class="mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none resize-none"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Bunga & Cicilan --}}
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-600 mb-3">Bunga & Cicilan</p>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                    Total Bunga (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="total_bunga" id="inputTotalBunga" required placeholder="10.000.000"
                                    oninput="hitungOtomatis()"
                                    class="rupiah-input mt-1 w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                                <p class="text-xs text-slate-400 mt-1">Total bunga selama tenor</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                                    Tenor <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2 mt-1">
                                    <input type="number" name="tenor" id="inputTenor" required min="1" placeholder="12"
                                        oninput="hitungOtomatis()"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                                    <select name="satuan_tenor"
                                        class="px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none">
                                        <option value="bulan" selected>Bln</option>
                                        <option value="hari">Hari</option>
                                        <option value="minggu">Mgg</option>
                                        <option value="tahun">Thn</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Preview kalkulasi otomatis --}}
                        <div id="kalkulasiResult"
                            class="hidden bg-emerald-50 border border-emerald-200 rounded-xl p-4 space-y-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-emerald-600 mb-2">Preview Cicilan</p>
                            <div class="flex justify-between text-sm text-slate-600 pb-2 border-b border-emerald-200">
                                <span>Cicilan per Periode</span>
                                <span id="kCicilan" class="font-semibold text-slate-800">-</span>
                            </div>
                            <div class="flex justify-between text-sm text-slate-600 pb-2 border-b border-emerald-200">
                                <span>Pokok per Cicilan</span>
                                <span id="kPokok" class="font-semibold text-slate-800">-</span>
                            </div>
                            <div class="flex justify-between text-sm text-slate-600 pb-2 border-b border-emerald-200">
                                <span>Bunga per Cicilan</span>
                                <span id="kBungaPerCicilan" class="font-semibold text-slate-800">-</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold text-emerald-700">
                                <span>Total Kewajiban</span>
                                <span id="kKewajiban">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- MODAL FOOTER --}}
            <div class="px-6 py-4 border-t flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 rounded-xl border text-sm font-semibold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="button" onclick="submitForm()" id="submitBtn"
                    class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-save mr-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#datatable').DataTable();
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const fmt = n => 'Rp ' + Number(n).toLocaleString('id-ID');

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID').format(value || 0);
        }

        function parseRupiah(value) {
            return parseInt(String(value).replace(/\D/g, '')) || 0;
        }

        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('rupiah-input')) {
                const raw = parseRupiah(e.target.value);
                e.target.value = raw ? formatRupiah(raw) : '';
            }
        });

        function hitungOtomatis() {
            const pokok = parseRupiah(document.getElementById('inputJumlahPinjaman').value);
            const bunga = parseRupiah(document.getElementById('inputTotalBunga').value);
            const tenor = parseInt(document.getElementById('inputTenor').value) || 0;
            const result = document.getElementById('kalkulasiResult');

            if (!pokok || !tenor) {
                result.classList.add('hidden');
                return;
            }

            const totalKewajiban = pokok + bunga;
            const cicilanPerPeriode = totalKewajiban / tenor;
            const pokokPerCicilan = pokok / tenor;
            const bungaPerCicilan = bunga / tenor;

            document.getElementById('kCicilan').textContent = fmt(cicilanPerPeriode);
            document.getElementById('kPokok').textContent = fmt(pokokPerCicilan);
            document.getElementById('kBungaPerCicilan').textContent = fmt(bungaPerCicilan);
            document.getElementById('kKewajiban').textContent = fmt(totalKewajiban);

            result.classList.remove('hidden');
        }

        const modal = $('#modalForm');
        const form = $('#formModal');
        const title = $('#modalTitle');
        const method = $('#methodField');
        const btn = $('#submitBtn');

        window.openCreateModal = function () {
            title.text('Tambah Pinjaman');
            form.attr('action', '/modals');
            method.val('');
            $('#modalId').val('');
            form[0].reset();
            $('#kalkulasiResult').addClass('hidden');
            modal.removeClass('hidden');
            $('input[name="nama_pemberi_pinjaman"]').focus();
        }

        window.openEditModal = function (data) {
            title.text('Edit Pinjaman');
            form.attr('action', `/modals/${data.id}`);
            method.val('PUT');
            $('#modalId').val(data.id);
            $('#kalkulasiResult').addClass('hidden');

            for (const [k, v] of Object.entries(data)) {
                const el = form[0].querySelector(`[name="${k}"]`);
                if (el) el.value = v ?? '';
            }

            modal.removeClass('hidden');
            $('input[name="nama_pemberi_pinjaman"]').focus();
            hitungOtomatis();
        }

        window.closeModal = function () {
            modal.addClass('hidden');
        }

        modal.on('click', function (e) {
            if ($(e.target).is(modal)) closeModal();
        });

        async function submitForm() {
    const id = $('#modalId').val();

    const data = Object.fromEntries(
        new FormData(form[0]).entries()
    );

    data.jumlah_pinjaman = parseRupiah(data.jumlah_pinjaman);
    data.nominal_pencairan = parseRupiah(data.nominal_pencairan);
    data.total_bunga = parseRupiah(data.total_bunga);

    btn.prop('disabled', true)
       .html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...');

    try {
        const res = await fetch(
            id ? `/modals/${id}` : '/modals',
            {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            }
        );

        const json = await res.json();

        if (!res.ok) {
            throw json;
        }

        closeModal();
        location.reload();

    } catch (err) {

        let msg = 'Terjadi kesalahan';

        if (err.errors) {
            msg = Object.values(err.errors)
                .flat()
                .join('<br>');
        } else if (err.message) {
            msg = err.message;
        }

        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            html: msg
        });

    } finally {
        btn.prop('disabled', false)
           .html('<i class="fa-solid fa-save mr-1"></i> Simpan');
    }
}

        window.deleteModal = function (id, kode, sudahCair) {
            if (sudahCair) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa',
                    text: 'Modal yang sudah dicairkan tidak dapat dihapus.'
                });
                return;
            }

            Swal.fire({
                title: 'Hapus Pinjaman?',
                html: `Kode <strong>${kode}</strong> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, hapus',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const res = await fetch(`/modals/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });
                    const json = await res.json();
                    if (json.success) location.reload();
                    else Swal.fire({ icon: 'error', title: 'Gagal', text: json.message });
                }
            });
        }
    </script>
@endpush