<div class="header-wrapper">
    <div class="container header-row">
        <div class="mobile-toggler">
            <i class="fas fa-bars toggler-icon" id="toggler-icon"></i>
            <?php if (!empty($web_data['cookie_cart_view'])) : ?>
                <span class="mobile-cart-count"><?php echo count($web_data['cookie_cart_view']); ?></span>
            <?php endif; ?>
        </div>
        <div class="brand-container">
            <a class="header-brand" href="<?php echo URL; ?>"><?php echo BRAND; ?></a>
        </div>
        <nav class="header-nav" id="header-nav">
            <ul class="navbar-nav">
                <?php foreach ($web_data['genders'] as $gender) : ?>
                    <li><a class="purgatory-icon nav-link<?php echo !empty($web_data['selected_gender_url']) && $web_data['selected_gender_url'] == $gender['gender_url'] ? ' selected' : ''; ?>" href="<?php echo URL . URL_ITEMS . '/' . $gender['gender_url']; ?>"><?php echo $gender['gender_name']; ?></a></li>
                <?php endforeach; ?>
                <div class="mobile-menu">
                    <li><a class="nav-link" href="<?php echo URL . URL_FAVORITES; ?>">Favorilerim</a></li>
                    <li>
                        <a class="nav-link cart" href="<?php echo URL . URL_CART; ?>">
                            <?php if (!empty($web_data['cookie_cart_view'])) : ?>
                                <span class="mobile-list-cart-count"><?php echo count($web_data['cookie_cart_view']); ?></span>
                            <?php endif; ?>
                            Sepetim
                        </a>
                    </li>
                    <?php if (!empty($web_data['authenticated_user'])) : ?>
                        <li><a class="nav-link mobile-link" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_INFO; ?>">Profilim</a></li>
                        <li><a class="nav-link mobile-link" href="<?php echo URL . URL_LOGOUT; ?>">Çıkış Yap</a></li>
                    <?php else : ?>
                        <li><a class="nav-link mobile-link" href="<?php echo URL . URL_LOGIN; ?>">Giriş Yap</a></li>
                        <li><a class="nav-link mobile-link" href="<?php echo URL . URL_REGISTER; ?>">Üye Ol</a></li>
                    <?php endif; ?>
                </div>
            </ul>
        </nav>
        <div class="icons">
            <i id="header-search-icon" class="fas fa-search icon search-icon" title="Ara"></i>
            <?php if (!empty($web_data['authenticated_user'])) : ?>
                <div class="show-profile action-open-icon purgatory-icon" id="action-open-icon">
                    <a class="row" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_INFO; ?>">
                        <span class="text">Profilim</span>
                        <i class="fas fa-angle-down profile-icon"></i>
                    </a>                    
                    <div class="triangle authed" id="action-triangle">
                        <div class="lower-triangle"></div>
                    </div>
                </div>
                <ul id="action-options" class="action-options">
                    <div class="triangle-container triangle-container-auth">
                        <div class="lower-triangle"></div>
                    </div>
                    <li class="dropdown-action-item">
                        <a class="dropdown-action-link authed_link" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_INFO; ?>">Profilim</a>
                    </li>
                    <li class="dropdown-action-item">
                        <a class="dropdown-action-link authed_link" href="<?php echo URL . URL_LOGOUT; ?>">Çıkış Yap</a>
                    </li>
                </ul>
            <?php else : ?>
                <a class="icon purgatory-icon action-open-icon not-auth" id="action-open-icon" href="<?php echo URL . URL_LOGIN; ?>">
                    <div class="triangle" id="action-triangle">
                        <div class="lower-triangle"></div>
                    </div>
                    <i class="fas fa-user"></i>
                </a>
                <ul id="action-options" class="action-options">
                    <li class="dropdown-action-item">
                        <a class="dropdown-action-link login-link" href="<?php echo URL . URL_LOGIN; ?>">Giriş Yap</a>
                    </li>
                    <li class="dropdown-action-item">
                        <a class="dropdown-action-link register-link" href="<?php echo URL . URL_REGISTER; ?>">Kayıt Ol</a>
                    </li>
                </ul>
            <?php endif; ?>
            <a class="icon purgatory-icon" href="<?php echo URL . URL_FAVORITES; ?>" title="Favorilerim"><i class="fas fa-heart"></i></a>
            <a id="header-cart-open-icon" class="icon purgatory-icon cart" href="<?php echo URL . URL_CART; ?>" title="Sepetim">
                <i class="fas fa-shopping-cart"></i>
                <?php if (!empty($web_data['cookie_cart_view'])) : ?>
                    <span class="cart-count"><?php echo count($web_data['cookie_cart_view']); ?></span>
                <?php endif; ?>
            </a>
            <?php if (!empty($web_data['cookie_cart_view'])) : ?>
                <?php if(!empty($_SESSION[SESSION_CART_SUCCESS])) : ?>
                    <div id="header-cart-container" class="cart-container">
                    <?php unset($_SESSION[SESSION_CART_SUCCESS]); ?>
                <?php else : ?>
                    <div id="header-cart-container" class="cart-container hidden">
                <?php endif; ?>
                    <div id="cart-close" class="cart-close-container">
                        <div class="cart-close">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                    <?php foreach ($web_data['cookie_cart_view'] as $cookie_cart_view) : ?>
                        <div class="cart-item-wrapper">
                            <div class="cart-item-container">
                                <a class="cart-item-link" href="<?php echo URL . URL_ITEM_DETAILS . '/' . $cookie_cart_view['item']['item_url']; ?>">
                                    <img class="cart-item-image" src="<?php echo URL . 'assets/images/items/' . $cookie_cart_view['item']['item_images_path'] . '/' . $cookie_cart_view['item']['item_images']; ?>" alt="<?php echo $cookie_cart_view['item']['item_name']; ?>">
                                </a>
                                <a class="cart-right" href="<?php echo URL . URL_ITEM_DETAILS . '/' . $cookie_cart_view['item']['item_url']; ?>">
                                    <span class="cart-item-name"><?php echo $cookie_cart_view['item']['item_name']; ?></span>
                                    <div class="cart-price-row">
                                        <span class="cart-item-old-price"><?php echo $cookie_cart_view['item']['item_price']; ?>₺</span>
                                        <span class="cart-item-new-price"><?php echo $cookie_cart_view['item']['item_discount_price']; ?>₺</span>
                                    </div>
                                    <span class="cart-item-size">Beden: <?php echo $cookie_cart_view['size']['size_name']; ?></span>
                                    <div class="cart-item-quantity">Adet: <?php echo $cookie_cart_view['quantity']; ?></div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <a href="<?php echo URL . URL_CART; ?>" class="cart-text">Sepete Git</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>