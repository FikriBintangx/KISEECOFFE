<div class="main-content">
    <section class="section">
        <div class="mb-4 p-4 d-flex justify-content-between align-items-center flex-wrap" style="background: #000;">
            <h1 class="text-white mb-2 mb-md-0" style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase;">LAPORAN PENJUALAN</h1>
            <div>
                 <a href="<?= base_url('laporan/export_excel_penjualan'); ?>" class="btn btn-success font-weight-bold mr-2" style="border: 2px solid #fff; box-shadow: 4px 4px 0 #fff; color: #fff; background: transparent;">
                    <i class="fas fa-file-excel"></i> EXCEL (ALL)
                </a>
                 <a href="<?= base_url('laporan/export_pdf_penjualan'); ?>" class="btn btn-danger font-weight-bold" style="border: 2px solid #fff; box-shadow: 4px 4px 0 #fff; color: #fff; background: transparent;">
                    <i class="fas fa-file-pdf"></i> PDF (ALL)
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card" style="border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                    <div class="card-body">
                         <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="tableUserGroup">
                                <thead style="background: #eee;">
                                    <tr>
                                        <th class="py-3 px-4 font-weight-bold" style="border-bottom: 2px solid #000;">PELANGGAN</th>
                                        <th class="py-3 font-weight-bold" style="border-bottom: 2px solid #000;">EMAIL</th>
                                        <th class="py-3 font-weight-bold" style="border-bottom: 2px solid #000;">TOTAL TRANSAKSI</th>
                                        <th class="py-3 font-weight-bold" style="border-bottom: 2px solid #000;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Grouping Logic (Manual for now, better in Controller but view logic is requested)
                                    $user_groups = [];
                                    if(isset($penjualan) && !empty($penjualan)) {
                                        foreach($penjualan as $p) {
                                            $uid = $p['user_id'];
                                            if(!isset($user_groups[$uid])) {
                                                $user_groups[$uid] = [
                                                    'nama' => $p['nama_user'],
                                                    'email' => $p['email'],
                                                    'count' => 0,
                                                    'user_id' => $uid
                                                ];
                                            }
                                            $user_groups[$uid]['count']++;
                                        }
                                    }
                                    ?>

                                    <?php if(empty($user_groups)): ?>
                                        <tr><td colspan="4" class="text-center font-weight-bold p-4">BELUM ADA DATA PENJUALAN</td></tr>
                                    <?php else: ?>
                                        <?php foreach($user_groups as $u): ?>
                                            <tr>
                                                <td class="px-4 font-weight-bold"><?= $u['nama']; ?></td>
                                                <td><?= $u['email']; ?></td>
                                                <td class="font-weight-bold"><?= $u['count']; ?> Transaksi</td>
                                                <td>
                                                    <a href="<?= base_url('laporan/detail_user_transaksi/' . $u['user_id']); ?>" class="btn btn-primary font-weight-bold" style="border: 2px solid #000; box-shadow: 3px 3px 0 #000;">
                                                        <i class="fas fa-eye"></i> LIHAT RIWAYAT
                                                    </a>
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
