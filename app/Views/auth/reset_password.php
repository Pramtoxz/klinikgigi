<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="<?= site_url('/') ?>"><img src="<?= base_url('assets/images/logo/logo.png') ?>" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle mb-4">Silakan masukkan password baru Anda.</p>

            <?php if (session()->getFlashdata('error')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Error!',
                            text: '<?= session()->getFlashdata('error') ?>',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
            <?php endif; ?>

            <?php if (isset($validation)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Validasi Error!',
                            html: `<?= $validation->listErrors() ?>`,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
            <?php endif; ?>

            <form action="<?= site_url('auth/reset-password') ?>" method="post">
                <input type="hidden" name="email" value="<?= $email ?>">
                
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="password" placeholder="Password Baru" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="password_confirm" placeholder="Konfirmasi Password" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Reset Password</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p><a href="<?= site_url('auth') ?>" class="font-bold">Kembali ke login</a></p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right"></div>
    </div>
</div>
<?= $this->endSection() ?>
