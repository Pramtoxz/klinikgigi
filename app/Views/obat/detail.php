<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Obat</h3>
                <p class="text-subtitle text-muted">Informasi lengkap data obat</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('obat') ?>">Obat</a></li>
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
                    <h5>Informasi Obat</h5>
                    <div>
                        <a href="<?= site_url('obat/' . $obat['idobat'] . '/edit') ?>" class="btn btn-warning me-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="<?= site_url('obat') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">ID Obat</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $obat['idobat'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Nama Obat</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $obat['nama'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Jenis Obat</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">
                                    <?php if ($obat['jenis'] == 'minum'): ?>
                                        <span class="badge bg-primary">Minum</span>
                                    <?php elseif ($obat['jenis'] == 'bahan'): ?>
                                        <span class="badge bg-success">Bahan</span>
                                    <?php else: ?>
                                        <?= $obat['jenis'] ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Stok</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $obat['stok'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Keterangan</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $obat['keterangan'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Terdaftar Sejak</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= date('d F Y H:i', strtotime($obat['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?> 