<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<!-- isi konten Start -->
<div class="page-heading">
    <div class="page-title">

    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">Kelola Data Pasien</h5>
                    <a href="<?= site_url('pasien/new') ?>" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="card-body">                
                <div class="table-responsive datatable-minimal mt-12">
                    <table class="table table-hover" id="table-pasien">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pasien</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Nomor HP</th>
                                <th>Akun</th>
                                <th class="no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via DataTables -->
                        </tbody>
                    </table>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAddUser">
                <div class="modal-body">
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
                    <input type="hidden" id="id-pasien" name="id_pasien">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- isi konten end -->
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

    // Initialize DataTable
    $('#table-pasien').DataTable({
        processing: true,
        serverSide: true,
        ajax: "<?= site_url('pasien/datatables') ?>",
        order: [[1, 'asc']],
        columns: [
            {data: 'no', orderable: false},
            {data: 'id_pasien'},
            {data: 'nama'},
            {data: 'jenkel'},
            {data: 'tgllahir'},
            {data: 'nohp'},
            {
                data: null,
                render: function(data, type, row) {
                    if (row.iduser) {
                        return '<span class="badge bg-success">Terdaftar</span>';
                    } else {
                        return '<span class="badge bg-warning">Belum Ada</span> ' +
                            '<i class="bi bi-key-fill btn-user-add" ' +
                            'data-bs-toggle="modal" ' +
                            'data-bs-target="#modalAddUser" ' +
                            'data-id="' + row.id_pasien + '" ' +
                            'data-nama="' + row.nama + '" ' +
                            'style="cursor: pointer;" ' +
                            'title="Buat Akun User"></i>';
                    }
                }
            },
            {
                data: 'action',
                orderable: false,
                className: 'text-center'
            }
        ],
        columnDefs: [
            {
                targets: [0, 7],
                className: 'text-center',
                orderable: false
            },
            {
                targets: 'no-sort',
                orderable: false
            }
        ]
    });

    // Modal Add User
    $(document).on('click', '.btn-user-add', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        $('#id-pasien').val(id);
        $('#nama-pasien').val(nama);
        
        // Reset form dan validasi
        $('#formAddUser').trigger('reset');
        $('.is-invalid').removeClass('is-invalid');
    });

    // Form Add User
    $('#formAddUser').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const id = $('#id-pasien').val();
        
        $.ajax({
            url: "<?= site_url('pasien') ?>/" + id + "/create-user",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                if (data.status === 'success') {
                    $('#modalAddUser').modal('hide');
                    Swal.fire({
                        title: 'Sukses!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#table-pasien').DataTable().ajax.reload();
                    });
                } else {
                    if (data.errors) {
                        // Tampilkan error validasi
                        if (data.errors.username) {
                            $('[name="username"]').addClass('is-invalid');
                            $('#error-username').text(data.errors.username);
                        }
                        if (data.errors.email) {
                            $('[name="email"]').addClass('is-invalid');
                            $('#error-email').text(data.errors.email);
                        }
                        if (data.errors.password) {
                            $('[name="password"]').addClass('is-invalid');
                            $('#error-password').text(data.errors.password);
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
    });

    // Delete Pasien
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        
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
                $.ajax({
                    url: "<?= site_url('pasien') ?>/" + id + "/delete",
                    type: "DELETE",
                    dataType: "json",
                    success: function(data) {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Sukses!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#table-pasien').DataTable().ajax.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
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
                            text: 'Terjadi kesalahan pada server',
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
