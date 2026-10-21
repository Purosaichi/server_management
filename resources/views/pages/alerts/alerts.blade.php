
@extends('layouts.app')

@section('title', 'Alerts')

@section('page-title', 'Alerts')
@section('page-subtitle', 'Peringatan kondisi server dan aplikasi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/alerts.css') }}">

<div class="alerts-header">
    <h1>Alerts</h1>
</div>

@if(session('success'))
    <div class="alerts-flash alerts-flash-success" role="status">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alerts-flash alerts-flash-error" role="alert">{{ $errors->first() }}</div>
@endif

<div class="alerts-stats-grid">
    <div class="alerts-stat-card">
        <div class="alerts-stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="alerts-stat-content">
            <p class="alerts-stat-label">Total Alerts</p>
            <p class="alerts-stat-value">{{ $stats['total'] }}</p>
            <p class="alerts-stat-sub">Semua Alert</p>
        </div>
    </div>
    <div class="alerts-stat-card">
        <div class="alerts-stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="alerts-stat-content">
            <p class="alerts-stat-label">Critical</p>
            <p class="alerts-stat-value">{{ $stats['critical'] }}</p>
            <p class="alerts-stat-sub">Perlu penanganan segera</p>
        </div>
    </div>
    <div class="alerts-stat-card">
        <div class="alerts-stat-icon yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="alerts-stat-content">
            <p class="alerts-stat-label">Warning</p>
            <p class="alerts-stat-value">{{ $stats['warning'] }}</p>
            <p class="alerts-stat-sub">Perlu diperhatikan</p>
        </div>
    </div>
    <div class="alerts-stat-card">
        <div class="alerts-stat-icon purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="alerts-stat-content">
            <p class="alerts-stat-label">Info</p>
            <p class="alerts-stat-value">{{ $stats['info'] }}</p>
            <p class="alerts-stat-sub">Informasi</p>
        </div>
    </div>
</div>

<div class="alerts-table-container">
    <div class="alerts-table-header">
        <h3 class="alerts-table-title">Daftar Alert</h3>
        <div class="alerts-filter-group">
            <button class="alerts-filter-btn active" data-filter="all">Semua</button>
            <button class="alerts-filter-btn inactive" data-filter="server">Server</button>
            <button class="alerts-filter-btn inactive" data-filter="application">Application</button>
            <button class="alerts-filter-btn inactive" data-filter="domain">Domain</button>
        </div>
        <div class="alerts-header-actions">
            <button type="button" class="alerts-add-button" id="add-alert-button">Tambah Alert</button>
            <button type="button" class="alerts-clear-all-button" id="clear-all-alerts">Clear Semua</button>
        </div>
    </div>
    <div class="alerts-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Level</th>
                    <th>Target</th>
                    <th>Type</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Durasi</th>
                    <th>PIC</th>
                    <th>Aksi</th>
                </tr>   
            </thead>
            <tbody id="alerts-table-body">
                
                @foreach($serverAlerts as $alert)
                <tr data-kategori="server">
                    <td>
                        <div>{{ $alert['waktu'] }}</div>
                        <div class="alerts-time">{{ $alert['jam'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-badge 
                            {{ $alert['level'] == 'Critical' ? 'alerts-badge-critical' : '' }}
                            {{ $alert['level'] == 'Warning' ? 'alerts-badge-warning' : '' }}
                            {{ $alert['level'] == 'Info' ? 'alerts-badge-info' : '' }}
                        ">
                            {{ $alert['level'] }}
                        </span>
                    </td>
                    <td>
                        <div>{{ $alert['target'] }}</div>
                        <div class="alerts-time">{{ $alert['target_sub'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-type-badge alerts-type-server">{{ $alert['type'] }}</span>
                    </td>
                    <td>
                        <div>{{ $alert['deskripsi'] }}</div>
                        <div class="alerts-desc-sub">{{ $alert['keterangan'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-status 
                            {{ $alert['status'] == 'Aktif' ? 'alerts-status-active' : '' }}
                        ">
                            {{ $alert['status'] }}
                        </span>
                    </td>
                    <td>{{ $alert['durasi'] }}</td>
                    <td>{{ $alert['pic'] }}</td>
                    <td class="alerts-row-actions">
                        <button type="button" class="alerts-edit-button" data-edit-alert
                            data-alert-id="{{ $alert['id'] }}" data-target-type="{{ $alert['target_type'] }}"
                            data-target-id="{{ $alert['target_id'] }}" data-pic-id="{{ $alert['pic_id'] }}"
                            data-level="{{ $alert['level'] }}" data-description="{{ $alert['deskripsi'] }}"
                            data-details="{{ $alert['keterangan'] }}" data-start="{{ $alert['waktu_mulai_raw'] }}"
                            data-end="{{ $alert['waktu_selesai_raw'] }}" data-status="{{ $alert['status'] }}">Edit</button>
                        <button type="button" class="alerts-row-clear-button" data-clear-alert="{{ $alert['id'] }}">Clear</button>
                    </td>
                </tr>
                @endforeach
                
                @foreach($applicationAlerts as $alert)
                <tr data-kategori="application">
                    <td>
                        <div>{{ $alert['waktu'] }}</div>
                        <div class="alerts-time">{{ $alert['jam'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-badge 
                            {{ $alert['level'] == 'Critical' ? 'alerts-badge-critical' : '' }}
                            {{ $alert['level'] == 'Warning' ? 'alerts-badge-warning' : '' }}
                            {{ $alert['level'] == 'Info' ? 'alerts-badge-info' : '' }}
                        ">
                            {{ $alert['level'] }}
                        </span>
                    </td>
                    <td>
                        <div>{{ $alert['target'] }}</div>
                        <div class="alerts-time">{{ $alert['target_sub'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-type-badge alerts-type-application">{{ $alert['type'] }}</span>
                    </td>
                    <td>
                        <div>{{ $alert['deskripsi'] }}</div>
                        <div class="alerts-desc-sub">{{ $alert['keterangan'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-status 
                            {{ $alert['status'] == 'Aktif' ? 'alerts-status-active' : '' }}
                        ">
                            {{ $alert['status'] }}
                        </span>
                    </td>
                    <td>{{ $alert['durasi'] }}</td>
                    <td>{{ $alert['pic'] }}</td>
                    <td class="alerts-row-actions">
                        <button type="button" class="alerts-edit-button" data-edit-alert
                            data-alert-id="{{ $alert['id'] }}" data-target-type="{{ $alert['target_type'] }}"
                            data-target-id="{{ $alert['target_id'] }}" data-pic-id="{{ $alert['pic_id'] }}"
                            data-level="{{ $alert['level'] }}" data-description="{{ $alert['deskripsi'] }}"
                            data-details="{{ $alert['keterangan'] }}" data-start="{{ $alert['waktu_mulai_raw'] }}"
                            data-end="{{ $alert['waktu_selesai_raw'] }}" data-status="{{ $alert['status'] }}">Edit</button>
                        <button type="button" class="alerts-row-clear-button" data-clear-alert="{{ $alert['id'] }}">Clear</button>
                    </td>
                </tr>
                @endforeach

                @foreach($domainAlerts as $alert)
                <tr data-kategori="domain">
                    <td>
                        <div>{{ $alert['waktu'] }}</div>
                        <div class="alerts-time">{{ $alert['jam'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-badge 
                            {{ $alert['level'] == 'Critical' ? 'alerts-badge-critical' : '' }}
                            {{ $alert['level'] == 'Warning' ? 'alerts-badge-warning' : '' }}
                            {{ $alert['level'] == 'Info' ? 'alerts-badge-info' : '' }}
                        ">
                            {{ $alert['level'] }}
                        </span>
                    </td>
                    <td>
                        <div>{{ $alert['target'] }}</div>
                        <div class="alerts-time">{{ $alert['target_sub'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-type-badge alerts-type-domain">{{ $alert['type'] }}</span>
                    </td>
                    <td>
                        <div>{{ $alert['deskripsi'] }}</div>
                        <div class="alerts-desc-sub">{{ $alert['keterangan'] }}</div>
                    </td>
                    <td>
                        <span class="alerts-status 
                            {{ $alert['status'] == 'Aktif' ? 'alerts-status-active' : '' }}
                        ">
                            {{ $alert['status'] }}
                        </span>
                    </td>
                    <td>{{ $alert['durasi'] }}</td>
                    <td>{{ $alert['pic'] }}</td>
                    <td class="alerts-row-actions">
                        <button type="button" class="alerts-edit-button" data-edit-alert
                            data-alert-id="{{ $alert['id'] }}" data-target-type="{{ $alert['target_type'] }}"
                            data-target-id="{{ $alert['target_id'] }}" data-pic-id="{{ $alert['pic_id'] }}"
                            data-level="{{ $alert['level'] }}" data-description="{{ $alert['deskripsi'] }}"
                            data-details="{{ $alert['keterangan'] }}" data-start="{{ $alert['waktu_mulai_raw'] }}"
                            data-end="{{ $alert['waktu_selesai_raw'] }}" data-status="{{ $alert['status'] }}">Edit</button>
                        <button type="button" class="alerts-row-clear-button" data-clear-alert="{{ $alert['id'] }}">Clear</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="alerts-table-footer">
        Menampilkan <span id="row-count">{{ count($serverAlerts) + count($applicationAlerts) + count($domainAlerts) }}</span> dari {{ $stats['total'] }} data
    </div>
</div>

    <div class="alerts-modal" id="category-modal" hidden>
        <div class="alerts-modal-backdrop" data-close-modal></div>
        <section class="alerts-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="category-modal-title">
            <button type="button" class="alerts-modal-close" data-close-modal aria-label="Tutup">&times;</button>
            <h2 id="category-modal-title">Pilih kategori alert</h2>
            <div class="alerts-choice-list">
                <button type="button" data-category-choice="Server">Server</button>
                <button type="button" data-category-choice="Application">Application</button>
                <button type="button" data-category-choice="Domain">Domain</button>
            </div>
        </section>
    </div>

    <div class="alerts-modal" id="level-modal" hidden>
        <div class="alerts-modal-backdrop" data-close-modal></div>
        <section class="alerts-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="level-modal-title">
            <button type="button" class="alerts-modal-close" data-close-modal aria-label="Tutup">&times;</button>
            <h2 id="level-modal-title">Pilih level alert</h2>
            <div class="alerts-choice-list">
                <button type="button" data-level-choice="Critical">Critical</button>
                <button type="button" data-level-choice="Warning">Warning</button>
            </div>
        </section>
    </div>

    <div class="alerts-modal" id="editor-modal" hidden>
        <div class="alerts-modal-backdrop" data-close-modal></div>
        <section class="alerts-modal-dialog alerts-editor-dialog" role="dialog" aria-modal="true" aria-labelledby="editor-title">
            <button type="button" class="alerts-modal-close" data-close-modal aria-label="Tutup">&times;</button>
            <h2 id="editor-title">Tambah alert</h2>
            <form id="alert-form" method="POST" action="{{ route('alerts.store') }}"
                data-store-url="{{ route('alerts.store') }}"
                data-update-url="{{ route('alerts.update', ['id' => '__ID__']) }}">
                @csrf
                <input type="hidden" name="_method" id="alert-form-method" value="" disabled>
                <input type="hidden" name="target_type" id="alert-target-type">
                <div class="alerts-form-grid">
                    <label>Target
                        <select data-target-select="Server" disabled>
                            <option value="">Pilih server</option>
                            @foreach($servers as $option)
                                <option value="{{ $option->id_server }}">{{ $option->nama_server }}</option>
                            @endforeach
                        </select>
                        <select data-target-select="Application" disabled hidden>
                            <option value="">Pilih application</option>
                            @foreach($applications as $option)
                                <option value="{{ $option->id_aplikasi }}">{{ $option->nama_aplikasi }}</option>
                            @endforeach
                        </select>
                        <select data-target-select="Domain" disabled hidden>
                            <option value="">Pilih domain</option>
                            @foreach($domains as $option)
                                <option value="{{ $option->id_domain }}">{{ $option->nama_domain }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>Level
                        <select name="level" id="alert-level" required>
                            <option value="Critical">Critical</option>
                            <option value="Warning">Warning</option>
                            <option value="Info" hidden>Info</option>
                        </select>
                    </label>
                    <label>Waktu mulai
                        <input type="datetime-local" name="waktu_mulai" id="alert-start" required>
                    </label>
                    <label>Waktu selesai
                        <input type="datetime-local" name="waktu_selesai" id="alert-end">
                    </label>
                    <label>Status
                        <select name="status" id="alert-status" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </label>
                    <label>PIC
                        <select name="id_pic" id="alert-pic">
                            <option value="">Tanpa PIC</option>
                            @foreach($pics as $option)
                                <option value="{{ $option->id_pic }}">{{ $option->nama_pic }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="alerts-form-wide">Deskripsi
                        <input type="text" name="deskripsi" id="alert-description" maxlength="255" required>
                    </label>
                    <label class="alerts-form-wide">Keterangan
                        <textarea name="keterangan" id="alert-details" rows="3" maxlength="1000"></textarea>
                    </label>
                </div>
                <div class="alerts-modal-actions">
                    <button type="button" class="alerts-cancel-button" data-close-modal>Batal</button>
                    <button type="submit" class="alerts-save-button">Simpan alert</button>
                </div>
            </form>
        </section>
    </div>

    <div class="alerts-modal" id="confirm-modal" hidden>
        <div class="alerts-modal-backdrop" data-close-modal></div>
        <section class="alerts-modal-dialog alerts-confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="confirm-title">
            <button type="button" class="alerts-modal-close" data-close-modal aria-label="Tutup">&times;</button>
            <h2 id="confirm-title">Clear alert?</h2>
            <p id="confirm-message">Alert yang dipilih akan dihapus permanen.</p>
            <form id="clear-alert-form" method="POST" action="{{ route('alerts.clear') }}">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <div class="alerts-modal-actions">
                    <button type="button" class="alerts-cancel-button" data-close-modal>Batal</button>
                    <button type="submit" class="alerts-row-clear-button">Clear</button>
                </div>
            </form>
        </section>
    </div>

<footer class="footer">
    <p>&copy; 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.alerts-filter-btn');
        const tableRows = document.querySelectorAll('#alerts-table-body tr');
        const rowCount = document.getElementById('row-count');
        const addButton = document.getElementById('add-alert-button');
        const clearAllButton = document.getElementById('clear-all-alerts');
        const categoryModal = document.getElementById('category-modal');
        const levelModal = document.getElementById('level-modal');
        const editorModal = document.getElementById('editor-modal');
        const confirmModal = document.getElementById('confirm-modal');
        const form = document.getElementById('alert-form');
        const methodInput = document.getElementById('alert-form-method');
        const targetTypeInput = document.getElementById('alert-target-type');
        const targetSelects = form.querySelectorAll('[data-target-select]');
        const selectedCategory = { value: 'Server' };
        const selectedLevel = { value: 'Critical' };

        function openModal(modal) {
            modal.hidden = false;
        }

        function closeModal(modal) {
            modal.hidden = true;
        }

        function configureTarget(type, id) {
            targetTypeInput.value = type;
            targetSelects.forEach(function(select) {
                const active = select.dataset.targetSelect === type;
                select.hidden = !active;
                select.disabled = !active;
                if (active) {
                    select.name = 'target_id';
                    select.value = id || '';
                } else {
                    select.removeAttribute('name');
                }
            });
        }

        function openEditor(type, level, alert) {
            form.reset();
            methodInput.disabled = true;
            methodInput.value = '';
            form.action = form.dataset.storeUrl;
            document.getElementById('editor-title').textContent = alert ? 'Edit alert' : 'Tambah alert';
            document.getElementById('alert-level').value = level || 'Critical';
            document.getElementById('alert-level').querySelector('option[value="Info"]').hidden = !(alert && alert.level === 'Info');
            document.getElementById('alert-start').value = alert ? alert.start : new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 16);
            document.getElementById('alert-end').value = alert ? alert.end : '';
            document.getElementById('alert-status').value = alert ? alert.status : 'Aktif';
            document.getElementById('alert-pic').value = alert ? alert.pic : '';
            document.getElementById('alert-description').value = alert ? alert.description : '';
            document.getElementById('alert-details').value = alert ? alert.details : '';

            if (alert) {
                form.action = form.dataset.updateUrl.replace('__ID__', alert.id);
                methodInput.value = 'PUT';
                methodInput.disabled = false;
            }

            configureTarget(type, alert ? alert.targetId : '');
            closeModal(levelModal);
            closeModal(categoryModal);
            openModal(editorModal);
        }

        function openClear(url, all) {
            const clearForm = document.getElementById('clear-alert-form');
            clearForm.action = url;
            document.getElementById('confirm-title').textContent = all ? 'Clear semua alert?' : 'Clear alert?';
            document.getElementById('confirm-message').textContent = all
                ? 'Semua alert akan dihapus permanen.'
                : 'Alert ini akan dihapus permanen.';
            openModal(confirmModal);
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.add('inactive');
                });
                this.classList.add('active');
                this.classList.remove('inactive');

                const filter = this.getAttribute('data-filter');
                let visibleCount = 0;
                addButton.dataset.category = filter;
                clearAllButton.hidden = filter !== 'all';

                tableRows.forEach(row => {
                    if (filter === 'all' || row.getAttribute('data-kategori') === filter) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                rowCount.textContent = visibleCount;
            });
        });

        addButton.addEventListener('click', function() {
            const category = this.dataset.category || 'all';
            if (category === 'all') {
                openModal(categoryModal);
                return;
            }

            selectedCategory.value = category.charAt(0).toUpperCase() + category.slice(1);
            openModal(levelModal);
        });

        document.querySelectorAll('[data-category-choice]').forEach(function(button) {
            button.addEventListener('click', function() {
                selectedCategory.value = this.dataset.categoryChoice;
                closeModal(categoryModal);
                openModal(levelModal);
            });
        });

        document.querySelectorAll('[data-level-choice]').forEach(function(button) {
            button.addEventListener('click', function() {
                selectedLevel.value = this.dataset.levelChoice;
                openEditor(selectedCategory.value, selectedLevel.value, null);
            });
        });

        document.querySelectorAll('[data-edit-alert]').forEach(function(button) {
            button.addEventListener('click', function() {
                openEditor(this.dataset.targetType, this.dataset.level, {
                    id: this.dataset.alertId,
                    targetId: this.dataset.targetId,
                    level: this.dataset.level,
                    pic: this.dataset.picId,
                    description: this.dataset.description,
                    details: this.dataset.details,
                    start: this.dataset.start,
                    end: this.dataset.end,
                    status: this.dataset.status,
                });
            });
        });

        document.querySelectorAll('[data-clear-alert]').forEach(function(button) {
            button.addEventListener('click', function() {
                openClear(form.dataset.updateUrl.replace('__ID__', this.dataset.clearAlert), false);
                document.querySelector('#clear-alert-form input[name="_method"]').value = 'DELETE';
            });
        });

        clearAllButton.addEventListener('click', function() {
            openClear("{{ route('alerts.clear') }}", true);
        });

        document.querySelectorAll('[data-close-modal]').forEach(function(button) {
            button.addEventListener('click', function() {
                closeModal(this.closest('.alerts-modal'));
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.alerts-modal:not([hidden])').forEach(closeModal);
            }
        });
    });
</script>
@endsection