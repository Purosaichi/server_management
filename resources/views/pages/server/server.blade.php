@extends('layouts.app')

@section('title', 'Server')

@section('page-title', 'Server')
@section('page-subtitle', 'Daftar semua server yang dimonitoring')

@section('content')
<link rel="stylesheet" href="{{ asset('css/server.css') }}">

<div class="header">
    <h2 class="title-header">Server</h2>
</div>

<!-- /* Notifikasi */ -->
@if(session('success'))
<div class="srv-alert srv-alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="srv-alert srv-alert-error">
    {{ session('error') }}
</div>
@endif

<div class="srv-table-container">
    <div class="srv-table-header">
        <h3 class="srv-table-title">Daftar Server</h3>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span class="srv-table-count">Total Server: {{ count($servers) }} server</span>
            <a href="{{ route('server.create') }}" class="srv-btn-add">+ Tambah Server</a>
        </div>
    </div>
    <div class="srv-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Server</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>CPU</th>
                    <th>RAM</th>
                    <th>DISC</th>
                    <th>Up Time</th>
                    <th class="srv-text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servers as $server)
                <tr ondblclick="window.location='{{ route('server.detail', $server['id']) }}'" 
                    style="cursor: pointer;" 
                    title="Double click untuk lihat detail">
                    <td><span class="srv-name">{{ $server['name'] }}</span></td>
                    <td>{{ $server['ip_address'] }}</td>
                    <td>
                        <span class="srv-badge 
                            {{ $server['status'] == 'Online' ? 'srv-badge-online' : 'srv-badge-offline' }}">
                            {{ $server['status'] }}
                        </span>
                    </td>
                    <td>{{ $server['cpu'] }}</td>
                    <td>{{ $server['ram'] }}</td>
                    <td>{{ $server['disk'] }}</td>
                    <td>{{ $server['uptime'] }}</td>
                    <td class="srv-text-center">
                        <a href="{{ route('server.edit', $server['id']) }}" class="srv-btn-edit">
                            Edit
                        </a>
                        <button type="button" class="srv-btn-delete" 
                                onclick="showDeleteModal({{ $server['id'] }}, '{{ $server['name'] }}')">
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #9ca3af; padding: 2rem;">
                        Belum ada data server.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- /* MODAL KONFIRMASI HAPUS */ -->
<div id="deleteModal" class="srv-modal-overlay" style="display: none;">
    <div class="srv-modal">
        <div class="srv-modal-icon">⚠️</div>
        <h3 class="srv-modal-title">Konfirmasi Hapus</h3>
        <p class="srv-modal-text">
            Apakah Anda yakin ingin menghapus server <strong id="deleteServerName"></strong>?
        </p>
        <div class="srv-modal-actions">
            <button type="button" class="srv-btn-cancel" onclick="closeDeleteModal()">
                Batal
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="srv-btn-delete-confirm">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>

<script>
    function showDeleteModal(id, name) {
        document.getElementById('deleteServerName').textContent = name;
        document.getElementById('deleteForm').action = '/server/' + id;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    /* Tutup modal kalo klik di luar */
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    /* Tutup modal kalo tekan ESC */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endsection