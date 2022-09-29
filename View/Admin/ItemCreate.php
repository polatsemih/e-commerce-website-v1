<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Ürün Ekle - Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <section class="item-create-section container">
        <h1 class="title mb">Ürün Ekle</h1>
        <form id="form-create" action="<?php echo URL . URL_ADMIN_ITEM_CREATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate enctype="multipart/form-data">
            <?php if (!empty($web_data['form_token'])) : ?>
                <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
            <?php endif; ?>
            <div class="row">
                <div class="col-label">
                    <label class="create-label" for="item_name">İsim</label>
                </div>
                <div class="col-input">
                    <?php if (!empty($web_data['item_name'])) : ?>
                        <input class="create-input" id="item_name" type="text" name="item_name" value="<?php echo $web_data['item_name']; ?>">
                    <?php else : ?>
                        <input class="create-input" id="item_name" type="text" name="item_name">
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
                        <input class="create-input" id="item_keywords" type="text" name="item_keywords">
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
                        <input class="create-input" id="item_description" type="text" name="item_description">
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
                        <input class="create-input" id="item_price" type="text" name="item_price">
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
                        <input class="create-input" id="item_discount_price" type="text" name="item_discount_price">
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
                        <input class="create-input" id="item_collection" type="text" name="item_collection">
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
                        <input class="create-input" id="item_material" type="text" name="item_material">
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
                        <input class="create-input" id="item_cut_model" type="text" name="item_cut_model">
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
                        <input class="create-input" id="item_thickness" type="text" name="item_thickness">
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
                        <input class="create-input" id="item_pattern" type="text" name="item_pattern">
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
                        <input class="create-input" id="item_lapel" type="text" name="item_lapel">
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
                        <input class="create-input" id="item_sleeve_type" type="text" name="item_sleeve_type">
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
                        <input class="create-input" id="item_sleeve_length" type="text" name="item_sleeve_length">
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
                        <input class="create-input" id="item_washing_style" type="text" name="item_washing_style">
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
                        <input class="create-input" id="item_model_size" type="text" name="item_model_size">
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
                        <input class="create-input" id="item_model_height" type="text" name="item_model_height">
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
                        <input class="create-input" id="item_model_weight" type="text" name="item_model_weight">
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
                            <span id="select-text-gender" class="select-text">Cinsiyet Seçin</span>
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
                            <span id="select-text-category" class="select-text">Kategori Seçin</span>
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
                            <input type="checkbox" class="checkbox" id="is_item_for_sale" name="is_item_for_sale" <?php echo !empty($web_data['is_item_for_sale']) ? ' checked' : ''; ?>>
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
                            <input type="checkbox" class="checkbox" id="is_item_home" name="is_item_home" <?php echo !empty($web_data['is_item_home']) ? ' checked' : ''; ?>>
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
                        <input class="create-input" id="item_total_quantity" type="text" name="item_total_quantity">
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
                            <input class="create-input" id="<?php echo $size['size_url']; ?>" type="text" name="<?php echo $size['size_url']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <h2 class="image-title">Ürün Görselleri</h2>
            <div class="item-image-wrapper">
                <div id="item-image-1" class="item-image-container">
                    <label for="upload-image-1" class="image-label">
                        <span class="text">1. Görseli Eklemek İçin Tıklayın</span>
                        <img id="item-image-src-1" class="item-image-src" src="">
                        <div class="upload-image-icon-container">
                            <i class="fas fa-camera upload-image-icon"></i>
                        </div>
                    </label>
                    <input id="upload-image-1" class="input-item-image" data-id="1" type="file" name="item_images[]" accept=".jpg, .jpeg, .png">
                </div>
                <div id="item-image-2" class="item-image-container">
                    <label for="upload-image-2" class="image-label">
                        <span class="text">2. Görseli Eklemek İçin Tıklayın</span>
                        <img id="item-image-src-2" class="item-image-src" src="">
                        <div class="upload-image-icon-container">
                            <i class="fas fa-camera upload-image-icon"></i>
                        </div>
                    </label>
                    <input id="upload-image-2" class="input-item-image" data-id="2" type="file" name="item_images[]" accept=".jpg, .jpeg, .png">
                </div>
                <div id="item-image-3" class="item-image-container">
                    <label for="upload-image-3" class="image-label">
                        <span class="text">3. Görseli Eklemek İçin Tıklayın</span>
                        <img id="item-image-src-3" class="item-image-src" src="">
                        <div class="upload-image-icon-container">
                            <i class="fas fa-camera upload-image-icon"></i>
                        </div>
                    </label>
                    <input id="upload-image-3" class="input-item-image" data-id="3" type="file" name="item_images[]" accept=".jpg, .jpeg, .png">
                </div>
            </div>
            <button class="btn-add-image">4. Görseli Ekle</button>
            <button class="btn-remove-image">4. Görseli Kaldır</button>
            <div class="row">
                <div class="right">
                    <input class="btn-create" type="submit" value="Ürünü Ekle">
                </div>
            </div>
        </form>
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
        function itemImageCall(inputImageName) {
            let element = document.getElementById(inputImageName);
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
        }
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
    </script>
    <script>
        $(document).ready(function() {
            $('#btn-hamburger').click(function() {
                $.ajax({
                    url: '<?php echo URL . URL_ADMIN_MENU; ?>',
                    type: 'POST'
                });
            });
            var index = 4;
            var remove_index = 0;
            $('.btn-add-image').click(function(e) {
                if (index < 7) {
                    e.preventDefault();
                    let v0 = $("<div></div>").attr('id', 'item-image-' + index).addClass('item-image-container');
                    let v1 = $("<label></label>").attr('for', 'upload-image-' + index).addClass('image-label');
                    let v2 = $("<span></span>").addClass('text').text(index + '. Görseli Eklemek İçin Tıklayın');
                    let v22 = $("<img></img>").addClass('item-image-src').attr('id', 'item-image-src-' + index).attr('src', '');
                    let v3 = $("<div></div>").addClass('upload-image-icon-container');
                    let v4 = $("<i></i>").addClass('fas fa-camera upload-image-icon');
                    let v5 = $("<input></input>").attr('id', 'upload-image-' + index).addClass('input-item-image').attr('data-id', index).attr('type', 'file').attr('name', 'item_images[]').attr('accept', '.jpg, .jpeg, .png');
                    v1.append(v2);
                    v1.append(v22);
                    v1.append(v3);
                    v3.append(v4);
                    v0.append(v1);
                    v0.append(v5);
                    $('.item-image-wrapper').append(v0);
                    itemImageCall('upload-image-' + index);
                    $('.btn-remove-image').text(index + '. Görseli Kaldır');
                    remove_index = index;
                    index = index + 1;
                    if (index == 7) {
                        if (!$('.btn-add-image').hasClass('hidden')) {
                            $('.btn-add-image').addClass('hidden');
                        }
                        remove_index = 6;
                    } else {
                        $('.btn-add-image').text(index + '. Görseli Ekle');
                    }
                }
                if (!$('.btn-remove-image').hasClass('active')) {
                    $('.btn-remove-image').addClass('active');
                }
            });
            $('.btn-remove-image').click(function(e) {
                if (index >= 4 && remove_index != 0) {
                    e.preventDefault();
                    $('#item-image-' + remove_index).remove();
                    $('.btn-add-image').text(remove_index + '. Görseli Ekle');
                    remove_index = remove_index - 1;
                    index = index - 1;
                    $('.btn-remove-image').text(remove_index + '. Görseli Kaldır');
                    if (remove_index == 3) {
                        if ($('.btn-remove-image').hasClass('active')) {
                            $('.btn-remove-image').removeClass('active');
                        }
                    }
                }
                if ($('.btn-add-image').hasClass('hidden')) {
                    $('.btn-add-image').removeClass('hidden');
                }
            });
        });
    </script>
</body>

</html>