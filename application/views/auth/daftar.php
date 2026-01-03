<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-md-9 col-lg-8">
            <div class="card neu-brutalism overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-block bg-primary text-white p-4 position-relative">
                        <div class="h-100 d-flex flex-column justify-content-center text-center position-relative" style="z-index: 1;">
                            <h3 class="fw-bold mb-4">Gabung KiiseCoffee</h3>
                            <p class="mb-4">Daftar sekarang dan nikmati promo eksklusif khusus member baru.</p>
                            
                            <div class="mb-4 py-3">
                                <i class="fas fa-mug-hot" style="font-size: 8rem; text-shadow: 4px 4px 0 #000;"></i>
                            </div>

                            <p class="mb-0">Sudah punya akun? <br>
                                <a href="<?= base_url('auth') ?>" class="btn btn-light btn-sm mt-2 neu-button-sm fw-bold">Masuk Disini</a>
                            </p>
                        </div>
                        <div class="overlay-pattern"></div>
                    </div>
                    
                    <div class="col-md-7 bg-white">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-black text-uppercase">Buat Akun Baru</h3>
                                <p class="text-muted small">Lengkapi data diri Anda di bawah ini</p>
                            </div>
                            
                            <?= $this->session->flashdata('message') ?>
                            
                            <form method="POST" action="<?= base_url('auth/daftar'); ?>" class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
                                        <input type="text" class="form-control neu-input" id="nama" name="nama" value="<?= set_value('nama') ?>" required>
                                        <?= form_error('nama', '<small class="text-danger fw-bold">', '</small>') ?>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-bold">Email</label>
                                        <input type="email" class="form-control neu-input" id="email" name="email" value="<?= set_value('email') ?>" required>
                                        <?= form_error('email', '<small class="text-danger fw-bold">', '</small>') ?>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password1" class="form-label fw-bold">Kata Sandi</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control neu-input border-end-0" id="password1" name="password1" required>
                                            <button class="btn btn-outline-dark border-start-0 toggle-password" type="button" style="border: 2px solid #000;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <?= form_error('password1', '<small class="text-danger fw-bold">', '</small>') ?>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password2" class="form-label fw-bold">Ulangi Sandi</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control neu-input border-end-0" id="password2" name="password2" required>
                                            <button class="btn btn-outline-dark border-start-0 toggle-password" type="button" style="border: 2px solid #000;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="no_telepon" class="form-label fw-bold">Nomor Telepon</label>
                                    <div class="input-group">
                                        <span class="input-group-text fw-bold border-dark border-2 bg-light">+62</span>
                                        <input type="tel" class="form-control neu-input" id="no_telepon" name="no_telepon" value="<?= set_value('no_telepon') ?>" required>
                                    </div>
                                    <?= form_error('no_telepon', '<small class="text-danger fw-bold">', '</small>') ?>
                                </div>
                                
                                <div class="form-check mb-4">
                                    <input class="form-check-input neu-checkbox" type="checkbox" id="terms" required>
                                    <label class="form-check-label small" for="terms">
                                        Saya setuju dengan <a href="#" class="text-dark fw-bold text-decoration-underline">Syarat & Ketentuan</a>
                                    </label>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg neu-button fw-bold">
                                        DAFTAR SEKARANG
                                    </button>
                                </div>

                                <div class="text-center mt-4">
                                    <p class="small text-muted mb-3">Atau daftar dengan</p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= base_url('auth/social_login/Google'); ?>" class="btn btn-outline-dark neu-button-icon" title="Daftar dengan Google">
                                            <i class="fab fa-google text-danger"></i>
                                        </a>
                                        <a href="<?= base_url('auth/social_login/Facebook'); ?>" class="btn btn-outline-dark neu-button-icon" title="Daftar dengan Facebook">
                                            <i class="fab fa-facebook-f text-primary"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="text-center mt-4 d-md-none">
                                <p class="small">Sudah punya akun? <a href="<?= base_url('auth') ?>" class="fw-bold text-primary">Masuk Disini</a></p>
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
