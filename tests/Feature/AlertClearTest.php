<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AlertClearTest extends TestCase
{
    public function test_clear_archives_completed_alerts_and_history_displays_them(): void
    {
        Schema::create('alert', function (Blueprint $table) {
            $table->increments('id_alert');
            $table->string('level')->default('Warning');
            $table->string('deskripsi')->default('Alert test');
            $table->text('keterangan')->nullable();
            $table->unsignedInteger('id_server')->nullable();
            $table->unsignedInteger('id_aplikasi')->nullable();
            $table->unsignedInteger('id_domain')->nullable();
            $table->unsignedInteger('id_pic')->nullable();
            $table->string('target_type')->default('Server');
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->string('status');
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
        Schema::create('domain', function (Blueprint $table) {
            $table->increments('id_domain');
            $table->string('nama_domain');
        });
        Schema::create('pic', function (Blueprint $table) {
            $table->increments('id_pic');
            $table->string('nama_pic');
        });

        DB::table('alert')->insert([
            ['status' => 'Aktif', 'deskripsi' => 'Alert aktif'],
            ['status' => 'Selesai', 'deskripsi' => 'Alert selesai satu'],
            ['status' => 'Selesai', 'deskripsi' => 'Alert selesai dua'],
        ]);

        $this->withSession(['user_id' => 1])
            ->delete('/alerts')
            ->assertRedirect(route('alerts.alerts'))
            ->assertSessionHas('success', 'Berhasil mengarsipkan 2 alert berstatus Selesai.');

        $this->assertDatabaseHas('alert', ['id_alert' => 1, 'status' => 'Aktif']);
        $this->assertDatabaseHas('alert', ['id_alert' => 2, 'status' => 'Selesai']);
        $this->assertNotNull(DB::table('alert')->where('id_alert', 2)->value('deleted_at'));
        $this->assertNotNull(DB::table('alert')->where('id_alert', 3)->value('deleted_at'));

        $this->withSession(['user_id' => 1])
            ->get(route('alerts.history'))
            ->assertOk()
            ->assertSee('Alert selesai satu')
            ->assertSee('Alert selesai dua')
            ->assertDontSee('Alert aktif');
    }
}
