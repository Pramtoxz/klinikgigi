<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Jadwal</h4>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h4>Periksa Entrian Form</h4>
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif ?>
                
                <form class="form form-horizontal" action="<?= site_url('jadwal') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="idjadwal">ID Jadwal</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="idjadwal" class="form-control" name="idjadwal" value="<?= $next_id ?>" readonly>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="id_dokter">Dokter</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="id_dokter" name="id_dokter" required>
                                    <option value="" selected disabled>Pilih Dokter</option>
                                    <?php foreach ($dokter as $row) : ?>
                                        <option value="<?= $row['id_dokter'] ?>"><?= $row['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="hari">Hari</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="hari" name="hari" required>
                                    <option value="" selected disabled>Pilih Hari</option>
                                    <?php foreach ($hari as $h) : ?>
                                        <option value="<?= $h ?>"><?= $h ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="waktu_mulai">Waktu Mulai</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="time" id="waktu_mulai" class="form-control" name="waktu_mulai" required>
                                <small class="text-muted">Format: HH:MM (24 jam)</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="waktu_selesai">Waktu Selesai</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="time" id="waktu_selesai" class="form-control" name="waktu_selesai" required>
                                <small class="text-muted">Format: HH:MM (24 jam)</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="is_active">Status</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                                <small class="text-muted">Jadwal akan muncul di booking online jika status aktif</small>
                            </div>
                            
                            <div class="col-12 d-flex justify-content-end">
                                <a href="<?= site_url('jadwal') ?>" class="btn btn-secondary me-1 mb-1">Batal</a>
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?> 