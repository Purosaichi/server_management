<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $table = 'server';
    protected $primaryKey = 'id_server';
    public $timestamps = false;

    protected $fillable = [
        'id_server', 'jumlah_prosessor', 'model_prosessor',
        'jumlah_inti_prosessor', 'jumlah_utas_prosessor',
        'kapasitas_memori', 'jenis_memori', 'jumlah_slot_memori',
        'jumlah_slot_memori_terpakai', 'jenis_penyimpanan',
        'kapasitas_penyimpanan', 'jumlah_media_penyimpanan',
        'tingkat_raid', 'jenis_server', 'sistem_operasi',
        'jenis_hypervisor', 'nama_cluster', 'alamat_ip_manajemen',
        'alamat_ip_produksi', 'id_Aset'
    ];

    // Relasi aset
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'id_Aset', 'id_aset');
    }
}