<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private function createPengguna(): Pengguna
    {
        return Pengguna::query()->create([
            'nama_pengguna' => 'Administrator',
            'nama_login' => 'admin@kemendik.go.id',
            'kata_sandi' => 'password',
            'status_pengguna' => 'Aktif',
        ]);
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk ke dashboard monitoring');
    }

    public function test_login_reads_pengguna_from_database(): void
    {
        $this->createPengguna();

        $this->from('/login')
            ->post('/login', [
                'email' => 'admin@kemendik.go.id',
                'password' => 'password',
            ])
            ->assertRedirect(route('server.index'))
            ->assertSessionHas('user_login', 'admin@kemendik.go.id');
    }

    public function test_logout_accepts_post_method(): void
    {
        $this->createPengguna();

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->post('/logout')
            ->assertRedirect('/login');
    }

    public function test_server_page_renders_with_dashboard_data(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/server')
            ->assertOk()
            ->assertSee('Total Server');
    }

    public function test_login_rejects_wrong_password(): void
    {
        $this->createPengguna();

        $this->from('/login')
            ->post('/login', [
                'email' => 'admin@kemendik.go.id',
                'password' => 'salah',
            ])
            ->assertRedirect('/login')
            ->assertSessionHas('error');
    }
}
