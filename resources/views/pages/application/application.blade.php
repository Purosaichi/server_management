@extends('layouts.app')

@section('title', 'Application')

@section('page-title', 'Application')
@section('page-subtitle', 'Daftar semua aplikasi yang dimonitoring')

@section('content')
<link rel="stylesheet" href="{{ asset('css/application.css') }}">

<div class="header">
    <h2 class="title-header">Application</h2>
</div>

{{-- Notifikasi --}}
@if(session('success'))
<div class="app-alert app-alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="app-alert app-alert-error">
    {{ session('error') }}
</div>
@endif

<div class="app-table-container">
    <div class="app-table-header">
        <h3 class="app-table-title">Daftar Aplikasi</h3>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span class="app-table-count">Total: {{ count($applications) }} aplikasi</span>
            <a href="{{ route('application.create') }}" class="app-btn-add">+ Tambah Aplikasi</a>
        </div>
    </div>
    <div class="app-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Application</th>
                    <th>Server</th>
                    <th>Status</th>
                    <th>Domain</th>
                    <th>Licenses</th>
                    <th>Next Maintenance</th>
                    <th class="app-text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr ondblclick="window.location='{{ route('application.detail', $app['id']) }}'" 
                    style="cursor: pointer;" 
                    title="Double click untuk lihat detail">
                    <td><span class="app-name">{{ $app['name'] }}</span></td>
                    <td>{{ $app['server_name'] }}</td>
                    <td>
                        <span class="app-badge 
                            {{ $app['status'] == 'Aktif' ? 'app-badge-green' : '' }}
                            {{ $app['status'] == 'Down' ? 'app-badge-red' : '' }}
                            {{ $app['status'] == 'Maintenance' ? 'app-badge-yellow' : '' }}
                        ">
                            {{ $app['status'] }}
                        </span>
                    </td>
                    <td>{{ $app['domain'] }}</td>
                    <td>
                        <span class="app-badge app-badge-red">
                            {{ $app['licenses'] }}
                        </span>
                    </td>
                    <td>{{ $app['next_maintenance'] }}</td>
                    <td class="app-text-center">
                        <a href="{{ route('application.edit', $app['id']) }}" class="app-btn-edit">
                            Edit
                        </a>
                        <button type="button" class="app-btn-delete" 
                                onclick="showDeleteModal({{ $app['id'] }}, '{{ $app['name'] }}')">
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9ca3af; padding: 2rem;">
                        Belum ada data aplikasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
<div id="deleteModal" class="app-modal-overlay" style="display: none;">
    <div class="app-modal">
        <div class="app-modal-icon">⚠️</div>
        <h3 class="app-modal-title">Konfirmasi Hapus</h3>
        <p class="app-modal-text">
            Apakah Anda yakin ingin menghapus aplikasi <strong id="deleteAppName"></strong>?
        </p>
        <p class="app-modal-warning">
            Data akan dihapus dari tampilan, tapi tetap tersimpan di database.
        </p>
        <div class="app-modal-actions">
            <button type="button" class="app-btn-cancel" onclick="closeDeleteModal()">
                Batal
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="app-btn-delete-confirm">
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
        document.getElementById('deleteAppName').textContent = name;
        document.getElementById('deleteForm').action = '/application/' + id;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endsection