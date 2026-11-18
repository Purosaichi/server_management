@extends('layouts.app')

@section('title', 'Aset')
@section('page-title', 'Aset')
@section('page-subtitle', 'Ringkasan data inventaris aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-dashboard.css') }}">

<div class="asset-dashboard-header">
    <div>
        <h2>Ringkasan Aset</h2>
        <p>Kelola seluruh data aset dari satu halaman.</p>
    </div>
    <div class="asset-dashboard-add-menu">
        <button type="button" class="asset-dashboard-add-trigger" data-add-assets-toggle aria-expanded="false" aria-controls="assetAddMenu">
            <i class="fas fa-plus" aria-hidden="true"></i>
            Tambah Data
            <i class="fas fa-chevron-down asset-dashboard-add-chevron" aria-hidden="true"></i>
        </button>
        <div class="asset-dashboard-add-options" id="assetAddMenu" hidden>
            <p>Pilih data yang ingin ditambahkan</p>
            <a href="{{ route('server.create') }}"><i class="fas fa-server" aria-hidden="true"></i> Server</a>
            <a href="{{ route('network-device.router-create') }}"><i class="fas fa-network-wired" aria-hidden="true"></i> Router</a>
            <a href="{{ route('network-device.switch-create') }}"><i class="fas fa-network-wired" aria-hidden="true"></i> Switch</a>
            <a href="{{ route('aset.lokasi.create') }}"><i class="fas fa-location-dot" aria-hidden="true"></i> Lokasi Aset</a>
            <a href="{{ route('aset.storage.create') }}"><i class="fas fa-hard-drive" aria-hidden="true"></i> Storage</a>
            <a href="{{ route('aset.penyedia.create') }}"><i class="fas fa-user" aria-hidden="true"></i> Penyedia</a>
            <a href="{{ route('aset.merek.create') }}"><i class="fas fa-tag" aria-hidden="true"></i> Merek</a>
            <a href="{{ route('aset.garansi.create') }}"><i class="fas fa-shield-halved" aria-hidden="true"></i> Garansi</a>
        </div>
    </div>
</div>

<div class="asset-dashboard-grid">
    @foreach ($sections as $section)
        <div class="asset-summary-card">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="asset-summary-label">{{ $section['title'] }}</p>
                    <p class="asset-summary-value">{{ $section['count'] }}</p>
                    <p class="asset-summary-detail">{{ $section['summary'] }}</p>
                </div>
                <div class="asset-summary-icon">
                    <i class="fas {{ $section['icon'] }} text-lg" aria-hidden="true"></i>
                </div>
            </div>

            <div class="asset-summary-list">
                <p class="asset-summary-list-heading">Data terbaru</p>
                @forelse ($section['items'] as $item)
                    <div class="asset-summary-row">
                        <span class="asset-summary-name">{{ $item['name'] }}</span>
                        <span class="asset-summary-meta">{{ $item['detail'] }}</span>
                    </div>
                @empty
                    <p class="asset-summary-empty">Belum ada data.</p>
                @endforelse
            </div>

            <a href="{{ $section['url'] }}" class="asset-summary-link">
                Lihat semua
                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
            </a>
        </div>
    @endforeach

</div>

<footer class="footer asset-dashboard-footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
<script>
    const addAssetsToggle = document.querySelector('[data-add-assets-toggle]');
    const addAssetsMenu = document.getElementById('assetAddMenu');

    addAssetsToggle?.addEventListener('click', function (event) {
        event.stopPropagation();
        const isOpen = addAssetsToggle.getAttribute('aria-expanded') === 'true';
        addAssetsToggle.setAttribute('aria-expanded', String(!isOpen));
        addAssetsToggle.classList.toggle('is-active', !isOpen);
        addAssetsMenu.hidden = isOpen;
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.asset-dashboard-add-menu')) {
            addAssetsToggle?.setAttribute('aria-expanded', 'false');
            addAssetsToggle?.classList.remove('is-active');
            if (addAssetsMenu) addAssetsMenu.hidden = true;
        }
    });
</script>
@endsection
