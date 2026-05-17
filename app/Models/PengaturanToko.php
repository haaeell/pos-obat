<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanToko extends Model
{
    protected $table = 'pengaturan_toko';

    protected $fillable = [
        'nama_toko',
        'alamat',
        'telepon',
        'email',
        'logo',
        'header_struk',
        'footer_struk',
        'stok_minimum_default',
    ];

    protected function casts(): array
    {
        return [
            'stok_minimum_default' => 'integer',
        ];
    }

    // -------------------------------------------------------
    // Helper — ambil satu-satunya record pengaturan
    // -------------------------------------------------------

    public static function instance(): static
    {
        return static::firstOrNew(['id' => 1]);
    }
}
