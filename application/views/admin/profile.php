<div class="main-content">
    <section class="section">
        <div class="section-header neu-brutalism-border">
            <h1><?= $title; ?></h1>
        </div>

        <div class="row">
            <!-- PROFILE CARD & EDIT -->
            <div class="col-lg-6">
                <!-- ERROR MESSAGE -->
                <?= $this->session->flashdata('message'); ?>
                <?= form_error('nama', '<div class="alert alert-danger neu-brutalism">', '</div>'); ?>

                <div class="card neu-brutalism-border">
                    <div class="card-header bg-primary text-white">
                        <h4>Edit Profile Data</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open_multipart('admin/update_profile'); ?>
                        <input type="hidden" name="email" value="<?= $user['email']; ?>">
                        <input type="hidden" name="old_image" value="<?= $user['image']; ?>">

                        <div class="form-group row text-center">
                            <div class="col-sm-12">
                                <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail rounded-circle mb-3 neu-brutalism-border" style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="custom-file text-left">
                                    <input type="file" class="custom-file-input" id="image" name="image">
                                    <label class="custom-file-label neu-brutalism-border" for="image">Pilih Gambar Baru</label>
                                </div>
                                <small class="text-muted">Format: jpg, png, webp (Max 5MB)</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email (Tidak bisa diubah)</label>
                            <input type="text" class="form-control neu-brutalism-border" id="email" value="<?= $user['email']; ?>" readonly style="background-color: #e9ecef;">
                        </div>

                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control neu-brutalism-border" id="nama" name="nama" value="<?= $user['nama']; ?>">
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary btn-lg btn-block neu-brutalism">
                                <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- CHANGE PASSWORD -->
            <div class="col-lg-6">
                <!-- ERROR MESSAGE PASSWORD -->
                <?= $this->session->flashdata('message_password'); ?>
                <?= form_error('current_password', '<div class="alert alert-danger neu-brutalism">', '</div>'); ?>
                <?= form_error('new_password1', '<div class="alert alert-danger neu-brutalism">', '</div>'); ?>
                <?= form_error('new_password2', '<div class="alert alert-danger neu-brutalism">', '</div>'); ?>

                <div class="card neu-brutalism-border">
                    <div class="card-header bg-warning text-dark">
                        <h4>Ganti Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/change_password'); ?>" method="post">
                            <div class="form-group">
                                <label for="current_password">Password Saat Ini</label>
                                <input type="password" class="form-control neu-brutalism-border" id="current_password" name="current_password">
                            </div>

                            <div class="form-group">
                                <label for="new_password1">Password Baru</label>
                                <input type="password" class="form-control neu-brutalism-border" id="new_password1" name="new_password1">
                            </div>

                            <div class="form-group">
                                <label for="new_password2">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control neu-brutalism-border" id="new_password2" name="new_password2">
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-warning btn-lg btn-block neu-brutalism">
                                    <i class="fas fa-key mr-2"></i> GANTI PASSWORD
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card neu-brutalism-border mt-4">
                     <div class="card-body">
                         <h5 class="font-weight-bold">Info Akun</h5>
                         <hr>
                         <p><strong>Bergabung Sejak:</strong> <?= date('d F Y', $user['date_created']); ?></p>
                         <p><strong>Role ID:</strong> <?= $user['role_id'] == 1 ? 'Administrator' : 'Member'; ?></p>
                     </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Custom File Input Label
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("image").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
