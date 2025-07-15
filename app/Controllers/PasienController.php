<?php

namespace App\Controllers;

use App\Models\Pasien as PasienModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Hermawan\DataTables\DataTable;

class PasienController extends ResourceController
{
    protected $pasienModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->pasienModel = new PasienModel();
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Data Pasien',
        ];
        
        return view('pasien/index', $data);
    }
    
    public function getDataTables()
    {
        $db = db_connect();
        $builder = $db->table('pasien');
        
        return DataTable::of($builder)
            ->addNumbering('no')
            ->add('action', function($row){
                return '
                <div class="d-flex">
                    <a href="'.site_url('pasien/'.$row->id_pasien).'" class="btn btn-info btn-sm me-1">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="'.site_url('pasien/'.$row->id_pasien.'/edit').'" class="btn btn-warning btn-sm me-1">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="'.$row->id_pasien.'">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                ';
            })
            ->format('tgllahir', function($value){
                return date('d/m/Y', strtotime($value));
            })
            ->format('jenkel', function($value){
                return ($value == 'L') ? 'Laki-laki' : 'Perempuan';
            })
            ->filter(function ($builder, $request) {
                if ($request->order) {
                    foreach ($request->order as $order) {
                        // Jika kolom yang diurutkan adalah 'no' (index 0), gunakan id_pasien sebagai gantinya
                        if ($request->columns[$order['column']]['data'] == 'no') {
                            $builder->orderBy('id_pasien', $order['dir']);
                        } else {
                            $builder->orderBy($request->columns[$order['column']]['data'], $order['dir']);
                        }
                    }
                } else {
                    $builder->orderBy('id_pasien', 'asc');
                }
            })
            ->toJson(true);
    }
    
    public function show($id_pasien = null)
    {
        $pasien = $this->pasienModel->find($id_pasien);
        
        if (!$pasien) {
            return redirect()->to(site_url('pasien'))->with('error', 'Data pasien tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Pasien',
            'pasien' => $pasien
        ];
        
        return view('pasien/detail', $data);
    }
    
    public function new()
    {
        // Generate ID Pasien dengan format PS0001, PS0002, dst
        $db = db_connect();
        $query = $db->query("SELECT CONCAT('PS', LPAD(IFNULL(MAX(SUBSTRING(id_pasien, 3)) + 1, 1), 4, '0')) AS next_number FROM pasien");
        $row = $query->getRow();
        $next_number = $row->next_number;
        
        $data = [
            'title' => 'Tambah Pasien',
            'next_number' => $next_number,
            'validation' => \Config\Services::validation()
        ];
        
        return view('pasien/tambah', $data);
    }
    
    public function create()
    {
        $rules = [
            'id_pasien' => [
                'rules' => 'required|is_unique[pasien.id_pasien]',
                'errors' => [
                    'required' => 'ID Pasien harus diisi',
                    'is_unique' => 'ID Pasien sudah terdaftar'
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
            'id_pasien' => $this->request->getPost('id_pasien'),
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'nohp' => $this->request->getPost('nohp'),
            'jenkel' => $this->request->getPost('jenkel'),
            'tgllahir' => $this->request->getPost('tgllahir'),
            'iduser' => null
        ];
        
        $this->pasienModel->insert($data);
        
        return redirect()->to(site_url('pasien'))->with('message', 'Data pasien berhasil ditambahkan');
    }
    
    public function edit($id_pasien = null)
    {
        $pasien = $this->pasienModel->find($id_pasien);
        
        if (!$pasien) {
            return redirect()->to(site_url('pasien'))->with('error', 'Data pasien tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Pasien',
            'pasien' => $pasien,
            'validation' => \Config\Services::validation()
        ];
        
        return view('pasien/edit', $data);
    }
    
    public function update($id_pasien = null)
    {
        $pasien = $this->pasienModel->find($id_pasien);
        
        if (!$pasien) {
            return redirect()->to(site_url('pasien'))->with('error', 'Data pasien tidak ditemukan');
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
        
        $this->pasienModel->update($id_pasien, $data);
        
        return redirect()->to(site_url('pasien'))->with('message', 'Data pasien berhasil diperbarui');
    }
    
    public function delete($id_pasien = null)
    {
        $pasien = $this->pasienModel->find($id_pasien);
        
        if (!$pasien) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data pasien tidak ditemukan'
            ]);
        }
        
        // Jika pasien memiliki user, hapus juga user tersebut
        if ($pasien['iduser']) {
            $this->userModel->delete($pasien['iduser']);
        }
        
        $this->pasienModel->delete($id_pasien);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data pasien berhasil dihapus'
        ]);
    }
    
    public function createUser($id_pasien = null)
    {
        $pasien = $this->pasienModel->find($id_pasien);
        
        if (!$pasien) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data pasien tidak ditemukan'
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
            'role' => 'user',
            'status' => 'active'
        ];
        
        $this->userModel->insert($userData);
        $userId = $this->userModel->getInsertID();
        
        // Update data pasien dengan ID user baru
        $this->pasienModel->update($id_pasien, ['iduser' => $userId]);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Akun user untuk pasien berhasil dibuat'
        ]);
    }
    
    public function updatePassword($id_pasien = null)
    {
        $pasien = $this->pasienModel->find($id_pasien);
        
        if (!$pasien) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data pasien tidak ditemukan'
            ]);
        }
        
        if (!$pasien['iduser']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pasien belum memiliki akun user'
            ]);
        }
        
        // Validasi input
        $rules = [
            'password' => [
                'rules' => 'permit_empty|min_length[6]',
                'errors' => [
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
        
        $password = $this->request->getPost('password');
        
        // Jika password kosong, abaikan update password
        if (empty($password)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Tidak ada perubahan pada password'
            ]);
        }
        
        // Update password user
        $userData = [
            'id' => $pasien['iduser'],
            'password' => $password
        ];
        
        $this->userModel->save($userData);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Password berhasil diperbarui'
        ]);
    }
} 