<?php

namespace App\Controllers;

use App\Models\Jadwal;
use App\Models\Dokter;
use CodeIgniter\RESTful\ResourceController;

class JadwalController extends ResourceController
{
    protected $jadwalModel;
    protected $dokterModel;
    
    public function __construct()
    {
        $this->jadwalModel = new Jadwal();
        $this->dokterModel = new Dokter();
    }
    
    public function index()
    {
        // Join dengan tabel dokter untuk mendapatkan nama dokter
        $builder = $this->jadwalModel->builder();
        $builder->select('jadwal.*, dokter.nama as nama_dokter');
        $builder->join('dokter', 'dokter.id_dokter = jadwal.iddokter');
        $builder->orderBy('jadwal.hari', 'ASC');
        $builder->orderBy('jadwal.waktu_mulai', 'ASC');
        
        $data = [
            'title' => 'Jadwal Praktek Dokter',
            'jadwal' => $builder->get()->getResultArray()
        ];
        
        return view('jadwal/index', $data);
    }
    
    public function show($id = null)
    {
        // Join dengan tabel dokter untuk mendapatkan nama dokter
        $builder = $this->jadwalModel->builder();
        $builder->select('jadwal.*, dokter.nama as nama_dokter');
        $builder->join('dokter', 'dokter.id_dokter = jadwal.iddokter');
        $builder->where('jadwal.idjadwal', $id);
        
        $jadwal = $builder->get()->getRowArray();
        
        if (!$jadwal) {
            return redirect()->to(site_url('jadwal'))->with('error', 'Data jadwal tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Jadwal',
            'jadwal' => $jadwal
        ];
        
        return view('jadwal/detail', $data);
    }
    
    public function new()
    {
        // Generate ID jadwal dengan format JD0001, JD0002, dst
        $db = db_connect();
        $query = $db->query("SELECT CONCAT('JD', LPAD(IFNULL(MAX(SUBSTRING(idjadwal, 3)) + 1, 1), 4, '0')) AS next_number FROM jadwal");
        $row = $query->getRow();
        $next_number = $row->next_number;
        
        $data = [
            'title' => 'Tambah Jadwal',
            'next_id' => $next_number,
            'dokter' => $this->dokterModel->findAll(),
            'validation' => \Config\Services::validation(),
            'hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']
        ];
        
        return view('jadwal/tambah', $data);
    }
    
    public function create()
    {
        $rules = [
            'idjadwal' => [
                'rules' => 'required|is_unique[jadwal.idjadwal]',
                'errors' => [
                    'required' => 'ID Jadwal harus diisi',
                    'is_unique' => 'ID Jadwal sudah terdaftar'
                ]
            ],
            'id_dokter' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dokter harus dipilih'
                ]
            ],
            'hari' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Hari harus dipilih'
                ]
            ],
            'waktu_mulai' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Waktu mulai harus diisi'
                ]
            ],
            'waktu_selesai' => [
                'rules' => 'required|greater_than_equal_to[waktu_mulai]',
                'errors' => [
                    'required' => 'Waktu selesai harus diisi',
                    'greater_than_equal_to' => 'Waktu selesai harus lebih besar atau sama dengan waktu mulai'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'idjadwal' => $this->request->getPost('idjadwal'),
            'iddokter' => $this->request->getPost('id_dokter'),
            'hari' => $this->request->getPost('hari'),
            'waktu_mulai' => $this->request->getPost('waktu_mulai'),
            'waktu_selesai' => $this->request->getPost('waktu_selesai'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        $this->jadwalModel->insert($data);
        
        return redirect()->to(site_url('jadwal'))->with('message', 'Data jadwal berhasil ditambahkan');
    }
    
    public function edit($id = null)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            return redirect()->to(site_url('jadwal'))->with('error', 'Data jadwal tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Jadwal',
            'jadwal' => $jadwal,
            'dokter' => $this->dokterModel->findAll(),
            'validation' => \Config\Services::validation(),
            'hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']
        ];
        
        return view('jadwal/edit', $data);
    }
    
    public function update($id = null)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            return redirect()->to(site_url('jadwal'))->with('error', 'Data jadwal tidak ditemukan');
        }
        
        $rules = [
            'id_dokter' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dokter harus dipilih'
                ]
            ],
            'hari' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Hari harus dipilih'
                ]
            ],
            'waktu_mulai' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Waktu mulai harus diisi'
                ]
            ],
            'waktu_selesai' => [
                'rules' => 'required|greater_than_equal_to[waktu_mulai]',
                'errors' => [
                    'required' => 'Waktu selesai harus diisi',
                    'greater_than_equal_to' => 'Waktu selesai harus lebih besar atau sama dengan waktu mulai'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'iddokter' => $this->request->getPost('id_dokter'),
            'hari' => $this->request->getPost('hari'),
            'waktu_mulai' => $this->request->getPost('waktu_mulai'),
            'waktu_selesai' => $this->request->getPost('waktu_selesai'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        $this->jadwalModel->update($id, $data);
        
        return redirect()->to(site_url('jadwal'))->with('message', 'Data jadwal berhasil diperbarui');
    }
    
    public function delete($id = null)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data jadwal tidak ditemukan'
            ]);
        }
        
        $this->jadwalModel->delete($id);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data jadwal berhasil dihapus'
        ]);
    }
    
    public function toggleActive($id = null)
    {
        $jadwal = $this->jadwalModel->find($id);
        
        if (!$jadwal) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data jadwal tidak ditemukan'
            ]);
        }
        
        // Toggle status
        $data = [
            'is_active' => $jadwal['is_active'] ? 0 : 1
        ];
        
        $this->jadwalModel->update($id, $data);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Status jadwal berhasil diubah',
            'is_active' => $data['is_active']
        ]);
    }
} 