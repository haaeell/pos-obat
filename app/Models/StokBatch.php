<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StokBatch extends Model
{
    protected $table = 'stok_batch';

    protected $fillable = [
        'produk_id',
        'barang_masuk_detail_id',
        'tanggal_masuk',
        'harga_modal',
        'jumlah_awal',
        'jumlah_tersisa',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_masuk'      => 'date',
            'harga_modal'        => 'decimal:2',
            'jumlah_awal'        => 'integer',
            'jumlah_tersisa'     => 'integer',
        ];
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    /** Batch yang masih ada stoknya, urut FIFO */
    public function scopeTersedia($query)
    {
        return $query->where('jumlah_tersisa', '>', 0)->orderBy('tanggal_masuk');
    }

    public function scopeUntukProduk($query, int $produkId)
    {
        return $query->where('produk_id', $produkId);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isTersedia(): bool
    {
        return $this->jumlah_tersisa > 0;
    }

    public function isHabis(): bool
    {
        return $this->jumlah_tersisa <= 0;
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function barangMasukDetail(): BelongsTo
    {
        return $this->belongsTo(BarangMasukDetail::class);
    }

    public function fifoLog(): HasMany
    {
        return $this->hasMany(TransaksiFifoLog::class);
    }
}
