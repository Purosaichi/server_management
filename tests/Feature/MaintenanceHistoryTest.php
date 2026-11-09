<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MaintenanceHistoryTest extends TestCase
{
    public function test_history_shows_soft_deleted_maintenance_only(): void
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->increments('id_maintenance');
            $table->string('kode_maintenance');
            $table->string('target_type');
            $table->unsignedInteger('id_server')->nullable();
            $table->unsignedInteger('id_aplikasi')->nullable();
            $table->unsignedInteger('id_pic')->nullable();
            $table->string('jenis_maintenance');
            $table->date('jadwal_tanggal')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
        Schema::create('server', function (Blueprint $table) {
            $table->increments('id_server');
            $table->string('nama_server');
        });
        Schema::create('aplikasi', function (Blueprint $table) {
            $table->increments('id_aplikasi');
            $table->string('nama_aplikasi');
        });
        Schema::create('pic', function (Blueprint $table) {
            $table->increments('id_pic');
            $table->string('nama_pic');
        });

        DB::table('server')->insert(['id_server' => 1, 'nama_server' => 'Server Arsip']);
        DB::table('maintenance')->insert([
            [
                'kode_maintenance' => 'MTN-SRV-001',
                'target_type' => 'Server',
                'id_server' => 1,
                'jenis_maintenance' => 'Pemeriksaan',
                'status' => 'Completed',
                'deleted_at' => now(),
            ],
            [
                'kode_maintenance' => 'MTN-SRV-002',
                'target_type' => 'Server',
                'id_server' => 1,
                'jenis_maintenance' => 'Pembaruan',
                'status' => 'Scheduled',
                'deleted_at' => null,
            ],
        ]);

        $this->withSession(['user_id' => 1])
            ->delete(route('maintenance.clear'))
            ->assertRedirect(route('maintenance.maintenance'));

        $this->withSession(['user_id' => 1])
            ->get(route('maintenance.history'))
            ->assertOk()
            ->assertSee('MTN-SRV-001')
            ->assertSee('Server Arsip')
            ->assertDontSee('MTN-SRV-002');
    }
}