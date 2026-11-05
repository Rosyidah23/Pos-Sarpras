<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'siswa', 'guru' atau 'petugas'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Peran yang bisa dibuatkan akunnya oleh petugas (keduanya sama-sama pelapor). */
    public const REPORTER_ROLES = ['siswa', 'guru'];

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /** Nama peran singkat: Petugas / Guru / Siswa. */
    public function roleName(): string
    {
        return match ($this->role) {
            'petugas' => 'Petugas',
            'guru' => 'Guru',
            default => 'Siswa',
        };
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
