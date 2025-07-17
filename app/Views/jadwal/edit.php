<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Jadwal</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="<?= site_url('jadwal/' . $jadwal['idjadwal']) ?>" method="post" id="form-edit-jadwal">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="idjadwal">ID Jadwal</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="idjadwal" class="form-control" name="idjadwal" value="<?= $jadwal['idjadwal'] ?>" readonly>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="id_dokter">Dokter</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="id_dokter" name="id_dokter" required>
                                    <option value="" disabled>Pilih Dokter</option>
                                    <?php foreach ($dokter as $row) : ?>
                                        <option value="<?= $row['id_dokter'] ?>" <?= ($row['id_dokter'] == $jadwal['iddokter']) ? 'selected' : '' ?>>
                                            <?= $row['nama'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="hari">Hari</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="hari" name="hari" required>
                                    <option value="" disabled>Pilih Hari</option>
                                    <?php foreach ($hari as $h) : ?>
                                        <option value="<?= $h ?>" <?= ($h == $jadwal['hari']) ? 'selected' : '' ?>>
                                            <?= $h ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="waktu_mulai">Waktu Mulai</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="time" id="waktu_mulai" class="form-control" name="waktu_mulai" value="<?= $jadwal['waktu_mulai'] ?>" required>
                                <small class="text-muted">Format: HH:MM (24 jam)</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="waktu_selesai">Waktu Selesai</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="time" id="waktu_selesai" class="form-control" name="waktu_selesai" value="<?= $jadwal['waktu_selesai'] ?>" required>
                                <small class="text-muted">Format: HH:MM (24 jam)</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="is_active">Status</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= $jadwal['is_active'] ? 'checked' : '' ?>>
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

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        <?php if (session()->getFlashdata('errors')) : ?>
            Swal.fire({
                title: 'Periksa Entrian Form',
                html: `<ul style="text-align: left;">
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>`,
                icon: 'error',
                confirmButtonText: 'Baik'
            });
        <?php endif ?>
        
        // Validasi client-side untuk waktu
        $('#form-edit-jadwal').submit(function(e) {
            const waktuMulai = $('#waktu_mulai').val();
            const waktuSelesai = $('#waktu_selesai').val();
            
            if (waktuMulai && waktuSelesai) {
                if (waktuSelesai < waktuMulai) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Periksa Entrian Form',
                        text: 'Waktu selesai harus lebih besar atau sama dengan waktu mulai',
                        icon: 'error',
                        confirmButtonText: 'Baik'
                    });
                }
            }
        });
    });
</script>
<?= $this->endSection() ?> 