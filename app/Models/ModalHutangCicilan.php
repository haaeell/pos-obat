<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModalHutangCicilan extends Model
{
    protected $table = 'modal_hutang_cicilan';

    protected $fillable = [
        'modal_hutang_id',
        'user_id',
        'tanggal',
        'jumlah',
        'metode_bayar',
        'referensi',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah'  => 'decimal:2',
        ];
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function modalHutang(): BelongsTo
    {
        return $this->belongsTo(ModalHutang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
