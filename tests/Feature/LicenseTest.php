<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LicenseTest extends TestCase
{
    public function test_license_store_saves_details_billing_and_uploaded_logo(): void
    {
        $this->createLicenseTables();

        try {
            $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
                ->post('/licenses', [
                    'nama_lisensi' => 'Microsoft SQL Server',
                    'provider' => 'Microsoft',
                    'status' => 'Akan Expired',
                    'jumlah_lisensi' => 2,
                    'tanggal_mulai' => '2026-01-01',
                    'tanggal_kadaluarsa' => '2026-09-20',
                    'nomor_lisensi' => 'LIC-MSSQL001',
                    'jenis_lisensi' => 'Subscription',
                    'billing_status' => 'Paid',
                    'billing_period' => '1 Tahun',
                    'last_payment' => '2026-08-15',
                    'next_due_date' => '2026-09-20',
                    'amount' => 250000,
                    'payment_method' => 'Transfer bank',
                    'logo' => UploadedFile::fake()->image('license.png'),
                ])
                ->assertRedirect(route('licenses.detail', 1));

            $this->assertDatabaseHas('lisensi', [
                'id_lisensi' => 1,
                'nama_lisensi' => 'Microsoft SQL Server',
                'nomor_lisensi' => 'LIC-MSSQL001',
                'jumlah_lisensi' => 2,
            ]);
            $this->assertDatabaseHas('tagihan', [
                'id_lisensi' => 1,
                'jenis_tagihan' => 'Lisensi',
                'status_pembayaran' => 'Paid',
                'jumlah' => 250000,
            ]);

            $logo = DB::table('lisensi')->where('id_lisensi', 1)->value('logo');
            $this->assertStringStartsWith('licenses/', $logo);
            $this->assertFileExists(public_path('images/'.$logo));
        } finally {
            $logo = DB::table('lisensi')->where('id_lisensi', 1)->value('logo');
            if ($logo) {
                File::delete(public_path('images/'.$logo));
            }
        }
    }

    public function test_license_delete_sets_deleted_at_without_removing_record(): void
    {
        $this->createLicenseTables();
        DB::table('lisensi')->insert([
            'nama_lisensi' => 'Microsoft SQL Server',
            'provider' => 'Microsoft',
            'status' => 'Aman',
            'jumlah_lisensi' => 2,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete('/licenses/1')
            ->assertRedirect(route('licenses.licenses'));

        $this->assertSame(1, DB::table('lisensi')->where('id_lisensi', 1)->count());
        $this->assertNotNull(DB::table('lisensi')->where('id_lisensi', 1)->value('deleted_at'));
    }

    public function test_license_expiry_note_shows_whole_days(): void
    {
        $this->createLicenseTables();
        DB::table('lisensi')->insert([
            'nama_lisensi' => 'Microsoft SQL Server',
            'provider' => 'Microsoft',
            'status' => 'Aman',
            'jumlah_lisensi' => 2,
            'tanggal_kadaluarsa' => '2027-01-01',
        ]);
        Carbon::setTestNow('2026-10-20 08:00:00');

        try {
            $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
                ->get('/licenses')
                ->assertOk()
                ->assertSee('(73 Hari Lagi)');
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_red_hat_license_uses_the_valid_png_logo(): void
    {
        $this->createLicenseTables();
        DB::table('lisensi')->insert([
            'nama_lisensi' => 'Red Hat Enterprise Linux',
            'provider' => 'Red Hat',
            'status' => 'Aman',
            'jumlah_lisensi' => 1,
            'logo' => 'redhat.png',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/licenses')
            ->assertOk()
            ->assertSee('images/Red-Hat.png');
    }

    private function createLicenseTables(): void
    {
        Schema::create('lisensi', function (Blueprint $table) {
            $table->increments('id_lisensi');
            $table->string('nama_lisensi');
            $table->string('provider');
            $table->string('status');
            $table->unsignedInteger('jumlah_lisensi');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->string('nomor_lisensi')->nullable();
            $table->string('jenis_lisensi')->nullable();
            $table->string('logo')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('tagihan', function (Blueprint $table) {
            $table->increments('id_tagihan');
            $table->string('jenis_tagihan');
            $table->unsignedInteger('id_domain')->nullable();
            $table->unsignedInteger('id_lisensi')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->string('siklus_tagihan')->nullable();
            $table->date('tanggal_bayar_terakhir')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->decimal('jumlah', 12, 2)->nullable();
            $table->string('metode_pembayaran')->nullable();
        });

        Schema::create('aset', function (Blueprint $table) {
            $table->increments('id_aset');
            $table->string('nama_aset');
        });

        Schema::create('lisensi_aset', function (Blueprint $table) {
            $table->increments('id_lisensi_aset');
            $table->unsignedInteger('id_lisensi');
            $table->unsignedInteger('id_aset');
            $table->date('tanggal_pemasangan')->nullable();
        });
    }
}