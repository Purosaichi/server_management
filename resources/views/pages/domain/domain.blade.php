@extends('layouts.app')

@section('title', 'Domain')

@section('content')
{{-- CSS Terpisah --}}
<link rel="stylesheet" href="{{ asset('css/domain.css') }}">

<div class="header">
    <h2 class="title-header">Domain</h2>
</div>

<div class="domain-empty-card">
    Belum ada data domain untuk ditampilkan.
</div>
<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection