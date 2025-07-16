<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Jenis Perawatan</h3>
                <p class="text-subtitle text-muted">Form untuk mengubah data jenis perawatan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('jenis') ?>">Jenis Perawatan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Jenis Perawatan</h4>
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

                <form action="<?= site_url('jenis/' . $jenis['idjenis']) ?>" method="post" class="form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idjenis">ID Jenis Perawatan</label>
                                <input type="text" class="form-control" id="idjenis" value="<?= $jenis['idjenis'] ?>" readonly>
                                <small class="text-muted">ID Jenis Perawatan tidak dapat diubah</small>
                            </div>

                            <div class="form-group">
                                <label for="namajenis">Nama Jenis Perawatan</label>
                                <input type="text" class="form-control" id="namajenis" name="namajenis" placeholder="Masukkan nama jenis perawatan" value="<?= old('namajenis', $jenis['namajenis']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estimasi">Estimasi Waktu (menit)</label>
                                <input type="number" class="form-control" id="estimasi" name="estimasi" placeholder="Contoh: 30, 60, 90" value="<?= old('estimasi', $jenis['estimasi']) ?>" min="1" required>
                                <small class="text-muted">Masukkan estimasi waktu dalam menit</small>
                            </div>

                            <div class="form-group">
                                <label for="harga">Harga (Rp)</label>
                                <input type="number" class="form-control" id="harga" name="harga" placeholder="Masukkan harga perawatan" value="<?= old('harga', $jenis['harga']) ?>" min="0" required>
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