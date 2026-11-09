@extends('layouts.app')

@section('title', 'Riwayat Alerts')
@section('page-title', 'Riwayat Alerts')
@section('page-subtitle', 'Daftar alert yang telah diarsipkan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/alerts.css') }}">

<div class="alerts-header">
    <h1>Riwayat Alerts</h1>
    <p>Alert yang di-clear tetap tersimpan dan dapat dilihat di sini.</p>
</div>

<div class="alerts-table-container">
    <div class="alerts-table-header">
        <h3 class="alerts-table-title">Alert Terarsip</h3>
        <a href="{{ route('alerts.alerts') }}" class="alerts-history-button">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            Kembali ke Alerts
        </a>
    </div>
    <div class="alerts-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Level</th>
                    <th>Target</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>PIC</th>
                    <th>Diarsipkan Pada</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alerts as $alert)
                    @php
                        $target = match ($alert->target_type) {
                            'Server' => $alert->nama_server,
                            'Application' => $alert->nama_aplikasi,
                            'Domain' => $alert->nama_domain,
                            default => null,
                        };
                    @endphp
                    <tr>
                        <td>
                            <div>{{ \Carbon\Carbon::parse($alert->waktu_mulai)->format('d M Y') }}</div>
                            <div class="alerts-time">{{ \Carbon\Carbon::parse($alert->waktu_mulai)->format('H:i') }}{{ $alert->waktu_selesai ? ' - ' . \Carbon\Carbon::parse($alert->waktu_selesai)->format('H:i') : '' }}</div>
                        </td>
                        <td><span class="alerts-badge alerts-badge-{{ strtolower($alert->level) }}">{{ $alert->level }}</span></td>
                        <td>
                            <div>{{ $target ?? '-' }}</div>
                            <div class="alerts-time">{{ $alert->target_type }}</div>
                        </td>
                        <td>
                            <div>{{ $alert->deskripsi }}</div>
                            @if($alert->keterangan)
                                <div class="alerts-desc-sub">{{ $alert->keterangan }}</div>
                            @endif
                        </td>
                        <td>{{ $alert->status }}</td>
                        <td>{{ $alert->nama_pic ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($alert->deleted_at)->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #9ca3af; padding: 2rem;">
                            Belum ada alert yang diarsipkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="alerts-table-footer">{{ $alerts->count() }} data</div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection