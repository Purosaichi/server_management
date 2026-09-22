<?php

namespace App\Modesl;

use Illuminate\Database\Eloquent\Model;

class DomainActivity extends Model
{
    protected $table = 'vw_domain_activity';
    protected $primarykey = 'id_activity';
    public $timestamps = false;
    public $incrementing = false;
}