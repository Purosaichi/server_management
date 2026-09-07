<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    public $timestamps = false;

    protected $fillable = [
        'nama_pengguna',
        'nama_login',
        'kata_sandi',
        'status_pengguna',
        'keterangan',
    ];

    protected $hidden = [
        'kata_sandi',
    ];
}
