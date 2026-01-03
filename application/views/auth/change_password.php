<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-md-6 col-lg-5">
            <div class="card neu-brutalism overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-key fa-3x mb-3"></i>
                        <h3 class="fw-black text-uppercase">Ubah Password</h3>
                        <p class="text-muted small">Silakan buat password baru untuk akun <?= $this->session->userdata('reset_email'); ?></p>
                    </div>
                    
                    <?= $this->session->flashdata('message') ?>
                    
                    <form method="POST" action="<?= base_url('auth/change_password'); ?>">
                        <div class="mb-3">
                            <label for="password1" class="form-label fw-bold">Password Baru</label>
                            <input type="password" class="form-control neu-input" id="password1" name="password1" placeholder="Minimal 3 karakter" required>
                            <?= form_error('password1', '<small class="text-danger fw-bold">', '</small>') ?>
                        </div>

                        <div class="mb-4">
                            <label for="password2" class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" class="form-control neu-input" id="password2" name="password2" placeholder="Ulangi password" required>
                            <?= form_error('password2', '<small class="text-danger fw-bold">', '</small>') ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg neu-button fw-bold">
                                GANTI PASSWORD
                            </button>
                        </div>
                    </form>
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
</style>
