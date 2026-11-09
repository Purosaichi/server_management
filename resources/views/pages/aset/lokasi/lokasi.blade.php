@extends('layouts.app')

@section('title', 'Lokasi Aset')
@section('page-title', 'Lokasi Aset')
@section('page-subtitle', 'Daftar lokasi penyimpanan dan penempatan aset')

@section('content')
<div class="rounded-lg border border-gray-200 bg-white px-6 py-10 text-center">
    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
        <i class="fas fa-location-dot text-xl" aria-hidden="true"></i>
    </div>
    <h1 class="text-lg font-semibold text-gray-900">Halaman Lokasi Aset</h1>
    <p class="mt-2 text-sm text-gray-500">Data lokasi aset akan ditampilkan di sini.</p>
</div>
@endsection