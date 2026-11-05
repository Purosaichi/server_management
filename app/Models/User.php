<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_akun';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama_pengguna',
        'email',
        'kata_sandi',
        'status_pengguna',
        'keterangan',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'kata_sandi' => 'hashed',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'kata_sandi';
    }
}
