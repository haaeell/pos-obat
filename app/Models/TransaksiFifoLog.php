<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiFifoLog extends Model
{
    protected $table = 'transaksi_fifo_log';

    protected $fillable = [
        'transaksi_detail_id',
        'stok_batch_id',
        'jumlah_dipakai',
        'harga_modal',
        'subtotal_hpp',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_dipakai' => 'integer',
            'harga_modal'    => 'decimal:2',
            'subtotal_hpp'   => 'decimal:2',
        ];
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function transaksiDetail(): BelongsTo
    {
        return $this->belongsTo(TransaksiDetail::class);
    }

    public function stokBatch(): BelongsTo
    {
        return $this->belongsTo(StokBatch::class);
    }
}
