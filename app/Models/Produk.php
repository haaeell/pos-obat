<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';

    protected $fillable = [
        'kode',
        'nama',
        'kategori_id',
        'supplier_id',
        'satuan',
        'harga_jual',
        'stok_minimum',
        'stok_saat_ini',
        'foto',
        'catatan',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'harga_jual'   => 'decimal:2',
            'stok_minimum' => 'integer',
            'stok_saat_ini' => 'integer',
            'is_aktif'     => 'boolean',
        ];
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeStokRendah($query)
    {
        return $query->whereColumn('stok_saat_ini', '<=', 'stok_minimum');
    }

    public function scopeStokHabis($query)
    {
        return $query->where('stok_saat_ini', 0);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isStokRendah(): bool
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }

    public function isStokHabis(): bool
    {
        return $this->stok_saat_ini <= 0;
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function barangMasukDetail(): HasMany
    {
        return $this->hasMany(BarangMasukDetail::class);
    }

    public function stokBatch(): HasMany
    {
        return $this->hasMany(StokBatch::class);
    }

    /** Batch yang masih tersisa (untuk FIFO) */
    public function stokBatchTersedia(): HasMany
    {
        return $this->hasMany(StokBatch::class)
            ->where('jumlah_tersisa', '>', 0)
            ->orderBy('tanggal_masuk');
    }

    public function transaksiDetail(): HasMany
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
