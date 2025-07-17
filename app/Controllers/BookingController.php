<?php

namespace App\Controllers;

use App\Models\Booking;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Jadwal;
use App\Models\Jenis;
use CodeIgniter\RESTful\ResourceController;
use Hermawan\DataTables\DataTable;

class BookingController extends ResourceController
{
    protected $bookingModel;
    protected $pasienModel;
    protected $dokterModel;
    protected $jadwalModel;
    protected $jenisModel;
    
    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->pasienModel = new Pasien();
        $this->dokterModel = new Dokter();
        $this->jadwalModel = new Jadwal();
        $this->jenisModel = new Jenis();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Data Booking',
        ];
        
        return view('booking/index', $data);
    }
    
    public function getDataTables()
    {
        $db = db_connect();
        $builder = $db->table('booking')
            ->select('
                booking.idbooking,
                pasien.nama as nama_pasien,
                dokter.nama as nama_dokter,
                booking.tanggal,
                booking.waktu_mulai,
                booking.waktu_selesai,
                booking.status
            ')
            ->join('pasien', 'pasien.id_pasien = booking.id_pasien')
            ->join('jadwal', 'jadwal.idjadwal = booking.idjadwal')
            ->join('dokter', 'dokter.id_dokter = jadwal.iddokter');
        
        return DataTable::of($builder)
            ->addNumbering('no')
            ->add('action', function($row){
                return '
                <div class="d-flex">
                    <a href="'.site_url('booking/'.$row->idbooking).'" class="btn btn-info btn-sm me-1">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="'.site_url('booking/'.$row->idbooking.'/edit').'" class="btn btn-warning btn-sm me-1">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="'.$row->idbooking.'">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                ';
            })
            ->format('tanggal', function($value){
                return date('d/m/Y', strtotime($value));
            })
            ->format('waktu_mulai', function($value){
                return date('H:i', strtotime($value));
            })
            ->format('waktu_selesai', function($value){
                return date('H:i', strtotime($value));
            })
            ->format('status', function($value){
                if ($value == 'diproses') {
                    return '<span class="badge bg-warning">Diproses</span>';
                } else if ($value == 'diterima') {
                    return '<span class="badge bg-success">Diterima</span>';
                } else if ($value == 'ditolak') {
                    return '<span class="badge bg-danger">Ditolak</span>';
                }
                return $value;
            })
            ->toJson(true);
    }
    
    public function show($id = null)
    {
        $db = db_connect();
        $booking = $db->table('booking')
            ->select('
                booking.*,
                pasien.nama as nama_pasien,
                dokter.nama as nama_dokter,
                jenis_perawatan.namajenis as jenis_perawatan,
                jenis_perawatan.estimasi as durasi_perawatan
            ')
            ->join('pasien', 'pasien.id_pasien = booking.id_pasien')
            ->join('jadwal', 'jadwal.idjadwal = booking.idjadwal')
            ->join('dokter', 'dokter.id_dokter = jadwal.iddokter')
            ->join('jenis_perawatan', 'jenis_perawatan.idjenis = booking.idjenis')
            ->where('booking.idbooking', $id)
            ->get()
            ->getRowArray();
        
        if (!$booking) {
            return redirect()->to(site_url('booking'))->with('error', 'Data booking tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Booking',
            'booking' => $booking
        ];
        
        return view('booking/detail', $data);
    }
    
    public function new()
    {
        // Generate ID Booking dengan format BK0001, BK0002, dst
        $db = db_connect();
        $tanggal = date('Ymd');
        $query = $db->query("SELECT CONCAT('BK', '$tanggal', LPAD(IFNULL(MAX(SUBSTRING(idbooking, 11)) + 1, 1), 4, '0')) AS next_number FROM booking WHERE idbooking LIKE 'BK$tanggal%'");
        $row = $query->getRow();
        $next_number = $row->next_number;
        
        $data = [
            'title' => 'Tambah Booking Offline',
            'next_number' => $next_number,
            'jenis' => $this->jenisModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        
        return view('booking/tambah', $data);
    }
    
    public function create()
    {
        $rules = [
            'idbooking' => [
                'rules' => 'required|is_unique[booking.idbooking]',
                'errors' => [
                    'required' => 'ID Booking harus diisi',
                    'is_unique' => 'ID Booking sudah terdaftar'
                ]
            ],
            'id_pasien' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pasien harus dipilih'
                ]
            ],
            'idjadwal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jadwal dokter harus dipilih'
                ]
            ],
            'idjenis' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jenis perawatan harus dipilih'
                ]
            ],
            'tanggal' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $idjadwal = $this->request->getPost('idjadwal');
        $tanggal = $this->request->getPost('tanggal');
        $idjenis = $this->request->getPost('idjenis');
        
        // Dapatkan jadwal dokter
        $jadwal = $this->jadwalModel->find($idjadwal);
        if (!$jadwal) {
            return redirect()->back()->withInput()->with('error', 'Jadwal dokter tidak ditemukan');
        }
        
        // Validasi apakah tanggal sesuai dengan hari jadwal dokter
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dayOfWeek = date('w', strtotime($tanggal)); // 0 (Minggu) sampai 6 (Sabtu)
        $dayName = $dayNames[$dayOfWeek];
        
        if ($dayName !== $jadwal['hari']) {
            return redirect()->back()->withInput()->with('error', "Tanggal {$tanggal} bukan hari {$jadwal['hari']}. Silakan pilih tanggal yang sesuai.");
        }
        
        // Validasi apakah waktu jadwal untuk hari ini sudah lewat
        if ($tanggal == date('Y-m-d')) {
            $currentTime = date('H:i:s');
            if ($currentTime > $jadwal['waktu_mulai']) {
                return redirect()->back()->withInput()->with('error', "Jadwal dokter untuk hari ini pada pukul " . substr($jadwal['waktu_mulai'], 0, 5) . " sudah lewat. Silakan pilih tanggal lain.");
            }
        }
        
        // Dapatkan durasi jenis perawatan
        $jenis = $this->jenisModel->find($idjenis);
        $durasi_menit = $jenis['estimasi'];
        
        // Cari slot waktu yang tersedia berdasarkan jadwal dokter
        $slot = $this->bookingModel->findAvailableSlotFromSchedule($idjadwal, $tanggal, $durasi_menit);
        
        if (!$slot) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada slot waktu tersedia pada jadwal yang dipilih');
        }
        
        $data = [
            'idbooking' => $this->request->getPost('idbooking'),
            'id_pasien' => $this->request->getPost('id_pasien'),
            'idjadwal' => $idjadwal,
            'idjenis' => $idjenis,
            'tanggal' => $tanggal,
            'waktu_mulai' => $slot['waktu_mulai'],
            'waktu_selesai' => $slot['waktu_selesai'],
            'status' => 'diterima', // Langsung diterima karena dari admin
            'catatan' => $this->request->getPost('catatan')
        ];
        
        $this->bookingModel->insert($data);
        
        return redirect()->to(site_url('booking'))->with('message', 'Booking berhasil ditambahkan');
    }
    
    public function edit($id = null)
    {
        $db = db_connect();
        $booking = $db->table('booking')
            ->select('booking.*, jadwal.hari')
            ->join('jadwal', 'jadwal.idjadwal = booking.idjadwal')
            ->where('booking.idbooking', $id)
            ->get()
            ->getRowArray();
        
        if (!$booking) {
            return redirect()->to(site_url('booking'))->with('error', 'Data booking tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Booking',
            'booking' => $booking,
            'pasien' => $this->pasienModel->findAll(),
            'jadwal' => $this->getJadwalWithDokter(),
            'jenis' => $this->jenisModel->findAll(),
            'status' => ['diproses', 'diterima', 'ditolak'],
            'validation' => \Config\Services::validation()
        ];
        
        return view('booking/edit', $data);
    }
    
    public function update($id = null)
    {
        $booking = $this->bookingModel->find($id);
        
        if (!$booking) {
            return redirect()->to(site_url('booking'))->with('error', 'Data booking tidak ditemukan');
        }
        
        $rules = [
            'status' => [
                'rules' => 'required|in_list[diproses,diterima,ditolak]',
                'errors' => [
                    'required' => 'Status harus dipilih',
                    'in_list' => 'Status tidak valid'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'status' => $this->request->getPost('status'),
            'catatan' => $this->request->getPost('catatan')
        ];
        
        $this->bookingModel->update($id, $data);
        
        return redirect()->to(site_url('booking'))->with('message', 'Data booking berhasil diperbarui');
    }
    
    public function delete($id = null)
    {
        $booking = $this->bookingModel->find($id);
        
        if (!$booking) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data booking tidak ditemukan'
            ]);
        }
        
        $this->bookingModel->delete($id);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data booking berhasil dihapus'
        ]);
    }
    
    // Method untuk mendapatkan jadwal dengan nama dokter
    private function getJadwalWithDokter()
    {
        $db = db_connect();
        $jadwal = $db->table('jadwal')
            ->select('jadwal.*, dokter.nama as nama_dokter')
            ->join('dokter', 'dokter.id_dokter = jadwal.iddokter')
            ->where('jadwal.is_active', 1)
            ->get()
            ->getResultArray();
        
        return $jadwal;
    }
    
    // API untuk mendapatkan slot waktu tersedia
    public function getAvailableSlot()
    {
        $idjadwal = $this->request->getGet('idjadwal');
        $tanggal = $this->request->getGet('tanggal');
        $idjenis = $this->request->getGet('idjenis');
        
        // Log input untuk debug
        log_message('debug', "getAvailableSlot START - jadwal: $idjadwal, tanggal: $tanggal, jenis: $idjenis");
        
        // Dapatkan jadwal
        $jadwal = $this->jadwalModel->find($idjadwal);
        if (!$jadwal) {
            log_message('error', "Jadwal not found: $idjadwal");
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Jadwal tidak ditemukan'
            ]);
        }
        
        log_message('debug', "Jadwal found: " . json_encode($jadwal));
        
        // Validasi apakah tanggal sesuai dengan hari jadwal dokter
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dayOfWeek = date('w', strtotime($tanggal)); // 0 (Minggu) sampai 6 (Sabtu)
        $dayName = $dayNames[$dayOfWeek];
        
        log_message('debug', "Validating day - selected date: $tanggal (day: $dayName), schedule day: {$jadwal['hari']}");
        
        if ($dayName !== $jadwal['hari']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => "Tanggal {$tanggal} bukan hari {$jadwal['hari']}. Silakan pilih tanggal yang sesuai."
            ]);
        }
        
        // Validasi apakah waktu jadwal untuk hari ini sudah lewat
        if ($tanggal == date('Y-m-d')) {
            $currentTime = date('H:i:s');
            
            // Log waktu untuk debug
            log_message('debug', "Today's booking check - current time: $currentTime, schedule start time: {$jadwal['waktu_mulai']}, end time: {$jadwal['waktu_selesai']}");
            
            // Jika waktu sekarang sudah melewati jadwal dan ditambah 30 menit, tampilkan pesan
            $timeBuffer = date('H:i:s', strtotime($currentTime) + (30 * 60));
            log_message('debug', "Current time + buffer (30 min): $timeBuffer");
            
            $timeBufferTimestamp = strtotime("2000-01-01 $timeBuffer");
            $jadwalEndTimestamp = strtotime("2000-01-01 {$jadwal['waktu_selesai']}");
            
            log_message('debug', "Comparing - time+buffer: $timeBufferTimestamp vs jadwal end: $jadwalEndTimestamp");
            
            if ($timeBufferTimestamp >= $jadwalEndTimestamp) {
                log_message('debug', "Current time + buffer exceeds schedule end time");
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => "Tidak ada slot waktu tersedia untuk hari ini. Silakan booking untuk hari lain."
                ]);
            }
        }
        
        // Dapatkan durasi jenis perawatan
        $jenis = $this->jenisModel->find($idjenis);
        if (!$jenis) {
            log_message('error', "Jenis not found: $idjenis");
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Jenis perawatan tidak ditemukan'
            ]);
        }
        
        $durasi_menit = $jenis['estimasi'];
        log_message('debug', "Treatment duration: $durasi_menit minutes");
        
        // Cari slot waktu yang tersedia berdasarkan jadwal dokter
        $slot = $this->bookingModel->findAvailableSlotFromSchedule($idjadwal, $tanggal, $durasi_menit);
        
        if (!$slot) {
            log_message('debug', "No available slot found");
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak ada slot waktu tersedia'
            ]);
        }
        
        log_message('debug', "FINAL: Found available slot: " . json_encode($slot));
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'waktu_mulai' => $slot['waktu_mulai'],
                'waktu_selesai' => $slot['waktu_selesai'],
                'durasi' => $durasi_menit
            ]
        ]);
    }
} 