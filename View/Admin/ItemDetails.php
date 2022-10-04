<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Ürün Detayları - Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <section class="item-create-section container">
        <div class="row">
            <div class="left">
                <h1 class="title mb">Ürün Detayları</h1>
            </div>
            <?php if (!empty($web_data['item'])) : ?>
                <div class="right">
                    <div class="row">
                        <a class="btn-user-past-orders" href="<?php echo URL . URL_ADMIN_ITEM_COMMENT . '/' . $web_data['item']['item_url']; ?>" title="Ürüne Yapılan Yorumlar">Yorumlar</a>
                        <form id="form-delete" action="<?php echo URL . URL_ADMIN_ITEM_DELETE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <input type="hidden" name="id" value="<?php echo $web_data['item']['id']; ?>">
                            <input type="submit" id="btn-item-delete" class="button-item-delete" value="Ürünü Kaldır">
                        </form>
                        <div class="delete-popup hidden">
                            <div class="popup-container">
                                <span class="text">Ürünü Kaldırmak İstediğinizden Emin Misiniz?</span>
                                <div class="delete-row">
                                    <button class="btn-delete-cancel">İPTAL</button>
                                    <button class="btn-delete-approve">SİL</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($web_data['item'])) : ?>
            <form id="form-create" action="<?php echo URL . URL_ADMIN_ITEM_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate enctype="multipart/form-data">
                <?php if (!empty($web_data['form_token'])) : ?>
                    <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                <?php endif; ?>
                <input type="hidden" name="item_hidden_url" value="<?php echo $web_data['item']['item_url']; ?>">
                <input type="hidden" name="id" value="<?php echo $web_data['item']['id']; ?>">
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_name">İsim</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_name'])) : ?>
                            <input class="create-input" id="item_name" type="text" name="item_name" value="<?php echo $web_data['item_name']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_name" type="text" name="item_name" value="<?php echo $web_data['item']['item_name']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_keywords">Anahtar Kelimeler (10)</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_keywords'])) : ?>
                            <input class="create-input" id="item_keywords" type="text" name="item_keywords" value="<?php echo $web_data['item_keywords']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_keywords" type="text" name="item_keywords" value="<?php echo $web_data['item']['item_keywords']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_description">Açıklama (160)</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_description'])) : ?>
                            <input class="create-input" id="item_description" type="text" name="item_description" value="<?php echo $web_data['item_description']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_description" type="text" name="item_description" value="<?php echo $web_data['item']['item_description']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_price">Fiyat (₺)</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_price'])) : ?>
                            <input class="create-input" id="item_price" type="text" name="item_price" value="<?php echo $web_data['item_price']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_price" type="text" name="item_price" value="<?php echo $web_data['item']['item_price']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_discount_price">İndirimli Fiyat (₺)</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_discount_price'])) : ?>
                            <input class="create-input" id="item_discount_price" type="text" name="item_discount_price" value="<?php echo $web_data['item_discount_price']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_discount_price" type="text" name="item_discount_price" value="<?php echo $web_data['item']['item_discount_price']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_collection">Koleksiyon</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_collection'])) : ?>
                            <input class="create-input" id="item_collection" type="text" name="item_collection" value="<?php echo $web_data['item_collection']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_collection" type="text" name="item_collection" value="<?php echo $web_data['item']['item_collection']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_material">Materyal</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_material'])) : ?>
                            <input class="create-input" id="item_material" type="text" name="item_material" value="<?php echo $web_data['item_material']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_material" type="text" name="item_material" value="<?php echo $web_data['item']['item_material']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_cut_model">Kesim</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_cut_model'])) : ?>
                            <input class="create-input" id="item_cut_model" type="text" name="item_cut_model" value="<?php echo $web_data['item_cut_model']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_cut_model" type="text" name="item_cut_model" value="<?php echo $web_data['item']['item_cut_model']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_thickness">Kalınlık</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_thickness'])) : ?>
                            <input class="create-input" id="item_thickness" type="text" name="item_thickness" value="<?php echo $web_data['item_thickness']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_thickness" type="text" name="item_thickness" value="<?php echo $web_data['item']['item_thickness']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_pattern">Desen</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_pattern'])) : ?>
                            <input class="create-input" id="item_pattern" type="text" name="item_pattern" value="<?php echo $web_data['item_pattern']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_pattern" type="text" name="item_pattern" value="<?php echo $web_data['item']['item_pattern']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_lapel">Yaka</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_lapel'])) : ?>
                            <input class="create-input" id="item_lapel" type="text" name="item_lapel" value="<?php echo $web_data['item_lapel']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_lapel" type="text" name="item_lapel" value="<?php echo $web_data['item']['item_lapel']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_sleeve_type">Kol Tipi</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_sleeve_type'])) : ?>
                            <input class="create-input" id="item_sleeve_type" type="text" name="item_sleeve_type" value="<?php echo $web_data['item_sleeve_type']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_sleeve_type" type="text" name="item_sleeve_type" value="<?php echo $web_data['item']['item_sleeve_type']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_sleeve_length">Kol Uzunluğu</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_sleeve_length'])) : ?>
                            <input class="create-input" id="item_sleeve_length" type="text" name="item_sleeve_length" value="<?php echo $web_data['item_sleeve_length']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_sleeve_length" type="text" name="item_sleeve_length" value="<?php echo $web_data['item']['item_sleeve_length']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_washing_style">Yıkma Stili</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_washing_style'])) : ?>
                            <input class="create-input" id="item_washing_style" type="text" name="item_washing_style" value="<?php echo $web_data['item_washing_style']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_washing_style" type="text" name="item_washing_style" value="<?php echo $web_data['item']['item_washing_style']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_model_size">Model Bedeni</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_model_size'])) : ?>
                            <input class="create-input" id="item_model_size" type="text" name="item_model_size" value="<?php echo $web_data['item_model_size']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_model_size" type="text" name="item_model_size" value="<?php echo $web_data['item']['item_model_size']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_model_height">Model Uzunluğu (cm)</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_model_height'])) : ?>
                            <input class="create-input" id="item_model_height" type="text" name="item_model_height" value="<?php echo $web_data['item_model_height']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_model_height" type="text" name="item_model_height" value="<?php echo $web_data['item']['item_model_height']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_model_weight">Model Ağırlığı (kg)</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_model_weight'])) : ?>
                            <input class="create-input" id="item_model_weight" type="text" name="item_model_weight" value="<?php echo $web_data['item_model_weight']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_model_weight" type="text" name="item_model_weight" value="<?php echo $web_data['item']['item_model_weight']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label">Cinsiyet</label>
                    </div>
                    <div class="col-input">
                        <div id="item-gender-wrapper" class="create-select-type type-gender">
                            <select class="item-select" name="gender">
                                <?php $catted = true; ?>
                                <?php foreach ($web_data['genders'] as $gender) : ?>
                                    <?php if (!empty($web_data['posted_gender'])) : ?>
                                        <?php $catted = false; ?>
                                        <option id="details-option-<?php echo $gender['gender_url'] ?>" value="<?php echo $gender['id']; ?>" selected></option>
                                    <?php elseif (!empty($web_data['item']['gender']) && $web_data['item']['gender'] == $gender['id']) : ?>
                                        <?php $catted = false; ?>
                                        <option id="details-option-<?php echo $gender['gender_url'] ?>" value="<?php echo $gender['id']; ?>" selected></option>
                                    <?php else : ?>
                                        <option id="details-option-<?php echo $gender['gender_url'] ?>" value="<?php echo $gender['id']; ?>"></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if ($catted) :  ?>
                                    <option value="0" selected></option>
                                <?php endif; ?>
                            </select>
                            <?php if (!empty($web_data['posted_gender'])) : ?>
                                <?php foreach ($web_data['genders'] as $gender) : ?>
                                    <?php if ($web_data['posted_gender'] == $gender['id']) : ?>
                                        <span id="select-text" class="select-text"><?php echo $gender['gender_name']; ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?php foreach ($web_data['genders'] as $gender) : ?>
                                    <?php if ($web_data['item']['gender'] == $gender['id']) : ?>
                                        <span id="select-text-gender" class="select-text"><?php echo $gender['gender_name']; ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="gender-select-wrapper" class="details-select">
                                <?php foreach ($web_data['genders'] as $gender) : ?>
                                    <span class="option" data-option="<?php echo $gender['gender_url']; ?>" data-name="<?php echo $gender['gender_name']; ?>"><?php echo $gender['gender_name']; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label">Kategori</label>
                    </div>
                    <div class="col-input">
                        <div id="item-category-wrapper" class="create-select-type type-category">
                            <select class="item-select" name="category">
                                <?php $setted = true; ?>
                                <?php foreach ($web_data['categories'] as $category) : ?>
                                    <?php if (!empty($web_data['posted_category'])) : ?>
                                        <?php $setted = false; ?>
                                        <option id="details-option-<?php echo $category['category_url'] ?>" value="<?php echo $category['id']; ?>" selected></option>
                                    <?php elseif (!empty($web_data['item']['category']) && $web_data['item']['category'] == $category['id']) : ?>
                                        <?php $setted = false; ?>
                                        <option id="details-option-<?php echo $category['category_url'] ?>" value="<?php echo $category['id']; ?>" selected></option>
                                    <?php else : ?>
                                        <option id="details-option-<?php echo $category['category_url'] ?>" value="<?php echo $category['id']; ?>"></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if ($setted) :  ?>
                                    <option value="0" selected></option>
                                <?php endif; ?>
                            </select>
                            <?php if (!empty($web_data['posted_category'])) : ?>
                                <?php foreach ($web_data['categories'] as $category) : ?>
                                    <?php if ($web_data['posted_category'] == $category['id']) : ?>
                                        <span id="select-text" class="select-text"><?php echo $category['category_name']; ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?php foreach ($web_data['categories'] as $category) : ?>
                                    <?php if ($web_data['item']['category'] == $category['id']) : ?>
                                        <span id="select-text-category" class="select-text"><?php echo $category['category_name']; ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="category-select-wrapper" class="details-select">
                                <?php foreach ($web_data['categories'] as $category) : ?>
                                    <span class="option" data-option="<?php echo $category['category_url']; ?>" data-name="<?php echo $category['category_name']; ?>"><?php echo $category['category_name']; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php foreach ($web_data['colors'] as $color) : ?>
                    <div class="row">
                        <div class="col-label">
                            <label class="create-label"><?php echo $color['color_name']; ?></label>
                        </div>
                        <div class="col-input">
                            <label for="<?php echo $color['color_url']; ?>">
                                <div class="checkbox-wrapper create-checkbox">
                                    <?php if (!empty($web_data['posted_colors']) && !empty($web_data['posted_colors'][$color['color_url']])) : ?>
                                        <input type="checkbox" class="checkbox" id="<?php echo $color['color_url']; ?>" name="<?php echo $color['color_url']; ?>" checked>
                                    <?php elseif ($web_data['item'][$color['color_url']] == 1) : ?>
                                        <input type="checkbox" class="checkbox" id="<?php echo $color['color_url']; ?>" name="<?php echo $color['color_url']; ?>" checked>
                                    <?php else : ?>
                                        <input type="checkbox" class="checkbox" id="<?php echo $color['color_url']; ?>" name="<?php echo $color['color_url']; ?>">
                                    <?php endif; ?>
                                    <span class="checkmark"></span>
                                </div>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label">Satışa Açık</label>
                    </div>
                    <div class="col-input">
                        <label for="is_item_for_sale">
                            <div class="checkbox-wrapper create-checkbox">
                                <input type="checkbox" class="checkbox" id="is_item_for_sale" name="is_item_for_sale" <?php echo !empty($web_data['is_item_for_sale']) || !empty($web_data['item']['is_item_for_sale']) ? ' checked' : ''; ?>>
                                <span class="checkmark"></span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label">Ana Sayfada Görünür</label>
                    </div>
                    <div class="col-input">
                        <label for="is_item_home">
                            <div class="checkbox-wrapper create-checkbox">
                                <input type="checkbox" class="checkbox" id="is_item_home" name="is_item_home" <?php echo !empty($web_data['is_item_home']) || !empty($web_data['item']['is_item_home']) ? ' checked' : ''; ?>>
                                <span class="checkmark"></span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="item_total_quantity">Toplam Adet</label>
                    </div>
                    <div class="col-input">
                        <?php if (!empty($web_data['item_total_quantity'])) : ?>
                            <input class="create-input" id="item_total_quantity" type="text" name="item_total_quantity" value="<?php echo $web_data['item_total_quantity']; ?>">
                        <?php else : ?>
                            <input class="create-input" id="item_total_quantity" type="text" name="item_total_quantity" value="<?php echo $web_data['item']['item_total_quantity']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <?php foreach ($web_data['sizes'] as $size) : ?>
                    <div class="row">
                        <div class="col-label">
                            <label class="create-label" for="<?php echo $size['size_url']; ?>"><?php echo $size['size_name']; ?> Beden Adedi</label>
                        </div>
                        <div class="col-input">
                            <?php if (isset($web_data['posted_sizes']) && isset($web_data['posted_sizes'][$size['size_url']])) : ?>
                                <input class="create-input" id="<?php echo $size['size_url']; ?>" type="text" name="<?php echo $size['size_url']; ?>" value="<?php echo $web_data['posted_sizes'][$size['size_url']]; ?>">
                            <?php else : ?>
                                <input class="create-input" id="<?php echo $size['size_url']; ?>" type="text" name="<?php echo $size['size_url']; ?>" value="<?php echo $web_data['item'][$size['size_url']]; ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="date_item_created">Ürünün Eklenme Tarihi</label>
                    </div>
                    <div class="col-input">
                        <input class="create-input" id="date_item_created" type="text" value="<?php echo date('d/m/Y H:i:s', strtotime($web_data['item']['date_item_created'])); ?>" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="date_item_last_updated">Ürünün Son Güncellenme Tarihi</label>
                    </div>
                    <div class="col-input">
                        <input class="create-input" id="date_item_last_updated" type="text" value="<?php echo !empty($web_data['item']['date_item_last_updated']) ? date('d/m/Y H:i:s', strtotime($web_data['item']['date_item_last_updated'])) : '-'; ?>" disabled>
                    </div>
                </div>
                <h2 class="image-title">Ürün Görselleri</h2>
                <div class="item-image-wrapper">
                    <?php foreach ($web_data['item']['item_images'] as $item_images) : ?>
                        <div id="item-image-<?php echo $item_images[0]; ?>" class="item-image-container">
                            <label for="upload-image-<?php echo $item_images[0]; ?>" class="image-label">
                                <span class="text"><?php echo $item_images[0]; ?>. Görseli Değiştirmek İçin Tıklayın</span>
                                <img id="item-image-src-<?php echo $item_images[0]; ?>" class="item-image-src" src="<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/' . $item_images[1]; ?>">
                                <div class="upload-image-icon-container">
                                    <i class="fas fa-camera upload-image-icon"></i>
                                </div>
                            </label>
                            <input id="upload-image-<?php echo $item_images[0]; ?>" class="input-item-image" data-id="<?php echo $item_images[0]; ?>" type="file" name="item_images[]" accept=".jpg, .jpeg, .png">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row">
                    <div class="right">
                        <input class="btn-create" type="submit" value="Ürünü Güncelle">
                    </div>
                </div>
            </form>
        <?php else : ?>
            <span class="not-found">Ürün Bulunamadı</span>
        <?php endif; ?>
    </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
    <script>
        document.getElementById('item-gender-wrapper').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('gender-select-wrapper').classList.toggle('active');
        });
        document.querySelectorAll('.type-gender .details-select .option').forEach(detailsSelectOption => {
            detailsSelectOption.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('details-option-' + detailsSelectOption.dataset.option).selected = true;
                document.getElementById('select-text-gender').innerHTML = detailsSelectOption.dataset.name;
            });
        });
        document.getElementById('item-category-wrapper').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('category-select-wrapper').classList.toggle('active');
        });
        document.querySelectorAll('.type-category .details-select .option').forEach(detailsSelectOption => {
            detailsSelectOption.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('details-option-' + detailsSelectOption.dataset.option).selected = true;
                document.getElementById('select-text-category').innerHTML = detailsSelectOption.dataset.name;
            });
        });
        document.querySelectorAll('.input-item-image').forEach(element => {
            element.onchange = function() {
                var item_image = this.files[0];
                var reader = new FileReader();
                reader.readAsDataURL(item_image);
                reader.onload = function(e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function(e2) {
                        var created_image = document.createElement('canvas');
                        created_image.width = 600;
                        created_image.height = 600;
                        var context = created_image.getContext('2d');
                        context.drawImage(e2.target, 0, 0, created_image.width, created_image.height);
                        document.getElementById('item-image-src-' + element.dataset.id).src = context.canvas.toDataURL('image/png', 1);
                    }
                }
            };
        });
        document.querySelector('.btn-create').addEventListener('click', (e) => {
            e.preventDefault();
            if (loaderWrapper.classList.contains('hidden')) {
                loaderWrapper.classList.remove('hidden');
            }
            if (!bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.add('noscroll');
            }
            document.getElementById('form-create').submit();
        });
        const deletePopup = document.querySelector('.delete-popup');
        document.getElementById('btn-item-delete').addEventListener('click', (e) => {
            e.preventDefault();
            if (deletePopup.classList.contains('hidden')) {
                deletePopup.classList.remove('hidden');
            }
            if (!bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.add('noscroll');
            }
        });
        document.querySelector('.btn-delete-cancel').addEventListener('click', (e) => {
            e.preventDefault();
            if (!deletePopup.classList.contains('hidden')) {
                deletePopup.classList.add('hidden');
            }
            if (bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.remove('noscroll');
            }
        });
        document.querySelector('.btn-delete-approve').addEventListener('click', (e) => {
            e.preventDefault();
            if (loaderWrapper.classList.contains('hidden')) {
                loaderWrapper.classList.remove('hidden');
            }
            if (!bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.add('noscroll');
            }
            document.getElementById('form-delete').submit();
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