@extends('layouts.app')

@section('title', 'Merek')
@section('page-title', 'Merek')
@section('page-subtitle', 'Daftar merek perangkat aset')

@section('content')
<link rel="stylesheet" href="{{ asset('css/merek.css') }}">

@if(session('success'))
    <div class="merek-alert" role="status">{{ session('success') }}</div>
@endif

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
            <a href="{{ route('aset.merek.create') }}" class="merek-add-button">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Tambah Merek
            </a>
        </div>
    </div>

    <div class="merek-table-wrap">
        <table class="merek-table">
            <thead>
                <tr>
                    <th>ID Merek</th>
                    <th>Nama Merek</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($brands as $brand)
                    <tr class="hover:bg-gray-50">
                        <td>{{ $brand->id_merek }}</td>
                        <td class="merek-name">{{ $brand->nama_merek }}</td>
                        <td class="merek-notes" title="{{ $brand->keterangan }}">{{ $brand->keterangan ?: '-' }}</td>
                        <td class="merek-actions">
                            <a href="{{ route('aset.merek.edit', $brand->id_merek) }}" class="merek-edit-button">
                                <i class="fas fa-pen" aria-hidden="true"></i>
                                Edit
                            </a>
                            <form action="{{ route('aset.merek.destroy', $brand->id_merek) }}" method="POST" class="merek-delete-form" data-delete-confirm data-delete-name="merek {{ $brand->nama_merek }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="merek-delete-button">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="merek-empty">Belum ada data merek.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@include('components.delete-confirm-modal')
@endsection
