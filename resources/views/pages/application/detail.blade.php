@extends('layouts.app')

@section('title', 'Detail Aplikasi - ' . $application['name'])

@section('page-title', 'Detail Aplikasi')
@section('page-subtitle', 'Informasi lengkap aplikasi ' . $application['name'])

@section('content')
<link rel="stylesheet" href="{{ asset('css/application-detail.css') }}">

<!-- /* Tombol Back */ -->
<div class="back-wrapper">
    <a href="{{ route('application.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

<!-- /* Header Card */ -->
<div class="header-card">
    <div class="header-left">
        <div class="app-icon">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.7"/>
                <path d="M3 9h18" stroke="currentColor" stroke-width="1.7"/>
                <circle cx="6.5" cy="6.5" r=".75" fill="currentColor"/>
                <circle cx="9.5" cy="6.5" r=".75" fill="currentColor"/>
                <rect x="6.5" y="12" width="4" height="5" rx=".75" fill="currentColor" fill-opacity=".9"/>
                <path d="M13 12h4.5M13 14.5h4.5M13 17h3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
        </div>
        
        <div>
            <h1 class="app-name">
                {{ $application['name'] }}
                @if($application['status'] === 'Aktif')
                    <span class="running-badge">RUNNING</span>
                @endif
            </h1>
            <p class="app-subtitle">{{ $application['description'] }}</p>
            
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

<div role="tablist" aria-label="Detail aplikasi" class="mb-5 flex flex-wrap gap-2 border-b border-gray-200">
    <button type="button" id="applicationTab" role="tab" aria-selected="true" aria-controls="applicationPanel"
            class="rounded-t-lg border-b-2 border-blue-600 px-4 py-3 text-sm font-semibold text-blue-700"
            onclick="showApplicationDetailTab('application')">
        Detail Aplikasi
    </button>
    <button type="button" id="domainTab" role="tab" aria-selected="false" aria-controls="domainPanel"
            class="rounded-t-lg border-b-2 border-transparent px-4 py-3 text-sm font-semibold text-gray-500 hover:text-blue-700"
            onclick="showApplicationDetailTab('domain')">
        Detail Domain
    </button>
    <button type="button" id="technologyTab" role="tab" aria-selected="false" aria-controls="technologyPanel"
            class="rounded-t-lg border-b-2 border-transparent px-4 py-3 text-sm font-semibold text-gray-500 hover:text-blue-700"
            onclick="showApplicationDetailTab('technology')">
        Teknologi Aplikasi
    </button>
    <button type="button" id="applicationUsersTab" role="tab" aria-selected="false" aria-controls="applicationUsersPanel"
            class="rounded-t-lg border-b-2 border-transparent px-4 py-3 text-sm font-semibold text-gray-500 hover:text-blue-700"
            onclick="showApplicationDetailTab('users')">
        Pengguna Aplikasi
    </button>
</div>

<div id="applicationPanel" role="tabpanel" aria-labelledby="applicationTab">
<!-- /* Baris 1: Informasi Aplikasi, Server, Domain, Licenses */ -->
<div class="content-grid-4">
    
    <!-- /* Informasi Aplikasi */ -->
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
        </div>
    </div>
    
    <!-- /* Server & Infrastruktur */ -->
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
    
    <!-- /* Domain */ -->
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
                    <span class="badge {{ $application['ssl_certificate'] == 'Valid' ? 'badge-success' : 'badge-danger' }}">
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
    
    <!-- /* Licenses */ -->
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
                    <span class="badge 
                        {{ $application['license_status'] == 'Aman' ? 'badge-success' : '' }}
                        {{ $application['license_status'] == 'Akan Expired' ? 'badge-warning' : '' }}
                        {{ $application['license_status'] == 'Expired' ? 'badge-danger' : '' }}
                    ">
                        {{ $application['license_status'] }}
                    </span>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- /* Baris 2: Pengurus, Maintenance, Riwayat */ -->
<div class="content-grid-3">
    
    <!-- /* Pengurus / PIC */ -->
    <div class="card" style="position: relative;">
        <h3 class="card-title">
            <svg ViewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Pengurus / PIC
        </h3>

        <button type="button" class="edit-pic-btn"
                onclick="openPicModal()"
                title="edit / Ganti PIC">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
        </button>

        <div class="info-list">
            @if($application['pic_name'] && $application['pic_name'] !== '-')
                <div class="info-row"><span class="info-label">Nama</span><span class="info-value">{{ $application['pic_name'] }}</span></div>
                <div class="info-row"><span class="info-label">Jabatan</span><span class="info-value">{{ $application['pic_jabatan'] }}</span></div>
                <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $application['pic_email'] }}</span></div>
                <div class="info-row"><span class="info-label">No. Telepon</span><span class="info-value">{{ $application['pic_telepon'] }}</span></div>
                <div class="info-row"><span class="info-label">Divisi</span><span class="info-value">{{ $application['pic_divisi'] }}</span></div>
            @else
                <div class="pic-empty">
                    Belum ada PIC. Klik tombol <strong>✏️</strong> di pojok kanan untuk menambahkan.
                </div>
            @endif
        </div>
    </div>

    <!-- /* Maintenance */ -->
    <div class="card" style="position: relative;">
        <div class="history-header" style="display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
            <span>Maintenance</span>
            <button type="button" class="add-maintenance-btn" onclick="openMaintenanceModal()" title="Tambah Jadwal Maintenance" style="position: static; width: 2rem; height: 2rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
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

            @php
                $nextMaintenanceEditable = isset($application['next_maintenance']['status']) && in_array($application['next_maintenance']['status'], ['Scheduled', 'In Progress'], true);
            @endphp
            @if($application['next_maintenance'])
            <div class="maintenance-box {{ $nextMaintenanceEditable ? 'maintenance-box-clickable' : 'maintenance-box-disabled' }}"
                 @if($nextMaintenanceEditable)
                 ondblclick="editMaintenance(this)"
                 data-maintenance-id="{{ $application['next_maintenance']['id'] }}"
                 data-maintenance-type="{{ $application['next_maintenance']['jenis'] }}"
                 data-maintenance-date="{{ $application['next_maintenance']['tanggal_input'] }}"
                 data-maintenance-status="{{ $application['next_maintenance']['status'] }}"
                 data-maintenance-pic="{{ $application['next_maintenance']['id_pic'] ?? '' }}"
                 title="Double click untuk edit"
                 @else
                 title="Maintenance yang sudah Completed atau Canceled tidak bisa diedit"
                 @endif>
                <div class="maintenance-box-title">
                    Maintenance Berikutnya
                    @if($nextMaintenanceEditable)
                    @endif
                </div>
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
            @else
            <div class="maintenance-box-empty">
                Belum ada jadwal maintenance berikutnya.
            </div>
            @endif
        </div>
    </div>

    <!-- /* Riwayat Maintenance */ -->
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
                            {{ $history['status'] == 'In Progress' ? 'badge-warning' : '' }}
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
    </div>
</div>

</div>

<div id="domainPanel" role="tabpanel" aria-labelledby="domainTab" hidden>
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-800">Domain {{ $application['name'] }}</h2>
        <p class="mt-1 text-sm text-gray-500">Domain yang terhubung dengan aplikasi ini.</p>
    </div>
    @forelse($domainDetails as $domain)
        <section class="mb-8">
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-lg font-bold text-gray-800">{{ $domain['domain'] }}</h3>
                <a href="{{ route('domain.edit', $domain['id']) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <i class="fas fa-pen-to-square" aria-hidden="true"></i>
                    Edit Detail Domain
                </a>
            </div>
            <div class="mb-5 grid gap-5 xl:grid-cols-[2fr_1fr]">
                <div class="grid gap-5 rounded-xl border border-gray-100 bg-white p-6 shadow-sm sm:grid-cols-2 lg:grid-cols-5 lg:items-center">
                    <div class="flex items-center gap-4 sm:col-span-2 lg:col-span-1">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white">
                            <i class="fas fa-globe text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $domain['domain'] }}</h3>
                            <span class="mt-1 inline-flex rounded bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">{{ $domain['status'] }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Purchase date</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">{{ $domain['purchase_date'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Expiration date</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">{{ $domain['expiration_date'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Auto renewal</p>
                        <p class="mt-1 text-sm font-semibold {{ $domain['auto_renewal'] === 'Active' ? 'text-green-700' : 'text-red-700' }}">{{ $domain['auto_renewal'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Domain status</p>
                        <p class="mt-1 text-sm font-semibold {{ $domain['domain_status'] === 'Normal' ? 'text-green-700' : 'text-red-700' }}">{{ $domain['domain_status'] }}</p>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-800">
                        <i class="fas fa-user text-gray-500"></i>
                        Person In Charge (PIC)
                    </h3>
                    <dl class="space-y-2 text-sm">
                        <div class="grid grid-cols-[100px_1fr] gap-2"><dt class="text-gray-500">Nama</dt><dd class="font-medium text-gray-800">{{ $domain['pic_name'] }}</dd></div>
                        <div class="grid grid-cols-[100px_1fr] gap-2"><dt class="text-gray-500">Jabatan</dt><dd class="font-medium text-gray-800">{{ $domain['pic_jabatan'] }}</dd></div>
                        <div class="grid grid-cols-[100px_1fr] gap-2"><dt class="text-gray-500">Email</dt><dd class="break-all font-medium text-gray-800">{{ $domain['pic_email'] }}</dd></div>
                        <div class="grid grid-cols-[100px_1fr] gap-2"><dt class="text-gray-500">No. Telepon</dt><dd class="font-medium text-gray-800">{{ $domain['pic_telepon'] }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="mb-5 grid gap-5 lg:grid-cols-3">
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-800"><i class="fas fa-shield-alt text-gray-500"></i>SSL Certificate</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Provider</dt><dd class="text-right font-medium text-gray-800">{{ $domain['ssl_provider'] }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Status</dt><dd class="font-medium {{ $domain['ssl_status'] === 'Valid' ? 'text-green-700' : 'text-red-700' }}">{{ $domain['ssl_status'] }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Issued date</dt><dd class="text-right font-medium text-gray-800">{{ $domain['ssl_issued_date'] }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Expiration date</dt><dd class="text-right font-medium text-gray-800">{{ $domain['ssl_expiration_date'] }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Certificate type</dt><dd class="text-right font-medium text-gray-800">{{ $domain['ssl_certificate_type'] }}</dd></div>
                    </dl>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between gap-2">
                        <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-800"><i class="fas fa-credit-card text-gray-500"></i>Billing Information</h3>
                        <span class="text-xs text-gray-500">Status: <strong class="{{ $domain['billing_status'] === 'Paid' ? 'text-green-700' : 'text-red-700' }}">{{ $domain['billing_status'] }}</strong></span>
                    </div>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Billing cycle</dt><dd class="text-right font-medium text-gray-800">{{ $domain['billing_cycle'] }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Last payment</dt><dd class="text-right font-medium text-gray-800">{{ $domain['last_payment'] }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Next due date</dt><dd class="text-right font-medium text-gray-800">{{ $domain['next_due_date'] }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Amount</dt><dd class="text-right font-medium text-gray-800">{{ $domain['amount'] }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-gray-500">Payment method</dt><dd class="text-right font-medium text-gray-800">{{ $domain['payment_method'] }}</dd></div>
                    </dl>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-800"><i class="fas fa-bell text-gray-500"></i>Upcoming Reminders</h3>
                    <div class="space-y-3">
                        @forelse($domain['reminders'] as $reminder)
                            <div class="rounded-lg {{ $reminder['type'] === 'warning' ? 'bg-amber-50' : 'bg-blue-50' }} p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $reminder['title'] }}</p>
                                        <p class="mt-1 text-xs text-gray-600">{{ $reminder['description'] }}</p>
                                    </div>
                                    <span class="shrink-0 text-xs font-medium text-gray-500">{{ $reminder['date'] }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">Tidak ada reminder.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <h3 class="border-b border-gray-100 px-5 py-4 text-sm font-semibold text-gray-800">Renewal / Activity History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Aktifitas</th>
                                <th class="px-5 py-3">PIC</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse($domain['activity_history'] as $history)
                                <tr>
                                    <td class="whitespace-nowrap px-5 py-3">{{ $history['tanggal'] }}</td>
                                    <td class="px-5 py-3">{{ $history['aktifitas'] }}</td>
                                    <td class="px-5 py-3">{{ $history['pic'] }}</td>
                                    <td class="px-5 py-3">{{ $history['status'] }}</td>
                                    <td class="px-5 py-3">{{ $history['catatan'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-6 text-center text-gray-400">Belum ada aktivitas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @empty
        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center text-sm text-gray-500">
            Belum ada domain yang terhubung dengan aplikasi ini.
        </div>
    @endforelse
</div>

@include('pages.application.technology', [
    'application' => $application,
    'applicationTechnologies' => $applicationTechnologies,
])
@include('pages.application.application-users', [
    'application' => $application,
    'applicationUsers' => $applicationUsers,
])

<!-- /* MODAL EDIT / TAMBAH PIC */ -->
<div id="picModal" class="modal-overlay" style="display: none;">
    <div class="modal-box">
        <h3 class="modal-title" id="picModalTitle">Edit PIC Aplikasi</h3>

        <div class="pic-tabs">
            <button type="button" class="pic-tab active" id="tabExisting" onclick="switchPicTab('existing')">
                Pilih PIC
            </button>
            <button type="button" class="pic-tab" id="tabNew" onclick="switchPicTab('new')">
                + PIC Baru
            </button>
        </div>

        <form id="picFormExisting" method="POST" action="{{ route('application.update-pic', $application['id']) }}">
            @csrf
            @method('PUT')

            <div class="modal-form-group">
                <label for="existing_pic_id">Pilih PIC <span style="color: red;">*</span></label>
                <select name="id_pic" id="existing_pic_id" required>
                    <option value="">-- Pilih PIC --</option>
                    @foreach($application['pics'] as $picId => $picName)
                        <option value="{{ $picId }}"
                            {{ ($application['pic_name'] ?? '') === $picName ? 'selected' : '' }}>
                            {{ $picName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-btn-cancel" onclick="closePicModal()">Batal</button>
                <button type="submit" class="modal-btn-submit">Simpan</button>
            </div>
        </form>

        <form id="picFormNew" method="POST" action="{{ route('application.store-pic') }}" style="display: none;">
            @csrf
            <input type="hidden" name="id_aplikasi" value="{{ $application['id'] }}">

            <div class="modal-form-group">
                <label for="nama_pic">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" name="nama_pic" id="nama_pic" placeholder="Contoh: Budi Santoso" required>
            </div>

            <div class="modal-form-group">
                <label for="jabatan">Jabatan</label>
                <input type="text" name="jabatan" id="jabatan" placeholder="Contoh: System Engineer">
            </div>

            <div class="modal-form-group">
                <label for="divisi">Divisi</label>
                <input type="text" name="divisi" id="divisi" placeholder="Contoh: IT Infrastructure">
            </div>

            <div class="modal-form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Contoh: budi@kemendik.go.id">
            </div>

            <div class="modal-form-group">
                <label for="telepon">No. Telepon</label>
                <input type="text" name="telepon" id="telepon" placeholder="Contoh: +62 812 0000 0003">
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-btn-cancel" onclick="closePicModal()">Batal</button>
                <button type="submit" class="modal-btn-submit">Tambah PIC</button>
            </div>
        </form>
    </div>
</div>

<!-- /* MODAL TAMBAH/EDIT MAINTENANCE */ -->
<div id="maintenanceModal" class="modal-overlay" style="display: none;">
    <div class="modal-box">
        <h3 class="modal-title" id="maintenanceModalTitle">Tambah Jadwal Maintenance</h3>
        
        <form id="maintenanceForm" method="POST" action="{{ route('maintenance.store') }}">
            @csrf
            <input type="hidden" name="id_aplikasi" value="{{ $application['id'] }}">
            <input type="hidden" name="id_maintenance" id="editMaintenanceId" value="">
            <input type="hidden" name="target_type" value="Application">
            
            <div class="modal-form-group">
                <label for="jenis_maintenance">Jenis Maintenance <span style="color: red;">*</span></label>
                <input type="text" name="jenis_maintenance" id="jenis_maintenance" 
                       placeholder="Contoh: Update & Patch" required>
            </div>

            <div class="modal-form-group">
                <label for="maintenance_pic_id">PIC</label>
                <select name="id_pic" id="maintenance_pic_id">
                    <option value="">-- Pilih PIC --</option>
                    @foreach($application['pics'] as $picId => $picName)
                        <option value="{{ $picId }}">{{ $picName }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="modal-form-group">
                <label for="jadwal_tanggal">Tanggal <span style="color: red;">*</span></label>
                <input type="date" name="jadwal_tanggal" id="jadwal_tanggal" required>
            </div>
            
            <div class="modal-form-row">
                <div class="modal-form-group">
                    <label for="jam_mulai">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai">
                </div>
                <div class="modal-form-group">
                    <label for="jam_selesai">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai">
                </div>
            </div>
            
            <div class="modal-form-group">
                <label for="status">Status <span style="color: red;">*</span></label>
                <select name="status" id="status" required>
                    <option value="Scheduled">Scheduled</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Canceled">Canceled</option>
                </select>
            </div>
            
            <div class="modal-form-group">
                <label for="catatan">Catatan</label>
                <textarea name="catatan" id="catatan" rows="2" 
                          placeholder="Catatan tambahan..."></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="modal-btn-cancel" onclick="closeMaintenanceModal()">
                    Batal
                </button>
                <button type="submit" class="modal-btn-submit" id="maintenanceSubmitBtn">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>

<script>
function showApplicationDetailTab(tab) {
    const tabs = {
        application: ['applicationTab', 'applicationPanel'],
        domain: ['domainTab', 'domainPanel'],
        technology: ['technologyTab', 'technologyPanel'],
        users: ['applicationUsersTab', 'applicationUsersPanel'],
    };

    Object.entries(tabs).forEach(([name, [tabId, panelId]]) => {
        const selected = name === tab;
        const tabElement = document.getElementById(tabId);
        const panelElement = document.getElementById(panelId);

        tabElement.setAttribute('aria-selected', String(selected));
        panelElement.hidden = !selected;
        tabElement.classList.toggle('border-blue-600', selected);
        tabElement.classList.toggle('text-blue-700', selected);
        tabElement.classList.toggle('border-transparent', !selected);
        tabElement.classList.toggle('text-gray-500', !selected);
    });

    const url = new URL(window.location.href);
    if (tab === 'application') {
        url.searchParams.delete('tab');
    } else {
        url.searchParams.set('tab', tab);
    }
    window.history.replaceState({}, '', url);
}


        /* MODAL PIC */
    function openPicModal() {
        const modal = document.getElementById('picModal');
        if (!modal) return;

        modal.style.display = 'flex';
        switchPicTab('existing');
    }

    function closePicModal() {
        const modal = document.getElementById('picModal');
        if (!modal) return;

        modal.style.display = 'none';
        const formNew = document.getElementById('picFormNew');
        if (formNew) formNew.reset();
    }

    function switchPicTab(tab) {
        const tabExisting = document.getElementById('tabExisting');
        const tabNew = document.getElementById('tabNew');
        const formExisting = document.getElementById('picFormExisting');
        const formNew = document.getElementById('picFormNew');

        if (!tabExisting || !tabNew || !formExisting || !formNew) return;

        if (tab === 'existing') {
            tabExisting.classList.add('active');
            tabNew.classList.remove('active');
            formExisting.style.display = 'block';
            formNew.style.display = 'none';
        } else {
            tabNew.classList.add('active');
            tabExisting.classList.remove('active');
            formNew.style.display = 'block';
            formExisting.style.display = 'none';
        }
    }

    const picModal = document.getElementById('picModal');
    if (picModal) {
        picModal.addEventListener('click', function(e) {
            if (e.target === this) closePicModal();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const picModalOpen = document.getElementById('picModal');
            if (picModalOpen && picModalOpen.style.display === 'flex') closePicModal();
            const maintenanceModalOpen = document.getElementById('maintenanceModal');
            if (maintenanceModalOpen && maintenanceModalOpen.style.display === 'flex') closeMaintenanceModal();
        }
    });

    /* MODAL MAINTENANCE */
    function openMaintenanceModal() {
        document.getElementById('maintenanceModalTitle').textContent = 'Tambah Jadwal Maintenance';
        document.getElementById('maintenanceSubmitBtn').textContent = 'Simpan';
        document.getElementById('editMaintenanceId').value = '';
        document.getElementById('jenis_maintenance').value = '';
        document.getElementById('maintenance_pic_id').value = '';
        document.getElementById('jadwal_tanggal').value = '';
        document.getElementById('jam_mulai').value = '';
        document.getElementById('jam_selesai').value = '';
        document.getElementById('status').value = 'Scheduled';
        document.getElementById('catatan').value = '';
        
        /* Ubah action ke store */
        document.getElementById('maintenanceForm').action = '{{ route('maintenance.store') }}';
        
        /* Hapus method PUT kalo ada */
        const methodInput = document.querySelector('#maintenanceForm input[name="_method"]');
        if (methodInput) methodInput.remove();
        
        document.getElementById('maintenanceModal').style.display = 'flex';
    }
    
    function editMaintenance(card) {
        const { maintenanceId, maintenanceType, maintenanceDate, maintenanceStatus, maintenancePic } = card.dataset;
        document.getElementById('maintenanceModalTitle').textContent = 'Edit Jadwal Maintenance';
        document.getElementById('maintenanceSubmitBtn').textContent = 'Update';
        document.getElementById('editMaintenanceId').value = maintenanceId;
        document.getElementById('jenis_maintenance').value = maintenanceType;
        document.getElementById('maintenance_pic_id').value = maintenancePic || '';
        document.getElementById('status').value = maintenanceStatus;
        document.getElementById('jadwal_tanggal').value = maintenanceDate;
        
        /* Ubah action ke update */
        document.getElementById('maintenanceForm').action = '/maintenance/' + maintenanceId;
        
        /* Tambah method PUT kalo belum ada */
        let methodInput = document.querySelector('#maintenanceForm input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            document.getElementById('maintenanceForm').appendChild(methodInput);
        }
        
        document.getElementById('maintenanceModal').style.display = 'flex';
    }
    
    function closeMaintenanceModal() {
        document.getElementById('maintenanceModal').style.display = 'none';
    }
    
    /* Tutup modal kalo klik di luar */
    document.getElementById('maintenanceModal').addEventListener('click', function(e) {
        if (e.target === this) closeMaintenanceModal();
    });
    
    /* Tutup modal kalo tekan ESC */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMaintenanceModal();
    });
</script>
@endsection