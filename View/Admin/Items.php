<?php require 'View/SharedAdmin/_admin_html.php'; ?>
<title>Ürünler | <?php echo BRAND; ?> - Yönetim Paneli</title>
<?php require 'View/SharedAdmin/_admin_head.php'; ?>
<script>
    $(document).ready(function() {
        $('#select_filter').change(function() {
            window.location.href = '<?php echo URL . '/AdminController/Items?'; ?>' + $('#filter_form').serialize();
        });
        $('#select_limit').change(function() {
            window.location.href = '<?php echo URL . '/AdminController/Items?'; ?>' + $('#limit_form').serialize();
        });
    });
</script>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
<a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/ItemCreate">
    <span class="btn-header-text">Ürün Ekle</span>
    <div class="btn-header-icon"><i class="fas fa-plus"></i></div>
</a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
<section>
    <div class="container">
        <div class="row-center table-filter-container">
            <div class="row-space row-left">
                <h1 class="main-title">Ürünler</h1>
                <form class="row filter-container" action="<?php echo URL; ?>/AdminController/Items" method="GET" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <?php if (!empty($data['params_without_search'])) :
                        echo $data['params_without_search'];
                    endif; ?>
                    <?php if (!empty($data['search'])) : ?>
                        <input class="table-search" type="search" name="search" placeholder="Başka Bir Ürün Ara" tabindex="1">
                    <?php else : ?>
                        <input class="table-search" type="search" name="search" placeholder="Ürün Ara" tabindex="1">
                    <?php endif; ?>
                    <button class="btn-table-search" type="submit"><i class="fas fa-search table-search-icon"></i></button>
                </form>
            </div>
            <div class="row-center row-right">
                <form id="filter_form" class="row filter-container" title="Ürünlerin satış durumları">
                    <?php if (!empty($data['params_without_filter'])) :
                        echo $data['params_without_filter'];
                    endif; ?>
                    <span class="filter-select-text">Filtrele</span>
                    <select id="select_filter" class="filter-select" name="filter">
                        <option value="butun-urunler" <?php echo (!empty($data['filter']) && ($data['filter'] == 'butun-urunler')) ? ' selected' : ''; ?>>Bütün Ürünler</option>
                        <option value="satistaki-urunler" <?php echo (!empty($data['filter']) && ($data['filter'] == 'satistaki-urunler')) ? ' selected' : ''; ?>>Satışta Olan Ürünler</option>
                        <option value="satista-olmayan-urunler" <?php echo (!empty($data['filter']) && ($data['filter'] == 'satista-olmayan-urunler')) ? ' selected' : ''; ?>>Satışta Olmayan Ürünler</option>
                    </select>
                </form>
                <form id="limit_form" class="row filter-container" title="Tabloda gösterilecek ürün sayısı">
                    <?php if (!empty($data['params_without_limit'])) :
                        echo $data['params_without_limit'];
                    endif; ?>
                    <span class="filter-select-text">Grupla</span>
                    <select id="select_limit" class="filter-select" name="limit">
                        <option value="5" <?php echo (!empty($data['limit']) && $data['limit'] == 5) ? ' selected' : ''; ?>>5</option>
                        <option value="10" <?php echo (!empty($data['limit']) && $data['limit'] == 10) ? ' selected' : ''; ?>>10</option>
                        <option value="25" <?php echo (!empty($data['limit']) && $data['limit'] == 25) ? ' selected' : ''; ?>>25</option>
                        <option value="50" <?php echo (!empty($data['limit']) && $data['limit'] == 50) ? ' selected' : ''; ?>>50</option>
                    </select>
                </form>
                <div class="filter-container" title="Ürünleri fiyat aralığında filtrele">
                    <span class="filter-toggler"><i class="fas fa-sliders-h filter-toggler-icon"></i> Fiyat Aralığı</span>
                    <div class="price-filter">
                        <?php if (!empty($data['min_price']) && !empty($data['max_price'])) : ?>
                            <form action="<?php echo URL; ?>/AdminController/Items" method="GET" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php if (!empty($data['params_without_minmax'])) :
                                    echo $data['params_without_minmax'];
                                endif; ?>
                                <div class="price-container">
                                    <span class="price-text">Minumum</span>
                                    <input id="min-price-input" class="price-input price-number" type="number" value="<?php echo $data['min_price']; ?>"></input> ₺
                                    <input id="min-price-range" class="price-range" type="range" name="min" min="<?php echo $data['min_price']; ?>" max="<?php echo $data['max_price']; ?>" value="<?php echo !empty($data['selected_min_price']) ? $data['selected_min_price'] : $data['min_price']; ?>" step="1">
                                </div>
                                <div class="price-container">
                                    <span class="price-text">Maximum</span>
                                    <input id="max-price-input" class="price-input price-number" type="number" value="<?php echo $data['max_price']; ?>"></input> ₺
                                    <input id="max-price-range" class="price-range" type="range" name="max" min="<?php echo $data['min_price'] + 1; ?>" max="<?php echo $data['max_price'] + 1; ?>" value="<?php echo !empty($data['selected_max_price']) ? $data['selected_max_price'] : $data['max_price'] + 1; ?>" step="1">
                                </div>
                                <div class="price-container row">
                                    <span class="row-left price-bot-text"><span id="min-price-text"></span> ile <span id="max-price-text"></span> fiyat aralığındaki ürünleri</span>
                                    <input class="row-right btn-price btn-1" type="submit" value="Filtrele">
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty($data['search'])) : ?>
            <div class="tree">
                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Items<?php echo (!empty($data['params_search_link']) && ($data['params_search_link'] != '?')) ? rtrim($data['params_search_link'], '&') : ''; ?>">ürünler</a>
                <span class="seperater">&gt;</span>
                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Items<?php echo (!empty($data['params_search_link']) && ($data['params_search_link'] != '?') && !empty($data['search'])) ? $data['params_search_link'] . 'search=' . $data['search'] : '?search=' . $data['search']; ?>"><?php echo $data['search']; ?></a>
            </div>
        <?php endif; ?>
        <?php if (empty($data['notfound_item'])) : ?>
            <?php if (!empty($data['items'])) : ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="table-align-center">Görsel</th>
                            <th style="width: 24%;" class="table-align-center">
                                <div class="row-center">
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="isim-azalan">
                                        <button class="btn-purify btn-desc" type="submit">
                                            <i class="fas fa-caret-down sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'isim-azalan')) ? ' selected' : ''; ?>" title="İsimleri sırala"></i>
                                        </button>
                                    </form>
                                    <span class="th-space">Ürün Adı</span>
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="isim-artan">
                                        <button class="btn-purify btn-asc" type="submit">
                                            <i class="fas fa-caret-up sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'isim-artan')) ? ' selected' : ''; ?>" title="İsimleri alfabetik sırala"></i>
                                        </button>
                                    </form>
                                </div>
                            </th>
                            <th style="width: 13%;" class="table-align-center">
                                <div class="row-center">
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="fiyat-azalan">
                                        <button class="btn-purify btn-desc" type="submit">
                                            <i class="fas fa-caret-down sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'fiyat-azalan')) ? ' selected' : ''; ?>" title="Fiyatları büyükten küçüğe sırala"></i>
                                        </button>
                                    </form>
                                    <span class="th-space">Fiyat</span>
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="fiyat-artan">
                                        <button class="btn-purify btn-asc" type="submit">
                                            <i class="fas fa-caret-up sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'fiyat-artan')) ? ' selected' : ''; ?>" title="Fiyatları küçükten büyüğe sırala"></i>
                                        </button>
                                    </form>
                                </div>
                            </th>
                            <th style="width: 13%;" class="table-align-center">
                                <div class="row-center">
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="indirimli-fiyat-azalan">
                                        <button class="btn-purify btn-desc" type="submit">
                                            <i class="fas fa-caret-down sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'indirimli-fiyat-azalan')) ? ' selected' : ''; ?>" title="İndirimli fiyatları büyükten küçüğe sırala"></i>
                                        </button>
                                    </form>
                                    <span class="th-space">İndirimli Fiyat</span>
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="indirimli-fiyat-artan">
                                        <button class="btn-purify btn-asc" type="submit">
                                            <i class="fas fa-caret-up sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'indirimli-fiyat-artan')) ? ' selected' : ''; ?>" title="İndirimli fiyatları küçükten büyüğe sırala"></i>
                                        </button>
                                    </form>
                                </div>
                            </th>
                            <th style="width: 12%;" class="table-align-center">
                                <div class="row-center">
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="adet-azalan">
                                        <button class="btn-purify btn-desc" type="submit">
                                            <i class="fas fa-caret-down sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'adet-azalan')) ? ' selected' : ''; ?>" title="Adetleri büyükten küçüğe sırala"></i>
                                        </button>
                                    </form>
                                    <span class="th-space">Toplam Adet</span>
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="adet-artan">
                                        <button class="btn-purify btn-asc" type="submit">
                                            <i class="fas fa-caret-up sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'adet-artan')) ? ' selected' : ''; ?>" title="Adetleri küçükten büyüğe sırala"></i>
                                        </button>
                                    </form>
                                </div>
                            </th>
                            <th style="width: 5%;" class="table-align-center">
                                <div class="row-center">
                                    <span class="th-space">Satışta</span>
                                </div>
                            </th>
                            <th style="width: 13%;" class="table-align-center">
                                <div class="row-center">
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="tarih-azalan">
                                        <button class="btn-purify btn-desc" type="submit">
                                            <i class="fas fa-caret-down sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'tarih-azalan')) ? ' selected' : ''; ?>" title="Eklenme tarihini göre büyükten küçüğe sırala"></i>
                                        </button>
                                    </form>
                                    <span class="th-space">Eklenme Tarihi</span>
                                    <form action="<?php echo URL; ?>/AdminController/Items" method="GET">
                                        <?php if (!empty($data['params_without_sort'])) :
                                            echo $data['params_without_sort'];
                                        endif; ?>
                                        <input type="hidden" name="sort" value="tarih-artan">
                                        <button class="btn-purify btn-asc" type="submit">
                                            <i class="fas fa-caret-up sort-icon<?php echo (!empty($data['sort']) && ($data['sort'] == 'tarih-artan')) ? ' selected' : ''; ?>" title="Eklenme tarihini göre küçükten büyüğe sırala"></i>
                                        </button>
                                    </form>
                                </div>
                            </th>
                            <th style="width: 10%;" class="table-align-center">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['items'] as $item) : ?>
                            <tr>
                                <?php $item_name = isset($item['item_name']) ? ucwords($item['item_name']) : '-'; ?>
                                <?php if (isset($item['item_images']) && isset($item['id'])) : ?>
                                    <td class="table-no-space"><img class="table-image" src="<?php echo URL . ITEM_IMAGES_PATH . $item['id'] . '/' . $item['item_images']; ?>" alt="<?php echo $item_name; ?>"></td>
                                <?php else : ?>
                                    <td class="table-align-left"><span class="text-danger">Ürün görseli bulunamadı</span></td>
                                <?php endif; ?>
                                <?php if (!empty($data['sort_deg']) && (($data['sort_deg'] == 'name_0') || ($data['sort_deg'] == 'name_1'))) : ?>
                                    <td class="table-align-left<?php echo ($data['sort_deg'] == 'name_0') ? ' desc' : ' asc'; ?>"><?php echo $item_name; ?></td>
                                <?php else : ?>
                                    <td class="table-align-left"><?php echo $item_name; ?></td>
                                <?php endif; ?>
                                <?php if (!empty($data['sort_deg']) && (($data['sort_deg'] == 'price_0') || ($data['sort_deg'] == 'price_1'))) : ?>
                                    <td class="table-align-center<?php echo ($data['sort_deg'] == 'price_0') ? ' desc' : ' asc'; ?>"><?php echo isset($item['item_price']) ? $item['item_price'] . ' ₺' : '-'; ?></td>
                                <?php else : ?>
                                    <td class="table-align-center"><?php echo isset($item['item_price']) ? $item['item_price'] . ' ₺' : '-'; ?></td>
                                <?php endif; ?>
                                <?php if (!empty($data['sort_deg']) && (($data['sort_deg'] == 'discount_price_0') || ($data['sort_deg'] == 'discount_price_1'))) : ?>
                                    <td class="table-align-center<?php echo ($data['sort_deg'] == 'discount_price_0') ? ' desc' : ' asc'; ?>"><?php echo isset($item['item_discount_price']) ? $item['item_discount_price'] . ' ₺' : '-'; ?></td>
                                <?php else : ?>
                                    <td class="table-align-center"><?php echo isset($item['item_discount_price']) ? $item['item_discount_price'] . ' ₺' : '-'; ?></td>
                                <?php endif; ?>
                                <?php if (!empty($data['sort_deg']) && (($data['sort_deg'] == 'number_0') || ($data['sort_deg'] == 'number_1'))) : ?>
                                    <td class="table-align-center<?php echo ($data['sort_deg'] == 'number_0') ? ' desc' : ' asc'; ?>"><?php echo isset($item['item_total_number']) ? $item['item_total_number'] : '-'; ?></td>
                                <?php else : ?>
                                    <td class="table-align-center"><?php echo isset($item['item_total_number']) ? $item['item_total_number'] : '-'; ?></td>
                                <?php endif; ?>
                                <td class="table-align-center"><?php echo (isset($item['item_insale']) && ($item['item_insale'] == 1)) ? '<i class="fas fa-check insale-icon"></i>' : '<i class="fas fa-times outsale-icon"></i>'; ?></td>
                                <?php if (!empty($data['sort_deg']) && (($data['sort_deg'] == 'date_0') || ($data['sort_deg'] == 'date_1'))) : ?>
                                    <td class="table-align-center<?php echo ($data['sort_deg'] == 'date_0') ? ' desc' : ' asc'; ?>"><?php echo isset($item['item_date_added']) ? date('d/m/Y', strtotime($item['item_date_added'])) : '-'; ?></td>
                                <?php else : ?>
                                    <td class="table-align-center"><?php echo isset($item['item_date_added']) ? date('d/m/Y', strtotime($item['item_date_added'])) : '-'; ?></td>
                                <?php endif; ?>
                                <td>
                                    <div class="btn-setting-container">
                                        <?php if (isset($item['item_url'])) : ?>
                                            <a class="btn-setting btn-1" href="<?php echo URL; ?>/AdminController/ItemDetails/<?php echo $item['item_url']; ?>" title="<?php echo $item_name; ?> Detayları ve Güncelleme">Detaylar</a>
                                        <?php endif;
                                        if (isset($item['id'])) : ?>
                                            <button class="btn-setting btn-3 btn-delete" data-id="<?php echo $item['id']; ?>" title="<?php echo $item_name; ?> Ürününü Sil">Sil</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php if (isset($data['total_page'])) : ?>
                <div class="row">
                    <div class="row-right">
                        <div class="row-space">
                            <?php for ($i = 1; $i <= $data['total_page']; $i++) : ?>
                                <?php if (!empty($data['params_page_link']) && ($data['params_page_link'] != '?')) : ?>
                                    <a class="pagination-link<?php echo (isset($data['page']) && ($data['page'] == $i)) ? ' selected' : ''; ?>" href="<?php echo URL . '/AdminController/Items' . $data['params_page_link'] . 'page=' . $i; ?>"><?php echo $i; ?></a>
                                <?php else : ?>
                                    <a class="pagination-link<?php echo (isset($data['page']) && ($data['page'] == $i)) ? ' selected' : ''; ?>" href="<?php echo URL . '/AdminController/Items?page=' . $i; ?>"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="notfound-container">
                <span class="text-notfound">Ürün Bulunamadı</span>
                <a class="btn-notfound row-center" href="<?php echo URL; ?>/AdminController/Items">
                    <span class="btn-header-text">Ürünlere Geri Dön</span>
                    <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
                </a>
            </div>
        <?php endif ?>
    </div>
</section>
<?php require 'View/SharedAdmin/_admin_footer.php'; ?>
<div class="delete-popup disable">
    <div class="confirm-box">
        <div class="confirm-title-container">
            <h3 class="confirm-title confirm-title-danger"></h3>
        </div>
        <div class="confirm-btns">
            <button class="btn-popup btn-cancel btn-2" title="Silme İşlemini İptal Et">İptal</button>
        </div>
    </div>
</div>
<?php if (!empty($data['min_price']) && !empty($data['max_price'])) : ?>
    <script>
        document.querySelector('.filter-toggler').addEventListener('click', () => {
            document.querySelector('.price-filter').classList.toggle('active');
        });
        let minPrice = <?php echo $data['min_price']; ?>;
        let maxPrice = <?php echo $data['max_price']; ?>;
        let minRange = document.getElementById('min-price-range');
        let maxRange = document.getElementById('max-price-range');
        let minPriceText = document.getElementById('min-price-text');
        let maxPriceText = document.getElementById('max-price-text');
        let minPriceInput = document.getElementById('min-price-input');
        let maxPriceInput = document.getElementById('max-price-input');
        minPriceText.innerHTML = minRange.value;
        maxPriceText.innerHTML = maxRange.value;
        minPriceInput.setAttribute('value', minRange.value);
        maxPriceInput.setAttribute('value', maxRange.value);
        minRange.oninput = function() {
            if ((parseInt(this.value) == (+maxPrice + 1)) || (parseInt(this.value) > maxPrice) || (parseInt(this.value) > maxRange.value)) {
                maxRange.value = (+maxPrice + +1);
                maxRange.setAttribute('value', (+maxPrice + +1));
                maxPriceText.innerHTML = (+maxPrice + +1);
                maxPriceInput.setAttribute('value', (+maxPrice + +1));
            } else {
                minRange.value = this.value;
                minRange.setAttribute('value', this.value);
                minPriceText.innerHTML = this.value;
                minPriceInput.setAttribute('value', this.value);
                maxRange.setAttribute('min', (+this.value + +1));
            }
        }
        maxRange.oninput = function() {
            if ((parseInt(this.value) > (+maxPrice + +1)) || (minRange.value >= parseInt(this.value))) {
                maxRange.value = (+maxPrice + +1);
                maxRange.setAttribute('value', (+maxPrice + +1));
                maxPriceText.innerHTML = (+maxPrice + +1);
                maxPriceInput.setAttribute('value', (+maxPrice + +1));
            } else {
                maxRange.value = this.value;
                maxRange.setAttribute('value', this.value);
                maxPriceText.innerHTML = this.value;
                maxPriceInput.setAttribute('value', this.value);
            }
        }
        minPriceInput.oninput = function() {
            let input = this.value;
            if ((typeof(input) == 'string') && !isNaN(input) && (input.indexOf(' ') === -1) && input.length < 7 && input > 0 && input <= maxPrice && input >= minPrice) {
                if (input.length > 0) {
                    input = parseInt(input);
                    minRange.value = input;
                    minRange.setAttribute('value', input);
                    minPriceText.innerHTML = input;
                    minPriceInput.setAttribute('value', input);
                    if ((input >= maxPrice) || (input >= maxRange.value)) {
                        maxRange.value = (+maxPrice + +1);
                        maxRange.setAttribute('value', (+maxPrice + +1));
                        maxPriceInput.setAttribute('value', (+maxPrice + +1));
                        maxPriceText.innerHTML = +maxPrice + +1;
                    } else {
                        maxRange.setAttribute('min', (+input + +1));
                    }
                }
            } else {
                minRange.setAttribute('value', minPrice);
                minRange.value = minPrice;
                minPriceInput.setAttribute('value', minPrice);
                minPriceInput.value = minPrice;
                minPriceText.innerHTML = minPrice;
                maxRange.value = (+maxPrice + +1);
                maxRange.setAttribute('value', (+maxPrice + +1));
                maxPriceInput.setAttribute('value', (+maxPrice + +1));
                maxPriceText.innerHTML = (+maxPrice + +1);
                maxRange.setAttribute('min', (+minPrice + +1));
            }
        }
        maxPriceInput.oninput = function() {
            let input = this.value;
            if ((typeof(input) == 'string') && !isNaN(input) && (input.indexOf(' ') === -1) && input.length < 7 && input > 0 && input <= maxPrice && input >= minPrice) {
                input = parseInt(input);
                if ((input.length == 0) || parseInt(input) > minRange.value) {
                    maxRange.setAttribute('value', (+maxPrice + 1));
                    maxPriceText.innerHTML = (+maxPrice + 1);
                    maxRange.value = (+maxPrice + 1);
                } else {
                    maxPriceText.innerHTML = input;
                    maxPriceInput.setAttribute('value', input);
                    maxRange.setAttribute('value', input);
                    maxRange.value = input;
                }
            } else {
                maxPriceText.innerHTML = (+maxPrice + 1);
                maxRange.setAttribute('value', (+maxPrice + 1));
                maxRange.value = (+maxPrice + 1);
                maxPriceInput.setAttribute('value', (+maxPrice + 1));
            }
        }
    </script>
<?php endif; ?>
<?php if (!empty($data['items'])) : ?>
    <script>
        let deleteBtns = document.querySelectorAll('.btn-delete');
        let deletePopup = document.querySelector('.delete-popup');
        let confirmBtns = document.querySelector('.confirm-btns');
        let btnCancel = document.querySelector('.btn-cancel');
        let confirmTitle = document.querySelector('.confirm-title');
        deleteBtns.forEach(deleteBtn => {
            deleteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const createdForm = document.createElement('form');
                createdForm.setAttribute('action', '<?php echo URL . '/AdminController/ItemDelete'; ?>');
                createdForm.setAttribute('method', 'POST');
                createdForm.setAttribute('id', 'delete-form');
                const input1 = document.createElement('input');
                input1.setAttribute('type', 'hidden');
                input1.setAttribute('name', 'item_id');
                input1.setAttribute('value', deleteBtn.dataset.id);
                createdForm.appendChild(input1);
                const input2 = document.createElement('input');
                input2.setAttribute('class', 'btn-popup btn-3 btn-delete');
                input2.setAttribute('type', 'submit');
                input2.setAttribute('name', 'delete_item');
                input2.setAttribute('title', 'Silme İşlemini Onayla');
                input2.setAttribute('value', 'Sil');
                createdForm.appendChild(input2);
                confirmBtns.appendChild(createdForm);
                const span = document.createElement('span');
                span.setAttribute('class', 'text-deleting disable');
                span.innerText = 'Ürün Siliniyor...';
                createdForm.appendChild(span);
                confirmTitle.innerText = 'Ürünü Silmek İstediğinizden Emin Misiniz?';
                if (deletePopup.classList.contains('disable')) {
                    deletePopup.classList.remove('disable');
                }
                input2.addEventListener('click', () => {
                    input2.classList.add('disable');
                    span.classList.remove('disable');
                });
            });
        });
        btnCancel.addEventListener('click', () => {
            let deleteForm = document.getElementById('delete-form');
            deleteForm.remove();
            if (!deletePopup.classList.contains('disable')) {
                deletePopup.classList.add('disable');
            }
        });
        deletePopup.addEventListener('mouseup', (e) => {
            if (e.target.classList == 'delete-popup') {
                let deleteForm = document.getElementById('delete-form');
                deleteForm.remove();
                if (!deletePopup.classList.contains('disable')) {
                    deletePopup.classList.add('disable');
                }
            }
        });
    </script>
<?php endif; ?>
</body>

</html>