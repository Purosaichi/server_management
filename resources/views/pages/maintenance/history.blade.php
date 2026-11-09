@extends('layouts.app')

@section('title', 'Riwayat Maintenance')
@section('page-title', 'Riwayat Maintenance')
@section('page-subtitle', 'Daftar maintenance yang telah dihapus dari daftar aktif')

@section('content')
<link rel="stylesheet" href="{{ asset('css/maintenance.css') }}">

<div class="header">
    <h2 class="title-header">Riwayat Maintenance</h2>
    <a href="{{ route('maintenance.maintenance') }}" class="mt-btn-history">
        <i class="fas fa-arrow-left" aria-hidden="true"></i>
        Kembali
    </a>
</div>

<div class="mt-table-container" style="margin-top: 1.5rem;">
    <div class="mt-table-header">
        <h3 class="mt-table-title">Maintenance Terhapus</h3>
        <span>{{ $maintenances->count() }} data</span>
    </div>
    <div class="mt-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Target</th>
                    <th>Jenis Maintenance</th>
                    <th>Jadwal</th>
                    <th>PIC</th>
                    <th>Status</th>
                    <th>Dihapus Pada</th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances as $maintenance)
                    @php
                        $target = match ($maintenance->target_type) {
                            'Server' => $maintenance->nama_server,
                            'Application' => $maintenance->nama_aplikasi,
                            default => null,
                        };
                    @endphp
                    <tr>
                        <td><span class="mt-id">{{ $maintenance->kode_maintenance ?? $maintenance->id_maintenance }}</span></td>
                        <td>
                            <div>{{ $target ?? '-' }}</div>
                            <div class="mt-time">{{ $maintenance->target_type ?? '-' }}</div>
                        </td>
                        <td>{{ $maintenance->jenis_maintenance ?? '-' }}</td>
                        <td>
                            <div>{{ $maintenance->jadwal_tanggal ? \Carbon\Carbon::parse($maintenance->jadwal_tanggal)->format('d M Y') : '-' }}</div>
                            <div class="mt-time">{{ $maintenance->jam_mulai ? substr($maintenance->jam_mulai, 0, 5) : '-' }}{{ $maintenance->jam_selesai ? ' - ' . substr($maintenance->jam_selesai, 0, 5) : '' }}</div>
                        </td>
                        <td>{{ $maintenance->nama_pic ?? '-' }}</td>
                        <td><span class="mt-status mt-status-completed">{{ $maintenance->status }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($maintenance->deleted_at)->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #9ca3af; padding: 2rem;">
                            Belum ada maintenance yang dihapus.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection