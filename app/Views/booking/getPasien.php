<!-- Modal Pilih Pasien -->
<div class="modal fade" id="pasienModal" tabindex="-1" role="dialog" aria-labelledby="pasienModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="pasienModalTitle">Pilih Pasien</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="pasien-loading" class="text-center mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data pasien...</p>
                </div>
                <div id="pasien-error" class="alert alert-danger d-none">
                    Gagal memuat data pasien. Silakan coba lagi.
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="table-pasien">
                        <thead>
                            <tr>
                                <th>ID Pasien</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi melalui AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                    <span>Batal</span>
                </button>
            </div>
        </div>
    </div>
</div> 