<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Jadwal</h3>
                <p class="text-subtitle text-muted">Informasi lengkap jadwal praktek dokter</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('jadwal') ?>">Jadwal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Informasi Jadwal</h5>
                    <div>
                        <a href="<?= site_url('jadwal/' . $jadwal['idjadwal'] . '/edit') ?>" class="btn btn-warning me-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="<?= site_url('jadwal') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">ID Jadwal</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $jadwal['idjadwal'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Nama Dokter</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $jadwal['nama_dokter'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Hari</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $jadwal['hari'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Jam Praktek</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $jadwal['jam'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-light-primary">
                            <h6 class="alert-heading">Informasi Jadwal</h6>
                            <p>
                                Jadwal praktek dokter dapat berubah sewaktu-waktu tergantung kondisi praktek dokter gigi.
                                Pastikan untuk selalu mengupdate informasi jadwal terbaru.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?> 