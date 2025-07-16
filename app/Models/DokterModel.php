<?php

namespace App\Models;

use CodeIgniter\Model;

class DokterModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'dokter';
    protected $primaryKey       = 'id_dokter';
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
        'id_dokter' => 'required|max_length[30]',
        'nama' => 'required|max_length[50]',
        'alamat' => 'permit_empty',
        'tgllahir' => 'permit_empty|valid_date',
        'nohp' => 'permit_empty|max_length[30]',
        'jenkel' => 'permit_empty|in_list[L,P]',
        'iduser' => 'permit_empty|integer'
    ];
    
    protected $validationMessages = [
        'id_dokter' => [
            'required' => 'ID Dokter harus diisi',
            'max_length' => 'ID Dokter maksimal 30 karakter'
        ],
        'nama' => [
            'required' => 'Nama dokter harus diisi',
            'max_length' => 'Nama dokter maksimal 50 karakter'
        ],
        'jenkel' => [
            'in_list' => 'Jenis kelamin harus L atau P'
        ]
    ];
    
    protected $skipValidation = false;

    /**
     * Generate ID Dokter baru dengan format DK0001, DK0002, dst
     * 
     * @return string
     */
    public function generateId(): string
    {
        $lastId = $this->selectMax('id_dokter')->first();
        $prefix = 'DK';
        
        if (empty($lastId) || !$lastId['id_dokter']) {
            return $prefix . '0001';
        }
        
        $lastNumber = (int) substr($lastId['id_dokter'], 2);
        $newNumber = $lastNumber + 1;
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Dapatkan dokter dengan data user
     * 
     * @param string $id_dokter
     * @return array|null
     */
    public function getDokterWithUser($id_dokter = null)
    {
        $builder = $this->db->table('dokter d');
        $builder->select('d.*, u.username, u.email, u.role');
        $builder->join('users u', 'u.id = d.iduser', 'left');
        
        if ($id_dokter) {
            $builder->where('d.id_dokter', $id_dokter);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
} 