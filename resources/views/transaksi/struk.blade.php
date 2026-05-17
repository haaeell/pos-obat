<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $transaksi->nomor }}</title>
    <style>
        /* ─── RESET ─────────────────── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ─── BASE ──────────────────── */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.55;
            color: #000;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* ─── KERTAS 58mm ───────────── */
        .kertas {
            width: 216px;
            /* 58mm ≈ 219px; pakai 216 untuk margin printer */
            background: #fff;
            padding: 10px 8px 30px;
            border: 1px solid #ccc;
        }

        /* ─── TEKS ──────────────────── */
        .center {
            text-align: center;
        }

        .bold {
            font-weight: 700;
        }

        .muted {
            color: #555;
        }

        .sm {
            font-size: 10px;
        }

        .lg {
            font-size: 13px;
        }

        /* ─── BARIS ─────────────────── */
        .row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .row .kanan {
            text-align: right;
            white-space: nowrap;
            margin-left: 4px;
        }

        /* ─── GARIS PUTUS ───────────── */
        hr {
            border: none;
            border-top: 1px dashed #888;
            margin: 5px 0;
        }

        hr.solid {
            border-top: 1px solid #000;
        }

        /* ─── TOTAL ─────────────────── */
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            font-size: 12px;
            padding-top: 3px;
        }

        /* ─── ITEM ──────────────────── */
        .item {
            margin-bottom: 4px;
        }

        .item-nama {
            font-weight: 700;
            word-break: break-word;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            color: #555;
        }

        /* ─── STATUS ────────────────── */
        .status-badge {
            text-align: center;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 1px;
            padding: 3px 0;
        }

        .status-lunas {
            color: #000;
        }

        .status-sebagian {
            color: #555;
        }

        .status-belum {
            color: #555;
        }

        /* ─── BARCODE SIM ───────────── */
        .barcode {
            font-family: 'Courier New', monospace;
            font-size: 36px;
            letter-spacing: -4px;
            line-height: 1;
            text-align: center;
            margin: 5px 0 2px;
            color: #000;
        }

        .barcode-num {
            text-align: center;
            font-size: 9px;
            letter-spacing: 0;
            color: #555;
        }

        /* ─── TOMBOL (screen only) ───── */
        .screen-only {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-print {
            padding: 10px 24px;
            background: #059669;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-back {
            padding: 10px 20px;
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
        }

        /* ─── PRINT ─────────────────── */
        @media print {
            body {
                background: none;
                padding: 0;
            }

            .kertas {
                border: none;
                width: 58mm;
                padding: 0 2mm 10mm;
            }

            .screen-only {
                display: none !important;
            }

            @page {
                size: 58mm auto;
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div>
        {{-- ══════════════ KERTAS STRUK ══════════════ --}}
        <div class="kertas" id="strukPaper">

            {{-- HEADER TOKO --}}
            @if ($toko?->logo)
                <div class="center" style="margin-bottom:4px;">
                    <img src="{{ asset('storage/' . $toko->logo) }}" alt="Logo"
                        style="max-width:80px; max-height:60px; object-fit:contain;">
                </div>
            @endif
            <div class="center bold lg">{{ $toko->nama_toko ?? 'TOKO TANI MAKMUR' }}</div>
            @if ($toko?->alamat)
                <div class="center muted sm">{{ $toko->alamat }}</div>
            @endif
            @if ($toko?->telepon)
                <div class="center muted sm">Telp: {{ $toko->telepon }}</div>
            @endif

            <hr>

            {{-- INFO TRANSAKSI --}}
            <div class="row">
                <span>No</span>
                <span class="kanan sm">{{ $transaksi->nomor }}</span>
            </div>
            <div class="row">
                <span>Tgl</span>
                <span class="kanan">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</span>
            </div>
            <div class="row">
                <span>Pukul</span>
                <span class="kanan">{{ $transaksi->created_at->format('H:i') }}</span>
            </div>
            <div class="row">
                <span>Kasir</span>
                <span class="kanan">{{ $transaksi->user->nama ?? '-' }}</span>
            </div>
            @if ($transaksi->pelanggan)
                <div class="row">
                    <span>Pelanggan</span>
                    <span class="kanan">{{ $transaksi->pelanggan->nama }}</span>
                </div>
            @endif

            <hr>

            {{-- ITEM --}}
            @foreach ($transaksi->detail as $item)
                <div class="item">
                    <div class="item-nama">{{ $item->produk->nama }}</div>
                    <div class="item-detail">
                        <span>{{ $item->jumlah }} {{ $item->produk->satuan }} ×
                            {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                        <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($item->diskon_item > 0)
                        <div class="item-detail">
                            <span>Diskon item</span>
                            <span>- {{ number_format($item->diskon_item * $item->jumlah, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            @endforeach

            <hr>

            {{-- RINGKASAN PEMBAYARAN --}}
            <div class="row">
                <span>Subtotal</span>
                <span class="kanan">{{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
            </div>

            @if ($transaksi->diskon_nominal > 0)
                <div class="row">
                    <span>Diskon</span>
                    <span class="kanan">- {{ number_format($transaksi->diskon_nominal, 0, ',', '.') }}</span>
                </div>
            @endif

            <hr class="solid">
            <div class="total-row">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
            </div>
            <hr class="solid">

            @if ($transaksi->status_bayar !== 'belum_bayar')
                <div class="row" style="margin-top:3px;">
                    <span>Bayar</span>
                    <span class="kanan">{{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</span>
                </div>
            @endif

            @if ($transaksi->status_bayar === 'lunas' && $transaksi->kembalian > 0)
                <div class="row bold">
                    <span>Kembali</span>
                    <span class="kanan">{{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
                </div>
            @endif

            @if ($transaksi->sisa_tagihan > 0)
                <div class="row bold">
                    <span>Sisa Tagihan</span>
                    <span class="kanan">{{ number_format($transaksi->sisa_tagihan, 0, ',', '.') }}</span>
                </div>
            @endif

            <hr>

            {{-- STATUS BAYAR --}}
            <div class="status-badge status-{{ $transaksi->status_bayar === 'lunas' ? 'lunas' : 'sebagian' }}">
                ★ {{ strtoupper(str_replace('_', ' ', $transaksi->status_bayar)) }} ★
            </div>

            <hr>

            {{-- FOOTER TOKO --}}
            <div class="center sm" style="margin-top:3px;">Terima kasih atas kepercayaan Anda!</div>
            @if ($toko?->footer_struk)
                <div class="center sm">{{ $toko->footer_struk }}</div>
            @else
                <div class="center sm">Barang yang dibeli tidak dapat dikembalikan.</div>
                <div class="center sm">Simpan struk ini sebagai bukti pembelian.</div>
            @endif

        </div>

        <div class="screen-only">
            <button class="btn-back" onclick="history.back()">← Kembali</button>
            <button class="btn-print" onclick="window.print()">🖨 Cetak Struk</button>
        </div>
    </div>

    <script>
    </script>

</body>

</html>