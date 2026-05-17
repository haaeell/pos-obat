<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now         = Carbon::now();
        $tanggalAwal = '2025-01-02';

        DB::table('pengaturan_toko')->insert([
            'nama_toko'            => 'Toko XYZ',
            'alamat'               => 'Jl. Test No. 45, Sleman, Yogyakarta',
            'telepon'              => '0274-123456',
            'email'                => 'tokoobattani.makmurajaya@gmail.com',
            'logo'                 => null,
            'header_struk'         => "TOKO XYZ\nJl. Test No. 45, Sleman\nTelp: 0274-123456",
            'footer_struk'         => "Terima kasih telah berbelanja!\nBarang yang sudah dibeli tidak dapat dikembalikan.",
            'stok_minimum_default' => 10,
            'created_at'           => $now,
            'updated_at'           => $now,
        ]);

        DB::table('users')->insert([
            [
                'nama'       => 'Budi Santoso',
                'email'      => 'admin@gmail.com',
                'password'   => Hash::make('password'),
                'role'       => 'owner',
                'is_aktif'   => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama'       => 'Agus Purnomo',
                'email'      => 'kasir@gmail.com',
                'password'   => Hash::make('password'),
                'role'       => 'kasir',
                'is_aktif'   => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('kategori')->insert([
            ['nama' => 'Pestisida Insektisida', 'deskripsi' => 'Obat pembasmi serangga hama tanaman',       'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pestisida Fungisida',   'deskripsi' => 'Obat pembasmi jamur pada tanaman',          'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pestisida Herbisida',   'deskripsi' => 'Obat pembasmi gulma / rumput liar',         'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pestisida Rodentisida', 'deskripsi' => 'Obat pembasmi tikus di lahan pertanian',    'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pupuk Kimia',           'deskripsi' => 'Pupuk anorganik seperti Urea, SP-36, KCl',  'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pupuk Organik',         'deskripsi' => 'Pupuk organik cair maupun padat',           'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pupuk Daun & ZPT',      'deskripsi' => 'Pupuk daun dan zat pengatur tumbuh',        'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Benih & Bibit',         'deskripsi' => 'Benih padi, jagung, sayuran, dan palawija', 'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Alat Pertanian',        'deskripsi' => 'Sprayer, cangkul, dan peralatan pertanian', 'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('supplier')->insert([
            [
                'kode'          => 'SUP-001',
                'nama'          => 'PT Petrokimia Gresik',
                'kontak_person' => 'Hendra Wijaya',
                'telepon'       => '031-3981811',
                'email'         => 'distribusi@petrokimia-gresik.com',
                'alamat'        => 'Jl. Jenderal Ahmad Yani, Gresik, Jawa Timur',
                'catatan'       => 'Supplier utama pupuk kimia bersubsidi',
                'is_aktif'      => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'kode'          => 'SUP-002',
                'nama'          => 'CV Agrindo Jaya',
                'kontak_person' => 'Pak Sugeng',
                'telepon'       => '0274-555321',
                'email'         => 'agrindo.jaya@gmail.com',
                'alamat'        => 'Jl. Magelang KM 12, Sleman, Yogyakarta',
                'catatan'       => 'Distributor pestisida dan pupuk organik lokal',
                'is_aktif'      => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'kode'          => 'SUP-003',
                'nama'          => 'PT Syngenta Indonesia',
                'kontak_person' => 'Rani Kusuma',
                'telepon'       => '021-52997000',
                'email'         => 'info@syngenta.com',
                'alamat'        => 'Wisma GKBI Lt. 19, Jl. Jend. Sudirman, Jakarta',
                'catatan'       => 'Produsen pestisida dan benih unggul',
                'is_aktif'      => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);

        DB::table('pelanggan')->insert([
            [
                'nama'       => 'Pak Hardi Susanto',
                'telepon'    => '082345678901',
                'alamat'     => 'Dusun Tempel, Turi, Sleman',
                'catatan'    => 'Petani cabai, beli pestisida dan benih rutin',
                'is_aktif'   => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama'       => 'Bu Saminah',
                'telepon'    => '083456789012',
                'alamat'     => 'Jl. Kaliurang KM 14, Sleman',
                'catatan'    => 'Petani sayuran, pelanggan tetap benih dan pupuk daun',
                'is_aktif'   => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama'       => 'Koperasi Tani Maju Bersama',
                'telepon'    => '084567890123',
                'alamat'     => 'Jl. Godean KM 7, Sleman',
                'catatan'    => 'Koperasi petani, pembelian partai besar pupuk kimia',
                'is_aktif'   => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $produk = [
            ['kode' => 'PRD-001', 'nama' => 'Confidor 5 WP 100gr',     'kategori_id' => 1, 'supplier_id' => 2, 'satuan' => 'sachet', 'harga_jual' => 35000,  'stok_minimum' => 20],
            ['kode' => 'PRD-002', 'nama' => 'Regent 50 SC 100ml',      'kategori_id' => 1, 'supplier_id' => 2, 'satuan' => 'botol',  'harga_jual' => 42000,  'stok_minimum' => 15],
            ['kode' => 'PRD-003', 'nama' => 'Antracol 70 WP 250gr',    'kategori_id' => 2, 'supplier_id' => 2, 'satuan' => 'sachet', 'harga_jual' => 38000,  'stok_minimum' => 20],
            ['kode' => 'PRD-004', 'nama' => 'Roundup 486 SL 1 Liter',  'kategori_id' => 3, 'supplier_id' => 2, 'satuan' => 'botol',  'harga_jual' => 95000,  'stok_minimum' => 10],
            ['kode' => 'PRD-005', 'nama' => 'Klerat RM-B 50gr',        'kategori_id' => 4, 'supplier_id' => 2, 'satuan' => 'sachet', 'harga_jual' => 15000,  'stok_minimum' => 25],
            ['kode' => 'PRD-006', 'nama' => 'Pupuk Urea Subsidi 50kg', 'kategori_id' => 5, 'supplier_id' => 1, 'satuan' => 'karung', 'harga_jual' => 115000, 'stok_minimum' => 50],
            ['kode' => 'PRD-007', 'nama' => 'Pupuk NPK Phonska 50kg',  'kategori_id' => 5, 'supplier_id' => 1, 'satuan' => 'karung', 'harga_jual' => 130000, 'stok_minimum' => 50],
            ['kode' => 'PRD-008', 'nama' => 'NASA POC 500ml',          'kategori_id' => 6, 'supplier_id' => 2, 'satuan' => 'botol',  'harga_jual' => 55000,  'stok_minimum' => 20],
            ['kode' => 'PRD-009', 'nama' => 'Benih Padi Ciherang 5kg', 'kategori_id' => 8, 'supplier_id' => 3, 'satuan' => 'kg',     'harga_jual' => 65000,  'stok_minimum' => 20],
            ['kode' => 'PRD-010', 'nama' => 'Sprayer Elektrik 16L',    'kategori_id' => 9, 'supplier_id' => 2, 'satuan' => 'unit',   'harga_jual' => 325000, 'stok_minimum' => 5],
        ];

        foreach ($produk as $p) {
            DB::table('produk')->insert([
                ...$p,
                'stok_saat_ini' => 0,
                'foto'          => null,
                'catatan'       => null,
                'is_aktif'      => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // Stok awal: [produk_id, jumlah, harga_modal]
        $stokAwal = [
            [1,  50, 25000],
            [2,  40, 30000],
            [3,  50, 27500],
            [4,  25, 70000],
            [5,  80, 10000],
            [6,  150, 95000],
            [7,  150, 108000],
            [8,  40, 40000],
            [9,  60, 52000],
            [10, 10, 250000],
        ];

        $bmId = DB::table('barang_masuk')->insertGetId([
            'nomor'                 => 'SA-20250102-001',
            'jenis'                 => 'stok_awal',
            'supplier_id'           => null,
            'user_id'               => 1,
            'tanggal'               => $tanggalAwal,
            'nomor_faktur_supplier' => null,
            'catatan'               => 'Input stok awal saat setup sistem toko',
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);

        foreach ($stokAwal as [$produkId, $jumlah, $hargaModal]) {
            $detailId = DB::table('barang_masuk_detail')->insertGetId([
                'barang_masuk_id' => $bmId,
                'produk_id'       => $produkId,
                'jumlah'          => $jumlah,
                'harga_modal'     => $hargaModal,
                'subtotal'        => $jumlah * $hargaModal,
                'catatan'         => 'Stok awal',
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);

            DB::table('stok_batch')->insert([
                'produk_id'              => $produkId,
                'barang_masuk_detail_id' => $detailId,
                'tanggal_masuk'          => $tanggalAwal,
                'harga_modal'            => $hargaModal,
                'jumlah_awal'            => $jumlah,
                'jumlah_tersisa'         => $jumlah,
                'created_at'             => $now,
                'updated_at'             => $now,
            ]);

            DB::table('produk')->where('id', $produkId)->update(['stok_saat_ini' => $jumlah]);
        }
    }
}
