<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use SoftDeletes;

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama',
        'telepon',
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

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function piutang(): HasMany
    {
        return $this->hasMany(Piutang::class);
    }

    // -------------------------------------------------------
    // Computed
    // -------------------------------------------------------

    public function totalPiutang(): float
    {
        return (float) $this->piutang()->where('status', '!=', 'lunas')->sum('sisa_tagihan');
    }
}
