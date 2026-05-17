<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use SoftDeletes;

    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'deskripsi',
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
}
