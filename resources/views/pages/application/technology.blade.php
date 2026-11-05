<link rel="stylesheet" href="{{ asset('css/application-technology.css') }}">

<div id="technologyPanel" class="technology-panel" role="tabpanel" aria-labelledby="technologyTab" hidden>
    @if(session('success'))
        <div class="technology-alert technology-alert-success" role="status">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="technology-alert technology-alert-error" role="alert">{{ $errors->first() }}</div>
    @endif

    @foreach($applicationTechnologies as $technology)
        <article class="technology-card">
            <header class="technology-heading">
                <div class="technology-heading-icon" aria-hidden="true">
                    <i class="fas fa-code"></i>
                </div>
                <div>
                    <h2>Teknologi Aplikasi</h2>
                    <p>Informasi teknis dan infrastruktur {{ $application['name'] }}.</p>
                </div>
                @if(! empty($technology->id_teknologi_aplikasi))
                    <button type="button" class="technology-action"
                        data-technology="{{ json_encode([
                            'id' => $technology->id_teknologi_aplikasi,
                            'bahasa_pemrograman' => $technology->bahasa_pemrograman,
                            'kerangka_pengembangan' => $technology->kerangka_pengembangan,
                            'metode_pengembangan' => $technology->metode_pengembangan,
                            'model_pengembangan' => $technology->model_pengembangan,
                            'basis_data' => $technology->basis_data,
                            'sistem_operasi_server' => $technology->sistem_operasi_server,
                            'layanan_infrastruktur' => $technology->layanan_infrastruktur,
                            'mekanisme_pembangunan' => $technology->mekanisme_pembangunan,
                            'keterangan' => $technology->keterangan,
                        ]) }}"
                        onclick="editTechnology(this)">
                        <i class="fas fa-pen" aria-hidden="true"></i>
                        Edit Teknologi
                    </button>
                @else
                    <button type="button" class="technology-action"
                        onclick="openTechnologyModal()">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Tambah Teknologi
                    </button>
                @endif
            </header>

            <div class="technology-grid">
                <section class="technology-section">
                    <h3><i class="fas fa-laptop" aria-hidden="true"></i>Pengembangan Aplikasi</h3>
                    <dl class="technology-list">
                        <div class="technology-item">
                            <dt>Bahasa Pemrograman</dt>
                            <dd>{{ $technology->bahasa_pemrograman ?: '-' }}</dd>
                        </div>
                        <div class="technology-item">
                            <dt>Kerangka Pengembangan</dt>
                            <dd>{{ $technology->kerangka_pengembangan ?: '-' }}</dd>
                        </div>
                        <div class="technology-item">
                            <dt>Metode Pengembangan</dt>
                            <dd>{{ $technology->metode_pengembangan ?: '-' }}</dd>
                        </div>
                        <div class="technology-item">
                            <dt>Model Pengembangan</dt>
                            <dd>{{ $technology->model_pengembangan ?: '-' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="technology-section">
                    <h3><i class="fas fa-database" aria-hidden="true"></i>Database dan Sistem Operasi</h3>
                    <dl class="technology-list">
                        <div class="technology-item">
                            <dt>Basis Data</dt>
                            <dd>{{ $technology->basis_data ?: '-' }}</dd>
                        </div>
                        <div class="technology-item">
                            <dt>Sistem Operasi Server</dt>
                            <dd>{{ $technology->sistem_operasi_server ?: '-' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="technology-section">
                    <h3><i class="fas fa-cloud" aria-hidden="true"></i>Infrastruktur</h3>
                    <dl class="technology-list">
                        <div class="technology-item">
                            <dt>Layanan Infrastruktur</dt>
                            <dd>{{ $technology->layanan_infrastruktur ?: '-' }}</dd>
                        </div>
                        <div class="technology-item">
                            <dt>Mekanisme Pembangunan</dt>
                            <dd>{{ $technology->mekanisme_pembangunan ?: '-' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="technology-notes">
                    <h3><i class="fas fa-circle-info" aria-hidden="true"></i>Keterangan</h3>
                    <p>{{ $technology->keterangan ?: '-' }}</p>
                </section>
            </div>
        </article>
    @endforeach

    @include('pages.application.technology-form', ['application' => $application])
</div>

<script>
    function openTechnologyModal(mode) {
        const modal = document.getElementById('technologyModal');
        const form = document.getElementById('technologyForm');
        const method = document.getElementById('technologyFormMethod');

        form.reset();
        form.action = form.dataset.storeUrl;
        method.disabled = true;
        document.getElementById('technologyModalTitle').textContent = 'Tambah Teknologi';
        modal.hidden = false;
        document.getElementById('bahasa_pemrograman').focus();
    }

    function editTechnology(button) {
        const data = JSON.parse(button.dataset.technology);
        const form = document.getElementById('technologyForm');
        const method = document.getElementById('technologyFormMethod');

        form.reset();
        form.action = `${form.dataset.updateUrl}/${data.id}`;
        method.disabled = false;
        document.getElementById('technologyModalTitle').textContent = 'Edit Teknologi';

        Object.entries(data).forEach(([name, value]) => {
            const field = document.getElementById(name);
            if (field) field.value = value ?? '';
        });

        document.getElementById('technologyModal').hidden = false;
        document.getElementById('bahasa_pemrograman').focus();
    }

    function closeTechnologyModal() {
        document.getElementById('technologyModal').hidden = true;
    }

    document.getElementById('technologyModal').addEventListener('click', function (event) {
        if (event.target === this) closeTechnologyModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeTechnologyModal();
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (new URLSearchParams(window.location.search).get('tab') === 'technology') {
            showApplicationDetailTab('technology');
        }
    });
</script>
