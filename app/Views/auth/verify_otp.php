<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="<?= site_url('/') ?>"><img src="<?= base_url('assets/images/logo/logo.png') ?>" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Verifikasi OTP</h1>
            <p class="auth-subtitle mb-4">Masukkan kode OTP yang telah dikirimkan ke email Anda</p>

            <?php if (isset($error)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Error!',
                            text: '<?= $error ?>',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
            <?php endif; ?>

            <form action="<?= site_url($action) ?>" method="post">
                <input type="hidden" name="email" value="<?= $email ?>">
                <input type="hidden" name="type" value="<?= $type ?>">
                
                <div class="form-group otp-inputs d-flex justify-content-between mb-4">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <input type="text" name="otp[]" class="form-control otp-input" maxlength="1" required autocomplete="off">
                    <?php endfor; ?>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Tidak menerima kode? <a href="javascript:void(0)" class="resend-otp">Kirim Ulang</a></span>
                    <span class="countdown">60</span>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-4">Verifikasi</button>
            </form>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // OTP input handling
    const otpInputs = document.querySelectorAll('.otp-input');
    
    otpInputs.forEach((input, index) => {
        input.addEventListener('keyup', function(e) {
            if (e.key !== 'Backspace' && input.value) {
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            }
        });
        
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !input.value) {
                if (index > 0) {
                    otpInputs[index - 1].focus();
                }
            }
        });
    });
    
    // Countdown timer
    let countdown = 60;
    const countdownElement = document.querySelector('.countdown');
    const resendButton = document.querySelector('.resend-otp');
    
    resendButton.style.pointerEvents = 'none';
    resendButton.style.opacity = '0.5';
    
    const timer = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        
        if (countdown <= 0) {
            clearInterval(timer);
            resendButton.style.pointerEvents = 'auto';
            resendButton.style.opacity = '1';
            countdownElement.textContent = '';
        }
    }, 1000);
    
    // Resend OTP
    resendButton.addEventListener('click', function() {
        if (countdown <= 0) {
            const email = document.querySelector('input[name="email"]').value;
            const type = document.querySelector('input[name="type"]').value;
            
            fetch('<?= site_url('auth/resend-otp') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `email=${email}&type=${type}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Reset countdown
                    countdown = 60;
                    resendButton.style.pointerEvents = 'none';
                    resendButton.style.opacity = '0.5';
                    
                    timer = setInterval(() => {
                        countdown--;
                        countdownElement.textContent = countdown;
                        
                        if (countdown <= 0) {
                            clearInterval(timer);
                            resendButton.style.pointerEvents = 'auto';
                            resendButton.style.opacity = '1';
                            countdownElement.textContent = '';
                        }
                    }, 1000);
                    
                    // Show success message using SweetAlert2
                    Swal.fire({
                        title: 'Sukses!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Show error message using SweetAlert2
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
                // Show error message using SweetAlert2
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
});
</script>

<style>
.otp-inputs .otp-input {
    width: 40px;
    height: 40px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
}
</style>
<?= $this->endSection() ?>
