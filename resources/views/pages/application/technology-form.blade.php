<div id="technologyModal" class="technology-modal" hidden>
    <div class="technology-modal-card" role="dialog" aria-modal="true" aria-labelledby="technologyModalTitle">
        <div class="technology-modal-header">
            <h2 id="technologyModalTitle">Tambah Teknologi</h2>
            <button type="button" class="technology-modal-close" aria-label="Tutup" onclick="closeTechnologyModal()">&times;</button>
        </div>
        <form id="technologyForm"
              data-store-url="{{ route('application.technology.store', $application['id']) }}"
              data-update-url="{{ url('/application/'.$application['id'].'/technology') }}"
              action="{{ route('application.technology.store', $application['id']) }}"
              method="POST">
            @csrf
            <input id="technologyFormMethod" type="hidden" name="_method" value="PUT" disabled>
            <div class="technology-form-grid">
                <div class="technology-form-field">
                    <label for="bahasa_pemrograman">Bahasa Pemrograman</label>
                    <input type="text" id="bahasa_pemrograman" name="bahasa_pemrograman" maxlength="200">
                </div>
                <div class="technology-form-field">
                    <label for="kerangka_pengembangan">Kerangka Pengembangan</label>
                    <input type="text" id="kerangka_pengembangan" name="kerangka_pengembangan" maxlength="200">
                </div>
                <div class="technology-form-field">
                    <label for="metode_pengembangan">Metode Pengembangan</label>
                    <input type="text" id="metode_pengembangan" name="metode_pengembangan" maxlength="200">
                </div>
                <div class="technology-form-field">
                    <label for="model_pengembangan">Model Pengembangan</label>
                    <input type="text" id="model_pengembangan" name="model_pengembangan" maxlength="200">
                </div>
                <div class="technology-form-field">
                    <label for="basis_data">Basis Data</label>
                    <input type="text" id="basis_data" name="basis_data" maxlength="200">
                </div>
                <div class="technology-form-field">
                    <label for="sistem_operasi_server">Sistem Operasi Server</label>
                    <input type="text" id="sistem_operasi_server" name="sistem_operasi_server" maxlength="200">
                </div>
                <div class="technology-form-field">
                    <label for="layanan_infrastruktur">Layanan Infrastruktur</label>
                    <input type="text" id="layanan_infrastruktur" name="layanan_infrastruktur" maxlength="200">
                </div>
                <div class="technology-form-field">
                    <label for="mekanisme_pembangunan">Mekanisme Pembangunan</label>
                    <input type="text" id="mekanisme_pembangunan" name="mekanisme_pembangunan" maxlength="100">
                </div>
                <div class="technology-form-field technology-form-field-wide">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"></textarea>
                </div>
            </div>
            <div class="technology-modal-actions">
                <button type="button" class="technology-cancel" onclick="closeTechnologyModal()">Batal</button>
                <button type="submit" class="technology-save">Simpan</button>
            </div>
        </form>
    </div>
</div>
