<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Jenis Perawatan</h3>
                <p class="text-subtitle text-muted">Informasi lengkap data jenis perawatan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('jenis') ?>">Jenis Perawatan</a></li>
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
                    <h5>Informasi Jenis Perawatan</h5>
                    <div>
                        <a href="<?= site_url('jenis/' . $jenis['idjenis'] . '/edit') ?>" class="btn btn-warning me-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="<?= site_url('jenis') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">ID Jenis</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $jenis['idjenis'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Nama Jenis Perawatan</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $jenis['namajenis'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Estimasi Waktu</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $jenis['estimasi'] ?> menit</p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Harga</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">Rp <?= number_format($jenis['harga'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Terdaftar Sejak</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= date('d F Y H:i', strtotime($jenis['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?> 