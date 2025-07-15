<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    /**
     * Filter untuk melindungi halaman admin
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika pengguna belum login, redirect ke halaman login
        if (!session()->get('logged_in')) {
            return redirect()->to(site_url('auth'))->with('error', 'Silakan login untuk mengakses halaman tersebut.');
        }
        
        // Jika bukan admin, redirect ke halaman home
        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('/'))->with('error', 'Anda tidak memiliki akses untuk halaman tersebut.');
        }
    }

    /**
     * Tidak ada yang perlu dilakukan setelah request
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
} 