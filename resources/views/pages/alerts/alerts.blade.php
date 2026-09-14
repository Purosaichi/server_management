@extends('layouts.app')

@section('title', 'Alerts')

@section('content')
<link rel="stylesheet" href="{{ asset('css/alerts.css') }}">

<div class="header">
    <h2 class="title-header">Alerts</h2>
</div>

<div class="alerts-card">
    <div class="alerts-item-header">
        <span class="alerts-badge badge-critical">Critical</span>
        <span class="alerts-time">2 Jam yang lalu</span>
    </div>       
    <h3 class="alerts-item-title">Server SVR-003 Offline</h3>
    <p class="alerts-item-text">Server SVR-003 tidak merespon sejak 2 jam yang lalu. Segera cek koneksi dan status hardware.</p>
    <span class="alerts-item-target">Target: SVR-003</span> 
    <p class="alerts-empty-text">Belum ada alert untuk ditampilkan.</p>
</div>
<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection