<?php

namespace App\Imports;

use App\Models\BarangMasuk;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\StokBatch;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProdukImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nama = trim($row['nama_produk'] ?? '');
            if (!$nama) continue;

            $namaKat = trim($row['kategori'] ?? '');
            if (!$namaKat) continue;

            $kategori = Kategori::firstOrCreate(
                ['nama' => $namaKat],
                ['is_aktif' => true]
            );

            $supplier = null;
            $namaSup  = trim($row['supplier'] ?? '');
            if ($namaSup) {
                $supplier = Supplier::where('nama', $namaSup)->first();
            }

            // Generate kode otomatis
            $last  = Produk::withTrashed()->orderByDesc('id')->first();
            $nomor = $last ? (int) substr($last->kode, 4) + 1 : 1;
            $kode  = 'PRD-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

            $hargaJual  = (float) ($row['harga_jual']   ?? 0);
            $stokMin    = (int)   ($row['stok_minimum'] ?? 5);
            $stokAwal   = (int)   ($row['stok_awal']    ?? 0);
            $hargaModal = (float) ($row['harga_modal']  ?? 0);
            $satuan     = trim($row['satuan']  ?? 'pcs');
            $catatan    = trim($row['catatan'] ?? '');

            $produk = Produk::create([
                'kode'          => $kode,
                'nama'          => $nama,
                'kategori_id'   => $kategori->id,
                'supplier_id'   => $supplier?->id,
                'satuan'        => $satuan,
                'harga_jual'    => $hargaJual,
                'stok_minimum'  => $stokMin,
                'stok_saat_ini' => 0,
                'catatan'       => $catatan ?: null,
                'is_aktif'      => true,
            ]);

            if ($stokAwal > 0) {
                $bm = BarangMasuk::create([
                    'nomor'       => 'SA-' . now()->format('Ymd') . '-' . str_pad($produk->id, 3, '0', STR_PAD_LEFT),
                    'jenis'       => 'stok_awal',
                    'supplier_id' => null,
                    'user_id'     => Auth::id(),
                    'tanggal'     => now()->toDateString(),
                    'catatan'     => 'Import stok awal: ' . $produk->nama,
                ]);

                $detail = $bm->detail()->create([
                    'produk_id'   => $produk->id,
                    'jumlah'      => $stokAwal,
                    'harga_modal' => $hargaModal,
                    'subtotal'    => $stokAwal * $hargaModal,
                ]);

                StokBatch::create([
                    'produk_id'              => $produk->id,
                    'barang_masuk_detail_id' => $detail->id,
                    'tanggal_masuk'          => now()->toDateString(),
                    'harga_modal'            => $hargaModal,
                    'jumlah_awal'            => $stokAwal,
                    'jumlah_tersisa'         => $stokAwal,
                ]);

                $produk->update(['stok_saat_ini' => $stokAwal]);
            }
        }
    }
}
