<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangMasuk extends Model
{
    use SoftDeletes;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'nomor',
        'jenis',
        'supplier_id',
        'user_id',
        'tanggal',
        'nomor_faktur_supplier',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeStokAwal($query)
    {
        return $query->where('jenis', 'stok_awal');
    }

    public function scopeMasukNormal($query)
    {
        return $query->where('jenis', 'masuk_normal');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isStokAwal(): bool
    {
        return $this->jenis === 'stok_awal';
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detail(): HasMany
    {
        return $this->hasMany(BarangMasukDetail::class);
    }

    public function getTotalAttribute()
    {
        return $this->detail->sum('subtotal');
    }
}
