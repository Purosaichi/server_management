@extends('layouts.app')

@section('title', 'Storage')
@section('page-title', 'Storage')
@section('page-subtitle', 'Daftar media dan kapasitas penyimpanan aset')

@section('content')
<div class="rounded-lg border border-gray-200 bg-white px-6 py-10 text-center">
    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
        <i class="fas fa-hard-drive text-xl" aria-hidden="true"></i>
    </div>
    <h1 class="text-lg font-semibold text-gray-900">Halaman Storage</h1>
    <p class="mt-2 text-sm text-gray-500">Data storage aset akan ditampilkan di sini.</p>
</div>
@endsection