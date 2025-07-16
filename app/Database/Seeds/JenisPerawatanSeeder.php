<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JenisPerawatanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idjenis' => 'JP0001',
                'namajenis' => 'Cabut gigi',
                'estimasi' => 30,
                'harga' => 250000,
                'created_at' => '2025-07-16 04:03:40',
                'updated_at' => '2025-07-16 04:03:40',
                'deleted_at' => null
            ]
        ];

        // Menggunakan Query Builder
        $this->db->table('jenis_perawatan')->insertBatch($data);
    }
} 