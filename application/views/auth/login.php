<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-md-9 col-lg-8">
            <div class="card neu-brutalism overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-block bg-primary text-white p-4 position-relative">
                        <div class="h-100 d-flex flex-column justify-content-center text-center position-relative" style="z-index: 1;">
                            <h3 class="fw-bold mb-4">Selamat Datang Kembali</h3>
                            <p class="mb-4">Masuk untuk mengakses akun KiiseCoffee Anda dan nikmati kopi terbaik.</p>
                            
                            <div class="mb-4 py-3">
                                <i class="fas fa-coffee" style="font-size: 8rem; text-shadow: 4px 4px 0 #000;"></i>
                            </div>

                            <p class="mb-0">Belum punya akun? <br>
                                <a href="<?= base_url('auth/daftar') ?>" class="btn btn-light btn-sm mt-2 neu-button-sm fw-bold">Daftar Sekarang</a>
                            </p>
                        </div>
                        <div class="overlay-pattern"></div>
                    </div>
                    
                    <div class="col-md-7 bg-white">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-black text-uppercase">Masuk Akun</h3>
                                <p class="text-muted small">Silakan masukkan kredensial Anda</p>
                            </div>
                            
                            <?= $this->session->flashdata('message') ?>
                            
                            <form method="POST" action="<?= base_url('auth'); ?>" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">Email Address</label>
                                    <input type="email" class="form-control neu-input" id="email" name="email" value="<?= set_value('email') ?>" placeholder="nama@email.com" required autofocus>
                                    <?= form_error('email', '<small class="text-danger fw-bold">', '</small>') ?>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="password" class="form-label fw-bold">Password</label>
                                        <a href="<?= base_url('auth/forgotpassword') ?>" class="text-decoration-none small text-primary fw-bold">Lupa password?</a>
                                    </div>
                                    <div class="input-group neu-input-group">
                                        <input type="password" class="form-control neu-input border-end-0" id="password" name="password" placeholder="Masukkan password" required>
                                        <button class="btn btn-outline-dark border-start-0 toggle-password" type="button" style="border: 2px solid #000;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?= form_error('password', '<small class="text-danger fw-bold">', '</small>') ?>
                                </div>
                                
                                <div class="form-check mb-4">
                                    <input class="form-check-input neu-checkbox" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label small" for="remember">Ingat saya di perangkat ini</label>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg neu-button fw-bold">
                                        MASUK SEKARANG
                                    </button>
                                </div>

                                <div class="text-center mt-4">
                                    <p class="small text-muted mb-3">Atau masuk dengan</p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= base_url('auth/social_login/Google'); ?>" class="btn btn-outline-dark neu-button-icon" title="Login dengan Google">
                                            <i class="fab fa-google text-danger"></i>
                                        </a>
                                        <a href="<?= base_url('auth/social_login/Facebook'); ?>" class="btn btn-outline-dark neu-button-icon" title="Login dengan Facebook">
                                            <i class="fab fa-facebook-f text-primary"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="text-center mt-3">
                                    <a href="<?= base_url('auth/daftar_admin'); ?>" class="text-decoration-none small text-muted" style="font-size: 0.7rem;">
                                        <i class="fas fa-user-shield me-1"></i> Daftar Admin
                                    </a>
                                </div>
                            </form>
                            
                            <div class="text-center mt-4 d-md-none">
                                <p class="small">Belum punya akun? <a href="<?= base_url('auth/daftar') ?>" class="fw-bold text-primary">Daftar Sekarang</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f0f0f0; }
    .neu-brutalism {
        border: 3px solid #000;
        border-radius: 0;
        box-shadow: 8px 8px 0px #000;
    }
    .neu-input {
        border: 2px solid #000;
        border-radius: 0;
        padding: 10px 15px;
        background: #fff;
    }
    .neu-input:focus {
        box-shadow: 4px 4px 0px #000 !important;
        border-color: #000 !important;
        outline: none !important;
        transform: translate(-2px, -2px);
    }
    .neu-button {
        border: 2px solid #000;
        border-radius: 0;
        box-shadow: 4px 4px 0px #000;
        transition: all 0.2s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .neu-button:hover {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0px #000;
    }
    .neu-button:active {
        transform: translate(4px, 4px);
        box-shadow: none;
    }
    .neu-button-sm {
        border: 2px solid #000;
        box-shadow: 3px 3px 0px #000;
        border-radius: 0;
    }
    .neu-button-sm:hover {
        transform: translate(1px, 1px);
        box-shadow: 2px 2px 0px #000;
    }
    .neu-button-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #000;
        border-radius: 0;
        box-shadow: 3px 3px 0px #000;
        transition: all 0.2s;
        background: #fff;
    }
    .neu-button-icon:hover {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0px #000;
    }
    .neu-checkbox {
        border: 2px solid #000;
        border-radius: 0;
        cursor: pointer;
    }
    .neu-checkbox:checked {
        background-color: #000;
        border-color: #000;
    }
    .overlay-pattern {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(#000 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.1;
        pointer-events: none;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

// function simulateLogin(provider) removed


    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
