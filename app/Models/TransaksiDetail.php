<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransaksiDetail extends Model
{
    protected $table = 'transaksi_detail';

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'harga_jual',
        'diskon_item',
        'subtotal',
        'hpp',
    ];

    protected function casts(): array
    {
        return [
            'jumlah'     => 'integer',
            'harga_jual' => 'decimal:2',
            'diskon_item' => 'decimal:2',
            'subtotal'   => 'decimal:2',
            'hpp'        => 'decimal:2',
        ];
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function laba(): float
    {
        return (float) ($this->subtotal - $this->hpp);
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function fifoLog(): HasMany
    {
        return $this->hasMany(TransaksiFifoLog::class);
    }
}
