@extends('layouts.app')

@section('title', 'Detail Domain - ' . $domain['domain'])

@section('page-title', 'Detail Domain')
@section('page-subtitle', 'Informasi lengkap domain ' . $domain['domain'])

@section('content')
<link rel="stylesheet" href="{{ asset('css/domain-detail.css') }}">

{{-- Tombol Back --}}
<div class="back-wrapper">
    <a href="{{ route('domain.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

{{-- Header Row --}}
<div class="header-row">
    
    {{-- Header Card --}}
    <div class="header-card">
        <div class="domain-icon">
            <svg viewBox="0 0 50 50" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="25" cy="25" r="20"/>
                <path d="M5 25h40M25 5a30 30 0 010 40M25 5a30 30 0 000 40"/>
            </svg>
        </div>
        
        <div style="grid-column: span 2;">
            <div class="domain-name">
                {{ $domain['domain'] }}
                <span class="online-badge">{{ $domain['status'] }}</span>
            </div>
        </div>
        
        <div class="header-info">
            <div class="info-label">Purchase date</div>
            <div class="info-value">{{ $domain['purchase_date'] }}</div>
        </div>
        
        <div class="header-info">
            <div class="info-label">Expiration date</div>
            <div class="info-value">{{ $domain['expiration_date'] }}</div>
        </div>
        
        <div class="header-info">
            <div class="info-label">Auto renewal</div>
            <div class="status-dot {{ $domain['auto_renewal'] == 'Active' ? 'active' : 'inactive' }}">
                {{ $domain['auto_renewal'] }}
            </div>
        </div>
        
        <div class="header-info">
            <div class="info-label">Domain status</div>
            <div class="status-dot {{ $domain['domain_status'] == 'Normal' ? 'active' : 'inactive' }}">
                {{ $domain['domain_status'] }}
            </div>
        </div>
    </div>
    
    {{-- PIC Card --}}
    <div class="pic-card">
        <h3 class="pic-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Person In Charge (PIC)
        </h3>
        <div class="info-list">
            <div class="info-row"><span class="info-label">Nama</span><span class="info-value">{{ $domain['pic_name'] }}</span></div>
            <div class="info-row"><span class="info-label">Jabatan</span><span class="info-value">{{ $domain['pic_jabatan'] }}</span></div>
            <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $domain['pic_email'] }}</span></div>
            <div class="info-row"><span class="info-label">No. Telepon</span><span class="info-value">{{ $domain['pic_telepon'] }}</span></div>
        </div>
    </div>
</div>

{{-- Content Grid --}}
<div class="content-grid-3">
    
    {{-- SSL Certificate --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                SSL Certificate
            </h3>
        </div>
        <div class="info-list">
            <div class="info-row"><span class="info-label">Provider</span><span class="info-value">{{ $domain['ssl_provider'] }}</span></div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    <span class="status-dot {{ $domain['ssl_status'] == 'Valid' ? 'active' : 'inactive' }}">
                        {{ $domain['ssl_status'] }}
                    </span>
                </span>
            </div>
            <div class="info-row"><span class="info-label">Issued date</span><span class="info-value">{{ $domain['ssl_issued_date'] }}</span></div>
            <div class="info-row"><span class="info-label">Expiration date</span><span class="info-value">{{ $domain['ssl_expiration_date'] }}</span></div>
            <div class="info-row"><span class="info-label">Sertificate type</span><span class="info-value">{{ $domain['ssl_certificate_type'] }}</span></div>
        </div>
    </div>
    
    {{-- Billing Information --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                    <path d="M2 10h20"/>
                </svg>
                Billing Information
            </h3>
            <div class="card-status">
                Status: <span class="status-dot {{ $domain['billing_status'] == 'Paid' ? 'paid' : 'unpaid' }}">{{ $domain['billing_status'] }}</span>
            </div>
        </div>
        <div class="info-list">
            <div class="info-row"><span class="info-label">Billing cycle</span><span class="info-value">{{ $domain['billing_cycle'] }}</span></div>
            <div class="info-row"><span class="info-label">Last payment</span><span class="info-value">{{ $domain['last_payment'] }}</span></div>
            <div class="info-row"><span class="info-label">Next due date</span><span class="info-value">{{ $domain['next_due_date'] }}</span></div>
            <div class="info-row"><span class="info-label">Amount</span><span class="info-value">{{ $domain['amount'] }}</span></div>
            <div class="info-row"><span class="info-label">Payment method</span><span class="info-value">{{ $domain['payment_method'] }}</span></div>
        </div>
    </div>
    
    {{-- Upcoming Reminders --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
                Upcoming Reminders
            </h3>
        </div>
        @forelse($domain['reminders'] as $reminder)
        <div class="reminder-item">
            <div class="reminder-icon {{ $reminder['type'] }}">
                @if($reminder['type'] == 'warning')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4M12 8h.01"/>
                    </svg>
                @endif
            </div>
            <div class="reminder-content">
                <div class="reminder-title">{{ $reminder['title'] }}</div>
                <div class="reminder-desc">{{ $reminder['description'] }}</div>
            </div>
            <div class="reminder-date">{{ $reminder['date'] }}</div>
        </div>
        @empty
        <p style="color: #9ca3af; font-size: 0.8125rem;">Tidak ada reminder.</p>
        @endforelse
    </div>
</div>

{{-- Renewal / Activity History --}}
<div class="history-card">
    <h3 class="history-header">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 6v6l4 2"/>
        </svg>
        Renewal / Activity History
    </h3>
    <table class="history-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Aktifitas</th>
                <th>PIC</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($domain['activity_history'] as $history)
            <tr>
                <td>{{ $history['tanggal'] }}</td>
                <td>{{ $history['aktifitas'] }}</td>
                <td>{{ $history['pic'] }}</td>
                <td><span class="badge badge-success">{{ $history['status'] }}</span></td>
                <td>{{ $history['catatan'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #9ca3af;">Belum ada aktivitas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>
@endsection