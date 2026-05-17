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
        $now = Carbon::now();

        // ============================================================
        // 1. PENGATURAN TOKO
        // ============================================================

        DB::table('pengaturan_toko')->insert([
            'nama_toko'            => 'Toko Obat Tani Makmur Jaya',
            'alamat'               => 'Jl. Raya Pertanian No. 45, Sleman, Yogyakarta',
            'telepon'              => '0274-123456',
            'email'                => 'tokoobattani.makmurajaya@gmail.com',
            'logo'                 => null,
            'header_struk'         => "TOKO OBAT TANI MAKMUR JAYA\nJl. Raya Pertanian No. 45, Sleman\nTelp: 0274-123456",
            'footer_struk'         => "Terima kasih telah berbelanja!\nBarang yang sudah dibeli tidak dapat dikembalikan.\nSimpan struk ini sebagai bukti pembelian.",
            'stok_minimum_default' => 10,
            'created_at'           => $now,
            'updated_at'           => $now,
        ]);

        // ============================================================
        // 2. USERS
        // ============================================================

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
                'nama'       => 'Dewi Rahayu',
                'email'      => 'admin@makmurajaya.com',
                'password'   => Hash::make('password'),
                'role'       => 'admin',
                'is_aktif'   => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama'       => 'Agus Purnomo',
                'email'      => 'kasir1@makmurajaya.com',
                'password'   => Hash::make('password'),
                'role'       => 'kasir',
                'is_aktif'   => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama'       => 'Siti Nurhaliza',
                'email'      => 'kasir2@makmurajaya.com',
                'password'   => Hash::make('password'),
                'role'       => 'kasir',
                'is_aktif'   => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // ============================================================
        // 3. KATEGORI
        // ============================================================

        DB::table('kategori')->insert([
            ['nama' => 'Pestisida Insektisida',  'deskripsi' => 'Obat pembasmi serangga hama tanaman',          'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pestisida Fungisida',    'deskripsi' => 'Obat pembasmi jamur pada tanaman',             'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pestisida Herbisida',    'deskripsi' => 'Obat pembasmi gulma / rumput liar',            'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pestisida Rodentisida',  'deskripsi' => 'Obat pembasmi tikus di lahan pertanian',       'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pupuk Kimia',            'deskripsi' => 'Pupuk anorganik seperti Urea, SP-36, KCl',     'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pupuk Organik',          'deskripsi' => 'Pupuk organik cair maupun padat',              'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Pupuk Daun & ZPT',       'deskripsi' => 'Pupuk daun dan zat pengatur tumbuh',          'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Benih & Bibit',          'deskripsi' => 'Benih padi, jagung, sayuran, dan palawija',    'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Alat Pertanian',         'deskripsi' => 'Sprayer, cangkul, dan peralatan pertanian',    'is_aktif' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ============================================================
        // 4. SUPPLIER
        // ============================================================

        DB::table('supplier')->insert([
            [
                'kode'           => 'SUP-001',
                'nama'           => 'PT Petrokimia Gresik',
                'kontak_person'  => 'Hendra Wijaya',
                'telepon'        => '031-3981811',
                'email'          => 'distribusi@petrokimia-gresik.com',
                'alamat'         => 'Jl. Jenderal Ahmad Yani, Gresik, Jawa Timur',
                'catatan'        => 'Supplier utama pupuk kimia bersubsidi',
                'is_aktif'       => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'kode'           => 'SUP-002',
                'nama'           => 'CV Agrindo Jaya',
                'kontak_person'  => 'Pak Sugeng',
                'telepon'        => '0274-555321',
                'email'          => 'agrindo.jaya@gmail.com',
                'alamat'         => 'Jl. Magelang KM 12, Sleman, Yogyakarta',
                'catatan'        => 'Distributor pestisida dan pupuk organik lokal',
                'is_aktif'       => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'kode'           => 'SUP-003',
                'nama'           => 'PT Syngenta Indonesia',
                'kontak_person'  => 'Rani Kusuma',
                'telepon'        => '021-52997000',
                'email'          => 'info@syngenta.com',
                'alamat'         => 'Wisma GKBI Lt. 19, Jl. Jend. Sudirman, Jakarta',
                'catatan'        => 'Produsen pestisida dan benih unggul',
                'is_aktif'       => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'kode'           => 'SUP-004',
                'nama'           => 'UD Tani Makmur Wonosari',
                'kontak_person'  => 'Bu Lastri',
                'telepon'        => '0274-391234',
                'email'          => null,
                'alamat'         => 'Jl. Wonosari – Jogja KM 3, Gunungkidul',
                'catatan'        => 'Supplier benih sayuran lokal dan alat tani',
                'is_aktif'       => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'kode'           => 'SUP-005',
                'nama'           => 'PT Bayer CropScience Indonesia',
                'kontak_person'  => 'Dimas Pratama',
                'telepon'        => '021-29882600',
                'email'          => 'cropscience.id@bayer.com',
                'alamat'         => 'Jl. DR. Ide Anak Agung Gde Agung, Jakarta Selatan',
                'catatan'        => 'Produsen pestisida premium (Confidor, Antracol)',
                'is_aktif'       => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ]);

        // ============================================================
        // 5. PRODUK
        // ============================================================

        // kategori IDs: 1=Insektisida, 2=Fungisida, 3=Herbisida, 4=Rodentisida
        //               5=Pupuk Kimia, 6=Pupuk Organik, 7=Pupuk Daun/ZPT
        //               8=Benih, 9=Alat Pertanian

        DB::table('produk')->insert([
            // --- Insektisida ---
            [
                'kode' => 'PRD-001',
                'nama' => 'Confidor 5 WP 100gr',
                'kategori_id' => 1,
                'supplier_id' => 5,
                'satuan' => 'sachet',
                'harga_jual' => 35000,
                'stok_minimum' => 20,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Insektisida sistemik bahan aktif imidakloprid',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-002',
                'nama' => 'Regent 50 SC 100ml',
                'kategori_id' => 1,
                'supplier_id' => 2,
                'satuan' => 'botol',
                'harga_jual' => 42000,
                'stok_minimum' => 15,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Insektisida fipronil untuk wereng dan penggerek batang',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-003',
                'nama' => 'Decis 25 EC 100ml',
                'kategori_id' => 1,
                'supplier_id' => 2,
                'satuan' => 'botol',
                'harga_jual' => 28000,
                'stok_minimum' => 15,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Insektisida deltametrin untuk hama kutu dan ulat',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-004',
                'nama' => 'Curacron 500 EC 250ml',
                'kategori_id' => 1,
                'supplier_id' => 3,
                'satuan' => 'botol',
                'harga_jual' => 65000,
                'stok_minimum' => 10,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Insektisida profenofos untuk ulat grayak dan thrips',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // --- Fungisida ---
            [
                'kode' => 'PRD-005',
                'nama' => 'Antracol 70 WP 250gr',
                'kategori_id' => 2,
                'supplier_id' => 5,
                'satuan' => 'sachet',
                'harga_jual' => 38000,
                'stok_minimum' => 20,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Fungisida propineb untuk penyakit bercak daun dan busuk',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-006',
                'nama' => 'Dithane M-45 80 WP 200gr',
                'kategori_id' => 2,
                'supplier_id' => 2,
                'satuan' => 'sachet',
                'harga_jual' => 22000,
                'stok_minimum' => 20,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Fungisida mankozeb untuk penyakit jamur padi dan sayuran',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-007',
                'nama' => 'Score 250 EC 50ml',
                'kategori_id' => 2,
                'supplier_id' => 3,
                'satuan' => 'botol',
                'harga_jual' => 58000,
                'stok_minimum' => 10,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Fungisida sistemik difenokonazol untuk blast dan karat daun',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // --- Herbisida ---
            [
                'kode' => 'PRD-008',
                'nama' => 'Roundup 486 SL 1 Liter',
                'kategori_id' => 3,
                'supplier_id' => 2,
                'satuan' => 'botol',
                'harga_jual' => 95000,
                'stok_minimum' => 10,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Herbisida sistemik glifosat untuk gulma berdaun lebar',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-009',
                'nama' => 'Gramoxone 276 SL 1 Liter',
                'kategori_id' => 3,
                'supplier_id' => 3,
                'satuan' => 'botol',
                'harga_jual' => 82000,
                'stok_minimum' => 10,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Herbisida kontak parakuat untuk pengendalian gulma cepat',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-010',
                'nama' => 'DMA 6 2,4-D 500ml',
                'kategori_id' => 3,
                'supplier_id' => 2,
                'satuan' => 'botol',
                'harga_jual' => 45000,
                'stok_minimum' => 15,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Herbisida selektif untuk gulma daun lebar di lahan padi',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // --- Rodentisida ---
            [
                'kode' => 'PRD-011',
                'nama' => 'Klerat RM-B 50gr',
                'kategori_id' => 4,
                'supplier_id' => 2,
                'satuan' => 'sachet',
                'harga_jual' => 15000,
                'stok_minimum' => 25,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Rodentisida umpan racun tikus bromadiolon',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // --- Pupuk Kimia ---
            [
                'kode' => 'PRD-012',
                'nama' => 'Pupuk Urea Subsidi 50kg',
                'kategori_id' => 5,
                'supplier_id' => 1,
                'satuan' => 'karung',
                'harga_jual' => 115000,
                'stok_minimum' => 50,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Pupuk urea bersubsidi, kadar N 46%',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-013',
                'nama' => 'Pupuk SP-36 50kg',
                'kategori_id' => 5,
                'supplier_id' => 1,
                'satuan' => 'karung',
                'harga_jual' => 120000,
                'stok_minimum' => 30,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Pupuk fosfat bersubsidi untuk akar tanaman',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-014',
                'nama' => 'Pupuk KCl 50kg',
                'kategori_id' => 5,
                'supplier_id' => 1,
                'satuan' => 'karung',
                'harga_jual' => 230000,
                'stok_minimum' => 20,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Pupuk kalium klorida untuk kualitas buah dan umbi',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-015',
                'nama' => 'Pupuk NPK Phonska 50kg',
                'kategori_id' => 5,
                'supplier_id' => 1,
                'satuan' => 'karung',
                'harga_jual' => 130000,
                'stok_minimum' => 50,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Pupuk majemuk NPK 15-15-15 bersubsidi',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // --- Pupuk Organik ---
            [
                'kode' => 'PRD-016',
                'nama' => 'Pupuk Kandang Ayam 25kg',
                'kategori_id' => 6,
                'supplier_id' => 2,
                'satuan' => 'karung',
                'harga_jual' => 25000,
                'stok_minimum' => 30,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Pupuk organik kotoran ayam sudah difermentasi',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-017',
                'nama' => 'NASA POC 500ml',
                'kategori_id' => 6,
                'supplier_id' => 2,
                'satuan' => 'botol',
                'harga_jual' => 55000,
                'stok_minimum' => 20,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Pupuk organik cair Natural Nusantara untuk pertumbuhan',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // --- Pupuk Daun & ZPT ---
            [
                'kode' => 'PRD-018',
                'nama' => 'Gandasil D 500gr',
                'kategori_id' => 7,
                'supplier_id' => 2,
                'satuan' => 'bungkus',
                'harga_jual' => 30000,
                'stok_minimum' => 20,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Pupuk daun untuk fase vegetatif (Daun)',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-019',
                'nama' => 'Atonik 6,5 L 100ml',
                'kategori_id' => 7,
                'supplier_id' => 2,
                'satuan' => 'botol',
                'harga_jual' => 32000,
                'stok_minimum' => 15,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'ZPT natrium nitrofenol untuk merangsang pertumbuhan akar',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // --- Benih ---
            [
                'kode' => 'PRD-020',
                'nama' => 'Benih Padi Ciherang 5kg',
                'kategori_id' => 8,
                'supplier_id' => 4,
                'satuan' => 'kg',
                'harga_jual' => 65000,
                'stok_minimum' => 20,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Benih padi unggul varietas Ciherang bersertifikat',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-021',
                'nama' => 'Benih Jagung Pionir P21 1kg',
                'kategori_id' => 8,
                'supplier_id' => 3,
                'satuan' => 'kg',
                'harga_jual' => 95000,
                'stok_minimum' => 15,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Benih jagung hibrida Pioneer P21',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-022',
                'nama' => 'Benih Cabai TM 999 10gr',
                'kategori_id' => 8,
                'supplier_id' => 4,
                'satuan' => 'sachet',
                'harga_jual' => 32000,
                'stok_minimum' => 20,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Benih cabai merah hibrida tahan layu dan CVPD',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-023',
                'nama' => 'Benih Tomat Servo F1 10gr',
                'kategori_id' => 8,
                'supplier_id' => 3,
                'satuan' => 'sachet',
                'harga_jual' => 45000,
                'stok_minimum' => 15,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Benih tomat hibrida produksi tinggi tahan virus',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // --- Alat Pertanian ---
            [
                'kode' => 'PRD-024',
                'nama' => 'Sprayer Elektrik Maspion 16L',
                'kategori_id' => 9,
                'supplier_id' => 4,
                'satuan' => 'unit',
                'harga_jual' => 325000,
                'stok_minimum' => 5,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Sprayer elektrik baterai 16 liter untuk pestisida',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'PRD-025',
                'nama' => 'Handsprayer Manual 15L',
                'kategori_id' => 9,
                'supplier_id' => 4,
                'satuan' => 'unit',
                'harga_jual' => 145000,
                'stok_minimum' => 5,
                'stok_saat_ini' => 0,
                'foto' => null,
                'catatan' => 'Sprayer gendong manual kapasitas 15 liter',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // ============================================================
        // 6. PELANGGAN
        // ============================================================

        DB::table('pelanggan')->insert([
            [
                'nama' => 'Kelompok Tani Sumber Makmur',
                'telepon' => '081234567890',
                'alamat' => 'Dusun Kragilan, Moyudan, Sleman',
                'catatan' => 'Pembelian pupuk subsidi rutin tiap musim tanam',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Pak Hardi Susanto',
                'telepon' => '082345678901',
                'alamat' => 'Dusun Tempel, Turi, Sleman',
                'catatan' => 'Petani cabai, beli pestisida dan benih rutin',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Bu Saminah',
                'telepon' => '083456789012',
                'alamat' => 'Jl. Kaliurang KM 14, Sleman',
                'catatan' => 'Petani sayuran, pelanggan tetap benih dan pupuk daun',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Koperasi Tani Maju Bersama',
                'telepon' => '084567890123',
                'alamat' => 'Jl. Godean KM 7, Sleman',
                'catatan' => 'Koperasi petani, pembelian partai besar pupuk kimia',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Pak Warsito',
                'telepon' => '085678901234',
                'alamat' => 'Dusun Cebongan, Gamping, Sleman',
                'catatan' => 'Petani padi, piutang rutin dilunasi saat panen',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Bu Poniyem',
                'telepon' => '086789012345',
                'alamat' => 'Dusun Samirono, Depok, Sleman',
                'catatan' => 'Petani tomat dan terong',
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // ============================================================
        // 7. BARANG MASUK — STOK AWAL
        // ============================================================

        $tanggalStokAwal = '2025-01-02';

        DB::table('barang_masuk')->insert([
            'nomor'                   => 'SA-20250102-001',
            'jenis'                   => 'stok_awal',
            'supplier_id'             => null,
            'user_id'                 => 1, // owner
            'tanggal'                 => $tanggalStokAwal,
            'nomor_faktur_supplier'   => null,
            'catatan'                 => 'Input stok awal saat setup sistem toko',
            'created_at'              => $now,
            'updated_at'              => $now,
        ]);

        // barang_masuk_id = 1 (stok awal)
        $stokAwal = [
            // [produk_id, jumlah, harga_modal]
            [1,  80,  25000, '2026-12-31'],
            [2,  60,  30000, '2026-10-31'],
            [3,  60,  20000, '2026-11-30'],
            [4,  40,  48000, '2026-09-30'],
            [5,  80,  27500, '2026-08-31'],
            [6,  80,  16000, '2026-07-31'],
            [7,  30,  42000, '2026-09-30'],
            [8,  25,  70000, '2026-12-31'],
            [9,  20,  60000, '2026-12-31'],
            [10, 40,  33000, '2026-12-31'],
            [11, 100, 10000, '2026-06-30'],
            [12, 200, 95000, null],
            [13, 120, 100000, null],
            [14, 60,  200000, null],
            [15, 200, 108000, null],
            [16, 80,  18000, null],
            [17, 60,  40000, '2026-12-31'],
            [18, 70,  22000, '2026-12-31'],
            [19, 50,  23000, '2026-12-31'],
            [20, 100, 52000, '2026-06-30'],
            [21, 50,  78000, '2026-06-30'],
            [22, 80,  23000, '2026-06-30'],
            [23, 50,  33000, '2026-06-30'],
            [24, 10,  250000, null],
            [25, 15,  110000, null],
        ];

        $detailIds = [];
        foreach ($stokAwal as $i => [$produkId, $jumlah, $hargaModal]) {
            $subtotal = $jumlah * $hargaModal;
            $detailId = DB::table('barang_masuk_detail')->insertGetId([
                'barang_masuk_id'    => 1,
                'produk_id'          => $produkId,
                'jumlah'             => $jumlah,
                'harga_modal'        => $hargaModal,
                'subtotal'           => $subtotal,
                'catatan'            => 'Stok awal',
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);

            DB::table('stok_batch')->insert([
                'produk_id'              => $produkId,
                'barang_masuk_detail_id' => $detailId,
                'tanggal_masuk'          => $tanggalStokAwal,
                'harga_modal'            => $hargaModal,
                'jumlah_awal'            => $jumlah,
                'jumlah_tersisa'         => $jumlah,
                'created_at'             => $now,
                'updated_at'             => $now,
            ]);

            DB::table('produk')->where('id', $produkId)->update(['stok_saat_ini' => $jumlah]);

            $detailIds[$produkId] = $detailId;
        }

        // ============================================================
        // 8. BARANG MASUK — MASUK NORMAL (2 transaksi)
        // ============================================================

        // Barang Masuk #2 dari SUP-002 (Agrindo Jaya) — 10 Jan 2025
        $tanggalBM2 = '2025-01-10';
        DB::table('barang_masuk')->insert([
            'nomor'                 => 'BM-20250110-001',
            'jenis'                 => 'masuk_normal',
            'supplier_id'           => 2,
            'user_id'               => 2,
            'tanggal'               => $tanggalBM2,
            'nomor_faktur_supplier' => 'AGRINDO/INV/2025/001',
            'catatan'               => 'Restock pestisida dan pupuk organik',
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);

        $bm2Items = [
            [2,  50, 30000, '2027-01-31'],
            [5,  60, 27500, '2027-02-28'],
            [8,  30, 70000, '2027-12-31'],
            [17, 40, 40000, '2027-06-30'],
        ];

        foreach ($bm2Items as [$produkId, $jumlah, $hargaModal]) {
            $detailId = DB::table('barang_masuk_detail')->insertGetId([
                'barang_masuk_id'    => 2,
                'produk_id'          => $produkId,
                'jumlah'             => $jumlah,
                'harga_modal'        => $hargaModal,
                'subtotal'           => $jumlah * $hargaModal,
                'catatan'            => null,
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);

            DB::table('stok_batch')->insert([
                'produk_id'              => $produkId,
                'barang_masuk_detail_id' => $detailId,
                'tanggal_masuk'          => $tanggalBM2,
                'harga_modal'            => $hargaModal,
                'jumlah_awal'            => $jumlah,
                'jumlah_tersisa'         => $jumlah,
                'created_at'             => $now,
                'updated_at'             => $now,
            ]);

            DB::table('produk')->where('id', $produkId)
                ->increment('stok_saat_ini', $jumlah);
        }

        // Barang Masuk #3 dari SUP-001 (Petrokimia) — 15 Jan 2025
        $tanggalBM3 = '2025-01-15';
        DB::table('barang_masuk')->insert([
            'nomor'                 => 'BM-20250115-001',
            'jenis'                 => 'masuk_normal',
            'supplier_id'           => 1,
            'user_id'               => 2,
            'tanggal'               => $tanggalBM3,
            'nomor_faktur_supplier' => 'PKG/DO/2025/0023',
            'catatan'               => 'Restock pupuk subsidi awal musim tanam',
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);

        $bm3Items = [
            [12, 300, 95000,  null],
            [13, 150, 100000, null],
            [15, 300, 108000, null],
        ];

        foreach ($bm3Items as [$produkId, $jumlah, $hargaModal]) {
            $detailId = DB::table('barang_masuk_detail')->insertGetId([
                'barang_masuk_id'    => 3,
                'produk_id'          => $produkId,
                'jumlah'             => $jumlah,
                'harga_modal'        => $hargaModal,
                'subtotal'           => $jumlah * $hargaModal,
                'catatan'            => null,
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);

            DB::table('stok_batch')->insert([
                'produk_id'              => $produkId,
                'barang_masuk_detail_id' => $detailId,
                'tanggal_masuk'          => $tanggalBM3,
                'harga_modal'            => $hargaModal,
                'jumlah_awal'            => $jumlah,
                'jumlah_tersisa'         => $jumlah,
                'created_at'             => $now,
                'updated_at'             => $now,
            ]);

            DB::table('produk')->where('id', $produkId)
                ->increment('stok_saat_ini', $jumlah);
        }

        // ============================================================
        // 9. TRANSAKSI PENJUALAN
        // ============================================================
        // Kita buat 3 transaksi sample. FIFO diambil dari batch stok awal (batch paling lama).
        // Untuk kesederhanaan seeder, semua item menggunakan batch stok awal (id batch = index produk).

        // Helper: ambil batch pertama per produk (stok awal)
        // batch stok awal: barang_masuk_detail id 1-25 → stok_batch id 1-25 (produk 1-25 berurutan)

        // --- TRANSAKSI 1: Pak Hardi (Petani Cabai) — 20 Jan 2025, Lunas ---
        $trx1Items = [
            // [produk_id, jumlah, harga_jual, stok_batch_id, harga_modal]
            ['produk_id' => 1,  'jumlah' => 3,  'harga_jual' => 35000, 'batch_id' => 1,  'harga_modal' => 25000],
            ['produk_id' => 6,  'jumlah' => 5,  'harga_jual' => 22000, 'batch_id' => 6,  'harga_modal' => 16000],
            ['produk_id' => 22, 'jumlah' => 10, 'harga_jual' => 32000, 'batch_id' => 22, 'harga_modal' => 23000],
        ];
        $this->buatTransaksi(
            nomor: 'TRX-20250120-001',
            pelangganId: 2,
            userId: 3,
            tanggal: '2025-01-20',
            items: $trx1Items,
            diskon: 0,
            jumlahBayar: 490000,
            statusBayar: 'lunas',
            now: $now
        );

        // --- TRANSAKSI 2: Koperasi Tani Maju Bersama — 22 Jan 2025, Lunas ---
        $trx2Items = [
            ['produk_id' => 12, 'jumlah' => 20, 'harga_jual' => 115000, 'batch_id' => 12, 'harga_modal' => 95000],
            ['produk_id' => 15, 'jumlah' => 20, 'harga_jual' => 130000, 'batch_id' => 15, 'harga_modal' => 108000],
            ['produk_id' => 13, 'jumlah' => 10, 'harga_jual' => 120000, 'batch_id' => 13, 'harga_modal' => 100000],
        ];
        $this->buatTransaksi(
            nomor: 'TRX-20250122-001',
            pelangganId: 4,
            userId: 3,
            tanggal: '2025-01-22',
            items: $trx2Items,
            diskon: 200000,
            jumlahBayar: 4500000,
            statusBayar: 'lunas',
            now: $now
        );

        // --- TRANSAKSI 3: Pak Warsito — 25 Jan 2025, Belum Lunas (piutang) ---
        $trx3Items = [
            ['produk_id' => 12, 'jumlah' => 10, 'harga_jual' => 115000, 'batch_id' => 12, 'harga_modal' => 95000],
            ['produk_id' => 8,  'jumlah' => 2,  'harga_jual' => 95000,  'batch_id' => 8,  'harga_modal' => 70000],
            ['produk_id' => 2,  'jumlah' => 5,  'harga_jual' => 42000,  'batch_id' => 2,  'harga_modal' => 30000],
        ];
        $this->buatTransaksi(
            nomor: 'TRX-20250125-001',
            pelangganId: 5,
            userId: 4,
            tanggal: '2025-01-25',
            items: $trx3Items,
            diskon: 0,
            jumlahBayar: 500000,
            statusBayar: 'sebagian',
            now: $now
        );

        // ============================================================
        // 10. PIUTANG (untuk Transaksi 3)
        // ============================================================

        // Transaksi 3: total = 1150000 + 190000 + 210000 = 1550000; bayar 500000 → sisa 1050000
        $totalTrx3 = (10 * 115000) + (2 * 95000) + (5 * 42000); // 1150000 + 190000 + 210000 = 1550000
        DB::table('piutang')->insert([
            'nomor'            => 'PIU-20250125-001',
            'transaksi_id'     => 3,
            'pelanggan_id'     => 5,
            'total_tagihan'    => $totalTrx3,
            'sudah_dibayar'    => 500000,
            'sisa_tagihan'     => $totalTrx3 - 500000,
            'status'           => 'sebagian',
            'tanggal_transaksi'    => '2025-01-25',
            'tanggal_jatuh_tempo'  => '2025-02-25',
            'dilunasi_pada'    => null,
            'catatan'          => 'Akan dilunasi setelah panen padi Februari 2025',
            'created_at'       => $now,
            'updated_at'       => $now,
        ]);

        DB::table('piutang_pembayaran')->insert([
            'piutang_id'   => 1,
            'user_id'      => 4,
            'tanggal'      => '2025-01-25',
            'jumlah'       => 500000,
            'metode_bayar' => 'tunai',
            'referensi'    => null,
            'catatan'      => 'Uang muka saat transaksi',
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
    }

    // ================================================================
    // HELPER: Buat transaksi lengkap beserta detail & FIFO log
    // ================================================================

    private function buatTransaksi(
        string $nomor,
        ?int $pelangganId,
        int $userId,
        string $tanggal,
        array $items,
        float $diskon,
        float $jumlahBayar,
        string $statusBayar,
        Carbon $now
    ): void {
        $subtotal  = 0;
        $totalHpp  = 0;

        foreach ($items as $item) {
            $subtotal += $item['harga_jual'] * $item['jumlah'];
            $totalHpp += $item['harga_modal'] * $item['jumlah'];
        }

        $total        = $subtotal - $diskon;
        $sisaTagihan  = max(0, $total - $jumlahBayar);
        $kembalian    = ($statusBayar === 'lunas') ? max(0, $jumlahBayar - $total) : 0;

        $trxId = DB::table('transaksi')->insertGetId([
            'nomor'          => $nomor,
            'pelanggan_id'   => $pelangganId,
            'user_id'        => $userId,
            'tanggal'        => $tanggal,
            'subtotal'       => $subtotal,
            'diskon_nominal' => $diskon,
            'total'          => $total,
            'total_hpp'      => $totalHpp,
            'status_bayar'   => $statusBayar,
            'jumlah_bayar'   => $jumlahBayar,
            'sisa_tagihan'   => $sisaTagihan,
            'kembalian'      => $kembalian,
            'catatan'        => null,
            'status'         => 'aktif',
            'dibatalkan_pada'  => null,
            'dibatalkan_oleh'  => null,
            'alasan_batal'   => null,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        foreach ($items as $item) {
            $hpp     = $item['harga_modal'] * $item['jumlah'];
            $itemSub = $item['harga_jual']  * $item['jumlah'];

            $detailId = DB::table('transaksi_detail')->insertGetId([
                'transaksi_id' => $trxId,
                'produk_id'    => $item['produk_id'],
                'jumlah'       => $item['jumlah'],
                'harga_jual'   => $item['harga_jual'],
                'diskon_item'  => 0,
                'subtotal'     => $itemSub,
                'hpp'          => $hpp,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            // FIFO log — pakai batch stok awal
            DB::table('transaksi_fifo_log')->insert([
                'transaksi_detail_id' => $detailId,
                'stok_batch_id'       => $item['batch_id'],
                'jumlah_dipakai'      => $item['jumlah'],
                'harga_modal'         => $item['harga_modal'],
                'subtotal_hpp'        => $hpp,
                'created_at'          => $now,
                'updated_at'          => $now,
            ]);

            // Kurangi stok_batch & produk
            DB::table('stok_batch')
                ->where('id', $item['batch_id'])
                ->decrement('jumlah_tersisa', $item['jumlah']);

            DB::table('produk')
                ->where('id', $item['produk_id'])
                ->decrement('stok_saat_ini', $item['jumlah']);
        }
    }
}
