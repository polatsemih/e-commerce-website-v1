<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo !empty($web_data['selected_gender_name']) ? $web_data['selected_gender_name'] . ' | ' : '';
            echo BRAND; ?></title>
    <meta name="description" content="koleksiyonu ile tarzını yenile. En trend tasarımlar." />
    <meta name="keywords" content="blanck, basic, blnckk" />
    <?php require 'View/SharedHome/_home_head.php'; ?>
</head>

<body>
    <?php require 'View/SharedCommon/_common_loader.php'; ?>
    <header>
        <?php require 'View/SharedHome/_home_body.php'; ?>
        <?php require 'View/SharedHome/_home_search.php'; ?>
    </header>
    <main>
        <div class="notification">
            <?php if (isset($_SESSION[SESSION_NOTIFICATION])) {
                echo $_SESSION[SESSION_NOTIFICATION];
                unset($_SESSION[SESSION_NOTIFICATION]);
            } ?>
        </div>
        <section class="items-section container">
            <div class="row-space">
                <button class="filters-toggle"><i class="fas fa-sliders-h filters-toggle-icon"></i>Filtreler</button>
                <div class="col-3 filters">
                    <ul class="nav-filters">
                        <h3 class="filters-title">Filtreler</h3>
                        <?php if (!empty($web_data['filter_categories'])) : ?>
                            <li class="filter-item">
                                <div class="dropdown-toggler filters-link<?php echo !empty($web_data['filter_categories']['selected']) ? ' active' : ''; ?>">
                                    <span class="row-left filter-link-title">Kategoriler</span>
                                    <i class="row-right dropdown-icon fas fa-angle-right<?php echo !empty($web_data['filter_categories']['selected']) ? ' rotate' : ''; ?>"></i>
                                </div>
                                <ul class="dropdown-menu<?php echo !empty($web_data['filter_categories']['selected']) ? ' active' : ''; ?>">
                                    <?php foreach ($web_data['filter_categories']['categories'] as $category) : ?>
                                        <li>
                                            <form action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" id="form-<?php echo $category['category_url']; ?>" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                <label for="checkbox-<?php echo $category['category_url']; ?>">
                                                    <div class="filters-link">
                                                        <?php if (!empty($web_data['get_hidden_inputs'])) : foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : if ($get_hidden_input_key !== 'kategori') : ?>
                                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                                        <?php endif;
                                                            endforeach;
                                                        endif; ?>
                                                        <input class="checkbox" type="checkbox" id="checkbox-<?php echo $category['category_url']; ?>" name="kategori" value="<?php echo $category['category_url']; ?>" <?php echo !empty($category['selected']) ? ' checked' : ''; ?>>
                                                        <span class="checkmark-filter"></span>
                                                        <span class="checkmark-filter-text"><?php echo $category['category_name']; ?></span>
                                                    </div>
                                                </label>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($web_data['filter_colors'])) : ?>
                            <li class="filter-item">
                                <div class="dropdown-toggler filters-link<?php echo !empty($web_data['filter_colors']['selected']) ? ' active' : ''; ?>">
                                    <span class="row-left filter-link-title">Renkler</span>
                                    <i class="row-right dropdown-icon fas fa-angle-right<?php echo !empty($web_data['filter_colors']['selected']) ? ' rotate' : ''; ?>"></i>
                                </div>
                                <ul class="dropdown-menu<?php echo !empty($web_data['filter_colors']['selected']) ? ' active' : ''; ?>">
                                    <?php foreach ($web_data['filter_colors']['colors'] as $color) : ?>
                                        <li>
                                            <form action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" id="form-<?php echo $color['color_url']; ?>" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                <label for="checkbox-<?php echo $color['color_url']; ?>">
                                                    <div class="filters-link">
                                                        <?php if (!empty($web_data['get_hidden_inputs'])) : foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : if ($get_hidden_input_key !== 'renk') : ?>
                                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                                        <?php endif;
                                                            endforeach;
                                                        endif; ?>
                                                        <input class="checkbox" type="checkbox" id="checkbox-<?php echo $color['color_url']; ?>" name="renk" value="<?php echo $color['color_url']; ?>" <?php echo !empty($color['selected']) ? ' checked' : ''; ?>>
                                                        <span class="checkmark-filter"></span>
                                                        <span class="checkmark-filter-text"><?php echo $color['color_name']; ?></span>
                                                    </div>
                                                </label>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($web_data['filter_sizes'])) : ?>
                            <li class="filter-item">
                                <div class="dropdown-toggler filters-link<?php echo !empty($web_data['filter_sizes']['selected']) ? ' active' : ''; ?>">
                                    <span class="row-left filter-link-title">Bedenler</span>
                                    <i class="row-right dropdown-icon fas fa-angle-right<?php echo !empty($web_data['filter_sizes']['selected']) ? ' rotate' : ''; ?>"></i>
                                </div>
                                <ul class="dropdown-menu<?php echo !empty($web_data['filter_sizes']['selected']) ? ' active' : ''; ?>">
                                    <?php foreach ($web_data['filter_sizes']['sizes'] as $size) : ?>
                                        <li>
                                            <form action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" id="form-<?php echo $size['size_url']; ?>" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                <label for="checkbox-<?php echo $size['size_url']; ?>">
                                                    <div class="filters-link">
                                                        <?php if (!empty($web_data['get_hidden_inputs'])) : foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : if ($get_hidden_input_key !== 'beden') : ?>
                                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                                        <?php endif;
                                                            endforeach;
                                                        endif; ?>
                                                        <input class="checkbox" type="checkbox" id="checkbox-<?php echo $size['size_url']; ?>" name="beden" value="<?php echo $size['size_url']; ?>" <?php echo !empty($size['selected']) ? ' checked' : ''; ?>>
                                                        <span class="checkmark-filter"></span>
                                                        <span class="checkmark-filter-text"><?php echo $size['size_name']; ?></span>
                                                    </div>
                                                </label>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($web_data['filter_prices'])) : ?>
                            <li class="filter-item">
                                <div class="dropdown-toggler filters-link<?php echo !empty($web_data['filter_prices']['selected']) ? ' active' : ''; ?>">
                                    <span class="row-left filter-link-title">Fiyatlar</span>
                                    <i class="row-right dropdown-icon fas fa-angle-right<?php echo !empty($web_data['filter_prices']['selected']) ? ' rotate' : ''; ?>"></i>
                                </div>
                                <ul class="dropdown-menu<?php echo !empty($web_data['filter_prices']['selected']) ? ' active' : ''; ?>">
                                    <?php foreach ($web_data['filter_prices']['prices'] as $price) : ?>
                                        <li>
                                            <form action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" id="form-<?php echo $price['min_price_url']; ?>" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                                <label for="checkbox-<?php echo $price['min_price_url']; ?>">
                                                    <div class="filters-link">
                                                        <?php if (!empty($web_data['get_hidden_inputs'])) : foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : if ($get_hidden_input_key !== 'min-fiyat' && $get_hidden_input_key !== 'max-fiyat') : ?>
                                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                                        <?php endif;
                                                            endforeach;
                                                        endif; ?>
                                                        <input class="checkbox" type="checkbox" id="checkbox-<?php echo $price['min_price_url']; ?>" name="min-fiyat" value="<?php echo $price['min_price_url']; ?>" <?php echo !empty($price['selected']) ? ' checked' : ''; ?>>
                                                        <input type="hidden" name="max-fiyat" value="<?php echo $price['max_price_url']; ?>">
                                                        <span class="checkmark-filter"></span>
                                                        <span class="checkmark-filter-text"><?php echo $price['price_name']; ?></span>
                                                    </div>
                                                </label>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                    <form class="price-text-container" action="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" method="GET" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                        <?php if (!empty($web_data['get_hidden_inputs'])) : foreach ($web_data['get_hidden_inputs'] as $get_hidden_input_key => $get_hidden_input) : if ($get_hidden_input_key !== 'min-fiyat' && $get_hidden_input_key !== 'max-fiyat') : ?>
                                                    <input type="hidden" name="<?php echo $get_hidden_input_key; ?>" value="<?php echo $get_hidden_input; ?>">
                                        <?php endif;
                                            endforeach;
                                        endif; ?>
                                        <input class="price-input" type="text" name="min-fiyat" placeholder="Min. Fiyat ₺" title="Minumum Fiyat">
                                        <input class="price-input" type="text" name="max-fiyat" placeholder="Max. Fiyat ₺" title="Maximum Fiyat">
                                        <input class="price-submit" type="submit" value="Filtrele" title="Fiyat Aralığına Göre Filtrele">
                                    </form>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-9 items">
                    <?php if (!empty($web_data['selected_filters'])) : ?>
                        <div class="selected-filter-container">
                            <span class="selected-filter-text">Seçilen Filtreler</span>
                            <?php foreach ($web_data['selected_filters'] as $selected_filter_name => $selected_filter) : ?>
                                <a class="filter-box" href="<?php echo $selected_filter['url']; ?>">
                                    <span class="selected-filter"><?php echo $selected_filter_name . ': ' . $selected_filter['name']; ?></span>
                                    <i class="fas fa-times filter-remove"></i>
                                </a>
                            <?php endforeach; ?>
                            <a class="filter-box" href="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>">
                                <span class="selected-filter">Filtreleri Temizle</span>
                                <i class="fas fa-times filter-remove"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($web_data['items_not_found'])) : ?>
                        <div class="not-found">
                            <div class="not-found-container">
                                <span class="not-found-text">Stokta Ürün Bulunamadı</span>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="row-items">
                            <?php foreach ($web_data['items'] as $item) : ?>
                                <div class="col-items">
                                    <a href="<?php echo URL . URL_ITEM_DETAILS . '/' . $item['item_url']; ?>">
                                        <div class="card-item">
                                            <div class="card-image-container">
                                                <img class="card-image" src="<?php echo !empty($item['item_images']) ? URL . 'assets/images/items/' . $item['item_images_path'] . '/' . $item['item_images'] : ''; ?>" alt="<?php echo $item['item_name']; ?> Görseli">
                                            </div>
                                            <div class="card-infos">
                                                <span class="card-text" title="Ürünün Adı"><?php echo $item['item_name']; ?></span>
                                                <div class="row">
                                                    <div class="price-container row-right">
                                                        <span class="card-price card-old-price" title="Ürünün Eski Fiyatı"><?php echo $item['item_price']; ?> ₺</span>
                                                        <span class="card-price card-new-price" title="Ürünün İndirimli Güncel Fiyatı"><?php echo $item['item_discount_price']; ?> ₺</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-infos-bot">
                                                <div class="card-color-infos" title="Ürüne Ait Renk Çeşitleri">
                                                    <?php if (!empty($web_data['filter_colors']['colors'])) : foreach ($web_data['filter_colors']['colors'] as $color) : if (!empty($item[$color['color_url']])) : ?>
                                                                <span class="card-color" style="background-color: <?php echo $color['color_hex']; ?>;"></span>
                                                    <?php endif;
                                                        endforeach;
                                                    endif; ?>
                                                </div>
                                                <span class="card-go-details" title="Ürünün Detayları" href="<?php echo URL . URL_ITEM_DETAILS . '/' . $item['item_url']; ?>">Detaylar<i class="fas fa-angle-right card-details-icon"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php require 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/home.js"></script>
    <script src="<?php echo URL; ?>assets/js/header.js"></script>
    <script src="<?php echo URL; ?>assets/js/filters.js"></script>
    <script>
        <?php foreach ($web_data['filter_categories']['categories'] as $category) : ?>
            document.querySelector('#checkbox-<?php echo $category['category_url']; ?>').addEventListener('change', () => {
                if (document.querySelector('#checkbox-<?php echo $category['category_url']; ?>').checked == true) {
                    document.querySelector('#form-<?php echo $category['category_url']; ?>').submit();
                } else {
                    <?php if (!empty($web_data['selected_filters'])) : foreach ($web_data['selected_filters'] as $selected_filter) : if ($selected_filter['url_name'] == $category['category_url']) : ?>
                                window.location.href = '<?php echo $selected_filter['url']; ?>';
                    <?php endif;
                        endforeach;
                    endif; ?>
                }
            });
        <?php endforeach; ?>
        <?php foreach ($web_data['filter_colors']['colors'] as $color) : ?>
            document.querySelector('#checkbox-<?php echo $color['color_url']; ?>').addEventListener('change', () => {
                if (document.querySelector('#checkbox-<?php echo $color['color_url']; ?>').checked == true) {
                    document.querySelector('#form-<?php echo $color['color_url']; ?>').submit();
                } else {
                    <?php if (!empty($web_data['selected_filters'])) : foreach ($web_data['selected_filters'] as $selected_filter) : if ($selected_filter['url_name'] == $color['color_url']) : ?>
                                window.location.href = '<?php echo $selected_filter['url']; ?>';
                    <?php endif;
                        endforeach;
                    endif; ?>
                }
            });
        <?php endforeach; ?>
        <?php foreach ($web_data['filter_sizes']['sizes'] as $size) : ?>
            document.querySelector('#checkbox-<?php echo $size['size_url']; ?>').addEventListener('change', () => {
                if (document.querySelector('#checkbox-<?php echo $size['size_url']; ?>').checked == true) {
                    document.querySelector('#form-<?php echo $size['size_url']; ?>').submit();
                } else {
                    <?php if (!empty($web_data['selected_filters'])) : foreach ($web_data['selected_filters'] as $selected_filter) : if ($selected_filter['url_name'] == $size['size_url']) : ?>
                                window.location.href = '<?php echo $selected_filter['url']; ?>';
                    <?php endif;
                        endforeach;
                    endif; ?>
                }
            });
        <?php endforeach; ?>
        <?php foreach ($web_data['filter_prices']['prices'] as $price) : ?>
            document.querySelector('#checkbox-<?php echo $price['min_price_url']; ?>').addEventListener('change', () => {
                if (document.querySelector('#checkbox-<?php echo $price['min_price_url']; ?>').checked == true) {
                    document.querySelector('#form-<?php echo $price['min_price_url']; ?>').submit();
                } else {
                    <?php if (!empty($web_data['selected_filters'])) : foreach ($web_data['selected_filters'] as $selected_filter) : if ($selected_filter['url_name'] == $price['min_price_url']) : ?>
                                window.location.href = '<?php echo $selected_filter['url']; ?>';
                    <?php endif;
                        endforeach;
                    endif; ?>
                }
            });
        <?php endforeach; ?>
    </script>
</body>

</html>