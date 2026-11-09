@extends('layouts.app')

@section('title', 'Network Device')

@section('page-title', 'Network Device')
@section('page-subtitle', 'Daftar Router & Switch yang dimonitoring')

@section('content')
<link rel="stylesheet" href="{{ asset('css/network-device.css') }}">

<div class="header">
    <h2 class="title-header">Network Device</h2>
</div>

@if(session('success'))
<div class="nd-alert nd-alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="nd-alert nd-alert-error">{{ session('error') }}</div>
@endif

<!-- Tab Navigasi -->
<div class="nd-tabs">
    <a href="{{ route('network-device.router') }}" 
       class="nd-tab {{ $activeTab === 'router' ? 'active' : '' }}">
        Router
    </a>
    <a href="{{ route('network-device.switch') }}" 
       class="nd-tab {{ $activeTab === 'switch' ? 'active' : '' }}">
        Switch
    </a>
</div>

<!-- Konten Berdasarkan Tab yang Aktif -->
@if($activeTab === 'router')
    @include('pages.aset.Network-Device.router', ['routers' => $routers])
@elseif($activeTab === 'switch')
    @include('pages.aset.Network-Device.switch', ['switches' => $switches])
@endif

<div id="ndDeleteModal" class="nd-modal-overlay" style="display: none;" aria-hidden="true">
    <div class="nd-modal" role="alertdialog" aria-modal="true" aria-labelledby="ndDeleteTitle">
        <h3 id="ndDeleteTitle" class="nd-modal-title">Konfirmasi Hapus</h3>
        <p class="nd-modal-text">Apakah Anda yakin ingin menghapus <strong id="ndDeleteDeviceName"></strong>?</p>
        <div class="nd-modal-actions">
            <button type="button" class="nd-btn-cancel" onclick="closeNetworkDeviceDeleteModal()" data-modal-cancel>Batal</button>
            <form id="ndDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="nd-btn-delete-confirm">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>

<script>
    document.querySelectorAll('.nd-device-row').forEach(function (row) {
        row.addEventListener('dblclick', function (event) {
            if (event.target.closest('a, button, form')) return;
            window.location.assign(row.dataset.detailUrl);
        });

        row.addEventListener('keydown', function (event) {
            if (event.target.closest('a, button, form')) return;
            if (event.key !== 'Enter') return;
            event.preventDefault();
            window.location.assign(row.dataset.detailUrl);
        });
    });

    const ndDeleteModal = document.getElementById('ndDeleteModal');
    let ndDeleteTrigger = null;

    function showNetworkDeviceDeleteModal(button) {
        ndDeleteTrigger = button;
        document.getElementById('ndDeleteDeviceName').textContent = button.dataset.deviceName;
        document.getElementById('ndDeleteForm').action = button.dataset.deleteUrl;
        ndDeleteModal.style.display = 'flex';
        ndDeleteModal.setAttribute('aria-hidden', 'false');
        ndDeleteModal.querySelector('[data-modal-cancel]').focus();
    }

    function closeNetworkDeviceDeleteModal() {
        ndDeleteModal.style.display = 'none';
        ndDeleteModal.setAttribute('aria-hidden', 'true');
        ndDeleteTrigger?.focus();
    }

    ndDeleteModal.addEventListener('click', function (event) {
        if (event.target === ndDeleteModal) closeNetworkDeviceDeleteModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && ndDeleteModal.style.display === 'flex') {
            closeNetworkDeviceDeleteModal();
        }
    });
</script>
@endsection