@extends('layouts.app')

@section('title', 'Detail ' . $license['name'])
@section('page-title', 'Detail Lisensi')

@section('content')
{{-- Pake licenses-detail.css (bukan licenses.css) --}}
<link rel="stylesheet" href="{{ asset('css/licenses-detail.css') }}">

{{-- Tombol Back --}}
<div class="lic-back-wrap">
    <a href="{{ route('licenses.licenses') }}" class="lic-back">← Back</a>
</div>

{{-- ============ HEADER CARD ============ --}}
<div class="lic-detail-header">
    <div class="lic-detail-logo">
        <div class="lic-detail-logo-image">
            <img src="{{ asset('images/' . $license['logo']) }}" alt="{{ $license['name'] }}" class="lic-detail-logo-img">
        </div>
        <div class="lic-detail-logo-content">
            <span class="lic-detail-label">Nama Lisensi</span>
            <span class="lic-detail-logo-text">{{ $license['name'] }}</span>
        </div>
    </div>

    <div class="lic-detail-info-item">
        <span class="lic-detail-label">Provider</span>
        <div class="lic-detail-provider">
            <div>
                <p class="lic-provider-name">{{ $license['provider'] }}</p>
                <span class="lic-status-badge 
                    {{ $license['status'] === 'Aman' ? 'lic-status-safe' : '' }}
                    {{ $license['status'] === 'Expired' ? 'lic-status-expired' : '' }}
                    {{ $license['status'] === 'Akan Expired' ? 'lic-status-warning' : '' }}
                ">
                    {{ $license['status'] }}
                </span>
            </div>
        </div>
    </div>

    <div class="lic-detail-info-item">
        <span class="lic-detail-label">Tanggal Mulai</span>
        <span class="lic-detail-value-lg">{{ $license['start_date'] }}</span>
    </div>

    <div class="lic-detail-info-item">
        <span class="lic-detail-label">Tanggal Berakhir</span>
        <span class="lic-detail-value-lg 
            {{ $license['status'] === 'Aman' ? 'lic-text-green' : '' }}
            {{ $license['status'] === 'Expired' ? 'lic-text-danger' : '' }}
            {{ $license['status'] === 'Akan Expired' ? 'lic-text-warning' : '' }}
        ">
            {{ $license['expired'] }}
        </span>
    </div>
</div>

{{-- ============ 3 CARD INFO ============ --}}
<div class="lic-info-grid">

    {{-- Billing Information --}}
    <div class="lic-info-card">
        <div class="lic-info-header">
            <h4>Billing Information</h4>
            <span class="lic-info-status">
                Status: <strong class="{{ $license['status'] === 'Expired' ? 'lic-text-danger' : 'lic-text-green' }}">
                    {{ $license['status'] === 'Expired' ? 'Unpaid' : 'Paid' }}
                </strong>
            </span>
        </div>
        <ul class="lic-info-list">
            <li><span>Billing Period</span><span>1 Tahun</span></li>
            <li><span>Last payment</span><span>{{ $license['last_payment'] }}</span></li>
            <li><span>Next due date</span><span>{{ $license['next_due_date'] }}</span></li>
            <li><span>Amount</span><span>Rp{{ $license['amount'] }}</span></li>
            <li><span>Payment method</span><span>{{ $license['payment_method'] }}</span></li>
        </ul>
    </div>

    {{-- Licenses Information --}}
    <div class="lic-info-card">
        <div class="lic-info-header">
            <h4>Licenses Information</h4>
            <span class="lic-info-status">
                Status: <strong class="lic-text-green">Active</strong>
            </span>
        </div>
        <ul class="lic-info-list">
            <li><span>ID License</span><span>LIC-{{ $license['id'] }}-2026</span></li>
            <li><span>Nama Lisensi</span><span>{{ $license['name'] }}</span></li>
            <li><span>Nomor Lisensi</span><span>{{ $license['license_number'] }}</span></li>
            <li><span>Jumlah Lisensi</span><span>{{ $license['jumlah'] }}</span></li>
            <li><span>Jenis Lisensi</span><span>{{ $license['license_type'] }}</span></li>
        </ul>
    </div>

    {{-- Asset Information --}}
    <div class="lic-info-card">
        <div class="lic-info-header">
            <h4>Asset Information</h4>
            <span class="lic-info-status">
                Status: <strong class="lic-text-green">Active</strong>
            </span>
        </div>
        <ul class="lic-info-list">
            <li><span>Nama Aset</span><span>{{ $license['asset_name'] }}</span></li>
            <li><span>Hostname</span><span>{{ $license['hostname'] }}</span></li>
            <li><span>IP Address</span><span>{{ $license['ip_address'] }}</span></li>
            <li><span>Lokasi</span><span>{{ $license['location'] }}</span></li>
            <li><span>PIC</span><span>{{ $license['pic'] }}</span></li>
        </ul>
    </div>

</div>

{{-- ============ TABEL RIWAYAT PEMBAYARAN ============ --}}
<div class="lic-payment-card">
    <table class="lic-payment-table">
        <thead>
            <tr>
                <th>Tanggal Bayar</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>15 Agustus 2026</td>
                <td>Rp{{ $license['amount'] }}</td>
                <td>Transfer bank</td>
                <td><span class="lic-payment-status">Paid</span></td>
            </tr>
            <tr>
                <td>15 Agustus 2025</td>
                <td>Rp{{ $license['amount'] }}</td>
                <td>Transfer bank</td>
                <td><span class="lic-payment-status">Paid</span></td>
            </tr>
            <tr>
                <td>15 Agustus 2024</td>
                <td>Rp{{ $license['amount'] }}</td>
                <td>Transfer bank</td>
                <td><span class="lic-payment-status">Paid</span></td>
            </tr>
        </tbody>
    </table>
</div>

<footer class="footer">
    © 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen
</footer>
@endsection