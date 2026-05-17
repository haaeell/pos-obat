{{-- resources/views/modals/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="p-6">

        {{-- ── HEADER ── --}}
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <a href="{{ route('modals.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 border border-slate-300 rounded-lg px-3 py-1.5 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <div class="w-px h-5 bg-slate-200"></div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-base font-semibold text-slate-800">{{ $modal->nama_pemberi_pinjaman }}</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $modal->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ ucfirst($modal->status) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $modal->kode_pinjaman }} · {{ ucfirst($modal->jenis_pinjaman) }}</p>
                </div>
            </div>
        </div>

        {{-- ── KPI BAR ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
            <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total kewajiban</p>
                <p class="text-lg font-bold text-slate-800 mt-1">Rp {{ number_format($modal->total_kewajiban, 0, ',', '.') }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Sisa kewajiban</p>
                <p class="text-lg font-bold text-red-600 mt-1">Rp {{ number_format($modal->sisa_kewajiban, 0, ',', '.') }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total terbayar</p>
                <p class="text-lg font-bold text-emerald-600 mt-1">Rp {{ number_format($modal->total_terbayar, 0, ',', '.') }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Progres pelunasan</p>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-lg font-bold text-slate-800">{{ $modal->getProgressPersen() }}%</p>
                    <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-2 bg-emerald-500 rounded-full transition-all duration-700"
                            style="width:{{ $modal->getProgressPersen() }}%"></div>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Cicilan {{ $modal->cicilan_ke }} / {{ $modal->tenor }}</p>
            </div>
        </div>

        {{-- ── MAIN CARD ── --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- TABS --}}
            <div class="flex border-b border-slate-200 px-5">
                <button id="tab-btn-cicilan" onclick="switchTab('cicilan')"
                    class="tab-btn flex items-center gap-1.5 px-1 py-3.5 text-sm font-semibold border-b-2 border-emerald-500 text-emerald-600 mr-5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10" />
                    </svg>
                    Jadwal cicilan
                    <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                        {{ $modal->cicilans->count() }}
                    </span>
                </button>
                <button id="tab-btn-info" onclick="switchTab('info')"
                    class="tab-btn flex items-center gap-1.5 px-1 py-3.5 text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                    </svg>
                    Info pinjaman
                </button>
            </div>

            {{-- TAB: JADWAL CICILAN --}}
            <div id="tab-cicilan">
                {{-- sub-header --}}
                <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100">
                    <div class="flex gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Lunas {{ $modal->cicilans->where('status', 'sudah_bayar')->count() }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            Terlambat {{ $modal->cicilans->where('status', 'terlambat')->count() }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            Belum {{ $modal->cicilans->whereIn('status', ['belum_bayar', 'sebagian'])->count() }}
                        </span>
                    </div>
                    <span class="text-xs text-slate-400">Cicilan {{ $modal->cicilan_ke }} / {{ $modal->tenor }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-400">
                                <th class="px-4 py-3 text-center w-10">#</th>
                                <th class="px-4 py-3 text-left">Jatuh tempo</th>
                                <th class="px-4 py-3 text-right">Nominal</th>
                                <th class="px-4 py-3 text-right">Denda</th>
                                <th class="px-4 py-3 text-left">Tgl bayar</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($modal->cicilans as $c)
                                @php
                                    $isNext = $c->cicilan_ke == $modal->cicilan_ke + 1;
                                    $rowClass = match ($c->status) {
                                        'sudah_bayar' => 'bg-emerald-50/40',
                                        'terlambat' => 'bg-amber-50/40',
                                        default => $isNext ? 'bg-blue-50/40' : '',
                                    };
                                    [$badgeClass, $badgeLabel] = match ($c->status) {
                                        'sudah_bayar' => ['bg-emerald-100 text-emerald-700', 'Lunas'],
                                        'terlambat' => ['bg-amber-100 text-amber-700', 'Terlambat'],
                                        'sebagian' => ['bg-blue-100 text-blue-700', 'Sebagian'],
                                        default => ['bg-red-100 text-red-700', 'Belum'],
                                    };
                                @endphp
                                <tr class="{{ $rowClass }} hover:brightness-[0.97] transition-all">
                                    <td class="px-4 py-2.5 text-center font-semibold text-slate-500">{{ $c->cicilan_ke }}</td>
                                    <td class="px-4 py-2.5 text-slate-500 whitespace-nowrap">{{ $c->tanggal_jatuh_tempo->format('d M Y') }}</td>
                                    <td class="px-4 py-2.5 text-right font-semibold text-slate-700">{{ number_format($c->nominal_cicilan, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2.5 text-right {{ $c->denda > 0 ? 'font-semibold text-red-600' : 'text-slate-300' }}">
                                        {{ $c->denda > 0 ? number_format($c->denda, 0, ',', '.') : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-500 whitespace-nowrap">
                                        {{ $c->tanggal_bayar ? $c->tanggal_bayar->format('d M Y') : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                            {{ $badgeLabel }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        @if($c->status === 'sudah_bayar')
                                            <svg class="w-5 h-5 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <button onclick="bayarCicilan({{ $modal->id }}, {{ $c->id }}, {{ $c->cicilan_ke }}, {{ $c->nominal_cicilan }})"
                                                class="inline-flex items-center gap-1 text-xs font-semibold bg-emerald-500 hover:bg-emerald-600 text-white px-2.5 py-1 rounded-lg transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Bayar
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50 border-t border-slate-200 text-sm font-semibold text-slate-600">
                                <td colspan="2" class="px-4 py-3 text-right text-xs uppercase tracking-wider text-slate-400">Total</td>
                                <td class="px-4 py-3 text-right">{{ number_format($modal->cicilans->sum('nominal_cicilan'), 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-red-600">{{ number_format($modal->cicilans->sum('denda'), 0, ',', '.') ?: '—' }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- TAB: INFO PINJAMAN --}}
            <div id="tab-info" class="hidden p-5">
                <div class="grid grid-cols-2 gap-x-8">
                    @php
                        $rows = [
                            ['Jumlah pinjaman', 'Rp ' . number_format($modal->jumlah_pinjaman, 0, ',', '.')],
                            ['Nominal pencairan', 'Rp ' . number_format($modal->nominal_pencairan, 0, ',', '.')],
                            ['Total bunga', 'Rp ' . number_format($modal->total_bunga, 0, ',', '.')],
                            ['Tenor', $modal->tenor . ' ' . $modal->satuan_tenor],
                            ['Cicilan / periode', 'Rp ' . number_format($modal->cicilan_per_periode, 0, ',', '.')],
                            ['Jenis pinjaman', ucfirst($modal->jenis_pinjaman)],
                            ['Tgl pinjaman', $modal->tanggal_pinjaman->format('d M Y')],
                            ['Tgl pencairan', $modal->tanggal_pencairan->format('d M Y')],
                            ['Jatuh tempo', $modal->tanggal_jatuh_tempo->format('d M Y')],
                            ['Kode pinjaman', $modal->kode_pinjaman],
                        ];
                    @endphp
                    @foreach($rows as [$lbl, $val])
                        <div class="flex flex-col gap-1 py-3 border-b border-dashed border-slate-100
                            {{ $loop->odd ? 'pr-8 border-r border-slate-100' : 'pl-0' }}">
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $lbl }}</span>
                            <span class="text-sm font-semibold text-slate-700">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
                @if($modal->keterangan)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Keterangan</p>
                        <p class="text-sm text-slate-700 mt-1">{{ $modal->keterangan }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ── MODAL BAYAR ── --}}
    <div id="modalBayar" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">

            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a5 5 0 00-10 0v2M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-slate-800">Bayar cicilan</span>
                </div>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-5 py-5 space-y-4">
                <div id="infoBayar" class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-400"></p>
                    <p class="text-base font-bold text-slate-800 mt-0.5"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal pembayaran</label>
                    <input type="date" id="tglBayar" value="{{ date('Y-m-d') }}"
                        class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Denda (Rp)</label>
                    <input type="number" id="inputDenda" min="0" value="0" placeholder="0"
                        class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Keterangan</label>
                    <input type="text" id="ketBayar" placeholder="Transfer BRI, cash, dll…"
                        class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition">
                </div>
            </div>

            <div class="flex justify-end gap-2 px-5 pb-5">
                <button onclick="closeModal()"
                    class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button onclick="konfirmasiBayar()"
                    class="inline-flex items-center gap-1.5 px-5 py-2 text-sm font-semibold bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Konfirmasi bayar
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const fmt = n => 'Rp ' + Number(n).toLocaleString('id-ID');
        let activeCicilanId = null;
        const modalId = {{ $modal->id }};

        function switchTab(tab) {
            const isCicilan = tab === 'cicilan';
            document.getElementById('tab-cicilan').classList.toggle('hidden', !isCicilan);
            document.getElementById('tab-info').classList.toggle('hidden', isCicilan);

            const btnCicilan = document.getElementById('tab-btn-cicilan');
            const btnInfo    = document.getElementById('tab-btn-info');

            btnCicilan.classList.toggle('border-emerald-500', isCicilan);
            btnCicilan.classList.toggle('text-emerald-600',   isCicilan);
            btnCicilan.classList.toggle('border-transparent', !isCicilan);
            btnCicilan.classList.toggle('text-slate-400',     !isCicilan);

            btnInfo.classList.toggle('border-emerald-500', !isCicilan);
            btnInfo.classList.toggle('text-emerald-600',   !isCicilan);
            btnInfo.classList.toggle('border-transparent', isCicilan);
            btnInfo.classList.toggle('text-slate-400',     isCicilan);
        }

        function openModal()  { const m = document.getElementById('modalBayar'); m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeModal() { const m = document.getElementById('modalBayar'); m.classList.add('hidden'); m.classList.remove('flex'); }

        document.getElementById('modalBayar').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        function bayarCicilan(mid, cid, ke, nominal) {
            activeCicilanId = cid;
            const box = document.getElementById('infoBayar');
            box.querySelector('p:first-child').textContent = `Cicilan ke-${ke}`;
            box.querySelector('p:last-child').textContent  = fmt(nominal);
            openModal();
        }

        async function konfirmasiBayar() {
            const payload = {
                cicilan_id:   activeCicilanId,
                tanggal_bayar: document.getElementById('tglBayar').value,
                denda:         document.getElementById('inputDenda').value || 0,
                keterangan:    document.getElementById('ketBayar').value,
            };

            const res  = await fetch(`/modals/${modalId}/bayar-cicilan`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(payload),
            });
            const json = await res.json();

            if (json.success) {
                await Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: json.message,
                    confirmButtonColor: '#059669', timer: 2000, timerProgressBar: true,
                });
                location.reload();
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: json.message, confirmButtonColor: '#dc2626' });
            }
        }
    </script>
@endpush