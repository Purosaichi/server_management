@extends('layouts.app')

@section('title', 'Detail Domain - ' . $domain['domain'])

@section('content')
<link rel="stylesheet" href="{{ asset('css/domain.css') }}">

<!-- Header -->
<div class="header">
    <h2 class="title-header">Detail Domain</h2>
    <a href="{{ url('/domain') }}" class="btn-back">← Kembali</a>
</div>

<!-- Card Detail Domain -->
<div class="alerts-card">
    <h3 class="detail-title">{{ $domain['domain'] }}</h3>

    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">Domain</span>
            <span class="detail-value">{{ $domain['domain'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Application</span>
            <span class="detail-value">{{ $domain['application'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Status</span>
            <span class="badge {{ $domain['status'] == 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                {{ $domain['status'] }}
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">PIC</span>
            <span class="detail-value">{{ $domain['pic'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">SSL Certificate</span>
            <span class="badge {{ $domain['ssl'] == 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                {{ $domain['ssl'] }}
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Last Activity</span>
            <span class="detail-value">{{ $domain['last_activity'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Registrar</span>
            <span class="detail-value">{{ $domain['registrar'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Nameserver</span>
            <span class="detail-value">{{ $domain['nameserver'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Dibuat</span>
            <span class="detail-value">{{ $domain['created_at'] }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Expired</span>
            <span class="detail-value">{{ $domain['expired_at'] }}</span>
        </div>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection