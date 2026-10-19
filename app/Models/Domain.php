<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = 'domain';

    protected $primaryKey = 'id_domain';

    public $timestamps = false;

    protected $fillable = [
        'nama_domain',
        'status',
        'registrar',
        'tanggal_pembelian',
        'tanggal_kadaluarsa',
        'perpanjangan_otomatis',
        'id_pic',
    ];
}
