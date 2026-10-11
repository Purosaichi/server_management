@extends('layouts.app')

@section('title', 'Tambah Server')

@section('page-title', 'Tambah Server')
@section('page-subtitle', 'Form untuk menambahkan server baru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/server.css') }}">

<div class="srv-back-wrapper">
    <a href="{{ route('server.index') }}" class="srv-back-btn">← Back</a>
</div>

<div class="srv-form-container">
    <h2 class="srv-form-title">Tambah Server Baru</h2>

    @if($errors->any())
    <div class="srv-alert srv-alert-error">
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('server.store') }}" method="POST">
        @csrf

        {{-- ===== INFORMASI DASAR ===== --}}
        <h3 class="srv-form-section">Informasi Dasar</h3>
        <div class="srv-form-grid">
            <div class="srv-form-group">
                <label for="nama_server">Nama Server <span style="color: red;">*</span></label>
                <input type="text" name="nama_server" id="nama_server" 
                       value="{{ old('nama_server') }}" 
                       placeholder="Contoh: SVR-004" required>
            </div>

            <div class="srv-form-group">
                <label for="hostname">Hostname</label>
                <input type="text" name="hostname" id="hostname" 
                       value="{{ old('hostname') }}" 
                       placeholder="Contoh: svr-004.kemendik.local">
            </div>

            <div class="srv-form-group">
                <label for="alamat_ip_manajemen">IP Manajemen</label>
                <input type="text" name="alamat_ip_manajemen" id="alamat_ip_manajemen" 
                       value="{{ old('alamat_ip_manajemen') }}" 
                       placeholder="Contoh: 10.10.1.4">
            </div>

            <div class="srv-form-group">
                <label for="alamat_ip_produksi">IP Produksi</label>
                <input type="text" name="alamat_ip_produksi" id="alamat_ip_produksi" 
                       value="{{ old('alamat_ip_produksi') }}" 
                       placeholder="Contoh: 103.231.3.4">
            </div>

            <div class="srv-form-group">
                <label for="sistem_operasi">Sistem Operasi</label>
                <input type="text" name="sistem_operasi" id="sistem_operasi" 
                       value="{{ old('sistem_operasi') }}" 
                       placeholder="Contoh: Windows Server 2025">
            </div>

            <div class="srv-form-group">
                <label for="jenis_server">Jenis Server</label>
                <select name="jenis_server" id="jenis_server">
                    <option value="">-- Pilih --</option>
                    <option value="Physical" {{ old('jenis_server') == 'Physical' ? 'selected' : '' }}>Physical</option>
                    <option value="Virtual" {{ old('jenis_server') == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                </select>
            </div>

            <div class="srv-form-group">
                <label for="lokasi_rack">Lokasi Rack</label>
                <input type="text" name="lokasi_rack" id="lokasi_rack" 
                       value="{{ old('lokasi_rack') }}" 
                       placeholder="Contoh: Data Center, Rack A04">
            </div>

            <div class="srv-form-group">
                <label for="status_aset">Status Aset <span style="color: red;">*</span></label>
                <select name="status_aset" id="status_aset" required>
                    <option value="Aktif" {{ old('status_aset') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status_aset') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="Dihapus" {{ old('status_aset') == 'Dihapus' ? 'selected' : '' }}>Dihapus</option>
                </select>
            </div>
        </div>

        {{-- ===== SPESIFIKASI HARDWARE ===== --}}
        <h3 class="srv-form-section">Spesifikasi Hardware</h3>
        <div class="srv-form-grid">
            <div class="srv-form-group">
                <label for="jumlah_prosessor">Jumlah Prosessor</label>
                <input type="number" name="jumlah_prosessor" id="jumlah_prosessor" 
                       value="{{ old('jumlah_prosessor') }}" 
                       placeholder="Contoh: 2">
            </div>

            <div class="srv-form-group">
                <label for="model_prosessor">Model Prosessor</label>
                <input type="text" name="model_prosessor" id="model_prosessor" 
                       value="{{ old('model_prosessor') }}" 
                       placeholder="Contoh: AMD EPYC 9655">
            </div>

            <div class="srv-form-group">
                <label for="kapasitas_memori_gb">Kapasitas Memori (GB)</label>
                <input type="number" name="kapasitas_memori_gb" id="kapasitas_memori_gb" 
                       value="{{ old('kapasitas_memori_gb') }}" 
                       placeholder="Contoh: 512">
            </div>

            <div class="srv-form-group">
                <label for="jenis_memori">Jenis Memori</label>
                <input type="text" name="jenis_memori" id="jenis_memori" 
                       value="{{ old('jenis_memori') }}" 
                       placeholder="Contoh: DDR5 ECC RDIMM">
            </div>

            <div class="srv-form-group">
                <label for="kapasitas_penyimpanan_gb">Kapasitas Penyimpanan (GB)</label>
                <input type="number" name="kapasitas_penyimpanan_gb" id="kapasitas_penyimpanan_gb" 
                       value="{{ old('kapasitas_penyimpanan_gb') }}" 
                       placeholder="Contoh: 2048">
            </div>

            <div class="srv-form-group">
                <label for="jenis_penyimpanan">Jenis Penyimpanan</label>
                <input type="text" name="jenis_penyimpanan" id="jenis_penyimpanan" 
                       value="{{ old('jenis_penyimpanan') }}" 
                       placeholder="Contoh: NVMe">
            </div>
        </div>

        {{-- ===== STATUS MONITORING ===== --}}
        <h3 class="srv-form-section">Status Monitoring</h3>
        <div class="srv-form-grid">
            <div class="srv-form-group">
                <label for="status_monitoring">Status <span style="color: red;">*</span></label>
                <select name="status_monitoring" id="status_monitoring" required>
                    <option value="Online" {{ old('status_monitoring') == 'Online' ? 'selected' : '' }}>Online</option>
                    <option value="Offline" {{ old('status_monitoring') == 'Offline' ? 'selected' : '' }}>Offline</option>
                </select>
            </div>

            <div class="srv-form-group">
                <label for="cpu_percent">CPU (%)</label>
                <input type="number" name="cpu_percent" id="cpu_percent" 
                       value="{{ old('cpu_percent', 0) }}" min="0" max="100">
            </div>

            <div class="srv-form-group">
                <label for="memory_percent">Memory (%)</label>
                <input type="number" name="memory_percent" id="memory_percent" 
                       value="{{ old('memory_percent', 0) }}" min="0" max="100">
            </div>

            <div class="srv-form-group">
                <label for="storage_percent">Storage (%)</label>
                <input type="number" name="storage_percent" id="storage_percent" 
                       value="{{ old('storage_percent', 0) }}" min="0" max="100">
            </div>
        </div>

        {{-- ===== TOMBOL AKSI ===== --}}
        <div class="srv-form-actions">
            <a href="{{ route('server.index') }}" class="srv-btn-cancel">Batal</a>
            <button type="submit" class="srv-btn-submit">Simpan Server</button>
        </div>
    </form>
</div>
@endsection