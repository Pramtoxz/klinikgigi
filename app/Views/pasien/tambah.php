<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
        <!-- <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Pasien</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('pasien') ?>">Pasien</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>  
        </div> -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Pasien</h4>
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

                <form action="<?= site_url('pasien') ?>" method="post" class="form">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_pasien">ID Pasien</label>
                                <input type="text" class="form-control" id="id_pasien" name="id_pasien" value="<?= $next_number ?>" readonly>
                                <small class="text-muted">ID Pasien otomatis dibuat oleh sistem</small>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" value="<?= old('nama') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="jenkel">Jenis Kelamin</label>
                                <select class="form-select" id="jenkel" name="jenkel" required>
                                    <option value="" disabled selected>Pilih jenis kelamin</option>
                                    <option value="L" <?= old('jenkel') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= old('jenkel') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgllahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tgllahir" name="tgllahir" value="<?= old('tgllahir') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="nohp">Nomor HP</label>
                                <input type="text" class="form-control" id="nohp" name="nohp" placeholder="Masukkan nomor HP" value="<?= old('nohp') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required><?= old('alamat') ?></textarea>
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
