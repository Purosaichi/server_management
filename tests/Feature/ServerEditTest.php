<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ServerEditTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('aset', function (Blueprint $table) {
            $table->increments('id_aset');
            $table->string('nama_aset');
            $table->string('status_aset');
        });

        Schema::create('server', function (Blueprint $table) {
            $table->increments('id_server');
            $table->unsignedInteger('id_aset');
            $table->string('nama_server');
            $table->string('hostname')->nullable();
            $table->string('alamat_ip_manajemen')->nullable();
            $table->string('alamat_ip_produksi')->nullable();
            $table->string('sistem_operasi')->nullable();
            $table->string('jenis_server')->nullable();
            $table->integer('jumlah_prosessor')->nullable();
            $table->string('model_prosessor')->nullable();
            $table->integer('kapasitas_memori_gb')->nullable();
            $table->string('jenis_memori')->nullable();
            $table->integer('kapasitas_penyimpanan_gb')->nullable();
            $table->string('jenis_penyimpanan')->nullable();
            $table->string('lokasi_rack')->nullable();
        });

        Schema::create('server_monitoring', function (Blueprint $table) {
            $table->increments('id_monitoring');
            $table->unsignedInteger('id_server');
            $table->string('status');
            $table->integer('cpu_percent')->default(0);
            $table->integer('memory_percent')->default(0);
            $table->integer('storage_percent')->default(0);
            $table->integer('uptime_detik')->default(0);
            $table->dateTime('dicatat_pada');
        });

        DB::table('aset')->insert([
            'id_aset' => 1,
            'nama_aset' => 'Server Utama',
            'status_aset' => 'Aktif',
        ]);
        DB::table('server')->insert([
            'id_server' => 1,
            'id_aset' => 1,
            'nama_server' => 'Server Utama',
            'hostname' => 'srv-utama.local',
            'alamat_ip_produksi' => '192.0.2.10',
        ]);
        DB::table('server_monitoring')->insert([
            'id_server' => 1,
            'status' => 'Online',
            'cpu_percent' => 20,
            'memory_percent' => 30,
            'storage_percent' => 40,
            'uptime_detik' => 3600,
            'dicatat_pada' => now(),
        ]);
    }

    public function test_server_edit_page_loads_existing_values(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('server.edit', 1))
            ->assertOk()
            ->assertSee('Edit Server: Server Utama')
            ->assertSee('value="srv-utama.local"', false)
            ->assertSee('value="192.0.2.10"', false)
            ->assertSee('value="Online"', false);
    }

    public function test_server_can_be_updated(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put(route('server.update', 1), [
                'nama_server' => 'Server Aplikasi',
                'hostname' => 'srv-aplikasi.local',
                'alamat_ip_manajemen' => '192.0.2.11',
                'alamat_ip_produksi' => '192.0.2.12',
                'sistem_operasi' => 'Linux',
                'jenis_server' => 'Virtual',
                'jumlah_prosessor' => 8,
                'model_prosessor' => 'AMD EPYC',
                'kapasitas_memori_gb' => 64,
                'jenis_memori' => 'DDR5',
                'kapasitas_penyimpanan_gb' => 1024,
                'jenis_penyimpanan' => 'NVMe',
                'lokasi_rack' => 'DC Utama - Rack A',
                'status_aset' => 'Aktif',
                'status_monitoring' => 'Offline',
                'cpu_percent' => 0,
                'memory_percent' => 15,
                'storage_percent' => 25,
            ])
            ->assertRedirect(route('server.index'))
            ->assertSessionHas('success', 'Server berhasil diupdate!');

        $this->assertDatabaseHas('server', [
            'id_server' => 1,
            'nama_server' => 'Server Aplikasi',
            'hostname' => 'srv-aplikasi.local',
            'jenis_server' => 'Virtual',
        ]);
        $this->assertDatabaseHas('aset', [
            'id_aset' => 1,
            'nama_aset' => 'Server Aplikasi',
            'status_aset' => 'Aktif',
        ]);
        $this->assertDatabaseHas('server_monitoring', [
            'id_server' => 1,
            'status' => 'Offline',
            'memory_percent' => 15,
            'storage_percent' => 25,
        ]);
    }
}
