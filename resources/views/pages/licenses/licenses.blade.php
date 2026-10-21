
@extends('layouts.app')

@section('title', 'Licenses')

@section('page-title', 'Licenses')
@section('page-subtitle', 'Daftar lisensi aplikasi dan server')

@section('content')
<link rel="stylesheet" href="{{ asset('css/licenses.css') }}">

<div class="header">
    <h2 class="title-header">Licenses</h2>
</div>

@if(session('success'))
    <div class="lic-alert-success" role="status">{{ session('success') }}</div>
@endif

<div class="lic-table-container">
    <div class="lic-table-header">
        <h3>Daftar Lisensi</h3>
        <div class="lic-table-tools">
            <span>Total: {{ $licenses->count() }} lisensi</span>
            <a href="{{ route('licenses.create') }}" class="lic-btn-add">+ Tambah Lisensi</a>
        </div>
    </div>
    <div class="lic-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama Lisensi</th>
                    <th>Provider</th>
                    <th>Status</th>
                    <th>Jumlah Lisensi</th>
                    <th>Tanggal Expired</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($licenses as $lic)
                <tr class="lic-row" data-detail-url="{{ route('licenses.detail', ['id' => $lic['id']]) }}">
                    <td>
                        <div class="lic-name-cell">
                            @if($lic['logo'])
                                <img src="{{ asset('images/' . $lic['logo']) }}" alt="{{ $lic['name'] }}" class="lic-logo">
                            @else
                                <span class="lic-logo-placeholder" aria-hidden="true">LIC</span>
                            @endif
                            <span class="lic-name-text">{{ $lic['name'] }}</span>
                        </div>
                    </td>
                    <td>{{ $lic['provider'] }}</td>
                    <td>
                        <span class="lic-badge 
                            {{ $lic['status_type'] == 'safe' ? 'lic-badge-safe' : 
                               ($lic['status_type'] == 'warning' ? 'lic-badge-warning' : 'lic-badge-expired') }}">
                            {{ $lic['status'] }}
                        </span>
                    </td>
                    <td>{{ $lic['jumlah'] }}</td>
                    <td>
                        <div class="lic-expired 
                            {{ $lic['expired_type'] == 'safe' ? 'lic-expired-safe' : 
                               ($lic['expired_type'] == 'warning' ? 'lic-expired-warning' : 'lic-expired-expired') }}">
                            {{ $lic['expired'] }}
                            @if($lic['expired_note'])
                                <span class="lic-expired-note">{{ $lic['expired_note'] }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="lic-row-actions">
                            <a href="{{ route('licenses.edit', ['id' => $lic['id']]) }}" class="lic-btn-edit">Edit</a>
                            <button type="button" class="lic-btn-delete"
                                data-delete-url="{{ route('licenses.destroy', ['id' => $lic['id']]) }}"
                                data-license-name="{{ $lic['name'] }}"
                                onclick="showLicenseDeleteModal(this)">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="lic-empty">Belum ada lisensi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="licenseDeleteModal" class="lic-modal-overlay" style="display: none;" aria-hidden="true">
    <div class="lic-modal" role="alertdialog" aria-modal="true" aria-labelledby="licenseDeleteTitle" aria-describedby="licenseDeleteText">
        <div class="lic-modal-icon" aria-hidden="true">⚠️</div>
        <h3 id="licenseDeleteTitle" class="lic-modal-title">Konfirmasi Hapus</h3>
        <p id="licenseDeleteText" class="lic-modal-text">
            Apakah Anda yakin ingin menghapus lisensi <strong id="licenseDeleteName"></strong>?
        </p>
        <div class="lic-modal-actions">
            <button type="button" class="lic-modal-cancel" onclick="closeLicenseDeleteModal()" data-modal-cancel>Batal</button>
            <form id="licenseDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="lic-modal-confirm">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

<footer class="footer">
    © 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen
</footer>

<script>
    const licenseDeleteModal = document.getElementById('licenseDeleteModal');
    let licenseDeleteTrigger = null;

    function showLicenseDeleteModal(button) {
        licenseDeleteTrigger = button;
        document.getElementById('licenseDeleteName').textContent = button.dataset.licenseName;
        document.getElementById('licenseDeleteForm').action = button.dataset.deleteUrl;
        licenseDeleteModal.style.display = 'flex';
        licenseDeleteModal.setAttribute('aria-hidden', 'false');
        licenseDeleteModal.querySelector('[data-modal-cancel]').focus();
    }

    function closeLicenseDeleteModal() {
        licenseDeleteModal.style.display = 'none';
        licenseDeleteModal.setAttribute('aria-hidden', 'true');
        licenseDeleteTrigger?.focus();
    }

    licenseDeleteModal.addEventListener('click', function (event) {
        if (event.target === licenseDeleteModal) closeLicenseDeleteModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && licenseDeleteModal.style.display === 'flex') {
            closeLicenseDeleteModal();
        }
    });

    document.querySelector('.lic-table-wrapper tbody').addEventListener('dblclick', function (event) {
        if (event.target.closest('.lic-row-actions')) return;

        const row = event.target.closest('tr[data-detail-url]');
        if (row) window.location.assign(row.dataset.detailUrl);
    });
</script>
@endsection