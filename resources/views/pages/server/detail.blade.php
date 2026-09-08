@extends('layouts.app')

@section('title', 'Detail Server')

@section('page-title', 'Detail Server')
@section('page-subtitle', 'Informasi lengkap server')

@section('content')
{{-- CSS Terpisah --}}
<link rel="stylesheet" href="{{ asset('css/server.css') }}">

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">{{ $server['name'] }}</h3>
        <span class="srv-badge {{ $server['status'] == 'Online' ? 'srv-badge-online' : 'srv-badge-offline' }}">
            {{ $server['status'] }}
        </span>
    </div>
    
    <div class="grid grid-cols-2 gap-4">
        <div><strong>IP Address:</strong> {{ $server['ip'] }}</div>
        <div><strong>Hostname:</strong> {{ $server['hostname'] }}</div>
        <div><strong>OS:</strong> {{ $server['os'] }}</div>
        <div><strong>Lokasi:</strong> {{ $server['location'] }}</div>
        <div><strong>CPU:</strong> {{ $server['cpu'] }}</div>
        <div><strong>RAM:</strong> {{ $server['ram'] }}</div>
        <div><strong>DISC:</strong> {{ $server['disk'] }}</div>
        <div><strong>Up Time:</strong> {{ $server['uptime'] }}</div>
        <div><strong>Last Check:</strong> {{ $server['last_check'] }}</div>
    </div>
    
    <div class="mt-6">
        <a href="{{ route('server.index') }}" class="text-blue-600 hover:underline">← Kembali ke daftar server</a>
    </div>
</div>
@endsection