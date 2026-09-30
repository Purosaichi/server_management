@extends('layouts.app')

@section('title', 'Network Device')

@section('page-title', 'Network Device')
@section('page-subtitle', 'Daftar Router & Switch yang dimonitoring')

@section('content')
<link rel="stylesheet" href="{{ asset('css/network-device.css') }}">

<div class="header">
    <h2 class="title-header">Network Device</h2>
</div>

{{-- Tab Navigasi --}}
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

{{-- Konten Tab --}}
@if($activeTab === 'router')
    @include('pages.network-device.router', ['routers' => $routers])
@elseif($activeTab === 'switch')
    @include('pages.network-device.switch', ['switches' => $switches])
@endif

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection