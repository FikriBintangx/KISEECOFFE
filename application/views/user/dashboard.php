<?php
// Hitung statistik user
$total_transaksi_user = isset($total_transaksi) ? $total_transaksi : 0;
$total_belanja_user = isset($total_belanja) ? $total_belanja : 0;
$poin_user = isset($user['poin']) ? $user['poin'] : 0;

// Member sejak menggunakan date_created (timestamp registrasi)
$member_sejak = isset($user['date_created']) ? date('d M Y', $user['date_created']) : '-';

// Tier info
$tier = isset($tier) ? $tier : 'BRONZE';
$tier_color = isset($tier_color) ? $tier_color : '#cd7f32';
$tier_icon = isset($tier_icon) ? $tier_icon : 'fa-award';
?>

<style>
/* BRUTALISM DASHBOARD - Bootstrap Aligned */
.neu-brutalism-border {
    border: 3px solid #000 !important;
    box-shadow: 6px 6px 0 #000 !important;
    border-radius: 0 !important;
}

.dashboard-card {
    transition: all 0.2s;
}

.dashboard-card:hover {
    transform: translate(-2px, -2px);
    box-shadow: 8px 8px 0 #000 !important;
}

.stat-box {
    border: 3px solid #000;
    box-shadow: 4px 4px 0 #000;
    transition: all 0.2s;
}

.stat-box:hover {
    transform: translate(-2px, -2px);
    box-shadow: 6px 6px 0 #000;
}

.stat-number {
    font-family: 'Archivo Black', sans-serif;
    font-size: 2.5rem;
    line-height: 1;
    margin: 10px 0;
    font-weight: 900;
}

.stat-label {
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.9rem;
    color: #555;
}

.quick-action-btn {
    border: 3px solid #000 !important;
    padding: 20px 15px !important;
    text-align: center;
    font-weight: 900;
    text-transform: uppercase;
    box-shadow: 4px 4px 0 #000;
    transition: all 0.2s;
    text-decoration: none !important;
    color: #000;
    display: block;
    height: 100%;
}

.quick-action-btn:hover {
    transform: translate(-2px, -2px);
    box-shadow: 6px 6px 0 #000;
    color: #000;
    text-decoration: none;
}

.badge-tier {
    display: inline-block;
    background: #000;
    color: #fff200;
    padding: 8px 20px;
    border: 3px solid #000;
    font-family: 'Archivo Black', sans-serif;
    font-size: 1.5rem;
    transform: rotate(-2deg);
    box-shadow: 4px 4px 0 #888;
}

.section-title {
    font-family: 'Archivo Black', sans-serif;
    font-weight: 900;
    text-transform: uppercase;
    border-bottom: 3px solid #000;
    padding-bottom: 10px;
}
</style>

<div class="main-content">
    <section class="section">
        
        <!-- WELCOME HEADER -->
        <div class="card neu-brutalism-border mb-4" style="background: var(--yellow);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="mb-2" style="font-family: 'Archivo Black', sans-serif; font-size: 2.5rem; text-transform: uppercase;">
                            <i class="fas fa-fire mr-2"></i> HI, <?= strtoupper($user['nama']); ?>!
                        </h1>
                        <p class="font-weight-bold mb-0">
                            Selamat datang di dashboard kamu. Cek statistik dan mulai order!
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <div class="badge-tier" style="background: <?= $tier_color; ?>; color: <?= ($tier == 'PLATINUM' || $tier == 'SILVER') ? '#000' : '#fff'; ?>;">
                            <i class="fas <?= $tier_icon; ?> mr-2"></i> <?= $tier; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->session->flashdata('message') ?>

        <!-- STATISTIK USER -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6 mb-3">
                <div class="card stat-box h-100 neu-brutalism-border" style="background: var(--green);">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-receipt fa-2x mb-2"></i>
                        <div class="stat-number"><?= $total_transaksi_user; ?></div>
                        <div class="stat-label">Total Order</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="card stat-box h-100 neu-brutalism-border" style="background: var(--yellow);">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-coins fa-2x mb-2"></i>
                        <div class="stat-number"><?= $poin_user; ?></div>
                        <div class="stat-label">Poin Kamu</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="card stat-box h-100 neu-brutalism-border" style="background: var(--blue);">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-money-bill-wave fa-2x mb-2 text-white"></i>
                        <div class="stat-number text-white" style="font-size: 1.5rem;">Rp <?= number_format($total_belanja_user, 0, ',', '.'); ?></div>
                        <div class="stat-label text-white">Total Belanja</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="card stat-box h-100 neu-brutalism-border" style="background: var(--pink);">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-calendar-alt fa-2x mb-2 text-white"></i>
                        <div class="stat-number text-white" style="font-size: 1.2rem;"><?= $member_sejak; ?></div>
                        <div class="stat-label text-white">Member Sejak</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="row mb-4">
            <div class="col-12 mb-3">
                <h3 class="section-title">
                    <i class="fas fa-bolt mr-2"></i> Quick Actions
                </h3>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <a href="<?= base_url('home'); ?>" class="btn btn-block quick-action-btn" style="background: var(--yellow);">
                    <i class="fas fa-utensils fa-2x mb-2 d-block"></i>
                    <span class="font-weight-bold">Pesan Menu</span>
                </a>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <a href="<?= base_url('transaksi/riwayat'); ?>" class="btn btn-block quick-action-btn" style="background: #000; color: #fff;">
                    <i class="fas fa-history fa-2x mb-2 d-block"></i>
                    <span class="font-weight-bold">Riwayat</span>
                </a>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <a href="<?= base_url('transaksi/keranjang'); ?>" class="btn btn-block quick-action-btn" style="background: var(--green);">
                    <i class="fas fa-shopping-cart fa-2x mb-2 d-block"></i>
                    <span class="font-weight-bold">Keranjang</span>
                </a>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <a href="<?= base_url('user/ubah'); ?>" class="btn btn-block quick-action-btn" style="background: var(--blue); color: #fff;">
                    <i class="fas fa-user-edit fa-2x mb-2 d-block"></i>
                    <span class="font-weight-bold">Edit Profil</span>
                </a>
            </div>
        </div>

        <!-- TRANSAKSI TERAKHIR -->
        <?php if (!empty($transaksi_terakhir)): ?>
        <div class="card dashboard-card neu-brutalism-border mb-4">
            <div class="card-header bg-dark text-white border-0">
                <h4 class="mb-0" style="font-family: 'Archivo Black', sans-serif; text-transform: uppercase;">
                    <i class="fas fa-clock mr-2"></i> Transaksi Terakhir
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="border: 3px solid #000;">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="border-0">Kode</th>
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Total</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($transaksi_terakhir, 0, 5) as $t): ?>
                            <tr class="font-weight-600">
                                <td class="font-weight-bold"><?= $t['kode_transaksi']; ?></td>
                                <td><?= date('d M Y', strtotime($t['created_at'])); ?></td>
                                <td class="font-weight-bold">Rp <?= number_format($t['total_harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php
                                    $st = isset($t['payment_status']) ? $t['payment_status'] : 'unpaid';
                                    $badge_class = 'secondary';
                                    $lbl = 'BELUM BAYAR';
                                    if($st == 'paid') { $badge_class = 'success'; $lbl = 'LUNAS'; }
                                    elseif($st == 'awaiting_verification') { $badge_class = 'warning'; $lbl = 'VERIFIKASI'; }
                                    elseif($st == 'failed') { $badge_class = 'danger'; $lbl = 'GAGAL'; }
                                    ?>
                                    <span class="badge badge-<?= $badge_class; ?> font-weight-bold px-3 py-2" style="border: 2px solid #000; box-shadow: 2px 2px 0 #000;">
                                        <?= $lbl; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('transaksi/detail/') . $t['id']; ?>" class="btn btn-sm btn-outline-dark font-weight-bold" style="border: 2px solid #000; box-shadow: 2px 2px 0 #000;">
                                        DETAIL
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-right">
                <a href="<?= base_url('transaksi/riwayat'); ?>" class="btn btn-dark font-weight-bold" style="border: 3px solid #000; box-shadow: 4px 4px 0 #888; padding: 10px 25px;">
                    LIHAT SEMUA <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="card dashboard-card neu-brutalism-border mb-4 text-center" style="background: #f8f9fa;">
            <div class="card-body py-5">
                <i class="fas fa-ghost fa-5x mb-3 text-muted"></i>
                <h4 class="font-weight-bold">Belum Ada Transaksi</h4>
                <p class="text-muted mb-4">Yuk mulai order menu favorit kamu!</p>
                <a href="<?= base_url('home'); ?>" class="btn btn-lg font-weight-bold" style="background: var(--yellow); border: 3px solid #000; box-shadow: 4px 4px 0 #000; padding: 15px 40px;">
                    MULAI BELANJA
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- MENU FAVORIT -->
        <?php if (!empty($menu_favorit)): ?>
        <div class="card dashboard-card neu-brutalism-border">
            <div class="card-header text-white border-0" style="background: #f472b6;">
                <h4 class="mb-0" style="font-family: 'Archivo Black', sans-serif; text-transform: uppercase;">
                    <i class="fas fa-heart mr-2"></i> Menu Paling Sering Kamu Pesan
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach (array_slice($menu_favorit, 0, 3) as $mf): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100" style="border: 3px solid #000; box-shadow: 4px 4px 0 #000;">
                            <div style="height: 150px; border-bottom: 3px solid #000; overflow: hidden;">
                                <img src="<?= base_url('assets/img/makanan/' . $mf['gambar']); ?>" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="font-weight-bold text-uppercase mb-2"><?= $mf['nama']; ?></h6>
                                <div class="badge badge-warning font-weight-bold px-3 py-2" style="border: 2px solid #000; font-size: 0.9rem;">
                                    <?= $mf['jumlah_order']; ?>x Dipesan
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </section>
</div>
