<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= $title; ?> | KiiseCoffee</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Public+Sans:wght@400;700;900&display=swap" rel="stylesheet">
    
    <!-- DARK MODE CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/darkmode.css">

    <style>
        :root {
            --border-width: 3px;
            --shadow-offset: 4px;
            --primary-yellow: #fff200;
            --primary-blue: #0056b3;
            --bg-grid: #e5e5e5;
        }
        
        body {
            background-color: #f8f9fa;
            background-image: 
                linear-gradient(var(--bg-grid) 1px, transparent 1px), 
                linear-gradient(90deg, var(--bg-grid) 1px, transparent 1px);
            background-size: 30px 30px;
            font-family: 'Public Sans', sans-serif;
            color: #000;
            padding-top: 100px;
        }

        .navbar-neu {
            background-color: #fff;
            border-bottom: var(--border-width) solid #000;
            padding: 10px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        
        .brand-box {
            font-family: 'Archivo Black', sans-serif;
            font-size: 1.8rem;
            text-transform: uppercase;
            color: #000;
            line-height: 1;
        }

        .cart-box-container {
            position: relative;
        }

        .btn-cart-box {
            background: var(--primary-yellow);
            border: var(--border-width) solid #000;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #000;
            box-shadow: var(--shadow-offset) var(--shadow-offset) 0 #000;
            transition: all 0.1s;
        }
        
        .btn-cart-box:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 #000;
        }

        .cart-count-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #000;
            color: var(--primary-yellow);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 0.8rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        .nav-logout {
            font-weight: 700;
            text-transform: uppercase;
            color: #000;
            margin-right: 20px;
            font-size: 0.9rem;
            text-decoration: none !important;
        }

        .neu-card {
            background: #fff;
            border: var(--border-width) solid #000;
            box-shadow: var(--shadow-offset) var(--shadow-offset) 0 #000;
            border-radius: 0;
            height: 100%;
            transition: transform 0.2s;
        }
        
        .neu-btn {
            border: var(--border-width) solid #000;
            font-weight: 800;
            text-transform: uppercase;
            border-radius: 0;
            padding: 10px 15px;
            box-shadow: var(--shadow-offset) var(--shadow-offset) 0 #000;
            transition: all 0.1s;
        }
        
        .neu-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #000;
        }
        
        .neu-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 #000;
        }

        .neu-input {
            border: var(--border-width) solid #000;
            border-radius: 0;
            padding: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-neu">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <?php 
                    $dashboard_url = base_url();
                    if($this->session->userdata('role_id') == 1) {
                        $dashboard_url = base_url('admin');
                    }
                ?>
                <a href="<?= $dashboard_url; ?>" class="text-decoration-none mr-3">
                    <div class="brand-box">KIISE COFFEE</div>
                </a>
                <!-- DIGITAL CLOCK -->
                <div id="digital-clock" class="font-weight-bold d-none d-md-block" style="font-family: 'Archivo Black'; font-size: 1.1rem; border: 2px solid #000; padding: 4px 8px; background: #fff200; box-shadow: 3px 3px 0 #000; letter-spacing: 1px;">
                    00:00:00
                </div>
            </div>

            <div class="d-flex align-items-center">
                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle" class="dark-mode-toggle mr-3" title="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>
                

                <?php if($this->session->userdata('email')): ?>
                    <!-- PROFILE ICON -->
                    <a href="<?= base_url('user'); ?>" class="mr-3 text-decoration-none text-dark" title="Profil Saya">
                        <div style="width: 45px; height: 45px; border: 3px solid #000; overflow: hidden; border-radius: 50%; box-shadow: 3px 3px 0 #000;">
                            <?php 
                                $img_src = 'default.jpg';
                                if(isset($user['image']) && !empty($user['image'])) {
                                    $img_src = $user['image'];
                                }
                                $img_url = base_url('assets/img/profile/') . $img_src; 
                            ?>
                            <img src="<?= $img_url; ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='<?= base_url('assets/img/profile/default.jpg'); ?>'">
                        </div>
                    </a>
                    <a href="<?= base_url('auth/keluar'); ?>" class="nav-logout">Logout</a>
                <?php else: ?>
                    <a href="<?= base_url('auth'); ?>" class="nav-logout">Login</a>
                <?php endif; ?>

                <a href="<?= base_url('transaksi/keranjang'); ?>" class="cart-box-container text-decoration-none">
                    <div class="btn-cart-box">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <?php if (isset($cart_count) && $cart_count > 0): ?>
                        <div class="cart-count-badge"><?= $cart_count; ?></div>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </nav>
    
    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-GB', { hour12: false });
            const clockElement = document.getElementById('digital-clock');
            if(clockElement) {
                clockElement.innerText = timeString;
            }
        }
        setInterval(updateClock, 1000);
        updateClock(); // Initial call
    </script>
    <div id="app">
