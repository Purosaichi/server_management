@extends('layouts.app')

@section('title', 'Detail Server - ' . $server['name'])

@section('page-title', 'Detail Server')
@section('page-subtitle', 'Informasi lengkap server ' . $server['name'])

@section('content')
<link rel="stylesheet" href="{{ asset('css/server-detail.css') }}">

{{-- Tombol Back --}}
<div class="back-wrapper">
    <a href="{{ route('server.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

{{-- Header Card --}}
<div class="header-card">
    <div class="header-left">
        <div class="server-icon">
            <svg viewBox="0 0 80 80" fill="none">
                <rect x="10" y="15" width="60" height="50" rx="4" fill="#374151"/>
                <rect x="15" y="20" width="50" height="15" rx="2" fill="#4b5563"/>
                <rect x="15" y="40" width="50" height="20" rx="2" fill="#4b5563"/>
                <circle cx="22" cy="27" r="2" fill="#16a34a"/>
                <circle cx="22" cy="47" r="2" fill="#16a34a"/>
                <circle cx="28" cy="27" r="2" fill="#6b7280"/>
                <circle cx="28" cy="47" r="2" fill="#6b7280"/>
                <circle cx="34" cy="27" r="2" fill="#6b7280"/>
                <circle cx="34" cy="47" r="2" fill="#6b7280"/>
                <rect x="55" y="23" width="8" height="2" fill="#2563eb"/>
                <rect x="55" y="27" width="8" height="2" fill="#2563eb"/>
                <rect x="55" y="43" width="8" height="2" fill="#2563eb"/>
                <rect x="55" y="47" width="8" height="2" fill="#2563eb"/>
            </svg>
        </div>
        
        <div>
            <span class="online-badge">{{ strtoupper($server['status']) }}</span>
            <h1 class="server-name">{{ $server['name'] }}</h1>
            <p class="server-subtitle">{{ $server['subtitle'] }}</p>
            
            <div class="info-grid">
                <span class="info-label">IP Address</span>
                <span class="info-value">{{ $server['ip_address'] ?? $server['ip'] ?? '-' }}</span>
                
                <span class="info-label">Hostname</span>
                <span class="info-value">{{ $server['hostname'] }}</span>
                
                <span class="info-label">Lokasi</span>
                <span class="info-value">{{ $server['location'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="header-stat">
        <div class="stat-label">Up Time</div>
        <div class="stat-value">{{ $server['uptime'] }}</div>
        <div class="stat-sub">Sejak {{ $server['uptime_since'] }}</div>
        <div style="margin-top: 1.5rem;">
            <div class="stat-label">Status Monitoring</div>
            <div class="stat-monitoring">{{ $server['status_monitoring'] }}</div>
        </div>
    </div>
    
    <div class="header-stat">
        <div class="stat-label">OS</div>
        <div class="stat-value-dark">{{ $server['os'] }}</div>
        <div style="margin-top: 1.5rem;">
            <div class="stat-label">Last Check</div>
            <div class="stat-value-dark">{{ $server['last_check'] }}</div>
            <div class="stat-sub">{{ $server['last_check_note'] }}</div>
        </div>
    </div>
</div>

{{-- Tab Navigasi --}}
<div class="tabs">
    <a href="#" class="tab active" onclick="showTab(event, 'overview')">Overview</a>
    <a href="#" class="tab" onclick="showTab(event, 'application')">Application</a>
    <a href="#" class="tab" onclick="showTab(event, 'history')">History</a>
</div>

{{-- Tab Overview --}}
<div id="tab-overview" class="tab-content active">
    <div class="content-grid">
        
        {{-- Kolom 1: Resource Utilization --}}
        <div class="column">
            <div class="card">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                    Resource Utilization
                </h3>
                
                <div class="gauges">
                    <div class="gauge">
                        <div class="gauge-circle">
                            <svg viewBox="0 0 80 80">
                                <circle class="gauge-bg" cx="40" cy="40" r="35"/>
                                <circle class="gauge-fill blue" cx="40" cy="40" r="35" 
                                        style="stroke-dashoffset: {{ 220 - (220 * $server['cpu_percent'] / 100) }}"/>
                            </svg>
                            <div class="gauge-text">{{ $server['cpu_percent'] }}%</div>
                        </div>
                        <div class="gauge-label">CPU</div>
                        <div class="gauge-detail">{{ $server['cpu_detail'] }}</div>
                    </div>
                    
                    <div class="gauge">
                        <div class="gauge-circle">
                            <svg viewBox="0 0 80 80">
                                <circle class="gauge-bg" cx="40" cy="40" r="35"/>
                                <circle class="gauge-fill yellow" cx="40" cy="40" r="35"
                                        style="stroke-dashoffset: {{ 220 - (220 * $server['memory_percent'] / 100) }}"/>
                            </svg>
                            <div class="gauge-text">{{ $server['memory_percent'] }}%</div>
                        </div>
                        <div class="gauge-label">MEMORY</div>
                        <div class="gauge-detail">{{ $server['memory_detail'] }}</div>
                    </div>
                    
                    <div class="gauge">
                        <div class="gauge-circle">
                            <svg viewBox="0 0 80 80">
                                <circle class="gauge-bg" cx="40" cy="40" r="35"/>
                                <circle class="gauge-fill red" cx="40" cy="40" r="35"
                                        style="stroke-dashoffset: {{ 220 - (220 * $server['storage_percent'] / 100) }}"/>
                            </svg>
                            <div class="gauge-text">{{ $server['storage_percent'] }}%</div>
                        </div>
                        <div class="gauge-label">STORAGE</div>
                        <div class="gauge-detail">{{ $server['storage_detail'] }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                    Riwayat Maintenance
                </h3>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Maintenance</th>
                            <th>PIC</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($server['maintenance_history'] as $history)
                        <tr>
                            <td>{{ $history['tanggal'] }}</td>
                            <td>{{ $history['jenis'] }}</td>
                            <td>{{ $history['pic'] }}</td>
                            <td class="status-completed">{{ $history['status'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Kolom 2: Informasi Dasar + Spesifikasi Hardware --}}
        <div class="column">
            <div class="card">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4M12 8h.01"/>
                    </svg>
                    Informasi Dasar
                </h3>
                <div class="info-list">
                    <div class="info-row"><span class="info-label">Server Type</span><span class="info-value">{{ $server['server_type'] }}</span></div>
                    <div class="info-row"><span class="info-label">Server Role</span><span class="info-value">{{ $server['server_role'] }}</span></div>
                    <div class="info-row"><span class="info-label">Manufacture</span><span class="info-value">{{ $server['manufacture'] }}</span></div>
                    <div class="info-row"><span class="info-label">Model</span><span class="info-value">{{ $server['model'] }}</span></div>
                    <div class="info-row"><span class="info-label">Serial Number</span><span class="info-value">{{ $server['serial_number'] }}</span></div>
                    <div class="info-row"><span class="info-label">Purchase Date</span><span class="info-value">{{ $server['purchase_date'] }}</span></div>
                    <div class="info-row"><span class="info-label">Warranty</span><span class="info-value">{{ $server['warranty'] }}</span></div>
                    <div class="info-row"><span class="info-label">Status</span><span class="info-value">{{ $server['server_status'] }}</span></div>
                </div>
            </div>
            
            <div class="card">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                        <path d="M9 9h6v6H9z"/>
                    </svg>
                    Spesifikasi Hardware
                </h3>
                <div class="info-list">
                    <div class="info-row"><span class="info-label">CPU</span><span class="info-value">{{ $server['cpu_spec'] }}</span></div>
                    <div class="info-row"><span class="info-label">RAM</span><span class="info-value">{{ $server['ram_spec'] }}</span></div>
                    <div class="info-row"><span class="info-label">STORAGE</span><span class="info-value">{{ $server['storage_spec'] }}</span></div>
                </div>
            </div>
        </div>
        
        {{-- Kolom 3: Informasi Jaringan + Catatan + Riwayat --}}
        <div class="column">
            <div class="card">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12.55a11 11 0 0114.08 0M1.42 9a16 16 0 0121.16 0M8.53 16.11a6 6 0 016.95 0M12 20h.01"/>
                    </svg>
                    Informasi Jaringan
                </h3>
                <div class="info-list">
                    <div class="info-row"><span class="info-label">IP Address</span><span class="info-value">{{ $server['ip_address'] ?? $server['ip'] ?? '-' }}</span></div>
                    <div class="info-row"><span class="info-label">Subnet Mask</span><span class="info-value">{{ $server['subnet_mask'] }}</span></div>
                    <div class="info-row"><span class="info-label">Gateway</span><span class="info-value">{{ $server['gateway'] }}</span></div>
                    <div class="info-row"><span class="info-label">DNS Server</span><span class="info-value">{{ $server['dns_server'] }}</span></div>
                    <div class="info-row"><span class="info-label">MAC Address</span><span class="info-value">{{ $server['mac_address'] }}</span></div>
                    <div class="info-row"><span class="info-label">Speed</span><span class="info-value">{{ $server['speed'] }}</span></div>
                    <div class="info-row"><span class="info-label">Network Usage</span><span class="info-value">↓ {{ $server['network_usage_down'] }} ↑ {{ $server['network_usage_up'] }}</span></div>
                </div>
            </div>
            
            <div class="card">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                    </svg>
                    Catatan Terakhir
                </h3>
                <div class="note">{{ $server['last_note'] }}</div>
                <div class="note-by">
                    Ditambahkan oleh <strong>{{ $server['last_note_by'] }}</strong><br>
                    {{ $server['last_note_date'] }}
                </div>
            </div>
            
        </div>
    </div>
</div>

{{-- Tab Application --}}
<div id="tab-application" class="tab-content">
    <div class="card">
        <h3 class="card-title">Application</h3>
        <p style="color: #6b7280; font-size: 0.875rem;">Belum ada aplikasi untuk server ini.</p>
    </div>
</div>

{{-- Tab History --}}
<div id="tab-history" class="tab-content">
    <div class="card">
        <h3 class="card-title">History</h3>
        <p style="color: #6b7280; font-size: 0.875rem;">Belum ada riwayat untuk server ini.</p>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>

<script>
    function showTab(event, tabName) {
        event.preventDefault();
        
        // Sembunyikan semua tab content
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.remove('active');
        });
        
        // Tampilkan tab yang dipilih
        document.getElementById('tab-' + tabName).classList.add('active');
        
        // Update class active
        document.querySelectorAll('.tab').forEach(el => {
            el.classList.remove('active');
        });
        event.target.classList.add('active');
    }
</script>
@endsection