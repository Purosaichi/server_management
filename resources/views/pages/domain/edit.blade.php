@extends('layouts.app')

@section('title', 'Edit Domain')

@section('page-title', 'Edit Domain')
@section('page-subtitle', 'Perbarui informasi domain')

@section('content')
<link rel="stylesheet" href="{{ asset('css/application.css') }}">

<div class="app-back-wrapper">
    <a href="{{ route('domain.domain') }}" class="app-back-btn">← Back</a>
</div>

<div class="app-form-container">
    <h2 class="app-form-title">Edit Domain</h2>

    @if($errors->any())
    <div class="app-alert app-alert-error">
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('domain.update', $domain->id_domain) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="app-form-grid">
            <div class="app-form-group">
                <label for="nama_domain">Nama Domain <span style="color: red;">*</span></label>
                <input type="text" name="nama_domain" id="nama_domain" value="{{ old('nama_domain', $domain->nama_domain) }}" placeholder="Contoh: example.com" required>
            </div>

            <div class="app-form-group">
                <label for="status">Status <span style="color: red;">*</span></label>
                <select name="status" id="status" required>
                    <option value="Aktif" {{ old('status', $domain->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status', $domain->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="app-form-group">
                <label for="registrar">Registrar</label>
                <input type="text" name="registrar" id="registrar" value="{{ old('registrar', $domain->registrar) }}" placeholder="Contoh: Namecheap">
            </div>

            <div class="app-form-group">
                <label for="tanggal_pembelian">Tanggal Pembelian</label>
                <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" value="{{ old('tanggal_pembelian', $domain->tanggal_pembelian) }}">
            </div>

            <div class="app-form-group">
                <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa</label>
                <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', $domain->tanggal_kadaluarsa) }}">
            </div>

            <div class="app-form-group">
                <label for="id_pic">PIC</label>
                <select name="id_pic" id="id_pic">
                    <option value="">-- Pilih PIC --</option>
                    @foreach($pics as $id => $nama)
                        <option value="{{ $id }}" {{ old('id_pic', $domain->id_pic) == $id ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="app-form-group app-checkbox-group">
                <input type="checkbox" name="perpanjangan_otomatis" id="perpanjangan_otomatis" value="1" {{ old('perpanjangan_otomatis', $domain->perpanjangan_otomatis) ? 'checked' : '' }}>
                <label for="perpanjangan_otomatis">Perpanjangan Otomatis</label>
            </div>
        </div>

        <h3 class="app-form-section">SSL Certificate</h3>
        <div class="app-form-grid">
            <div class="app-form-group">
                <label for="ssl_provider">Provider</label>
                <input type="text" name="ssl_provider" id="ssl_provider" value="{{ old('ssl_provider', $sslCertificate->provider ?? '') }}">
            </div>

            <div class="app-form-group">
                <label for="ssl_status">Status SSL</label>
                <select name="ssl_status" id="ssl_status">
                    <option value="">-- Pilih Status --</option>
                    @foreach(['Valid', 'Expired', 'Pending'] as $status)
                        <option value="{{ $status }}" {{ old('ssl_status', $sslCertificate->status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="app-form-group">
                <label for="tanggal_ssl_terbit">Issued Date</label>
                <input type="date" name="tanggal_ssl_terbit" id="tanggal_ssl_terbit" value="{{ old('tanggal_ssl_terbit', $sslCertificate->tanggal_terbit ?? '') }}">
            </div>

            <div class="app-form-group">
                <label for="ssl_tanggal_kadaluarsa">Expiration Date</label>
                <input type="date" name="ssl_tanggal_kadaluarsa" id="ssl_tanggal_kadaluarsa" value="{{ old('ssl_tanggal_kadaluarsa', $sslCertificate->tanggal_kadaluarsa ?? '') }}">
            </div>

            <div class="app-form-group">
                <label for="ssl_tipe">Certificate Type</label>
                <input type="text" name="ssl_tipe" id="ssl_tipe" value="{{ old('ssl_tipe', $sslCertificate->tipe_sertifikat ?? '') }}">
            </div>
        </div>

        <h3 class="app-form-section">Billing Information</h3>
        <div class="app-form-grid">
            <div class="app-form-group">
                <label for="billing_status">Status Pembayaran</label>
                <select name="billing_status" id="billing_status">
                    <option value="">-- Pilih Status --</option>
                    @foreach(['Paid', 'Unpaid'] as $status)
                        <option value="{{ $status }}" {{ old('billing_status', $billing->status_pembayaran ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="app-form-group">
                <label for="billing_cycle">Billing Cycle</label>
                <input type="text" name="billing_cycle" id="billing_cycle" value="{{ old('billing_cycle', $billing->siklus_tagihan ?? '') }}">
            </div>

            <div class="app-form-group">
                <label for="last_payment">Last Payment</label>
                <input type="date" name="last_payment" id="last_payment" value="{{ old('last_payment', $billing->tanggal_bayar_terakhir ?? '') }}">
            </div>

            <div class="app-form-group">
                <label for="next_due_date">Next Due Date</label>
                <input type="date" name="next_due_date" id="next_due_date" value="{{ old('next_due_date', $billing->tanggal_jatuh_tempo ?? '') }}">
            </div>

            <div class="app-form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount', $billing->jumlah ?? '') }}" min="0" step="0.01">
            </div>

            <div class="app-form-group">
                <label for="payment_method">Payment Method</label>
                <input type="text" name="payment_method" id="payment_method" value="{{ old('payment_method', $billing->metode_pembayaran ?? '') }}">
            </div>
        </div>

        <div class="app-form-actions">
            <a href="{{ route('domain.domain') }}" class="app-btn-cancel">Batal</a>
            <button type="submit" class="app-btn-submit">Update Domain</button>
        </div>
    </form>
</div>
@endsection
