<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">

    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Jenis Perawatan</h4>
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

                <form action="<?= site_url('jenis') ?>" method="post" class="form">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idjenis">ID Jenis Perawatan</label>
                                <input type="text" class="form-control" id="idjenis" name="idjenis" value="<?= $next_id ?>" readonly>
                                <small class="text-muted">ID Jenis Perawatan otomatis dibuat oleh sistem</small>
                            </div>

                            <div class="form-group">
                                <label for="namajenis">Nama Jenis Perawatan</label>
                                <input type="text" class="form-control" id="namajenis" name="namajenis" placeholder="Masukkan nama jenis perawatan" value="<?= old('namajenis') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estimasi">Estimasi Waktu (menit)</label>
                                <input type="number" class="form-control" id="estimasi" name="estimasi" placeholder="Contoh: 30, 60, 90" value="<?= old('estimasi') ?>" min="1" required>
                                <small class="text-muted">Masukkan estimasi waktu dalam menit</small>
                            </div>

                            <div class="form-group">
                                <label for="harga">Harga (Rp)</label>
                                <input type="number" class="form-control" id="harga" name="harga" placeholder="Masukkan harga perawatan" value="<?= old('harga') ?>" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-3">
                        <a href="<?= site_url('jenis') ?>" class="btn btn-secondary me-1 mb-1">Batal</a>
                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?> 