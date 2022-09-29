<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Ürünler - Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <main>
        <section class="item-section container">
            <div class="row">
                <div class="left">
                    <h1 class="title" title="Toplam Ürün Sayısı">Ürünler (<?php echo $web_data['item_total_count']; ?>)</h1>
                    <div class="btn-item-filters">
                        <span class="text">Filtreler</span>
                        <i class="fas fa-sort-amount-down-alt"></i>
                    </div>
                    <?php if (!empty($web_data['selected_gender']) || isset($web_data['selected_home']) || isset($web_data['selected_sale']) || isset($web_data['selected_date']) || isset($web_data['selected_name_desc']) || isset($web_data['selected_name_asc']) || isset($web_data['selected_price_desc']) || isset($web_data['selected_price_asc']) || isset($web_data['selected_discount_price_desc']) || isset($web_data['selected_discount_price_asc']) || isset($web_data['selected_quantity_desc']) || isset($web_data['selected_quantity_asc'])) : ?>
                        <a href="<?php echo URL . URL_ADMIN_ITEMS; ?>" class="btn-item-filters">
                            <span class="text">Filtreleri Temizle</span>
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="right">
                    <div class="filter-row" title="Tek Seferde Kaç Ürün Gösterileceğini Belirtir">
                        <div id="details-item-limit" class="item-limit">
                            <select class="item-select">
                                <option value="20" selected></option>
                                <option value="50"></option>
                                <option value="100"></option>
                                <option value="500"></option>
                            </select>
                            <?php if (!empty($web_data['selected_limit'])) : ?>
                                <span id="select-text" class="select-text"><?php echo $web_data['selected_limit']; ?></span>
                            <?php else : ?>
                                <span id="select-text" class="select-text">20</span>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="details-select" class="details-select">
                                <span class="option" data-url="20">20</span>
                                <span class="option" data-url="50">50</span>
                                <span class="option" data-url="100">100</span>
                                <span class="option" data-url="500">500</span>
                            </div>
                        </div>
                    </div>
                    <div class="search-container">
                        <input type="search" class="admin-search" placeholder="Ürün Ara">
                        <i id="btn-search" class="fas fa-search"></i>
                    </div>
                </div>
            </div>
            <?php if (!empty($web_data['selected_search'])) : ?>
                <div class="search-tree">
                    <a class="text underline" href="<?php echo URL . URL_ADMIN_ITEMS . '?' . $web_data['url_search']; ?>">Ürünler</a>
                    <span class="text">></span>
                    <span class="link text underline"><?php echo $web_data['selected_search']; ?></span>
                </div>
            <?php endif; ?>
            <div class="filters-wrapper<?php echo !empty($web_data['selected_gender']) || isset($web_data['selected_home']) || isset($web_data['selected_sale']) || isset($web_data['selected_date']) ? ' active' : ''; ?>">
                <h2 class="filter-title">Filtreler</h2>
                <div class="row">
                    <div class="filter-row" title="Ürünleri Kategorilerine Göre Filtrele">
                        <span class="filter-label">Cinsiyet</span>
                        <div id="details-item-gender" class="item-gender">
                            <select class="item-select">
                                <option value="all"></option>
                                <option value="kadin"></option>
                                <option value="erkek"></option>
                            </select>
                            <?php if (!empty($web_data['selected_gender'])) : ?>
                                <?php if ($web_data['selected_gender'] == 'kadin') : ?>
                                    <span id="select-text-gender" class="select-text">Kadın</span>
                                <?php elseif ($web_data['selected_gender'] == 'erkek') : ?>
                                    <span id="select-text-gender" class="select-text">Erkek</span>
                                <?php else : ?>
                                    <span id="select-text-gender" class="select-text">Tümü</span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span id="select-text-gender" class="select-text">Tümü</span>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="gender-select" class="gender-select">
                                <span class="option" data-url="all">Tümü</span>
                                <span class="option" data-url="kadin">Kadın</span>
                                <span class="option" data-url="erkek">Erkek</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-row" title="Ana Sayfada Olan Ürünleri Filtrele">
                        <span class="filter-label">Ana Sayfada</span>
                        <div id="details-item-home" class="item-home">
                            <select class="item-select">
                                <option value="all"></option>
                                <option value="1"></option>
                                <option value="0"></option>
                            </select>
                            <?php if (isset($web_data['selected_home'])) : ?>
                                <?php if ($web_data['selected_home'] == 1) : ?>
                                    <span id="select-text-home" class="select-text">Evet</span>
                                <?php elseif ($web_data['selected_home'] == 0) : ?>
                                    <span id="select-text-home" class="select-text">Hayır</span>
                                <?php else : ?>
                                    <span id="select-text-home" class="select-text">Tümü</span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span id="select-text-home" class="select-text">Tümü</span>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="home-select" class="home-select">
                                <span class="option" data-url="all">Tümü</span>
                                <span class="option" data-url="1">Evet</span>
                                <span class="option" data-url="0">Hayır</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-row" title="Satıştaki Ürünleri Göster">
                        <span class="filter-label">Satışta</span>
                        <div id="details-item-sale" class="item-sale">
                            <select class="item-select">
                                <option value="all"></option>
                                <option value="1"></option>
                                <option value="0"></option>
                            </select>
                            <?php if (isset($web_data['selected_sale'])) : ?>
                                <?php if ($web_data['selected_sale'] == 1) : ?>
                                    <span id="select-text-sale" class="select-text">Evet</span>
                                <?php elseif ($web_data['selected_sale'] == 0) : ?>
                                    <span id="select-text-sale" class="select-text">Hayır</span>
                                <?php else : ?>
                                    <span id="select-text-sale" class="select-text">Tümü</span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span id="select-text-sale" class="select-text">Tümü</span>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="sale-select" class="sale-select">
                                <span class="option" data-url="all">Tümü</span>
                                <span class="option" data-url="1">Evet</span>
                                <span class="option" data-url="0">Hayır</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-row" title="Ürünleri Eklenme Tarihlerine Göre Filtrele">
                        <span class="filter-label">Eklenme Tarihi</span>
                        <div id="details-item-date" class="item-date">
                            <select class="item-select">
                                <option value="all"></option>
                                <option value="1"></option>
                                <option value="0"></option>
                            </select>
                            <?php if (isset($web_data['selected_date'])) : ?>
                                <?php if ($web_data['selected_date'] == 1) : ?>
                                    <span id="select-text-date" class="select-text">Eskiden Yeniye</span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span id="select-text-date" class="select-text">Yeniden Eskiye</span>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="date-select" class="date-select">
                                <span class="option" data-url="0">Yeniden Eskiye</span>
                                <span class="option" data-url="1">Eskiden Yeniye</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!empty($web_data['items'])) : ?>
                <div class="row-item">
                    <span class="item-container item-image-container item-header">Görsel</span>
                    <span class="item-container item-name item-header item-sort-header">
                        <i id="item-name-desc" class="fas fa-sort-alpha-down-alt sort-icon sort-icon-desc"></i>
                        İsim
                        <i id="item-name-asc" class="fas fa-sort-alpha-down sort-icon sort-icon-asc"></i>
                    </span>
                    <span class="item-container item-s item-header tem-sort-header">
                        <i id="item-price-desc" class="fas fa-sort-numeric-down-alt sort-icon sort-icon-desc"></i>
                        Fiyat
                        <i id="item-price-asc" class="fas fa-sort-numeric-down sort-icon sort-icon-asc"></i>
                    </span>
                    <span class="item-container item-s item-header tem-sort-header">
                        <i id="item-discount-price-desc" class="fas fa-sort-numeric-down-alt sort-icon sort-icon-desc"></i>
                        İndirimli Fiyat
                        <i id="item-discount-price-asc" class="fas fa-sort-numeric-down sort-icon sort-icon-asc"></i>
                    </span>
                    <span class="item-container item-s item-header tem-sort-header">
                        <i id="item-quantity-desc" class="fas fa-sort-numeric-down-alt sort-icon sort-icon-desc"></i>
                        Adet
                        <i id="item-quantity-asc" class="fas fa-sort-numeric-down sort-icon sort-icon-asc"></i>
                    </span>
                    <span class="item-container item-xs item-header">Ana Sayfada</span>
                    <span class="item-container item-xs item-header">Satışta</span>
                    <span class="item-container item-xs item-header">İşlemler</span>
                </div>
                <?php foreach ($web_data['items'] as $item) : ?>
                    <div class="row-item">
                        <span class="item-container item-image-container">
                            <img class="item-image" src="<?php echo URL . 'assets/images/items/' . $item['item_images_path'] . '/' . $item['item_images']; ?>" alt="<?php echo $item['item_name']; ?>">
                        </span>
                        <?php if (!empty($web_data['selected_name_asc'])) : ?>
                            <span class="item-container item-name item-sort-name selected-asc"><?php echo $item['item_name']; ?></span>
                        <?php elseif (!empty($web_data['selected_name_desc'])) : ?>
                            <span class="item-container item-name item-sort-name selected-desc"><?php echo $item['item_name']; ?></span>
                        <?php else : ?>
                            <span class="item-container item-name item-sort-name"><?php echo $item['item_name']; ?></span>
                        <?php endif; ?>
                        <?php if (!empty($web_data['selected_price_asc'])) : ?>
                            <span class="item-container item-s item-sort-price selected-asc"><?php echo $item['item_price']; ?></span>
                        <?php elseif (!empty($web_data['selected_price_desc'])) : ?>
                            <span class="item-container item-s item-sort-price selected-desc"><?php echo $item['item_price']; ?></span>
                        <?php else : ?>
                            <span class="item-container item-s item-sort-price"><?php echo $item['item_price']; ?></span>
                        <?php endif; ?>
                        <?php if (!empty($web_data['selected_discount_price_asc'])) : ?>
                            <span class="item-container item-s item-sort-discount-price selected-asc"><?php echo $item['item_discount_price']; ?> ₺</span>
                        <?php elseif (!empty($web_data['selected_discount_price_desc'])) : ?>
                            <span class="item-container item-s item-sort-discount-price selected-desc"><?php echo $item['item_discount_price']; ?> ₺</span>
                        <?php else : ?>
                            <span class="item-container item-s item-sort-discount-price"><?php echo $item['item_discount_price']; ?> ₺</span>
                        <?php endif; ?>
                        <?php if (!empty($web_data['selected_quantity_asc'])) : ?>
                            <span class="item-container item-s item-sort-quantity selected-asc"><?php echo $item['item_total_quantity']; ?></span>
                        <?php elseif (!empty($web_data['selected_quantity_desc'])) : ?>
                            <span class="item-container item-s item-sort-quantity selected-desc"><?php echo $item['item_total_quantity']; ?></span>
                        <?php else : ?>
                            <span class="item-container item-s item-sort-quantity"><?php echo $item['item_total_quantity']; ?></span>
                        <?php endif; ?>
                        <span class="item-container item-xs">
                            <label for="is-home">
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" class="checkbox" id="is-home" <?php echo $item['is_item_home'] == 1 ? ' checked' : ''; ?> disabled>
                                    <span class="checkmark"></span>
                                </div>
                            </label>
                        </span>
                        <span class="item-container item-xs">
                            <label for="is-home">
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" class="checkbox" id="is-home" <?php echo $item['is_item_for_sale'] == 1 ? ' checked' : ''; ?> disabled>
                                    <span class="checkmark"></span>
                                </div>
                            </label>
                        </span>
                        <span class="item-container item-xs">
                            <a class="btn-success" href="<?php echo URL . URL_ADMIN_ITEM_DETAILS . '/' . $item['item_url']; ?>">Detaylar</a>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <span class="item-not-found">Aranılan Kriterlerde Ürün Bulunamadı</span>
            <?php endif; ?>
        </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
    <script>
        document.querySelector('.btn-item-filters').addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelector('.filters-wrapper').classList.toggle('active');
        });
        document.getElementById('details-item-gender').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('gender-select').classList.toggle('active');
        });
        document.getElementById('details-item-limit').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('details-select').classList.toggle('active');
        });
        document.getElementById('details-item-home').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('home-select').classList.toggle('active');
        });
        document.getElementById('details-item-sale').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('sale-select').classList.toggle('active');
        });
        document.getElementById('details-item-date').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('date-select').classList.toggle('active');
        });
        document.querySelectorAll('.item-gender .gender-select .option').forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                <?php if (!empty($web_data['url_gender'])) : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?cinsiyet=' ?>' + element.dataset.url + '&<?php echo $web_data['url_gender']; ?>';
                <?php else : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?cinsiyet=' ?>' + element.dataset.url;
                <?php endif; ?>
            });
        });
        document.querySelectorAll('.item-limit .details-select .option').forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                <?php if (!empty($web_data['url_limit'])) : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?limit=' ?>' + element.dataset.url + '&<?php echo $web_data['url_limit']; ?>';
                <?php else : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?limit=' ?>' + element.dataset.url;
                <?php endif; ?>
            });
        });
        document.querySelectorAll('.item-home .home-select .option').forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                <?php if (!empty($web_data['url_home'])) : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?anasayfada=' ?>' + element.dataset.url + '&<?php echo $web_data['url_home']; ?>';
                <?php else : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?anasayfada=' ?>' + element.dataset.url;
                <?php endif; ?>
            });
        });
        document.querySelectorAll('.item-sale .sale-select .option').forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                <?php if (!empty($web_data['url_sale'])) : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?satista=' ?>' + element.dataset.url + '&<?php echo $web_data['url_sale']; ?>';
                <?php else : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?satista=' ?>' + element.dataset.url;
                <?php endif; ?>
            });
        });
        document.querySelectorAll('.item-date .date-select .option').forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                <?php if (!empty($web_data['url_date'])) : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?eklenme-tarihi=' ?>' + element.dataset.url + '&<?php echo $web_data['url_date']; ?>';
                <?php else : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?eklenme-tarihi=' ?>' + element.dataset.url;
                <?php endif; ?>
            });
        });
        document.getElementById('btn-search').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_search'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?ara=' ?>' + document.querySelector('.admin-search').value + '&<?php echo $web_data['url_search']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?ara=' ?>' + document.querySelector('.admin-search').value;
            <?php endif; ?>
        });
        document.getElementById('item-name-desc').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_sort'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?isim-azalan=' ?>' + 1 + '&<?php echo $web_data['url_sort']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?isim-azalan=' ?>' + 1;
            <?php endif; ?>
        });
        document.getElementById('item-name-asc').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_sort'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?isim-artan=' ?>' + 1 + '&<?php echo $web_data['url_sort']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?isim-artan=' ?>' + 1;
            <?php endif; ?>
        });
        document.getElementById('item-price-desc').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_sort'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?fiyat-azalan=' ?>' + 1 + '&<?php echo $web_data['url_sort']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?fiyat-azalan=' ?>' + 1;
            <?php endif; ?>
        });
        document.getElementById('item-price-asc').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_sort'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?fiyat-artan=' ?>' + 1 + '&<?php echo $web_data['url_sort']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?fiyat-artan=' ?>' + 1;
            <?php endif; ?>
        });
        document.getElementById('item-discount-price-desc').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_sort'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?indirimli-fiyat-azalan=' ?>' + 1 + '&<?php echo $web_data['url_sort']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?indirimli-fiyat-azalan=' ?>' + 1;
            <?php endif; ?>
        });
        document.getElementById('item-discount-price-asc').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_sort'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?indirimli-fiyat-artan=' ?>' + 1 + '&<?php echo $web_data['url_sort']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?indirimli-fiyat-artan=' ?>' + 1;
            <?php endif; ?>
        });
        document.getElementById('item-quantity-desc').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_sort'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?adet-azalan=' ?>' + 1 + '&<?php echo $web_data['url_sort']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?adet-azalan=' ?>' + 1;
            <?php endif; ?>
        });
        document.getElementById('item-quantity-asc').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_sort'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?adet-artan=' ?>' + 1 + '&<?php echo $web_data['url_sort']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_ITEMS . '?adet-artan=' ?>' + 1;
            <?php endif; ?>
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#btn-hamburger').click(function() {
                $.ajax({
                    url: '<?php echo URL . URL_ADMIN_MENU; ?>',
                    type: 'POST'
                });
            });
        });
    </script>
</body>

</html>