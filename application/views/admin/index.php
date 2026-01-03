<style>
/* ADMIN DASHBOARD - Bootstrap Brutalism */
.neu-brutalism-border {
    border: 3px solid #000 !important;
    box-shadow: 6px 6px 0 #000 !important;
    border-radius: 0 !important;
}

.neu-brutalism {
    border: 3px solid #000 !important;
    box-shadow: 4px 4px 0 #000 !important;
    border-radius: 0 !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    transition: all 0.2s;
}

.neu-brutalism:hover {
    transform: translate(-2px, -2px);
    box-shadow: 6px 6px 0 #000 !important;
}

.stat-card {
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translate(-2px, -2px);
    box-shadow: 8px 8px 0 #000 !important;
}

.stat-number {
    font-family: 'Archivo Black', sans-serif;
    font-size: 2.5rem;
    line-height: 1;
    font-weight: 900;
}

.stat-label {
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.9rem;
}

.section-title {
    font-family: 'Archivo Black', sans-serif;
    font-weight: 900;
    text-transform: uppercase;
    border-bottom: 3px solid #000;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.card-header h4 {
    margin: 0;
    font-family: 'Archivo Black', sans-serif;
    text-transform: uppercase;
}
</style>

<div class="main-content">
    <section class="section">
        <!-- HEADER -->
        <div class="mb-4">
            <h1 style="font-family: 'Archivo Black', sans-serif; font-size: 2.5rem; text-transform: uppercase;">
                <i class="fas fa-tachometer-alt mr-2"></i> <?= $title; ?>
            </h1>
        </div>
        
        <?php if ($this->session->flashdata('message')): ?>
        <div class="row">
            <div class="col-12">
                <?= $this->session->flashdata('message'); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- CONTROL PANEL: DAPUR & TOKO -->
        <div class="row mb-4">
            <!-- STATUS DAPUR -->
            <div class="col-md-6 mb-3">
                <div class="card neu-brutalism-border h-100" style="background: #E9D5FF;">
                    <div class="card-header bg-dark text-white border-0">
                        <h4><i class="fas fa-fire mr-2"></i> STATUS DAPUR</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/update_kitchen_status'); ?>" method="POST">
                            <button type="submit" name="status" value="1" class="btn btn-lg btn-block neu-brutalism mb-2 <?= ($kitchen_status == 1) ? 'btn-success' : 'btn-outline-success'; ?>">
                                <i class="fas fa-smile mr-2"></i> SANTAI (5-10mnt)
                            </button>
                            <button type="submit" name="status" value="2" class="btn btn-lg btn-block neu-brutalism mb-2 <?= ($kitchen_status == 2) ? 'btn-warning' : 'btn-outline-warning'; ?>">
                                <i class="fas fa-stopwatch mr-2"></i> SIBUK (15-20mnt)
                            </button>
                            <button type="submit" name="status" value="3" class="btn btn-lg btn-block neu-brutalism <?= ($kitchen_status == 3) ? 'btn-danger' : 'btn-outline-danger'; ?>">
                                <i class="fas fa-fire mr-2"></i> NGEBUL (30+mnt)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- STATUS TOKO -->
            <?php 
                $shop_stat = isset($shop_override_status) ? $shop_override_status : 'auto'; 
            ?>
            <div class="col-md-6 mb-3">
                <div class="card neu-brutalism-border h-100" style="background: #BFDBFE;">
                    <div class="card-header bg-primary text-white border-0">
                        <h4><i class="fas fa-store mr-2"></i> STATUS TOKO</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/update_shop_status'); ?>" method="POST">
                            <button type="submit" name="status" value="auto" class="btn btn-lg btn-block neu-brutalism mb-2 <?= ($shop_stat == 'auto') ? 'btn-info' : 'btn-outline-info'; ?>">
                                <i class="fas fa-robot mr-2"></i> OTOMATIS (Ikut Jam)
                            </button>
                            <button type="submit" name="status" value="open" class="btn btn-lg btn-block neu-brutalism mb-2 <?= ($shop_stat == 'open') ? 'btn-success' : 'btn-outline-success'; ?>">
                                <i class="fas fa-door-open mr-2"></i> PAKSA BUKA
                            </button>
                            <button type="submit" name="status" value="closed" class="btn btn-lg btn-block neu-brutalism <?= ($shop_stat == 'closed') ? 'btn-danger' : 'btn-outline-danger'; ?>">
                                <i class="fas fa-door-closed mr-2"></i> PAKSA TUTUP
                            </button>
                        </form>
                        <small class="text-muted mt-2 d-block text-center font-weight-bold">
                            *Otomatis: Buka 08:00 - 22:00
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ALERT STOK MENIPIS -->
        <?php if (!empty($stok_menipis)): ?>
        <div class="alert alert-danger neu-brutalism mb-4" role="alert">
            <h5 class="alert-heading font-weight-bold">
                <i class="fas fa-exclamation-triangle mr-2"></i> Peringatan Stok Menipis!
            </h5>
            <p class="mb-2">Beberapa menu berikut memiliki stok kurang dari 5:</p>
            <ul class="mb-3">
                <?php foreach($stok_menipis as $sm): ?>
                    <li>
                        <strong><?= $sm['nama']; ?></strong> 
                        <span class="badge badge-danger ml-2">Sisa: <?= $sm['stok']; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <hr>
            <a href="<?= base_url('makanan'); ?>" class="btn btn-light btn-sm neu-brutalism text-danger font-weight-bold">
                <i class="fas fa-edit mr-1"></i> Kelola Stok Sekarang
            </a>
        </div>
        <?php endif; ?>

        <!-- STATISTIK CARDS -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-6 mb-3">
                <!-- Total User: White -> Soft Blue -->
                <div class="card stat-card neu-brutalism-border h-100" style="background: #BAE6FD !important;">
                    <div class="card-body text-center p-3">
                        <div class="mb-3" style="width: 60px; height: 60px; margin: 0 auto; background: #000; border: 3px solid #000; display: flex; align-items: center; justify-content: center;">
                            <i class="far fa-user fa-2x text-white"></i>
                        </div>
                        <h4 class="stat-number"><?= $total_user; ?></h4>
                        <p class="stat-label mb-0" style="color: #000;">Total User</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-3">
                <div class="card stat-card neu-brutalism-border h-100" style="background: #BBF7D0 !important;">
                    <div class="card-body text-center p-3">
                        <div class="mb-3" style="width: 60px; height: 60px; margin: 0 auto; background: #000; border: 3px solid #000; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-utensils fa-2x text-white"></i>
                        </div>
                        <h4 class="stat-number"><?= isset($total_makanan) ? $total_makanan : 0; ?></h4>
                        <p class="stat-label mb-0">Total Menu</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-3">
                <div class="card stat-card neu-brutalism-border h-100" style="background: #FEF08A !important;">
                    <div class="card-body text-center p-3">
                        <div class="mb-3" style="width: 60px; height: 60px; margin: 0 auto; background: #000; border: 3px solid #000; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-shopping-bag fa-2x text-white"></i>
                        </div>
                        <h4 class="stat-number"><?= isset($total_transaksi) ? $total_transaksi : 0; ?></h4>
                        <p class="stat-label mb-0">Total Transaksi</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-3">
                <!-- Total Penjualan: Black -> Soft Orange/Pink -->
                <div class="card stat-card neu-brutalism-border h-100" style="background: #FED7AA !important;">
                    <div class="card-body text-center p-3">
                        <div class="mb-3" style="width: 60px; height: 60px; margin: 0 auto; background: #000; border: 3px solid #000; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                        </div>
                        <h4 class="stat-number" style="font-size: 1.5rem; color: #000;">Rp <?= isset($total_penjualan) ? number_format($total_penjualan, 0, ',', '.') : '0'; ?></h4>
                        <p class="stat-label mb-0" style="color: #000;">Total Penjualan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATISTIK HARI INI -->
        <?php if (isset($transaksi_hari_ini) || isset($penjualan_hari_ini)) : ?>
        <div class="row mb-4">
            <div class="col-lg-6 col-md-6 mb-3">
                <div class="card stat-card neu-brutalism-border h-100" style="background: #BFDBFE;">
                    <div class="card-body text-center p-3">
                        <div class="mb-3" style="width: 60px; height: 60px; margin: 0 auto; background: #000; border: 3px solid #000; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-calendar-day fa-2x text-white"></i>
                        </div>
                        <h4 class="stat-number text-dark"><?= isset($transaksi_hari_ini) ? $transaksi_hari_ini : 0; ?></h4>
                        <p class="stat-label text-dark mb-0">Transaksi Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-3">
                <div class="card stat-card neu-brutalism-border h-100" style="background: #BBF7D0;">
                    <div class="card-body text-center p-3">
                        <div class="mb-3" style="width: 60px; height: 60px; margin: 0 auto; background: #000; border: 3px solid #000; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-line fa-2x text-white"></i>
                        </div>
                        <h4 class="stat-number" style="font-size: 1.5rem;">Rp <?= isset($penjualan_hari_ini) ? number_format($penjualan_hari_ini, 0, ',', '.') : '0'; ?></h4>
                        <p class="stat-label mb-0">Penjualan Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- MENU PER KATEGORI -->
        <?php if (isset($makanan_per_kategori) && !empty($makanan_per_kategori)) : ?>
        <div class="row mb-4">
            <div class="col-12 mb-3">
                <h3 class="section-title">
                    <i class="fas fa-chart-pie mr-2"></i> Menu Per Kategori
                </h3>
            </div>
            <?php 
            $colors = ['#FEF08A', '#BBF7D0', '#BFDBFE', '#FBCFE8', '#FED7AA'];
            $i = 0;
            foreach ($makanan_per_kategori as $mpk): 
                $bg = $colors[$i % count($colors)];
                $i++;
            ?>
            <div class="col-lg-3 col-md-4 col-6 mb-3">
                <div class="card stat-card neu-brutalism-border h-100" style="background: <?= $bg; ?>;">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-utensils fa-3x mb-3"></i>
                        <h3 class="stat-number"><?= $mpk['jumlah']; ?></h3>
                        <p class="stat-label mb-0"><?= $mpk['kategori']; ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- QUICK ACTIONS -->
        <div class="card neu-brutalism-border">
            <div class="card-header bg-dark text-white border-0">
                <h4><i class="fas fa-bolt mr-2"></i> Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                        <a href="<?= base_url('laporan'); ?>" class="btn btn-block neu-brutalism py-3" style="background: #FEF08A; color: #000; height: 100%;">
                            <i class="fas fa-file-alt fa-2x d-block mb-2"></i>
                            <span class="font-weight-bold">Laporan</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                        <a href="<?= base_url('transaksi/kelola'); ?>" class="btn btn-block neu-brutalism py-3" style="background: #BFDBFE; color: #000; height: 100%;">
                            <i class="fas fa-shopping-bag fa-2x d-block mb-2"></i>
                            <span class="font-weight-bold">Transaksi</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                        <a href="<?= base_url('makanan'); ?>" class="btn btn-block neu-brutalism py-3" style="background: #BBF7D0; color: #000; height: 100%;">
                            <i class="fas fa-utensils fa-2x d-block mb-2"></i>
                            <span class="font-weight-bold">Menu</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                        <a href="<?= base_url('admin/role_user'); ?>" class="btn btn-block neu-brutalism py-3" style="background: #DDD6FE; color: #000; height: 100%;">
                            <i class="fas fa-users fa-2x d-block mb-2"></i>
                            <span class="font-weight-bold">Users</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
