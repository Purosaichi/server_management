@extends('layouts.app')

@section('title', 'Merek')
@section('page-title', 'Merek')
@section('page-subtitle', 'Daftar merek perangkat aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/merek.css') }}">

<div class="merek-page">
    <div class="merek-header">
        <div class="merek-heading">
            <span class="merek-mark" aria-hidden="true"><i class="fas fa-tag"></i></span>
            <div>
                <span class="merek-eyebrow">Inventaris Infrastruktur</span>
                <h1 class="merek-title">Merek</h1>
                <p class="merek-subtitle">Daftar merek perangkat aset</p>
            </div>
        </div>
        <div class="merek-header-actions">
            <div class="merek-count" aria-label="Jumlah merek">
                <i class="fas fa-tag" aria-hidden="true"></i>
                <strong>{{ $brands->count() }}</strong>
                <span>merek</span>
            </div>
        </div>
    </div>

    <div class="merek-table-wrap">
        <table class="merek-table">
            <thead>
                <tr>
                    <th>ID Merek</th>
                    <th>Nama Merek</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($brands as $brand)
                    <tr class="hover:bg-gray-50">
                        <td>{{ $brand->id_merek }}</td>
                        <td class="merek-name">{{ $brand->nama_merek }}</td>
                        <td class="merek-notes" title="{{ $brand->keterangan }}">{{ $brand->keterangan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="merek-empty">Belum ada data merek.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
