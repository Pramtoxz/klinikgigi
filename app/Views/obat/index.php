<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<!-- isi konten Start -->
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Obat</h3>
                <p class="text-subtitle text-muted">Kelola data obat klinik</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Obat</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">Daftar Obat</h5>
                    <a href="<?= site_url('obat/new') ?>" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus"></i> Tambah Obat
                    </a>
                </div>
            </div>
            <div class="card-body">                
                <div class="table-responsive datatable-minimal mt-12">
                    <table class="table table-hover" id="table-obat">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Obat</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Stok</th>
                                <th>Keterangan</th>
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
    $('#table-obat').DataTable({
        processing: true,
        serverSide: true,
        ajax: "<?= site_url('obat/datatables') ?>",
        order: [[1, 'asc']],
        columns: [
            {data: 'no', orderable: false},
            {data: 'idobat'},
            {data: 'nama'},
            {
                data: 'jenis',
                render: function(data, type, row) {
                    if (data === 'minum') {
                        return '<span class="badge bg-primary">Minum</span>';
                    } else if (data === 'bahan') {
                        return '<span class="badge bg-success">Bahan</span>';
                    } else {
                        return data;
                    }
                }
            },
            {data: 'stok'},
            {data: 'keterangan'},
            {
                data: 'action',
                orderable: false,
                className: 'text-center'
            }
        ],
        columnDefs: [
            {
                targets: [0, 6],
                className: 'text-center',
                orderable: false
            },
            {
                targets: 'no-sort',
                orderable: false
            }
        ]
    });

    // Delete Obat
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Hapus Data',
            text: "Apakah Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= site_url('obat') ?>/${id}/delete`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Berhasil!',
                                response.message,
                                'success'
                            );
                            $('#table-obat').DataTable().ajax.reload();
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?> 