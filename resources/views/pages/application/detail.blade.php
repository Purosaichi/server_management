@extends('layouts.app')

@section('title', 'Detail Aplikasi - ' . $application['name'])

@section('page-title', 'Detail Aplikasi')
@section('page-subtitle', 'Informasi lengkap aplikasi ' . $application['name'])

@section('content')
<link rel="stylesheet" href="{{ asset('css/application-detail.css') }}">

{{-- Tombol Back --}}
<div class="back-wrapper">
    <a href="{{ route('application.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

{{-- Header Card --}}
<div class="header-card">
    <div class="header-left">
        <div class="app-icon">
            <svg viewBox="0 0 60 60" fill="none">
                <circle cx="30" cy="20" r="8" fill="white"/>
                <path d="M15 45c0-8 7-14 15-14s15 6 15 14" stroke="white" stroke-width="3" fill="none"/>
                <circle cx="45" cy="18" r="6" fill="white" opacity="0.7"/>
                <path d="M38 40c0-5 4-9 9-9s9 4 9 9" stroke="white" stroke-width="2.5" fill="none" opacity="0.7"/>
            </svg>
        </div>
        
        <div>
            <h1 class="app-name">
                {{ $application['name'] }}
                @if($application['status'] === 'Aktif')
                    <span class="running-badge">RUNNING</span>
                @endif
            </h1>
            <p class="app-subtitle">Sistem management sumber daya guru</p>
            
            <div class="app-meta">
                <span>Versi {{ $application['version'] }}</span>
                <span class="meta-divider">|</span>
                <span>Kategori <span class="meta-badge">{{ $application['category'] }}</span></span>
                <span class="meta-divider">|</span>
                <span>SLA {{ $application['sla'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="header-stat">
        <div class="stat-label">Status Up Time</div>
        <div class="stat-value">{{ $application['uptime'] }}</div>
        <div class="stat-sub">{{ $application['uptime_period'] }}</div>
    </div>
    
    <div class="header-stat">
        <div class="stat-label">Sejak</div>
        <div class="stat-value-dark">{{ $application['since'] }}</div>
        <div class="stat-sub">{{ $application['since_duration'] }}</div>
    </div>
</div>

{{-- Baris 1: Informasi Aplikasi, Server, Domain, Licenses --}}
<div class="content-grid-4">
    
    {{-- Informasi Aplikasi --}}
    <div class="card">
        <h3 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
            Informasi Aplikasi
        </h3>
        <div class="info-list">
            <div class="info-row"><span class="info-label">Nama Aplikasi</span><span class="info-value">{{ $application['name'] }}</span></div>
            <div class="info-row"><span class="info-label">Kode Aplikasi</span><span class="info-value">{{ $application['code'] }}</span></div>
            <div class="info-row"><span class="info-label">Kategori</span><span class="info-value">{{ $application['category'] }}</span></div>
            <div class="info-row"><span class="info-label">Deskripsi</span><span class="info-value">{{ $application['description'] }}</span></div>
        </div>
    </div>
    
    {{-- Server & Infrastruktur --}}
    <div class="card">
        <h3 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2"/>
                <path d="M8 21h8M12 17v4"/>
            </svg>
            Server & Infrastruktur
        </h3>
        <div class="info-list">
            <div class="info-row"><span class="info-label">Server</span><span class="info-value link">{{ $application['server'] }}</span></div>
            <div class="info-row"><span class="info-label">IP Address</span><span class="info-value">{{ $application['ip_address'] }}</span></div>
            <div class="info-row"><span class="info-label">OS</span><span class="info-value">{{ $application['os'] }}</span></div>
            <div class="info-row"><span class="info-label">Database</span><span class="info-value">{{ $application['database'] }}</span></div>
        </div>
    </div>
    
    {{-- Domain --}}
    <div class="card">
        <h3 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M2 12h20M12 2a15 15 0 010 20M12 2a15 15 0 000 20"/>
            </svg>
            Domain
        </h3>
        <div class="info-list">
            <div class="info-row"><span class="info-label">Domain utama</span><span class="info-value link">{{ $application['domain'] }}</span></div>
            <div class="info-row">
                <span class="info-label">SSL Certificate</span>
                <span class="info-value">
                    <span class="badge {{ $application['ssl_certificate'] == 'VALID' ? 'badge-success' : 'badge-danger' }}">
                        {{ $application['ssl_certificate'] }}
                    </span>
                </span>
            </div>
            <div class="info-row"><span class="info-label">SSL Expired</span><span class="info-value">{{ $application['ssl_expired'] }}</span></div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    <span class="status-dot {{ $application['domain_status'] == 'Aktif' ? 'active' : 'inactive' }}">
                        {{ $application['domain_status'] }}
                    </span>
                </span>
            </div>
        </div>
    </div>
    
    {{-- Licenses --}}
    <div class="card">
        <h3 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Licenses
        </h3>
        <div class="info-list">
            <div class="info-row"><span class="info-label">Nama Lisensi</span><span class="info-value">{{ $application['license_name'] }}</span></div>
            <div class="info-row"><span class="info-label">Provider</span><span class="info-value">{{ $application['license_provider'] }}</span></div>
            <div class="info-row"><span class="info-label">Jumlah Lisensi</span><span class="info-value">{{ $application['license_count'] }}</span></div>
            <div class="info-row">
                <span class="info-label">Tanggal Expired</span>
                <span class="info-value" style="color: #d97706;">
                    {{ $application['license_expired'] }}
                    <span style="font-size: 0.7rem;">{{ $application['license_expired_note'] }}</span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    <span class="badge badge-warning">{{ $application['license_status'] }}</span>
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Baris 2: Pengurus, Maintenance, Riwayat --}}
<div class="content-grid-3">
    
    {{-- Pengurus / PIC --}}
    <div class="card">
        <h3 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Pengurus / PIC
        </h3>
        <div class="info-list">
            <div class="info-row"><span class="info-label">Nama</span><span class="info-value">{{ $application['pic_name'] }}</span></div>
            <div class="info-row"><span class="info-label">Jabatan</span><span class="info-value">{{ $application['pic_jabatan'] }}</span></div>
            <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $application['pic_email'] }}</span></div>
            <div class="info-row"><span class="info-label">No. Telepon</span><span class="info-value">{{ $application['pic_telepon'] }}</span></div>
            <div class="info-row"><span class="info-label">Divisi</span><span class="info-value">{{ $application['pic_divisi'] }}</span></div>
        </div>
    </div>
    
    {{-- Maintenance --}}
    <div class="card">
        <h3 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
            </svg>
            Maintenance
        </h3>
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            
            {{-- Maintenance Terakhir --}}
            @if($application['last_maintenance'])
            <div class="maintenance-box">
                <div class="maintenance-box-title">Maintenance Terakhir</div>
                <div class="info-list">
                    <div class="info-row"><span class="info-label">Tanggal</span><span class="info-value">{{ $application['last_maintenance']['tanggal'] }}</span></div>
                    <div class="info-row"><span class="info-label">Jenis</span><span class="info-value">{{ $application['last_maintenance']['jenis'] }}</span></div>
                    <div class="info-row"><span class="info-label">PIC</span><span class="info-value">{{ $application['last_maintenance']['pic'] }}</span></div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value"><span class="badge badge-success">{{ $application['last_maintenance']['status'] }}</span></span>
                    </div>
                </div>
            </div>
            @endif
            
            {{-- Maintenance Berikutnya --}}
            @if($application['next_maintenance'])
            <div class="maintenance-box">
                <div class="maintenance-box-title">Maintenance Berikutnya</div>
                <div class="info-list">
                    <div class="info-row"><span class="info-label">Tanggal</span><span class="info-value">{{ $application['next_maintenance']['tanggal'] }}</span></div>
                    <div class="info-row"><span class="info-label">Jenis</span><span class="info-value">{{ $application['next_maintenance']['jenis'] }}</span></div>
                    <div class="info-row"><span class="info-label">PIC</span><span class="info-value">{{ $application['next_maintenance']['pic'] }}</span></div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value"><span class="badge badge-info">{{ $application['next_maintenance']['status'] }}</span></span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    {{-- Riwayat Maintenance --}}
    <div class="card">
        <div class="history-header">Riwayat Maintenance</div>
        <table class="history-table">
            <tbody>
                @forelse($application['maintenance_history'] as $history)
                <tr>
                    <td class="history-date">{{ $history['tanggal'] }}</td>
                    <td class="history-jenis">{{ $history['jenis'] }}</td>
                    <td style="text-align: right;">
                        <span class="badge 
                            {{ $history['status'] == 'Completed' ? 'badge-success' : '' }}
                            {{ $history['status'] == 'Scheduled' ? 'badge-info' : '' }}
                            {{ $history['status'] == 'Canceled' ? 'badge-gray' : '' }}
                        ">
                            {{ $history['status'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #9ca3af; padding: 1rem;">
                        Belum ada riwayat maintenance
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if(count($application['maintenance_history']) > 0)
        <a href="#" class="link-all">Lihat Semua Riwayat</a>
        @endif
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection