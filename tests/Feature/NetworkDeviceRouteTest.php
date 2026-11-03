<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NetworkDeviceRouteTest extends TestCase
{
    public function test_network_device_routes_are_registered(): void
    {
        $this->assertSame('/network-device/router', route('network-device.router', [], false));
        $this->assertSame('/network-device/switch', route('network-device.switch', [], false));
        $this->assertSame('/network-device/router/create', route('network-device.router-create', [], false));
        $this->assertSame('/network-device/switch/create', route('network-device.switch-create', [], false));
    }

    public function test_router_rows_open_their_detail_on_double_click(): void
    {
        $html = view('pages.network-device.router', [
            'routers' => collect([(object) [
                'id_router' => 42,
                'jenis_router' => 'Core',
                'kapasitas_bandwidth' => 1000,
                'protokol_routing' => 'OSPF',
                'jumlah_tunnel_vpn' => 2,
                'status_vpn' => 'Aktif',
                'status_nat' => 'Aktif',
                'status_ha' => 'Aktif',
                'alamat_ip_manajemen' => '192.0.2.1',
            ]]),
        ])->render();

        $this->assertStringContainsString('data-detail-url="'.route('network-device.router-detail', 42).'"', $html);
        $this->assertStringContainsString('class="nd-device-row"', $html);
        $this->assertStringContainsString('href="'.route('network-device.router-edit', 42).'"', $html);
        $this->assertStringContainsString('data-delete-url="'.route('network-device.router-destroy', 42).'"', $html);
        $this->assertStringNotContainsString('Selengkapnya', $html);
        $this->assertStringNotContainsString('nd-btn-detail', $html);
    }

    public function test_switch_rows_open_their_detail_on_double_click(): void
    {
        $html = view('pages.network-device.switch', [
            'switches' => collect([(object) [
                'id_switch' => 24,
                'jenis_switch' => 'Core',
                'lapisan_jaringan' => 'Layer 3',
                'jumlah_port' => 48,
                'jumlah_port_terpakai' => 20,
                'jumlah_port_tersedia' => 28,
                'kecepatan_port' => '1 Gbps',
                'jumlah_port_uplink' => 4,
                'status_stack' => 'Aktif',
                'jumlah_vlan' => 10,
                'alamat_ip_manajemen' => '192.0.2.2',
                'alamat_mac' => '00:11:22:33:44:55',
            ]]),
        ])->render();

        $this->assertStringContainsString('data-detail-url="'.route('network-device.switch-detail', 24).'"', $html);
        $this->assertStringContainsString('class="nd-device-row"', $html);
        $this->assertStringContainsString('href="'.route('network-device.switch-edit', 24).'"', $html);
        $this->assertStringContainsString('data-delete-url="'.route('network-device.switch-destroy', 24).'"', $html);
        $this->assertStringNotContainsString('Detail →', $html);
        $this->assertStringNotContainsString('nd-btn-detail', $html);
    }

    public function test_router_can_be_created(): void
    {
        Schema::create('router', function (Blueprint $table) {
            $table->increments('id_router');
            $table->string('jenis_router');
            $table->decimal('kapasitas_bandwidth')->nullable();
            $table->string('protokol_routing')->nullable();
            $table->integer('jumlah_tunnel_vpn')->nullable();
            $table->string('status_vpn')->nullable();
            $table->string('status_nat')->nullable();
            $table->string('status_ha')->nullable();
            $table->string('alamat_ip_manajemen')->nullable();
            $table->integer('id_aset')->nullable();
        });

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/network-device/router/create')
            ->assertOk()
            ->assertSee('Tambah Router Baru')
            ->assertSee('name="jenis_router"', false);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->post('/network-device/router', [
                'jenis_router' => 'Core',
                'kapasitas_bandwidth' => 1000,
                'protokol_routing' => 'OSPF',
                'jumlah_tunnel_vpn' => 2,
                'status_vpn' => 'Aktif',
                'status_nat' => 'Aktif',
                'status_ha' => 'Aktif',
                'alamat_ip_manajemen' => '192.0.2.10',
            ])
            ->assertRedirect(route('network-device.router'));

        $this->assertDatabaseHas('router', [
            'jenis_router' => 'Core',
            'kapasitas_bandwidth' => 1000,
            'alamat_ip_manajemen' => '192.0.2.10',
        ]);
    }

    public function test_switch_can_be_created(): void
    {
        Schema::create('switch', function (Blueprint $table) {
            $table->increments('id_switch');
            $table->string('jenis_switch');
            $table->string('lapisan_jaringan')->nullable();
            $table->integer('jumlah_port')->nullable();
            $table->integer('jumlah_port_terpakai')->nullable();
            $table->integer('jumlah_port_tersedia')->nullable();
            $table->string('kecepatan_port')->nullable();
            $table->integer('jumlah_port_uplink')->nullable();
            $table->string('status_stack')->nullable();
            $table->integer('jumlah_anggota_stack')->nullable();
            $table->integer('jumlah_vlan')->nullable();
            $table->string('alamat_ip_manajemen')->nullable();
            $table->string('alamat_mac')->nullable();
            $table->integer('id_aset')->nullable();
        });

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/network-device/switch/create')
            ->assertOk()
            ->assertSee('Tambah Switch Baru')
            ->assertSee('name="jenis_switch"', false);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->post('/network-device/switch', [
                'jenis_switch' => 'Access',
                'lapisan_jaringan' => 'Layer 2',
                'jumlah_port' => 48,
                'jumlah_port_terpakai' => 20,
                'jumlah_port_tersedia' => 28,
                'kecepatan_port' => '1 Gbps',
                'jumlah_port_uplink' => 4,
                'status_stack' => 'Aktif',
                'jumlah_anggota_stack' => 2,
                'jumlah_vlan' => 10,
                'alamat_ip_manajemen' => '192.0.2.11',
                'alamat_mac' => '00:11:22:33:44:55',
            ])
            ->assertRedirect(route('network-device.switch'));

        $this->assertDatabaseHas('switch', [
            'jenis_switch' => 'Access',
            'jumlah_port' => 48,
            'alamat_mac' => '00:11:22:33:44:55',
        ]);
    }

    public function test_router_can_be_updated(): void
    {
        Schema::create('router', function (Blueprint $table) {
            $table->increments('id_router');
            $table->string('jenis_router');
            $table->decimal('kapasitas_bandwidth')->nullable();
            $table->string('protokol_routing')->nullable();
            $table->integer('jumlah_tunnel_vpn')->nullable();
            $table->string('status_vpn')->nullable();
            $table->string('status_nat')->nullable();
            $table->string('status_ha')->nullable();
            $table->string('alamat_ip_manajemen')->nullable();
            $table->integer('id_aset')->nullable();
        });

        DB::table('router')->insert([
            'id_router' => 42,
            'jenis_router' => 'Core',
            'kapasitas_bandwidth' => 100,
            'protokol_routing' => 'OSPF',
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/network-device/router/42/edit')
            ->assertOk()
            ->assertSee('Edit Router #42')
            ->assertSee('name="jumlah_tunnel_vpn"', false);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put('/network-device/router/42', [
                'jenis_router' => 'Edge',
                'kapasitas_bandwidth' => 500,
                'protokol_routing' => 'BGP',
                'jumlah_tunnel_vpn' => 4,
                'status_vpn' => 'Aktif',
                'status_nat' => 'Aktif',
                'status_ha' => 'Tidak Aktif',
                'alamat_ip_manajemen' => '192.0.2.42',
            ])
            ->assertRedirect(route('network-device.router'));

        $this->assertDatabaseHas('router', [
            'id_router' => 42,
            'jenis_router' => 'Edge',
            'jumlah_tunnel_vpn' => 4,
            'alamat_ip_manajemen' => '192.0.2.42',
        ]);
    }

    public function test_switch_can_be_updated(): void
    {
        Schema::create('switch', function (Blueprint $table) {
            $table->increments('id_switch');
            $table->string('jenis_switch');
            $table->string('lapisan_jaringan')->nullable();
            $table->integer('jumlah_port')->nullable();
            $table->integer('jumlah_port_terpakai')->nullable();
            $table->integer('jumlah_port_tersedia')->nullable();
            $table->string('kecepatan_port')->nullable();
            $table->integer('jumlah_port_uplink')->nullable();
            $table->string('status_stack')->nullable();
            $table->integer('jumlah_anggota_stack')->nullable();
            $table->integer('jumlah_vlan')->nullable();
            $table->string('alamat_ip_manajemen')->nullable();
            $table->string('alamat_mac')->nullable();
            $table->integer('id_aset')->nullable();
        });

        DB::table('switch')->insert([
            'id_switch' => 24,
            'jenis_switch' => 'Core',
            'jumlah_port' => 24,
        ]);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/network-device/switch/24/edit')
            ->assertOk()
            ->assertSee('Edit Switch #24')
            ->assertSee('name="jumlah_port_terpakai"', false);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->put('/network-device/switch/24', [
                'jenis_switch' => 'Access',
                'lapisan_jaringan' => 'Layer 2',
                'jumlah_port' => 48,
                'jumlah_port_terpakai' => 20,
                'jumlah_port_tersedia' => 28,
                'kecepatan_port' => '1 Gbps',
                'jumlah_port_uplink' => 4,
                'status_stack' => 'Aktif',
                'jumlah_anggota_stack' => 2,
                'jumlah_vlan' => 10,
                'alamat_ip_manajemen' => '192.0.2.24',
                'alamat_mac' => '00:11:22:33:44:55',
            ])
            ->assertRedirect(route('network-device.switch'));

        $this->assertDatabaseHas('switch', [
            'id_switch' => 24,
            'jenis_switch' => 'Access',
            'jumlah_port' => 48,
            'alamat_mac' => '00:11:22:33:44:55',
        ]);
    }

    public function test_router_can_be_deleted(): void
    {
        Schema::create('router', function (Blueprint $table) {
            $table->increments('id_router');
            $table->string('jenis_router');
        });
        DB::table('router')->insert(['id_router' => 42, 'jenis_router' => 'Core']);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete('/network-device/router/42')
            ->assertRedirect(route('network-device.router'));

        $this->assertDatabaseMissing('router', ['id_router' => 42]);
    }

    public function test_switch_can_be_deleted(): void
    {
        Schema::create('switch', function (Blueprint $table) {
            $table->increments('id_switch');
            $table->string('jenis_switch');
        });
        DB::table('switch')->insert(['id_switch' => 24, 'jenis_switch' => 'Core']);

        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->delete('/network-device/switch/24')
            ->assertRedirect(route('network-device.switch'));

        $this->assertDatabaseMissing('switch', ['id_switch' => 24]);
    }
}
