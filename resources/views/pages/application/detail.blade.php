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
    <div class="card" style="position: relative;">
        <h3 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
            </svg>
            Maintenance
        </h3>
        
        {{-- TOMBOL TAMBAH MAINTENANCE --}}
        <button type="button" class="add-maintenance-btn" 
                onclick="openMaintenanceModal()" 
                title="Tambah Jadwal Maintenance">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
        
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
            
            {{-- Maintenance Berikutnya (BISA DIKLIK 2X BUAT EDIT HANYA YANG Scheduled / In Progress) --}}
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
                    <span class="edit-hint">✏️ Double click untuk edit</span>
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

{{-- ===== MODAL TAMBAH/EDIT MAINTENANCE ===== --}}
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
                <label for="id_pic">PIC</label>
                <select name="id_pic" id="id_pic">
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
    // ===== MODAL MAINTENANCE =====
    function openMaintenanceModal() {
        document.getElementById('maintenanceModalTitle').textContent = 'Tambah Jadwal Maintenance';
        document.getElementById('maintenanceSubmitBtn').textContent = 'Simpan';
        document.getElementById('editMaintenanceId').value = '';
        document.getElementById('jenis_maintenance').value = '';
        document.getElementById('id_pic').value = '';
        document.getElementById('jadwal_tanggal').value = '';
        document.getElementById('jam_mulai').value = '';
        document.getElementById('jam_selesai').value = '';
        document.getElementById('status').value = 'Scheduled';
        document.getElementById('catatan').value = '';
        
        // Ubah action ke store
        document.getElementById('maintenanceForm').action = '{{ route('maintenance.store') }}';
        
        // Hapus method PUT kalo ada
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
        document.getElementById('id_pic').value = maintenancePic || '';
        document.getElementById('status').value = maintenanceStatus;
        document.getElementById('jadwal_tanggal').value = maintenanceDate;
        
        // Ubah action ke update
        document.getElementById('maintenanceForm').action = '/maintenance/' + maintenanceId;
        
        // Tambah method PUT kalo belum ada
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
    
    // Tutup modal kalo klik di luar
    document.getElementById('maintenanceModal').addEventListener('click', function(e) {
        if (e.target === this) closeMaintenanceModal();
    });
    
    // Tutup modal kalo tekan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMaintenanceModal();
    });
</script>
@endsection