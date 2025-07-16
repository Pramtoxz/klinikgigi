<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // Jalankan semua seeder dalam urutan yang tepat
        $this->call('UserSeeder');
        $this->call('OtpSeeder');
        $this->call('DokterSeeder');
        $this->call('JenisPerawatanSeeder');
        $this->call('ObatSeeder');
        $this->call('PasienSeeder');
        $this->call('JadwalSeeder');
        
        echo "Semua data berhasil di-seed!";
    }
} 