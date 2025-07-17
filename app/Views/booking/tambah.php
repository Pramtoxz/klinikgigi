<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Booking Offline</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="<?= site_url('booking') ?>" method="post" id="form-tambah-booking">
                    <?= csrf_field() ?>
                    
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="idbooking">ID Booking</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="idbooking" class="form-control" name="idbooking" value="<?= $next_number ?>" readonly>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="id_pasien">Pasien</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div class="input-group">
                                    <input type="hidden" id="id_pasien" name="id_pasien" required>
                                    <input type="text" id="nama_pasien" class="form-control" placeholder="Pilih Pasien" readonly required>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#pasienModal">
                                        <i class="bi bi-search"></i> Pilih Pasien
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="idjadwal">Jadwal Dokter</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div class="input-group">
                                    <input type="hidden" id="idjadwal" name="idjadwal" required>
                                    <input type="text" id="info_jadwal" class="form-control" placeholder="Pilih Jadwal Dokter" readonly required>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#dokterModal">
                                        <i class="bi bi-search"></i> Pilih Jadwal
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="error-jadwal"></div>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="tanggal">Tanggal Kunjungan</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="date" id="tanggal" class="form-control" name="tanggal" required
                                       min="<?= date('Y-m-d') ?>">
                                <small class="text-muted">Pilih tanggal mulai hari ini</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="blok_waktu">Blok Waktu</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="blok_waktu" name="blok_waktu" required>
                                    <option value="" selected disabled>Pilih Blok Waktu</option>
                                    <?php foreach ($blok_waktu as $blok) : ?>
                                        <option value="<?= $blok ?>"><?= $blok ?> (<?= $blok == 'Pagi' ? '08:00 - 12:00' : '13:00 - 23:00' ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="idjenis">Jenis Perawatan</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="idjenis" name="idjenis" required>
                                    <option value="" selected disabled>Pilih Jenis Perawatan</option>
                                    <?php foreach ($jenis as $row) : ?>
                                        <option value="<?= $row['idjenis'] ?>" data-durasi="<?= $row['estimasi'] ?>">
                                            <?= $row['namajenis'] ?> (Durasi: <?= $row['estimasi'] ?> menit)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="waktu_estimasi">Estimasi Waktu</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div id="estimasi-waktu-container" class="d-none alert alert-light-primary">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <div>
                                            <div>Estimasi waktu kedatangan: <span id="estimasi-waktu">-</span></div>
                                            <div>Durasi perawatan: <span id="durasi-perawatan">-</span> menit</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="no-slot-message" class="d-none alert alert-light-danger">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    Tidak ada slot waktu tersedia pada jadwal dan blok waktu yang dipilih
                                </div>
                                <button type="button" id="check-slot" class="btn btn-sm btn-info mb-2">
                                    <i class="bi bi-calendar-check"></i> Cek Ketersediaan Slot
                                </button>
                                <!-- Hidden input to store the calculated time -->
                                <input type="hidden" id="waktu_mulai_hidden" name="waktu_mulai">
                                <input type="hidden" id="waktu_selesai_hidden" name="waktu_selesai">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="catatan">Catatan (Opsional)</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                            </div>
                            
                            <div class="col-12 d-flex justify-content-end">
                                <a href="<?= site_url('booking') ?>" class="btn btn-secondary me-1 mb-1">Batal</a>
                                <button type="submit" class="btn btn-primary me-1 mb-1" id="btn-submit" disabled>Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Include modals -->
<?= $this->include('booking/getPasien') ?>
<?= $this->include('booking/getDokter') ?>

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
        
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({
                title: 'Error',
                text: '<?= session()->getFlashdata('error') ?>',
                icon: 'error',
                confirmButtonText: 'Baik'
            });
        <?php endif ?>
        
        // =================================================================
        // Inisialisasi Modal Pasien
        // =================================================================
        let pasienTable;
        
        // Event ketika modal pasien ditampilkan
        $('#pasienModal').on('shown.bs.modal', function() {
            console.log('Modal pasien dibuka');
            $('#pasien-loading').removeClass('d-none');
            $('#pasien-error').addClass('d-none');
            
            // Inisialisasi atau reload DataTable
            if ($.fn.DataTable.isDataTable('#table-pasien')) {
                pasienTable.ajax.reload();
            } else {
                try {
                    pasienTable = $('#table-pasien').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "<?= site_url('pasien/datatables') ?>",
                            dataSrc: function(json) {
                                console.log('Data pasien yang diterima:', json);
                                $('#pasien-loading').addClass('d-none');
                                if (!json.data || json.data.length === 0) {
                                    console.log('Tidak ada data pasien ditemukan');
                                }
                                return json.data || [];
                            },
                            error: function(xhr, error, thrown) {
                                console.error('Error saat mengambil data pasien:', error);
                                $('#pasien-loading').addClass('d-none');
                                $('#pasien-error').removeClass('d-none');
                            }
                        },
                        columns: [
                            {data: 'id_pasien'},
                            {data: 'nama'},
                            {data: 'nohp'},
                            {data: 'alamat'},
                            {
                                data: null,
                                render: function(data) {
                                    return `<button type="button" class="btn btn-sm btn-primary btn-pilih-pasien" 
                                            data-id="${data.id_pasien}" data-nama="${data.nama}">
                                            <i class="bi bi-check-circle"></i> Pilih
                                            </button>`;
                                }
                            }
                        ],
                        language: {
                            zeroRecords: "Tidak ada data pasien ditemukan",
                            processing: "Memuat data...",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            infoEmpty: "Tidak ada data yang ditampilkan",
                            search: "Cari:",
                            paginate: {
                                first: "Pertama",
                                last: "Terakhir",
                                next: "Selanjutnya",
                                previous: "Sebelumnya"
                            }
                        }
                    });
                } catch (e) {
                    console.error('Error saat inisialisasi DataTable pasien:', e);
                    $('#pasien-loading').addClass('d-none');
                    $('#pasien-error').removeClass('d-none');
                }
            }
        });
        
        // Destroy datatable saat modal ditutup untuk mencegah konflik
        $('#pasienModal').on('hidden.bs.modal', function () {
            if ($.fn.DataTable.isDataTable('#table-pasien')) {
                // Jangan destroy, hanya clear untuk mencegah lag saat dibuka kembali
                pasienTable.clear().draw();
            }
        });
        
        // Event handler untuk tombol pilih pasien
        $(document).on('click', '.btn-pilih-pasien', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            
            // Set nilai ke form
            $('#id_pasien').val(id);
            $('#nama_pasien').val(nama);
            
            // Tutup modal
            $('#pasienModal').modal('hide');
        });
        
        // =================================================================
        // Inisialisasi Modal Jadwal Dokter
        // =================================================================
        let jadwalTable;
        
        // Event ketika modal jadwal ditampilkan
        $('#dokterModal').on('shown.bs.modal', function() {
            console.log('Modal jadwal dokter dibuka');
            $('#jadwal-loading').removeClass('d-none');
            $('#jadwal-error').addClass('d-none');
            
            // Inisialisasi atau reload DataTable
            if ($.fn.DataTable.isDataTable('#table-jadwal')) {
                jadwalTable.ajax.reload();
            } else {
                try {
                    jadwalTable = $('#table-jadwal').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "<?= site_url('jadwal/datatables') ?>",
                            dataSrc: function(json) {
                                console.log('Data jadwal yang diterima:', json);
                                $('#jadwal-loading').addClass('d-none');
                                if (!json.data || json.data.length === 0) {
                                    console.log('Tidak ada data jadwal ditemukan');
                                }
                                return json.data || [];
                            },
                            error: function(xhr, error, thrown) {
                                console.error('Error saat mengambil data jadwal:', error);
                                $('#jadwal-loading').addClass('d-none');
                                $('#jadwal-error').removeClass('d-none');
                            }
                        },
                        columns: [
                            {data: 'idjadwal'},
                            {data: 'nama_dokter'},
                            {data: 'hari'},
                            {
                                data: null,
                                render: function(data) {
                                    // Pastikan format waktu yang benar
                                    const waktuMulai = data.waktu_mulai ? data.waktu_mulai.substring(0, 5) : '00:00';
                                    const waktuSelesai = data.waktu_selesai ? data.waktu_selesai.substring(0, 5) : '00:00';
                                    return `${waktuMulai} - ${waktuSelesai}`;
                                }
                            },
                            {
                                data: 'is_active',
                                render: function(data) {
                                    return data == 1 ? 
                                        '<span class="badge bg-success">Aktif</span>' : 
                                        '<span class="badge bg-danger">Tidak Aktif</span>';
                                }
                            },
                            {
                                data: null,
                                render: function(data) {
                                    if(data.is_active == 1) {
                                        return `<button type="button" class="btn btn-sm btn-primary btn-pilih-jadwal" 
                                                data-id="${data.idjadwal}" 
                                                data-dokter="${data.nama_dokter}"
                                                data-hari="${data.hari}"
                                                data-waktu="${data.waktu_mulai ? data.waktu_mulai.substring(0, 5) : '00:00'} - ${data.waktu_selesai ? data.waktu_selesai.substring(0, 5) : '00:00'}">
                                                <i class="bi bi-check-circle"></i> Pilih
                                                </button>`;
                                    } else {
                                        return '<button type="button" class="btn btn-sm btn-secondary" disabled>Tidak Tersedia</button>';
                                    }
                                }
                            }
                        ],
                        language: {
                            zeroRecords: "Tidak ada data jadwal ditemukan",
                            processing: "Memuat data...",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            infoEmpty: "Tidak ada data yang ditampilkan",
                            search: "Cari:",
                            paginate: {
                                first: "Pertama",
                                last: "Terakhir",
                                next: "Selanjutnya",
                                previous: "Sebelumnya"
                            }
                        }
                    });
                } catch (e) {
                    console.error('Error saat inisialisasi DataTable jadwal:', e);
                    $('#jadwal-loading').addClass('d-none');
                    $('#jadwal-error').removeClass('d-none');
                }
            }
        });
        
        // Destroy datatable saat modal ditutup untuk mencegah konflik
        $('#dokterModal').on('hidden.bs.modal', function () {
            if ($.fn.DataTable.isDataTable('#table-jadwal')) {
                // Jangan destroy, hanya clear untuk mencegah lag saat dibuka kembali
                jadwalTable.clear().draw();
            }
            $('#jadwal-loading').addClass('d-none');
            $('#jadwal-error').addClass('d-none');
        });
        
        // Event handler untuk tombol pilih jadwal
        $(document).on('click', '.btn-pilih-jadwal', function() {
            const id = $(this).data('id');
            const dokter = $(this).data('dokter');
            const hari = $(this).data('hari');
            const waktu = $(this).data('waktu');
            
            // Set nilai ke form
            $('#idjadwal').val(id);
            $('#info_jadwal').val(`${dokter} - ${hari} (${waktu})`);
            
            // Tutup modal
            $('#dokterModal').modal('hide');
        });
        
        // =================================================================
        // Cek Ketersediaan Slot
        // =================================================================
        // Function to check slot availability
        $('#check-slot').on('click', function() {
            const idjadwal = $('#idjadwal').val();
            const tanggal = $('#tanggal').val();
            const blok_waktu = $('#blok_waktu').val();
            const idjenis = $('#idjenis').val();
            
            // Validasi input
            if (!idjadwal || !tanggal || !blok_waktu || !idjenis) {
                Swal.fire({
                    title: 'Perhatian',
                    text: 'Harap isi semua field terlebih dahulu!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Show loading
            Swal.fire({
                title: 'Memeriksa ketersediaan slot...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Cek ketersediaan slot
            $.ajax({
                url: "<?= site_url('get-available-slot') ?>",
                type: "GET",
                data: {
                    idjadwal: idjadwal,
                    tanggal: tanggal,
                    blok_waktu: blok_waktu,
                    idjenis: idjenis
                },
                dataType: "json",
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        const durasi = response.data.durasi;
                        const waktuMulai = response.data.waktu_mulai;
                        const waktuSelesai = response.data.waktu_selesai;
                        
                        // Tampilkan estimasi waktu
                        $('#estimasi-waktu').text(formatTime(waktuMulai));
                        $('#durasi-perawatan').text(durasi);
                        $('#estimasi-waktu-container').removeClass('d-none');
                        $('#no-slot-message').addClass('d-none');
                        
                        // Set nilai hidden input
                        $('#waktu_mulai_hidden').val(waktuMulai);
                        $('#waktu_selesai_hidden').val(waktuSelesai);
                        
                        // Enable submit button
                        $('#btn-submit').prop('disabled', false);
                    } else {
                        $('#estimasi-waktu-container').addClass('d-none');
                        $('#no-slot-message').removeClass('d-none');
                        $('#btn-submit').prop('disabled', true);
                        
                        Swal.fire({
                            title: 'Tidak Ada Slot Tersedia',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.error('Error:', error);
                    
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan pada server',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
        
        // Reset estimasi waktu ketika ada perubahan pada input
        $('#idjadwal, #tanggal, #blok_waktu, #idjenis').on('change', function() {
            $('#estimasi-waktu-container').addClass('d-none');
            $('#no-slot-message').addClass('d-none');
            $('#btn-submit').prop('disabled', true);
        });
        
        // Format waktu HH:MM:SS menjadi HH:MM
        function formatTime(timeString) {
            const time = new Date('2000-01-01T' + timeString);
            return time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    });
</script>
<?= $this->endSection() ?> 