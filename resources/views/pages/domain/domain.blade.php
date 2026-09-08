@extends('layouts.app')

@section('title', 'Domain')

@section('content')
{{-- CSS Terpisah --}}
<link rel="stylesheet" href="{{ asset('css/domain.css') }}">

<div class="domain-header">
    <h1>Domain</h1>
    <p>Daftar domain yang terhubung ke sistem.</p>
</div>

<div class="domain-empty-card">
    Belum ada data domain untuk ditampilkan.
</div>
@endsection