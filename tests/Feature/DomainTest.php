<?php

namespace Tests\Feature;

use App\Http\Controllers\DomainController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class DomainTest extends TestCase
{
    public function test_domain_page_renders(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/domain')
            ->assertOk()
            ->assertSee('Daftar Domain');
    }

    public function test_domain_list_can_search_by_name_and_filter_by_status(): void
    {
        Schema::create('domain', function (Blueprint $table) {
            $table->increments('id_domain');
            $table->string('nama_domain');
            $table->string('status');
            $table->timestamp('deleted_at')->nullable();
        });
        DB::table('domain')->insert([
            ['nama_domain' => 'portal.example', 'status' => 'Aktif', 'deleted_at' => null],
            ['nama_domain' => 'archive.example', 'status' => 'Tidak Aktif', 'deleted_at' => null],
            ['nama_domain' => 'deleted.example', 'status' => 'Aktif', 'deleted_at' => now()],
        ]);
        DB::statement('CREATE VIEW vw_domain_detail AS SELECT id_domain, nama_domain, status AS status_domain, NULL AS nama_aplikasi, NULL AS nama_pic, NULL AS ssl_status, NULL AS tanggal_kadaluarsa FROM domain');

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/domain?search=example&status=Aktif')
            ->assertOk()
            ->assertSee('portal.example')
            ->assertDontSee('archive.example')
            ->assertDontSee('deleted.example');
    }

    public function test_domain_row_has_detail_url_for_double_click(): void
    {
        $html = view('pages.domain.domain', [
            'search' => '',
            'status' => '',
            'domains' => [
                [
                    'id' => 42,
                    'domain' => 'example.com',
                    'application' => 'SIMA',
                    'status' => 'Aktif',
                    'pic' => 'Budi',
                    'ssl' => 'Aktif',
                    'last_activity' => '12 Sep 2026',
                ],
            ],
        ])->render();

        $this->assertStringContainsString('data-detail-url="'.route('domain.detail', 42).'"', $html);
        $this->assertStringNotContainsString('data-detail-url="'.route('domain.detail', 1).'"', $html);
        $this->assertStringNotContainsString('Selengkapnya', $html);
    }

    public function test_domain_list_has_functional_edit_link_and_inert_delete_button(): void
    {
        $html = view('pages.domain.domain', [
            'search' => '',
            'status' => '',
            'domains' => [
                [
                    'id' => 42,
                    'domain' => 'example.com',
                    'application' => 'SIMA',
                    'status' => 'Aktif',
                    'pic' => 'Budi',
                    'ssl' => 'Valid',
                    'last_activity' => '12 Sep 2026',
                ],
            ],
        ])->render();

        $this->assertStringContainsString('href="'.route('domain.edit', 42).'"', $html);
        $this->assertStringContainsString('class="dmn-btn-delete"', $html);
        $this->assertStringContainsString('data-delete-url="'.route('domain.destroy', 42).'"', $html);
        $this->assertStringContainsString('Konfirmasi Hapus', $html);
        $this->assertStringContainsString('Ya, Hapus', $html);
        $this->assertStringContainsString('name="_method" value="DELETE"', $html);
    }

    public function test_destroy_soft_deletes_domain_without_removing_row(): void
    {
        Schema::create('domain', function (Blueprint $table) {
            $table->increments('id_domain');
            $table->string('nama_domain');
            $table->string('status');
            $table->timestamp('deleted_at')->nullable();
        });
        DB::table('domain')->insert([
            'id_domain' => 42,
            'nama_domain' => 'example.com',
            'status' => 'Aktif',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete('/domain/42')
            ->assertRedirect(route('domain.domain'));

        $this->assertSame(1, DB::table('domain')->where('id_domain', 42)->count());
        $this->assertNotNull(DB::table('domain')->where('id_domain', 42)->value('deleted_at'));
    }

    public function test_create_domain_form_includes_domain_detail_fields(): void
    {
        $html = view('pages.domain.create', [
            'errors' => new MessageBag,
            'pics' => collect(),
        ])->render();

        $this->assertStringNotContainsString('name="id_aplikasi"', $html);
        $this->assertStringContainsString('class="app-form-group app-checkbox-group"', $html);
        $this->assertStringContainsString('SSL Certificate', $html);
        $this->assertStringContainsString('Billing Information', $html);

        foreach ([
            'ssl_provider',
            'ssl_status',
            'tanggal_ssl_terbit',
            'ssl_tanggal_kadaluarsa',
            'ssl_tipe',
            'billing_status',
            'billing_cycle',
            'last_payment',
            'next_due_date',
            'amount',
            'payment_method',
        ] as $field) {
            $this->assertStringContainsString('name="'.$field.'"', $html);
        }
    }

    public function test_storing_domain_saves_ssl_and_billing_to_their_tables(): void
    {
        Schema::create('domain', function (Blueprint $table) {
            $table->increments('id_domain');
            $table->string('nama_domain')->unique();
            $table->string('status');
            $table->string('registrar')->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->boolean('perpanjangan_otomatis')->default(false);
            $table->unsignedInteger('id_pic')->nullable();
        });
        Schema::create('ssl_certificate', function (Blueprint $table) {
            $table->increments('id_ssl');
            $table->unsignedInteger('id_domain');
            $table->string('provider')->nullable();
            $table->string('status')->default('Valid');
            $table->string('tipe_sertifikat')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
        });
        Schema::create('tagihan', function (Blueprint $table) {
            $table->increments('id_tagihan');
            $table->string('jenis_tagihan');
            $table->unsignedInteger('id_domain')->nullable();
            $table->unsignedInteger('id_lisensi')->nullable();
            $table->string('status_pembayaran')->default('Unpaid');
            $table->string('siklus_tagihan')->nullable();
            $table->date('tanggal_bayar_terakhir')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->decimal('jumlah', 12, 2)->nullable();
            $table->string('metode_pembayaran')->nullable();
        });

        app(DomainController::class)->store(Request::create('/domain', 'POST', [
            'nama_domain' => 'regression-test.example',
            'status' => 'Aktif',
            'ssl_provider' => 'Test SSL',
            'ssl_status' => 'Valid',
            'tanggal_ssl_terbit' => '2026-10-19',
            'ssl_tanggal_kadaluarsa' => '2027-10-19',
            'ssl_tipe' => 'DV',
            'billing_status' => 'Paid',
            'billing_cycle' => '1 tahun',
            'last_payment' => '2026-10-19',
            'next_due_date' => '2027-10-19',
            'amount' => '250000',
            'payment_method' => 'Transfer bank',
        ]));

        $domainId = DB::table('domain')->where('nama_domain', 'regression-test.example')->value('id_domain');

        $this->assertDatabaseHas('ssl_certificate', [
            'id_domain' => $domainId,
            'provider' => 'Test SSL',
            'status' => 'Valid',
            'tipe_sertifikat' => 'DV',
        ]);
        $this->assertDatabaseHas('tagihan', [
            'id_domain' => $domainId,
            'jenis_tagihan' => 'Domain',
            'status_pembayaran' => 'Paid',
            'jumlah' => 250000,
            'metode_pembayaran' => 'Transfer bank',
        ]);
    }
}
