<?php

namespace Tests\Feature;

use App\Models\Router;
use App\Models\SwitchDevice;
use Tests\TestCase;

class NetworkDeviceModelTest extends TestCase
{
    public function test_network_device_models_use_correct_primary_keys(): void
    {
        $this->assertSame('id_router', (new Router())->getKeyName());
        $this->assertSame('id_switch', (new SwitchDevice())->getKeyName());
        $this->assertTrue((new Router())->getIncrementing());
        $this->assertTrue((new SwitchDevice())->getIncrementing());
    }
}
