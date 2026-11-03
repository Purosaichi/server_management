<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AlertClearTest extends TestCase
{
    public function test_clear_deletes_completed_alerts_and_keeps_active_alerts(): void
    {
        Schema::create('alert', function (Blueprint $table) {
            $table->increments('id_alert');
            $table->string('status');
        });

        DB::table('alert')->insert([
            ['status' => 'Aktif'],
            ['status' => 'Selesai'],
            ['status' => 'Selesai'],
        ]);

        $this->withSession(['user_id' => 1])
            ->delete('/alerts')
            ->assertRedirect(route('alerts.alerts'))
            ->assertSessionHas('success', 'Berhasil menghapus 2 alert berstatus Selesai.');

        $this->assertDatabaseHas('alert', ['id_alert' => 1, 'status' => 'Aktif']);
        $this->assertDatabaseMissing('alert', ['id_alert' => 2]);
        $this->assertDatabaseMissing('alert', ['id_alert' => 3]);
    }
}
