<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            padding: 24px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #059669;
        }

        .header-left h1 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .header-left p {
            font-size: 10px;
            color: #64748b;
        }

        .header-right {
            text-align: right;
        }

        .header-right .badge {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .bg-emerald {
            background: #059669;
        }

        .bg-red {
            background: #dc2626;
        }

        .bg-orange {
            background: #ea580c;
        }

        .bg-slate {
            background: #475569;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        table tr:last-child {
            border-bottom: none;
        }

        table td {
            padding: 6px 8px;
        }

        table td:last-child {
            text-align: right;
            font-weight: 600;
        }

        table tr.total {
            background: #ecfdf5;
        }

        table tr.total td {
            font-weight: 700;
        }

        table tr.total-red {
            background: #fef2f2;
        }

        table tr.total-red td {
            font-weight: 700;
        }

        table tr.negative td:last-child {
            color: #ef4444;
        }

        table tr.orange td:last-child {
            color: #ea580c;
        }

        .grid {
            display: table;
            width: 100%;
        }

        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 12px;
        }

        .col:last-child {
            padding-right: 0;
            padding-left: 12px;
            border-left: 1px solid #e2e8f0;
        }

        .footer {
            margin-top: 32px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #94a3b8;
        }

        /* ── Stat boxes row 1 ── */
        .stat-grid {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }

        .stat-box {
            display: table-cell;
            width: 20%;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 10px;
            background: #f8fafc;
        }

        .stat-box+.stat-box {
            border-left: none;
        }

        .stat-box.orange {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .stat-box.orange .stat-value {
            color: #c2410c;
        }

        .stat-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 3px;
        }

        .stat-value {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
        }

        .stat-sub {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .positive {
            color: #059669;
        }

        .negative {
            color: #ef4444;
        }

        .orange-text {
            color: #ea580c;
        }

        .badge-sm {
            display: inline-block;
            font-size: 8px;
            font-weight: 700;
            padding: 1px 5px;
            border-radius: 10px;
        }

        .badge-red {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge-orange {
            background: #ffedd5;
            color: #c2410c;
        }
    </style>
</head>

<body>

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-left">
            <h1>{{ $toko->nama_toko ?? 'Laporan Keuangan' }}</h1>
            <p>{{ $toko->alamat ?? '' }}</p>
            <p style="margin-top:2px; font-weight:700; color:#059669;">LAPORAN KEUANGAN</p>
        </div>
        <div class="header-right">
            <div class="badge">
                {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} —
                {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
            </div>
            <p style="margin-top:8px; color:#94a3b8;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- ── STATISTIK UTAMA (5 kotak) ── --}}
    <div class="stat-grid">
        <div class="stat-box">
            <div class="stat-label">Penjualan Periode</div>
            <div class="stat-value">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</div>
            <div class="stat-sub">{{ $ringkasan['jumlah_transaksi'] }} transaksi</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Laba Kotor</div>
            <div class="stat-value {{ $ringkasan['laba_kotor'] >= 0 ? 'positive' : 'negative' }}">
                Rp {{ number_format($ringkasan['laba_kotor'], 0, ',', '.') }}
            </div>
            <div class="stat-sub">Margin {{ number_format($ringkasan['margin'], 1) }}%</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Saldo Kas</div>
            <div class="stat-value {{ $saldo['kas'] >= 0 ? 'positive' : 'negative' }}">
                Rp {{ number_format($saldo['kas'], 0, ',', '.') }}
            </div>
            <div class="stat-sub">Tunai + pencairan − pengeluaran</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Hutang Modal</div>
            <div class="stat-value negative">Rp {{ number_format($saldo['hutang_modal'], 0, ',', '.') }}</div>
            <div class="stat-sub">{{ $ringkasan['jumlah_modal_aktif'] }} pinjaman aktif</div>
        </div>
        {{-- BARU --}}
        <div class="stat-box orange">
            <div class="stat-label">Hutang Supplier</div>
            <div class="stat-value">Rp {{ number_format($saldo['hutang_supplier'], 0, ',', '.') }}</div>
            <div class="stat-sub">
                {{ $saldo['jumlah_hutang_supplier'] }} transaksi
                @if($saldo['hutang_supplier_jt'] > 0)
                    · ⚠ {{ $saldo['hutang_supplier_jt'] }} jatuh tempo
                @endif
            </div>
        </div>
    </div>

    {{-- ── POSISI KEUANGAN ── --}}
    <div class="grid">

        {{-- ASET + PENJUALAN --}}
        <div class="col">
            <div class="section">
                <div class="section-title bg-emerald">Aset</div>
                <table>
                    <tr>
                        <td>Kas</td>
                        <td>Rp {{ number_format($saldo['kas'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Piutang Pelanggan</td>
                        <td>Rp {{ number_format($saldo['piutang'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Nilai Stok Barang</td>
                        <td>Rp {{ number_format($saldo['nilai_stok'], 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total">
                        <td>Total Aset</td>
                        <td>Rp
                            {{ number_format($saldo['kas'] + $saldo['piutang'] + $saldo['nilai_stok'], 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <div class="section-title bg-slate">Ringkasan Penjualan</div>
                <table>
                    <tr>
                        <td>Total Penjualan</td>
                        <td>Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total HPP (FIFO)</td>
                        <td>Rp {{ number_format($ringkasan['total_hpp'], 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total">
                        <td>Laba Kotor</td>
                        <td class="{{ $ringkasan['laba_kotor'] >= 0 ? 'positive' : 'negative' }}">
                            Rp {{ number_format($ringkasan['laba_kotor'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Margin Laba</td>
                        <td>{{ number_format($ringkasan['margin'], 1) }}%</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- KEWAJIBAN & EKUITAS --}}
        <div class="col">
            <div class="section">
                <div class="section-title bg-red">Kewajiban & Ekuitas</div>
                <table>
                    <tr class="negative">
                        <td>Sisa Hutang Modal</td>
                        <td>Rp {{ number_format($saldo['hutang_modal'], 0, ',', '.') }}</td>
                    </tr>
                    {{-- BARU --}}
                    <tr class="orange">
                        <td>
                            Hutang Supplier
                            @if($saldo['hutang_supplier_jt'] > 0)
                                <span class="badge-sm badge-red">⚠ {{ $saldo['hutang_supplier_jt'] }} jatuh tempo</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($saldo['hutang_supplier'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8; padding-left:14px;">↳ Jumlah transaksi hutang</td>
                        <td style="color:#94a3b8;">{{ $saldo['jumlah_hutang_supplier'] }} transaksi</td>
                    </tr>
                    <tr class="total-red">
                        <td>Total Kewajiban</td>
                        <td class="negative">
                            Rp {{ number_format($saldo['hutang_modal'] + $saldo['hutang_supplier'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="total">
                        <td>Ekuitas Bersih</td>
                        <td class="{{ $saldo['ekuitas'] >= 0 ? 'positive' : 'negative' }}">
                            Rp {{ number_format($saldo['ekuitas'], 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <div class="section-title bg-slate">Piutang & Modal</div>
                <table>
                    <tr>
                        <td>Piutang Belum Lunas</td>
                        <td>Rp {{ number_format($saldo['piutang'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Pelanggan Piutang</td>
                        <td>{{ $ringkasan['jumlah_piutang'] }} pelanggan</td>
                    </tr>
                    <tr>
                        <td>Pinjaman Aktif</td>
                        <td>{{ $ringkasan['jumlah_modal_aktif'] }} pinjaman</td>
                    </tr>
                    <tr>
                        <td>Total Pencairan Modal</td>
                        <td>Rp {{ number_format($saldo['total_pencairan_modal'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Cicilan Terbayar</td>
                        <td>Rp {{ number_format($saldo['total_cicilan_terbayar'], 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

    <div class="footer">
        <span>{{ $toko->nama_toko ?? '' }} &mdash; Laporan Keuangan</span>
        <span>Periode {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d
            {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</span>
    </div>

</body>

</html>