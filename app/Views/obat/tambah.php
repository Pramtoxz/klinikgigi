<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">

    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Obat</h4>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <h4 class="alert-heading">Terjadi Kesalahan</h4>
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('obat') ?>" method="post" class="form">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idobat">ID Obat</label>
                                <input type="text" class="form-control" id="idobat" name="idobat" value="<?= $next_id ?>" readonly>
                                <small class="text-muted">ID Obat otomatis dibuat oleh sistem</small>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Obat</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama obat" value="<?= old('nama') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis">Jenis Obat</label>
                                <select class="form-select" id="jenis" name="jenis" required>
                                    <option value="" disabled selected>Pilih jenis obat</option>
                                    <option value="minum" <?= old('jenis') == 'minum' ? 'selected' : '' ?>>Minum</option>
                                    <option value="bahan" <?= old('jenis') == 'bahan' ? 'selected' : '' ?>>Bahan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan obat" required><?= old('keterangan') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-3">
                        <a href="<?= site_url('obat') ?>" class="btn btn-secondary me-1 mb-1">Batal</a>
                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?> 