<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OtpSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 5,
                'email' => 'pramuditometra@gmail.com',
                'otp_code' => '411073',
                'type' => 'register',
                'is_used' => 1,
                'expires_at' => '2025-06-14 22:24:12',
                'created_at' => '2025-06-14 22:14:12',
                'updated_at' => '2025-06-14 22:14:50'
            ],
            [
                'id' => 7,
                'email' => 'bossrentalpadang@gmail.com',
                'otp_code' => '377888',
                'type' => 'register',
                'is_used' => 1,
                'expires_at' => '2025-06-14 22:30:04',
                'created_at' => '2025-06-14 22:20:04',
                'updated_at' => '2025-06-14 22:20:22'
            ],
            [
                'id' => 9,
                'email' => 'srimulyarni2@gmail.com',
                'otp_code' => '866665',
                'type' => 'register',
                'is_used' => 1,
                'expires_at' => '2025-06-14 22:54:28',
                'created_at' => '2025-06-14 22:44:28',
                'updated_at' => '2025-06-14 22:45:35'
            ],
            [
                'id' => 10,
                'email' => 'rindianir573@gmail.com',
                'otp_code' => '216643',
                'type' => 'register',
                'is_used' => 1,
                'expires_at' => '2025-06-28 10:39:30',
                'created_at' => '2025-06-28 10:29:30',
                'updated_at' => '2025-06-28 10:30:11'
            ],
            [
                'id' => 11,
                'email' => '03xa8cfygp@cross.edu.pl',
                'otp_code' => '678301',
                'type' => 'register',
                'is_used' => 1,
                'expires_at' => '2025-07-03 07:31:50',
                'created_at' => '2025-07-03 07:21:50',
                'updated_at' => '2025-07-03 07:22:22'
            ],
            [
                'id' => 12,
                'email' => 'putrialifianoerbalqis@gmail.com',
                'otp_code' => '531028',
                'type' => 'register',
                'is_used' => 1,
                'expires_at' => '2025-07-03 14:35:06',
                'created_at' => '2025-07-03 14:25:06',
                'updated_at' => '2025-07-03 14:26:15'
            ],
            [
                'id' => 13,
                'email' => 'gamingda273@gmail.com',
                'otp_code' => '119943',
                'type' => 'register',
                'is_used' => 1,
                'expires_at' => '2025-07-16 04:45:40',
                'created_at' => '2025-07-16 04:35:40',
                'updated_at' => '2025-07-16 04:36:06'
            ]
        ];

        // Menggunakan Query Builder
        $this->db->table('otp_codes')->insertBatch($data);
    }
} 