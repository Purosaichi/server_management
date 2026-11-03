<div class="nd-table-container">
    <div class="nd-table-header">
        <h3 class="nd-table-title">Daftar Router</h3>
        <div class="nd-table-actions">
            <span class="nd-table-count">Total: {{ $routers->count() }} router</span>
            <a href="{{ route('network-device.router-create') }}" class="nd-btn-add">+ Tambah Router</a>
        </div>
    </div>
    <div class="nd-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis</th>
                    <th>Bandwidth</th>
                    <th>Protokol</th>
                    <th>VPN Tunnel</th>
                    <th>Status VPN</th>
                    <th>Status NAT</th>
                    <th>Status HA</th>
                    <th>IP Manajemen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($routers as $router)
                <tr class="nd-device-row" data-detail-url="{{ route('network-device.router-detail', $router->id_router) }}" tabindex="0" aria-label="Detail router {{ $router->id_router }}">
                    <td><span class="nd-name">{{ $router->id_router }}</span></td>
                    <td>
                        <span class="nd-badge 
                            {{ $router->jenis_router == 'Core' ? 'nd-badge-blue' : '' }}
                            {{ $router->jenis_router == 'Edge' ? 'nd-badge-green' : '' }}
                            {{ $router->jenis_router == 'Branch' ? 'nd-badge-orange' : '' }}
                        ">
                            {{ $router->jenis_router }}
                        </span>
                    </td>
                    <td>{{ $router->kapasitas_bandwidth }} Mbps</td>
                    <td>{{ $router->protokol_routing }}</td>
                    <td>{{ $router->jumlah_tunnel_vpn ?? '-' }}</td>
                    <td>
                        <span class="nd-status {{ $router->status_vpn == 'Aktif' ? 'nd-status-active' : 'nd-status-inactive' }}">
                            {{ $router->status_vpn }}
                        </span>
                    </td>
                    <td>
                        <span class="nd-status {{ $router->status_nat == 'Aktif' ? 'nd-status-active' : 'nd-status-inactive' }}">
                            {{ $router->status_nat }}
                        </span>
                    </td>
                    <td>
                        <span class="nd-status {{ $router->status_ha == 'Aktif' ? 'nd-status-active' : 'nd-status-inactive' }}">
                            {{ $router->status_ha }}
                        </span>
                    </td>
                    <td>{{ $router->alamat_ip_manajemen ?? '-' }}</td>
                    <td class="nd-text-center">
                        <a href="{{ route('network-device.router-edit', $router->id_router) }}" class="nd-btn-edit">Edit</a>
                        <button type="button" class="nd-btn-delete"
                            data-delete-url="{{ route('network-device.router-destroy', $router->id_router) }}"
                            data-device-name="router #{{ $router->id_router }}"
                            onclick="showNetworkDeviceDeleteModal(this)">
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center; color: #9ca3af; padding: 2rem;">
                        Belum ada data router.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>