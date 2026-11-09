<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AsetPageTest extends TestCase
{
    public function test_asset_menu_opens_the_placeholder_page(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.index'))
            ->assertOk()
            ->assertSee('Halaman Aset')
            ->assertSee('Data inventaris aset akan ditampilkan di sini.')
            ->assertSee(route('server.index'))
            ->assertSee('Server')
            ->assertSee(route('network-device.router'))
            ->assertSee('Network Device')
            ->assertSee(route('aset.storage'))
            ->assertSee('Storage')
            ->assertSee('data-sidebar-toggle', false)
            ->assertSee('aria-controls="asetSubmenu"', false);
    }

    public function test_asset_location_menu_opens_its_placeholder_page(): void
    {
        Schema::create('lokasi_aset', function (Blueprint $table) {
            $table->increments('id_lokasi_aset');
            $table->unsignedInteger('id_aset');
            $table->string('nama_data_center')->nullable();
            $table->string('nama_gedung')->nullable();
            $table->string('lantai')->nullable();
            $table->string('nama_ruangan')->nullable();
            $table->string('zona')->nullable();
            $table->string('kode_rak')->nullable();
            $table->string('nama_rak')->nullable();
            $table->string('tinggi_rak')->nullable();
            $table->string('posisi_rak')->nullable();
            $table->date('tanggal_penempatan')->nullable();
            $table->text('keterangan')->nullable();
        });
        DB::table('lokasi_aset')->insert([
            'id_aset' => 17,
            'nama_data_center' => 'DC Utama',
            'nama_gedung' => 'Gedung A',
            'lantai' => '2',
            'nama_ruangan' => 'Ruang Server',
            'zona' => 'Utara',
            'kode_rak' => 'RK-01',
            'nama_rak' => 'Rak Utama',
            'tinggi_rak' => '42U',
            'posisi_rak' => 'U10-U20',
            'tanggal_penempatan' => '2026-11-01',
            'keterangan' => 'Lokasi utama',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.lokasi'))
            ->assertOk()
            ->assertSee('ID Lokasi')
            ->assertSee('Data Center')
            ->assertSee('DC Utama')
            ->assertSee('Ruang Server')
            ->assertSee('Lokasi utama');
    }

    public function test_storage_menu_opens_its_placeholder_page(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage'))
            ->assertOk()
            ->assertSee('Halaman Storage')
            ->assertSee('Data storage aset akan ditampilkan di sini.');
    }
}