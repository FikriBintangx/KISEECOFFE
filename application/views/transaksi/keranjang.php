<div class="main-content">
    <section class="section">
        <!-- HEADER JUDUL (FIXED: Kontras tulisan hitam di background kuning) -->
        <div class="mb-4 p-4" style="background: #fff200; border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
            <h1 style="font-family: 'Archivo Black', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; color: #000 !important; margin: 0; letter-spacing: -1px;">
                <i class="fas fa-shopping-basket mr-2"></i> KERANJANG BELANJA
            </h1>
        </div>

        <?= $this->session->flashdata('message') ?>

        <?php if (empty($items)) : ?>
            <!-- Tampilan Kosong -->
            <div class="p-5 text-center" style="background: #fff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
                <i class="fas fa-cookie-bite fa-5x mb-3" style="color: #000;"></i>
                <h2 style="font-family: 'Archivo Black', sans-serif; text-transform: uppercase; color: #000;">KERANJANG KOSONG</h2>
                <p class="font-weight-bold mb-4 text-dark">Perut lapar? Jangan dibiarkan kosong!</p>
                <a href="<?= base_url('home'); ?>" class="btn btn-lg" style="background: #000; color: #fff; border: 3px solid #000; font-weight: 900; box-shadow: 4px 4px 0 #888;">
                    <i class="fas fa-arrow-left mr-2"></i> MULAI JAJAN
                </a>
            </div>
        <?php else : ?>
            <div class="row">
                <!-- KOLOM KIRI: TABEL ITEM -->
                <div class="col-md-8 mb-4">
                    <div style="background: #fff; border: 3px solid #000; box-shadow: 6px 6px 0 #000; overflow: hidden;">
                        <!-- Header Card -->
                        <div class="p-3" style="border-bottom: 3px solid #000; background: #000; color: #fff;">
                            <h5 class="m-0 font-weight-bold" style="font-family: 'Archivo Black', sans-serif; text-transform: uppercase; letter-spacing: 1px;">
                                <i class="fas fa-list mr-2"></i> DAFTAR MENU
                            </h5>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table m-0">
                                <!-- Pakai !important agar warna tetap header terang teks gelap walau dark mode aktif -->
                                <thead style="background: #f8f9fa !important; color: #000 !important; border-bottom: 3px solid #000;">
                                    <tr>
                                        <th class="py-3 px-4 text-uppercase font-weight-bold" style="border: none; color: #000 !important;">MENU</th>
                                        <th class="py-3 text-uppercase font-weight-bold" style="border: none; color: #000 !important;">HARGA</th>
                                        <th class="py-3 text-uppercase font-weight-bold text-center" style="border: none; color: #000 !important;">QTY</th>
                                        <th class="py-3 text-uppercase font-weight-bold" style="border: none; color: #000 !important;">SUBTOTAL</th>
                                        <th class="py-3 text-uppercase font-weight-bold text-center" style="border: none; color: #000 !important;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item) : ?>
                                        <!-- Hapus color: #000 permanen agar di darkmode teks bisa jadi putih -->
                                        <tr style="border-bottom: 2px solid #000; font-weight: 600;">
                                            <!-- Gambar & Nama -->
                                            <td class="p-3 align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 70px; height: 70px; border: 3px solid #000; margin-right: 15px; overflow: hidden; background: #fff;">
                                                        <img src="<?= base_url('assets/img/makanan/') . $item['gambar']; ?>" 
                                                             alt="<?= $item['nama']; ?>" 
                                                             style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                    <!-- Hapus color #000 supaya text ngikutin tema (Putih saat dark, Hitam saat light) -->
                                                    <div class="d-flex flex-column">
                                                        <span class="font-weight-bold text-uppercase" style="font-size: 1rem;">
                                                            <?= $item['nama']; ?>
                                                        </span>
                                                        <?php if (!empty($item['catatan'])): ?>
                                                            <div class="small mt-1 opacity-75">
                                                                <i class="fas fa-sticky-note mr-1"></i> <?= $item['catatan']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="align-middle">
                                                Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?>
                                            </td>
                                            
                                            <!-- Qty Control -->
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-center">
                                                    <button class="btn btn-sm font-weight-bold" 
                                                            style="background: #fff; color: #000 !important; border: 2px solid #000; box-shadow: 2px 2px 0 #000; width: 30px;" 
                                                            onclick="updateQty(<?= $item['id']; ?>, <?= $item['jumlah'] - 1; ?>)">-</button>
                                                    
                                                    <input type="text" class="form-control form-control-sm text-center font-weight-bold mx-2" 
                                                           style="width: 45px; border: 2px solid #000; border-radius: 0; background: #fff; color: #000 !important; font-size: 1rem;" 
                                                           value="<?= $item['jumlah']; ?>" readonly>
                                                    
                                                    <button class="btn btn-sm font-weight-bold" 
                                                            style="background: #fff200; color: #000 !important; border: 2px solid #000; box-shadow: 2px 2px 0 #000; width: 30px;" 
                                                            onclick="updateQty(<?= $item['id']; ?>, <?= $item['jumlah'] + 1; ?>)">+</button>
                                                </div>
                                            </td>
                                            
                                            <td class="align-middle font-weight-bold">
                                                Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?>
                                            </td>
                                            
                                            <!-- Hapus -->
                                            <td class="align-middle text-center">
                                                <a href="<?= base_url('transaksi/hapus_item/') . $item['id']; ?>" 
                                                   class="btn btn-danger btn-sm rounded-0"
                                                   style="border: 2px solid #000; box-shadow: 2px 2px 0 #000; font-weight: bold;"
                                                   onclick="return confirm('Yakin hapus menu ini?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: RINGKASAN (KUNING) -->
                <div class="col-md-4">
                    <div class="p-4" style="background: #fff200; border: 3px solid #000; box-shadow: 8px 8px 0 #000; position: sticky; top: 100px;">
                        <h4 class="mb-4 font-weight-bold text-uppercase" style="font-family: 'Archivo Black', sans-serif; color: #000 !important; border-bottom: 3px solid #000; padding-bottom: 15px;">
                            RINGKASAN
                        </h4>
                        
                        <div class="d-flex justify-content-between mb-3 font-weight-bold" style="font-size: 1.1rem;">
                            <span style="color: #000 !important;">TOTAL ITEM</span>
                            <span style="color: #000 !important;"><?= count($items); ?> Menu</span>
                        </div>
                        
                        <!-- Box Total: Putih dengan text hitam agar kontras aman -->
                        <div class="d-flex justify-content-between align-items-center mb-4 p-3" style="background: #fff !important; color: #000 !important; border: 3px solid #000; box-shadow: 4px 4px 0 rgba(0,0,0,0.1);">
                            <span class="h6 font-weight-bold m-0 text-uppercase" style="color: #000 !important;">TOTAL BAYAR</span>
                            <span class="h3 font-weight-bold m-0 text-danger">Rp <?= number_format($total, 0, ',', '.'); ?></span>
                        </div>

                        <!-- Tombol Checkout -->
                        <a href="<?= base_url('transaksi/checkout'); ?>" class="btn btn-block py-3 font-weight-bold mb-3 text-uppercase" 
                           style="background: #fff; color: #000 !important; border: 3px solid #000; box-shadow: 4px 4px 0 #000; font-size: 1.2rem; transition: all 0.2s;"
                           onmouseover="this.style.transform='translate(-2px,-2px)'; this.style.boxShadow='6px 6px 0 #000';"
                           onmouseout="this.style.transform='translate(0,0)'; this.style.boxShadow='4px 4px 0 #000';">
                            CHECKOUT SEKARANG <i class="fas fa-arrow-right ml-2 opacity-75" style="color: #000 !important;"></i>
                        </a>

                        <!-- Tombol Tambah Menu -->
                        <a href="<?= base_url('home'); ?>" class="btn btn-block py-3 font-weight-bold text-uppercase" 
                           style="background: transparent; border: 3px solid #000; color: #000 !important;">
                            TAMBAH MENU LAIN
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<script>
function updateQty(detailId, qty) {
    if (qty < 1) return; // Mencegah qty 0
    
    // Visual feedback
    document.body.style.cursor = 'wait';
    
    fetch('<?= base_url("transaksi/update_item"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'detail_id=' + detailId + '&jumlah=' + qty
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('Gagal update keranjang');
            document.body.style.cursor = 'default';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.body.style.cursor = 'default';
    });
}
</script>
