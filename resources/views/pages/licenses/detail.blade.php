@extends('layouts.app')

@section('title', 'Detail License - ' . $license['name'])

@section('content')
<link rel="stylesheet" href="{{ asset('css/licenses.css') }}">

<!-- Header -->
<div class="header">
    <h2 class="title-header">Detail License</h2>
    <a href="{{ route('licenses.licenses') }}" class="btn-back">← Kembali</a>
</div>

<!-- Card Detail License -->
<div class="alerts-card">
    <div class="detail-header">
        <img src="{{ asset('images/' . $license['logo']) }}" alt="{{ $license['name'] }}" class="detail-license-logo">
        <h3 class="detail-title">{{ $license['name'] }}</h3>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">Nama License</span>
            <span class="detail-value">{{ $license['name'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Provider</span>
            <span class="detail-value">{{ $license['provider'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Status</span>
            <span class="badge {{ $license['status'] == 'Aman' ? 'badge-aktif' : 'badge-warning' }}">
                {{ $license['status'] }}
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Jumlah License</span>
            <span class="detail-value">{{ $license['jumlah'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Tanggal Expired</span>
            <span class="detail-value">{{ $license['expired'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">PIC</span>
            <span class="detail-value">{{ $license['pic'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Digunakan Oleh</span>
            <span class="detail-value">{{ $license['digunakan_oleh'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Keterangan</span>
            <span class="detail-value">{{ $license['keterangan'] }}</span>
        </div>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection