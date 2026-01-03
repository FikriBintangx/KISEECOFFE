<div class="main-content">
    <section class="section">
        <div class="mb-4 p-4" style="background: #fff200; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <h1 style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; color: #000; margin: 0;">
                KELOLA TRANSAKSI
            </h1>
        </div>

        <?= $this->session->flashdata('message') ?>

        <div class="p-4 mb-4" style="background: #fff; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <form method="GET" action="<?= base_url('transaksi/kelola'); ?>" class="form-row align-items-end">
                <div class="col-md-3">
                    <label class="font-weight-bold">Status</label>
                    <select name="status" class="form-control font-weight-bold" style="border: 2px solid #000; border-radius: 0;">
                        <option value="">Semua Status</option>
                        <option value="pending" <?= ($status_filter == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="diproses" <?= ($status_filter == 'diproses') ? 'selected' : ''; ?>>Diproses</option>
                        <option value="selesai" <?= ($status_filter == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold">Mulai</label>
                    <input type="date" name="tanggal_awal" class="form-control font-weight-bold" style="border: 2px solid #000; border-radius: 0;" value="<?= $tanggal_awal; ?>">
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold">Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control font-weight-bold" style="border: 2px solid #000; border-radius: 0;" value="<?= $tanggal_akhir; ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-block font-weight-bold" style="background: #000; color: #fff; border: 2px solid #000; border-radius: 0;">FILTER</button>
                </div>
            </form>
        </div>

        <div style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
            <div class="table-responsive">
                <table class="table m-0 table-hover" id="myTable">
                    <thead style="background: #000; color: #fff;">
                        <tr>
                            <th class="py-3 px-4 border-0">NO</th>
                            <th class="py-3 border-0">KODE</th>
                            <th class="py-3 border-0">USER</th>
                            <th class="py-3 border-0">TANGGAL</th>
                            <th class="py-3 border-0">TOTAL</th>
                            <th class="py-3 border-0">STATUS</th>
                            <th class="py-3 border-0">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($transaksi as $t) : ?>
                            <tr style="border-bottom: 2px solid #000; font-weight: 600;">
                                <td class="px-4 py-3"><?= $no++; ?></td>
                                <td class="py-3 font-weight-bold"><?= $t['kode_transaksi']; ?></td>
                                <td class="py-3"><?= $t['nama_user']; ?><br><small class="text-muted"><?= $t['email']; ?></small></td>
                                <td class="py-3"><?= date('d/m/y H:i', strtotime($t['created_at'])); ?></td>
                                <td class="py-3 font-weight-bold">Rp <?= number_format($t['total_harga'], 0, ',', '.'); ?></td>
                                <td class="py-3">
                                    <span class="badge" style="border: 2px solid #000; background: #fff; color: #000; border-radius: 0; font-size: 0.8rem;">
                                        <?= strtoupper($t['status']); ?>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <a href="<?= base_url('transaksi/detail_admin/') . $t['id']; ?>" class="btn btn-sm font-weight-bold" style="background: #fff200; border: 2px solid #000; color: #000;">DETAIL</a>
                                    <button class="btn btn-sm font-weight-bold" style="background: #fff; border: 2px solid #000; color: #000;" data-toggle="modal" data-target="#modalStatus<?= $t['id']; ?>">UBAH</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Update -->
<?php foreach ($transaksi as $t) : ?>
<div class="modal fade" id="modalStatus<?= $t['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: 3px solid #000; border-radius: 0; box-shadow: 10px 10px 0 #000;">
            <div class="modal-header" style="border-bottom: 3px solid #000; background: #fff200;">
                <h5 class="modal-title font-weight-bold text-uppercase">UPDATE STATUS</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('transaksi/update_status'); ?>" method="POST">
                <input type="hidden" name="transaksi_id" value="<?= $t['id']; ?>">
                <div class="modal-body font-weight-bold">
                    <div class="form-group">
                        <label>STATUS PESANAN</label>
                        <select name="status" class="form-control" style="border: 2px solid #000; border-radius: 0;">
                            <option value="pending" <?= ($t['status'] == 'pending') ? 'selected' : ''; ?>>PENDING</option>
                            <option value="diproses" <?= ($t['status'] == 'diproses') ? 'selected' : ''; ?>>DIPROSES</option>
                            <option value="selesai" <?= ($t['status'] == 'selesai') ? 'selected' : ''; ?>>SELESAI</option>
                            <option value="dibatalkan" <?= ($t['status'] == 'dibatalkan') ? 'selected' : ''; ?>>DIBATALKAN</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>STATUS PEMBAYARAN</label>
                        <select name="payment_status" class="form-control" style="border: 2px solid #000; border-radius: 0;">
                            <option value="unpaid" <?= ($t['payment_status'] == 'unpaid') ? 'selected' : ''; ?>>BELUM LUNAS</option>
                            <option value="paid" <?= ($t['payment_status'] == 'paid') ? 'selected' : ''; ?>>LUNAS</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 3px solid #000;">
                    <button type="submit" class="btn btn-block font-weight-bold" style="background: #000; color: #fff; border: 2px solid #000;">SIMPAN PERUBAHAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>