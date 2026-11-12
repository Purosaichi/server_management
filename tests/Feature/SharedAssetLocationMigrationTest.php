<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SharedAssetLocationMigrationTest extends TestCase
{
    public function test_existing_asset_locations_are_migrated_to_shared_location_records(): void
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->increments('id_aset');
            $table->string('nama_aset');
        });

        Schema::create('lokasi_aset', function (Blueprint $table) {
            $table->increments('id_lokasi_aset');
            $table->unsignedInteger('id_aset');
            $table->unsignedInteger('id_data_center')->nullable();
            $table->string('nama_gedung')->nullable();
            $table->string('lantai')->nullable();
            $table->string('nama_ruangan')->nullable();
            $table->string('zona')->nullable();
            $table->string('kode_rak')->nullable();
            $table->string('nama_rak')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        DB::table('aset')->insert([
            ['id_aset' => 1, 'nama_aset' => 'Server Aktif'],
            ['id_aset' => 2, 'nama_aset' => 'Server Tanpa Lokasi Aktif'],
        ]);
        DB::table('lokasi_aset')->insert([
            [
                'id_lokasi_aset' => 10,
                'id_aset' => 1,
                'id_data_center' => 1,
                'nama_gedung' => 'Gedung A',
                'lantai' => null,
                'nama_ruangan' => 'Ruang Server',
                'zona' => null,
                'kode_rak' => 'A01',
                'nama_rak' => 'Rack A01',
                'deleted_at' => null,
            ],
            [
                'id_lokasi_aset' => 20,
                'id_aset' => 2,
                'id_data_center' => 1,
                'nama_gedung' => 'Gedung A',
                'lantai' => null,
                'nama_ruangan' => 'Ruang Server',
                'zona' => null,
                'kode_rak' => 'A01',
                'nama_rak' => 'Rack A01',
                'deleted_at' => null,
            ],
            [
                'id_lokasi_aset' => 30,
                'id_aset' => 2,
                'id_data_center' => null,
                'nama_gedung' => null,
                'lantai' => null,
                'nama_ruangan' => null,
                'zona' => null,
                'kode_rak' => null,
                'nama_rak' => null,
                'deleted_at' => now(),
            ],
        ]);

        $migration = require database_path('migrations/2026_11_12_000000_make_asset_locations_shared.php');
        $migration->up();

        $this->assertDatabaseHas('aset', [
            'id_aset' => 1,
            'id_lokasi_aset' => 10,
        ]);
        $this->assertDatabaseHas('aset', [
            'id_aset' => 2,
            'id_lokasi_aset' => 10,
        ]);
        $this->assertDatabaseHas('lokasi_aset', [
            'id_lokasi_aset' => 10,
            'id_aset' => null,
        ]);
        $this->assertDatabaseHas('lokasi_aset', [
            'id_lokasi_aset' => 20,
            'id_aset' => null,
        ]);
        $this->assertNotNull(DB::table('lokasi_aset')->where('id_lokasi_aset', 20)->value('deleted_at'));
        $this->assertDatabaseHas('lokasi_aset', [
            'id_lokasi_aset' => 30,
            'id_aset' => null,
        ]);
    }
}
