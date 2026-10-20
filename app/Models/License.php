<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use SoftDeletes;

    protected $table = 'lisensi';
    protected $primaryKey = 'id_lisensi';
    public $timestamps = false;

    protected $fillable = [
        'nama_lisensi',
        'provider',
        'status',
        'jumlah_lisensi',
        'tanggal_mulai',
        'tanggal_kadaluarsa',
        'nomor_lisensi',
        'jenis_lisensi',
        'logo',
    ];
}
