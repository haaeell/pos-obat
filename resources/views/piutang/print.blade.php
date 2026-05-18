<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Piutang- {{ $piutang->nomor }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #1e293b;
            margin: 0;
            background: #f8fafc;
        }

        .page {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .header {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 32px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .company-wrap {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .logo {
            width: 78px;
            height: 78px;
            object-fit: contain;
            background: white;
            border-radius: 14px;
            padding: 6px;
        }

        .store-name {
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
        }

        .store-info {
            font-size: 12px;
            opacity: .9;
            margin-top: 3px;
        }

        .doc-title {
            margin-top: 14px;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .company h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .company p {
            margin: 6px 0 0;
            opacity: .9;
            font-size: 13px;
        }

        .invoice-box {
            text-align: right;
        }

        .invoice-box .label {
            font-size: 12px;
            opacity: .8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .invoice-box .number {
            font-size: 22px;
            font-weight: bold;
            margin-top: 4px;
        }

        .content {
            padding: 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px;
            background: #fff;
        }

        .card-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 12px;
            letter-spacing: .5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 10px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            color: #64748b;
        }

        .info-value {
            font-weight: 600;
            text-align: right;
        }

        .status {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
        }

        .status.lunas {
            background: #dcfce7;
            color: #166534;
        }

        .status.sebagian {
            background: #fef3c7;
            color: #92400e;
        }

        .status.belum {
            background: #fee2e2;
            color: #991b1b;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #0f172a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            background: #f1f5f9;
            color: #334155;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        table tbody td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .product-name {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .product-note {
            font-size: 12px;
            color: #64748b;
        }

        .summary {
            margin-top: 28px;
            margin-left: auto;
            width: 380px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }

        .summary table td {
            padding: 14px 18px;
        }

        .summary table tr:not(:last-child) td {
            border-bottom: 1px solid #e2e8f0;
        }

        .summary .grand-total {
            background: #f8fafc;
            font-size: 18px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .signature {
            width: 250px;
            text-align: center;
        }

        .signature .line {
            margin-top: 70px;
            border-top: 1px solid #94a3b8;
            padding-top: 10px;
            font-weight: 600;
        }

        .print-btn {
            position: fixed;
            right: 25px;
            top: 25px;
            background: #059669;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
        }

        @media print {

            body {
                background: white;
            }

            .page {
                margin: 0;
                box-shadow: none;
                max-width: 100%;
                border-radius: 0;
            }

            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

    <button onclick="window.print()" class="print-btn">
        Print
    </button>

    <div class="page">

        {{-- HEADER --}}
        <div class="header">

            <div class="header-top">

                <div class="company-wrap">

                    @if ($toko?->logo)
                        <img src="{{ asset('storage/' . $toko->logo) }}" class="logo">
                    @endif

                    <div class="company">
                        <div class="store-name">
                            {{ $toko->nama_toko ?? 'Nama Toko' }}
                        </div>

                        <div class="store-info">
                            {{ $toko->alamat ?? '-' }}
                        </div>

                        <div class="store-info">
                            Telp: {{ $toko->telepon ?? '-' }}

                            @if ($toko?->email)
                                | {{ $toko->email }}
                            @endif
                        </div>

                        <div class="doc-title">
                            DETAIL PIUTANG
                        </div>

                        <p>
                            Dokumen rincian piutang pelanggan dan riwayat pembayaran
                        </p>
                    </div>

                </div>

                <div class="invoice-box">
                    <div class="label">
                        Nomor Piutang
                    </div>

                    <div class="number">
                        {{ $piutang->nomor }}
                    </div>
                </div>

            </div>

        </div>

        <div class="content">

            {{-- INFO --}}
            <div class="grid">

                {{-- DATA PELANGGAN --}}
                <div class="card">

                    <div class="card-title">
                        Informasi Pelanggan
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            Nama
                        </div>

                        <div class="info-value">
                            {{ $piutang->pelanggan->nama }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            Telepon
                        </div>

                        <div class="info-value">
                            {{ $piutang->pelanggan->telepon ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            Alamat
                        </div>

                        <div class="info-value">
                            {{ $piutang->pelanggan->alamat ?? '-' }}
                        </div>
                    </div>

                </div>

                {{-- DATA TRANSAKSI --}}
                <div class="card">

                    <div class="card-title">
                        Informasi Transaksi
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            Nomor Transaksi
                        </div>

                        <div class="info-value">
                            {{ $piutang->transaksi->nomor ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            Tanggal
                        </div>

                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($piutang->tanggal_transaksi)->translatedFormat('d F Y') }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            Status
                        </div>

                        <div class="info-value">

                            @if ($piutang->status == 'lunas')
                                <span class="status lunas">
                                    Lunas
                                </span>
                            @elseif($piutang->status == 'sebagian')
                                <span class="status sebagian">
                                    Sebagian
                                </span>
                            @else
                                <span class="status belum">
                                    Belum Bayar
                                </span>
                            @endif

                        </div>
                    </div>

                </div>

            </div>

            {{-- DETAIL PRODUK --}}
            <div class="section-title">
                Detail Produk / Item Transaksi
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Produk</th>
                        <th width="100" class="text-right">Qty</th>
                        <th width="180" class="text-right">Harga</th>
                        <th width="180" class="text-right">Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($piutang->transaksi->detail ?? [] as $i => $detail)
                        <tr>

                            <td>
                                {{ $i + 1 }}
                            </td>

                            <td>

                                <div class="product-name">
                                    {{ $detail->produk->nama ?? $detail->nama_produk ?? '-' }}
                                </div>

                                <div class="product-note">

                                    @if (!empty($detail->catatan))
                                        Catatan :
                                        {{ $detail->catatan }}
                                    @else
                                        Produk transaksi pelanggan
                                    @endif

                                </div>

                            </td>

                            <td class="text-right">
                                {{ number_format($detail->qty ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="text-right">
                                Rp {{ number_format($detail->harga ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="text-right">
                                Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>

            {{-- RIWAYAT PEMBAYARAN --}}
            <div class="section-title" style="margin-top:35px">
                Riwayat Pembayaran
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="170">Tanggal</th>
                        <th width="140">Metode</th>
                        <th>Catatan</th>
                        <th width="180" class="text-right">Jumlah</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($piutang->pembayaran as $item)
                        <tr>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>

                            <td>
                                {{ ucfirst($item->metode_bayar) }}
                            </td>

                            <td>

                                <div style="font-weight:600">
                                    {{ $item->user->nama ?? '-' }}
                                </div>

                                <div class="product-note">
                                    {{ $item->catatan ?? '-' }}
                                </div>

                            </td>

                            <td class="text-right" style="font-weight:bold;color:#059669">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>

                        </tr>
                    @empty

                        <tr>
                            <td colspan="4" style="text-align:center;padding:30px;color:#64748b">
                                Belum ada pembayaran
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

            {{-- SUMMARY --}}
            <div class="summary">

                <table>

                    <tr>
                        <td>Total Tagihan</td>

                        <td class="text-right" style="font-weight:700">
                            Rp {{ number_format($piutang->total_tagihan, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <td>Sudah Dibayar</td>

                        <td class="text-right" style="font-weight:700;color:#059669">
                            Rp {{ number_format($piutang->sudah_dibayar, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr class="grand-total">
                        <td>Sisa Piutang</td>

                        <td class="text-right" style="color:#dc2626">
                            Rp {{ number_format($piutang->sisa_tagihan, 0, ',', '.') }}
                        </td>
                    </tr>

                </table>

            </div>

            {{-- TTD --}}
            <div class="footer">

                <div class="signature">
                    Mengetahui

                    <div class="line">
                        {{ $toko->nama_toko ?? 'Nama Toko' }}
                    </div>
                </div>

                <div class="signature">
                    Pelanggan

                    <div class="line">
                        {{ $piutang->pelanggan->nama }}
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>

<script>
    window.print();
</script>

</html>