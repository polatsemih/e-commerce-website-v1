<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Sepet | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="cart-section container">
            <?php if (!empty($web_data['cart_data'])) : ?>
                <div class="row">
                    <div class="col-items">
                        <h1 class="title">Sepetim</h1>
                        <?php foreach ($web_data['cart_data'] as $cart_data) : ?>
                            <div class="cart-card">
                                <a href="<?php echo URL . URL_ITEM_DETAILS . '/' . $cart_data['item']['item_url']; ?>">
                                    <div class="details-row">
                                        <div class="cart-image-container">
                                            <img class="cart-item-image" src="<?php echo URL . 'assets/images/items/' . $cart_data['item']['item_images_path'] . '/' . $cart_data['item']['item_images']; ?>" alt="<?php echo $cart_data['item']['item_name']; ?>">
                                        </div>
                                        <div class="cart-item-name-row">
                                            <span class="cart-item-name"><?php echo $cart_data['item']['item_name']; ?></span>
                                            <span class="cart-item-size">Beden: <?php echo $cart_data['size']['size_name']; ?></span>
                                        </div>
                                    </div>
                                </a>
                                <div class="right-row">
                                    <div class="cart-item-price-row">
                                        <span class="cart-item-old-price"><?php echo $cart_data['item']['item_price']; ?> ₺</span>
                                        <span class="cart-item-new-price"><?php echo $cart_data['item']['item_discount_price']; ?> ₺</span>
                                    </div>
                                    <div class="cart-quantity-form-container">
                                        <form action="<?php echo URL . URL_UPDATE_CART; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                            <input type="hidden" name="item_size" value="<?php echo $cart_data['size']['size_cart_id']; ?>">
                                            <input type="hidden" name="item" value="<?php echo $cart_data['item']['item_cart_id']; ?>">
                                            <button class="header-cart-quantity-box header-cart-quantity-box-1" type="submit" name="decrease_cart_quantity"><i class="fas fa-minus icon"></i></button>
                                        </form>
                                        <div class="cart-item-quantity">Adet: <?php echo $cart_data['quantity']; ?></div>
                                        <form action="<?php echo URL . URL_UPDATE_CART; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                            <input type="hidden" name="item_size" value="<?php echo $cart_data['size']['size_cart_id']; ?>">
                                            <input type="hidden" name="item" value="<?php echo $cart_data['item']['item_cart_id']; ?>">
                                            <button class="header-cart-quantity-box header-cart-quantity-box-2" type="submit" name="increase_cart_quantity"><i class="fas fa-plus icon"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="row">
                            <div class="row-right">
                                <form action="<?php echo URL . URL_EMPTY_CART; ?>" method="POST">
                                    <input class="btn-empty-cart" type="submit" name="submit_empty_cart" value="Sepeti Boşalt">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-cart-price">
                        <?php if (WEB_SHOPPING_PERMISSION && !empty($web_data['cart_data_price']) && !empty($web_data['cart_data_total_price'])) : ?>
                            <div class="vertical">
                                <div class="selected-item">
                                    <span class="text">Seçilen Ürün Adedi: <?php echo count($web_data['cart_data']); ?></span>
                                </div>
                                <div class="price-infos">
                                    <span class="text">Ücret</span>
                                    <span class="text old-price"><?php echo $web_data['cart_data_price']; ?> ₺</span>
                                </div>
                                <div class="price-infos">
                                    <span class="text">İndirimli Ücret</span>
                                    <span class="text"><?php echo $web_data['cart_data_total_price']; ?> ₺</span>
                                </div>
                                <div class="price-infos">
                                    <span class="text">Kargo Ücreti</span>
                                    <span class="text">0 ₺</span>
                                </div>
                                <div class="price-infos">
                                    <span class="text">Toplam Ücret</span>
                                    <span class="text"><?php echo $web_data['cart_data_total_price']; ?> ₺</span>
                                </div>
                            </div>
                            <?php if (!empty($web_data['authenticated_user'])) : ?>
                                <a class="btn-submit-cart" href="<?php echo URL . URL_ORDER_INITIALIZE; ?>">Alışverişi Tamamla</a>
                            <?php else : ?>
                                <a class="btn-submit-cart" href="<?php echo URL . URL_LOGIN . '?yonlendir=' . URL_ORDER_INITIALIZE; ?>">Alışverişi Tamamla</a>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="cart-out-of-service">
                                <span class="text text-1">Alışveriş Hizmeti Devre Dışı</span>
                                <span class="text text-2">Teknik bir hatadan dolayı geçici süreliğine alışveriş hizmeti devre dışıdır. Sorundan haberdarız ve sorunun üzerinden çalışıyoruz. Anlayışınız ve sabrınız için teşekkür ederiz.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="notfound-container">
                    <span class="text">Sepetinizde Hiç Ürün Yok</span>
                    <a href="<?php echo URL; ?>" class="link">Ürünleri Görüntüle</a>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <script>
        $(document).ready(function() {
            var request;
            var requestUsable = true;
            var inputSearch = $('#input-search');
            var navSearch = $('.nav-search');
            var navSearchPopular = $('.nav-search-popular');
            inputSearch.on('input', function(e) {
                e.preventDefault();
                if (!$.trim(inputSearch.val())) {
                    $('#nav-search-wrapper').remove();
                    if (navSearchPopular.hasClass('hidden')) {
                        navSearchPopular.removeClass('hidden');
                    }
                    if (!navSearch.hasClass('hidden')) {
                        navSearch.addClass('hidden');
                    }
                } else if (requestUsable) {
                    requestUsable = false;
                    const formSearch = $('#form-search');
                    const inputsformSearch = formSearch.find('input');
                    request = $.ajax({
                        url: '<?php echo URL . URL_ITEM_SEARCH; ?>',
                        type: 'POST',
                        data: formSearch.serialize()
                    });
                    inputsformSearch.prop('disabled', true);
                    request.done(function(response) {
                        requestUsable = true;
                        if (!navSearchPopular.hasClass('hidden')) {
                            navSearchPopular.addClass('hidden');
                        }
                        if (navSearch.hasClass('hidden')) {
                            navSearch.removeClass('hidden');
                        }
                        response = jQuery.parseJSON(response);
                        if (response.hasOwnProperty('shutdown')) {
                            window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                        } else if (response.hasOwnProperty('exception')) {
                            window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                        } else if (response.hasOwnProperty('stop')) {

                        } else if (response.hasOwnProperty('not_found_search_item')) {
                            $('#nav-search-wrapper').remove();
                            let ss1 = $("<div></div>").attr('id', 'nav-search-wrapper');
                            let ss2 = $("<li></li>").addClass('search-item');
                            ss1.append(ss2);
                            let ss3 = $("<a></a>").addClass('not-found-search').text('Aranılan kriterde ürün bulunamadı');
                            ss2.append(ss3);
                            navSearch.append(ss1);
                        } else if (response.hasOwnProperty('searched_items')) {
                            $('#nav-search-wrapper').remove();
                            let s1 = $("<div></div>").attr('id', 'nav-search-wrapper');
                            $.each(response['searched_items'], function(key, searchitem) {
                                let s2 = $("<li></li>").addClass('search-item');
                                s1.append(s2);
                                let s3 = $("<a></a>").addClass('search-link').attr('href', '<?php echo URL . URL_ITEM_DETAILS . '/' ?>' + searchitem['item_url']).text(searchitem['item_name']);
                                s2.append(s3);
                            });
                            navSearch.append(s1);
                        }
                    });
                    request.always(function() {
                        inputsformSearch.prop('disabled', false);
                        inputSearch.focus();
                    });
                }
            });
        });
    </script>
</body>

</html>