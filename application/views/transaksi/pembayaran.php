<div class="main-content">
    <section class="section">
        <div class="section-header mb-4">
            <h1 style="font-family: 'Archivo Black'; text-transform: uppercase; font-size: 2.5rem; color: #000;"><?= $title; ?></h1>
        </div>

        <?= $this->session->flashdata('message') ?>

        <div class="row">
            <!-- Bagian Scan QRIS -->
            <div class="col-md-7 mb-4">
                <div class="neu-card text-center h-100" style="background: #fff;">
                    <div class="card-header" style="background: #000; color: #fff200;">
                        <h3 style="font-family: 'Archivo Black'; margin:0;">SCAN QRIS</h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="alert font-weight-bold text-left" style="background: #fff200; color: #000; border: 2px solid #000; border-radius: 0;">
                            <i class="fas fa-info-circle mr-2"></i> PENTING: Scan QRIS di bawah pakai GoPay, OVO, Dana, atau Mobile Banking.
                        </div>

                        <div class="d-inline-block p-2 mt-3 mb-4" style="border: 4px solid #000; box-shadow: 8px 8px 0 #000;">
                            <img src="<?= base_url('assets/img/qris/QRIS.jpg'); ?>" 
                                 alt="QRIS" 
                                 class="img-fluid" 
                                 style="max-width: 250px;">
                        </div>

                        <h4 class="font-weight-bold">TOTAL TAGIHAN</h4>
                        <h1 class="display-4 font-weight-bold text-primary" style="font-family: 'Archivo Black';">
                            Rp <?= number_format($transaksi['total_harga'], 0, ',', '.'); ?>
                        </h1>
                        <p class="font-weight-bold mt-2 text-uppercase">Kode: <?= $transaksi['kode_transaksi']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Konfirmasi & Upload -->
            <div class="col-md-5">
                <div class="neu-card h-100" style="background: #f8f9fa;">
                    <div class="card-header bg-white border-bottom-0 pt-4">
                        <h4 style="font-family: 'Archivo Black';">KONFIRMASI</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($transaksi['bukti_bayar']) : ?>
                            <div class="alert alert-success font-weight-bold" style="border: 2px solid #000; border-radius: 0;">
                                <i class="fas fa-check-circle mr-2"></i> BUKTI SUDAH DIUPLOAD
                            </div>
                            <img src="<?= base_url('assets/uploads/payments/') . $transaksi['bukti_bayar']; ?>" 
                                 class="img-fluid mb-3" style="border: 2px solid #000; box-shadow: 4px 4px 0 #000;">
                            
                            <div class="text-center font-weight-bold p-2 mb-3" style="border: 2px solid #000; background: #fff;">
                                STATUS: <?= strtoupper($transaksi['payment_status']); ?>
                            </div>

                            <?php if ($transaksi['payment_status'] == 'paid') : ?>
                                <a href="<?= base_url('transaksi/resi/') . $transaksi['id']; ?>" class="btn neu-btn btn-block" style="background: #000; color: #fff;">
                                    CETAK RESI
                                </a>
                            <?php endif; ?>

                        <?php else : ?>
                            <p class="font-weight-bold">Upload bukti transfer di sini:</p>
                            <?= form_open_multipart('transaksi/upload_bukti'); ?>
                                <input type="hidden" name="transaksi_id" value="<?= $transaksi['id']; ?>">
                                
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="bukti_bayar" name="bukti_bayar" accept="image/*" required>
                                        <label class="custom-file-label neu-input" for="bukti_bayar">Pilih Foto...</label>
                                    </div>
                                    <small class="text-muted font-weight-bold">Format: JPG/PNG (Max 2MB)</small>
                                </div>

                                <button type="submit" class="btn neu-btn btn-block mt-4" style="background: #0056b3; color: #fff;">
                                    <i class="fas fa-upload mr-2"></i> KIRIM BUKTI
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('transaksi/riwayat'); ?>" class="btn neu-btn btn-block mt-3" style="background: #fff;">
                            LIHAT RIWAYAT
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("bukti_bayar").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>