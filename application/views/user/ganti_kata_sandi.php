<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="mb-4 p-4" style="background: #fff200; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <h1 style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; color: #000; margin: 0;">
                <i class="fas fa-lock mr-2"></i> GANTI KATA SANDI
            </h1>
        </div>

        <?= $this->session->flashdata('message') ?>

        <div style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
            <div class="p-3" style="border-bottom: 3px solid #000; background: #000; color: #fff;">
                <h4 class="m-0 font-weight-bold" style="font-family: 'Archivo Black';">FORM KEAMANAN</h4>
            </div>
            
            <div class="card-body p-4">
                <form action="<?= base_url('user/ganti_kata_sandi'); ?>" method="POST">
                    
                    <div class="form-group mb-4">
                        <label for="current_password" class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-2 d-block">Kata Sandi Saat Ini</label>
                        <?= form_error('current_password', '<small class="text-danger font-weight-bold pl-1">', '</small>') ?>
                        <input id="current_password" type="password" class="form-control font-weight-bold" name="current_password" 
                               style="border: 3px solid #000; border-radius: 0; height: 50px; font-size: 1.1rem;">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="password1" class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-2 d-block">Kata Sandi Baru</label>
                                <?= form_error('password1', '<small class="text-danger font-weight-bold pl-1">', '</small>') ?>
                                <input id="password1" type="password" class="form-control font-weight-bold" name="password1" 
                                       style="border: 3px solid #000; border-radius: 0; height: 50px; font-size: 1.1rem;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="password2" class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-2 d-block">Ulangi Kata Sandi Baru</label>
                                <?= form_error('password2', '<small class="text-danger font-weight-bold pl-1">', '</small>') ?>
                                <input id="password2" type="password" class="form-control font-weight-bold" name="password2" 
                                       style="border: 3px solid #000; border-radius: 0; height: 50px; font-size: 1.1rem;">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning font-weight-bold text-dark" style="border: 2px solid #000; border-radius: 0;">
                        <i class="fas fa-info-circle mr-2"></i> Pastikan kata sandi baru Anda kuat dan sulit ditebak.
                    </div>

                    <button type="submit" class="btn btn-block py-3 font-weight-bold text-uppercase mt-4" 
                        style="background: #000; color: #fff200; border: 3px solid #000; box-shadow: 4px 4px 0 #888; font-size: 1.2rem; transition: all 0.2s;"
                        onmouseover="this.style.transform='translate(-2px,-2px)'; this.style.boxShadow='6px 6px 0 #888';"
                        onmouseout="this.style.transform='translate(0,0)'; this.style.boxShadow='4px 4px 0 #888';">
                        <i class="fas fa-check-circle mr-2"></i> GANTI KATA SANDI
                    </button>
                </form>

            </div>
        </div>
    </section>
</div>