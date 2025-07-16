<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DokterSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_dokter' => 'DK0001',
                'nama' => 'Pramtoxz',
                'alamat' => 'Jl. Padang',
                'tgllahir' => '2025-07-03',
                'nohp' => '08129323923',
                'jenkel' => 'L',
                'iduser' => 7,
                'created_at' => '2025-07-03 06:30:48',
                'updated_at' => '2025-07-03 06:30:48',
                'deleted_at' => null
            ]
        ];

        // Menggunakan Query Builder
        $this->db->table('dokter')->insertBatch($data);
    }
} 