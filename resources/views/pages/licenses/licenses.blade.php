@extends('layouts.app')

@section('title', 'Licenses')

@section('content')
<link rel="stylesheet" href="{{ asset('css/licenses.css') }}">

<div class="header">
    <h2 class="title-header">Licenses</h2>
</div>

<div class="licenses-empty-card">
    Belum ada data lisensi untuk ditampilkan.
</div>
<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection