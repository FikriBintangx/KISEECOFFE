<div class="main-content">
    <section class="section">
        <!-- HEADER JUDUL -->
        <div class="mb-4 p-4" style="background: #fff200; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <h1 style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; color: #000; margin: 0; letter-spacing: -1px;">
                <i class="fas fa-cash-register mr-2"></i> CHECKOUT PESANAN
            </h1>
        </div>

        <?= $this->session->flashdata('message') ?>
        
        <?php if (validation_errors()): ?>
            <div class="alert font-weight-bold mb-4" style="background: #ff4757; color: #fff; border: 3px solid #000; box-shadow: 4px 4px 0 #000; border-radius: 0;">
                <i class="fas fa-exclamation-triangle mr-2"></i> PERIKSA INPUTAN ANDA!
                <div class="mt-2 text-small">
                    <?= validation_errors(); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Review Pesanan (Kiri) -->
            <div class="col-md-6 mb-4">
                <div style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
                    <div class="p-3" style="border-bottom: 3px solid #000; background: #000; color: #fff;">
                        <h4 class="m-0 font-weight-bold" style="font-family: 'Archivo Black', sans-serif; text-transform: uppercase;">
                            ITEM DIBELI
                        </h4>
                    </div>
                    <div class="p-4">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($items as $item) : ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3" style="border-bottom: 2px dashed #000;">
                                    <div>
                                        <h6 class="font-weight-bold mb-1 text-uppercase" style="font-size: 1.1rem;"><?= $item['nama']; ?></h6>
                                        <?php if (!empty($item['catatan'])): ?>
                                            <div class="text-muted small mb-1" style="font-size: 0.85rem;">
                                                <i class="fas fa-sticky-note mr-1"></i> <?= $item['catatan']; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="font-weight-bold text-muted">
                                            <?= $item['jumlah']; ?> x Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?>
                                        </div>
                                    </div>
                                    <span class="font-weight-bold" style="font-size: 1.1rem;">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 3px solid #000;">
                            <h4 class="font-weight-bold m-0" style="font-family: 'Archivo Black', sans-serif;">TOTAL</h4>
                            <h3 class="font-weight-bold m-0" style="color: #000; background: #fff200; padding: 5px 10px; border: 2px solid #000;">
                                Rp <?= number_format($total, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran (Kanan) -->
            <div class="col-md-6">
                <div style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000; position: sticky; top: 100px;">
                    <div class="p-3" style="border-bottom: 3px solid #000; background: #fff200;">
                        <h4 class="m-0 font-weight-bold" style="font-family: 'Archivo Black', sans-serif; text-transform: uppercase;">
                            <i class="fas fa-wallet mr-2"></i> INFORMASI BAYAR
                        </h4>
                    </div>
                    <div class="p-4">
                        <form action="<?= base_url('transaksi/checkout'); ?>" method="POST" enctype="multipart/form-data">
                            
                            <!-- OPSI JENIS PESANAN -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-3 d-block">Jenis Pesanan</label>
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                    <label class="btn btn-outline-dark font-weight-bold py-3 active" style="border-width: 3px; border-radius: 0;">
                                        <input type="radio" name="jenis_pesanan" id="dine_in" value="Dine In" checked onchange="togglePaymentMethods()"> 
                                        <i class="fas fa-utensils mr-2"></i> MAKAN DI TEMPAT
                                    </label>
                                    <label class="btn btn-outline-dark font-weight-bold py-3" style="border-width: 3px; border-radius: 0;">
                                        <input type="radio" name="jenis_pesanan" id="delivery" value="Delivery" onchange="togglePaymentMethods()"> 
                                        <i class="fas fa-motorcycle mr-2"></i> PENGIRIMAN
                                    </label>
                                </div>
                            </div>

                            <!-- FORM ALAMAT (Hidden by default) -->
                            <div id="address_section" class="form-group mb-4" style="display: none;">
                                <label class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-2 d-block">Alamat Pengiriman</label>
                                <textarea name="alamat" id="alamat" class="form-control font-weight-bold" rows="3" placeholder="Masukkan alamat lengkap pengiriman..." style="border: 3px solid #000; border-radius: 0; box-shadow: 4px 4px 0 #ccc;"></textarea>
                                <?= form_error('alamat', '<small class="text-danger font-weight-bold pl-1">', '</small>') ?>
                            </div>

                            <!-- OPSI METODE PEMBAYARAN -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-2 d-block">Metode Pembayaran</label>
                                <select name="metode_pembayaran" id="metode_pembayaran" class="form-control font-weight-bold" style="height: 50px; border: 3px solid #000; border-radius: 0; box-shadow: 4px 4px 0 #ccc;" required onchange="showQrisPreview()">
                                    <!-- Options diisi via Javascript -->
                                </select>
                            </div>

                            <!-- PREVIEW QRIS & UPLOAD BUKTI -->
                            <div id="qris_preview" class="mb-4" style="display: none;">
                                <div class="p-3 text-center mb-3" style="background: #f8f9fa; border: 3px solid #000;">
                                    <p class="font-weight-bold mb-2">SCAN QRIS DI BAWAH INI:</p>
                                    <img src="<?= base_url('assets/img/qris/QRIS.jpg'); ?>" alt="QRIS Code" class="img-fluid" style="max-width: 200px; border: 2px solid #000;">
                                    <p class="small font-weight-bold mt-2 text-muted">Silakan scan menggunakan E-Wallet Anda.</p>
                                </div>
                                
                                <div class="form-group">
                                    <label class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-2 d-block">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="bukti_bayar" name="bukti_bayar" accept="image/*">
                                        <label class="custom-file-label font-weight-bold" for="bukti_bayar" style="border: 3px solid #000; border-radius: 0;">Pilih File...</label>
                                    </div>
                                    <small class="text-muted font-weight-bold">Format: JPG, PNG, JPEG. Max 2MB.</small>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-uppercase border-bottom border-dark pb-1 mb-2 d-block">Catatan (Opsional)</label>
                                <textarea name="catatan" class="form-control font-weight-bold" rows="3" placeholder="Contoh: Jangan terlalu pedas..." style="border: 3px solid #000; border-radius: 0; box-shadow: 4px 4px 0 #ccc;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-block py-3 font-weight-bold text-uppercase" 
                                style="background: #000; color: #fff; border: 3px solid #000; box-shadow: 4px 4px 0 #888; font-size: 1.2rem; transition: all 0.2s;"
                                onmouseover="this.style.transform='translate(-2px,-2px)'; this.style.boxShadow='6px 6px 0 #888';"
                                onmouseout="this.style.transform='translate(0,0)'; this.style.boxShadow='4px 4px 0 #888';">
                                <i class="fas fa-check-circle mr-2"></i> BUAT PESANAN
                            </button>
                            
                            <a href="<?= base_url('transaksi/keranjang'); ?>" class="btn btn-block py-3 font-weight-bold mt-3 text-uppercase" 
                               style="background: #fff; color: #000; border: 3px solid #000; box-shadow: 4px 4px 0 #ccc;">
                                KEMBALI
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        togglePaymentMethods(); // Jalankan saat load awal
    });

    function togglePaymentMethods() {
        const isDineIn = document.getElementById('dine_in').checked;
        const select = document.getElementById('metode_pembayaran');
        const addressSection = document.getElementById('address_section');
        
        // Reset options
        select.innerHTML = '';

        if (isDineIn) {
            // Jika Makan di Tempat -> HANYA QRIS, Hide Address
            let option = document.createElement("option");
            option.text = "QRIS (SCAN)";
            option.value = "QRIS";
            select.add(option);
            
            addressSection.style.display = 'none';
        } else {
            // Jika Pengiriman -> QRIS & TUNAI, Show Address
            let opt1 = document.createElement("option");
            opt1.text = "QRIS (SCAN)";
            opt1.value = "QRIS";
            select.add(opt1);

            let opt2 = document.createElement("option");
            opt2.text = "TUNAI (BAYAR DI TEMPAT)";
            opt2.value = "Tunai";
            select.add(opt2);
            
            addressSection.style.display = 'block';
        }
        
        // Refresh preview
        showQrisPreview();
    }

    function showQrisPreview() {
        const select = document.getElementById('metode_pembayaran');
        const qrisPreview = document.getElementById('qris_preview');
        const buktiBayar = document.getElementById('bukti_bayar');
        
        if (select.value === 'QRIS') {
            qrisPreview.style.display = 'block';
            buktiBayar.required = true;
        } else {
            qrisPreview.style.display = 'none';
            buktiBayar.required = false;
        }
    }

    // Custom File Input Label
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("bukti_bayar").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
