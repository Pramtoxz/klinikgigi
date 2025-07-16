<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ObatSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idobat' => 'OB0001',
                'nama' => 'DELUXE',
                'stok' => 0,
                'jenis' => 'bahan',
                'keterangan' => 'sadasd',
                'created_at' => '2025-07-16 03:53:05',
                'updated_at' => '2025-07-16 03:53:05',
                'deleted_at' => null
            ]
        ];

        // Menggunakan Query Builder
        $this->db->table('obat')->insertBatch($data);
    }
} 