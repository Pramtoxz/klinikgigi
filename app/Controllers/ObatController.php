<?php

namespace App\Controllers;

use App\Models\Obat as ObatModel;
use CodeIgniter\RESTful\ResourceController;
use Hermawan\DataTables\DataTable;

class ObatController extends ResourceController
{
    protected $obatModel;
    
    public function __construct()
    {
        $this->obatModel = new ObatModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Data Obat',
        ];
        
        return view('obat/index', $data);
    }
    
    public function getDataTables()
    {
        $db = db_connect();
        $builder = $db->table('obat');
        
        return DataTable::of($builder)
            ->addNumbering('no')
            ->add('action', function($row){
                return '
                <div class="d-flex">
                    <a href="'.site_url('obat/'.$row->idobat).'" class="btn btn-info btn-sm me-1">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="'.site_url('obat/'.$row->idobat.'/edit').'" class="btn btn-warning btn-sm me-1">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="'.$row->idobat.'">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                ';
            })
            ->toJson(true);
    }
    
    public function show($idobat = null)
    {
        $obat = $this->obatModel->find($idobat);
        
        if (!$obat) {
            return redirect()->to(site_url('obat'))->with('error', 'Data obat tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Obat',
            'obat' => $obat
        ];
        
        return view('obat/detail', $data);
    }
    
    public function new()
    {
        $db = db_connect();
        $query = $db->query("SELECT CONCAT('OB', LPAD(IFNULL(MAX(SUBSTRING(idobat, 3)) + 1, 1), 4, '0')) AS next_number FROM obat");
        $row = $query->getRow();
        $next_number = $row->next_number;
        
        $data = [
            'title' => 'Tambah Obat',
            'next_id' => $next_number,
            'validation' => \Config\Services::validation()
        ];
        
        return view('obat/tambah', $data);
    }
    
    public function create()
    {
        $rules = [
            'idobat' => [
                'rules' => 'required|is_unique[obat.idobat]',
                'errors' => [
                    'required' => 'ID Obat harus diisi',
                    'is_unique' => 'ID Obat sudah terdaftar'
                ]
            ],
            'nama' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter'
                ]
            ],
            'jenis' => [
                'rules' => 'required|in_list[minum,bahan]',
                'errors' => [
                    'required' => 'Jenis harus diisi',
                    'in_list' => 'Jenis obat harus minum atau bahan'
                ]
            ],
            'keterangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Keterangan harus diisi'
                ]
            ],
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'idobat' => $this->request->getPost('idobat'),
            'nama' => $this->request->getPost('nama'),
            'stok' => 0, // Stok default 0
            'jenis' => $this->request->getPost('jenis'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];
        
        $this->obatModel->insert($data);
        
        return redirect()->to(site_url('obat'))->with('message', 'Data obat berhasil ditambahkan');
    }
    
    public function edit($idobat = null)
    {
        $obat = $this->obatModel->find($idobat);
        
        if (!$obat) {
            return redirect()->to(site_url('obat'))->with('error', 'Data obat tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Obat',
            'obat' => $obat,
            'validation' => \Config\Services::validation()
        ];
        
        return view('obat/edit', $data);
    }
    
    public function update($idobat = null)
    {
        $obat = $this->obatModel->find($idobat);
        
        if (!$obat) {
            return redirect()->to(site_url('obat'))->with('error', 'Data obat tidak ditemukan');
        }
        
        $rules = [
            'nama' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama Obat harus diisi',
                    'min_length' => 'Nama Obat minimal 3 karakter'
                ]
            ],
            'jenis' => [
                'rules' => 'required|in_list[minum,bahan]',
                'errors' => [
                    'required' => 'Jenis harus diisi',
                    'in_list' => 'Jenis obat harus minum atau bahan'
                ]
            ],
            'keterangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Keterangan harus diisi'
                ]
            ],
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'jenis' => $this->request->getPost('jenis'),
            'keterangan' => $this->request->getPost('keterangan')
        ];
        
        $this->obatModel->update($idobat, $data);
        
        return redirect()->to(site_url('obat'))->with('message', 'Data obat berhasil diperbarui');
    }
    
    public function delete($idobat = null)
    {
        $obat = $this->obatModel->find($idobat);
        
        if (!$obat) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data obat tidak ditemukan'
            ]);
        }
        
        $this->obatModel->delete($idobat);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data obat berhasil dihapus'
        ]);
    }
} 