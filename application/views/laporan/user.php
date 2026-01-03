<div class="main-content">
    <section class="section">
        <div class="section-header neu-brutalism-border">
            <h1><?= $title; ?></h1>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="<?= base_url('laporan/user'); ?>" class="form-inline">
                    <div class="form-group mr-2">
                        <label class="mr-2">Filter Role:</label>
                        <select name="role_id" class="form-control">
                            <option value="">Semua Role</option>
                            <?php foreach ($roles as $r) : ?>
                                <option value="<?= $r['id']; ?>" <?= ($role_filter == $r['id']) ? 'selected' : ''; ?>>
                                    <?= html_escape($r['role']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary neu-brutalism">Filter</button>
                    <a href="<?= base_url('laporan/user'); ?>" class="btn btn-secondary neu-brutalism ml-2">Reset</a>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?= base_url('laporan/export_excel_user?' . http_build_query(['role_id' => $role_filter])); ?>" class="btn btn-info neu-brutalism">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="<?= base_url('laporan/export_pdf_user?' . http_build_query(['role_id' => $role_filter])); ?>" class="btn btn-danger neu-brutalism ml-2">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <button onclick="window.print()" class="btn btn-success neu-brutalism ml-2">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <div class="card neu-brutalism-border" id="printArea">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4>Laporan Data User - KiiseCoffee</h4>
                    <p class="text-muted mb-0">Tanggal Cetak: <?= date('d F Y H:i:s'); ?></p>
                </div>
                <div class="d-print-none">
                    <input type="text" id="searchUser" class="form-control" placeholder="Cari User...">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="userTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)) : ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($users as $u) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= html_escape($u['nama']); ?></td>
                                        <td><?= html_escape($u['email']); ?></td>
                                        <td><?= html_escape($u['role']); ?></td>
                                        <td><?= date('d F Y', $u['date_created']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total User:</th>
                                <th><?= count($users); ?> User</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Fitur Search Sederhana
    document.getElementById('searchUser').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let rows = document.querySelectorAll('#userTable tbody tr');
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>

<style>
    @media print {
        @page { size: landscape; }
        body { -webkit-print-color-adjust: exact; visibility: hidden; }
        #printArea, #printArea * { visibility: visible; }
        #printArea { position: absolute; left: 0; top: 0; width: 100%; }
        .section-header, .btn, .form-inline, #searchUser { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        table { font-size: 12px; width: 100% !important; }
    }
</style>