<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Informasi Klinik' ?></title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/vendors/iconly/bold.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') ?>">
    <!-- Bootstrap Icons (yang sudah ada) -->
    <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-icons/bootstrap-icons.css') ?>">
    <!-- Font Awesome untuk ikon tambahan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap5.min.css">
    <?= $this->renderSection('styles') ?>
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.svg') ?>" type="image/x-icon">
</head>
<!-- <style>
    .btn-user-add {
        cursor: pointer;
        color: #435ebe;
    }
    .btn-user-add:hover {
        color: #233a85;
    }
</style> -->
<body>
    <div id="app">
        <!-- Sidebar -->
        <?= $this->include('layouts/sidebar') ?>
        <!-- End Sidebar -->

        <!-- Main -->
        <div id="main">
            <!-- Navbar -->
            <?= $this->include('layouts/navbar') ?>
            <!-- End Navbar -->

            <!-- Page Header -->
            <?php if (isset($pageTitle)): ?>
            <div class="page-heading">
                <h3><?= $pageTitle ?></h3>
                <?php if (isset($pageDescription)): ?>
                <p class="text-subtitle text-muted"><?= $pageDescription ?></p>
                <?php endif; ?>

                <?php if (isset($breadcrumb)): ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <?php foreach ($breadcrumb as $item): ?>
                            <li class="breadcrumb-item <?= isset($item['active']) && $item['active'] ? 'active' : '' ?>"
                                <?= isset($item['active']) && $item['active'] ? 'aria-current="page"' : '' ?>>
                                <?php if (isset($item['active']) && $item['active']): ?>
                                    <?= $item['label'] ?>
                                <?php else: ?>
                                    <a href="<?= $item['link'] ?>"><?= $item['label'] ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </nav>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <!-- End Page Header -->

            <!-- Content -->
            <?= $this->renderSection('content') ?>
            <!-- End Content -->
            
            <!-- Footer -->
            <?= $this->include('layouts/footer') ?>
            <!-- End Footer -->
        </div>
        <!-- End Main -->
    </div>
    <!-- jQuery first, then DataTables, then SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <?= $this->renderSection('javascript') ?>
    

    <script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>
