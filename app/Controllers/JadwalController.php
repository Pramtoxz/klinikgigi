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
    
    public function datatables()
    {
        $request = $this->request->getGet();
        $draw = $request['draw'] ?? 1;
        $start = $request['start'] ?? 0;
        $length = $request['length'] ?? 10;
        $search = $request['search']['value'] ?? '';
        
        // Debug: Catat request yang diterima
        log_message('debug', 'JadwalController::datatables Request: ' . json_encode($request));
        
        // Join dengan tabel dokter untuk mendapatkan nama dokter
        $builder = $this->jadwalModel->builder();
        $builder->select('jadwal.*, dokter.nama as nama_dokter');
        $builder->join('dokter', 'dokter.id_dokter = jadwal.iddokter');
        
        // Filter pencarian
        if (!empty($search)) {
            $builder->groupStart()
                ->like('jadwal.idjadwal', $search)
                ->orLike('dokter.nama', $search)
                ->orLike('jadwal.hari', $search)
                ->groupEnd();
        }
        
        // Hitung total records
        $totalRecords = $builder->countAllResults(false);
        
        // Ordering
        $order = $request['order'] ?? [];
        $columns = ['idjadwal', 'nama_dokter', 'hari', 'waktu_mulai', 'is_active'];
        
        if (!empty($order)) {
            $columnIdx = intval($order[0]['column']);
            $columnName = $columns[$columnIdx] ?? 'jadwal.hari';
            $dir = $order[0]['dir'] ?? 'asc';
            $builder->orderBy($columnName, $dir);
        } else {
            $builder->orderBy('jadwal.hari', 'ASC');
            $builder->orderBy('jadwal.waktu_mulai', 'ASC');
        }
        
        // Pagination
        $builder->limit($length, $start);
        
        // Fetch data
        $result = $builder->get()->getResultArray();
        
        // Format data untuk DataTables
        $data = [];
        foreach ($result as $row) {
            $data[] = [
                'idjadwal' => $row['idjadwal'],
                'nama_dokter' => $row['nama_dokter'],
                'hari' => $row['hari'],
                'waktu_mulai' => $row['waktu_mulai'],
                'waktu_selesai' => $row['waktu_selesai'],
                'is_active' => $row['is_active'],
            ];
        }
        
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ];
        
        // Debug: Catat response yang dikembalikan
        log_message('debug', 'JadwalController::datatables Response: ' . json_encode($response));
        
        return $this->response->setJSON($response);
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
                'rules' => 'required|check_time_greater[waktu_mulai]',
                'errors' => [
                    'required' => 'Waktu selesai harus diisi',
                    'check_time_greater' => 'Waktu selesai harus lebih besar atau sama dengan waktu mulai'
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
                'rules' => 'required|check_time_greater[waktu_mulai]',
                'errors' => [
                    'required' => 'Waktu selesai harus diisi',
                    'check_time_greater' => 'Waktu selesai harus lebih besar atau sama dengan waktu mulai'
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