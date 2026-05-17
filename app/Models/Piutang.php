<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Piutang extends Model
{
    use SoftDeletes;

    protected $table = 'piutang';

    protected $fillable = [
        'nomor',
        'transaksi_id',
        'pelanggan_id',
        'total_tagihan',
        'sudah_dibayar',
        'sisa_tagihan',
        'status',
        'tanggal_transaksi',
        'tanggal_jatuh_tempo',
        'dilunasi_pada',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'total_tagihan'       => 'decimal:2',
            'sudah_dibayar'       => 'decimal:2',
            'sisa_tagihan'        => 'decimal:2',
            'tanggal_transaksi'   => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'dilunasi_pada'       => 'datetime',
        ];
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeBelumLunas($query)
    {
        return $query->whereIn('status', ['belum_bayar', 'sebagian']);
    }

    public function scopeJatuhTempo($query, string $tanggal)
    {
        return $query->where('tanggal_jatuh_tempo', '<=', $tanggal)
            ->whereIn('status', ['belum_bayar', 'sebagian']);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    public function isTerlambat(): bool
    {
        return ! $this->isLunas()
            && $this->tanggal_jatuh_tempo !== null
            && $this->tanggal_jatuh_tempo->isPast();
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PiutangPembayaran::class);
    }
}
