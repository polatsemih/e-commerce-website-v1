<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo $web_data['selected_gender_name'] . ' | ' . BRAND; ?></title>
    <meta name="robots" content="all" />
    <meta name="description" content="<?php echo $web_data['selected_gender_description']; ?>" />
    <meta name="keywords" content="<?php echo $web_data['selected_gender_keywords']; ?>" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <div class="container">
            <section class="items-section">
                <button id="filters-toggle" class="filters-toggle"><i class="fas fa-sliders-h"></i>Filtreler</button>
                <div id="filters-wrapper" class="col-3 filters-wrapper">
                    <ul class="nav-filters">
                        <h2 class="title">Filtreler</h2>
                        <?php $has_filter = 0; ?>
                        <?php if (!empty($web_data['filter_categories'])) : ?>
                            <?php $has_filter = 1; ?>
                            <li class="item">
                                <div class="link dropdown-toggler<?php echo !empty($web_data['filter_categories']['selected']) ? ' active' : ''; ?>">
                                    <span class="link-title">Kategoriler</span>
                                    <i class="fas fa-angle-right dropdown-icon<?php echo !empty($web_data['filter_categories']['selected']) ? ' rotate' : ''; ?>"></i>
                                </div>
                                <ul class="dropdown-menu<?php echo !empty($web_data['filter_categories']['selected']) ? ' active' : ''; ?>">
                                    <?php foreach ($web_data['filter_categories']['categories'] as $category) : ?>
                                        <li>
                                            <form action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" id="form-<?php echo $category['category_url']; ?>" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                <label for="checkbox-<?php echo $category['category_url']; ?>">
                                                    <div class="link">
                                                        <?php if (!empty($web_data['get_hidden_inputs'])) : ?>
                                                            <?php foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : ?>
                                                                <?php if ($get_hidden_input_key !== 'kategori') : ?>
                                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        <input class="checkbox" type="checkbox" id="checkbox-<?php echo $category['category_url']; ?>" name="kategori" value="<?php echo $category['category_url']; ?>" <?php echo !empty($category['selected']) ? ' checked' : ''; ?>>
                                                        <span class="checkmark"></span>
                                                        <span class="checkmark-text"><?php echo $category['category_name']; ?></span>
                                                    </div>
                                                </label>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($web_data['filter_colors'])) : ?>
                            <?php $has_filter = 1; ?>
                            <li class="item">
                                <div class="link dropdown-toggler<?php echo !empty($web_data['filter_colors']['selected']) ? ' active' : ''; ?>">
                                    <span class="link-title">Renkler</span>
                                    <i class="fas fa-angle-right dropdown-icon<?php echo !empty($web_data['filter_colors']['selected']) ? ' rotate' : ''; ?>"></i>
                                </div>
                                <ul class="dropdown-menu<?php echo !empty($web_data['filter_colors']['selected']) ? ' active' : ''; ?>">
                                    <?php foreach ($web_data['filter_colors']['colors'] as $color) : ?>
                                        <li>
                                            <form action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" id="form-<?php echo $color['color_url']; ?>" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                <label for="checkbox-<?php echo $color['color_url']; ?>">
                                                    <div class="link">
                                                        <?php if (!empty($web_data['get_hidden_inputs'])) : ?>
                                                            <?php foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : ?>
                                                                <?php if ($get_hidden_input_key !== 'renk') : ?>
                                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        <input class="checkbox" type="checkbox" id="checkbox-<?php echo $color['color_url']; ?>" name="renk" value="<?php echo $color['color_url']; ?>" <?php echo !empty($color['selected']) ? ' checked' : ''; ?>>
                                                        <span class="checkmark"></span>
                                                        <span class="checkmark-text"><?php echo $color['color_name']; ?></span>
                                                    </div>
                                                </label>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($web_data['filter_sizes'])) : ?>
                            <?php $has_filter = 1; ?>
                            <li class="item">
                                <div class="link dropdown-toggler<?php echo !empty($web_data['filter_sizes']['selected']) ? ' active' : ''; ?>">
                                    <span class="link-title">Bedenler</span>
                                    <i class="fas fa-angle-right dropdown-icon<?php echo !empty($web_data['filter_sizes']['selected']) ? ' rotate' : ''; ?>"></i>
                                </div>
                                <ul class="dropdown-menu<?php echo !empty($web_data['filter_sizes']['selected']) ? ' active' : ''; ?>">
                                    <?php foreach ($web_data['filter_sizes']['sizes'] as $size) : ?>
                                        <li>
                                            <form action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" id="form-<?php echo $size['size_url']; ?>" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                <label for="checkbox-<?php echo $size['size_url']; ?>">
                                                    <div class="link">
                                                        <?php if (!empty($web_data['get_hidden_inputs'])) : ?>
                                                            <?php foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : ?>
                                                                <?php if ($get_hidden_input_key !== 'beden') : ?>
                                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        <input class="checkbox" type="checkbox" id="checkbox-<?php echo $size['size_url']; ?>" name="beden" value="<?php echo $size['size_url']; ?>" <?php echo !empty($size['selected']) ? ' checked' : ''; ?>>
                                                        <span class="checkmark"></span>
                                                        <span class="checkmark-text"><?php echo $size['size_name']; ?></span>
                                                    </div>
                                                </label>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($web_data['filter_prices'])) : ?>
                            <?php $has_filter = 1; ?>
                            <li class="item">
                                <div class="link dropdown-toggler<?php echo !empty($web_data['filter_prices']['selected']) ? ' active' : ''; ?>">
                                    <span class="link-title">Fiyatlar</span>
                                    <i class="fas fa-angle-right dropdown-icon<?php echo !empty($web_data['filter_prices']['selected']) ? ' rotate' : ''; ?>"></i>
                                </div>
                                <ul class="dropdown-menu<?php echo !empty($web_data['filter_prices']['selected']) ? ' active' : ''; ?>">
                                    <?php foreach ($web_data['filter_prices']['prices'] as $price) : ?>
                                        <li>
                                            <form action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" id="form-<?php echo $price['min_price_url']; ?>" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                <label for="checkbox-<?php echo $price['min_price_url']; ?>">
                                                    <div class="link">
                                                        <?php if (!empty($web_data['get_hidden_inputs'])) : ?>
                                                            <?php foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : ?>
                                                                <?php if ($get_hidden_input_key !== 'min-fiyat' && $get_hidden_input_key !== 'max-fiyat') : ?>
                                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        <input class="checkbox" type="checkbox" id="checkbox-<?php echo $price['min_price_url']; ?>" name="min-fiyat" value="<?php echo $price['min_price_url']; ?>" <?php echo !empty($price['selected']) ? ' checked' : ''; ?>>
                                                        <input type="hidden" name="max-fiyat" value="<?php echo $price['max_price_url']; ?>">
                                                        <span class="checkmark"></span>
                                                        <span class="checkmark-text"><?php echo $price['price_name']; ?></span>
                                                    </div>
                                                </label>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                    <form class="price-text-container" action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                        <?php if (!empty($web_data['get_hidden_inputs'])) : ?>
                                            <?php foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : ?>
                                                <?php if ($get_hidden_input_key !== 'min-fiyat' && $get_hidden_input_key !== 'max-fiyat') : ?>
                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <input class="price-input" type="text" name="min-fiyat" placeholder="Min. Fiyat ₺" title="Minumum Fiyat">
                                        <input class="price-input" type="text" name="max-fiyat" placeholder="Max. Fiyat ₺" title="Maximum Fiyat">
                                        <button class="price-submit" type="submit" title="Fiyat Aralığına Göre Filtrele"><i class="fas fa-search"></i></button>
                                    </form>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-9">
                    <?php if (!empty($web_data['selected_filters'])) : ?>
                        <div class="selected-filter">
                            <span class="text">Seçilen Filtreler</span>
                            <?php foreach ($web_data['selected_filters'] as $selected_filter_name => $selected_filter) : ?>
                                <a class="box" href="<?php echo $selected_filter['url']; ?>">
                                    <span class="selected"><?php echo $selected_filter_name . ': ' . $selected_filter['name']; ?></span>
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endforeach; ?>
                            <a class="box" href="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>">
                                <span class="selected">Filtreleri Temizle</span>
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($web_data['items_not_found'])) : ?>
                        <div class="not-found">
                            <span class="text">Stokta Ürün Bulunamadı</span>
                        </div>
                    <?php else : ?>
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
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <?php if (!empty($has_filter) && $has_filter == 1) : ?>
        <script>
            const dropdownTogglers = document.querySelectorAll('.dropdown-toggler');
            const dropdownIcon = document.querySelectorAll('.dropdown-icon');
            const dropdownMenus = document.querySelectorAll('.dropdown-menu');
            dropdownTogglers.forEach((toggler, i) => {
                toggler.addEventListener('click', () => {
                    dropdownIcon[i].classList.toggle('rotate');
                    dropdownMenus[i].classList.toggle('active');
                    dropdownTogglers[i].classList.toggle('active');
                });
            });
            document.getElementById('filters-toggle').addEventListener('click', () => {
                document.getElementById('filters-wrapper').classList.toggle('active');
            });
            <?php if (!empty($web_data['filter_categories'])) : ?>
                <?php foreach ($web_data['filter_categories']['categories'] as $category) : ?>
                    document.getElementById('checkbox-<?php echo $category['category_url']; ?>').addEventListener('change', (e) => {
                        e.preventDefault();
                        if (document.getElementById('checkbox-<?php echo $category['category_url']; ?>').checked == true) {
                            document.getElementById('form-<?php echo $category['category_url']; ?>').submit();
                        } else {
                            <?php if (!empty($web_data['selected_filters'])) : ?>
                                <?php foreach ($web_data['selected_filters'] as $selected_filter) : ?>
                                    <?php if ($selected_filter['url_name'] == $category['category_url']) : ?>
                                        window.location.href = '<?php echo $selected_filter['url']; ?>';
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        }
                    });
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($web_data['filter_colors'])) : ?>
                <?php foreach ($web_data['filter_colors']['colors'] as $color) : ?>
                    document.getElementById('checkbox-<?php echo $color['color_url']; ?>').addEventListener('change', (e) => {
                        e.preventDefault();
                        if (document.getElementById('checkbox-<?php echo $color['color_url']; ?>').checked == true) {
                            document.getElementById('form-<?php echo $color['color_url']; ?>').submit();
                        } else {
                            <?php if (!empty($web_data['selected_filters'])) : ?>
                                <?php foreach ($web_data['selected_filters'] as $selected_filter) : ?>
                                    <?php if ($selected_filter['url_name'] == $color['color_url']) : ?>
                                        window.location.href = '<?php echo $selected_filter['url']; ?>';
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        }
                    });
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($web_data['filter_sizes'])) : ?>
                <?php foreach ($web_data['filter_sizes']['sizes'] as $size) : ?>
                    document.getElementById('checkbox-<?php echo $size['size_url']; ?>').addEventListener('change', (e) => {
                        e.preventDefault();
                        if (document.getElementById('checkbox-<?php echo $size['size_url']; ?>').checked == true) {
                            document.getElementById('form-<?php echo $size['size_url']; ?>').submit();
                        } else {
                            <?php if (!empty($web_data['selected_filters'])) : ?>
                                <?php foreach ($web_data['selected_filters'] as $selected_filter) : ?>
                                    <?php if ($selected_filter['url_name'] == $size['size_url']) : ?>
                                        window.location.href = '<?php echo $selected_filter['url']; ?>';
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        }
                    });
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($web_data['filter_prices'])) : ?>
                <?php foreach ($web_data['filter_prices']['prices'] as $price) : ?>
                    document.getElementById('checkbox-<?php echo $price['min_price_url']; ?>').addEventListener('change', (e) => {
                        e.preventDefault();
                        if (document.getElementById('checkbox-<?php echo $price['min_price_url']; ?>').checked == true) {
                            document.getElementById('form-<?php echo $price['min_price_url']; ?>').submit();
                        } else {
                            <?php if (!empty($web_data['selected_filters'])) : ?>
                                <?php foreach ($web_data['selected_filters'] as $selected_filter) : ?>
                                    <?php if ($selected_filter['url_name'] == $price['min_price_url']) : ?>
                                        window.location.href = '<?php echo $selected_filter['url']; ?>';
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        }
                    });
                <?php endforeach; ?>
            <?php endif; ?>
        </script>
    <?php endif; ?>
    <?php if (empty($web_data['items_not_found'])) : ?>
        <script>
            $(document).ready(function() {
                var colorArray = [];
                <?php if (!empty($web_data['filter_colors'])) : ?>
                    <?php foreach ($web_data['filter_colors']['colors'] as $color) : ?>
                        colorArray.push({
                            'url': '<?php echo $color['color_url'] ?>',
                            'hex': '<?php echo $color['color_hex'] ?>'
                        });
                    <?php endforeach; ?>
                <?php endif; ?>
                function setHomeItems(newitems) {
                    $.each(newitems, function(key, newitem) {
                        let x1 = $("<div></div>").addClass('col-items');
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
                        let x7 = $("<span></span>").addClass('card-text').attr('title', 'Ürünün Adı').text(newitem['item_name']);
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
                        let x13 = $("<div></div>").addClass('card-color-infos').attr('title', 'Ürüne Ait Renkler');
                        x12.append(x13);
                        if (colorArray.length > 0) {
                            let x14;
                            $.each(colorArray, function(key, colorJq) {
                                if (typeof(newitem[colorJq['url']]) != "undefined" && newitem[colorJq['url']] !== null && newitem[colorJq['url']] == 1) {
                                    x14 = $("<span></span>").addClass('card-color').attr('style', 'background-color: ' + colorJq['hex']);
                                    x13.append(x14);
                                }
                            });
                        }
                        let x15 = $("<span></span>").addClass('card-go-details').attr('title', 'Ürünün Detayları').attr('href', '<?php echo URL . URL_ITEM_DETAILS . '/'; ?>' + newitem['item_url']).text('Detaylar');
                        x12.append(x15);
                        let x16 = $("<i></i>").addClass('fas fa-angle-right');
                        x15.append(x16);
                        $('.row-items').append(x1);                 
                    });
                }
                setHomeItems(<?php echo $web_data['items']; ?>);
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
                            url: '<?php echo $web_data['home_jquery_url']; ?>',
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
                            if (response.hasOwnProperty('not_found_search_item')) {
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