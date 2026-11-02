<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Models\SwitchDevice;
use Illuminate\Http\Request;

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

    public function editRouter(int $id)
    {
        $router = Router::findOrFail($id);

        return view('pages.network-device.router-edit', compact('router'));
    }

    public function updateRouter(Request $request, int $id)
    {
        $validated = $request->validate([
            'jenis_router' => 'required|in:Core,Edge,Branch',
            'kapasitas_bandwidth' => 'nullable|numeric|min:0',
            'protokol_routing' => 'nullable|string|max:100',
            'jumlah_tunnel_vpn' => 'nullable|integer|min:0',
            'status_vpn' => 'nullable|string|max:50',
            'status_nat' => 'nullable|string|max:50',
            'status_ha' => 'nullable|string|max:50',
            'alamat_ip_manajemen' => 'nullable|ip',
        ]);

        Router::findOrFail($id)->update($validated);

        return redirect()->route('network-device.router')
            ->with('success', 'Data router berhasil diperbarui.');
    }

    public function destroyRouter(int $id)
    {
        Router::findOrFail($id)->delete();

        return redirect()->route('network-device.router')
            ->with('success', 'Data router berhasil dihapus.');
    }

    public function switchDetail(int $id)
    {
        $switch = SwitchDevice::find($id);
        abort_unless($switch, 404);
        return view('pages.network-device.switch-detail', compact('switch'));
    }

    public function editSwitch(int $id)
    {
        $switch = SwitchDevice::findOrFail($id);

        return view('pages.network-device.switch-edit', compact('switch'));
    }

    public function updateSwitch(Request $request, int $id)
    {
        $validated = $request->validate([
            'jenis_switch' => 'required|in:Core,Distribution,Access',
            'lapisan_jaringan' => 'nullable|string|max:50',
            'jumlah_port' => 'nullable|integer|min:0',
            'jumlah_port_terpakai' => 'nullable|integer|min:0',
            'jumlah_port_tersedia' => 'nullable|integer|min:0',
            'kecepatan_port' => 'nullable|string|max:50',
            'jumlah_port_uplink' => 'nullable|integer|min:0',
            'status_stack' => 'nullable|string|max:50',
            'jumlah_anggota_stack' => 'nullable|integer|min:0',
            'jumlah_vlan' => 'nullable|integer|min:0',
            'alamat_ip_manajemen' => 'nullable|ip',
            'alamat_mac' => 'nullable|string|max:17',
        ]);

        SwitchDevice::findOrFail($id)->update($validated);

        return redirect()->route('network-device.switch')
            ->with('success', 'Data switch berhasil diperbarui.');
    }

    public function destroySwitch(int $id)
    {
        SwitchDevice::findOrFail($id)->delete();

        return redirect()->route('network-device.switch')
            ->with('success', 'Data switch berhasil dihapus.');
    }
}
