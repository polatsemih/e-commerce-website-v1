<div class="header-container">
    <div class="container row-adjacent header-sub-container">
        <div class="mobile-toggler">
            <i class="toggler-icon fas fa-bars"></i>
        </div>
        <div class="header-brand-container">
            <a class="header-brand" href="<?php echo URL; ?>"><?php echo BRAND; ?></a>
        </div>
        <nav class="header-nav">
            <ul class="navbar-nav">
                <?php if (!empty($web_data['filter_genders'])) : foreach ($web_data['filter_genders'] as $filter_gender) : ?>
                    <li><a class="nav-link" href="<?php echo URL . URL_ITEMS . '/' . $filter_gender['gender_url']; ?>"><?php echo $filter_gender['gender_name']; ?></a></li>
                <?php endforeach; endif; ?>
                <div class="mobile-menu">
                    <li><a class="nav-link" href="<?php echo URL . URL_FAVORITES; ?>">Favoriler</a></li>
                    <li><a class="nav-link" href="<?php echo URL . URL_SHOPPINGCART; ?>">Sepet</a></li>
                    <?php if (!empty($web_data['authed_user'])) : ?>
                        <li><a href="<?php echo URL . URL_PROFILE; ?>" class="nav-link mobile-link">Profil</a></li>
                        <li><a href="<?php echo URL . URL_SETTINGS; ?>" class="nav-link mobile-link">Ayarlar</a></li>
                        <li><a href="<?php echo URL . URL_LOGOUT; ?>" class="nav-link mobile-link">Çıkış Yap</a></li>
                    <?php else : ?>
                        <li><a href="<?php echo URL . URL_LOGIN; ?>" class="nav-link mobile-link">Giriş Yap</a></li>
                        <li><a href="<?php echo URL . URL_REGISTER; ?>" class="nav-link mobile-link">Üye Ol</a></li>
                    <?php endif; ?>
                </div>
            </ul>
        </nav>
        <div class="row-right icons-container">
            <i class="header-icon header-search-icon fas fa-search" title="Ara"></i>
            <?php if (!empty($web_data['authed_user'])) : ?>
                <div class="header-show-profile action-open-icon purgatory-icons">
                    <div class="row-center">
                        <span class="show-profile-text">Profilim</span>
                        <i class="fas fa-angle-down show-profile-icon"></i>
                        <ul class="action-options">
                            <div class="triangle-container triangle-container-auth">
                                <div class="lower-triangle"></div>
                            </div>
                            <li class="dropdown-action-item">
                                <a class="dropdown-action-link authed_link" href="<?php echo URL . URL_PROFILE; ?>">Profil</a>
                            </li>
                            <li class="dropdown-action-item">
                                <a class="dropdown-action-link authed_link" href="<?php echo URL . URL_SETTINGS; ?>">Ayarlar</a>
                            </li>
                            <li class="dropdown-action-item">
                                <a class="dropdown-action-link authed_link" href="<?php echo URL . URL_LOGOUT; ?>">Çıkış Yap</a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php else : ?>
                <a href="<?php echo URL . URL_LOGIN; ?>"><i class="header-icon action-open-icon action-open-scale purgatory-icons fas fa-user"></i></a>
                <ul class="action-options">
                    <div class="triangle-container triangle-container-not-auth">
                        <div class="lower-triangle"></div>
                    </div>
                    <li class="dropdown-action-item">
                        <a class="dropdown-action-link login-link" href="<?php echo URL . URL_LOGIN; ?>">Giriş Yap</a>
                    </li>
                    <li class="dropdown-action-item">
                        <a class="dropdown-action-link register-link" href="<?php echo URL . URL_REGISTER; ?>">Kayıt Ol</a>
                    </li>
                </ul>
            <?php endif; ?>
            <a class="header-icon purgatory-icons" href="<?php echo URL . URL_FAVORITES; ?>" title="Favoriler">
                <i class="fas fa-heart"></i>
            </a>
            <a class="header-icon purgatory-icons" href="<?php echo URL . URL_SHOPPINGCART; ?>" title="Sepet">
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
    </div>
</div>