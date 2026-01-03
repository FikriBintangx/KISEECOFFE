<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= $title ?></title>
    <!-- MOBILE NATIVE FEEL -->
    <meta name="theme-color" content="#FFFFFF">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- General CSS Files -->
    <!-- ONLINE -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous"> -->

    <!-- OFFLINE -->
    <link rel="stylesheet" href="<?= base_url(); ?>template/stisla/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>template/stisla/node_modules/@fortawesome/fontawesome-free/css/all.min.css">

    <!-- CSS Libraries -->
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Public+Sans:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>template/stisla/assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url(); ?>template/stisla/assets/css/my.css">
    <link rel="stylesheet" href="<?= base_url(); ?>template/stisla/assets/css/components.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/datatables/datatables.css">
    
    <!-- DARK MODE CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/darkmode.css?v=<?= time(); ?>">
    
    <style>
        /* FIX UI/UX OVERLAP ISSUE */
        .main-content {
            padding-top: 140px !important; /* Push content down below the 115px header */
        }
        
        /* Vertically align topbar items */
        .navbar.main-navbar {
            top: 15px; /* Push navbar down slightly to center it within the 115px bg */
        }
        
        /* Ensure search bar looks good */
        .form-inline .form-control {
            height: 45px;
            font-size: 15px;
        }

        /* Ensure section header title is visible */
        .section-header h1 {
            margin-top: 0;
            color: #000;
        }

        /* MOBILE NATIVE BOTTOM NAV STYLES */
        @media (max-width: 767.98px) {
            .main-content {
                padding-top: 80px !important; /* Adjust top padding for mobile */
                padding-bottom: 80px !important; /* Space for bottom nav */
            }
            
            /* Hide Default Sidebar on Mobile if we use Bottom Nav (Optional, user preference) 
               For now we keep sidebar accessible via hamburger 
            */

            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                background-color: #ffffff;
                border-top: 2px solid #000; /* Brutalism style */
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                display: flex;
                justify-content: space-around;
                align-items: center;
                height: 70px;
                padding-bottom: env(safe-area-inset-bottom); /* iOS Save Area */
            }

            .mobile-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                color: #000;
                font-size: 0.75rem;
                font-weight: 600;
                width: 100%;
                height: 100%;
            }

            .mobile-nav-item i {
                font-size: 1.4rem;
                margin-bottom: 4px;
                color: #000;
                transition: transform 0.2s;
            }
            
            .mobile-nav-item.active i {
                color: #000;
                transform: scale(1.1);
            }

            .mobile-nav-item.active {
                background-color: #fff200; /* Highlight Active */
            }
            
            /* Adjustment for topbar on mobile to save space */
            .navbar-bg {
                height: 70px;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
