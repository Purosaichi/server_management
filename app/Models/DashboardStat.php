<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Models;

class DashboardStat extends Models
{
    protected $table = 'vw_dashboard_stats';
    protected $primarykey = null;
    public $timestamps = false;
    public $incrementing = false; 
}