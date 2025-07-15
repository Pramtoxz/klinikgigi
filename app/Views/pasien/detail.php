<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Pasien</h3>
                <p class="text-subtitle text-muted">Informasi lengkap data pasien</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('pasien') ?>">Pasien</a></li>
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
                    <h5>Informasi Pasien</h5>
                    <div>
                        <a href="<?= site_url('pasien/' . $pasien['id_pasien'] . '/edit') ?>" class="btn btn-warning me-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="<?= site_url('pasien') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">ID Pasien</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $pasien['id_pasien'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Nama Lengkap</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $pasien['nama'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Jenis Kelamin</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $pasien['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Tanggal Lahir</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= date('d F Y', strtotime($pasien['tgllahir'])) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Nomor HP</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $pasien['nohp'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Alamat</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= $pasien['alamat'] ?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Status Akun</label>
                            <div class="col-md-8">
                                <?php if ($pasien['iduser']): ?>
                                    <span class="badge bg-success">Terdaftar</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Belum Ada</span>
                                    <button class="btn btn-sm btn-primary ms-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalAddUser"
                                            data-id="<?= $pasien['id_pasien'] ?>"
                                            data-nama="<?= $pasien['nama'] ?>">
                                        <i class="bi bi-key-fill"></i> Buat Akun
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label">Terdaftar Sejak</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext"><?= date('d F Y H:i', strtotime($pasien['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
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
<script>
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

    // Reset form ketika modal ditutup
    modalAddUser.addEventListener('hidden.bs.modal', function () {
        formAddUser.reset();
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
    });
</script>
<?= $this->endSection() ?>
