<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'jadwal';
    protected $primaryKey       = 'idjadwal';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['hari', 'waktu_mulai', 'waktu_selesai', 'iddokter', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'idjadwal' => 'required|max_length[30]',
        'hari' => 'required|max_length[50]',
        'waktu_mulai' => 'required',
        'waktu_selesai' => 'permit_empty',
        'iddokter' => 'required|max_length[30]',
        'is_active' => 'permit_empty|integer|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'idjadwal' => [
            'required' => 'ID Jadwal harus diisi',
            'max_length' => 'ID Jadwal maksimal 30 karakter'
        ],
        'hari' => [
            'required' => 'Hari harus diisi',
            'max_length' => 'Hari maksimal 50 karakter'
        ],
        'waktu_mulai' => [
            'required' => 'Waktu mulai harus diisi'
        ],
        'iddokter' => [
            'required' => 'Dokter harus dipilih',
            'max_length' => 'ID Dokter maksimal 30 karakter'
        ],
        'is_active' => [
            'integer' => 'Status aktif harus berupa angka',
            'in_list' => 'Status aktif harus 0 atau 1'
        ]
    ];
    
    protected $skipValidation = false;

    /**
     * Generate ID Jadwal baru dengan format JD0001, JD0002, dst
     * 
     * @return string
     */
    public function generateId(): string
    {
        $lastId = $this->selectMax('idjadwal')->first();
        $prefix = 'JD';
        
        if (empty($lastId) || !$lastId['idjadwal']) {
            return $prefix . '0001';
        }
        
        $lastNumber = (int) substr($lastId['idjadwal'], 2);
        $newNumber = $lastNumber + 1;
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Dapatkan jadwal dengan data dokter
     * 
     * @param string|null $idjadwal
     * @return array
     */
    public function getJadwalWithDokter($idjadwal = null)
    {
        $builder = $this->db->table('jadwal j');
        $builder->select('j.*, d.nama as nama_dokter');
        $builder->join('dokter d', 'd.id_dokter = j.iddokter');
        
        if ($idjadwal) {
            $builder->where('j.idjadwal', $idjadwal);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Dapatkan jadwal berdasarkan dokter
     * 
     * @param string $iddokter
     * @return array
     */
    public function getJadwalByDokter($iddokter)
    {
        return $this->where('iddokter', $iddokter)
                    ->where('is_active', 1)
                    ->findAll();
    }
} 