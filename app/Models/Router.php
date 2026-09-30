<?php 

namespace App\Models;

use Illuminate \Database\Eloquent\Model;

class Router extends Model
{
    protected $table = 'router';
    protected $primarykey = 'id_router';
    public $timestamps= false;
    public $iscrementing = true;

    protected $fillable = [
        'jenis_router',
        'kapasitas_bandwidth',
        'protokol_routing',
        'jumlah_tunnel-vpn',
        'status_vpn',
        'status_nat',
        'status_ha',
        'alamat_ip_manajemen',
        'id_aset',
    ];
}