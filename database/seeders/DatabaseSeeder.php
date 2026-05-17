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
                'email'      => 'owner@gmail.com',
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
    }
}
