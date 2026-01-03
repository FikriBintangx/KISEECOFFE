<div class="main-content">
    <section class="section">
        <div class="section-header neu-brutalism-border">
            <h1><?= $title; ?></h1>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="<?= base_url('laporan/makanan'); ?>" class="form-inline">
                    <div class="form-group mr-2">
                        <label class="mr-2">Filter Kategori:</label>
                        <select name="kategori" class="form-control">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($kategori as $k) : ?>
                                <option value="<?= html_escape($k['kategori']); ?>" <?= ($kategori_filter == $k['kategori']) ? 'selected' : ''; ?>>
                                    <?= html_escape($k['kategori']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary neu-brutalism">Filter</button>
                    <a href="<?= base_url('laporan/makanan'); ?>" class="btn btn-secondary neu-brutalism ml-2">Reset</a>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?= base_url('laporan/export_excel_makanan?' . http_build_query(['kategori' => $kategori_filter])); ?>" class="btn btn-info neu-brutalism">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="<?= base_url('laporan/export_pdf_makanan?' . http_build_query(['kategori' => $kategori_filter])); ?>" class="btn btn-danger neu-brutalism ml-2">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <button onclick="window.print()" class="btn btn-success neu-brutalism ml-2">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <div class="card neu-brutalism-border" id="printArea">
            <div class="card-header">
                <h4>Laporan Menu Makanan - KiiseCoffee</h4>
                <p class="text-muted">Tanggal Cetak: <?= date('d F Y H:i:s'); ?></p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($makanan)) : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($makanan as $m) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= html_escape($m['nama']); ?></td>
                                        <td><?= html_escape($m['kategori']); ?></td>
                                        <td>Rp <?= number_format($m['harga'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge badge-success">Tersedia</span>
                                        </td>
                                        <td><?= html_escape($m['deskripsi']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total Menu:</th>
                                <th colspan="3"><?= count($makanan); ?> Menu</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    @media print {
        @page { size: landscape; }
        body { -webkit-print-color-adjust: exact; visibility: hidden; }
        #printArea, #printArea * { visibility: visible; }
        #printArea { position: absolute; left: 0; top: 0; width: 100%; }
        .section-header, .btn, .form-inline { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        table { font-size: 12px; width: 100% !important; }
    }
</style>