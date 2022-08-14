</head>

<body>
    <?php require 'View/SharedCommon/_common_loader.php'; ?>
    <?php $menu_preference = isset($_SESSION[COOKIE_ADMIN_MENU]) && ($_SESSION[COOKIE_ADMIN_MENU] == 'false'); ?>
    <nav class="navbar navbar-default<?php echo $menu_preference ? ' toggler-active' : ''; ?>">
        <div class="navbar-top">
            <i class="navbar-mobile-close-toggler fas fa-times"></i>
            <div class="navbar-brand">
                <a class="navbar-brand-text" href="<?php echo URL; ?>AdminController" title="Yönetici Paneli"><?php echo BRAND; ?></a>
                <a class="home-panel-text" href="<?php echo URL; ?>" title="Kullanıcı Paneli">/ Ana Sayfa</a>
            </div>
        </div>
        <ul class="navbar-nav">
            <div class="user-profile-mobile">
                <?php if (!empty($web_data['authed_user'])) : ?>
                    <span class="nav-title"><?php echo ucwords($web_data['authed_user']['first_name']) . ' ' . ucwords($web_data['authed_user']['last_name']); ?></span>
                <?php endif; ?>
                <li>
                    <div class="dropdown-toggler nav-link">
                        <span class="row-left">Profil Ayarları</span>
                        <i class="fas fa-angle-right row-right dropdown-toggler-icon"></i>
                    </div>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-link nav-link" href="<?php echo URL; ?>AdminController/Profile">Profil</a></li>
                        <li><a class="dropdown-link nav-link" href="<?php echo URL; ?>AdminController/Settings">Ayarlar</a></li>
                        <li><a class="dropdown-link nav-link" href="<?php echo URL; ?>AccountController/LogOut">Çıkış Yap</a></li>
                    </ul>
                </li>
            </div>
            <span class="nav-title">ÜRÜN VE FİLTRE AYARLARI</span>
            <li><a class="nav-link" href="<?php echo URL; ?>AdminController/Items">Ürünler</a></li>
            <li><a class="nav-link" href="<?php echo URL; ?>AdminController/Filters">Filtreler</a></li>
            <span class="nav-title">KULLANICI AYARLARI</span>
            <li><a class="nav-link" href="<?php echo URL; ?>AdminController/Users">Kullanıcılar</a></li>
            <li><a class="nav-link" href="<?php echo URL; ?>AdminController/Roles">Roller</a></li>
            <li><a class="nav-link" href="<?php echo URL; ?>AdminController/AdvertisingInfos">Kullanıcı Reklam Verileri</a></li>
            <span class="nav-title">STOK VE SATIŞ AYARLARI</span>
            <li><a class="nav-link" href="<?php echo URL; ?>AdminController/StockControl">Stok Kontrol</a></li>
            <li><a class="nav-link" href="<?php echo URL; ?>AdminController/SalesHistory">Satış Geçmişi</a></li>
            <span class="nav-title">VERİTABANI AYARLARI</span>
            <li><a class="nav-link" href="<?php echo URL; ?>AdminController/Clearweb_database">Veritabanı Bakım</a></li>
        </ul>
    </nav>
    <div class="admin-panel admin-panel-default<?php echo $menu_preference ? ' toggler-active' : ''; ?>">
        <header>
            <div class="notification">
                <?php if (isset($_SESSION[SESSION_NOTIFICATION])) {
                    echo $_SESSION[SESSION_NOTIFICATION];
                    unset($_SESSION[SESSION_NOTIFICATION]);
                } ?>
            </div>
            <div class="container">
                <div class="row">
                    <div class="row-center row-left">
                        <button id="btn-hamburger" class="btn-purify">
                            <div class="hamburger-toggler navbar-desktop-toggler">
                                <div class="hamburger-line"></div>
                                <div class="hamburger-line"></div>
                                <div class="hamburger-line"></div>
                            </div>
                        </button>
                        <div class="hamburger-toggler navbar-mobile-toggler">
                            <div class="hamburger-line"></div>
                            <div class="hamburger-line"></div>
                            <div class="hamburger-line"></div>
                        </div>
                    </div>
                    <div class="row-center row-right">