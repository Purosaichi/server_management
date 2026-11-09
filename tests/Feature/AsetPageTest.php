<?php

namespace Tests\Feature;

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

    public function test_asset_location_menu_opens_its_placeholder_page(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.lokasi'))
            ->assertOk()
            ->assertSee('Halaman Lokasi Aset')
            ->assertSee('Data lokasi aset akan ditampilkan di sini.');
    }

    public function test_storage_menu_opens_its_placeholder_page(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get(route('aset.storage'))
            ->assertOk()
            ->assertSee('Halaman Storage')
            ->assertSee('Data storage aset akan ditampilkan di sini.');
    }
}