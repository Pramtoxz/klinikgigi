<?php

namespace App\Models;

use CodeIgniter\Model;

class Booking extends Model
{
    protected $table            = 'booking';
    protected $primaryKey       = 'idbooking';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idbooking', 'id_pasien', 'idjadwal', 'idjenis', 'tanggal', 
        'waktu_mulai', 'waktu_selesai', 'status', 'bukti_bayar', 'catatan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Cek ketersediaan jadwal
    public function isSlotAvailable($idjadwal, $tanggal, $waktu_mulai, $waktu_selesai, $exclude_id = null)
    {
        $builder = $this->builder();
        $builder->where('idjadwal', $idjadwal);
        $builder->where('tanggal', $tanggal);
        $builder->where('deleted_at IS NULL');
        
        // Kondisi untuk cek overlap waktu
        $builder->groupStart()
            ->where("(waktu_mulai < '$waktu_selesai' AND waktu_selesai > '$waktu_mulai')")
        ->groupEnd();
        
        // Jika ada ID yang dikecualikan (untuk keperluan edit)
        if ($exclude_id) {
            $builder->where('idbooking !=', $exclude_id);
        }
        
        $count = $builder->countAllResults();
        return ($count == 0);
    }
    
    // Mencari slot waktu tersedia dalam range waktu tertentu
    public function findAvailableSlot($idjadwal, $tanggal, $blok_waktu, $durasi_menit)
    {
        // Definisikan waktu blok
        $waktu_mulai_blok = ($blok_waktu == 'Pagi') ? '08:00:00' : '13:00:00';
        $waktu_selesai_blok = ($blok_waktu == 'Pagi') ? '12:00:00' : '17:00:00';
        
        // Ambil semua booking pada jadwal dan tanggal tersebut
        $existing_bookings = $this->where('idjadwal', $idjadwal)
                                 ->where('tanggal', $tanggal)
                                 ->where('waktu_mulai >=', $waktu_mulai_blok)
                                 ->where('waktu_selesai <=', $waktu_selesai_blok)
                                 ->where('status !=', 'ditolak')
                                 ->orderBy('waktu_mulai', 'ASC')
                                 ->findAll();
        
        // Buffer time antar pasien (dalam menit)
        $buffer_time = 10;
        
        // Konversi durasi dan buffer ke format time interval (jam)
        $durasi_jam = $durasi_menit / 60;
        $buffer_jam = $buffer_time / 60;
        
        // Inisialisasi waktu mulai pencarian dengan waktu mulai blok
        $current_time = $waktu_mulai_blok;
        
        // Iterasi melalui existing bookings untuk menemukan slot kosong
        foreach ($existing_bookings as $booking) {
            $slot_end_time = date('H:i:s', strtotime($current_time) + ($durasi_menit * 60));
            
            // Jika slot yang tersedia cukup sebelum booking berikutnya
            if (strtotime($slot_end_time) <= strtotime($booking['waktu_mulai'])) {
                return [
                    'waktu_mulai' => $current_time,
                    'waktu_selesai' => $slot_end_time
                ];
            }
            
            // Update current_time ke akhir booking + buffer
            $current_time = date('H:i:s', strtotime($booking['waktu_selesai']) + ($buffer_time * 60));
        }
        
        // Cek apakah masih ada slot setelah booking terakhir
        $slot_end_time = date('H:i:s', strtotime($current_time) + ($durasi_menit * 60));
        if (strtotime($slot_end_time) <= strtotime($waktu_selesai_blok)) {
            return [
                'waktu_mulai' => $current_time,
                'waktu_selesai' => $slot_end_time
            ];
        }
        
        // Jika tidak ada slot yang tersedia
        return null;
    }
    
    // Mencari slot waktu tersedia berdasarkan jadwal dokter
    public function findAvailableSlotFromSchedule($idjadwal, $tanggal, $durasi_menit)
    {
        // Get jadwal details
        $db = \Config\Database::connect();
        $jadwal = $db->table('jadwal')
                    ->where('idjadwal', $idjadwal)
                    ->get()
                    ->getRowArray();
        
        if (!$jadwal) {
            return null;
        }
        
        // Debug log untuk input parameter
        log_message('debug', "findAvailableSlotFromSchedule - jadwal: $idjadwal, tanggal: $tanggal, durasi: $durasi_menit");
        log_message('debug', "Jadwal dokter: " . json_encode($jadwal));
        
        // Ambil waktu mulai dan selesai dari jadwal dokter
        $waktu_mulai_jadwal = $jadwal['waktu_mulai'];
        $waktu_selesai_jadwal = $jadwal['waktu_selesai'];
        
        // Debug: Log awal waktu jadwal
        log_message('debug', "Original jadwal time: $waktu_mulai_jadwal - $waktu_selesai_jadwal");
        
        // Jika tanggal hari ini, waktu mulai harus lebih dari waktu saat ini
        if ($tanggal == date('Y-m-d')) {
            // Ambil waktu saat ini dan tambahkan buffer 30 menit
            $currentTime = date('H:i:s');
            $waktu_mulai_minimal = date('H:i:s', strtotime($currentTime) + (30 * 60));
            
            // Debug: Log waktu untuk troubleshooting
            log_message('debug', "Current time: $currentTime, Minimal start time: $waktu_mulai_minimal, Jadwal start time: $waktu_mulai_jadwal");
            
            // Waktu mulai harus yang lebih besar antara waktu jadwal dan waktu saat ini + 30 menit
            $currentTimestamp = strtotime("2000-01-01 $waktu_mulai_minimal");
            $jadwalTimestamp = strtotime("2000-01-01 $waktu_mulai_jadwal");
            
            log_message('debug', "Comparing timestamps - Current+buffer: $currentTimestamp, Jadwal: $jadwalTimestamp");
            
            if ($currentTimestamp > $jadwalTimestamp) {
                $waktu_mulai_jadwal = $waktu_mulai_minimal;
                log_message('debug', "ADJUSTED TIME: Using current time + buffer as start time: $waktu_mulai_jadwal");
            }
            
            // Jika waktu mulai yang disesuaikan sudah melewati waktu selesai jadwal, tidak ada slot tersedia
            $startTimestamp = strtotime("2000-01-01 $waktu_mulai_jadwal");
            $endTimestamp = strtotime("2000-01-01 $waktu_selesai_jadwal");
            
            if ($startTimestamp >= $endTimestamp) {
                log_message('debug', "No slots available - adjusted start time exceeds schedule end time");
                return null;
            }
        }
        
        // Ambil semua booking pada jadwal dan tanggal tersebut
        $existing_bookings = $this->where('idjadwal', $idjadwal)
                                 ->where('tanggal', $tanggal)
                                 ->where('status !=', 'ditolak')
                                 ->orderBy('waktu_mulai', 'ASC')
                                 ->findAll();
        
        // Log jumlah booking yang ada
        log_message('debug', "Found " . count($existing_bookings) . " existing bookings for date $tanggal with jadwal $idjadwal");
        foreach ($existing_bookings as $booking) {
            log_message('debug', "Existing booking: " . json_encode($booking));
        }
        
        // Buffer time antar pasien (dalam menit)
        $buffer_time = 10;
        
        // Inisialisasi waktu mulai pencarian dengan waktu mulai jadwal yang sudah disesuaikan
        $current_time = $waktu_mulai_jadwal;
        log_message('debug', "Initial current_time after adjustments: $current_time");
        
        // Iterasi melalui existing bookings untuk menemukan slot kosong
        foreach ($existing_bookings as $booking) {
            log_message('debug', "Checking booking: " . json_encode($booking));
            
            // Jika waktu mulai booking lebih besar dari waktu saat ini, maka ada slot di antara
            $slot_end_time = date('H:i:s', strtotime($current_time) + ($durasi_menit * 60));
            log_message('debug', "Potential slot: $current_time - $slot_end_time, checking against booking: {$booking['waktu_mulai']} - {$booking['waktu_selesai']}");
            
            // Jika slot yang tersedia cukup sebelum booking berikutnya
            $slotEndTimestamp = strtotime("2000-01-01 $slot_end_time");
            $bookingStartTimestamp = strtotime("2000-01-01 {$booking['waktu_mulai']}");
            
            log_message('debug', "Comparing slot end: $slotEndTimestamp vs booking start: $bookingStartTimestamp");
            
            if ($slotEndTimestamp <= $bookingStartTimestamp) {
                log_message('debug', "Found available slot: $current_time - $slot_end_time");
                return [
                    'waktu_mulai' => $current_time,
                    'waktu_selesai' => $slot_end_time
                ];
            }
            
            // Update current_time ke akhir booking + buffer
            $current_time = date('H:i:s', strtotime($booking['waktu_selesai']) + ($buffer_time * 60));
            log_message('debug', "Updated current time to: $current_time after booking");
        }
        
        // Cek apakah masih ada slot setelah booking terakhir
        $slot_end_time = date('H:i:s', strtotime($current_time) + ($durasi_menit * 60));
        log_message('debug', "After checking all bookings, final potential slot: $current_time - $slot_end_time");
        
        $slotEndTimestamp = strtotime("2000-01-01 $slot_end_time");
        $jadwalEndTimestamp = strtotime("2000-01-01 $waktu_selesai_jadwal");
        
        log_message('debug', "Final comparison - slot end: $slotEndTimestamp vs jadwal end: $jadwalEndTimestamp");
        
        if ($slotEndTimestamp <= $jadwalEndTimestamp) {
            log_message('debug', "Found available slot after all bookings: $current_time - $slot_end_time");
            return [
                'waktu_mulai' => $current_time,
                'waktu_selesai' => $slot_end_time
            ];
        }
        
        log_message('debug', "No available slots found");
        // Jika tidak ada slot yang tersedia
        return null;
    }
} 