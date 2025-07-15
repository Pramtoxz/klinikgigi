<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistem Informasi Klinik</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-icons/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/pages/auth.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.svg') ?>" type="image/x-icon">
</head>

<body>
    <div id="auth">
        <?= $this->renderSection('content') ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('message')): ?>
        Swal.fire({
            title: 'Sukses!',
            text: '<?= session()->getFlashdata('message') ?>',
            icon: 'success',
            confirmButtonText: 'OK'
        });
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({
            title: 'Error!',
            text: '<?= session()->getFlashdata('error') ?>',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        <?php endif; ?>
    });
    </script>
</body>

</html>

