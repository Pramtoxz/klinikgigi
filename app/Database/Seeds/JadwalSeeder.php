<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idjadwal' => 'JD0001',
                'hari' => 'Selasa',
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '12:00:00',
                'iddokter' => 'DK0001',
                'is_active' => 1,
                'created_at' => '2025-07-15 15:53:53',
                'updated_at' => '2025-07-16 03:02:24',
                'deleted_at' => null
            ],
            [
                'idjadwal' => 'JD0002',
                'hari' => 'Senin',
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '10:00:00',
                'iddokter' => 'DK0001',
                'is_active' => 1,
                'created_at' => '2025-07-15 16:27:55',
                'updated_at' => '2025-07-16 03:02:27',
                'deleted_at' => null
            ]
        ];

        // Menggunakan Query Builder
        $this->db->table('jadwal')->insertBatch($data);
    }
} 