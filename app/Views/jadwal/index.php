<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <!-- <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Jadwal Praktek Dokter</h3>
                <p class="text-subtitle text-muted">Kelola jadwal praktek dokter gigi</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jadwal</li>
                    </ol>
                </nav>
            </div>
        </div> -->
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">Daftar Jadwal Praktek</h5>
                    <a href="<?= site_url('jadwal/new') ?>" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus"></i> Tambah Jadwal
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-jadwal">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Jadwal</th>
                                <th>Nama Dokter</th>
                                <th>Hari</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($jadwal as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['idjadwal'] ?></td>
                                    <td><?= $row['nama_dokter'] ?></td>
                                    <td><?= $row['hari'] ?></td>
                                    <td><?= date('H:i', strtotime($row['waktu_mulai'])) ?></td>
                                    <td><?= $row['waktu_selesai'] ? date('H:i', strtotime($row['waktu_selesai'])) : '-' ?></td>
                                    <td>
                                        <?php if ($row['is_active']): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="<?= site_url('jadwal/' . $row['idjadwal']) ?>" class="btn btn-info btn-sm me-1">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= site_url('jadwal/' . $row['idjadwal'] . '/edit') ?>" class="btn btn-warning btn-sm me-1">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-primary btn-sm me-1 btn-toggle-status" data-id="<?= $row['idjadwal'] ?>" data-status="<?= $row['is_active'] ?>">
                                                <i class="bi bi-toggle-<?= $row['is_active'] ? 'on' : 'off' ?>"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<?= $row['idjadwal'] ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            
                            <?php if (empty($jadwal)) : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data jadwal</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        // Menampilkan flash message jika ada
        <?php if (session()->getFlashdata('message')) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= session()->getFlashdata('message') ?>',
            });
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= session()->getFlashdata('error') ?>',
            });
        <?php endif; ?>
        
        // DataTable
        $('#table-jadwal').DataTable({
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
        
        // Toggle status jadwal
        $('.btn-toggle-status').on('click', function() {
            const id = $(this).data('id');
            const currentStatus = $(this).data('status');
            const newStatus = currentStatus ? 'nonaktif' : 'aktif';
            
            Swal.fire({
                title: 'Ubah Status',
                text: `Apakah Anda yakin ingin mengubah status jadwal menjadi ${newStatus}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= site_url('jadwal') ?>/${id}/toggleActive`,
                        type: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan pada server',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
        
        // Delete confirmation
        $('.btn-delete').on('click', function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Hapus Jadwal',
                text: "Apakah Anda yakin ingin menghapus jadwal ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send delete request
                    $.ajax({
                        url: "<?= site_url('jadwal') ?>/" + id + "/delete",
                        type: "DELETE",
                        dataType: "json",
                        success: function(data) {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus data',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?> 