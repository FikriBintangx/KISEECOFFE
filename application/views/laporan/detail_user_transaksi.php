<div class="main-content">
    <section class="section">
        <div class="mb-4 p-4" style="background: #000;">
            <h1 class="text-white" style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase;">RIWAYAT TRANSAKSI: <?= strtoupper($target_user['nama']); ?></h1>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="mb-4">
                    <a href="<?= base_url('laporan/penjualan'); ?>" class="btn btn-warning font-weight-bold mr-2" style="border: 2px solid #000; box-shadow: 4px 4px 0 #000; color: #000;">
                        <i class="fas fa-arrow-left"></i> KEMBALI
                    </a>
                    
                    <?php
                        // Build query parameters for export links to maintain filters
                        $params = [
                            'filter_type' => $filter_type,
                            'date_start' => $date_start,
                            'date_end' => $date_end,
                            'month' => $month
                        ];
                        $query_string = http_build_query($params);
                    ?>
                    
                    <a href="<?= base_url('laporan/export_excel_detail_user/' . $target_user['id'] . '?' . $query_string); ?>" class="btn btn-success font-weight-bold mr-2" style="border: 2px solid #000; box-shadow: 4px 4px 0 #000;">
                        <i class="fas fa-file-excel"></i> EXPORT EXCEL
                    </a>
                     <a href="<?= base_url('laporan/export_pdf_detail_user/' . $target_user['id'] . '?' . $query_string); ?>" class="btn btn-danger font-weight-bold" style="border: 2px solid #000; box-shadow: 4px 4px 0 #000;">
                        <i class="fas fa-file-pdf"></i> EXPORT PDF
                    </a>
                </div>

                <!-- FILTER CARD -->
                <div class="card mb-4" style="border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                    <div class="card-body" style="background: #e5e5e5;">
                        <h5 class="font-weight-bold mb-3"><i class="fas fa-filter mr-2"></i> FILTER RIWAYAT</h5>
                        <form method="GET" action="">
                            <div class="row align-items-end">
                                <div class="col-md-3 mb-2">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">Tipe Filter</label>
                                        <select name="filter_type" id="filter_type" class="form-control" style="border: 2px solid #000; border-radius: 0;">
                                            <option value="all" <?= isset($filter_type) && $filter_type == 'all' ? 'selected' : '' ?>>Semua</option>
                                            <option value="daily" <?= isset($filter_type) && $filter_type == 'daily' ? 'selected' : '' ?>>Harian (Tanggal)</option>
                                            <option value="monthly" <?= isset($filter_type) && $filter_type == 'monthly' ? 'selected' : '' ?>>Bulanan</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Daily Inputs -->
                                <div class="col-md-3 mb-2 filter-input daily-input" style="display: none;">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">Dari Tanggal</label>
                                        <input type="date" name="date_start" class="form-control" value="<?= isset($date_start) ? $date_start : '' ?>" style="border: 2px solid #000; border-radius: 0;">
                                    </div>
                                </div>
                                 <div class="col-md-3 mb-2 filter-input daily-input" style="display: none;">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">Sampai Tanggal</label>
                                        <input type="date" name="date_end" class="form-control" value="<?= isset($date_end) ? $date_end : '' ?>" style="border: 2px solid #000; border-radius: 0;">
                                    </div>
                                </div>

                                <!-- Monthly Input -->
                                 <div class="col-md-4 mb-2 filter-input monthly-input" style="display: none;">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">Pilih Bulan</label>
                                        <input type="month" name="month" class="form-control" value="<?= isset($month) ? $month : '' ?>" style="border: 2px solid #000; border-radius: 0;">
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <button type="submit" class="btn btn-primary btn-block font-weight-bold" style="border: 2px solid #000; box-shadow: 4px 4px 0 #000; height: 42px; border-radius: 0;">
                                        FILTER
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card" style="border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                    <div class="card-body">
                         <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="tableRiwayatUser">
                                <thead style="background: #eee;">
                                    <tr>
                                        <th class="py-3 px-4 font-weight-bold" style="border-bottom: 2px solid #000;">TANGGAL</th>
                                        <th class="py-3 font-weight-bold" style="border-bottom: 2px solid #000;">KODE TRANSAKSI</th>
                                        <th class="py-3 font-weight-bold" style="border-bottom: 2px solid #000;">TOTAL</th>
                                        <th class="py-3 font-weight-bold" style="border-bottom: 2px solid #000;">STATUS</th>
                                        <th class="py-3 font-weight-bold" style="border-bottom: 2px solid #000;">METODE</th>
                                        <th class="py-3 font-weight-bold" style="border-bottom: 2px solid #000;">RESI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($riwayat)): ?>
                                        <tr><td colspan="6" class="text-center font-weight-bold p-4">BELUM ADA RIWAYAT TRANSAKSI</td></tr>
                                    <?php else: ?>
                                        <?php foreach($riwayat as $r): ?>
                                            <tr>
                                                <td class="px-4"><?= date('d F Y H:i', strtotime($r['created_at'])); ?></td>
                                                <td class="font-weight-bold">#<?= $r['id']; ?></td>
                                                <td>Rp <?= number_format($r['total_harga'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <?php 
                                                    $badge = 'secondary';
                                                    if($r['status'] == 'selesai') $badge = 'success';
                                                    else if($r['status'] == 'diproses') $badge = 'warning';
                                                    else if($r['status'] == 'dibatalkan') $badge = 'danger';
                                                    ?>
                                                    <span class="badge badge-<?= $badge; ?>" style="border: 1px solid #000;"><?= strtoupper($r['status']); ?></span>
                                                </td>
                                                <td><?= $r['metode_pembayaran'] ? strtoupper($r['metode_pembayaran']) : '-'; ?></td>
                                                <td>
                                                    <?php 
                                                    // Logic Show Resi
                                                    $show_resi = false;
                                                    $metode = strtolower($r['metode_pembayaran']);
                                                    if ($r['status'] == 'selesai') {
                                                        $show_resi = true;
                                                    } elseif ($metode != 'qris' && $metode != 'transfer') {
                                                        $show_resi = true;
                                                    }
                                                    ?>

                                                    <?php if($show_resi): ?>
                                                        <a href="<?= base_url('transaksi/resi/' . $r['id']); ?>" target="_blank" class="btn btn-info btn-sm font-weight-bold" style="background: #17a2b8; color: #fff; border: 2px solid #000;">
                                                            <i class="fas fa-print"></i> LIHAT RESI
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Belum Tersedia</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterType = document.getElementById('filter_type');
        const dailyInputs = document.querySelectorAll('.daily-input');
        const monthlyInputs = document.querySelectorAll('.monthly-input');

        function toggleInputs() {
            dailyInputs.forEach(el => el.style.display = 'none');
            monthlyInputs.forEach(el => el.style.display = 'none');

            if(filterType.value === 'daily') {
                dailyInputs.forEach(el => el.style.display = 'block');
            } else if(filterType.value === 'monthly') {
                monthlyInputs.forEach(el => el.style.display = 'block');
            }
        }

        filterType.addEventListener('change', toggleInputs);
        toggleInputs(); // Run on load
    });
</script>

<style>
    /* FORCE BLACK STYLING FOR INPUTS ON FOCUS */
    .form-control:focus {
        border-color: #000 !important;
        box-shadow: 0 0 0 0.2rem rgba(0,0,0,0.25) !important; /* Optional: adds a slight black glow or just remove it */
        box-shadow: 4px 4px 0 #000 !important; /* Brutalist shadow on focus */
        outline: none !important;
    }
    
    /* Specific overrides for date/month inputs if needed by browser defaults */
    input[type="date"]:focus,
    input[type="month"]:focus,
    select:focus {
        border-color: #000 !important;
        outline: 2px solid #000 !important; /* Valid black outline */
        outline-offset: -2px; /* Pulls outline inside */
    }
</style>
