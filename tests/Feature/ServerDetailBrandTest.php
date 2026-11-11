<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ServerDetailBrandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('vw_server_status', function (Blueprint $table) {
            $table->unsignedInteger('id_server')->primary();
            $table->string('nama_server');
            $table->string('alamat_ip_produksi')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('cpu_percent')->nullable();
            $table->unsignedInteger('memory_percent')->nullable();
            $table->unsignedInteger('storage_percent')->nullable();
            $table->unsignedBigInteger('uptime_detik')->nullable();
            $table->string('subnet_mask')->nullable();
            $table->string('gateway')->nullable();
            $table->string('dns_server')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('network_speed')->nullable();
            $table->string('network_usage_down')->nullable();
            $table->string('network_usage_up')->nullable();
            $table->dateTime('pengecekan_terakhir')->nullable();
        });

        Schema::create('server', function (Blueprint $table) {
            $table->increments('id_server');
            $table->unsignedInteger('id_aset');
            $table->string('jenis_server')->nullable();
            $table->string('server_role')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('aset', function (Blueprint $table) {
            $table->increments('id_aset');
            $table->unsignedInteger('id_merek')->nullable();
            $table->string('model')->nullable();
            $table->string('nomor_seri')->nullable();
            $table->date('tanggal_pengadaan')->nullable();
        });

        Schema::create('merek', function (Blueprint $table) {
            $table->increments('id_merek');
            $table->string('nama_merek');
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('server_monitoring', function (Blueprint $table) {
            $table->increments('id_monitoring');
            $table->unsignedInteger('id_server');
            $table->string('status');
            $table->unsignedInteger('cpu_percent')->default(0);
            $table->unsignedInteger('memory_percent')->default(0);
            $table->unsignedInteger('storage_percent')->default(0);
            $table->unsignedBigInteger('uptime_detik')->default(0);
            $table->dateTime('dicatat_pada');
        });

        Schema::create('aplikasi', function (Blueprint $table) {
            $table->increments('id_aplikasi');
            $table->unsignedInteger('id_server');
            $table->timestamp('deleted_at')->nullable();
            $table->string('nama_aplikasi');
            $table->string('kode_aplikasi')->nullable();
            $table->string('kategori')->nullable();
            $table->string('versi')->nullable();
            $table->string('status')->nullable();
        });

        Schema::create('vw_maintenance', function (Blueprint $table) {
            $table->unsignedInteger('id_maintenance')->nullable();
            $table->string('target_type')->nullable();
            $table->string('nama_target')->nullable();
            $table->dateTime('jadwal_tanggal')->nullable();
            $table->string('jam_mulai')->nullable();
            $table->string('jam_selesai')->nullable();
            $table->string('jenis_maintenance')->nullable();
            $table->unsignedInteger('id_pic')->nullable();
            $table->string('status')->nullable();
            $table->text('catatan')->nullable();
            $table->string('nama_pic')->nullable();
        });

        Schema::create('pic', function (Blueprint $table) {
            $table->increments('id_pic');
            $table->string('nama_pic');
            $table->string('jabatan')->nullable();
        });

        Schema::create('server_notes', function (Blueprint $table) {
            $table->increments('id_note');
            $table->unsignedInteger('id_server');
            $table->text('catatan')->nullable();
            $table->unsignedInteger('id_pic')->nullable();
            $table->dateTime('dibuat_pada')->nullable();
            $table->dateTime('diperbarui_pada')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        DB::table('merek')->insert([
            'id_merek' => 1,
            'nama_merek' => 'Lenovo',
        ]);
        DB::table('aset')->insert([
            'id_aset' => 1,
            'id_merek' => 1,
        ]);
        DB::table('server')->insert([
            'id_server' => 1,
            'id_aset' => 1,
            'jenis_server' => 'Physical',
            'server_role' => 'Application Server',
        ]);
        DB::table('vw_server_status')->insert([
            'id_server' => 1,
            'nama_server' => 'Server Utama',
            'alamat_ip_produksi' => '192.0.2.10',
            'status' => 'Online',
            'cpu_percent' => 20,
            'memory_percent' => 30,
            'storage_percent' => 40,
            'uptime_detik' => 3600,
            'pengecekan_terakhir' => '2026-11-11 04:46:45',
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

    public function test_server_detail_displays_the_brand_linked_to_its_asset(): void
    {
        Carbon::setTestNow('2026-11-11 05:00:00');
        try {
            $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
                ->get(route('server.detail', 1))
                ->assertOk()
                ->assertSee('Merek')
                ->assertSee('Lenovo')
                ->assertSee('11 November 2026, 04:46:45')
                ->assertSee('yang lalu')
                ->assertSee('title="Edit Informasi Dasar"', false)
                ->assertSee('onclick="openBasicInfoModal()"', false);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_server_detail_basic_information_can_be_updated(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put(route('server.update-basic-info', 1), [
                'jenis_server' => 'Virtual',
                'server_role' => 'Database Server',
                'id_merek' => 1,
                'model' => 'ThinkSystem SR650',
                'nomor_seri' => 'SN-12345',
                'tanggal_pengadaan' => '2025-01-15',
                'status_monitoring' => 'Offline',
            ])
            ->assertRedirect(route('server.detail', 1))
            ->assertSessionHas('success', 'Informasi dasar server berhasil diperbarui.');

        $this->assertDatabaseHas('server', [
            'id_server' => 1,
            'jenis_server' => 'Virtual',
            'server_role' => 'Database Server',
        ]);
        $this->assertDatabaseHas('aset', [
            'id_aset' => 1,
            'id_merek' => 1,
            'model' => 'ThinkSystem SR650',
            'nomor_seri' => 'SN-12345',
            'tanggal_pengadaan' => '2025-01-15',
        ]);
        $this->assertDatabaseHas('server_monitoring', [
            'id_server' => 1,
            'status' => 'Offline',
            'cpu_percent' => 20,
            'memory_percent' => 30,
            'storage_percent' => 40,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('server.detail', 1))
            ->assertOk()
            ->assertDontSee('Warranty Until')
            ->assertDontSee('name="tanggal_garansi"', false);
    }
}
