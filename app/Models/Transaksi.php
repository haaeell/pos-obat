<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'nomor',
        'pelanggan_id',
        'user_id',
        'tanggal',
        'subtotal',
        'diskon_nominal',
        'total',
        'total_hpp',
        'status_bayar',
        'jumlah_bayar',
        'sisa_tagihan',
        'kembalian',
        'catatan',
        'status',
        'dibatalkan_pada',
        'dibatalkan_oleh',
        'alasan_batal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'         => 'date',
            'subtotal'        => 'decimal:2',
            'diskon_nominal'  => 'decimal:2',
            'total'           => 'decimal:2',
            'total_hpp'       => 'decimal:2',
            'jumlah_bayar'    => 'decimal:2',
            'sisa_tagihan'    => 'decimal:2',
            'kembalian'       => 'decimal:2',
            'dibatalkan_pada' => 'datetime',
        ];
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeDibatalkan($query)
    {
        return $query->where('status', 'dibatalkan');
    }

    public function scopeLunas($query)
    {
        return $query->where('status_bayar', 'lunas');
    }

    public function scopeBelumLunas($query)
    {
        return $query->whereIn('status_bayar', ['sebagian', 'belum_bayar']);
    }

    public function scopeTanggal($query, string $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    public function scopePeriode($query, string $dari, string $sampai)
    {
        return $query->whereBetween('tanggal', [$dari, $sampai]);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function isDibatalkan(): bool
    {
        return $this->status === 'dibatalkan';
    }

    public function isLunas(): bool
    {
        return $this->status_bayar === 'lunas';
    }

    public function labaBruto(): float
    {
        return (float) ($this->total - $this->total_hpp);
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pembatal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function piutang(): HasOne
    {
        return $this->hasOne(Piutang::class);
    }
}
