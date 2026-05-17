<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PiutangPembayaran extends Model
{
    protected $table = 'piutang_pembayaran';

    protected $fillable = [
        'piutang_id',
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

    public function piutang(): BelongsTo
    {
        return $this->belongsTo(Piutang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
