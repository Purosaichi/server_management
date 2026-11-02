<?php

namespace Tests\Feature;

use Tests\TestCase;

class NetworkDeviceRouteTest extends TestCase
{
    public function test_network_device_routes_are_registered(): void
    {
        $this->assertSame('/network-device/router', route('network-device.router', [], false));
        $this->assertSame('/network-device/switch', route('network-device.switch', [], false));
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
        $this->assertStringNotContainsString('Detail →', $html);
        $this->assertStringNotContainsString('nd-btn-detail', $html);
    }
}
