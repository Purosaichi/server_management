<link rel="stylesheet" href="{{ asset('css/delete-confirm.css') }}">

<div id="deleteConfirmModal" class="delete-confirm-overlay" hidden>
    <section class="delete-confirm-dialog" role="alertdialog" aria-modal="true"
             aria-labelledby="deleteConfirmTitle" aria-describedby="deleteConfirmMessage">
        <div class="delete-confirm-icon" aria-hidden="true">⚠️</div>
        <h2 id="deleteConfirmTitle" class="delete-confirm-title">Konfirmasi Hapus</h2>
        <p id="deleteConfirmMessage" class="delete-confirm-message">
            Apakah Anda yakin ingin menghapus data <strong id="deleteConfirmItem"></strong>?
        </p>
        <p class="delete-confirm-warning">Data yang dihapus tidak dapat dikembalikan.</p>
        <div class="delete-confirm-actions">
            <button type="button" class="delete-confirm-cancel" data-delete-cancel>
                Batal
            </button>
            <button type="button" class="delete-confirm-submit" data-delete-submit>
                Ya, Hapus
            </button>
        </div>
    </section>
</div>

<script src="{{ asset('js/delete-confirm.js') }}" defer></script>
