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
}
