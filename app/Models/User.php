<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'is_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif'          => 'boolean',
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function piutangPembayaran(): HasMany
    {
        return $this->hasMany(PiutangPembayaran::class);
    }

    public function modalHutang(): HasMany
    {
        return $this->hasMany(ModalHutang::class);
    }

    public function modalHutangCicilan(): HasMany
    {
        return $this->hasMany(ModalHutangCicilan::class);
    }

    /** Transaksi yang dibatalkan oleh user ini */
    public function transaksiDibatalkan(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'dibatalkan_oleh');
    }
}
