<?php

namespace App\Models;

use CodeIgniter\Model;

class PasienModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pasien';
    protected $primaryKey       = 'id_pasien';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'alamat', 'tgllahir', 'nohp', 'jenkel', 'iduser'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_pasien' => 'required|max_length[30]',
        'nama' => 'required|max_length[50]',
        'alamat' => 'permit_empty',
        'tgllahir' => 'permit_empty|valid_date',
        'nohp' => 'permit_empty|max_length[30]',
        'jenkel' => 'permit_empty|in_list[L,P]',
        'iduser' => 'permit_empty|integer'
    ];
    
    protected $validationMessages = [
        'id_pasien' => [
            'required' => 'ID Pasien harus diisi',
            'max_length' => 'ID Pasien maksimal 30 karakter'
        ],
        'nama' => [
            'required' => 'Nama pasien harus diisi',
            'max_length' => 'Nama pasien maksimal 50 karakter'
        ],
        'jenkel' => [
            'in_list' => 'Jenis kelamin harus L atau P'
        ]
    ];
    
    protected $skipValidation = false;

    /**
     * Generate ID Pasien baru dengan format PS0001, PS0002, dst
     * 
     * @return string
     */
    public function generateId(): string
    {
        $lastId = $this->selectMax('id_pasien')->first();
        $prefix = 'PS';
        
        if (empty($lastId) || !$lastId['id_pasien']) {
            return $prefix . '0001';
        }
        
        $lastNumber = (int) substr($lastId['id_pasien'], 2);
        $newNumber = $lastNumber + 1;
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Dapatkan pasien dengan data user
     * 
     * @param string $id_pasien
     * @return array|null
     */
    public function getPasienWithUser($id_pasien = null)
    {
        $builder = $this->db->table('pasien p');
        $builder->select('p.*, u.username, u.email, u.role');
        $builder->join('users u', 'u.id = p.iduser', 'left');
        
        if ($id_pasien) {
            $builder->where('p.id_pasien', $id_pasien);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
} 