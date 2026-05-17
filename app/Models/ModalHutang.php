<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModalHutang extends Model
{
    use SoftDeletes;

    protected $table = 'modal_hutang';

    protected $fillable = [
        'nomor',
        'jenis',
        'nama_sumber',
        'nama_kreditur',
        'telepon_kreditur',
        'jumlah_total',
        'tanggal_diterima',
        'jangka_bulan',
        'bunga_persen',
        'cicilan_per_bulan',
        'tanggal_jatuh_tempo',
        'sudah_dibayar',
        'sisa_hutang',
        'status',
        'dilunasi_pada',
        'catatan',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_total'        => 'decimal:2',
            'bunga_persen'        => 'decimal:2',
            'cicilan_per_bulan'   => 'decimal:2',
            'sudah_dibayar'       => 'decimal:2',
            'sisa_hutang'         => 'decimal:2',
            'jangka_bulan'        => 'integer',
            'tanggal_diterima'    => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'dilunasi_pada'       => 'datetime',
        ];
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopePinjaman($query)
    {
        return $query->where('jenis', 'pinjaman');
    }

    public function scopeModalSendiri($query)
    {
        return $query->where('jenis', 'modal_sendiri');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    public function isPinjaman(): bool
    {
        return $this->jenis === 'pinjaman';
    }

    public function isModalSendiri(): bool
    {
        return $this->jenis === 'modal_sendiri';
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cicilan(): HasMany
    {
        return $this->hasMany(ModalHutangCicilan::class);
    }
}
