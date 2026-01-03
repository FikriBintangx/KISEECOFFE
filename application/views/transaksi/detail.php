<div class="main-content">
    <section class="section">
        <div class="mb-4 p-4" style="background: #fff200; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <h1 style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; color: #000; margin: 0;">
                <i class="fas fa-file-invoice mr-2"></i> DETAIL PESANAN
            </h1>
        </div>

        <div class="row">
            <div class="col-md-8 mb-4">
                <div style="background: #fff; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                    <div class="p-3" style="background: #000; color: #fff;">
                        <h4 class="m-0 font-weight-bold" style="font-family: 'Archivo Black';">RINCIAN MENU</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead style="background: #eee; border-bottom: 3px solid #000;">
                                <tr>
                                    <th class="py-3 px-4 font-weight-bold border-0">MENU</th>
                                    <th class="py-3 font-weight-bold border-0 text-right">HARGA</th>
                                    <th class="py-3 font-weight-bold border-0 text-center">QTY</th>
                                    <th class="py-3 font-weight-bold border-0 text-right">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item) : ?>
                                    <tr style="border-bottom: 2px solid #000; font-weight: 600;">
                                        <td class="px-4 py-3"><?= $item['nama']; ?></td>
                                        <td class="py-3 text-right">Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                                        <td class="py-3 text-center"><?= $item['jumlah']; ?></td>
                                        <td class="py-3 text-right font-weight-bold">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot style="background: #fff200; border-top: 3px solid #000;">
                                <tr>
                                    <th colspan="3" class="px-4 py-3 text-right font-weight-bold text-uppercase" style="font-size: 1.2rem;">Total Bayar</th>
                                    <th class="py-3 text-right font-weight-bold" style="font-size: 1.2rem;">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
                    <div class="p-3" style="background: #000; color: #fff;">
                        <h4 class="m-0 font-weight-bold" style="font-family: 'Archivo Black';">INFO TRANSAKSI</h4>
                    </div>
                    <div class="p-4">
                        <div class="mb-3">
                            <label class="font-weight-bold d-block text-uppercase border-bottom border-dark">Kode</label>
                            <div class="font-weight-bold" style="font-size: 1.1rem;">#<?= $transaksi['kode_transaksi']; ?></div>
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold d-block text-uppercase border-bottom border-dark">Tanggal</label>
                            <div class="font-weight-bold"><?= date('d M Y H:i', strtotime($transaksi['created_at'])); ?></div>
                        </div>
                        <div class="mb-4">
                            <label class="font-weight-bold d-block text-uppercase border-bottom border-dark mb-2">Status</label>
                            <?php
                            $st = isset($transaksi['payment_status']) ? $transaksi['payment_status'] : 'unpaid';
                            $bg = '#e2e8f0'; $lbl = 'BELUM DIBAYAR';
                            if($st == 'paid') { $bg = '#4ade80'; $lbl = 'LUNAS'; }
                            elseif($st == 'awaiting_verification') { $bg = '#fff200'; $lbl = 'MENUNGGU VERIFIKASI'; }
                            elseif($st == 'failed') { $bg = '#ff4757'; $lbl = 'GAGAL'; }
                            ?>
                            <div class="text-center font-weight-bold p-2 text-uppercase" style="background: <?= $bg; ?>; border: 2px solid #000;">
                                <?= $lbl; ?>
                            </div>
                        </div>

                        <?php if ($transaksi['metode_pembayaran'] == 'transfer' && isset($transaksi['bukti_bayar'])) : ?>
                             <div class="mb-4">
                                <label class="font-weight-bold border-bottom border-dark d-block">BUKTI TRANSFER</label>
                                <img src="<?= base_url('assets/uploads/payments/') . $transaksi['bukti_bayar']; ?>" class="img-fluid mt-2" style="border: 2px solid #000;">
                             </div>
                        <?php endif; ?>

                        <a href="<?= base_url('transaksi/riwayat'); ?>" class="btn btn-block font-weight-bold text-uppercase mb-2" style="background: #fff; border: 3px solid #000; box-shadow: 4px 4px 0 #ccc; color: #000;">
                            KEMBALI
                        </a>
                        <?php if ($st == 'paid') : ?>
                            <a href="<?= base_url('transaksi/resi/') . $transaksi['id']; ?>" class="btn btn-block font-weight-bold text-uppercase" style="background: #000; border: 3px solid #000; box-shadow: 4px 4px 0 #888; color: #fff;">
                                <i class="fas fa-print mr-2"></i> CETAK RESI
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
