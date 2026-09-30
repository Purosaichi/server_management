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

    
}
