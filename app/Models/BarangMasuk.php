<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected $fillable = [
        'nomor',
        'jenis',
        'supplier_id',
        'user_id',
        'tanggal',
        'nomor_faktur_supplier',
        'catatan',
        // ── kolom baru pembayaran ──
        'status_bayar',
        'total_dibayar',
        'tanggal_jatuh_tempo',
        'catatan_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'             => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'total_dibayar'       => 'decimal:2',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeStokAwal($query)
    {
        return $query->where('jenis', 'stok_awal');
    }

    public function scopeMasukNormal($query)
    {
        return $query->where('jenis', 'masuk_normal');
    }

    public function scopeHutang($query)
    {
        return $query->whereIn('status_bayar', ['hutang', 'sebagian']);
    }

    public function scopeJatuhTempo($query, int $hariKedepan = 7)
    {
        return $query->hutang()
            ->whereNotNull('tanggal_jatuh_tempo')
            ->where('tanggal_jatuh_tempo', '<=', now()->addDays($hariKedepan));
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function isStokAwal(): bool
    {
        return $this->jenis === 'stok_awal';
    }

    public function isLunas(): bool
    {
        return $this->status_bayar === 'lunas';
    }

    public function isHutang(): bool
    {
        return in_array($this->status_bayar, ['hutang', 'sebagian']);
    }

    /**
     * Hitung ulang status_bayar & total_dibayar setelah ada cicilan baru.
     * Dipanggil setiap kali cicilan disimpan / dihapus.
     */
    public function recalculatePayment(): void
    {
        $totalPembayaran = $this->pembayaran()->sum('jumlah_bayar');
        $grandTotal      = $this->detail->sum('subtotal');

        if ($totalPembayaran <= 0) {
            $status = 'hutang';
        } elseif ($totalPembayaran >= $grandTotal) {
            $status = 'lunas';
        } else {
            $status = 'sebagian';
        }

        $this->update([
            'total_dibayar' => $totalPembayaran,
            'status_bayar'  => $status,
        ]);
    }

    // ── Accessors ─────────────────────────────────────────────────────────

    public function getTotalAttribute(): float
    {
        return (float) $this->detail->sum('subtotal');
    }

    public function getSisaHutangAttribute(): float
    {
        return max(0, $this->total - (float) $this->total_dibayar);
    }

    public function getLabelStatusBayarAttribute(): string
    {
        return match ($this->status_bayar) {
            'lunas'    => 'Lunas',
            'sebagian' => 'Sebagian',
            'hutang'   => 'Hutang',
            default    => '-',
        };
    }

    public function getBadgeStatusBayarAttribute(): string
    {
        return match ($this->status_bayar) {
            'lunas'    => 'bg-emerald-100 text-emerald-700',
            'sebagian' => 'bg-amber-100 text-amber-700',
            'hutang'   => 'bg-red-100 text-red-700',
            default    => 'bg-slate-100 text-slate-500',
        };
    }

    // ── Relasi ────────────────────────────────────────────────────────────

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detail(): HasMany
    {
        return $this->hasMany(BarangMasukDetail::class);
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(HutangSupplierPembayaran::class)->latest('tanggal_bayar');
    }
}
