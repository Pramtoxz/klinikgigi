<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">

    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Pasien</h4>
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

                <form action="<?= site_url('pasien/' . $pasien['id_pasien']) ?>" method="post" class="form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_pasien">ID Pasien</label>
                                <input type="text" class="form-control" id="id_pasien" value="<?= $pasien['id_pasien'] ?>" readonly>
                                <small class="text-muted">ID Pasien tidak dapat diubah</small>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" value="<?= old('nama', $pasien['nama']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="jenkel">Jenis Kelamin</label>
                                <select class="form-select" id="jenkel" name="jenkel" required>
                                    <option value="" disabled>Pilih jenis kelamin</option>
                                    <option value="L" <?= old('jenkel', $pasien['jenkel']) == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= old('jenkel', $pasien['jenkel']) == 'P' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgllahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tgllahir" name="tgllahir" value="<?= old('tgllahir', $pasien['tgllahir']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="nohp">Nomor HP</label>
                                <input type="text" class="form-control" id="nohp" name="nohp" placeholder="Masukkan nomor HP" value="<?= old('nohp', $pasien['nohp']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required><?= old('alamat', $pasien['alamat']) ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-3">
                        <a href="<?= site_url('pasien') ?>" class="btn btn-secondary me-1 mb-1">Batal</a>
                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
