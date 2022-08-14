<?php require 'View/SharedAdmin/_admin_html.php'; ?>
<title><?php echo isset($data['item']['item_name']) ? ucwords($data['item']['item_name']) . ' | ' : ''; ?><?php echo BRAND; ?> - Yönetim Paneli</title>
<?php require 'View/SharedAdmin/_admin_head.php'; ?>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
<a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Items">
    <span class="btn-header-text">Ürünlere Geri Dön</span>
    <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
</a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
<section>
    <div class="container">
        <?php if (empty($data['notfound_item'])) : ?>
            <?php $item_name = isset($data['item']['item_name']) ? ucwords($data['item']['item_name']) : ''; ?>
            <?php $item_id = isset($data['item']['id']) ? $data['item']['id'] : ''; ?>
            <div class="row-space-center">
                <div class="row-left">
                    <h1 class="main-title">"<?php echo $item_name; ?>" Detayları ve Güncelleme</h1>
                    <div class="row-adjacent item-stars">
                        <span><i class="fas fa-star item-star checked"></i></span>
                        <span><i class="fas fa-star item-star checked"></i></span>
                        <span><i class="fas fa-star-half-alt item-star-half checked"></i></span>
                        <span><i class="fas fa-star item-star"></i></span>
                        <span><i class="fas fa-star item-star"></i></span>
                    </div>
                </div>
                <div class="row-right">
                    <div class="row-space">
                        <a class="btn-comments btn-1" href="<?php echo URL.'/AdminController/ItemComments/'.$data['item']['item_url']; ?>" title="Ürüne Yapılan Yorumlar">Yorumlar</a>
                        <button id="btn_delete" class="btn-form btn-3" data-id="<?php echo $item_id; ?>" title="<?php echo $item_name; ?> Ürününü Sil">Ürünü Sil</button>
                    </div>
                </div>
            </div>
            <div class="tree">
                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Items">ürünler</a>
                <?php if (isset($data['item']['item_url'])) : ?>
                    <span class="seperater">&gt;</span>
                    <a class="tree-guide" href="<?php echo URL; ?>/AdminController/ItemDetails/<?php echo $data['item']['item_url']; ?>"><?php echo $data['item']['item_url']; ?></a>
                <?php endif; ?>
            </div>
            <form action="<?php echo URL; ?>/AdminController/ItemUpdate" method="POST" autocomplete="off" novalidate enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $item_id; ?>">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 50%;" class="table-align-left text-theme-primary">Ad</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_name')) : ?>
                                <td style="width: 50%;"><input type="text" class="form-input input-danger" name="item_name" placeholder="Ürün adını girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_name'])) : ?>
                                <td style="width: 50%;"><input type="text" class="form-input" name="item_name" value="<?php echo $data['item']['item_name']; ?>" placeholder="Ürün adını girin"></td>
                            <?php else : ?>
                                <td style="width: 50%;"><input type="text" class="form-input" name="item_name" placeholder="Ürün adını girin" autofocus></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Fiyat (₺)</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_price')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_price" placeholder="1.200,99 ₺ için 1200.99 girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_price'])) : ?>
                                <td><input type="text" class="form-input" name="item_price" value="<?php echo $data['item']['item_price']; ?>" placeholder="1.200,99 ₺ için 1200.99 girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_price" placeholder="1.200,99 ₺ için 1200.99 girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">İndirimli Fiyat (₺)</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_discount_price')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_discount_price" placeholder="1.100,99 ₺ için 1100.99 girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_discount_price'])) : ?>
                                <td><input type="text" class="form-input" name="item_discount_price" value="<?php echo $data['item']['item_discount_price']; ?>" placeholder="1.100,99 ₺ için 1100.99 girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_discount_price" placeholder="1.100,99 ₺ için 1100.99 girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Anasayfada Çıksın Mı?</td>
                            <td class="form-checkbox">
                                <label for="item_ishome">
                                    <input type="checkbox" class="checkbox" id="item_ishome" name="item_ishome" <?php echo (isset($data['item']['item_ishome']) && ($data['item']['item_ishome'] == 1)) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Ürün Satışta Mı?</td>
                            <td class="form-checkbox">
                                <label for="item_insale">
                                    <input type="checkbox" class="checkbox" id="item_insale" name="item_insale" <?php echo (isset($data['item']['item_insale']) && ($data['item']['item_insale'] == 1)) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Kargo Süresi (Gün)</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_in_shipping')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_in_shipping" placeholder="7 gün için 7 girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_in_shipping'])) : ?>
                                <td><input type="text" class="form-input" name="item_in_shipping" value="<?php echo $data['item']['item_in_shipping']; ?>" placeholder="7 gün için 7 girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_in_shipping" placeholder="7 gün için 7 girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Materyal</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_material')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_material" placeholder="Ürün materyalini girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_material'])) : ?>
                                <td><input type="text" class="form-input" name="item_material" value="<?php echo $data['item']['item_material']; ?>" placeholder="Ürün materyalini girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_material" placeholder="Ürün materyalini girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Kumaş Tipi</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_fabric_type')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_fabric_type" placeholder="Kumaş tipini girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_fabric_type'])) : ?>
                                <td><input type="text" class="form-input" name="item_fabric_type" value="<?php echo $data['item']['item_fabric_type']; ?>" placeholder="Kumaş tipini girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_fabric_type" placeholder="Kumaş tipini girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Model</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_model')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_model" placeholder="Ürün modelini girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_model'])) : ?>
                                <td><input type="text" class="form-input" name="item_model" value="<?php echo $data['item']['item_model']; ?>" placeholder="Ürün modelini girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_model" placeholder="Ürün modelini girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Yaka Tipi</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_lapel')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_lapel" placeholder="Yaka tipini girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_lapel'])) : ?>
                                <td><input type="text" class="form-input" name="item_lapel" value="<?php echo $data['item']['item_lapel']; ?>" placeholder="Yaka tipini girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_lapel" placeholder="Yaka tipini girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Kalınlık</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_thickness')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_thickness" placeholder="Ürün kalınlığını girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_thickness'])) : ?>
                                <td><input type="text" class="form-input" name="item_thickness" value="<?php echo $data['item']['item_thickness']; ?>" placeholder="Ürün kalınlığını girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_thickness" placeholder="Ürün kalınlığını girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Desen</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_pattern')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_pattern" placeholder="Deseni girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_pattern'])) : ?>
                                <td><input type="text" class="form-input" name="item_pattern" value="<?php echo $data['item']['item_pattern']; ?>" placeholder="Deseni girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_pattern" placeholder="Deseni girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Kol Tipi</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_sleeve_type')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_sleeve_type" placeholder="Kol tipini girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_sleeve_type'])) : ?>
                                <td><input type="text" class="form-input" name="item_sleeve_type" value="<?php echo $data['item']['item_sleeve_type']; ?>" placeholder="Kol tipini girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_sleeve_type" placeholder="Kol tipini girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Kol Uzunluğu</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_sleeve_length')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_sleeve_length" placeholder="Kol uzunluğunu girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_sleeve_length'])) : ?>
                                <td><input type="text" class="form-input" name="item_sleeve_length" value="<?php echo $data['item']['item_sleeve_length']; ?>" placeholder="Kol uzunluğunu girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_sleeve_length" placeholder="Kol uzunluğunu girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Yıkama Stili</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_washing_style')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_washing_style" placeholder="Yıkama stilini girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_washing_style'])) : ?>
                                <td><input type="text" class="form-input" name="item_washing_style" value="<?php echo $data['item']['item_washing_style']; ?>" placeholder="Yıkama stilini girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_washing_style" placeholder="Yıkama stilini girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Modelin Bedeni</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_model_size')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_model_size" placeholder="XL için xl girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_model_size'])) : ?>
                                <td><input type="text" class="form-input" name="item_model_size" value="<?php echo $data['item']['item_model_size']; ?>" placeholder="Medium için m girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_model_size" placeholder="Medium için m girin"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Modelin Uzunluğu (cm)</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_model_height')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_model_height" placeholder="Örnek: 180" autofocus></td>
                            <?php elseif (isset($data['item']['item_model_height'])) : ?>
                                <td><input type="text" class="form-input" name="item_model_height" value="<?php echo $data['item']['item_model_height']; ?>" placeholder="Örnek: 180"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_model_height" placeholder="Örnek: 180"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Modelin Ağırlığı (kg)</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_model_weight')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_model_weight" placeholder="Tekrar Girin Örnek: 80" autofocus></td>
                            <?php elseif (isset($data['item']['item_model_weight'])) : ?>
                                <td><input type="text" class="form-input" name="item_model_weight" value="<?php echo $data['item']['item_model_weight']; ?>" placeholder="Örnek: 80"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_model_weight" placeholder="Örnek: 80"></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Stok Adedi</td>
                            <?php if (isset($data['error_input']) && ($data['error_input'] === 'item_total_number')) : ?>
                                <td><input type="text" class="form-input input-danger" name="item_total_number" placeholder="Stok adedini girin" autofocus></td>
                            <?php elseif (isset($data['item']['item_total_number'])) : ?>
                                <td><input type="text" class="form-input" name="item_total_number" value="<?php echo $data['item']['item_total_number']; ?>" placeholder="Stok adedini girin"></td>
                            <?php else : ?>
                                <td><input type="text" class="form-input" name="item_total_number" placeholder="Stok adedini girin"></td>
                            <?php endif; ?>
                        </tr>
                        <?php if (isset($data['filters'])) : ?>
                            <?php foreach ($data['filters'] as $key => $filter) : ?>
                                <?php if ($filter['filter_type'] == 1) : ?>
                                    <?php foreach ($filter['filters_sub'] as $filter_sub) : ?>
                                        <tr>
                                            <?php $filter_sub_name = isset($filter_sub['filter_sub_name']) ? $filter_sub['filter_sub_name'] : ''; ?>
                                            <?php $filter_sub_name_for_db = str_replace(' ', '_', $filter_sub['filter_sub_name']); ?>
                                            <?php $key_for_db = str_replace(' ', '_', $key); ?>
                                            <td class="table-align-left text-theme-primary"><?php echo ucwords($key) . ' (' . strtoupper($filter_sub_name) . ')'; ?></td>
                                            <?php if (isset($data['error_input']) && ($data['error_input'] === $key_for_db . '_' . $filter_sub_name)) : ?>
                                                <td><input type="text" class="form-input input-danger" name="<?php echo $key_for_db . '_' . $filter_sub_name; ?>" placeholder="<?php echo strtoupper($filter_sub_name); ?> adedini girin" autofocus></td>
                                            <?php elseif (isset($data['item'][$key_for_db . '_' . $filter_sub_name_for_db])) : ?>
                                                <td><input type="text" class="form-input" name="<?php echo $key_for_db . '_' . $filter_sub_name_for_db; ?>" value="<?php echo $data['item'][$key_for_db . '_' . $filter_sub_name_for_db]; ?>" placeholder="<?php echo strtoupper($filter_sub_name); ?> adedini girin"></td>
                                            <?php else : ?>
                                                <td><input type="text" class="form-input" name="<?php echo $key_for_db . '_' . $filter_sub_name_for_db; ?>" placeholder="<?php echo strtoupper($filter_sub_name); ?> adedini girin"></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php elseif ($filter['filter_type'] == 2) : ?>
                                    <tr>
                                        <?php $key_for_db = str_replace(' ', '_', $key); ?>
                                        <td class="table-align-left text-theme-primary"><?php echo ucwords($key); ?></td>
                                        <?php if (isset($data['error_input']) && ($data['error_input'] === $key_for_db)) : ?>
                                            <td>
                                                <select class="table-select text-danger" name="<?php echo $key_for_db; ?>">
                                                    <option value="default" selected><?php echo ucwords($key); ?> Seçin</option>
                                                    <?php foreach ($filter['filters_sub'] as $filter_sub) : ?>
                                                        <?php $filter_sub_name = isset($filter_sub['filter_sub_name']) ? $filter_sub['filter_sub_name'] : ''; ?>
                                                        <option value="<?php echo $filter_sub_name; ?>"><?php echo ucwords($filter_sub_name); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        <?php else : ?>
                                            <td>
                                                <select class="table-select" name="<?php echo $key_for_db; ?>">
                                                    <option value="default" selected><?php echo ucwords($key); ?> Seçin</option>
                                                    <?php foreach ($filter['filters_sub'] as $filter_sub) : ?>
                                                        <?php $filter_sub_name = isset($filter_sub['filter_sub_name']) ? $filter_sub['filter_sub_name'] : ''; ?>
                                                        <?php $item_selected = isset($data['item'][$key_for_db]) ? $data['item'][$key_for_db] : ''; ?>
                                                        <option value="<?php echo $filter_sub_name; ?>" <?php echo ($filter_sub['id'] == $item_selected) ? 'selected' : ''; ?>><?php echo ucwords($filter_sub_name); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php elseif ($filter['filter_type'] == 3) : ?>
                                    <?php foreach ($filter['filters_sub'] as $filter_sub) : ?>
                                        <tr>
                                            <?php $filter_sub_name = isset($filter_sub['filter_sub_name']) ? $filter_sub['filter_sub_name'] : ''; ?>
                                            <?php $filter_sub_name_for_db = str_replace(' ', '_', $filter_sub['filter_sub_name']); ?>
                                            <?php $key_for_db = str_replace(' ', '_', $key); ?>
                                            <td class="table-align-left text-theme-primary"><?php echo ucwords($key) . ' (' . ucwords($filter_sub_name) . ')'; ?></td>
                                            <td class="form-checkbox">
                                                <label for="<?php echo $key_for_db . '_' . $filter_sub_name; ?>">
                                                    <input type="checkbox" class="checkbox" id="<?php echo $key_for_db . '_' . $filter_sub_name; ?>" name="<?php echo $key_for_db . '_' . $filter_sub_name_for_db; ?>" <?php echo isset($data['item'][$key_for_db . '_' . $filter_sub_name_for_db]) && $data['item'][$key_for_db . '_' . $filter_sub_name_for_db] == 1 ? 'checked' : ''; ?>>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr>
                            <td class="table-align-left text-theme-primary">Oluşturulma Tarihi</td>
                            <td class="table-align-left"><?php echo isset($data['item']['item_date_added']) ? date('d/m/Y H.i.s', strtotime($data['item']['item_date_added'])) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td class="table-align-left text-theme-primary">Son Güncellenme Tarihi</td>
                            <td class="table-align-left"><?php echo isset($data['item']['item_date_updated']) ? date('d/m/Y H.i.s', strtotime($data['item']['item_date_updated'])) : '-'; ?></td>
                        </tr>
                    </tbody>
                </table>
                <h2 class="second-title">Ürün Görsellerini Güncelle</h2>
                <?php if (isset($data['item']['item_images'])) : ?>
                    <div class="unselectable">
                        <?php $image_count = count($data['item']['item_images']); ?>
                        <div id="item_images_container-extra" class="row-wrap">
                            <?php foreach ($data['item']['item_images'] as $item_images) : ?>
                                <div class="item-image-container">
                                    <label for="upload-image-<?php echo $item_images[0]; ?>" class="image-label">
                                        <span class="image-label-text"><?php echo $item_images[0]; ?>. Görseli Güncellemek İçin Tıklayın</span>
                                        <img src="<?php echo URL . ITEM_IMAGES_PATH . $item_id . '/' . $item_images[1]; ?>" class="uploaded-image">
                                        <div class="upload-image-icon-container">
                                            <i class="fas fa-camera upload-image-icon"></i>
                                        </div>
                                    </label>
                                    <input id="upload-image-<?php echo $item_images[0]; ?>" class="item-image-input" type="file" name="item_image[]" value="<?php echo $item_images[1]; ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (isset($data['image_error_message'])) : ?>
                            <div class="image-error-container"><span class="image-error"><?php echo $data['image_error_message']; ?></span></div>
                        <?php endif; ?>
                        <div class="clon-image" id="add-extra-image" title="Fazladan Ürün Görseli Ekle"></div>
                        <div class="remove-clon-image disable" title="Sonradan Eklenmiş Görselleri Sil">Sonradan Eklenmiş Görselleri Sil</div>
                    </div>
                <?php endif; ?>
                <div class="row-space">
                    <div class="row-right">
                        <input id="btn_update" class="btn-form btn-2" type="submit" name="update_item" value="Ürünü Güncelle" title="<?php echo $item_name; ?> Ürününü Güncelle">
                        <span class="form-success-uploading disable">Ürün Güncelleniyor...</span>
                    </div>
                </div>
            </form>
        <?php else : ?>
            <div class="notfound-container">
                <span class="text-notfound">Ürün Bulunamadı</span>
                <a class="btn-notfound row-center" href="<?php echo URL; ?>/AdminController/Items">
                    <span class="btn-header-text">Ürünlere Geri Dön</span>
                    <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
                </a>
            </div>
        <?php endif; ?>
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
<?php if (empty($data['notfound_item'])) : ?>
    <script>
        const btnUpdate = document.querySelector('#btn_update');
        const textLoading = document.querySelector('.form-success-uploading');
        btnUpdate.addEventListener('click', () => {
            btnUpdate.classList.add('disable');
            textLoading.classList.remove('disable');
        });
        let deletePopup = document.querySelector('.delete-popup');
        let confirmBtns = document.querySelector('.confirm-btns');
        let btnCancel = document.querySelector('.btn-cancel');
        let deleteBtn = document.querySelector('#btn_delete');
        let confirmTitle = document.querySelector('.confirm-title');
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
<?php if (isset($image_count)) : ?>
    <script>
        let k = <?php echo $image_count + 1; ?>;
        const addExtraImage = document.querySelector('#add-extra-image');
        const itemImages = document.querySelector('#item_images_container-extra');
        const removeClonImage = document.querySelector('.remove-clon-image');
        let j = k;
        if (j >= 3 && j <= 6) {
            addExtraImage.innerHTML = j + '. Görseli Ekle';
        } else {
            if (!addExtraImage.classList.contains('disable')) {
                addExtraImage.classList.add('disable');
            }
        }
        addExtraImage.addEventListener('click', (e) => {
            e.preventDefault();
            if (j >= 3 && j <= 6) {
                const div = document.createElement('div');
                div.setAttribute('class', 'item-image-container extra-div');
                const label = document.createElement('label');
                label.setAttribute('for', 'upload-image-' + j);
                label.setAttribute('class', 'image-label');
                div.appendChild(label);
                const span = document.createElement('span');
                span.setAttribute('class', 'image-label-text');
                span.innerHTML = j + '. Görseli Eklemek İçin Tıklayın';
                label.appendChild(span);
                const img = document.createElement('img');
                img.setAttribute('src', '');
                img.setAttribute('class', 'uploaded-image uploaded-image-extra');
                label.appendChild(img);
                const div2 = document.createElement('div');
                div2.setAttribute('class', 'upload-image-icon-container');
                const i = document.createElement('i');
                i.setAttribute('class', 'fas fa-camera upload-image-icon');
                div2.appendChild(i);
                label.appendChild(div2);
                const input = document.createElement('input');
                input.setAttribute('id', 'upload-image-' + j);
                input.setAttribute('class', 'item-image-input item-image-input-extra');
                input.setAttribute('type', 'file');
                input.setAttribute('name', 'item_image[]');
                div.appendChild(input);
                itemImages.appendChild(div);
                j++;
                if (j >= 3 && j < 7) {
                    addExtraImage.innerHTML = j + '. Görseli Ekle';
                } else {
                    if (!addExtraImage.classList.contains('disable')) {
                        addExtraImage.classList.add('disable');
                    }
                }
                if (removeClonImage.classList.contains('disable')) {
                    removeClonImage.classList.remove('disable');
                }
                let imageInputsExtra = document.querySelectorAll('.item-image-input-extra');
                let uploadedImagesExtra = document.querySelectorAll('.uploaded-image-extra');
                imageInputsExtra.forEach((imageInputExtra, i) => {
                    imageInputExtra.onchange = function() {
                        var item = this.files[0];
                        var reader = new FileReader();
                        reader.readAsDataURL(item);
                        reader.name = item.name;
                        reader.size = item.size;
                        reader.onload = function(event) {
                            var img = new Image();
                            img.src = event.target.result;
                            img.size = event.target.size;
                            img.onload = function(el) {
                                var elem = document.createElement('canvas');
                                elem.width = 800;
                                elem.height = 1200;
                                var ctx = elem.getContext('2d');
                                ctx.drawImage(el.target, 0, 0, elem.width, elem.height);
                                var srcEncoded = ctx.canvas.toDataURL('image/png', 1);
                                uploadedImagesExtra[i].src = srcEncoded;
                            }
                        }
                    };
                });
            }
        });
        let imageInputs = document.querySelectorAll('.item-image-input');
        let uploadedImages = document.querySelectorAll('.uploaded-image');
        imageInputs.forEach((imageInput, i) => {
            imageInput.onchange = function() {
                var item = this.files[0];
                var reader = new FileReader();
                reader.readAsDataURL(item);
                reader.name = item.name;
                reader.size = item.size;
                reader.onload = function(event) {
                    var img = new Image();
                    img.src = event.target.result;
                    img.size = event.target.size;
                    img.onload = function(el) {
                        var elem = document.createElement('canvas');
                        elem.width = 800;
                        elem.height = 1200;
                        var ctx = elem.getContext('2d');
                        ctx.drawImage(el.target, 0, 0, elem.width, elem.height);
                        var srcEncoded = ctx.canvas.toDataURL('image/png', 1);
                        uploadedImages[i].src = srcEncoded;
                    }
                }
            };
        });
        removeClonImage.addEventListener('click', (e) => {
            e.preventDefault();
            let extraDivs = document.querySelectorAll('.extra-div');
            extraDivs.forEach(extraDiv => {
                extraDiv.remove();
            });
            j = k;
            if (addExtraImage.classList.contains('disable')) {
                addExtraImage.classList.remove('disable');
            }
            addExtraImage.innerHTML = j + '. Görseli Ekle';
            if (!removeClonImage.classList.contains('disable')) {
                removeClonImage.classList.add('disable');
            }
        });
    </script>
<?php endif; ?>
</body>

</html>