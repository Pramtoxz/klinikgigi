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
                    <h5 class="card-title">Kelola Data Booking</h5>
                    <a href="<?= site_url('booking/new') ?>" class="btn btn-primary ">
                        Tambah
                    </a>
                </div>
            </div>
            <div class="card-body">                
                <div class="table-responsive datatable-minimal">
                    <table class="table table-hover" id="table-booking">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Booking</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
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
    // Global error handler
    window.onerror = function(message, source, lineno, colno, error) {
        console.error('JavaScript Error:', message, 'at', source, ':', lineno, ':', colno);
        console.error('Error object:', error);
        alert('Terjadi error JavaScript. Silakan periksa konsol browser (F12) untuk detail.');
        return false;
    };
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
    $('#table-booking').DataTable({
        processing: true,
        serverSide: true,
        ajax: "<?= site_url('booking/datatables') ?>",
        order: [],
        columns: [
            {data: 'no'},
            {data: 'idbooking'},
            {data: 'nama_pasien'},
            {data: 'nama_dokter'},
            {data: 'tanggal'},
            {
                data: null,
                render: function(data, type, row) {
                    return row.waktu_mulai + ' - ' + row.waktu_selesai;
                }
            },
            {data: 'status'},
            {data: 'action'}
        ]
    });

    // Delete Booking
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Anda yakin ingin menghapus data booking ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= site_url('booking') ?>/" + id + "/delete",
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
                                $('#table-booking').DataTable().ajax.reload();
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