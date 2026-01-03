<div class="main-content">
    <section class="section">
        <div class="mb-4 p-4" style="background: #fff200; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <h1 style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; color: #000; margin: 0;">
                DETAIL TRANSAKSI (ADMIN)
            </h1>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="mb-4" style="background: #fff; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                    <div class="p-3" style="background: #000; color: #fff;">
                        <h4 class="m-0 font-weight-bold" style="font-family: 'Archivo Black';">ITEM PESANAN</h4>
                    </div>
                    <table class="table m-0">
                        <thead style="background: #eee; border-bottom: 3px solid #000;">
                            <tr>
                                <th class="py-3 px-4 border-0 font-weight-bold">MENU</th>
                                <th class="py-3 border-0 font-weight-bold">HARGA</th>
                                <th class="py-3 border-0 font-weight-bold">QTY</th>
                                <th class="py-3 border-0 font-weight-bold">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detail as $d) : ?>
                                <tr style="border-bottom: 2px solid #000; font-weight: 600;">
                                    <td class="px-4 py-3"><?= $d['nama']; ?></td>
                                    <td class="py-3">Rp <?= number_format($d['harga_satuan'], 0, ',', '.'); ?></td>
                                    <td class="py-3"><?= $d['jumlah']; ?></td>
                                    <td class="py-3">Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-md-4">
                <div style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
                    <div class="p-3" style="background: #000; color: #fff;">
                        <h4 class="m-0 font-weight-bold" style="font-family: 'Archivo Black';">INFO CUSTOMER</h4>
                    </div>
                    <div class="p-4">
                        <p class="mb-2"><strong>NAMA:</strong> <?= $transaksi['nama_user']; ?></p>
                        <p class="mb-2"><strong>EMAIL:</strong> <?= $transaksi['email']; ?></p>
                        <p class="mb-2"><strong>METODE:</strong> <?= strtoupper($transaksi['metode_pembayaran']); ?></p>
                        <p class="mb-4"><strong>CATATAN:</strong> <?= $transaksi['catatan'] ? $transaksi['catatan'] : '-'; ?></p>
                        
                        <?php if($transaksi['bukti_bayar']): ?>
                            <div class="mb-4">
                                <strong class="d-block border-bottom border-dark mb-2">BUKTI BAYAR</strong>
                                <img src="<?= base_url('assets/uploads/payments/') . $transaksi['bukti_bayar']; ?>" class="img-fluid" style="border: 2px solid #000;">
                                <a href="<?= base_url('assets/uploads/payments/') . $transaksi['bukti_bayar']; ?>" target="_blank" class="btn btn-sm btn-dark mt-2 btn-block">Lihat Penuh</a>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <strong class="d-block border-bottom border-dark mb-2">STATUS</strong>
                            <?php if($transaksi['status'] == 'selesai'): ?>
                                <span class="badge badge-success" style="border: 2px solid #000; font-size: 1rem;">SELESAI</span>
                            <?php elseif($transaksi['status'] == 'dibatalkan'): ?>
                                <span class="badge badge-danger" style="border: 2px solid #000; font-size: 1rem;">DIBATALKAN</span>
                            <?php else: ?>
                                <span class="badge badge-warning" style="border: 2px solid #000; font-size: 1rem; color: #000;"><?= strtoupper($transaksi['status']); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if($transaksi['metode_pembayaran'] == 'qris' && $transaksi['status'] != 'selesai'): ?>
                            <form action="<?= base_url('transaksi/verify_payment'); ?>" method="post" class="mb-3">
                                <input type="hidden" name="transaksi_id" value="<?= $transaksi['id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-block font-weight-bold mb-2" style="border: 3px solid #000; box-shadow: 4px 4px 0 #000;">
                                    <i class="fas fa-check"></i> TERIMA PEMBAYARAN
                                </button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-block font-weight-bold" style="border: 3px solid #000; box-shadow: 4px 4px 0 #000;">
                                    <i class="fas fa-times"></i> TOLAK
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if(($transaksi['metode_pembayaran'] != 'qris' && $transaksi['status'] != 'selesai' && $transaksi['status'] != 'dibatalkan')): ?>
                             <form action="<?= base_url('transaksi/update_status'); ?>" method="post" class="mb-3">
                                <input type="hidden" name="transaksi_id" value="<?= $transaksi['id']; ?>">
                                <input type="hidden" name="payment_status" value="paid">
                                <input type="hidden" name="status" value="selesai">
                                <button type="submit" class="btn btn-success btn-block font-weight-bold mb-2" style="border: 3px solid #000; box-shadow: 4px 4px 0 #000;">
                                    <i class="fas fa-check-double"></i> SELESAIKAN ORDER
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php 
                            $show_resi = ($transaksi['payment_status'] == 'paid' || $transaksi['status'] == 'selesai' || (!in_array(strtolower(isset($transaksi['metode_pembayaran']) ? $transaksi['metode_pembayaran'] : ''), ['qris', 'transfer', 'transfer_bank'])));
                            if ($show_resi) : 
                        ?>
                            <a href="<?= base_url('transaksi/resi/') . $transaksi['id']; ?>" target="_blank" class="btn btn-info btn-block font-weight-bold mb-3" style="border: 3px solid #000; box-shadow: 4px 4px 0 #000;">
                                <i class="fas fa-print"></i> LIHAT RESI
                            </a>
                        <?php endif; ?>

                        <a href="<?= base_url('transaksi/kelola'); ?>" class="btn btn-block font-weight-bold" style="background: #fff; color: #000; border: 3px solid #000; box-shadow: 4px 4px 0 #ccc;">KEMBALI KE LIST</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
