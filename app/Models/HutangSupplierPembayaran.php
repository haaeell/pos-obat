<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HutangSupplierPembayaran extends Model
{
    protected $table = 'hutang_supplier_pembayaran';

    protected $fillable = [
        'barang_masuk_id',
        'user_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_bayar',
        'nomor_referensi',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'jumlah_bayar'  => 'decimal:2',
        ];
    }

    // ── Relasi ────────────────────────────────────────────────────────────

    public function barangMasuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helper label metode ───────────────────────────────────────────────

    public function getLabelMetodeAttribute(): string
    {
        return match ($this->metode_bayar) {
            'tunai'    => 'Tunai',
            'transfer' => 'Transfer Bank',
            'cek'      => 'Cek / Giro',
            'lainnya'  => 'Lainnya',
            default    => '-',
        };
    }
}
