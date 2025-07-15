<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/vendors/simple-datatables/style.css') ?>">

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Daftar Pasien</h5>
                    <a href="<?= site_url('pasien/new') ?>" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus"></i> Tambah Pasien
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table-pasien">
                    <thead>
                        <tr>
                            <th>ID Pasien</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Nomor HP</th>
                            <th>Akun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pasien as $p): ?>
                        <tr>
                            <td><?= $p['id_pasien'] ?></td>
                            <td><?= $p['nama'] ?></td>
                            <td><?= $p['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td><?= date('d/m/Y', strtotime($p['tgllahir'])) ?></td>
                            <td><?= $p['nohp'] ?></td>
                            <td>
                                <?php if ($p['iduser']): ?>
                                    <span class="badge bg-success">Terdaftar</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Belum Ada</span>
                                    <i class="bi bi-key-fill btn-user-add ms-1" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#modalAddUser"
                                       data-id="<?= $p['id_pasien'] ?>"
                                       data-nama="<?= $p['nama'] ?>"
                                       title="Buat Akun User"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="<?= site_url('pasien/' . $p['id_pasien']) ?>" class="btn btn-info btn-sm me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= site_url('pasien/' . $p['id_pasien'] . '/edit') ?>" class="btn btn-warning btn-sm me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $p['id_pasien'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="modalAddUserTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="modalAddUserTitle">Buat Akun User untuk Pasien</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formAddUser">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pasien</label>
                        <input type="text" class="form-control" id="nama-pasien" readonly>
                        <input type="hidden" id="id-pasien" name="id_pasien">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" required>
                        <div class="invalid-feedback" id="error-username"></div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                        <div class="invalid-feedback" id="error-email"></div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required>
                        <div class="invalid-feedback" id="error-password"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary rounded-pill" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="<?= base_url('assets/vendors/simple-datatables/simple-datatables.js') ?>"></script>
<script>
    // Simple Datatable
    let tablePasien = document.querySelector('#table-pasien');
    let dataTable = new simpleDatatables.DataTable(tablePasien);

    // Modal Add User
    const modalAddUser = document.getElementById('modalAddUser');
    modalAddUser.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        
        document.getElementById('id-pasien').value = id;
        document.getElementById('nama-pasien').value = nama;
    });

    // Form Add User
    const formAddUser = document.getElementById('formAddUser');
    formAddUser.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(formAddUser);
        const id = document.getElementById('id-pasien').value;
        
        fetch(`<?= site_url('pasien') ?>/${id}/create-user`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Sukses!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                if (data.errors) {
                    // Tampilkan error validasi
                    if (data.errors.username) {
                        document.querySelector('[name="username"]').classList.add('is-invalid');
                        document.getElementById('error-username').textContent = data.errors.username;
                    }
                    if (data.errors.email) {
                        document.querySelector('[name="email"]').classList.add('is-invalid');
                        document.getElementById('error-email').textContent = data.errors.email;
                    }
                    if (data.errors.password) {
                        document.querySelector('[name="password"]').classList.add('is-invalid');
                        document.getElementById('error-password').textContent = data.errors.password;
                    }
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Terjadi kesalahan saat membuat akun user',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan pada server',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });

    // Delete Pasien
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Anda yakin ingin menghapus data pasien ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`<?= site_url('pasien') ?>/${id}/delete`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Sukses!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan pada server',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
    });

    // Reset form ketika modal ditutup
    modalAddUser.addEventListener('hidden.bs.modal', function () {
        formAddUser.reset();
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
    });
</script>
<?= $this->endSection() ?>
