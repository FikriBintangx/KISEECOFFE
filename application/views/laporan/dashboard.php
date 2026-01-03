<div class="main-content">
    <section class="section">
        <div class="section-header neu-brutalism-border">
            <h1><?= $title; ?></h1>
        </div>

        <div class="text-right mb-4">
            <button onclick="window.print()" class="btn btn-success neu-brutalism">
                <i class="fas fa-print"></i> Print Laporan
            </button>
        </div>

        <div class="card neu-brutalism-border" id="printArea">
            <div class="card-header">
                <h4>Laporan Dashboard - KiiseCoffee</h4>
                <p class="text-muted">Tanggal Cetak: <?= date('d F Y H:i:s'); ?></p>
            </div>
            <div class="card-body">
                <h5 class="mb-4">Statistik Umum</h5>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total User</h4>
                                </div>
                                <div class="card-body">
                                    <?= number_format($total_user); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Menu</h4>
                                </div>
                                <div class="card-body">
                                    <?= number_format($total_makanan); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Transaksi</h4>
                                </div>
                                <div class="card-body">
                                    <?= isset($total_transaksi) ? number_format($total_transaksi) : 0; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Penjualan</h4>
                                </div>
                                <div class="card-body">
                                    Rp <?= isset($total_penjualan) ? number_format($total_penjualan, 0, ',', '.') : '0'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Grafik Penjualan</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="salesChart" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Menu Terlaris</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="menuChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($transaksi_per_status) && !empty($transaksi_per_status)) : ?>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">Transaksi per Status</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transaksi_per_status as $tps) : ?>
                                        <tr>
                                            <td><?= ucfirst(html_escape($tps['status'])); ?></td>
                                            <td><?= $tps['jumlah']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Statistik Hari Ini</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Transaksi Hari Ini</td>
                                        <td><?= isset($transaksi_hari_ini) ? $transaksi_hari_ini : 0; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Penjualan Hari Ini</td>
                                        <td>Rp <?= isset($penjualan_hari_ini) ? number_format($penjualan_hari_ini, 0, ',', '.') : '0'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Menu per Kategori</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($makanan_per_kategori as $mpk) : ?>
                                        <tr>
                                            <td><?= html_escape($mpk['kategori']); ?></td>
                                            <td><?= $mpk['jumlah']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">User per Role</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($user_per_role as $upr) : ?>
                                        <tr>
                                            <td><?= html_escape($upr['role']); ?></td>
                                            <td><?= $upr['jumlah']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">Menu Terbaru</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Menu</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($makanan_terbaru as $mt) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= html_escape($mt['nama']); ?></td>
                                            <td><?= html_escape($mt['kategori']); ?></td>
                                            <td>Rp <?= number_format($mt['harga'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (isset($transaksi_terbaru) && !empty($transaksi_terbaru)) : ?>
                    <div class="col-md-6">
                        <h5 class="mb-3">Transaksi Terbaru</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>User</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($transaksi_terbaru as $tt) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= html_escape($tt['kode_transaksi']); ?></td>
                                            <td><?= html_escape($tt['nama_user']); ?></td>
                                            <td>Rp <?= number_format($tt['total_harga'], 0, ',', '.'); ?></td>
                                            <td>
                                                <?php
                                                $badge_class = 'secondary';
                                                if ($tt['status'] == 'selesai') $badge_class = 'success';
                                                elseif ($tt['status'] == 'diproses') $badge_class = 'warning';
                                                elseif ($tt['status'] == 'dibatalkan') $badge_class = 'danger';
                                                ?>
                                                <span class="badge badge-<?= $badge_class; ?>"><?= ucfirst(html_escape($tt['status'])); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dummy untuk visualisasi (ganti dengan data PHP jika ada)
    const ctxSales = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Penjualan Minggu Ini',
                data: [120000, 190000, 300000, 500000, 200000, 300000, 450000],
                borderColor: '#000',
                borderWidth: 2,
                backgroundColor: 'rgba(0,0,0,0.1)',
                tension: 0.4
            }]
        },
        options: { responsive: true }
    });

    const ctxMenu = document.getElementById('menuChart').getContext('2d');
    const menuChart = new Chart(ctxMenu, {
        type: 'doughnut',
        data: {
            labels: ['Kopi Susu', 'Americano', 'Latte', 'Snack', 'Lainnya'],
            datasets: [{
                data: [30, 20, 15, 25, 10],
                backgroundColor: ['#6777ef', '#63ed7a', '#ffa426', '#fc544b', '#cdd3d8'],
            }]
        }
    });
</script>

<style>
    @media print {
        @page { size: landscape; }
        body { -webkit-print-color-adjust: exact; visibility: hidden; }
        #printArea, #printArea * { visibility: visible; }
        #printArea { position: absolute; left: 0; top: 0; width: 100%; }
        .section-header, .btn, canvas { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        table { font-size: 12px; width: 100% !important; }
    }
</style>