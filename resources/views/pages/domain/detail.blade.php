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
<div class="app-detail-container">
    <h3 class="app-detail-title">{{ $domain['domain'] }}</h3>

    <div class="app-detail-grid">
        <div class="app-detail-item"><strong>Domain:</strong> <span>{{ $domain['domain'] }}</span></div>
        <div class="app-detail-item"><strong>Application:</strong> <span>{{ $domain['application'] }}</span></div>
        <div class="app-detail-item"><strong>Status:</strong> <span>{{ $domain['status'] }}</span></div>
        <div class="app-detail-item"><strong>PIC:</strong> <span>{{ $domain['pic'] }}</span></div>
        <div class="app-detail-item"><strong>SSL Certificate:</strong> <span>{{ $domain['ssl'] }}</span></div>
        <div class="app-detail-item"><strong>Last Activity:</strong> <span>{{ $domain['last_activity'] }}</span></div>
        <div class="app-detail-item"><strong>Registrar:</strong> <span>{{ $domain['registrar'] }}</span></div>
        <div class="app-detail-item"><strong>Nameserver:</strong> <span>{{ $domain['nameserver'] }}</span></div>
        <div class="app-detail-item"><strong>Dibuat:</strong> <span>{{ $domain['created_at'] }}</span></div>
        <div class="app-detail-item"><strong>Expired:</strong> <span>{{ $domain['expired_at'] }}</span></div>
    </div>

    <div class="app-detail-back">
        <a href="{{ url('/domain') }}">← Kembali</a>
    </div>
</div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection