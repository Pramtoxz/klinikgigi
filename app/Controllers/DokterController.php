<?php

namespace App\Controllers;

use App\Models\Dokter as DokterModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class DokterController extends ResourceController
{
    protected $dokterModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->dokterModel = new DokterModel();
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Data Dokter',
            'dokter' => $this->dokterModel->findAll()
        ];
        
        return view('dokter/index', $data);
    }
    
    public function show($id_dokter = null)
    {
        $dokter = $this->dokterModel->find($id_dokter);
        
        if (!$dokter) {
            return redirect()->to(site_url('dokter'))->with('error', 'Data dokter tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Dokter',
            'dokter' => $dokter
        ];
        
        return view('dokter/detail', $data);
    }
    
    public function new()
    {
        // Generate ID dokter dengan format PS0001, PS0002, dst
        $db = db_connect();
        $query = $db->query("SELECT CONCAT('PS', LPAD(IFNULL(MAX(SUBSTRING(id_dokter, 3)) + 1, 1), 4, '0')) AS next_number FROM dokter");
        $row = $query->getRow();
        $next_number = $row->next_number;
        
        $data = [
            'title' => 'Tambah Dokter',
            'next_id' => $next_number,
            'validation' => \Config\Services::validation()
        ];
        
        return view('dokter/tambah', $data);
    }
    
    public function create()
    {
        $rules = [
            'id_dokter' => [
                'rules' => 'required|is_unique[dokter.id_dokter]',
                'errors' => [
                    'required' => 'ID Dokter harus diisi',
                    'is_unique' => 'ID Dokter sudah terdaftar'
                ]
            ],
            'nama' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter'
                ]
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat harus diisi'
                ]
            ],
            'nohp' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Nomor HP harus diisi',
                    'numeric' => 'Nomor HP harus berupa angka'
                ]
            ],
            'jenkel' => [
                'rules' => 'required|in_list[L,P]',
                'errors' => [
                    'required' => 'Jenis kelamin harus dipilih',
                    'in_list' => 'Jenis kelamin tidak valid'
                ]
            ],
            'tgllahir' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal lahir harus diisi',
                    'valid_date' => 'Format tanggal lahir tidak valid'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'id_dokter' => $this->request->getPost('id_dokter'),
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'nohp' => $this->request->getPost('nohp'),
            'jenkel' => $this->request->getPost('jenkel'),
            'tgllahir' => $this->request->getPost('tgllahir'),
            'iduser' => null
        ];
        
        $this->dokterModel->insert($data);
        
        return redirect()->to(site_url('dokter'))->with('message', 'Data dokter berhasil ditambahkan');
    }
    
    public function edit($id_dokter = null)
    {
        $dokter = $this->dokterModel->find($id_dokter);
        
        if (!$dokter) {
            return redirect()->to(site_url('dokter'))->with('error', 'Data dokter tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Dokter',
            'dokter' => $dokter,
            'validation' => \Config\Services::validation()
        ];
        
        return view('dokter/edit', $data);
    }
    
    public function update($id_dokter = null)
    {
        $dokter = $this->dokterModel->find($id_dokter);
        
        if (!$dokter) {
            return redirect()->to(site_url('dokter'))->with('error', 'Data dokter tidak ditemukan');
        }
        
        $rules = [
            'nama' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter'
                ]
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat harus diisi'
                ]
            ],
            'nohp' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Nomor HP harus diisi',
                    'numeric' => 'Nomor HP harus berupa angka'
                ]
            ],
            'jenkel' => [
                'rules' => 'required|in_list[L,P]',
                'errors' => [
                    'required' => 'Jenis kelamin harus dipilih',
                    'in_list' => 'Jenis kelamin tidak valid'
                ]
            ],
            'tgllahir' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal lahir harus diisi',
                    'valid_date' => 'Format tanggal lahir tidak valid'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'nohp' => $this->request->getPost('nohp'),
            'jenkel' => $this->request->getPost('jenkel'),
            'tgllahir' => $this->request->getPost('tgllahir')
        ];
        
        $this->dokterModel->update($id_dokter, $data);
        
        return redirect()->to(site_url('dokter'))->with('message', 'Data dokter berhasil diperbarui');
    }
    
    public function delete($id_dokter = null)
    {
        $dokter = $this->dokterModel->find($id_dokter);
        
        if (!$dokter) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data dokter tidak ditemukan'
            ]);
        }
        
        // Jika dokter memiliki user, hapus juga user tersebut
        if ($dokter['iduser']) {
            $this->userModel->delete($dokter['iduser']);
        }
        
        $this->dokterModel->delete($id_dokter);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data dokter berhasil dihapus'
        ]);
    }
    
    public function createUser($id_dokter = null)
    {
        $dokter = $this->dokterModel->find($id_dokter);
        
        if (!$dokter) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data dokter tidak ditemukan'
            ]);
        }
        
        // Validasi input
        $rules = [
            'username' => [
                'rules' => 'required|min_length[5]|is_unique[users.username]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'min_length' => 'Username minimal 5 karakter',
                    'is_unique' => 'Username sudah digunakan'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah digunakan'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        // Buat user baru
        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => 'dokter',
            'status' => 'active'
        ];
        
        $this->userModel->insert($userData);
        $userId = $this->userModel->getInsertID();
        
        // Update data dokter dengan ID user baru
        $this->dokterModel->update($id_dokter, ['iduser' => $userId]);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Akun user untuk dokter berhasil dibuat'
        ]);
    }
} 