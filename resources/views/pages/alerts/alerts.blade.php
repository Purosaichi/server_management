@extends('layouts.app')

@section('title', 'Alerts')

@section('content')
{{-- CSS Terpisah --}}
<link rel="stylesheet" href="{{ asset('css/alerts.css') }}">

<div class="alerts-header">
    <h1 class="alerts-title">Alerts</h1>
    <p class="alerts-subtitle">Peringatan kondisi server dan aplikasi.</p>
</div>

<div class="alerts-empty-card">
    <p class="alerts-empty-text">Belum ada alert untuk ditampilkan.</p>
</div>
@endsection