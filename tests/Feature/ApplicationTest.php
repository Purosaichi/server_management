<?php

namespace Tests\Feature;

use App\Http\Controllers\ApplicationController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    protected function tearDown(): void
    {
        Schema::dropIfExists('vw_aplikasi_detail');

        parent::tearDown();
    }

    public function test_create_application_form_lists_available_domains(): void
    {
        $html = view('pages.application.create', [
            'errors' => new MessageBag(),
            'servers' => collect(),
            'licenses' => collect(),
            'domains' => collect([(object) [
                'id_domain' => 42,
                'nama_domain' => 'example.com',
            ]]),
        ])->render();

        $this->assertStringContainsString('name="id_domain"', $html);
        $this->assertStringContainsString('<option value="42"', $html);
        $this->assertStringContainsString('example.com', $html);
        $this->assertStringNotContainsString('name="nama_domain"', $html);
    }

    public function test_application_index_filters_by_name_and_status_together(): void
    {
        Schema::create('vw_aplikasi_detail', function (Blueprint $table): void {
            $table->unsignedInteger('id_aplikasi')->primary();
            $table->string('nama_aplikasi');
            $table->string('nama_server')->nullable();
            $table->string('status_aplikasi');
            $table->string('nama_domain')->nullable();
            $table->string('license_name')->nullable();
            $table->string('next_maintenance')->nullable();
        });

        DB::table('vw_aplikasi_detail')->insert([
            [
                'id_aplikasi' => 1,
                'nama_aplikasi' => 'Portal Guru',
                'status_aplikasi' => 'Down',
            ],
            [
                'id_aplikasi' => 2,
                'nama_aplikasi' => 'Portal Guru Baru',
                'status_aplikasi' => 'Aktif',
            ],
            [
                'id_aplikasi' => 3,
                'nama_aplikasi' => 'Sistem Pendidikan',
                'status_aplikasi' => 'Down',
            ],
        ]);

        $response = app(ApplicationController::class)->index(
            Request::create('/application?search=Portal&status=Down', 'GET')
        );

        $this->assertSame('Portal', $response->getData()['search']);
        $this->assertSame('Down', $response->getData()['status']);
        $this->assertSame([1], array_column($response->getData()['applications'], 'id'));
        $this->assertEquals(3, $response->getData()['applicationStats']->total);
        $this->assertEquals(1, $response->getData()['applicationStats']->active);
        $this->assertEquals(2, $response->getData()['applicationStats']->down);

        $html = $response->render();
        $this->assertStringContainsString('Total Aplikasi', $html);
        $this->assertStringContainsString('Aplikasi Aktif', $html);
        $this->assertStringContainsString('Aplikasi Down', $html);
        $this->assertStringContainsString('name="search"', $html);
        $this->assertStringContainsString('value="Portal"', $html);
        $this->assertStringContainsString('name="status"', $html);
        $this->assertStringContainsString("addEventListener('input'", $html);
        $this->assertStringContainsString('requestSubmit()', $html);
        $this->assertStringNotContainsString('class="app-filter-submit"', $html);
    }
}