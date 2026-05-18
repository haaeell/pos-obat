<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $transaksi->nomor }}</title>
    <style>
        /* ─── RESET ───────────────────────────── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ─── BASE (screen preview) ───────────── */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.6;
            color: #000;
            background: #d0d0d0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px;
        }

        /* ─── KERTAS A4 CONTINUOUS FORM ────────── */
        .kertas {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            padding: 18mm 20mm 20mm 20mm;
            position: relative;
            /* Simulasi lubang sprocket di preview */
            border-left: 12mm solid #e8e8e8;
            border-right: 12mm solid #e8e8e8;
        }

        /* Lubang sprocket simulasi */
        .kertas::before,
        .kertas::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 12mm;
            background-image: radial-gradient(circle, #bbb 4px, transparent 4px);
            background-size: 12mm 12mm;
            background-repeat: repeat-y;
            background-position: center 6mm;
        }

        .kertas::before {
            left: 0;
        }

        .kertas::after {
            right: 0;
        }

        /* ─── JUDUL STRUK ────────────────────── */
        .struk-title {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 14px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }

        /* ─── HEADER INFO ─────────────────────── */
        .header-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 20px;
            margin-bottom: 14px;
        }

        .info-row {
            display: flex;
            gap: 4px;
            font-size: 12px;
            line-height: 1.8;
        }

        .info-label {
            min-width: 120px;
            color: #000;
        }

        .info-label::after {
            content: ':';
        }

        .info-val {
            font-weight: 600;
            flex: 1;
        }

        /* ─── GARIS ───────────────────────────── */
        .garis-solid {
            border: none;
            border-top: 1.5px solid #000;
            margin: 8px 0;
        }

        .garis-dash {
            border: none;
            border-top: 1px dashed #555;
            margin: 6px 0;
        }

        /* ─── TABEL PRODUK ────────────────────── */
        .tbl-produk {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin: 10px 0;
        }

        .tbl-produk thead th {
            background: #000;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .tbl-produk thead th.text-right {
            text-align: right;
        }

        .tbl-produk thead th.text-center {
            text-align: center;
        }

        .tbl-produk tbody tr {
            border-bottom: 1px dashed #ccc;
        }

        .tbl-produk tbody td {
            padding: 6px 8px;
            vertical-align: top;
        }

        .tbl-produk tfoot td {
            padding: 4px 8px;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .diskon-row td {
            color: #555;
            font-size: 11px;
            padding: 2px 8px 5px !important;
        }

        /* ─── RINGKASAN KANAN ────────────────── */
        .ringkasan {
            width: 220px;
            margin-left: auto;
            margin-top: 8px;
        }

        .ringkasan-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            padding: 3px 0;
            border-bottom: 1px dashed #ddd;
        }

        .ringkasan-row:last-child {
            border: none;
        }

        .ringkasan-row .lbl {
            color: #333;
        }

        .ringkasan-row .val {
            font-weight: 600;
            text-align: right;
        }

        .ringkasan-total {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 700;
            padding: 5px 0;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            margin: 4px 0;
        }

        /* ─── STATUS BAYAR ───────────────────── */
        .status-area {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 20px;
        }

        .status-badge-wrap {
            font-size: 13px;
        }

        .status-badge-wrap .badge-label {
            font-size: 11px;
            color: #555;
            margin-bottom: 4px;
        }

        .status-badge {
            display: inline-block;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 2px;
            padding: 4px 14px;
            border: 2px solid #000;
        }

        .status-lunas {
            border-color: #000;
        }

        .status-sebagian {
            border-color: #000;
        }

        .status-belum {
            border-color: #000;
        }

        /* ─── TANDA TANGAN / CATATAN ─────────── */
        .ttd-area {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            margin-top: 30px;
            font-size: 11px;
        }

        .ttd-col {
            text-align: center;
        }

        .ttd-col .ttd-title {
            font-weight: 700;
            margin-bottom: 50px;
        }

        .ttd-col .ttd-line {
            border-top: 1px solid #000;
            padding-top: 4px;
        }

        /* ─── FOOTER ─────────────────────────── */
        .footer-struk {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #555;
            border-top: 1px dashed #aaa;
            padding-top: 8px;
        }

        /* ─── TOMBOL SCREEN ──────────────────── */
        .screen-only {
            margin-top: 20px;
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-print {
            padding: 10px 28px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        .btn-back {
            padding: 10px 20px;
            background: #fff;
            color: #374151;
            border: 1.5px solid #999;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            font-family: 'Courier New', monospace;
        }

        /* ─── PRINT ──────────────────────────── */
        @media print {
            body {
                background: none;
                padding: 0;
                display: block;
            }

            .kertas {
                width: 210mm;
                min-height: auto;
                border: none;
                padding: 10mm 15mm 15mm 15mm;
            }

            /* Sembunyikan simulasi sprocket saat print */
            .kertas::before,
            .kertas::after {
                display: none;
            }

            .screen-only {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div>
        {{-- ══════════ KERTAS STRUK ══════════ --}}
        <div class="kertas" id="strukPaper">

            {{-- JUDUL --}}
            <div class="struk-title">Struk Penjualan</div>

            {{-- HEADER GRID: Info Toko kiri, Info Transaksi kanan --}}
            <div class="header-grid">

                {{-- KIRI: Info Toko & Pelanggan --}}
                <div>
                    @if ($toko?->logo)
                        <div style="margin-bottom:6px;">
                            <img src="{{ asset('storage/' . $toko->logo) }}" alt="Logo"
                                style="max-height:45px; object-fit:contain;">
                        </div>
                    @endif

                    <div class="info-row">
                        <span class="info-label">Nama Toko</span>
                        <span class="info-val">{{ $toko->nama_toko ?? 'TOKO TANI MAKMUR' }}</span>
                    </div>
                    @if ($toko?->alamat)
                        <div class="info-row">
                            <span class="info-label">Alamat</span>
                            <span class="info-val">{{ $toko->alamat }}</span>
                        </div>
                    @endif
                    @if ($toko?->telepon)
                        <div class="info-row">
                            <span class="info-label">Telepon</span>
                            <span class="info-val">{{ $toko->telepon }}</span>
                        </div>
                    @endif
                    @if ($transaksi->pelanggan)
                        <div class="info-row" style="margin-top:8px;">
                            <span class="info-label">Pelanggan</span>
                            <span class="info-val">{{ $transaksi->pelanggan->nama }}</span>
                        </div>
                    @endif
                </div>

                {{-- KANAN: Info Nomor & Tanggal --}}
                <div>
                    <div class="info-row">
                        <span class="info-label">No. Struk</span>
                        <span class="info-val">{{ $transaksi->nomor }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span
                            class="info-val">{{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Pukul</span>
                        <span class="info-val">{{ $transaksi->created_at->format('H:i') }} WIB</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kasir</span>
                        <span class="info-val">{{ $transaksi->user->nama ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-val">{{ strtoupper(str_replace('_', ' ', $transaksi->status_bayar)) }}</span>
                    </div>
                </div>
            </div>

            <hr class="garis-solid">

            {{-- TABEL PRODUK --}}
            <table class="tbl-produk">
                <thead>
                    <tr>
                        <th style="width:30px;">No</th>
                        <th>Nama Produk</th>
                        <th class="text-center" style="width:50px;">Qty</th>
                        <th class="text-center" style="width:40px;">Sat</th>
                        <th class="text-right" style="width:100px;">Harga Satuan</th>
                        <th class="text-right" style="width:90px;">Diskon</th>
                        <th class="text-right" style="width:110px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi->detail as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->produk->nama }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-center">{{ $item->produk->satuan }}</td>
                            <td class="text-right">{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td class="text-right">
                                @if ($item->diskon_item > 0)
                                    {{ number_format($item->diskon_item * $item->jumlah, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach

                    {{-- Baris kosong jika item sedikit, biar tabel tidak pendek banget --}}
                    @if ($transaksi->detail->count() < 5)
                        @for ($e = $transaksi->detail->count(); $e < 5; $e++)
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
                    @endif
                </tbody>
            </table>

            <hr class="garis-solid">

            {{-- RINGKASAN PEMBAYARAN (rata kanan) --}}
            <div class="ringkasan">
                <div class="ringkasan-row">
                    <span class="lbl">Subtotal</span>
                    <span class="val">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($transaksi->diskon_nominal > 0)
                    <div class="ringkasan-row">
                        <span class="lbl">Diskon</span>
                        <span class="val">- Rp {{ number_format($transaksi->diskon_nominal, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="ringkasan-total">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                </div>
                @if ($transaksi->status_bayar !== 'belum_bayar')
                    <div class="ringkasan-row">
                        <span class="lbl">Jumlah Bayar</span>
                        <span class="val">Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($transaksi->status_bayar === 'lunas' && $transaksi->kembalian > 0)
                    <div class="ringkasan-row">
                        <span class="lbl">Kembali</span>
                        <span class="val">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($transaksi->sisa_tagihan > 0)
                    <div class="ringkasan-row">
                        <span class="lbl">Sisa Tagihan</span>
                        <span class="val">Rp {{ number_format($transaksi->sisa_tagihan, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            {{-- STATUS & TANDA TANGAN --}}
            <div class="status-area">
                <div class="status-badge-wrap">
                    <div class="badge-label">Status Pembayaran</div>
                    <div
                        class="status-badge status-{{ $transaksi->status_bayar === 'lunas' ? 'lunas' : ($transaksi->status_bayar === 'sebagian' ? 'sebagian' : 'belum') }}">
                        ★ {{ strtoupper(str_replace('_', ' ', $transaksi->status_bayar)) }} ★
                    </div>
                </div>
            </div>

            {{-- TANDA TANGAN --}}
            <div class="ttd-area">
                <div class="ttd-col">
                    <div class="ttd-title">Pelanggan</div>
                    <div class="ttd-line">
                        {{ $transaksi->pelanggan->nama ?? '( ........................ )' }}<br>
                        {{ $transaksi->pelanggan->telepon ?? '' }}
                    </div>
                </div>
                <div class="ttd-col">
                    <div class="ttd-title">Mengetahui</div>
                    <div class="ttd-line">( .................. )</div>
                </div>
                <div class="ttd-col">
                    <div class="ttd-title">Kasir</div>
                    <div class="ttd-line">{{ $transaksi->user->nama ?? '( ........................ )' }}</div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="footer-struk">
                <div>Terima kasih atas kepercayaan Anda berbelanja di {{ $toko->nama_toko ?? 'Toko Kami' }}!</div>
                @if ($toko?->footer_struk)
                    <div>{{ $toko->footer_struk }}</div>
                @else
                    <div>Barang yang dibeli tidak dapat dikembalikan &bull; Simpan struk ini sebagai bukti pembelian.</div>
                @endif
            </div>

        </div>

        {{-- TOMBOL SCREEN ONLY --}}
        <div class="screen-only">
            <button class="btn-back" onclick="history.back()">&#8592; Kembali</button>
            <button class="btn-print" onclick="window.print()">&#128424; Cetak Struk</button>
        </div>
    </div>
</body>

<script>
    window.print();
</script>

</html>