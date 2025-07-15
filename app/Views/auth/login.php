<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="<?= site_url('/') ?>"><img src="<?= base_url('assets/images/logo/logo.png') ?>" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Login</h1>
            <p class="auth-subtitle mb-5">Silakan masukkan username/email dan password Anda.</p>

            <div id="login-error" class="alert alert-danger" style="display: none;"></div>

            <form id="login-form" method="post">
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" name="username" placeholder="Username atau Email" required>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="password" placeholder="Password" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-gray-600" for="remember">
                        Ingat saya
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Login</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">Belum punya akun? <a href="<?= site_url('auth/register') ?>" class="font-bold">Daftar</a>.</p>
                <p><a class="font-bold" href="<?= site_url('auth/forgot-password') ?>">Lupa password?</a></p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(loginForm);
        
        fetch('<?= site_url('auth/login') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Sukses!',
                    text: data.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
});
</script>
<?= $this->endSection() ?>
