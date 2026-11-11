<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
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
            ->assertSee(route('aset.penyedia'))
            ->assertSee('Penyedia')
            ->assertSee(route('aset.merek'))
            ->assertSee('Merek')
            ->assertSee(route('aset.garansi'))
            ->assertSee('Garansi')
            ->assertSee('data-sidebar-toggle', false)
            ->assertSee('aria-controls="asetSubmenu"', false);
    }

    public function test_warranty_page_displays_warranty_records(): void
    {
        $this->createWarrantyTables();

        DB::table('aset')->insert([
            'id_aset' => 1,
            'nama_aset' => 'Aset Server Utama',
        ]);
        DB::table('server')->insert([
            'id_server' => 1,
            'id_aset' => 1,
            'nama_server' => 'Server Utama',
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 1,
            'id_aset' => 1,
            'id_server' => 1,
            'nomor_garansi' => 'GAR-001',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_berakhir' => '2028-01-01',
            'tingkat_layanan' => 'On-site',
            'tingkat_sla' => '24x7',
            'status_garansi' => 'Aktif',
            'keterangan' => 'Garansi vendor',
        ]);
        DB::table('aset')->insert([
            'id_aset' => 2,
            'nama_aset' => 'Aset Server Lama',
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 2,
            'id_aset' => 2,
            'nomor_garansi' => 'GAR-002',
            'tanggal_berakhir' => '2026-11-10',
            'status_garansi' => 'Aktif',
        ]);

        Carbon::setTestNow('2026-11-11 12:00:00');
        try {
            $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
                ->get(route('aset.garansi'))
                ->assertOk()
                ->assertSee('Daftar masa garansi aset dan server')
                ->assertSee('GAR-001')
                ->assertSee('Server Utama')
                ->assertSee('01/01/2028')
                ->assertSee('On-site')
                ->assertSee('24x7')
                ->assertSee('Garansi vendor')
                ->assertSee('warranty-status-active', false)
                ->assertSee('warranty-status-ended', false)
                ->assertSee('<span class="warranty-status-badge warranty-status-active">', false)
                ->assertSee('<span class="warranty-status-badge warranty-status-ended">', false)
                ->assertSee(route('aset.garansi.destroy', 1))
                ->assertSee('Yakin ingin menghapus data garansi ini?')
                ->assertSee(route('aset.garansi.create'))
                ->assertDontSee('Tambah Data Garansi')
                ->assertSee('sidebar-active', false);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_warranty_is_active_through_its_end_date(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'nama_aset' => 'Aset Server',
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 1,
            'id_aset' => 1,
            'tanggal_berakhir' => '2026-11-11',
            'status_garansi' => 'Berakhir',
        ]);

        Carbon::setTestNow('2026-11-11 12:00:00');
        try {
            $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
                ->get(route('aset.garansi'))
                ->assertOk()
                ->assertSee('<span class="warranty-status-badge warranty-status-active">', false)
                ->assertDontSee('<span class="warranty-status-badge warranty-status-ended">', false);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_warranty_edit_page_displays_existing_values(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'nama_aset' => 'Aset Server',
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 1,
            'id_aset' => 1,
            'nomor_garansi' => 'GAR-EDIT',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_berakhir' => '2028-01-01',
            'tingkat_layanan' => 'On-site',
            'tingkat_sla' => '24x7',
            'keterangan' => 'Garansi vendor',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.garansi.edit', 1))
            ->assertOk()
            ->assertSee('Edit Data Garansi')
            ->assertSee('Aset Server')
            ->assertSee('GAR-EDIT')
            ->assertSee('value="2025-01-01"', false)
            ->assertSee('value="2028-01-01"', false)
            ->assertSee(route('aset.garansi.update', 1));
    }

    public function test_warranty_can_be_updated_and_status_is_recalculated(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'nama_aset' => 'Aset Server',
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 1,
            'id_aset' => 1,
            'nomor_garansi' => 'GAR-OLD',
            'tanggal_berakhir' => '2028-01-01',
            'status_garansi' => 'Aktif',
        ]);

        Carbon::setTestNow('2026-11-11 12:00:00');
        try {
            $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
                ->put(route('aset.garansi.update', 1), [
                    'id_aset' => 1,
                    'nomor_garansi' => 'GAR-UPDATED',
                    'tanggal_mulai' => '2025-01-01',
                    'tanggal_berakhir' => '2026-11-10',
                    'tingkat_layanan' => 'On-site',
                    'tingkat_sla' => '24x7',
                    'status_garansi' => 'Manual',
                    'keterangan' => 'Data diperbarui',
                ])
                ->assertRedirect(route('aset.garansi'))
                ->assertSessionHas('success', 'Data garansi berhasil diperbarui.');

            $this->assertDatabaseHas('garansi', [
                'id_garansi' => 1,
                'id_aset' => 1,
                'nomor_garansi' => 'GAR-UPDATED',
                'tanggal_berakhir' => '2026-11-10',
                'status_garansi' => 'Berakhir',
                'keterangan' => 'Data diperbarui',
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_warranty_can_be_deleted(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'nama_aset' => 'Aset Server',
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 1,
            'id_aset' => 1,
            'nomor_garansi' => 'GAR-DELETE',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete(route('aset.garansi.destroy', 1))
            ->assertRedirect(route('aset.garansi'))
            ->assertSessionHas('success', 'Data garansi berhasil dihapus.');

        $this->assertDatabaseMissing('garansi', [
            'id_garansi' => 1,
        ]);
    }

    public function test_reassigned_warranty_releases_its_previous_server_for_the_dropdown(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            ['id_aset' => 1, 'nama_aset' => 'Aset Server Pertama'],
            ['id_aset' => 2, 'nama_aset' => 'Aset Server Kedua'],
        ]);
        DB::table('server')->insert([
            ['id_server' => 1, 'id_aset' => 1, 'nama_server' => 'Server Pertama'],
            ['id_server' => 2, 'id_aset' => 2, 'nama_server' => 'Server Kedua'],
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 1,
            'id_aset' => 1,
            'id_server' => 1,
            'nomor_garansi' => 'GAR-OLD',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put(route('aset.garansi.update', 1), [
                'id_aset' => 2,
                'nomor_garansi' => 'GAR-REASSIGNED',
            ])
            ->assertRedirect(route('aset.garansi'));

        $this->assertDatabaseHas('garansi', [
            'id_garansi' => 1,
            'id_aset' => 2,
            'id_server' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.garansi.create'))
            ->assertOk()
            ->assertSee('Aset Server Pertama')
            ->assertDontSee('Aset Server Kedua');
    }

    public function test_warranty_create_page_displays_the_add_form_separately(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            ['id_aset' => 1, 'nama_aset' => 'Aset Server Bergaransi'],
            ['id_aset' => 2, 'nama_aset' => 'Aset Server Tersedia'],
        ]);
        DB::table('server')->insert([
            ['id_server' => 1, 'id_aset' => 1, 'nama_server' => 'Server Bergaransi'],
            ['id_server' => 2, 'id_aset' => 2, 'nama_server' => 'Server Tersedia'],
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 1,
            'id_aset' => 1,
            'id_server' => 1,
            'nomor_garansi' => 'GAR-001',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.garansi.create'))
            ->assertOk()
            ->assertSee('Tambah Data Garansi')
            ->assertSee('Status garansi ditentukan otomatis berdasarkan tanggal berakhir.')
            ->assertDontSee('Status Garansi')
            ->assertDontSee('Aset Server Bergaransi')
            ->assertDontSee('Server Bergaransi')
            ->assertSee('Aset Server Tersedia')
            ->assertDontSee('Aset Server Tersedia (Server Tersedia)')
            ->assertDontSee('Server (opsional)')
            ->assertSee(route('aset.garansi.store'))
            ->assertSee(route('aset.garansi'));
    }

    public function test_warranty_can_be_created_from_the_warranty_page(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'nama_aset' => 'Aset Server Utama',
        ]);
        DB::table('server')->insert([
            'id_server' => 1,
            'id_aset' => 1,
            'nama_server' => 'Server Utama',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->from(route('aset.garansi.create'))
            ->post(route('aset.garansi.store'), [
                'id_aset' => 1,
                'nomor_garansi' => 'GAR-002',
                'tanggal_mulai' => '2025-01-01',
                'tanggal_berakhir' => '2028-01-01',
                'tingkat_layanan' => 'On-site',
                'tingkat_sla' => '24x7',
                'status_garansi' => 'Manual',
                'keterangan' => 'Garansi server utama',
            ])
            ->assertRedirect(route('aset.garansi'))
            ->assertSessionHas('success', 'Data garansi berhasil ditambahkan.');

        $this->assertDatabaseHas('garansi', [
            'id_aset' => 1,
            'nomor_garansi' => 'GAR-002',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_berakhir' => '2028-01-01',
            'tingkat_layanan' => 'On-site',
            'tingkat_sla' => '24x7',
            'status_garansi' => 'Aktif',
            'keterangan' => 'Garansi server utama',
        ]);
    }

    public function test_warranty_cannot_be_added_twice_for_the_same_asset(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'nama_aset' => 'Aset Bergaransi',
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 1,
            'id_aset' => 1,
            'nomor_garansi' => 'GAR-001',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->from(route('aset.garansi.create'))
            ->post(route('aset.garansi.store'), [
                'id_aset' => 1,
                'nomor_garansi' => 'GAR-DUPLICATE',
            ])
            ->assertRedirect(route('aset.garansi.create'))
            ->assertSessionHasErrors('id_aset');

        $this->assertDatabaseCount('garansi', 1);
    }

    public function test_expired_warranty_is_saved_with_ended_status(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'nama_aset' => 'Aset Server',
        ]);

        Carbon::setTestNow('2026-11-11 12:00:00');
        try {
            $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
                ->post(route('aset.garansi.store'), [
                    'id_aset' => 1,
                    'tanggal_berakhir' => '2026-11-10',
                ])
                ->assertRedirect(route('aset.garansi'));

            $this->assertDatabaseHas('garansi', [
                'id_aset' => 1,
                'status_garansi' => 'Berakhir',
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_warranty_cannot_be_added_for_a_server_that_already_has_one(): void
    {
        $this->createWarrantyTables();
        DB::table('aset')->insert([
            ['id_aset' => 1, 'nama_aset' => 'Aset Server Bergaransi'],
            ['id_aset' => 2, 'nama_aset' => 'Aset Tercatat Garansi'],
        ]);
        DB::table('server')->insert([
            'id_server' => 1,
            'id_aset' => 1,
            'nama_server' => 'Server Bergaransi',
        ]);
        DB::table('garansi')->insert([
            'id_garansi' => 1,
            'id_aset' => 2,
            'id_server' => 1,
            'nomor_garansi' => 'GAR-001',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->from(route('aset.garansi.create'))
            ->post(route('aset.garansi.store'), [
                'id_aset' => 1,
                'nomor_garansi' => 'GAR-DUPLICATE',
            ])
            ->assertRedirect(route('aset.garansi.create'))
            ->assertSessionHasErrors('id_aset');

        $this->assertDatabaseCount('garansi', 1);
    }

    public function test_brand_page_displays_active_records(): void
    {
        $this->createBrandTable();
        DB::table('merek')->insert([
            [
                'id_merek' => 1,
                'nama_merek' => 'Dell',
                'keterangan' => 'Perangkat server',
                'deleted_at' => null,
            ],
            [
                'id_merek' => 2,
                'nama_merek' => 'Merek Terhapus',
                'keterangan' => null,
                'deleted_at' => now(),
            ],
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.merek'))
            ->assertOk()
            ->assertSee('Dell')
            ->assertSee('Perangkat server')
            ->assertDontSee('Merek Terhapus')
            ->assertSee(route('aset.merek.edit', 1))
            ->assertSee(route('aset.merek.destroy', 1))
            ->assertSee('Edit')
            ->assertSee('Hapus')
            ->assertSee(asset('css/merek.css'))
            ->assertDontSee(asset('css/aset-location.css'))
            ->assertSee(route('aset.merek'))
            ->assertSee('sidebar-active', false);
    }

    public function test_brand_can_be_edited(): void
    {
        $this->createBrandTable();
        DB::table('merek')->insert([
            'id_merek' => 1,
            'nama_merek' => 'Nama Lama',
            'keterangan' => 'Keterangan lama',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.merek.edit', 1))
            ->assertOk()
            ->assertSee('Edit Merek')
            ->assertSee('value="Nama Lama"', false)
            ->assertSee('Keterangan lama')
            ->assertSee(route('aset.merek.update', 1))
            ->assertSee(asset('css/merek.css'));

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put(route('aset.merek.update', 1), [
                'nama_merek' => 'Nama Baru',
                'keterangan' => 'Keterangan baru',
            ])
            ->assertRedirect(route('aset.merek'))
            ->assertSessionHas('success', 'Data merek berhasil diperbarui.');

        $this->assertDatabaseHas('merek', [
            'id_merek' => 1,
            'nama_merek' => 'Nama Baru',
            'keterangan' => 'Keterangan baru',
            'deleted_at' => null,
        ]);
    }

    public function test_brand_can_be_soft_deleted(): void
    {
        $this->createBrandTable();
        DB::table('merek')->insert([
            'id_merek' => 1,
            'nama_merek' => 'Merek untuk Dihapus',
            'keterangan' => null,
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete(route('aset.merek.destroy', 1))
            ->assertRedirect(route('aset.merek'))
            ->assertSessionHas('success', 'Data merek berhasil dihapus.');

        $this->assertDatabaseCount('merek', 1);
        $this->assertDatabaseMissing('merek', [
            'id_merek' => 1,
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.merek'))
            ->assertOk()
            ->assertDontSee('Merek untuk Dihapus')
            ->assertSee('Belum ada data merek.');

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.merek.edit', 1))
            ->assertNotFound();
    }

    public function test_brand_can_be_created(): void
    {
        $this->createBrandTable();

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.merek.create'))
            ->assertOk()
            ->assertSee('Tambah Merek')
            ->assertSee(route('aset.merek.store'))
            ->assertSee(asset('css/merek.css'));

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->post(route('aset.merek.store'), [
                'nama_merek' => 'Lenovo',
                'keterangan' => 'Perangkat server dan jaringan',
            ])
            ->assertRedirect(route('aset.merek'))
            ->assertSessionHas('success', 'Data merek berhasil ditambahkan.');

        $this->assertDatabaseHas('merek', [
            'nama_merek' => 'Lenovo',
            'keterangan' => 'Perangkat server dan jaringan',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.merek'))
            ->assertOk()
            ->assertSee('Lenovo')
            ->assertSee('Data merek berhasil ditambahkan.');
    }

    public function test_brand_name_is_required(): void
    {
        $this->createBrandTable();

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->from(route('aset.merek.create'))
            ->post(route('aset.merek.store'), [
                'nama_merek' => '',
                'keterangan' => 'Tanpa nama',
            ])
            ->assertRedirect(route('aset.merek.create'))
            ->assertSessionHasErrors('nama_merek');

        $this->assertDatabaseCount('merek', 0);
    }

    public function test_provider_page_displays_records_from_the_provider_table(): void
    {
        $this->createProviderTable();
        DB::table('penyedia')->insert([
            'id_penyedia' => 1,
            'nama_penyedia' => 'PT Penyedia Utama',
            'alamat' => 'Jl. Merdeka No. 10',
            'nomor_telepon' => '021123456',
            'alamat_email' => 'kontak@penyedia.test',
            'nama_kontak' => 'Budi Santoso',
            'keterangan' => 'Vendor perangkat server',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.penyedia'))
            ->assertOk()
            ->assertSee('PT Penyedia Utama')
            ->assertSee('Jl. Merdeka No. 10')
            ->assertSee('021123456')
            ->assertSee('kontak@penyedia.test')
            ->assertSee('Budi Santoso')
            ->assertSee('Vendor perangkat server')
            ->assertSee('Nama PIC')
            ->assertSee(route('aset.penyedia.edit', 1))
            ->assertSee(route('aset.penyedia.destroy', 1))
            ->assertSee('Edit')
            ->assertSee('Hapus')
            ->assertSee(route('aset.penyedia'))
            ->assertSee('sidebar-active', false);
    }

    public function test_provider_can_be_soft_deleted(): void
    {
        $this->createProviderTable();
        DB::table('penyedia')->insert([
            'id_penyedia' => 1,
            'nama_penyedia' => 'PT Penyedia untuk Dihapus',
            'alamat_email' => 'hapus@example.test',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete(route('aset.penyedia.destroy', 1))
            ->assertRedirect(route('aset.penyedia'))
            ->assertSessionHas('success', 'Data penyedia berhasil dihapus.');

        $this->assertDatabaseCount('penyedia', 1);
        $this->assertDatabaseMissing('penyedia', [
            'id_penyedia' => 1,
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.penyedia'))
            ->assertOk()
            ->assertDontSee('PT Penyedia untuk Dihapus')
            ->assertSee('Belum ada data penyedia.');

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.penyedia.edit', 1))
            ->assertNotFound();
    }

    public function test_provider_can_be_edited(): void
    {
        $this->createProviderTable();
        DB::table('penyedia')->insert([
            'id_penyedia' => 1,
            'nama_penyedia' => 'PT Penyedia Lama',
            'alamat' => 'Alamat Lama',
            'nomor_telepon' => '021111111',
            'alamat_email' => 'lama@example.test',
            'nama_kontak' => 'Kontak Lama',
            'keterangan' => 'Catatan lama',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.penyedia.edit', 1))
            ->assertOk()
            ->assertSee('Edit Penyedia')
            ->assertSee('value="PT Penyedia Lama"', false)
            ->assertSee('value="lama@example.test"', false)
            ->assertSee('Alamat Lama')
            ->assertSee(route('aset.penyedia.update', 1));

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put(route('aset.penyedia.update', 1), [
                'nama_penyedia' => 'PT Penyedia Baru',
                'alamat' => 'Alamat Baru',
                'nomor_telepon' => '021222222',
                'alamat_email' => 'baru@example.test',
                'nama_kontak' => 'Kontak Baru',
                'keterangan' => 'Catatan baru',
            ])
            ->assertRedirect(route('aset.penyedia'))
            ->assertSessionHas('success', 'Data penyedia berhasil diperbarui.');

        $this->assertDatabaseHas('penyedia', [
            'id_penyedia' => 1,
            'nama_penyedia' => 'PT Penyedia Baru',
            'alamat' => 'Alamat Baru',
            'nomor_telepon' => '021222222',
            'alamat_email' => 'baru@example.test',
            'nama_kontak' => 'Kontak Baru',
            'keterangan' => 'Catatan baru',
        ]);
    }

    public function test_provider_can_be_created(): void
    {
        $this->createProviderTable();

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.penyedia.create'))
            ->assertOk()
            ->assertSee('Tambah Penyedia')
            ->assertSee(route('aset.penyedia.store'));

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->post(route('aset.penyedia.store'), [
                'nama_penyedia' => 'PT Teknologi Nusantara',
                'alamat' => 'Jl. Merdeka No. 20',
                'nomor_telepon' => '021987654',
                'alamat_email' => 'vendor@example.test',
                'nama_kontak' => 'Siti Aminah',
                'keterangan' => 'Penyedia perangkat jaringan',
            ])
            ->assertRedirect(route('aset.penyedia'))
            ->assertSessionHas('success', 'Data penyedia berhasil ditambahkan.');

        $this->assertDatabaseHas('penyedia', [
            'nama_penyedia' => 'PT Teknologi Nusantara',
            'alamat' => 'Jl. Merdeka No. 20',
            'nomor_telepon' => '021987654',
            'alamat_email' => 'vendor@example.test',
            'nama_kontak' => 'Siti Aminah',
            'keterangan' => 'Penyedia perangkat jaringan',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.penyedia'))
            ->assertOk()
            ->assertSee('PT Teknologi Nusantara')
            ->assertSee('Data penyedia berhasil ditambahkan.');
    }

    public function test_provider_requires_a_name_and_valid_email(): void
    {
        $this->createProviderTable();

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->from(route('aset.penyedia.create'))
            ->post(route('aset.penyedia.store'), [
                'nama_penyedia' => '',
                'alamat_email' => 'bukan-email',
            ])
            ->assertRedirect(route('aset.penyedia.create'))
            ->assertSessionHasErrors(['nama_penyedia', 'alamat_email']);

        $this->assertDatabaseCount('penyedia', 0);
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
            ->assertSee('45,00%')
            ->assertSee('10.000')
            ->assertSee('4.500')
            ->assertSee('5.500')
            ->assertSee(route('aset.storage.detail', 1))
            ->assertSee('Detail')
            ->assertDontSee('RAID 6')
            ->assertDontSee('Fibre Channel')
            ->assertDontSee('192.0.2.20')
            ->assertDontSee('Storage Arsip')
            ->assertSee('Storage', false);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage.detail', 1))
            ->assertOk()
            ->assertSee('Detail Storage')
            ->assertSee('Storage Utama')
            ->assertSee('RAID 6')
            ->assertSee('Fibre Channel')
            ->assertSee('192.0.2.20')
            ->assertSee('5.500')
            ->assertSee('45,00%')
            ->assertSee('Kapasitas Snapshot')
            ->assertSee(route('aset.storage.edit', 1))
            ->assertSee('Edit Storage')
            ->assertSee(route('aset.storage'));
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

    public function test_storage_can_be_soft_deleted_from_the_storage_page(): void
    {
        $this->createStorageTables();
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
            'kapasitas_terpakai' => 4500,
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage'))
            ->assertOk()
            ->assertSee(route('aset.storage.destroy', 1))
            ->assertSee('Hapus');

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage.detail', 1))
            ->assertOk()
            ->assertSee(route('aset.storage.destroy', 1))
            ->assertSee('Hapus Storage');

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete(route('aset.storage.destroy', 1))
            ->assertRedirect(route('aset.storage'))
            ->assertSessionHas('success', 'Data storage berhasil dihapus.');

        $this->assertDatabaseCount('storage', 1);
        $this->assertDatabaseMissing('storage', [
            'id_storage' => 1,
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage'))
            ->assertOk()
            ->assertDontSee('Storage Utama')
            ->assertSee('Belum ada data storage.');

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage.detail', 1))
            ->assertNotFound();
    }

    public function test_storage_can_be_created_with_automatically_calculated_capacity(): void
    {
        $this->createStorageTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'kode_aset' => 'AST-001',
            'nama_aset' => 'Storage Utama',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage.create'))
            ->assertOk()
            ->assertSee('Tambah Storage')
            ->assertSee('AST-001 - Storage Utama')
            ->assertSee('Kapasitas tersedia dan persentase penggunaan dihitung otomatis');

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->post(route('aset.storage.store'), [
                'id_aset' => 1,
                'jenis_storage' => 'SAN',
                'kapasitas_raw' => 10000,
                'kapasitas_terpakai' => 4500,
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
            ])
            ->assertRedirect(route('aset.storage.detail', 1))
            ->assertSessionHas('success', 'Data storage berhasil ditambahkan.');

        $this->assertDatabaseHas('storage', [
            'id_storage' => 1,
            'id_aset' => 1,
            'kapasitas_raw' => 10000,
            'kapasitas_terpakai' => 4500,
            'jenis_storage' => 'SAN',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage.detail', 1))
            ->assertOk()
            ->assertSee('5.500')
            ->assertSee('45,00%');
    }

    public function test_storage_cannot_be_created_if_used_capacity_exceeds_raw_capacity(): void
    {
        $this->createStorageTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'kode_aset' => 'AST-001',
            'nama_aset' => 'Storage Utama',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->from(route('aset.storage.create'))
            ->post(route('aset.storage.store'), [
                'id_aset' => 1,
                'kapasitas_raw' => 100,
                'kapasitas_terpakai' => 101,
            ])
            ->assertRedirect(route('aset.storage.create'))
            ->assertSessionHasErrors('kapasitas_terpakai');

        $this->assertDatabaseCount('storage', 0);
    }

    public function test_storage_can_be_edited_and_capacities_are_recalculated(): void
    {
        $this->createStorageTables();
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
            'kapasitas_terpakai' => 4500,
            'jenis_disk' => 'SSD',
            'tingkat_raid' => 'RAID 6',
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage.edit', 1))
            ->assertOk()
            ->assertSee('Edit Storage')
            ->assertSee('value="10000"', false)
            ->assertSee('value="4500"', false)
            ->assertSee('value="RAID 6"', false);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put(route('aset.storage.update', 1), [
                'id_aset' => 1,
                'jenis_storage' => 'NAS',
                'kapasitas_raw' => 12000,
                'kapasitas_terpakai' => 7000,
                'jenis_disk' => 'HDD',
                'jumlah_disk' => 12,
                'tingkat_raid' => 'RAID 5',
                'jumlah_pengendali' => 1,
                'protokol_storage' => 'iSCSI',
                'jumlah_volume' => 4,
                'jumlah_lun' => 8,
                'kapasitas_snapshot' => 500,
                'status_replikasi' => 'Aktif',
                'alamat_ip_manajemen' => '192.0.2.21',
            ])
            ->assertRedirect(route('aset.storage.detail', 1))
            ->assertSessionHas('success', 'Data storage berhasil diperbarui.');

        $this->assertDatabaseHas('storage', [
            'id_storage' => 1,
            'jenis_storage' => 'NAS',
            'kapasitas_raw' => 12000,
            'kapasitas_terpakai' => 7000,
            'jenis_disk' => 'HDD',
            'tingkat_raid' => 'RAID 5',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage.detail', 1))
            ->assertOk()
            ->assertSee('5.000')
            ->assertSee('58,33%')
            ->assertSee('RAID 5');
    }

    public function test_storage_update_rejects_used_capacity_above_raw_capacity(): void
    {
        $this->createStorageTables();
        DB::table('aset')->insert([
            'id_aset' => 1,
            'kode_aset' => 'AST-001',
            'nama_aset' => 'Storage Utama',
        ]);
        DB::table('storage')->insert([
            'id_storage' => 1,
            'id_aset' => 1,
            'kapasitas_raw' => 100,
            'kapasitas_terpakai' => 50,
            'deleted_at' => null,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->from(route('aset.storage.edit', 1))
            ->put(route('aset.storage.update', 1), [
                'id_aset' => 1,
                'kapasitas_raw' => 100,
                'kapasitas_terpakai' => 101,
            ])
            ->assertRedirect(route('aset.storage.edit', 1))
            ->assertSessionHasErrors('kapasitas_terpakai');

        $this->assertDatabaseHas('storage', [
            'id_storage' => 1,
            'kapasitas_raw' => 100,
            'kapasitas_terpakai' => 50,
        ]);
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

    private function createProviderTable(): void
    {
        Schema::create('penyedia', function (Blueprint $table) {
            $table->increments('id_penyedia');
            $table->string('nama_penyedia');
            $table->text('alamat')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('alamat_email')->nullable();
            $table->string('nama_kontak')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    private function createBrandTable(): void
    {
        Schema::create('merek', function (Blueprint $table) {
            $table->increments('id_merek');
            $table->string('nama_merek');
            $table->text('keterangan')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    private function createWarrantyTables(): void
    {
        Schema::create('garansi', function (Blueprint $table) {
            $table->increments('id_garansi');
            $table->unsignedInteger('id_aset');
            $table->string('nomor_garansi')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->string('tingkat_layanan')->nullable();
            $table->string('tingkat_sla')->nullable();
            $table->string('status_garansi')->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedInteger('id_server')->nullable();
        });
        Schema::create('aset', function (Blueprint $table) {
            $table->increments('id_aset');
            $table->string('nama_aset');
        });
        Schema::create('server', function (Blueprint $table) {
            $table->increments('id_server');
            $table->unsignedInteger('id_aset');
            $table->string('nama_server');
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