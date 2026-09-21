<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerStatus extends Model
{
    protected $table = 'vw_server_status';
    protected $primaryKey = 'id_server';

    public $timestamps = false;
}