<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="mb-4 p-4" style="background: #fff200; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <h1 style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; color: #000; margin: 0;">
                <i class="fas fa-user-edit mr-2"></i> EDIT PROFIL
            </h1>
        </div>

        <?= $this->session->flashdata('message') ?>

        <div style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
            <div class="card-body p-4">
                <?= form_open_multipart('user/ubah'); ?>
                
                <div class="row">
                    <!-- KOLOM INPUT (Kiri) -->
                    <div class="col-md-7 mb-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-2 d-block">Email (Tidak dapat diubah)</label>
                            <input type="text" class="form-control font-weight-bold" name="email" value="<?= $user['email']; ?>" readonly 
                                   style="border: 3px solid #000; border-radius: 0; background: #e9ecef; color: #555; height: 50px;">
                        </div>

                        <div class="form-group mb-4">
                            <label for="nama" class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-2 d-block">Nama Lengkap</label>
                            <?= form_error('nama', '<small class="text-danger font-weight-bold pl-1">', '</small>') ?>
                            <input id="nama" type="text" class="form-control font-weight-bold" name="nama" value="<?= $user['nama']; ?>" 
                                   style="border: 3px solid #000; border-radius: 0; height: 50px;">
                        </div>
                    </div>

                    <!-- KOLOM GAMBAR (Kanan) -->
                    <div class="col-md-5 mb-4 text-center">
                        <div class="p-3" style="border: 3px dashed #000; background: #f8f9fa;">
                            <label class="font-weight-bold mb-3 d-block text-uppercase">FOTO PROFIL</label>
                            <div class="mb-3 d-inline-block p-1" style="border: 3px solid #000; background: #fff;">
                                <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>?v=<?= time(); ?>" class="img-fluid img-preview" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            
                            <div class="custom-file text-left mt-2">
                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/*" onchange="previewImage()" style="cursor: pointer;">
                                <label class="custom-file-label font-weight-bold text-truncate" for="image" style="border: 3px solid #000; border-radius: 0; cursor: pointer;">Pilih File Baru...</label>
                            </div>
                            <small class="text-muted font-weight-bold mt-2 d-block">Format: JPG/PNG, Max 2MB</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-block py-3 font-weight-bold text-uppercase" 
                            style="background: #000; color: #fff200; border: 3px solid #000; box-shadow: 4px 4px 0 #888; font-size: 1.2rem; transition: all 0.2s;"
                            onmouseover="this.style.transform='translate(-2px,-2px)'; this.style.boxShadow='6px 6px 0 #888';"
                            onmouseout="this.style.transform='translate(0,0)'; this.style.boxShadow='4px 4px 0 #888';">
                            <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN
                        </button>
                    </div>
                </div>

                <?= form_close(); ?>
            </div>
        </div>
    </section>
</div>

<script>
    function previewImage() {
        const input = document.getElementById('image');
        const preview = document.querySelector('.img-preview');
        const label = document.querySelector('.custom-file-label');
        
        if (input && preview && input.files && input.files[0]) {
            const file = input.files[0];
            preview.src = URL.createObjectURL(file);
            label.textContent = file.name;
        }
    }
</script>