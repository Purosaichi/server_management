<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        return User::query()->create([
            'nama_pengguna' => 'Administrator',
            'email' => 'admin@kemendik.go.id',
            'kata_sandi' => 'password',
            'status_pengguna' => 'Aktif',
        ]);
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk ke dashboard monitoring');
    }

    public function test_login_reads_users_from_database(): void
    {
        $this->createUser();

        $this->from('/login')
            ->post('/login', [
                'email' => 'admin@kemendik.go.id',
                'password' => 'password',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('user_login', 'admin@kemendik.go.id');
    }

    public function test_logout_accepts_post_method(): void
    {
        $this->createUser();

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

    public function test_application_page_renders_with_application_data(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/application')
            ->assertOk()
            ->assertSee('GTK - Guru');
    }

    public function test_login_rejects_wrong_password(): void
    {
        $this->createUser();

        $this->from('/login')
            ->post('/login', [
                'email' => 'admin@kemendik.go.id',
                'password' => 'salah',
            ])
            ->assertRedirect('/login')
            ->assertSessionHas('error');
    }

    public function test_license_detail_page_shows_application_logo(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/licenses/1')
            ->assertOk()
            ->assertSee('images/SWL_server.png', false);
    }
}
