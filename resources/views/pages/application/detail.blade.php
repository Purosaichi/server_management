@extends('layouts.app')

@section('title', 'Detail Aplikasi')

@section('page-title', 'Detail Aplikasi')
@section('page-subtitle', 'Informasi lengkap aplikasi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/application.css') }}">

<div class="app-detail-container">
    <h3 class="app-detail-title">{{ $application['name'] }}</h3>
    <div class="app-detail-grid">
        <div class="app-detail-item"><strong>Server:</strong> <span>{{ $application['server'] }}</span></div>
        <div class="app-detail-item"><strong>Status:</strong> <span>{{ $application['status'] }}</span></div>
        <div class="app-detail-item"><strong>Domain:</strong> <span>{{ $application['domain'] }}</span></div>
        <div class="app-detail-item"><strong>Lisensi:</strong> <span>{{ $application['licenses'] }}</span></div>
        <div class="app-detail-item"><strong>Maintenance:</strong> <span>{{ $application['maintenance'] }}</span></div>
        <div class="app-detail-item"><strong>Deskripsi:</strong> <span>{{ $application['description'] ?? '-' }}</span></div>
        <div class="app-detail-item"><strong>Versi:</strong> <span>{{ $application['version'] ?? '-' }}</span></div>
        <div class="app-detail-item"><strong>Kategori:</strong> <span>{{ $application['category'] ?? '-' }}</span></div>
    </div>
    <div class="app-detail-back">
        <a href="{{ route('application.index') }}">← Kembali ke daftar aplikasi</a>
    </div>
</div>
@endsection