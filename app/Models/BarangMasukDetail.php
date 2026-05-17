<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BarangMasukDetail extends Model
{
    protected $table = 'barang_masuk_detail';

    protected $fillable = [
        'barang_masuk_id',
        'produk_id',
        'jumlah',
        'harga_modal',
        'subtotal',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah'             => 'integer',
            'harga_modal'        => 'decimal:2',
            'subtotal'           => 'decimal:2',
        ];
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function barangMasuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function stokBatch(): HasOne
    {
        return $this->hasOne(StokBatch::class);
    }
}
