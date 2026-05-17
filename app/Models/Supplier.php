<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $table = 'supplier';

    protected $fillable = [
        'kode',
        'nama',
        'kontak_person',
        'telepon',
        'email',
        'alamat',
        'catatan',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
        ];
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class);
    }

    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
