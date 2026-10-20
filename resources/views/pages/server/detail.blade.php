@extends('layouts.app')

@section('title', 'Detail Server - ' . $server['name'])

@section('page-title', 'Detail Server')
@section('page-subtitle', 'Informasi lengkap server ' . $server['name'])

@section('content')
<link rel="stylesheet" href="{{ asset('css/server-detail.css') }}">

<div class="back-wrapper">
    <a href="{{ route('server.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

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
                <span class="info-value">{{ $server['ip_address'] }}</span>
                
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

<div class="tabs">
    <a href="#" class="tab active" onclick="showTab(event, 'overview')">Overview</a>
    <a href="#" class="tab" onclick="showTab(event, 'application')">Application</a>
    <a href="#" class="tab" onclick="showTab(event, 'history')">History</a>
</div>

<div id="tab-overview" class="tab-content active">
    <div class="content-grid">
        
        {{-- /* KOLOM 1 */ --}}
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

            {{-- /* Riwayat Maintenance */ --}}
            <div class="card">
                <div class="maintenance-history-header">
                    <h3 class="card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                        Riwayat Maintenance
                    </h3>
                    <button type="button" class="add-maintenance-btn" onclick="openMaintenanceModal()" title="Tambah Jadwal Maintenance">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </button>
                </div>
                <div class="maintenance-history-table-wrap">
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
                            @forelse($server['maintenance_history'] as $history)
                            <tr
                                @if($history['editable'])
                                class="maintenance-row-editable"
                                ondblclick="editMaintenance(this)"
                                data-maintenance-id="{{ $history['id'] }}"
                                data-maintenance-type="{{ $history['jenis'] }}"
                                data-maintenance-date="{{ $history['tanggal_input'] }}"
                                data-maintenance-start="{{ $history['jam_mulai'] }}"
                                data-maintenance-end="{{ $history['jam_selesai'] }}"
                                data-maintenance-status="{{ $history['status'] }}"
                                data-maintenance-pic="{{ $history['id_pic'] ?? '' }}"
                                data-maintenance-note="{{ $history['catatan'] }}"
                                title="Double click untuk mengedit jadwal"
                                @endif>
                                <td>{{ $history['tanggal'] }}</td>
                                <td>{{ $history['jenis'] }}</td>
                                <td>{{ $history['pic'] }}</td>
                                <td>
                                    <span class="maintenance-status-badge {{
                                        $history['status'] === 'Completed' ? 'badge-success' :
                                        ($history['status'] === 'Scheduled' ? 'badge-info' :
                                        ($history['status'] === 'In Progress' ? 'badge-warning' : 'badge-gray'))
                                    }}">{{ $history['status'] }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="maintenance-empty">Belum ada riwayat maintenance untuk server ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- /* KOLOM 2 */ --}}
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
        
        {{-- /* KOLOM 3 */ --}}
        <div class="column">
            <div class="card" style="position: relative;">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12.55a11 11 0 0114.08 0M1.42 9a16 16 0 0121.16 0M8.53 16.11a6 6 0 016.95 0M12 20h.01"/>
                    </svg>
                    Informasi Jaringan
                </h3>

                <button type="button" class="edit-network-btn" onclick="openNetworkModal()" title="Edit Informasi Jaringan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </button>

                <div class="info-list">
                    <div class="info-row"><span class="info-label">IP Address</span><span class="info-value">{{ $server['ip_address'] }}</span></div>
                    <div class="info-row"><span class="info-label">Subnet Mask</span><span class="info-value">{{ $server['subnet_mask'] }}</span></div>
                    <div class="info-row"><span class="info-label">Gateway</span><span class="info-value">{{ $server['gateway'] }}</span></div>
                    <div class="info-row"><span class="info-label">DNS Server</span><span class="info-value">{{ $server['dns_server'] }}</span></div>
                    <div class="info-row"><span class="info-label">MAC Address</span><span class="info-value">{{ $server['mac_address'] }}</span></div>
                    <div class="info-row"><span class="info-label">Speed</span><span class="info-value">{{ $server['speed'] }}</span></div>
                    <div class="info-row"><span class="info-label">Network Usage</span><span class="info-value">↓ {{ $server['network_usage_down'] }} ↑ {{ $server['network_usage_up'] }}</span></div>
                </div>
            </div>

            <div class="card" style="position: relative;">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                    </svg>
                    Catatan Terakhir
                </h3>

                <button type="button" class="add-note-btn" onclick="openNoteModal()" title="Tambah Catatan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>

                @if($latestNote)
                    <div class="note note-clickable"
                        ondblclick="editNote(this)"
                        data-note-id="{{ $latestNote->id_note }}"
                        data-note-text="{{ $latestNote->catatan }}"
                        data-note-pic="{{ $latestNote->id_pic ?? '' }}"
                        title="Double click untuk edit">
                        {{ $latestNote->catatan }}
                    </div>
                    <div class="note-by">
                        Ditambahkan oleh <strong>{{ $latestNote->nama_pic ?? '-' }}</strong><br>
                        {{ \Carbon\Carbon::parse($latestNote->dibuat_pada)->translatedFormat('d F Y, H:i') }}
                        @if($latestNote->diperbarui_pada)
                            <br><em style="font-size: 0.7rem;">Diedit: {{ \Carbon\Carbon::parse($latestNote->diperbarui_pada)->translatedFormat('d F Y, H:i') }}</em>
                        @endif
                    </div>
                @else
                    <div class="note-empty">
                        Belum ada catatan. Klik tombol <strong>+</strong> di pojok kanan untuk menambahkan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="tab-application" class="tab-content">
    <div class="card">
        <div class="server-applications-header">
            <h3 class="card-title">Aplikasi yang Terhubung</h3>
            <span class="server-applications-count">{{ $applications->count() }} aplikasi</span>
        </div>
        @if($applications->isNotEmpty())
            <div class="server-applications-table-wrap">
                <table class="server-applications-table">
                    <thead>
                        <tr>
                            <th>Nama Aplikasi</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Versi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr>
                                <td>
                                    <a class="server-application-link" href="{{ route('application.detail', $application->id_aplikasi) }}">
                                        {{ $application->nama_aplikasi }}
                                    </a>
                                </td>
                                <td>{{ $application->kode_aplikasi ?: '-' }}</td>
                                <td>{{ $application->kategori ?: '-' }}</td>
                                <td>{{ $application->versi ?: '-' }}</td>
                                <td>
                                    <span class="server-application-status status-{{ \Illuminate\Support\Str::slug($application->status) }}">
                                        {{ $application->status ?: '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="server-applications-empty">Belum ada aplikasi yang terhubung ke server ini.</p>
        @endif
    </div>
</div>

<div id="tab-history" class="tab-content">
    <div class="card">
        <h3 class="card-title">History</h3>
        <p style="color: #6b7280; font-size: 0.875rem;">Belum ada riwayat untuk server ini.</p>
    </div>
</div>

{{-- /* MODAL MAINTENANCE */ --}}
<div id="maintenanceModal" class="server-maintenance-modal" style="display: none;">
    <div class="server-maintenance-modal-box">
        <h3 class="server-maintenance-modal-title" id="maintenanceModalTitle">Tambah Jadwal Maintenance</h3>
        <form id="maintenanceForm" method="POST" action="{{ route('maintenance.store') }}">
            @csrf
            <input type="hidden" name="target_type" value="Server">
            <input type="hidden" name="id_server" value="{{ $server['id'] }}">

            <div class="server-maintenance-form-group">
                <label for="maintenance_type">Jenis Maintenance <span>*</span></label>
                <input type="text" name="jenis_maintenance" id="maintenance_type" maxlength="100" required>
            </div>
            <div class="server-maintenance-form-group">
                <label for="maintenance_pic">PIC</label>
                <select name="id_pic" id="maintenance_pic">
                    <option value="">-- Pilih PIC --</option>
                    @foreach($pics as $picId => $picName)
                        <option value="{{ $picId }}">{{ $picName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="server-maintenance-form-group">
                <label for="maintenance_date">Tanggal <span>*</span></label>
                <input type="date" name="jadwal_tanggal" id="maintenance_date" required>
            </div>
            <div class="server-maintenance-form-row">
                <div class="server-maintenance-form-group">
                    <label for="maintenance_start">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="maintenance_start">
                </div>
                <div class="server-maintenance-form-group">
                    <label for="maintenance_end">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="maintenance_end">
                </div>
            </div>
            <div class="server-maintenance-form-group">
                <label for="maintenance_status">Status <span>*</span></label>
                <select name="status" id="maintenance_status" required>
                    <option value="Scheduled">Scheduled</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Canceled">Canceled</option>
                </select>
            </div>
            <div class="server-maintenance-form-group">
                <label for="maintenance_note">Catatan</label>
                <textarea name="catatan" id="maintenance_note" rows="3"></textarea>
            </div>
            <div class="server-maintenance-modal-actions">
                <button type="button" class="server-maintenance-cancel" onclick="closeMaintenanceModal()">Batal</button>
                <button type="submit" class="server-maintenance-submit" id="maintenanceSubmitBtn">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- /* MODAL INFORMASI JARINGAN */ --}}
<div id="networkModal" class="modal-overlay" style="display: none;">
    <div class="modal-box">
        <h3 class="modal-title">Edit Informasi Jaringan</h3>
        
        <form id="networkForm" method="POST" action="{{ route('server.update-network', $server['id']) }}">
            @csrf
            @method('PUT')
            
            <div class="modal-form-group">
                <label for="network_ip_manajemen">IP Manajemen</label>
                <input type="text" name="alamat_ip_manajemen" id="network_ip_manajemen" 
                       value="{{ $server['ip_address'] }}" 
                       placeholder="Contoh: 10.10.1.1">
            </div>
            
            <div class="modal-form-group">
                <label for="network_ip_produksi">IP Produksi</label>
                <input type="text" name="alamat_ip_produksi" id="network_ip_produksi" 
                       placeholder="Contoh: 103.231.3.1">
            </div>
            
            <div class="modal-form-row">
                <div class="modal-form-group">
                    <label for="network_subnet">Subnet Mask</label>
                    <input type="text" name="subnet_mask" id="network_subnet" 
                           value="{{ $server['subnet_mask'] }}" 
                           placeholder="Contoh: 255.255.255.0">
                </div>
                <div class="modal-form-group">
                    <label for="network_gateway">Gateway</label>
                    <input type="text" name="gateway" id="network_gateway" 
                           value="{{ $server['gateway'] }}" 
                           placeholder="Contoh: 192.168.1.1">
                </div>
            </div>
            
            <div class="modal-form-group">
                <label for="network_dns">DNS Server</label>
                <input type="text" name="dns_server" id="network_dns" 
                       value="{{ $server['dns_server'] }}" 
                       placeholder="Contoh: 8.8.8.8, 1.1.1.1">
            </div>
            
            <div class="modal-form-group">
                <label for="network_mac">MAC Address</label>
                <input type="text" name="mac_address" id="network_mac" 
                       value="{{ $server['mac_address'] }}" 
                       placeholder="Contoh: 00:1A:2B:3C:4D:5E">
            </div>
            
            <div class="modal-form-row">
                <div class="modal-form-group">
                    <label for="network_speed">Speed</label>
                    <input type="text" name="network_speed" id="network_speed" 
                           value="{{ $server['speed'] }}" 
                           placeholder="Contoh: 1 Gbps">
                </div>
                <div class="modal-form-group">
                    <label for="network_down">Usage Down</label>
                    <input type="text" name="network_usage_down" id="network_down" 
                           value="{{ $server['network_usage_down'] }}" 
                           placeholder="Contoh: 125 Mbps">
                </div>
            </div>
            
            <div class="modal-form-group">
                <label for="network_up">Usage Up</label>
                <input type="text" name="network_usage_up" id="network_up" 
                       value="{{ $server['network_usage_up'] }}" 
                       placeholder="Contoh: 48 Mbps">
            </div>
            
            <div class="modal-actions">
                <button type="button" class="modal-btn-cancel" onclick="closeNetworkModal()">Batal</button>
                <button type="submit" class="modal-btn-submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- /* MODAL CATATAN */ --}}
<div id="noteModal" class="modal-overlay" style="display: none;">
    <div class="modal-box">
        <h3 class="modal-title" id="noteModalTitle">Tambah Catatan</h3>
        
        <form id="noteForm" method="POST" action="{{ route('server-notes.store') }}">
            @csrf
            <input type="hidden" name="id_server" value="{{ $server['id'] }}">
            <input type="hidden" name="id_note" id="editNoteId" value="">
            
            <div class="modal-form-group">
                <label for="note_pic">PIC</label>
                <select name="id_pic" id="note_pic">
                    <option value="">-- Pilih PIC --</option>
                    @foreach($pics as $picId => $picName)
                        <option value="{{ $picId }}">{{ $picName }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="modal-form-group">
                <label for="note_text">Catatan <span style="color: red;">*</span></label>
                <textarea name="catatan" id="note_text" rows="4" 
                          placeholder="Tulis catatan di sini..." required></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="modal-btn-cancel" onclick="closeNoteModal()">Batal</button>
                <button type="submit" class="modal-btn-submit" id="noteSubmitBtn">Simpan</button>
            </div>
        </form>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>

<script>

    /* MODAL NETWORK */
    function openNetworkModal() {
        document.getElementById('networkModal').style.display = 'flex';
    }

    function closeNetworkModal() {
        document.getElementById('networkModal').style.display = 'none';
    }

    document.getElementById('networkModal').addEventListener('click', function(e) {
        if (e.target === this) closeNetworkModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeNetworkModal();
    });
    /* MODAL CATATAN */
    function openNoteModal() {
        const form = document.getElementById('noteForm');
        form.reset();
        form.action = @json(route('server-notes.store'));
        document.querySelector('#noteForm input[name="_method"]')?.remove();
        document.getElementById('noteModalTitle').textContent = 'Tambah Catatan';
        document.getElementById('noteSubmitBtn').textContent = 'Simpan';
        document.getElementById('editNoteId').value = '';
        document.getElementById('noteModal').style.display = 'flex';
    }

    function editNote(el) {
        const data = el.dataset;
        const form = document.getElementById('noteForm');
        form.action = @json(url('/server-notes')) + '/' + encodeURIComponent(data.noteId);
        
        let methodInput = document.querySelector('#noteForm input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
        
        document.getElementById('noteModalTitle').textContent = 'Edit Catatan';
        document.getElementById('noteSubmitBtn').textContent = 'Update';
        document.getElementById('editNoteId').value = data.noteId;
        document.getElementById('note_text').value = data.noteText;
        document.getElementById('note_pic').value = data.notePic || '';
        document.getElementById('noteModal').style.display = 'flex';
    }

    function closeNoteModal() {
        document.getElementById('noteModal').style.display = 'none';
    }

    document.getElementById('noteModal').addEventListener('click', function(e) {
        if (e.target === this) closeNoteModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeNoteModal();
    });

    /* MODAL MAINTENANCE */
    function openMaintenanceModal() {
        const form = document.getElementById('maintenanceForm');
        form.reset();
        form.action = @json(route('maintenance.store'));
        document.querySelector('#maintenanceForm input[name="_method"]')?.remove();
        document.getElementById('maintenanceModalTitle').textContent = 'Tambah Jadwal Maintenance';
        document.getElementById('maintenanceSubmitBtn').textContent = 'Simpan';
        document.getElementById('maintenanceModal').style.display = 'flex';
    }

    function editMaintenance(row) {
        const data = row.dataset;
        const form = document.getElementById('maintenanceForm');
        form.action = @json(url('/maintenance')) + '/' + encodeURIComponent(data.maintenanceId);

        let methodInput = document.querySelector('#maintenanceForm input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        document.getElementById('maintenanceModalTitle').textContent = 'Edit Jadwal Maintenance';
        document.getElementById('maintenanceSubmitBtn').textContent = 'Update';
        document.getElementById('maintenance_type').value = data.maintenanceType;
        document.getElementById('maintenance_pic').value = data.maintenancePic || '';
        document.getElementById('maintenance_date').value = data.maintenanceDate;
        document.getElementById('maintenance_start').value = data.maintenanceStart;
        document.getElementById('maintenance_end').value = data.maintenanceEnd;
        document.getElementById('maintenance_status').value = data.maintenanceStatus;
        document.getElementById('maintenance_note').value = data.maintenanceNote;
        document.getElementById('maintenanceModal').style.display = 'flex';
    }

    function closeMaintenanceModal() {
        document.getElementById('maintenanceModal').style.display = 'none';
    }

    document.getElementById('maintenanceModal').addEventListener('click', function(event) {
        if (event.target === this) closeMaintenanceModal();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeMaintenanceModal();
    });

    /* TAB */
    function showTab(event, tabName) {
        event.preventDefault();
        
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.remove('active');
        });
        
        document.getElementById('tab-' + tabName).classList.add('active');
        
        document.querySelectorAll('.tab').forEach(el => {
            el.classList.remove('active');
        });
        event.target.classList.add('active');
    }
</script>
@endsection