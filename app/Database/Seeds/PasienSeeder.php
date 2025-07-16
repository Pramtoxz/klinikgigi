<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PasienSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_pasien' => 'PS0001',
                'nama' => 'Pramudito Metra',
                'alamat' => 'Jl. Padang',
                'tgllahir' => '2025-07-03',
                'nohp' => '08129323923',
                'jenkel' => 'L',
                'iduser' => 3,
                'created_at' => '2025-07-03 06:30:48',
                'updated_at' => '2025-07-03 06:30:48',
                'deleted_at' => null
            ],
            [
                'id_pasien' => 'PS0002',
                'nama' => 'Cimul',
                'alamat' => 'ss',
                'tgllahir' => '2025-07-09',
                'nohp' => '081234256734',
                'jenkel' => 'P',
                'iduser' => 5,
                'created_at' => '2025-07-09 02:20:38',
                'updated_at' => '2025-07-15 14:32:09',
                'deleted_at' => null
            ],
            [
                'id_pasien' => 'PS0003',
                'nama' => 'Tari',
                'alamat' => 'asdasd',
                'tgllahir' => '2025-07-15',
                'nohp' => '08743557687',
                'jenkel' => 'L',
                'iduser' => null,
                'created_at' => '2025-07-15 16:16:54',
                'updated_at' => '2025-07-15 16:16:54',
                'deleted_at' => null
            ],
            [
                'id_pasien' => 'PS0004',
                'nama' => 'Agus Saputra',
                'alamat' => 'asdasd',
                'tgllahir' => '2025-07-15',
                'nohp' => '06754343212',
                'jenkel' => 'L',
                'iduser' => null,
                'created_at' => '2025-07-15 16:25:30',
                'updated_at' => '2025-07-15 16:25:30',
                'deleted_at' => null
            ]
        ];

        // Menggunakan Query Builder
        $this->db->table('pasien')->insertBatch($data);
    }
} 