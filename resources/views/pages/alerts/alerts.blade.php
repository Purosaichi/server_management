@extends('layouts.app')

@section('title', 'Alerts')

@section('content')
<link rel="stylesheet" href="{{ asset('css/alerts.css') }}">

<div class="header">
    <h2 class="title-header">Alerts</h2>
</div>

<div class="alerts-card">
    <p class="alerts-empty-text">Belum ada alert untuk ditampilkan.</p>
</div>
<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection