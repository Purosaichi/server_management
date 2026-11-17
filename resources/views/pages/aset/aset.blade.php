@extends('layouts.app')

@section('title', 'Aset')
@section('page-title', 'Aset')
@section('page-subtitle', 'Ringkasan data inventaris aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/aset-dashboard.css') }}">

<div class="asset-dashboard-header">
    <h2>Ringkasan Aset</h2>
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

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection
