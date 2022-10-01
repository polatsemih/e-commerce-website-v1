<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Favoriler | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="favorite-section container">
            <h1 class="favorites-title">Favorilerim</h1>
            <?php if (!empty($web_data['authenticated_user'])) : ?>
                <?php if (!empty($web_data['favorite_items'])) : ?>
                    <div class="favorite-items-wrapper">
                        <div class="row-items"></div>
                        <div id="items-loading" class="items-loading-wrapper disable">
                            <div class="items-loading-container">
                                <div class="circle-row">
                                    <div class="circle circle-1"></div>
                                    <div class="circle circle-2"></div>
                                    <div class="circle circle-3"></div>
                                </div>
                                <div class="shadow shadow-1"></div>
                                <div class="shadow shadow-2"></div>
                                <div class="shadow shadow-3"></div>
                                <span class="text">Sıradaki Ürünler Yükleniyor</span>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="favorite-card">
                        <span class="text">Favorilere eklenmiş ürün yok</span>
                        <div class="row">
                            <a class="cart-notfound-link" href="<?php echo URL; ?>">Ürünleri Görüntüle</a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="favorite-card">
                    <span class="text">Favorilere ürün ekleyebilmek için giriş yapmalısınız</span>
                    <div class="row">
                        <a class="action login" href="<?php echo URL . URL_LOGIN; ?>">Giriş Yap</a>
                        <a class="action register" href="<?php echo URL . URL_REGISTER; ?>">Kayıt Ol</a>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <?php if (!empty($web_data['favorite_items'])) : ?>
        <script>
            $(document).ready(function() {
                var notificationClient = $('.notification-client');
                var notificationHidden = 0;
                var notificationRemoved = 0;

                function setClientNotification(notificationMessage) {
                    clearTimeout(notificationHidden);
                    clearTimeout(notificationRemoved);
                    notificationClient.html(notificationMessage);
                    if (notificationClient.hasClass('hidden')) {
                        notificationClient.removeClass('hidden');
                    }
                    if (notificationClient.hasClass('removed')) {
                        notificationClient.removeClass('removed');
                    }
                    notificationHidden = setTimeout(() => {
                        if (!notificationClient.hasClass('hidden')) {
                            notificationClient.addClass('hidden');
                        }
                        notificationRemoved = setTimeout(() => {
                            if (!notificationClient.hasClass('removed')) {
                                notificationClient.addClass('removed');
                            }
                        }, 1500);
                    }, 10000);
                }

                function setHomeItems(newitems) {
                    $.each(newitems, function(key, newitem) {
                        let x1 = $("<div></div>").attr('id', 'card-item' + newitem['item_cart_id']).addClass('col-items');
                        let x2 = $("<a></a>").attr('href', '<?php echo URL . URL_ITEM_DETAILS . '/'; ?>' + newitem['item_url']);
                        x1.append(x2);
                        let x3 = $("<div></div>").addClass('card-item');
                        x2.append(x3);
                        let x4 = $("<div></div>").addClass('card-image-container');
                        x3.append(x4);
                        let x5 = $("<img></img>").addClass('card-image').attr('src', '<?php echo URL; ?>' + 'assets/images/items/' + newitem['item_images_path'] + '/' + newitem['item_images']).attr('alt', newitem['item_name'] + ' Görseli');
                        x4.append(x5);
                        let x6 = $("<div></div>").addClass('card-infos');
                        x3.append(x6);
                        let x7 = $("<span></span>").addClass('card-text').attr('title', 'Ürünün Adı').append(newitem['item_name']);
                        x6.append(x7);
                        let x8 = $("<div></div>").addClass('row-price');
                        x6.append(x8);
                        let x9 = $("<div></div>").addClass('left');
                        x8.append(x9);
                        let x10 = $("<span></span>").addClass('card-price card-old-price').attr('title', 'Ürünün Eski Fiyatı').text(newitem['item_price'] + ' ₺');
                        x9.append(x10);
                        let x11 = $("<span></span>").addClass('card-price card-new-price').attr('title', 'Ürünün İndirimli Güncel Fiyatı').text(newitem['item_discount_price'] + ' ₺');
                        x9.append(x11);
                        let x12 = $("<div></div>").addClass('card-infos-bot');
                        x3.append(x12);
                        let x13 = $("<div></div>").addClass('remove-favorite');
                        x12.append(x13);
                        let xx14 = $("<form></form>").attr('id', 'form-remove-from-favorites' + newitem['item_cart_id']);
                        x13.append(xx14);
                        let xx15 = $("<input></input>").attr('type', 'hidden').attr('name', 'item').attr('value', newitem['item_cart_id']);
                        xx14.append(xx15);
                        let xx16 = $("<button></button>").addClass('btn-remove-from-favorites').attr('type', 'submit').attr('id', 'submit-remove-from-favorites' + newitem['item_cart_id']).attr('title', 'Ürünü favorilerimden kaldır');
                        xx14.append(xx16);
                        let xx17 = $("<i></i>").addClass('far fa-heart details-favorites-icon selected');
                        xx16.append(xx17);
                        let x15 = $("<span></span>").addClass('card-go-details').attr('title', 'Ürünün Detayları').attr('href', '<?php echo URL . URL_ITEM_DETAILS . '/'; ?>' + newitem['item_url']).text('Detaylar');
                        x12.append(x15);
                        let x16 = $("<i></i>").addClass('fas fa-angle-right');
                        x15.append(x16);
                        $('.row-items').append(x1);
                        $('#submit-remove-from-favorites' + newitem['item_cart_id']).click(function(e) {
                            e.preventDefault();
                            if (requestUsable) {
                                requestUsable = false;
                                const formRemoveFromFavorites = $('#form-remove-from-favorites' + newitem['item_cart_id']);
                                const inputsformRemoveFromFavorites = formRemoveFromFavorites.find('input, button');
                                request = $.ajax({
                                    url: '<?php echo URL . URL_REMOVE_FAVORITE; ?>',
                                    type: 'POST',
                                    data: formRemoveFromFavorites.serialize()
                                });
                                inputsformRemoveFromFavorites.prop('disabled', true);
                                request.done(function(response) {
                                    requestUsable = true;
                                    response = jQuery.parseJSON(response);
                                    if (response.hasOwnProperty('reset') && response['reset'] == false) {
                                        if (response.hasOwnProperty('notification')) {
                                            setClientNotification(response['notification']);
                                        }
                                        $('#card-item' + newitem['item_cart_id']).remove();
                                        if ($('.row-items').children().length == 0) {
                                            $('.favorite-items-wrapper').remove();
                                            let yy1 = $("<div></div>").addClass('favorite-card');
                                            let yy2 = $("<span></span>").addClass('text').text('Favorilere eklenmiş ürün yok');
                                            yy1.append(yy2);
                                            let yy3 = $("<div></div>").addClass('row');
                                            yy1.append(yy3);
                                            let yy4 = $("<a></a>").addClass('cart-notfound-link').attr('href', '<?php echo URL; ?>').text('Ürünleri Görüntüle');
                                            yy3.append(yy4);
                                            $('.favorite-section').append(yy1);
                                        }
                                    } else {
                                        window.location.href = resetLocation;
                                    }
                                });
                                request.always(function() {
                                    inputsformRemoveFromFavorites.prop('disabled', false);
                                });
                            }
                        });
                    });
                }
                setHomeItems(<?php echo $web_data['favorite_items']; ?>);
                var offSetItem = <?php echo ITEM_LOAD_LIMIT_IN_ONCE; ?>;
                var request;
                var requestUsable = true;
                var requestScroll = true;
                var itemsLoadingWrapper = $('#items-loading');
                $(window).scroll(function() {
                    if ($(window).scrollTop() + $(window).height() > itemsLoadingWrapper.offset().top - 200 && requestUsable && requestScroll) {
                        requestUsable = false;
                        requestScroll = false;
                        if (itemsLoadingWrapper.hasClass('disable')) {
                            itemsLoadingWrapper.removeClass('disable')
                        }
                        request = $.ajax({
                            url: URL.URL_FAVORITES,
                            type: 'POST',
                            data: {
                                loadedItemOffSet: offSetItem
                            }
                        });
                        request.done(function(response) {
                            requestUsable = true;
                            response = jQuery.parseJSON(response);
                            if (!response.hasOwnProperty('stop')) {
                                setHomeItems(response);
                                offSetItem += <?php echo ITEM_LOAD_LIMIT_IN_ONCE; ?>;
                                requestScroll = true;
                            }
                        });
                        request.always(function() {
                            if (!itemsLoadingWrapper.hasClass('disable')) {
                                itemsLoadingWrapper.addClass('disable')
                            }
                        });
                    }
                });
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
    <?php endif; ?>
    <?php if (!empty($web_data['cookie_cart'])) : ?>
        <script src="<?php echo URL; ?>assets/js/header_cart.js"></script>
    <?php endif; ?>
</body>

</html>