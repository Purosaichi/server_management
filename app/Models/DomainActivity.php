<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainActivity extends Model
{
    protected $table = 'vw_domain_activity';
    protected $primaryKey = 'id_activity';
    public $timestamps = false;
    public $incrementing = false;
}