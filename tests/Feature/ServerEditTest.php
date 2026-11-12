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
            $table->string('kode_aset')->nullable();
            $table->string('nama_aset');
            $table->string('status_aset');
            $table->unsignedInteger('id_lokasi_aset')->nullable();
            $table->string('kondisi_aset')->nullable();
            $table->string('status_kepemilikan')->nullable();
            $table->date('tanggal_pengadaan')->nullable();
            $table->integer('tahun_pengadaan')->nullable();
            $table->text('keterangan')->nullable();
        });

        Schema::create('data_center', function (Blueprint $table) {
            $table->increments('id_data_center');
            $table->string('nama_data_center');
        });

        Schema::create('lokasi_aset', function (Blueprint $table) {
            $table->increments('id_lokasi_aset');
            $table->unsignedInteger('id_aset')->nullable();
            $table->unsignedInteger('id_data_center')->nullable();
            $table->string('nama_gedung')->nullable();
            $table->string('lantai')->nullable();
            $table->string('nama_ruangan')->nullable();
            $table->string('zona')->nullable();
            $table->string('kode_rak')->nullable();
            $table->string('nama_rak')->nullable();
            $table->integer('tinggi_rak')->nullable();
            $table->string('posisi_rak')->nullable();
            $table->date('tanggal_penempatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
            'id_lokasi_aset' => 1,
        ]);
        DB::table('data_center')->insert([
            'id_data_center' => 1,
            'nama_data_center' => 'Data Center Utama',
        ]);
        DB::table('lokasi_aset')->insert([
            'id_lokasi_aset' => 1,
            'id_aset' => null,
            'id_data_center' => 1,
            'nama_gedung' => 'Gedung A',
            'nama_ruangan' => 'Ruang Server',
            'kode_rak' => 'A01',
            'nama_rak' => 'Rack A01',
        ]);
        DB::table('aset')->insert([
            'id_aset' => 2,
            'nama_aset' => 'Aset Lain',
            'status_aset' => 'Aktif',
            'id_lokasi_aset' => 2,
        ]);
        DB::table('lokasi_aset')->insert([
            'id_lokasi_aset' => 2,
            'id_aset' => null,
            'id_data_center' => 1,
            'nama_gedung' => 'Gedung B',
            'nama_ruangan' => 'Ruang Server Cadangan',
            'kode_rak' => 'B02',
            'nama_rak' => 'Rack B02',
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
            ->assertSee('value="1" selected', false)
            ->assertSee('Data Center Utama - Gedung A - Ruang Server - A01 - Rack A01')
            ->assertSee('value="Online"', false);
    }

    public function test_server_create_page_lists_asset_locations(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('server.create'))
            ->assertOk()
            ->assertSee('Lokasi Aset')
            ->assertSee('value="1"', false)
            ->assertSee('Data Center Utama - Gedung A - Ruang Server - A01 - Rack A01');
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
                'lokasi_aset_id' => 2,
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
        $this->assertDatabaseHas('lokasi_aset', [
            'id_lokasi_aset' => 1,
            'id_aset' => null,
            'nama_gedung' => 'Gedung A',
            'nama_rak' => 'Rack A01',
        ]);
        $this->assertDatabaseHas('lokasi_aset', [
            'id_lokasi_aset' => 2,
            'id_aset' => null,
            'nama_gedung' => 'Gedung B',
            'nama_rak' => 'Rack B02',
        ]);
        $this->assertDatabaseHas('aset', [
            'id_aset' => 1,
            'id_lokasi_aset' => 2,
        ]);
    }

    public function test_server_can_be_assigned_to_a_shared_asset_location(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->post(route('server.store'), [
                'nama_server' => 'Server Baru',
                'lokasi_aset_id' => 2,
                'status_aset' => 'Aktif',
                'status_monitoring' => 'Online',
            ])
            ->assertRedirect(route('server.index'))
            ->assertSessionHas('success', 'Server berhasil ditambahkan!');

        $newAsset = DB::table('aset')->where('nama_aset', 'Server Baru')->first();

        $this->assertNotNull($newAsset);
        $this->assertDatabaseHas('aset', [
            'id_aset' => $newAsset->id_aset,
            'id_lokasi_aset' => 2,
        ]);
        $this->assertDatabaseHas('lokasi_aset', [
            'id_lokasi_aset' => 2,
            'id_aset' => null,
            'nama_rak' => 'Rack B02',
        ]);
    }
}
