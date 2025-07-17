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
} 