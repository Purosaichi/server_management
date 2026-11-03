<div class="nd-table-container">
    <div class="nd-table-header">
        <h3 class="nd-table-title">Daftar Switch</h3>
        <div class="nd-table-actions">
            <span class="nd-table-count">Total: {{ $switches->count() }} switch</span>
            <a href="{{ route('network-device.switch-create') }}" class="nd-btn-add">+ Tambah Switch</a>
        </div>
    </div>
    <div class="nd-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis</th>
                    <th>Layer</th>
                    <th>Total Port</th>
                    <th>Terpakai</th>
                    <th>Tersedia</th>
                    <th>Kecepatan</th>
                    <th>Uplink</th>
                    <th>Stack</th>
                    <th>VLAN</th>
                    <th>IP Manajemen</th>
                    <th>MAC Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($switches as $switch)
                <tr class="nd-device-row" data-detail-url="{{ route('network-device.switch-detail', $switch->id_switch) }}" tabindex="0" aria-label="Detail switch {{ $switch->id_switch }}">
                    <td><span class="nd-name">{{ $switch->id_switch }}</span></td>
                    <td>
                        <span class="nd-badge 
                            {{ $switch->jenis_switch == 'Core' ? 'nd-badge-blue' : '' }}
                            {{ $switch->jenis_switch == 'Distribution' ? 'nd-badge-green' : '' }}
                            {{ $switch->jenis_switch == 'Access' ? 'nd-badge-orange' : '' }}
                        ">
                            {{ $switch->jenis_switch }}
                        </span>
                    </td>
                    <td>{{ $switch->lapisan_jaringan ?? '-' }}</td>
                    <td>{{ $switch->jumlah_port ?? '-' }}</td>
                    <td>{{ $switch->jumlah_port_terpakai ?? '-' }}</td>
                    <td>{{ $switch->jumlah_port_tersedia ?? '-' }}</td>
                    <td>{{ $switch->kecepatan_port ?? '-' }}</td>
                    <td>{{ $switch->jumlah_port_uplink ?? '-' }}</td>
                    <td>
                        <span class="nd-status {{ $switch->status_stack == 'Aktif' ? 'nd-status-active' : 'nd-status-inactive' }}">
                            {{ $switch->status_stack }}
                        </span>
                    </td>
                    <td>{{ $switch->jumlah_vlan ?? '-' }}</td>
                    <td>{{ $switch->alamat_ip_manajemen ?? '-' }}</td>
                    <td>{{ $switch->alamat_mac ?? '-' }}</td>
                    <td class="nd-text-center">
                        <a href="{{ route('network-device.switch-edit', $switch->id_switch) }}" class="nd-btn-edit">Edit</a>
                        <button type="button" class="nd-btn-delete"
                            data-delete-url="{{ route('network-device.switch-destroy', $switch->id_switch) }}"
                            data-device-name="switch #{{ $switch->id_switch }}"
                            onclick="showNetworkDeviceDeleteModal(this)">
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" style="text-align: center; color: #9ca3af; padding: 2rem;">
                        Belum ada data switch.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>