<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';
    protected $primaryKey = 'id_aset';
    public $timestamps = false;

    protected $fillable = [
        'id_aset', 'kode_aset', 'nama_aset', 'id_jenis_perangkat',
        'id_merek', 'model_perangkat', 'nomor_seri', 'nomor_part',
        'status_aset', 'kondisi_aset', 'status_kepemilikan',
        'tanggal_pengadaan', 'tahun_pengadaan', 'nomor_pengadaan',
        'nilai_pengadaan', 'id_penyedia', 'tanggal_pemasangan',
        'tanggal_penghapusan', 'keterangan'
    ];

    // Relasi server
    public function server()
    {
        return $this->hasOne(Server::class, 'id_Aset', 'id_aset');
    }
}