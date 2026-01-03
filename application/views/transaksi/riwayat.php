<div class="main-content">
    <section class="section">
        <!-- HEADER JUDUL -->
        <div class="mb-4 p-4" style="background: #fff200; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <h1 style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; color: #000; margin: 0; letter-spacing: -1px;">
                <i class="fas fa-history mr-2"></i> RIWAYAT TRANSAKSI
            </h1>
        </div>

        <?= $this->session->flashdata('message') ?>

        <?php if (empty($transaksi)) : ?>
            <div class="p-5 text-center" style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
                <i class="fas fa-ghost fa-5x mb-3" style="color: #000;"></i>
                <h2 style="font-family: 'Archivo Black', sans-serif; text-transform: uppercase;">BELUM ADA RIWAYAT</h2>
                <p class="font-weight-bold mb-4">Kamu belum pernah jajan di sini nih.</p>
                <a href="<?= base_url('home'); ?>" class="btn btn-lg font-weight-bold" style="background: #000; color: #fff; border: 3px solid #000; box-shadow: 4px 4px 0 #888;">
                    MULAI BELANJA
                </a>
            </div>
        <?php else : ?>
            <div style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
                <div class="table-responsive">
                    <table class="table m-0 table-hover" id="myTableRiwayat">
                        <thead style="background: #000; color: #fff;">
                            <tr>
                                <th class="py-3 px-4 border-0">NO</th>
                                <th class="py-3 border-0">KODE</th>
                                <th class="py-3 border-0">TANGGAL</th>
                                <th class="py-3 border-0">TOTAL</th>
                                <th class="py-3 border-0">METODE</th>
                                <th class="py-3 border-0 text-center">STATUS</th>
                                <th class="py-3 border-0 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($transaksi as $t) : ?>
                                <tr style="border-bottom: 3px solid #000; font-weight: 600;">
                                    <td class="px-4 py-3 align-middle"><?= $no++; ?></td>
                                    <td class="py-3 align-middle font-weight-bold"><?= $t['kode_transaksi']; ?></td>
                                    <td class="py-3 align-middle"><?= date('d M Y H:i', strtotime($t['created_at'])); ?></td>
                                    <td class="py-3 align-middle font-weight-bold">Rp <?= number_format($t['total_harga'], 0, ',', '.'); ?></td>
                                    <td class="py-3 align-middle text-uppercase"><?= isset($t['metode_pembayaran']) ? $t['metode_pembayaran'] : '-'; ?></td>
                                    <td class="py-3 align-middle text-center">
                                        <?php
                                        $st = isset($t['payment_status']) ? $t['payment_status'] : 'unpaid';
                                        $bg = '#e2e8f0'; $lbl = 'BELUM BAYAR';
                                        if($st == 'paid') { $bg = '#4ade80'; $lbl = 'LUNAS'; }
                                        elseif($st == 'awaiting_verification') { $bg = '#fff200'; $lbl = 'VERIFIKASI'; }
                                        elseif($st == 'failed') { $bg = '#ff4757'; $lbl = 'GAGAL'; }
                                        ?>
                                        <span style="background: <?= $bg; ?>; border: 2px solid #000; padding: 4px 8px; font-weight: 900; font-size: 0.8rem; box-shadow: 2px 2px 0 #000;">
                                            <?= $lbl; ?>
                                        </span>
                                    </td>
                                    <td class="py-3 align-middle text-center">
                                        <a href="<?= base_url('transaksi/detail/') . $t['id']; ?>" class="btn btn-sm font-weight-bold" style="background: #fff; border: 2px solid #000; color: #000; box-shadow: 2px 2px 0 #000;">DETAIL</a>
                                        <?php 
                                            $show_resi = ($st == 'paid' || $t['status'] == 'selesai' || (!in_array(strtolower(isset($t['metode_pembayaran']) ? $t['metode_pembayaran'] : ''), ['qris', 'transfer', 'transfer_bank'])));
                                            if ($show_resi) : 
                                        ?>
                                            <a href="<?= base_url('transaksi/resi/') . $t['id']; ?>" class="btn btn-sm font-weight-bold ml-1" style="background: #000; border: 2px solid #000; color: #fff; box-shadow: 2px 2px 0 #888;">RESI</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>
