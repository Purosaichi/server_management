@extends('layouts.app')

@section('title', 'Detail ' . $license['name'])
@section('page-title', 'Licenses Page')

@section('content')
<link rel="stylesheet" href="{{ asset('css/licenses.css') }}">

<!-- Tombol Back -->
<div class="lic-back-wrap">
    <a href="{{ route('licenses.licenses') }}" class="lic-back">← Back</a>
</div>

<!-- ============ HEADER CARD ============ -->
<div class="lic-detail-header">
    <div class="lic-detail-logo">
        <img src="{{ asset('images/' . $license['logo']) }}" alt="{{ $license['name'] }}" class="lic-detail-logo-img">
        <span class="lic-detail-logo-text">{{ $license['name'] }}</span>
    </div>

    <div class="lic-detail-info">
        <div class="lic-detail-info-item">
            <span class="lic-detail-label">Provider</span>
            <div class="lic-detail-provider">
                <img src="{{ asset('images/' . ($license['name'] === 'Microsoft 365' ? 'microsoft.png' : $license['logo'])) }}" alt="{{ $license['provider'] }}" class="lic-provider-logo">
                <div>
                    <p class="lic-provider-name">{{ $license['provider'] }}</p>
                    <span class="lic-status-badge {{ $license['status'] === 'Aman' ? 'lic-status-safe' : ($license['status'] === 'Expired' ? 'lic-status-expired' : 'lic-status-warning') }}">{{ $license['status'] }}</span>
                </div>
            </div>
        </div>

        <div class="lic-detail-info-item">
            <span class="lic-detail-label">Tanggal Mulai</span>
            <span class="lic-detail-value-lg">{{ $license['start_date'] }}</span>
        </div>

        <div class="lic-detail-info-item">
            <span class="lic-detail-label">Tanggal Berakhir</span>
            <span class="lic-detail-value-lg {{ $license['status'] === 'Aman' ? 'lic-text-green' : ($license['status'] === 'Expired' ? 'lic-text-danger' : 'lic-text-warning') }}">{{ $license['expired'] }}</span>
        </div>
    </div>
</div>

<!-- ============ 3 CARD INFO ============ -->
<div class="lic-info-grid">

    <!-- Billing Information -->
    <div class="lic-info-card">
        <div class="lic-info-header">
            <h4><i class="fas fa-file-invoice"></i> Billing Information</h4>
            <span class="lic-info-status">Status: <strong class="lic-text-green">{{ $license['status'] === 'Expired' ? 'Unpaid' : 'Paid' }}</strong></span>
        </div>
        <ul class="lic-info-list">
            <li><span>Billing Period</span><span>1 Tahun</span></li>
            <li><span>Last payment</span><span>{{ $license['last_payment'] }}</span></li>
            <li><span>Next due date</span><span>{{ $license['next_due_date'] }}</span></li>
            <li><span>Amount</span><span>{{ $license['amount'] }}</span></li>
            <li><span>Payment method</span><span>{{ $license['payment_method'] }}</span></li>
        </ul>
    </div>

    <!-- Licenses Information -->
    <div class="lic-info-card">
        <div class="lic-info-header">
            <h4><i class="fas fa-key"></i> Licenses Information</h4>
            <span class="lic-info-status">Status: <strong class="lic-text-green">Active</strong></span>
        </div>
        <ul class="lic-info-list">
            <li><span>ID License</span><span>LIC-{{ $license['id'] }}-2025</span></li>
            <li><span>Nama Lisensi</span><span>{{ $license['name'] }}</span></li>
            <li><span>Nomor Lisensi</span><span>{{ $license['license_number'] }}</span></li>
            <li><span>Jumlah Lisensi</span><span>{{ $license['jumlah'] }}</span></li>
            <li><span>Jenis Lisensi</span><span>{{ $license['license_type'] }}</span></li>
        </ul>
    </div>

    <!-- Asset Information -->
    <div class="lic-info-card">
        <div class="lic-info-header">
            <h4><i class="fas fa-server"></i> Asset Information</h4>
            <span class="lic-info-status">Status: <strong class="lic-text-green">Active</strong></span>
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

<!-- ============ TABEL RIWAYAT PEMBAYARAN ============ -->
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
            @php
                preg_match('/(\d{4})$/', $license['last_payment'], $paymentYearMatch);
                $lastPaymentYear = (int) ($paymentYearMatch[1] ?? date('Y'));
            @endphp
            @for ($yearOffset = 0; $yearOffset < 3; $yearOffset++)
                <tr>
                    <td>{{ preg_replace('/\d{4}$/', (string) ($lastPaymentYear - $yearOffset), $license['last_payment']) }}</td>
                    <td>Rp{{ $license['amount'] }}</td>
                    <td>Transfer bank</td>
                    <td><span class="lic-payment-status">Paid</span></td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<footer class="footer">
    © 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen
</footer>
@endsection