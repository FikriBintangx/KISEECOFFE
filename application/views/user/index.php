<!-- Main Content -->
<div class="main-content">
    <section class="section">
        
        <!-- HEADER DASHBOARD + PROFILE QUICK ACCESS -->
        <div class="mb-4 p-4 position-relative" style="background: #fff200; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <div class="row align-items-center">
                <div class="col-md-8 col-8">
                    <h1 style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 1.8rem; text-transform: uppercase; color: #000; margin: 0; letter-spacing: -1px;">
                        HI, <?= strtoupper($user['nama']); ?>!
                    </h1>
                    <p class="font-weight-bold mb-0 mt-1 text-dark d-none d-md-block">
                        Selamat datang kembali! Lapar? Cek menu di bawah.
                    </p>
                </div>
                
                <!-- ICON PROFILE (DROPDOWN) -->
                <div class="col-md-4 col-4 text-right">
                    <div class="dropdown d-inline-block">
                        <button class="btn p-0 rounded-circle" type="button" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 55px; height: 55px; border: 3px solid #000; overflow: hidden; box-shadow: 4px 4px 0 #fff;">
                            <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="w-100 h-100" style="object-fit: cover;">
                        </button>
                        
                        <!-- Dropdown Menu Brutalist -->
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown" style="border: 3px solid #000; border-radius: 0; box-shadow: 6px 6px 0 rgba(0,0,0,0.2); min-width: 200px; padding: 0;">
                            <div class="px-3 py-2 bg-dark text-white font-weight-bold text-uppercase" style="border-bottom: 3px solid #000;">
                                AKUN SAYA
                            </div>
                            <a class="dropdown-item py-3 font-weight-bold border-bottom border-dark" href="<?= base_url('user/ubah'); ?>">
                                <i class="fas fa-user-edit mr-2 text-primary"></i> Edit Profil
                            </a>
                            <a class="dropdown-item py-3 font-weight-bold border-bottom border-dark" href="<?= base_url('user/ganti_kata_sandi'); ?>">
                                <i class="fas fa-key mr-2 text-warning"></i> Ganti Sandi
                            </a>
                            <a class="dropdown-item py-3 font-weight-bold text-danger" href="<?= base_url('auth/keluar'); ?>">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->session->flashdata('message') ?>

        <div class="row">
            <!-- KOLOM KIRI: REKOMENDASI MAKANAN (LOAD DR DB) -->
            <div class="col-lg-8 mb-4">
                <div style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000; height: 100%;">
                    <div class="p-3 d-flex justify-content-between align-items-center" style="border-bottom: 3px solid #000; background: #000; color: #fff;">
                        <h4 class="m-0 font-weight-bold" style="font-family: 'Archivo Black'; font-size: 1.2rem;">
                            <i class="fas fa-fire mr-2 text-warning"></i> REKOMENDASI UNTUKMU
                        </h4>
                        <a href="<?= base_url('home'); ?>" class="btn btn-sm font-weight-bold" style="background: #fff200; color: #000; border: 2px solid #fff; border-radius: 0;">LIHAT MENU</a>
                    </div>
                    
                    <div class="p-4">
                        <!-- LOGIC PHP UNTUK LOAD GAMBAR -->
                        <?php if(isset($makanan) && !empty($makanan)): ?>
                            <div class="row">
                                <?php 
                                // Ambil 3 makanan acak untuk ditampilkan
                                shuffle($makanan);
                                $display_food = array_slice($makanan, 0, 3);
                                foreach($display_food as $m): 
                                ?>
                                <div class="col-md-4 mb-4">
                                    <div class="h-100" style="border: 3px solid #000; transition: transform 0.2s;">
                                        <div style="height: 140px; overflow: hidden; border-bottom: 3px solid #000;">
                                            <img src="<?= base_url('assets/img/makanan/') . $m['gambar']; ?>" class="w-100 h-100" style="object-fit: cover;">
                                        </div>
                                        <div class="p-3 text-center bg-white">
                                            <h6 class="font-weight-bold text-uppercase text-truncate mb-1" style="font-family: 'Archivo Black';"><?= $m['nama']; ?></h6>
                                            <div class="font-weight-bold" style="color: #000; background: #fff200; display: inline-block; padding: 2px 5px; border: 2px solid #000;">
                                                Rp <?= number_format($m['harga'], 0, ',', '.'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <!-- Fallback jika data tidak ada -->
                            <div class="text-center py-5">
                                <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                                <h5 class="font-weight-bold">Belum ada rekomendasi.</h5>
                                <a href="<?= base_url('home'); ?>" class="btn btn-dark font-weight-bold mt-2" style="border: 2px solid #000;">Pesan Sekarang</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: INFO USER & STATS -->
            <div class="col-lg-4">
                
                <!-- CARD STATUS MEMBER -->
                <div class="mb-4 p-3 d-flex align-items-center" style="background: #e3f2fd; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                    <div class="mr-3 p-2 bg-white border border-dark rounded-circle">
                        <i class="fas fa-crown fa-2x text-warning"></i>
                    </div>
                    <div>
                        <small class="font-weight-bold text-uppercase text-muted">Status Member</small>
                        <h4 class="font-weight-bold m-0" style="font-family: 'Archivo Black'; color: #000;">GOLD TIER</h4>
                    </div>
                </div>

                <!-- CARD INFO AKUN -->
                <div style="background: #fff; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                    <div class="p-3" style="border-bottom: 3px solid #000; background: #f8f9fa;">
                        <h5 class="m-0 font-weight-bold text-uppercase text-dark" style="font-family: 'Archivo Black';">
                            DATA AKUN
                        </h5>
                    </div>
                    <div class="p-4">
                        <div class="mb-3">
                            <label class="font-weight-bold text-uppercase text-muted mb-0" style="font-size: 0.8rem;">Email Terdaftar</label>
                            <div class="font-weight-bold text-dark border-bottom border-dark pb-1"><?= $user['email']; ?></div>
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold text-uppercase text-muted mb-0" style="font-size: 0.8rem;">Bergabung Sejak</label>
                            <div class="font-weight-bold text-dark border-bottom border-dark pb-1"><?= $tanggal_bergabung; ?></div>
                        </div>
                        
                        <a href="<?= base_url('transaksi/riwayat'); ?>" class="btn btn-block py-3 font-weight-bold text-uppercase mt-4" 
                           style="background: #000; color: #fff; border: 3px solid #000; box-shadow: 4px 4px 0 #888; transition: all 0.2s;"
                           onmouseover="this.style.transform='translate(-2px,-2px)'; this.style.boxShadow='6px 6px 0 #888';"
                           onmouseout="this.style.transform='translate(0,0)'; this.style.boxShadow='4px 4px 0 #888';">
                            <i class="fas fa-history mr-2"></i> Cek Riwayat
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>