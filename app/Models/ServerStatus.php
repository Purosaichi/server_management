<?php

namespace App\Models;

use Illuminate\database\Eloguent\Model;

class ServerStatus extends Model
{
    protected $table = 'vw_server_status';
    protected $primaryley = 'id_server';

    public $timestamps = 'false';
}