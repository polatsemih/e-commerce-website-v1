<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo BRAND; ?></title>
    <meta name="robots" content="all" />
    <meta name="description" content="<?php echo BRAND; ?>'in modern koleksiyonu ile tarzını yenile. En trend tasarımlar ücretsiz kargo ile seni bekliyor. Hemen incelemeye başla!" />
    <meta name="keywords" content="blanck basic, blnckk, hoodie, merch" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <div class="container">
            <section class="section-home">
                <div class="row">
                    <?php foreach ($web_data['genders'] as $gender) : ?>
                        <a href="<?php echo URL . URL_ITEMS . '/' . $gender['gender_url']; ?>">
                            <div class="gender-wrapper">
                                <img class="gender-image" src="<?php echo URL; ?>assets/images/home/<?php echo $gender['gender_home_image']; ?>" alt="<?php echo $gender['gender_name']; ?>">
                                <span class="gender-text"><?php echo $gender['gender_name']; ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php if (!empty($web_data['home_items'])) : ?>
                <section class="section-home-items">
                    <h1 class="main-title"><?php echo BRAND; ?> Öne Çıkan Ürünler</h1>
                    <div class="row">
                        <span id="arrow-left" class="arrow arrow-left"><i class="fas fa-chevron-left icon"></i></span>
                        <span id="arrow-right" class="arrow arrow-right"><i class="fas fa-chevron-right icon"></i></span>
                        <?php foreach ($web_data['home_items'] as $home_item) : ?>
                            <div class="col">
                                <a href="<?php echo URL . URL_ITEM_DETAILS . '/' . $home_item['item_url']; ?>">
                                    <div class="card-item">
                                        <div class="card-image-container">
                                            <img class="card-image" src="<?php echo URL . 'assets/images/items/' . $home_item['item_images_path'] . '/' . $home_item['item_images']; ?>" alt="<?php echo $home_item['item_name']; ?>">
                                        </div>
                                        <div class="card-infos">
                                            <span class="card-text" title="Ürünün Adı"><?php echo $home_item['item_name']; ?></span>
                                            <div class="row-price">
                                                <div class="left">
                                                    <span class="card-price card-old-price" title="Ürünün Eski Fiyatı"><?php echo $home_item['item_price']; ?> ₺</span>
                                                    <span class="card-price card-new-price" title="Ürünün İndirimli Güncel Fiyatı"><?php echo $home_item['item_discount_price']; ?> ₺</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-go-details-container">
                                            <span class="card-go-details" title="Ürünün Detayları" href="<?php echo URL . URL_ITEM_DETAILS . '/' . $home_item['item_url']; ?>">Detaylar<i class="fas fa-angle-right"></i></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <?php if (!empty($web_data['home_items'])) : ?>
        <script src="<?php echo URL; ?>assets/js/item_slider.js"></script>
    <?php endif; ?>
    <?php if (!empty($web_data['cookie_cart'])) : ?>
        <script src="<?php echo URL; ?>assets/js/header_cart.js"></script>
        <?php if (!empty($_SESSION[SESSION_CART_SUCCESS])) : ?>
            <script>
                if (!headerCartOpenIcon.classList.contains('scaled')) {
                    headerCartOpenIcon.classList.add('scaled');
                }
                if (headerCartContainer.classList.contains('hidden')) {
                    headerCartContainer.classList.remove('hidden');
                }
            </script>
            <?php unset($_SESSION[SESSION_CART_SUCCESS]); ?>
        <?php endif; ?>
    <?php endif; ?>
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
                                let s3 = $("<a></a>").addClass('search-link').attr('href', '<?php echo URL . URL_ITEM_DETAILS . '/' ?>' + searchitem['item_url']).append(searchitem['item_name']);
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