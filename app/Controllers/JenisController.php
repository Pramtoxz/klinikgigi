<?php

namespace App\Controllers;

use App\Models\Jenis as JenisModel;
use CodeIgniter\RESTful\ResourceController;
use Hermawan\DataTables\DataTable;

class JenisController extends ResourceController
{
    protected $jenisModel;
    
    public function __construct()
    {
        $this->jenisModel = new JenisModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Data Jenis Perawatan',
        ];
        
        return view('jenis/index', $data);
    }
    
    public function getDataTables()
    {
        $db = db_connect();
        $builder = $db->table('jenis_perawatan');
        
        return DataTable::of($builder)
            ->addNumbering('no')
            ->add('action', function($row){
                return '
                <div class="d-flex">
                    <a href="'.site_url('jenis/'.$row->idjenis).'" class="btn btn-info btn-sm me-1">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="'.site_url('jenis/'.$row->idjenis.'/edit').'" class="btn btn-warning btn-sm me-1">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="'.$row->idjenis.'">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                ';
            })
            ->format('estimasi', function($value){
                return $value . ' menit';
            })
            ->format('harga', function($value){
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->toJson(true);
    }
    
    public function show($idjenis = null)
    {
        $jenis = $this->jenisModel->find($idjenis);
        
        if (!$jenis) {
            return redirect()->to(site_url('jenis'))->with('error', 'Data jenis perawatan tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Jenis Perawatan',
            'jenis' => $jenis
        ];
        
        return view('jenis/detail', $data);
    }
    
    public function new()
    {
        // Generate ID Jenis dengan format JP0001, JP0002, dst
        $db = db_connect();
        $query = $db->query("SELECT CONCAT('JP', LPAD(IFNULL(MAX(SUBSTRING(idjenis, 3)) + 1, 1), 4, '0')) AS next_number FROM jenis_perawatan");
        $row = $query->getRow();
        $next_number = $row->next_number;
        
        $data = [
            'title' => 'Tambah Jenis Perawatan',
            'next_id' => $next_number,
            'validation' => \Config\Services::validation()
        ];
        
        return view('jenis/tambah', $data);
    }
    
    public function create()
    {
        $rules = [
            'idjenis' => [
                'rules' => 'required|is_unique[jenis_perawatan.idjenis]',
                'errors' => [
                    'required' => 'ID Jenis Perawatan harus diisi',
                    'is_unique' => 'ID Jenis Perawatan sudah terdaftar'
                ]
            ],
            'namajenis' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama Jenis Perawatan harus diisi',
                    'min_length' => 'Nama Jenis Perawatan minimal 3 karakter'
                ]
            ],
            'estimasi' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Estimasi waktu harus diisi',
                    'integer' => 'Estimasi waktu harus berupa angka',
                    'greater_than' => 'Estimasi waktu harus lebih dari 0'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Harga harus diisi',
                    'numeric' => 'Harga harus berupa angka',
                    'greater_than' => 'Harga harus lebih dari 0'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'idjenis' => $this->request->getPost('idjenis'),
            'namajenis' => $this->request->getPost('namajenis'),
            'estimasi' => $this->request->getPost('estimasi'),
            'harga' => $this->request->getPost('harga')
        ];
        
        $this->jenisModel->insert($data);
        
        return redirect()->to(site_url('jenis'))->with('message', 'Data jenis perawatan berhasil ditambahkan');
    }
    
    public function edit($idjenis = null)
    {
        $jenis = $this->jenisModel->find($idjenis);
        
        if (!$jenis) {
            return redirect()->to(site_url('jenis'))->with('error', 'Data jenis perawatan tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Jenis Perawatan',
            'jenis' => $jenis,
            'validation' => \Config\Services::validation()
        ];
        
        return view('jenis/edit', $data);
    }
    
    public function update($idjenis = null)
    {
        $jenis = $this->jenisModel->find($idjenis);
        
        if (!$jenis) {
            return redirect()->to(site_url('jenis'))->with('error', 'Data jenis perawatan tidak ditemukan');
        }
        
        $rules = [
            'namajenis' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama Jenis Perawatan harus diisi',
                    'min_length' => 'Nama Jenis Perawatan minimal 3 karakter'
                ]
            ],
            'estimasi' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Estimasi waktu harus diisi',
                    'integer' => 'Estimasi waktu harus berupa angka',
                    'greater_than' => 'Estimasi waktu harus lebih dari 0'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Harga harus diisi',
                    'numeric' => 'Harga harus berupa angka',
                    'greater_than' => 'Harga harus lebih dari 0'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'namajenis' => $this->request->getPost('namajenis'),
            'estimasi' => $this->request->getPost('estimasi'),
            'harga' => $this->request->getPost('harga')
        ];
        
        $this->jenisModel->update($idjenis, $data);
        
        return redirect()->to(site_url('jenis'))->with('message', 'Data jenis perawatan berhasil diperbarui');
    }
    
    public function delete($idjenis = null)
    {
        $jenis = $this->jenisModel->find($idjenis);
        
        if (!$jenis) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data jenis perawatan tidak ditemukan'
            ]);
        }
        
        $this->jenisModel->delete($idjenis);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data jenis perawatan berhasil dihapus'
        ]);
    }
} 