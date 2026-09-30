<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Models\SwitchDevice;


class NetworkDeviceController extends Controller
{
    public function index()
    {
        return $this -> router();
    }

    public function router()
    {
        $routers = Router::orderBy('id_router')->get();
        
        return view('pages.network-device.index', [
            'activeTab' => 'router',
            'routers' => $routers,
            'switches' => collect(),
        ]);
    }

    public function switch()
    {
        $switches = switchDevice::orderBy('id_switch')->get();

        return view('pages.network-device.index', [
            'activeTab' => 'switch',
            'routers' => collect(),
            'switches' => $switches,
        ]);
    }

    public function routerDetail(int $id)
    {
        $router = Router::find($id);
        abort_unless($router, 404);
        return view('pages.network-device.router-detail', compact('router'));
    }

    public function switchDetail(int $id)
    {
        $switch = SwitchDevice::find($id);
        abort_unless($switch, 404);
        return view('pages.network-device.switch-detail', compact('switch'));
    }
}
