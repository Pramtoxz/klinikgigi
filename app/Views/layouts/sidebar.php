<?php
$uri = service('uri')->getSegments();
$uri1 = $uri[0] ?? 'admin';
$uri2 = $uri[1] ?? '';
?>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="<?= base_url('/admin') ?>"><img src="<?= base_url('assets/images/logo/logo.png') ?>" alt="Logo" srcset=""></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <!-- <?php if(session()->get('logged_in')): ?>
            <div class="user-info p-3 mb-2 d-flex">
                <div class="avatar avatar-lg me-3">
                    <img src="<?= base_url('assets/images/faces/1.jpg') ?>" alt="User Avatar">
                </div>
                <div>
                    <h6 class="mb-1"><?= session()->get('username') ?></h6>
                    <p class="text-muted mb-0 text-sm"><?= ucfirst(session()->get('role')) ?></p>
                </div>
            </div>
            <?php endif; ?> -->
            
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item <?= ($uri1 == 'admin' && empty($uri2)) ? 'active' : '' ?>">
                    <a href="<?= site_url('admin') ?>" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($uri1 == 'dokter') ? 'active' : '' ?>">
                    <a href="<?= site_url('dokter') ?>" class='sidebar-link'>
                    <i class="bi bi-person-badge-fill"></i> 
                        <span>Data Dokter</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($uri1 == 'pasien') ? 'active' : '' ?>">
                    <a href="<?= site_url('pasien') ?>" class='sidebar-link'>
                        <i class="bi bi-people-fill"></i>
                        <span>Data Pasien</span>
                    </a>
                </li>

                <li class="sidebar-title">Pengaturan</li>

                <li class="sidebar-item <?= ($uri1 == 'admin' && $uri2 == 'profile') ? 'active' : '' ?>">
                    <a href="<?= site_url('admin/profile') ?>" class='sidebar-link'>
                        <i class="bi bi-person-fill"></i>
                        <span>Profil Saya</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($uri1 == 'admin' && $uri2 == 'settings') ? 'active' : '' ?>">
                    <a href="<?= site_url('admin/settings') ?>" class='sidebar-link'>
                        <i class="bi bi-gear-fill"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="<?= site_url('auth/logout') ?>" class='sidebar-link'>
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Keluar</span>
                    </a>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
