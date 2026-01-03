<?php
date_default_timezone_set('Asia/Jakarta');
$jam_sekarang = (int)date('H');
$buka_jam = 8; 
$tutup_jam = 22;
$is_open_time = ($jam_sekarang >= $buka_jam && $jam_sekarang < $tutup_jam);
$shop_status_db = isset($shop_override_status) ? $shop_override_status : 'auto'; // 'auto', 'open', 'closed'

if ($shop_status_db == 'open') {
    $is_open = true;
} elseif ($shop_status_db == 'closed') {
    $is_open = false;
} else {
    $is_open = $is_open_time;
}


// LOGIKA STATUS DAPUR
$status_db = isset($kitchen_status) ? (int)$kitchen_status : 1; 

$dapur_bg = '';
$dapur_text = '';
$dapur_icon = '';
$dapur_desc = '';

if($status_db == 1) {
    $dapur_bg = '#4ade80'; // Hijau
    $dapur_text = 'DAPUR SANTAI';
    $dapur_icon = 'fa-smile';
    $dapur_desc = 'Pesanan cepat (5-10 Menit)';
} elseif($status_db == 2) {
    $dapur_bg = '#facc15'; // Kuning
    $dapur_text = 'DAPUR SIBUK';
    $dapur_icon = 'fa-stopwatch';
    $dapur_desc = 'Mohon bersabar (15-20 Menit)';
} else {
    $dapur_bg = '#ff4757'; // Merah
    $dapur_text = 'DAPUR NGEBUL';
    $dapur_icon = 'fa-fire';
    $dapur_desc = 'Antrian padat (30+ Menit)';
}
?>

<style>
/* UTILITIES NEU BRUTALISM */
.font-black { font-family: 'Archivo Black', sans-serif; }
.neu-border { border: 3px solid #000; }
.neu-shadow { box-shadow: 6px 6px 0 #000; }
.neu-hover:hover { transform: translate(-2px, -2px); box-shadow: 8px 8px 0 #000; }

body.dark-mode .neu-shadow { box-shadow: 6px 6px 0 rgba(255, 255, 255, 0.2); }
body.dark-mode .neu-hover:hover { box-shadow: 8px 8px 0 rgba(255, 255, 255, 0.2); }
body.dark-mode .neu-border { border: 3px solid var(--border-color); }

/* HEADER */
.header-bar {
    border: 3px solid #000;
    background: #fff;
    padding: 15px 20px;
    box-shadow: 8px 8px 0 #000;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* HERO SECTION */
.hero-wrapper {
    position: relative;
    border: 3px solid #000;
    background: #000;
    box-shadow: 8px 8px 0 #000;
    overflow: hidden;
    height: 400px;
    margin-bottom: 30px;
}
.hero-collage-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.8;
    transition: transform 5s ease;
}
.hero-wrapper:hover .hero-collage-img {
    transform: scale(1.1);
}
.hero-overlay-text {
    position: absolute;
    bottom: 30px;
    left: 30px;
    z-index: 10;
    text-shadow: 4px 4px 0 #000;
}
.hero-title {
    font-family: 'Archivo Black', sans-serif;
    font-size: 3.5rem;
    line-height: 0.9;
    color: #fff;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.hero-subtitle {
    display: inline-block;
    background: #000;
    color: #fff;
    padding: 5px 15px;
    font-weight: bold;
    font-size: 1.1rem;
    border: 2px solid #fff;
}

/* FILTER BUTTONS */
.filter-card {
    border: 3px solid #000;
    padding: 12px;
    text-align: center;
    font-weight: 900;
    text-transform: uppercase;
    cursor: pointer;
    box-shadow: 4px 4px 0 #000;
    transition: 0.2s;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
.filter-card:hover, .filter-card.active { transform: translate(-2px, -2px); box-shadow: 6px 6px 0 #000; }
.filter-card.yellow { background: var(--yellow); color: #000; }
.filter-card.white { background: var(--bg-card); color: var(--text-primary); }
.filter-card.blue { background: var(--blue); color: #000; }
.filter-card.red { background: #ff4757; color: #fff; }
.filter-card.dark { background: #000; color: #fff; }

body.dark-mode .filter-card.white { background: var(--bg-card); color: var(--text-primary); border-color: var(--border-color); }
body.dark-mode .filter-card.dark { background: #333; color: #fff; border-color: var(--border-color); }
body.dark-mode .filter-card:hover, body.dark-mode .filter-card.active { box-shadow: 6px 6px 0 rgba(255,255,255,0.2); }

/* MENU CARD */
.menu-card {
    border: 3px solid #000;
    background: var(--bg-card);
    box-shadow: 5px 5px 0 #000;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.2s;
}
.menu-card:hover { transform: translate(-3px, -3px); box-shadow: 8px 8px 0 #000; }

/* DARK MODE OVERRIDES FOR MENU CARD */
body.dark-mode .menu-card {
    border: 3px solid var(--border-color);
    box-shadow: 5px 5px 0 rgba(255,255,255,0.2);
}
body.dark-mode .menu-card:hover {
    box-shadow: 8px 8px 0 rgba(255,255,255,0.2);
}

.menu-img-box {
    position: relative;
    width: 100%;
    height: 220px;
    border-bottom: 3px solid #000;
    overflow: hidden;
}
.menu-img-box img { width: 100%; height: 100%; object-fit: cover; }

/* WISHLIST BUTTON */
.wishlist-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #fff;
    border: 3px solid #000;
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    z-index: 20;
    box-shadow: 3px 3px 0 #000;
    transition: all 0.2s;
    color: #ff4757; /* Ubah default jadi merah */
}
.wishlist-btn:hover { background: #ff4757; color: #fff; }
.wishlist-btn.active { background: #ff4757; color: #fff; }

/* BUTTONS */
.btn-add-yellow {
    background: var(--yellow);
    color: #000;
    font-weight: 900;
    border: 3px solid #000;
    width: 100%;
    padding: 10px;
    text-transform: uppercase;
    box-shadow: 3px 3px 0 #000;
    transition: all 0.2s;
}
.btn-add-yellow:hover {
    background: #000;
    color: var(--yellow);
    transform: translateY(-2px);
    box-shadow: 5px 5px 0 #000;
}

body.dark-mode .btn-add-yellow {
    border-color: var(--border-color);
    box-shadow: 3px 3px 0 rgba(255,255,255,0.2);
}
body.dark-mode .btn-add-yellow:hover {
    background: #333;
    box-shadow: 5px 5px 0 rgba(255,255,255,0.2);
}

/* FLOATING CHECKOUT */
.checkout-bar {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #4ade80; /* Brutalist Green */
    color: #000; /* Black Text for contrast */
    border: 3px solid #000;
    padding: 15px 30px;
    font-weight: 900;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    box-shadow: 6px 6px 0 #000;
    z-index: 9999;
    text-decoration: none !important;
    transition: 0.2s;
}
.checkout-bar:hover { transform: scale(1.05); color: #fff; box-shadow: 8px 8px 0 #000; }

/* KITCHEN STATUS */
.kitchen-status-bar {
    border: 3px solid #000;
    box-shadow: 6px 6px 0 #000;
    padding: 15px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    font-weight: 900;
    color: #000 !important;
    /* Removed Pulse Animation to avoid distraction, kept vibrant color */
}
}
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.01); }
    100% { transform: scale(1); }
}
.search-item:hover {
    background: #fff200 !important;
    transform: translateX(5px);
}
</style>

<div class="main-content pb-5">
    <div class="container">
        
        <!-- SUCCESS/ERROR MESSAGE -->
        <?= $this->session->flashdata('message'); ?>

        <!-- RUNNING TEXT (MARQUEE) -->
        <div class="marquee-container mb-4" style="border: 3px solid #000; background: var(--yellow); overflow: hidden; white-space: nowrap; box-shadow: 4px 4px 0 #000; color: #000 !important;">
            <div class="marquee-content" style="display: inline-block; padding: 10px; font-weight: 900; font-size: 1.2rem; text-transform: uppercase; animation: marquee 15s linear infinite; color: #000 !important;">
                SELAMAT DATANG DI KIISE COFFEE! ☕ NIKMATI KOPI PILIHAN DAN MAKANAN LEZAT 🍔 DISKON SPESIAL UNTUK MEMBER BARU! 🔥 RASA ASLI, GAYA KEREN. 🚀
            </div>
            <div class="marquee-content" style="display: inline-block; padding: 10px; font-weight: 900; font-size: 1.2rem; text-transform: uppercase; animation: marquee 15s linear infinite; color: #000 !important;">
                SELAMAT DATANG DI KIISE COFFEE! ☕ NIKMATI KOPI PILIHAN DAN MAKANAN LEZAT 🍔 DISKON SPESIAL UNTUK MEMBER BARU! 🔥 RASA ASLI, GAYA KEREN. 🚀
            </div>
        </div>
        <style>
            @keyframes marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(-100%); }
            }
        </style>

        <!-- ALERT TOKO TUTUP -->
        <?php if (!$is_open): ?>
        <div class="alert bg-danger text-white mb-4 font-weight-bold text-center text-uppercase" style="border: 3px solid #000; box-shadow: 4px 4px 0 #000;">
            <i class="fas fa-store-slash mr-2"></i> Maaf, Kami Sedang Tutup. Buka Jam 08:00 - 22:00.
        </div>
        <?php endif; ?>

        <!-- HERO SECTION (DINAMIS DARI DB) -->
        <div class="hero-wrapper" style="background: #fff; padding: 0; height: 350px; position: relative; border-bottom: 4px solid #000; overflow: hidden;">
            <?php 
                // Ambil 3 gambar acak
                $hero_images = [];
                if(!empty($menu)) {
                    $keys = array_rand($menu, min(3, count($menu)));
                    if(!is_array($keys)) $keys = [$keys];
                    foreach($keys as $key) {
                        $hero_images[] = base_url('assets/img/makanan/' . $menu[$key]['gambar']);
                    }
                }
                // Fallback jika gambar kurang dari 3: Gunakan yang ada berulang kali
                $count = count($hero_images);
                if ($count > 0 && $count < 3) {
                    // Isi slot kosong dengan mengulang gambar yang ada
                    while(count($hero_images) < 3) {
                        $hero_images[] = $hero_images[0]; 
                    }
                } elseif ($count == 0) {
                   // Jika tidak ada gambar sama sekali (database kosong), pakai dummy local atau warna
                   $hero_images = [
                       base_url('assets/img/makanan/default.jpg'),
                       base_url('assets/img/makanan/default.jpg'),
                       base_url('assets/img/makanan/default.jpg')
                   ];
                }
            ?>
            <div class="row no-gutters h-100">
                <!-- Gambar Besar Kiri -->
                <div class="col-md-8 h-100" style="border-right: 4px solid #000; position: relative; background: #eee;">
                    <?php if(strpos($hero_images[0], 'default.jpg') !== false && !file_exists(FCPATH . 'assets/img/makanan/default.jpg')): ?>
                        <!-- Fallback jika file default.jpg fisik tidak ada -->
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #ddd; color: #555; font-weight: bold;">
                            NO IMAGE
                        </div>
                    <?php else: ?>
                        <img src="<?= $hero_images[0]; ?>" class="w-100 h-100" style="object-fit: cover; filter: grayscale(100%); transition: 0.5s;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(100%)'">
                    <?php endif; ?>
                </div>
                <!-- Dua Gambar Kanan -->
                <div class="col-md-4 h-100">
                    <div class="h-50" style="border-bottom: 4px solid #000; position: relative; background: #ccc;">
                         <img src="<?= $hero_images[1]; ?>" class="w-100 h-100" style="object-fit: cover;" onerror="this.style.display='none'; this.parentElement.style.background='#ccc';">
                    </div>
                    <div class="h-50" style="position: relative; background: #bbb;">
                        <img src="<?= $hero_images[2]; ?>" class="w-100 h-100" style="object-fit: cover;" onerror="this.style.display='none'; this.parentElement.style.background='#bbb';">
                    </div>
                </div>
            </div>
            
            <!-- TEXT OVERLAY - CENTERED STICKER -->
            <div class="hero-text-sticker" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-3deg); background: #fff; border: 4px solid #000; padding: 25px 40px; box-shadow: 12px 12px 0 #000; text-align: center; z-index: 10; min-width: 320px; color: #000 !important;">
                <h1 style="font-family: 'Archivo Black', sans-serif; font-size: 3rem; line-height: 1.2; margin: 0; color: #000 !important; text-transform: uppercase;">
                    RASA ASLI.<br>
                    <span style="color: #fff !important; background: #000; padding: 2px 15px; display: inline-block; transform: rotate(-2deg);"> Kiise Coffee </span>
                </h1>
                <div class="mt-3 font-weight-bold" style="background: var(--yellow); display: inline-block; padding: 8px 20px; border: 3px solid #000; font-size: 1.1rem; transform: rotate(2deg); box-shadow: 4px 4px 0 #000; color: #000 !important;">
                    Kopi, Susu, Matcha. Kiise Keren.
                </div>
            </div>
        </div>

        <!-- STATUS DAPUR -->
        <div class="kitchen-status-bar" style="background: <?= $dapur_bg; ?>;">
            <i class="fas <?= $dapur_icon; ?> fa-2x mr-3"></i>
            <div class="text-left">
                <div style="font-family: 'Archivo Black'; font-size: 1.5rem; line-height: 1;"><?= $dapur_text; ?></div>
                <div style="font-size: 0.9rem; font-weight: 700;"><?= $dapur_desc; ?></div>
            </div>
        </div>

        <!-- SEARCH BAR & FILTER -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="input-group mb-0" style="position: relative; z-index: 1000;">
                    <input type="text" id="searchInput" class="form-control font-weight-bold" placeholder="Cari menu favoritmu..." onkeyup="smartSearch()" autocomplete="off" style="border: 3px solid #000; height: 50px; font-size: 1.1rem; border-radius: 0; box-shadow: 4px 4px 0 #000;">
                    <div class="input-group-append">
                        <span class="input-group-text font-weight-bold" style="background: #000; color: #fff; border: 3px solid #000; border-left: none; border-radius: 0;">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <!-- Dropdown Smart Search -->
                    <div id="search-results" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 3px solid #000; box-shadow: 6px 6px 0 #000; z-index: 9999; max-height: 400px; overflow-y: auto; margin-top: 10px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER BUTTONS -->
        <div class="row mb-5">
            <!-- Add 'all' active class by default to show it's selected -->
            <div class="col-6 col-md-3 mb-3 px-2">
                <div class="filter-card white active" onclick="filterMenu('all', this)" id="btn-all">SEMUA MENU</div>
            </div>
            <div class="col-6 col-md-3 mb-3 px-2">
                <div class="filter-card yellow" onclick="filterMenu('makanan', this)">LAPAR BERAT</div>
            </div>
            <div class="col-6 col-md-3 mb-3 px-2">
                <div class="filter-card white" onclick="filterMenu('kopi', this)">BUTUH KOPI</div>
            </div>
            <div class="col-6 col-md-3 mb-3 px-2">
                <div class="filter-card blue" onclick="filterMenu('non-kopi', this)">SANTAI SORE</div>
            </div>
            <!-- Extra Filter Row -->
            <div class="col-6 px-2 mt-2">
                <!-- Button Favorit jadi Putih dgn Ikon Merah -->
                <div class="filter-card white" id="btn-fav" onclick="showFavorites()" style="color: #000;">
                    <i class="fas fa-heart mr-2 text-danger"></i> FAVORITKU
                </div>
            </div>
            <div class="col-6 px-2 mt-2">
                <a href="<?= base_url('transaksi/riwayat'); ?>" class="text-decoration-none">
                    <div class="filter-card dark">
                        <i class="fas fa-receipt mr-2"></i> RIWAYAT
                    </div>
                </a>
            </div>
        </div>

        <!-- DAFTAR MENU -->
        <div class="row" id="menuContainer">
            <?php if (!empty($menu)): ?>
                <?php foreach ($menu as $m): ?>
                    <?php 
                        $stok = isset($m['stok']) ? $m['stok'] : 0;
                        $kategori = strtolower(isset($m['kategori']) ? $m['kategori'] : 'lainnya');
                        $nama_lower = strtolower($m['nama']);
                        
                        // LOGIKA KATEGORI LEBIH PINTAR & DINAMIS
                        $mood = 'non-kopi'; // Default

                        if ($kategori == 'makanan' || strpos($nama_lower, 'burger') !== false || strpos($nama_lower, 'fries') !== false) {
                            $mood = 'makanan';
                        } 
                        // Kopi: Cek Kategori 'kopi' ATAU nama mengandung kata kunci kopi
                        elseif ($kategori == 'kopi' || strpos($nama_lower, 'kopi') !== false || strpos($nama_lower, 'coffee') !== false || strpos($nama_lower, 'americano') !== false || strpos($nama_lower, 'latte') !== false || strpos($nama_lower, 'espresso') !== false) {
                            $mood = 'kopi';
                        } 
                        // Non-Kopi (Santai Sore): Cek Kategori 'minuman' ATAU nama mengandung matcha, coklat, tea, dll
                        elseif ($kategori == 'minuman' || strpos($nama_lower, 'matcha') !== false || strpos($nama_lower, 'tea') !== false || strpos($nama_lower, 'chocolate') !== false || strpos($nama_lower, 'sugar') !== false || strpos($nama_lower, 'yakult') !== false) {
                            $mood = 'non-kopi';
                        }
                    ?>
                    
                    <div class="col-lg-6 col-md-12 mb-4 menu-item" data-mood="<?= $mood; ?>" data-id="<?= $m['id']; ?>">
                        <div class="neu-card d-flex h-100" style="background: var(--bg-card); border: 3px solid #000; box-shadow: 6px 6px 0 #000; transition: transform 0.2s;">
                            <!-- Image Side -->
                            <div style="width: 180px; min-width: 180px; border-right: 3px solid #000; position: relative; overflow: hidden;">
                                <!-- Tombol Wishlist -->
                                <div class="wishlist-btn" onclick="toggleWishlist(<?= $m['id']; ?>)" id="wishlist-<?= $m['id']; ?>" style="top: 8px; left: 8px; right: auto; width: 35px; height: 35px; font-size: 1rem; border: 2px solid #000;">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <img src="<?= base_url('assets/img/makanan/' . $m['gambar']); ?>" alt="<?= $m['nama']; ?>" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" onerror="this.onerror=null;this.src='https://placehold.co/400x300?text=No+Image';">
                            </div>
                            
                            <!-- Content Side -->
                            <div class="p-3 d-flex flex-column flex-grow-1 position-relative">
                                <h5 class="font-weight-bold text-uppercase mb-1" style="font-family: 'Archivo Black'; font-size: 1.4rem; line-height: 1; letter-spacing: -0.5px;">
                                    <?= $m['nama']; ?>
                                </h5>
                                <p class="text-muted small mb-3" style="line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-weight: 500;">
                                    <?= isset($m['deskripsi']) ? $m['deskripsi'] : 'Menu spesial dengan bahan pilihan terbaik.'; ?>
                                </p>
                                
                                <div class="mt-auto d-flex justify-content-between align-items-end">
                                    <div class="font-weight-bold" style="font-size: 1.2rem; background: #000; color: #fff; padding: 2px 8px; transform: rotate(-2deg);">
                                        Rp <?= number_format($m['harga'], 0, ',', '.'); ?>
                                    </div>
                                    
                                    <?php if(!$is_open): ?>
                                        <button class="btn btn-sm font-weight-bold" disabled style="border: 2px solid #000; opacity: 1; background: #ff4757; color: #fff;">TUTUP</button>
                                    <?php elseif($this->session->userdata('role_id') == 1): ?>
                                        <!-- Admin tidak bisa order -->
                                        <span class="badge badge-dark p-2" style="border: 2px solid #000;">ADMIN VIEW</span>
                                    <?php elseif($stok > 0): ?>
                                        <button class="btn btn-sm font-weight-bold" style="background: var(--yellow); border: 2px solid #000; box-shadow: 3px 3px 0 #000; padding: 8px 20px; font-size: 0.9rem;" 
                                            data-id="<?= $m['id']; ?>"
                                            data-nama="<?= strtoupper($m['nama']); ?>"
                                            data-harga="<?= $m['harga']; ?>"
                                            data-gambar="<?= base_url('assets/img/makanan/' . $m['gambar']); ?>"
                                            data-stok="<?= $stok; ?>"
                                            data-toggle="modal" data-target="#productModal">
                                            ADD +
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary font-weight-bold" disabled style="border: 2px solid #000; opacity: 1; background: #ccc; color: #555;">HABIS</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- FLOATING CHECKOUT BAR -->
    <?php if (isset($cart_count) && $cart_count > 0 && $this->session->userdata('role_id') != 1): ?>
    <a href="<?= base_url('transaksi/keranjang'); ?>" class="checkout-bar">
        <span class="mr-2"><?= $cart_count; ?> Items |</span> 
        <span>Rp <?= number_format($cart_total, 0, ',', '.'); ?></span>
        <i class="fas fa-arrow-right ml-3"></i>
        <div style="font-size: 0.8rem; margin-left: 10px; font-weight: normal; display:block;">BAYAR</div>
    </a>
    <?php endif; ?>
</div>

<!-- MODAL MENU -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: 3px solid #000; box-shadow: 8px 8px 0 #000; border-radius: 0;">
            <div class="modal-header" style="border-bottom: 3px solid #000; background: var(--yellow);">
                <h5 class="modal-title font-black text-uppercase" id="modalNamaMenu">MENU</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <img id="modalGambar" src="" class="img-fluid mb-3 w-100" style="border: 3px solid #000;">
                <h2 class="font-black text-center mb-1" id="modalHarga"></h2>
                <p class="text-center font-weight-bold text-muted mb-4">Sisa Stok: <span id="modalStok">0</span></p>
                
                <form id="form-tambah-keranjang" action="<?= base_url('transaksi/tambah_keranjang'); ?>" method="POST">
                    <input type="hidden" name="makanan_id" id="inputMakananId">
                    
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-uppercase" style="border-bottom: 2px solid #000; display:block; margin-bottom:10px;">Jumlah</label>
                        <div class="d-flex">
                            <input type="number" name="jumlah" id="inputJumlah" class="form-control text-center font-weight-bold" value="1" min="1" max="10" style="border: 3px solid #000; height: 50px; font-size: 1.5rem; border-radius:0;">
                        </div>
                    </div>
                    
                    <div class="form-group mb-4">
                         <label class="font-weight-bold text-uppercase" style="border-bottom: 2px solid #000; display:block; margin-bottom:10px;">Catatan</label>
                         <input type="text" name="catatan" class="form-control font-weight-bold" placeholder="Contoh: Pedas, Tanpa Es..." style="border: 3px solid #000; height: 50px; border-radius:0;">
                    </div>

                    <button type="submit" class="btn btn-add-yellow btn-block py-3" style="font-size: 1.2rem;">
                        MASUKKAN KERANJANG
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadWishlist();

    // Populate Modal Data
    $('#productModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nama = button.data('nama');
        var harga = button.data('harga');
        var gambar = button.data('gambar');
        var stok = button.data('stok');

        var modal = $(this);
        modal.find('#modalNamaMenu').text(nama);
        modal.find('#modalHarga').text('Rp ' + new Intl.NumberFormat('id-ID').format(harga));
        modal.find('#modalStok').text(stok);
        modal.find('#modalGambar').attr('src', gambar);
        modal.find('#inputMakananId').val(id);
        modal.find('#inputJumlah').val(1);
    });

    // AJAX ADD TO CART (DELEGATED EVENT - MORE ROBUST)
    $(document).on('submit', '#form-tambah-keranjang', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var originalText = btn.text();

        btn.prop('disabled', true).text('MENAMBAHKAN...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    // Tutup Modal
                    $('#productModal').modal('hide');
                    
                    // Update Badge Cart di Header (jika ada)
                    $('.cart-count-badge').text(response.cart_count);
                    if ($('.cart-count-badge').length == 0 && response.cart_count > 0) {
                        $('.btn-cart-box').parent().append('<div class="cart-count-badge">' + response.cart_count + '</div>');
                    }

                    // Update Floating Checkout Bar
                    var checkoutBar = $('.checkout-bar');
                    var formattedTotal = new Intl.NumberFormat('id-ID').format(response.cart_total);
                    
                    if (checkoutBar.length > 0) {
                        checkoutBar.find('span').first().text(response.cart_count + ' Items |');
                        checkoutBar.find('span').eq(1).text('Rp ' + formattedTotal);
                    } else {
                        // Buat baru jika belum ada
                        var newBar = `
                        <a href="<?= base_url('transaksi/keranjang'); ?>" class="checkout-bar">
                            <span class="mr-2">${response.cart_count} Items |</span> 
                            <span>Rp ${formattedTotal}</span>
                            <i class="fas fa-arrow-right ml-3"></i>
                            <div style="font-size: 0.8rem; margin-left: 10px; font-weight: normal; display:block;">BAYAR</div>
                        </a>`;
                        $('body').append(newBar);
                    }

                    // NOTIFIKASI SUKSES YANG JELAS
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
                btn.prop('disabled', false).text(originalText);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan koneksi.'
                });
                btn.prop('disabled', false).text(originalText);
            }
        });
    });
});

function smartSearch() {
    let input = document.getElementById('searchInput').value;
    let resultsContainer = document.getElementById('search-results');
    
    if (input.length < 1) {
        resultsContainer.style.display = 'none';
        // Reset manual items display
        document.querySelectorAll('.menu-item').forEach(item => item.style.display = 'block');
        return;
    }

    // Client-side quick filter first
    let items = document.querySelectorAll('.menu-item');
    items.forEach(function(item) {
        let text = item.innerText.toLowerCase();
        if (text.includes(input.toLowerCase())) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });

    // AJAX for suggestions
    fetch('<?= base_url("home/search_ajax?keyword="); ?>' + encodeURIComponent(input))
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let html = '';
                data.forEach(item => {
                    html += `
                        <div class="search-item p-3 d-flex align-items-center" style="cursor: pointer; border-bottom: 2px solid #000; transition: 0.2s;" onclick="selectSearchItem(${item.id})">
                            <img src="${item.gambar_url}" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #000; margin-right: 15px;">
                            <div class="flex-grow-1">
                                <div class="font-weight-bold text-uppercase" style="font-size: 1rem; line-height: 1;">${item.nama}</div>
                                <div class="text-muted small">${item.kategori} | ${item.harga_formatted}</div>
                            </div>
                        </div>
                    `;
                });
                resultsContainer.innerHTML = html;
                resultsContainer.style.display = 'block';
            } else {
                resultsContainer.style.display = 'none';
            }
        });
}

function selectSearchItem(id) {
    // Force show all items first
    document.querySelectorAll('.menu-item').forEach(item => item.style.display = 'block');
    document.querySelectorAll('.filter-card').forEach(btn => btn.classList.remove('active'));
    document.getElementById('btn-all').classList.add('active');

    let resultsContainer = document.getElementById('search-results');
    resultsContainer.style.display = 'none';
    document.getElementById('searchInput').value = '';

    // Cari elemen target
    let target = document.querySelector(`.menu-item[data-id="${id}"]`);
    if (target) {
        // Scroll ke item tersebut
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Efek Highlight agar mata user langsung tertuju ke sana
        let card = target.querySelector('.neu-card');
        if (card) {
            card.style.transition = 'all 0.3s ease';
            card.style.transform = 'scale(1.05)';
            card.style.boxShadow = '15px 15px 0 #fff200';
            card.style.borderColor = '#fff200';
            card.style.zIndex = '1000';

            // Setelah scroll selesai (estimasi), buka modal dan kembalikan style
            setTimeout(() => {
                card.style.transform = '';
                card.style.boxShadow = '6px 6px 0 #000';
                card.style.borderColor = '#000';
                card.style.zIndex = '';
                
                // Trigger modal click
                // Sesuaikan selector dengan tombol ADD +
                let addBtn = target.querySelector('button[data-toggle="modal"]');
                if (addBtn) {
                   addBtn.click();
                }
            }, 800);
        }
    }
}

// Close search on click outside, but allow clicks inside search results
document.addEventListener('click', function(e) {
    let searchInput = document.getElementById('searchInput');
    let resultsContainer = document.getElementById('search-results');
    
    if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
        resultsContainer.style.display = 'none';
    }
});

function filterMenu(mood, btnElement) {
    // Reset search
    document.getElementById('searchInput').value = '';

    // Visual feedback for active button
    if(btnElement) {
        document.querySelectorAll('.filter-card').forEach(btn => {
            btn.classList.remove('active');
            // Remove transform enhancement from others
            btn.style.transform = 'translate(0, 0)';
            btn.style.boxShadow = '4px 4px 0 #000';
        });
        
        btnElement.classList.add('active');
        // Visual 'Pressed' effect
        btnElement.style.transform = 'translate(0, 0)'; 
        btnElement.style.boxShadow = 'inset 4px 4px 0 #000'; 
    }

    // Reset warna tombol Favorit
    let btnFav = document.getElementById('btn-fav');
    if(btnFav) {
        btnFav.classList.remove('yellow'); 
        btnFav.style.background = '#ff4757';
        btnFav.style.color = '#fff';
        btnFav.innerHTML = '<i class="fas fa-heart mr-2"></i> FAVORITKU';
    }

    let items = document.querySelectorAll('.menu-item');
    items.forEach(function(item) {
        if (mood === 'all' || item.getAttribute('data-mood') === mood) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function toggleWishlist(id) {
    id = parseInt(id);
    let favorites = JSON.parse(localStorage.getItem('kiise_fav')) || [];
    favorites = favorites.map(x => parseInt(x));
    
    let index = favorites.indexOf(id);
    let btn = document.getElementById('wishlist-' + id);
    
    if (index === -1) {
        favorites.push(id);
        if(btn) btn.classList.add('active');
    } else {
        favorites.splice(index, 1);
        if(btn) btn.classList.remove('active');
        
        let btnFavText = document.getElementById('btn-fav').innerText;
        if(btnFavText.includes("DITAMPILKAN")) {
             let item = document.querySelector(`.menu-item[data-id="${id}"]`);
             if(item) item.style.display = 'none';
        }
    }
    
    localStorage.setItem('kiise_fav', JSON.stringify(favorites));
}

function loadWishlist() {
    let favorites = JSON.parse(localStorage.getItem('kiise_fav')) || [];
    favorites.forEach(function(id) {
        let btn = document.getElementById('wishlist-' + id);
        if(btn) btn.classList.add('active');
    });
}

function showFavorites() {
    let favorites = JSON.parse(localStorage.getItem('kiise_fav')) || [];
    favorites = favorites.map(x => parseInt(x));
    
    let items = document.querySelectorAll('.menu-item');
    let btnFav = document.getElementById('btn-fav');

    if(favorites.length === 0) {
        alert("Kamu belum punya menu favorit! Klik ikon hati pada menu untuk menambahkannya.");
        filterMenu('all');
        return;
    }

    btnFav.style.background = '#000';
    btnFav.innerHTML = '<i class="fas fa-heart mr-2"></i> DITAMPILKAN';

    items.forEach(function(item) {
        let id = parseInt(item.getAttribute('data-id'));
        if (favorites.includes(id)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
