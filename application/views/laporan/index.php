<div class="main-content">
    <section class="section">
        <div class="section-header neu-brutalism-border">
            <h1><?= $title; ?></h1>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 neu-brutalism">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Laporan Menu</h4>
                        </div>
                        <div class="card-body">
                            <a href="<?= base_url('laporan/makanan'); ?>" class="btn btn-primary btn-sm neu-brutalism">
                                <i class="fas fa-file-alt"></i> Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 neu-brutalism">
                    <div class="card-icon bg-success">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Laporan User</h4>
                        </div>
                        <div class="card-body">
                            <a href="<?= base_url('laporan/user'); ?>" class="btn btn-success btn-sm neu-brutalism">
                                <i class="fas fa-file-alt"></i> Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 neu-brutalism">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Laporan Penjualan</h4>
                        </div>
                        <div class="card-body">
                            <a href="<?= base_url('laporan/penjualan'); ?>" class="btn btn-warning btn-sm neu-brutalism">
                                <i class="fas fa-file-alt"></i> Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 neu-brutalism">
                    <div class="card-icon bg-info">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Laporan Dashboard</h4>
                        </div>
                        <div class="card-body">
                            <a href="<?= base_url('laporan/dashboard'); ?>" class="btn btn-info btn-sm neu-brutalism">
                                <i class="fas fa-file-alt"></i> Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>