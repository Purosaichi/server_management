@extends('layouts.app')

@section('title', 'Licenses')

@section('content')
{{-- CSS Terpisah --}}
<link rel="stylesheet" href="{{ asset('css/licenses.css') }}">

<div class="licenses-header">
    <h1>Licenses</h1>
    <p>Daftar lisensi aplikasi dan server.</p>
</div>

<div class="licenses-empty-card">
    Belum ada data lisensi untuk ditampilkan.
</div>
<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection