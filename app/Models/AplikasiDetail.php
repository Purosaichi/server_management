<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApliaksiDetail extends Model
{
    protected $table = 'vw_aplikasi_detil';
    protected $primarykey = 'id_aplikasi';
    public $timestamps = false;
}