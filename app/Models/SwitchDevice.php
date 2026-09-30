<?php

namespace App\Models;

use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Database\Eloquent\model;

class SwitchDevice extends Model
{
    protected $table = 'switch';
    protected $primaryKey = 'id_switch';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'jenis_switch',
        'lapisan_jaringan',
        'jumlah_port',
        'jumlah_port_terpakai',
        'jumlah_port_tersedia',
        'kecepatan_port',
        'jumlah_port_uplink',
        'status_stack',
        'jumlah_anggota_stack',
        'jumlah_vlan',
        'alamat_ip_manajemen',
        'alamat_mac',
        'id_aset',
    ];
}