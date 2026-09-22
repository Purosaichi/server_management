<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainDetail extends Model
{
    protected $table = 'vw_domain_detail';
    protected $primarykey = 'id_domain';
    public $timestamps = false;
    public $incrementing = false;
}