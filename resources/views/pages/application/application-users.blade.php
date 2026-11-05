<link rel="stylesheet" href="{{ asset('css/application-users.css') }}">

<div id="applicationUsersPanel" class="application-users-panel" role="tabpanel" aria-labelledby="applicationUsersTab" hidden>
    <header class="application-users-heading">
        <div class="application-users-heading-icon" aria-hidden="true">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <h2>Pengguna Aplikasi</h2>
            <p>Data pengguna untuk {{ $application['name'] }}.</p>
        </div>
    </header>

    @if($applicationUsers->isNotEmpty())
        <div class="application-users-grid">
            @foreach($applicationUsers as $userGroup)
                <article class="application-user-card">
                    <div class="application-user-card-top">
                        <div class="application-user-type-icon" aria-hidden="true">
                            <i class="fas fa-user-group"></i>
                        </div>
                        <span class="application-user-count">
                            {{ $userGroup->jumlah_pengguna !== null ? number_format($userGroup->jumlah_pengguna) : '-' }}
                            <span>pengguna</span>
                        </span>
                    </div>
                    <div class="application-user-card-body">
                        <p class="application-user-label">Jenis Pengguna</p>
                        <h3>{{ $userGroup->jenis_pengguna ?: '-' }}</h3>
                        <div class="application-user-description">
                            <p class="application-user-label">Keterangan</p>
                            <p>{{ $userGroup->keterangan ?: 'Tidak ada keterangan.' }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="application-users-empty">
            <i class="fas fa-user-slash" aria-hidden="true"></i>
            <h3>Belum ada data pengguna</h3>
            <p>Belum ada informasi pengguna untuk aplikasi ini.</p>
        </div>
    @endif
</div>
