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

    public function test_asset_location_menu_displays_location_records(): void
    {
        $this->createLocationTable();
        $this->createDataCenterTable();
        DB::table('data_center')->insert([
            'id_data_center' => 1,
            'nama_data_center' => 'DC Utama',
            'deleted_at' => null,
        ]);
        DB::table('lokasi_aset')->insert([
            'id_aset' => 17,
            'id_data_center' => 1,
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
            ->assertDontSee('ID Lokasi')
            ->assertDontSee('ID Aset')
            ->assertSee('Data Center')
            ->assertSee('DC Utama')
            ->assertSee('Ruang Server')
            ->assertDontSee('Lokasi utama')
            ->assertDontSee('U10-U20')
            ->assertSee('data-detail-url="' . route('aset.lokasi.detail', 1) . '"', false)
            ->assertSee('ondblclick=', false)
            ->assertSee(route('aset.lokasi.edit', 1))
            ->assertDontSee('asset-location-detail-button')
            ->assertSee('Edit')
            ->assertSee(route('aset.lokasi.destroy', 1))
            ->assertSee('Hapus');
    }

    public function test_asset_location_detail_displays_full_location_information(): void
    {
        $this->createLocationTable();
        $this->createDataCenterTable();
        Schema::create('aset', function (Blueprint $table) {
            $table->increments('id_aset');
            $table->string('kode_aset');
            $table->string('nama_aset');
        });
        DB::table('aset')->insert([
            'id_aset' => 17,
            'kode_aset' => 'AST-017',
            'nama_aset' => 'Server Utama',
        ]);
        DB::table('data_center')->insert([
            'id_data_center' => 1,
            'nama_data_center' => 'DC Utama',
            'deleted_at' => null,
        ]);
        DB::table('lokasi_aset')->insert([
            'id_lokasi_aset' => 1,
            'id_aset' => 17,
            'id_data_center' => 1,
            'nama_gedung' => 'Gedung A',
            'lantai' => '2',
            'nama_ruangan' => 'Ruang Server',
            'zona' => 'Utara',
            'kode_rak' => 'RK-01',
            'nama_rak' => 'Rak Utama',
            'tinggi_rak' => 42,
            'posisi_rak' => 'U10-U20',
            'tanggal_penempatan' => '2026-11-01',
            'keterangan' => 'Lokasi utama',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.lokasi.detail', 1))
            ->assertOk()
            ->assertSee('AST-017')
            ->assertSee('Server Utama')
            ->assertSee('DC Utama')
            ->assertSee('Gedung A')
            ->assertSee('Ruang Server')
            ->assertSee('RK-01')
            ->assertSee('42U')
            ->assertSee('U10-U20')
            ->assertSee('1 Nov 2026')
            ->assertSee('Lokasi utama')
            ->assertSee(route('aset.lokasi.edit', 1));
    }

    public function test_asset_location_data_center_tab_displays_data_centers(): void
    {
        $this->createDataCenterTable();
        DB::table('data_center')->insert([
            'nama_data_center' => 'DC Cadangan',
            'lokasi' => 'Bekasi',
            'tipe_dc' => 'DRP',
            'keterangan' => 'Lokasi pemulihan bencana',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.data-centers'))
            ->assertOk()
            ->assertSee('Data Center')
            ->assertSee('DC Cadangan')
            ->assertSee('Bekasi')
            ->assertSee('DRP')
            ->assertSee('Lokasi pemulihan bencana')
            ->assertSee('Lokasi Aset')
            ->assertSee('Tambah Data Center')
            ->assertSee(route('aset.data-centers.create'))
            ->assertSee(route('aset.data-centers.edit', 1))
            ->assertSee(route('aset.data-centers.destroy', 1))
            ->assertSee('Edit');
    }

    public function test_data_center_can_be_created(): void
    {
        $this->createDataCenterTable();

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.data-centers.create'))
            ->assertOk()
            ->assertSee('Tambah Data Center')
            ->assertSee('Nama Data Center')
            ->assertSee('<select name="tipe_dc">', false)
            ->assertSee('<option value="Primary"', false)
            ->assertSee('<option value="DRP"', false)
            ->assertSee('<option value="Cloud"', false);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->post(route('aset.data-centers.store'), [
                'nama_data_center' => 'DC Baru',
                'lokasi' => 'Bandung',
                'tipe_dc' => 'Primary',
                'keterangan' => 'Data center operasional baru',
            ])
            ->assertRedirect(route('aset.data-centers'))
            ->assertSessionHas('success', 'Data Center berhasil ditambahkan.');

        $this->assertDatabaseHas('data_center', [
            'nama_data_center' => 'DC Baru',
            'lokasi' => 'Bandung',
            'tipe_dc' => 'Primary',
            'keterangan' => 'Data center operasional baru',
        ]);
    }

    public function test_data_center_can_be_edited(): void
    {
        $this->createDataCenterTable();
        DB::table('data_center')->insert([
            'id_data_center' => 1,
            'nama_data_center' => 'DC Lama',
            'lokasi' => 'Jakarta',
            'tipe_dc' => 'Primary',
            'keterangan' => 'Pusat data lama',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.data-centers.edit', 1))
            ->assertOk()
            ->assertSee('Edit Data Center')
            ->assertSee('value="DC Lama"', false)
            ->assertSee('value="Jakarta"', false)
            ->assertSee('Pusat data lama');

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put(route('aset.data-centers.update', 1), [
                'nama_data_center' => 'DC Baru',
                'lokasi' => 'Bandung',
                'tipe_dc' => 'DRP',
                'keterangan' => 'Pusat data cadangan',
            ])
            ->assertRedirect(route('aset.data-centers'))
            ->assertSessionHas('success', 'Data Center berhasil diperbarui.');

        $this->assertDatabaseHas('data_center', [
            'id_data_center' => 1,
            'nama_data_center' => 'DC Baru',
            'lokasi' => 'Bandung',
            'tipe_dc' => 'DRP',
            'keterangan' => 'Pusat data cadangan',
        ]);
    }

    public function test_data_center_can_be_soft_deleted(): void
    {
        $this->createDataCenterTable();
        DB::table('data_center')->insert([
            'id_data_center' => 1,
            'nama_data_center' => 'DC Arsip',
            'lokasi' => 'Bandung',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete(route('aset.data-centers.destroy', 1))
            ->assertRedirect(route('aset.data-centers'))
            ->assertSessionHas('success', 'Data Center berhasil dihapus.');

        $this->assertDatabaseMissing('data_center', [
            'id_data_center' => 1,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseHas('data_center', [
            'id_data_center' => 1,
            'nama_data_center' => 'DC Arsip',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.data-centers'))
            ->assertOk()
            ->assertDontSee('DC Arsip');
    }

    public function test_location_can_be_created_for_an_asset(): void
    {
        $this->createLocationTable();
        $this->createDataCenterTable();
        Schema::create('aset', function (Blueprint $table) {
            $table->increments('id_aset');
            $table->string('kode_aset');
            $table->string('nama_aset');
        });
        DB::table('aset')->insert([
            'id_aset' => 17,
            'kode_aset' => 'AST-017',
            'nama_aset' => 'Server Utama',
        ]);
        DB::table('data_center')->insert([
            'id_data_center' => 1,
            'nama_data_center' => 'DC Utama',
            'lokasi' => 'Jakarta',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.lokasi.create'))
            ->assertOk()
            ->assertSee('Tambah Lokasi Aset')
            ->assertSee('AST-017 - Server Utama')
            ->assertSee('DC Utama - Jakarta');

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->post(route('aset.lokasi.store'), [
                'id_aset' => 17,
                'id_data_center' => 1,
                'nama_gedung' => 'Gedung A',
                'lantai' => '2',
                'nama_ruangan' => 'Ruang Server',
                'zona' => 'Utara',
                'kode_rak' => 'RK-01',
                'nama_rak' => 'Rak Utama',
                'tinggi_rak' => 42,
                'posisi_rak' => 'U10-U20',
                'tanggal_penempatan' => '2026-11-01',
                'keterangan' => 'Lokasi utama',
            ])
            ->assertRedirect(route('aset.lokasi'))
            ->assertSessionHas('success', 'Lokasi aset berhasil ditambahkan.');

        $this->assertDatabaseHas('lokasi_aset', [
            'id_aset' => 17,
            'id_data_center' => 1,
            'kode_rak' => 'RK-01',
            'tinggi_rak' => 42,
            'keterangan' => 'Lokasi utama',
        ]);
    }

    public function test_location_can_be_edited(): void
    {
        $this->createLocationTable();
        $this->createDataCenterTable();
        Schema::create('aset', function (Blueprint $table) {
            $table->increments('id_aset');
            $table->string('kode_aset');
            $table->string('nama_aset');
        });
        DB::table('aset')->insert([
            'id_aset' => 17,
            'kode_aset' => 'AST-017',
            'nama_aset' => 'Server Utama',
        ]);
        DB::table('data_center')->insert([
            'id_data_center' => 1,
            'nama_data_center' => 'DC Utama',
            'lokasi' => 'Jakarta',
            'deleted_at' => null,
        ]);
        DB::table('lokasi_aset')->insert([
            'id_lokasi_aset' => 1,
            'id_aset' => 17,
            'id_data_center' => 1,
            'nama_gedung' => 'Gedung A',
            'zona' => 'Utara',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.lokasi.edit', 1))
            ->assertOk()
            ->assertSee('Edit Lokasi Aset')
            ->assertSee('AST-017 - Server Utama')
            ->assertSee('value="Gedung A"', false)
            ->assertSee('value="Utara"', false);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put(route('aset.lokasi.update', 1), [
                'id_aset' => 17,
                'id_data_center' => 1,
                'nama_gedung' => 'Gedung B',
                'lantai' => '3',
                'nama_ruangan' => 'Ruang Baru',
                'zona' => 'Selatan',
                'kode_rak' => 'RK-02',
                'nama_rak' => 'Rak Baru',
                'tinggi_rak' => 42,
                'posisi_rak' => 'U1-U42',
                'tanggal_penempatan' => '2026-11-02',
                'keterangan' => 'Lokasi diperbarui',
            ])
            ->assertRedirect(route('aset.lokasi'))
            ->assertSessionHas('success', 'Lokasi aset berhasil diperbarui.');

        $this->assertDatabaseHas('lokasi_aset', [
            'id_lokasi_aset' => 1,
            'nama_gedung' => 'Gedung B',
            'zona' => 'Selatan',
            'keterangan' => 'Lokasi diperbarui',
        ]);
    }

    public function test_location_can_be_soft_deleted(): void
    {
        $this->createLocationTable();
        $this->createDataCenterTable();
        DB::table('lokasi_aset')->insert([
            'id_lokasi_aset' => 1,
            'id_aset' => 17,
            'nama_gedung' => 'Gedung A',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete(route('aset.lokasi.destroy', 1))
            ->assertRedirect(route('aset.lokasi'))
            ->assertSessionHas('success', 'Lokasi aset berhasil dihapus.');

        $this->assertDatabaseMissing('lokasi_aset', [
            'id_lokasi_aset' => 1,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseHas('lokasi_aset', [
            'id_lokasi_aset' => 1,
            'nama_gedung' => 'Gedung A',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.lokasi'))
            ->assertOk()
            ->assertDontSee('Gedung A');
    }

    public function test_storage_page_displays_storage_records_and_capacity_summary(): void
    {
        $this->createStorageTables();
        DB::table('aset')->insert([
            ['id_aset' => 1, 'kode_aset' => 'AST-001', 'nama_aset' => 'Storage Utama'],
            ['id_aset' => 2, 'kode_aset' => 'AST-002', 'nama_aset' => 'Storage Arsip'],
        ]);
        DB::table('storage')->insert([
            [
                'id_storage' => 1,
                'id_aset' => 1,
                'jenis_storage' => 'SAN',
                'kapasitas_raw' => 10000,
                'kapasitas_terpakai' => 4500,
                'kapasitas_tersedia' => 5500,
                'persentase_penggunaan' => 45.00,
                'jenis_disk' => 'SSD',
                'jumlah_disk' => 24,
                'tingkat_raid' => 'RAID 6',
                'jumlah_pengendali' => 2,
                'protokol_storage' => 'Fibre Channel',
                'jumlah_volume' => 8,
                'jumlah_lun' => 16,
                'kapasitas_snapshot' => 1000,
                'status_replikasi' => 'Aktif',
                'alamat_ip_manajemen' => '192.0.2.20',
                'deleted_at' => null,
            ],
            [
                'id_storage' => 2,
                'id_aset' => 2,
                'jenis_storage' => 'NAS',
                'kapasitas_raw' => 99999,
                'kapasitas_terpakai' => null,
                'kapasitas_tersedia' => null,
                'persentase_penggunaan' => null,
                'jenis_disk' => null,
                'jumlah_disk' => null,
                'tingkat_raid' => null,
                'jumlah_pengendali' => null,
                'protokol_storage' => null,
                'jumlah_volume' => null,
                'jumlah_lun' => null,
                'kapasitas_snapshot' => null,
                'status_replikasi' => null,
                'alamat_ip_manajemen' => null,
                'deleted_at' => now(),
            ],
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage'))
            ->assertOk()
            ->assertSee('ID Storage')
            ->assertSee('ID Aset: 1')
            ->assertSee('Storage Utama')
            ->assertSee('AST-001')
            ->assertSee('SAN')
            ->assertSee('RAID 6')
            ->assertSee('Fibre Channel')
            ->assertSee('192.0.2.20')
            ->assertSee('45,00%')
            ->assertSee('10.000')
            ->assertSee('4.500')
            ->assertSee('5.500')
            ->assertDontSee('Storage Arsip')
            ->assertSee('Storage', false);
    }

    public function test_storage_page_works_when_deleted_at_column_is_missing(): void
    {
        $this->createStorageTables(false);
        DB::table('aset')->insert([
            'id_aset' => 1,
            'kode_aset' => 'AST-001',
            'nama_aset' => 'Storage Utama',
        ]);
        DB::table('storage')->insert([
            'id_storage' => 1,
            'id_aset' => 1,
            'jenis_storage' => 'SAN',
            'kapasitas_raw' => 10000,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage'))
            ->assertOk()
            ->assertSee('Storage Utama')
            ->assertSee('SAN');
    }

    private function createStorageTables(bool $withDeletedAt = true): void
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->increments('id_aset');
            $table->string('kode_aset');
            $table->string('nama_aset');
        });

        Schema::create('storage', function (Blueprint $table) use ($withDeletedAt) {
            $table->increments('id_storage');
            $table->unsignedInteger('id_aset');
            $table->string('jenis_storage', 50)->nullable();
            $table->integer('kapasitas_raw')->nullable();
            $table->integer('kapasitas_terpakai')->nullable();
            $table->integer('kapasitas_tersedia')->nullable();
            $table->decimal('persentase_penggunaan', 5, 2)->nullable();
            $table->string('jenis_disk', 50)->nullable();
            $table->integer('jumlah_disk')->nullable();
            $table->string('tingkat_raid', 10)->nullable();
            $table->integer('jumlah_pengendali')->nullable();
            $table->string('protokol_storage', 50)->nullable();
            $table->integer('jumlah_volume')->nullable();
            $table->integer('jumlah_lun')->nullable();
            $table->integer('kapasitas_snapshot')->nullable();
            $table->string('status_replikasi', 50)->nullable();
            $table->string('alamat_ip_manajemen', 45)->nullable();
            if ($withDeletedAt) {
                $table->timestamp('deleted_at')->nullable();
            }
        });
    }

    private function createLocationTable(): void
    {
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
            $table->integer('tinggi_rak')->nullable();
            $table->string('posisi_rak')->nullable();
            $table->date('tanggal_penempatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    private function createDataCenterTable(): void
    {
        Schema::create('data_center', function (Blueprint $table) {
            $table->increments('id_data_center');
            $table->string('nama_data_center');
            $table->string('lokasi')->nullable();
            $table->string('tipe_dc')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
}