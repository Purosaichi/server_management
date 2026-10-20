
@extends('layouts.app')

@section('title', 'Domain')

@section('page-title', 'Domain')
@section('page-subtitle', 'Daftar semua domain yang terdaftar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/domain.css') }}">

<div class="header">
    <h2 class="title-header">Domains</h2>
</div>

<div class="dmn-table-container">
    <div class="dmn-table-header">
        <h3 class="dmn-table-title">Daftar Domain</h3>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span class="dmn-table-count">Total: {{ count($domains) }} domain</span>
            <a href="{{ route('domain.create') }}" class="dmn-btn-add"> + Tambah Domain</a>
        </div>    
    </div>
    <div class="dmn-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Domain</th>
                    <th>Application</th>
                    <th>Status</th>
                    <th>PIC</th>
                    <th>SSL Certificate</th>
                    <th>Last Activity</th>
                    <th class="dmn-text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($domains as $domain)
                <tr class="dmn-domain-row" data-detail-url="{{ route('domain.detail', $domain['id']) }}" tabindex="0" aria-label="Detail {{ $domain['domain'] }}">
                    <td><span class="dmn-name">{{ $domain['domain'] }}</span></td>
                    <td>{{ $domain['application'] }}</td>
                    <td>
                        <span class="dmn-badge 
                            {{ $domain['status'] == 'Aktif' ? 'dmn-badge-green' : 'dmn-badge-red' }}">
                            {{ $domain['status'] }}
                        </span>
                    </td>
                    <td>{{ $domain['pic'] }}</td>
                    <td>
                        <span class="dmn-badge 
                            {{ $domain['ssl'] == 'Aktif' ? 'dmn-badge-green' : 'dmn-badge-red' }}">
                            {{ $domain['ssl'] }}
                        </span>
                    </td>
                    <td>{{ $domain['last_activity'] }}</td>
                    <td class="dmn-text-center">
                        <div class="dmn-actions">
                            <a href="{{ route('domain.edit', $domain['id']) }}" class="dmn-btn-edit">
                                Edit
                            </a>
                            <button type="button" class="dmn-btn-delete"
                                data-delete-url="{{ route('domain.destroy', $domain['id']) }}"
                                data-domain-name="{{ $domain['domain'] }}"
                                onclick="showDomainDeleteModal(this)">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="domainDeleteModal" class="dmn-modal-overlay" style="display: none;" aria-hidden="true">
    <div class="dmn-modal" role="alertdialog" aria-modal="true" aria-labelledby="domainDeleteTitle" aria-describedby="domainDeleteText">
        <div class="dmn-modal-icon" aria-hidden="true">⚠️</div>
        <h3 id="domainDeleteTitle" class="dmn-modal-title">Konfirmasi Hapus</h3>
        <p id="domainDeleteText" class="dmn-modal-text">
            Apakah Anda yakin ingin menghapus domain <strong id="domainDeleteName"></strong>?
        </p>
        <div class="dmn-modal-actions">
            <button type="button" class="dmn-modal-cancel" onclick="closeDomainDeleteModal()" data-modal-cancel>
                Batal
            </button>
            <form id="domainDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="dmn-modal-confirm">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>

<script>
    const domainDeleteModal = document.getElementById('domainDeleteModal');
    let domainDeleteTrigger = null;

    function showDomainDeleteModal(button) {
        domainDeleteTrigger = button;
        document.getElementById('domainDeleteName').textContent = button.dataset.domainName;
        document.getElementById('domainDeleteForm').action = button.dataset.deleteUrl;
        domainDeleteModal.style.display = 'flex';
        domainDeleteModal.setAttribute('aria-hidden', 'false');
        domainDeleteModal.querySelector('[data-modal-cancel]').focus();
    }

    function closeDomainDeleteModal() {
        domainDeleteModal.style.display = 'none';
        domainDeleteModal.setAttribute('aria-hidden', 'true');
        domainDeleteTrigger?.focus();
    }

    domainDeleteModal.addEventListener('click', function (event) {
        if (event.target === domainDeleteModal) closeDomainDeleteModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && domainDeleteModal.style.display === 'flex') {
            closeDomainDeleteModal();
        }
    });

    document.querySelectorAll('.dmn-domain-row').forEach(function (row) {
        row.addEventListener('dblclick', function (event) {
            if (event.target.closest('a, button, form')) return;
            window.location.assign(row.dataset.detailUrl);
        });

        row.addEventListener('keydown', function (event) {
            if (event.key !== 'Enter') return;
            event.preventDefault();
            window.location.assign(row.dataset.detailUrl);
        });
    });
</script>
@endsection