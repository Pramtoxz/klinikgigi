<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisPerawatanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'jenis_perawatan';
    protected $primaryKey       = 'idjenis';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['namajenis', 'estimasi', 'harga'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'idjenis' => 'required|max_length[30]',
        'namajenis' => 'required|max_length[50]',
        'estimasi' => 'required|integer',
        'harga' => 'required|numeric'
    ];
    
    protected $validationMessages = [
        'idjenis' => [
            'required' => 'ID Jenis Perawatan harus diisi',
            'max_length' => 'ID Jenis Perawatan maksimal 30 karakter'
        ],
        'namajenis' => [
            'required' => 'Nama jenis perawatan harus diisi',
            'max_length' => 'Nama jenis perawatan maksimal 50 karakter'
        ],
        'estimasi' => [
            'required' => 'Estimasi waktu harus diisi',
            'integer' => 'Estimasi waktu harus berupa angka'
        ],
        'harga' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka'
        ]
    ];
    
    protected $skipValidation = false;

    /**
     * Generate ID Jenis Perawatan baru dengan format JP0001, JP0002, dst
     * 
     * @return string
     */
    public function generateId(): string
    {
        $lastId = $this->selectMax('idjenis')->first();
        $prefix = 'JP';
        
        if (empty($lastId) || !$lastId['idjenis']) {
            return $prefix . '0001';
        }
        
        $lastNumber = (int) substr($lastId['idjenis'], 2);
        $newNumber = $lastNumber + 1;
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Format harga ke format rupiah
     * 
     * @param float $harga
     * @return string
     */
    public function formatHarga($harga): string
    {
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }
} 