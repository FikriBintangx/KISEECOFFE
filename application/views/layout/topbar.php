<style>
/* ADMIN TOPBAR - BOOTSTRAP ALIGNED */
.navbar-bg {
    border-bottom: 3px solid #000 !important;
    background: #fff; /* Removed !important to allow dark mode override */
    height: auto;
}

.main-navbar {
    background: #fff; /* Removed !important to allow dark mode override */
    padding: 15px 0;
}

.digital-clock {
    font-family: 'Archivo Black', sans-serif;
    font-size: 1.1rem;
    border: 3px solid #000;
    padding: 8px 15px;
    background: #fff200;
    box-shadow: 3px 3px 0 #000;
    letter-spacing: 1px;
    font-weight: 900;
}

.nav-link-user img {
    border: 3px solid #000 !important;
    width: 45px;
    height: 45px;
    box-shadow: 3px 3px 0 #000;
}

.search-input {
    border: 3px solid #000;
    border-right: none;
    background: #fff;
    height: 50px;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 0;
}

.search-input:focus {
    box-shadow: none !important;
    border-color: #000 !important;
    outline: none !important;
}

.search-btn {
    border: 3px solid #000;
    border-left: none;
    background: #000;
    color: #fff;
    height: 50px;
    width: 60px;
    border-radius: 0;
}

.search-btn:hover {
    background: #333;
    color: #fff;
}

.dropdown-menu {
    border: 3px solid #000 !important;
    box-shadow: 6px 6px 0 #000 !important;
    border-radius: 0 !important;
    margin-top: 10px !important;
}

.dropdown-item {
    font-weight: 600;
    padding: 12px 20px;
}

.dropdown-item:hover {
    background: #fff200;
}

.dropdown-divider {
    border-top: 2px solid #000;
    margin: 0;
}

/* SEARCH RESULT */
.search-result {
    position: absolute;
    z-index: 999;
    width: 100%;
    background: #fff;
    border: 3px solid #000;
    box-shadow: 6px 6px 0 #000;
    margin-top: 10px;
    max-height: 400px;
    overflow-y: auto;
    display: none;
}

.search-result .search-header {
    padding: 12px 15px;
    font-weight: 900;
    background: #000;
    color: #fff;
    border-bottom: 3px solid #000;
    text-transform: uppercase;
    font-family: 'Archivo Black', sans-serif;
}

.search-result .search-item {
    border-bottom: 2px solid #000;
}

.search-result .search-item:last-child {
    border-bottom: none;
}

.search-result .search-item a {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    text-decoration: none;
    color: #000 !important; /* Force black text for contrast */
    font-weight: 600;
    transition: all 0.2s;
}

.search-result .search-item a:hover {
    background: #fff200;
    transform: translateX(5px);
    color: #000 !important;
}

.search-result .search-icon {
    width: 35px;
    height: 35px;
    line-height: 35px;
    text-align: center;
    border: 2px solid #000;
    margin-right: 12px;
    font-weight: bold;
    color: #000 !important; /* Force icon color */
}
</style>

<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <div class="container-fluid px-4">
        <div class="row w-100 align-items-center">
            <!-- LEFT: HAMBURGER + SEARCH BAR -->
            <div class="col-md-8 col-lg-9 col-8">
                <div class="d-flex align-items-center">
                    <!-- Hamburger Menu -->
                    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg text-dark mr-3" style="font-size: 1.5rem;">
                        <i class="fas fa-bars"></i>
                    </a>
                    
                    <!-- Search Bar -->
                    <div class="flex-grow-1" style="position: relative; max-width: 600px;">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control search-input" 
                                   name="q" 
                                   id="admin-search-input" 
                                   placeholder="Cari menu, data..." 
                                   autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn search-btn" type="button" onclick="document.getElementById('admin-search-input').focus()">
                                    <i class="fas fa-search fa-lg"></i>
                                </button>
                            </div>
                        </div>
                        <!-- SEARCH RESULTS -->
                        <div class="search-result" id="admin-search-result"></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: CLOCK + DARK MODE + PROFILE -->
            <div class="col-md-4 col-lg-3 col-4">
                <div class="d-flex align-items-center justify-content-end">
                    <!-- Digital Clock (Hidden on Mobile) -->
                    <div id="digital-clock" class="digital-clock mr-3 d-none d-md-flex" style="height: 45px; align-items: center;">
                        00:00:00
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" class="btn mr-3" title="Toggle Dark Mode" style="border: 3px solid #000; box-shadow: 3px 3px 0 #000; background: #212529; color: #fff; height: 45px; width: 45px; border-radius: 0; display: flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="fas fa-moon"></i>
                    </button>

                    <!-- Profile Dropdown -->
                    <?php 
                        $safe_img = (isset($user['image']) && $user['image']) ? $user['image'] : 'default.png';
                        $safe_name = (isset($user['nama'])) ? $user['nama'] : 'User';
                        $safe_role = (isset($user['role_id'])) ? $user['role_id'] : 2;
                    ?>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user p-0">
                            <img alt="image" 
                                 src="<?= base_url('assets/img/profile/' . $safe_img); ?>" 
                                 class="rounded-circle" 
                                 style="border: 3px solid #000; width: 45px; height: 45px; object-fit: cover; box-shadow: 3px 3px 0 #000;"
                                 onerror="this.src='<?= base_url('assets/img/profile/default.png'); ?>'">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <?php if ($safe_role == 1) : ?>
                                <a href="<?= base_url('admin/profile'); ?>" class="dropdown-item has-icon">
                                    <i class="far fa-user mr-2"></i> Profil Admin
                                </a>
                            <?php else : ?>
                                <a href="<?= base_url('user'); ?>" class="dropdown-item has-icon">
                                    <i class="far fa-user mr-2"></i> Profil Saya
                                </a>
                                <a href="<?= base_url('user/ubah'); ?>" class="dropdown-item has-icon">
                                    <i class="fas fa-user-edit mr-2"></i> Ubah Profil
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?= base_url('auth/keluar'); ?>" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Digital Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-GB', { hour12: false });
        const clockElement = document.getElementById('digital-clock');
        if(clockElement) {
            clockElement.innerText = timeString;
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Search functionality
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('admin-search-input');
        const searchResult = document.getElementById('admin-search-result');
        let timeout = null;

        if (searchInput && searchResult) {
            searchInput.addEventListener('keyup', function(e) {
                clearTimeout(timeout);
                const query = e.target.value;

                if (query.length > 0) {
                    timeout = setTimeout(function() {
                        $.ajax({
                            url: '<?= base_url('ajax/search_global') ?>',
                            type: 'GET',
                            data: { q: query },
                            dataType: 'json',
                            success: function(data) {
                                let html = '';
                                if (data.length > 0) {
                                    html += '<div class="search-header">Hasil Pencarian</div>';
                                    data.forEach(item => {
                                        let icon = 'fas fa-search';
                                        if (item.type === 'Menu') icon = 'fas fa-bars';
                                        if (item.type === 'Submenu') icon = 'fas fa-chevron-right';
                                        if (item.type === 'Makanan') icon = 'fas fa-utensils';
                                        if (item.type === 'User') icon = 'fas fa-user';
                                        if (item.type === 'Transaksi') icon = 'fas fa-file-invoice-dollar';

                                        html += `
                                        <div class="search-item">
                                            <a href="${item.url}">
                                                <div class="search-icon bg-primary text-white" style="border: 2px solid #000;">
                                                    <i class="${icon}"></i>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; color: #000 !important;">${item.title}</div>
                                                    <div class="text-small" style="color: #000 !important;">${item.type}</div>
                                                </div>
                                            </a>
                                        </div>`;
                                    });
                                } else {
                                    html += '<div class="search-item"><a href="#" style="padding: 15px;">Tidak ada hasil ditemukan</a></div>';
                                }
                                searchResult.innerHTML = html;
                                searchResult.style.display = 'block';
                            }
                        });
                    }, 300);
                } else {
                    searchResult.style.display = 'none';
                    searchResult.innerHTML = '';
                }
            });

            // Close search result when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResult.contains(e.target)) {
                    searchResult.style.display = 'none';
                }
            });

            // Show results again on focus if query exists
            searchInput.addEventListener('focus', function() {
                if (this.value.length > 0 && searchResult.innerHTML !== '') {
                    searchResult.style.display = 'block';
                }
            });
        }
    });
</script>
