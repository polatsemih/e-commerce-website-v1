<?php require_once 'View/SharedCommon/_loader.php'; ?>
<nav class="navbar navbar-default<?php echo $web_data['admin_menu'] == 1 ? ' toggler-active' : ''; ?>">
    <div class="navbar-top">
        <i class="navbar-mobile-close-toggler fas fa-times"></i>
        <div class="navbar-brand">
            <a class="navbar-brand-text" href="<?php echo URL . URL_ADMIN_INDEX; ?>" title="Yönetici Paneli"><?php echo BRAND; ?></a>
            <a class="home-panel-text" href="<?php echo URL; ?>" title="Kullanıcı Paneli">/ Ana Sayfa</a>
        </div>
    </div>
    <ul class="navbar-nav">
        <div class="user-profile-mobile">
            <span class="nav-title">Ayarlar</span>
            <li>
                <div class="dropdown-toggler nav-link">
                    <span class="row-left">Profil Ayarları</span>
                    <i class="fas fa-angle-right row-right dropdown-toggler-icon"></i>
                </div>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-link nav-link" href="<?php // echo URL . URL_ADMIN_PROFILE; 
                                                                ?>">Profil</a></li>
                    <li><a class="dropdown-link nav-link" href="<?php echo URL . URL_ADMIN_LOGOUT; ?>">Çıkış Yap</a></li>
                </ul>
            </li>
        </div>
        <span class="nav-title">ÜRÜN AYARLARI</span>
        <li><a class="nav-link" href="<?php echo URL . URL_ADMIN_ITEMS; ?>">Ürünler</a></li>
        <li><a class="nav-link" href="<?php echo URL . URL_ADMIN_ITEM_CREATE; ?>">Ürün Ekle</a></li>
        <span class="nav-title">SİPARİŞ AYARLARI</span>
        <li><a class="nav-link" href="<?php echo URL . URL_ADMIN_ORDERS; ?>">Siparişler</a></li>
        <span class="nav-title">KULLANICI AYARLARI</span>
        <li><a class="nav-link" href="<?php echo URL . URL_ADMIN_USERS; ?>">Kullancılar</a></li>
        <li><a class="nav-link" href="<?php echo URL . URL_ADMIN_SEND_EMAIL; ?>">Email Gönder</a></li>
        <span class="nav-title">SEYİR DEFTERİ</span>
        <li><a class="nav-link" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_PAGE; ?>">Kayıtlar</a></li>
    </ul>
</nav>
<div class="admin-panel admin-panel-default<?php echo $web_data['admin_menu'] == 1 ? ' toggler-active' : ''; ?>">
    <header>
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
                    <div class="btn-header user-profile">
                        <span class="user-name">Ayarlar</span>
                        <i class="fas fa-chevron-down dropdown-profile-icon"></i>
                        <ul class="dropdown-profile-menu">
                            <li><a class="dropdown-profile-link" href="<?php // echo URL . URL_ADMIN_PROFILE; 
                                                                        ?>">Profil</a></li>
                            <li><a class="dropdown-profile-link" href="<?php echo URL . URL_ADMIN_LOGOUT; ?>">Çıkış Yap</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="notification-wrapper">
            <?php if (!empty($_SESSION[SESSION_NOTIFICATION_NAME])) {
                echo $_SESSION[SESSION_NOTIFICATION_NAME];
                unset($_SESSION[SESSION_NOTIFICATION_NAME]);
            }
            ?>
        </div>