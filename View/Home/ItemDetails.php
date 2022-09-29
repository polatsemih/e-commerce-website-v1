<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo $web_data['item']['item_name'] . ' | ' . BRAND; ?></title>
    <meta name="robots" content="all" />
    <meta name="description" content="<?php echo $web_data['item']['item_description']; ?>" />
    <meta name="keywords" content="<?php echo $web_data['item']['item_keywords']; ?>" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <div class="container">
            <section class="section-details-main">
                <div class="col-5">
                    <figure id="zoom-figure-id" class="zoom-figure" style="background-image: url(<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/' . $web_data['item']['item_images'][0][1]; ?>);">
                        <img id="zoom-image-id" class="zoom-image" src="<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/' . $web_data['item']['item_images'][0][1]; ?>" alt="<?php echo $web_data['item']['item_name']; ?>">
                    </figure>
                    <div class="mini-images">
                        <i id="image-left-slider" class="fas fa-caret-left icon left"></i>
                        <i id="image-right-slider" class="fas fa-caret-right icon right"></i>
                        <?php $mini_image_index = 0; ?>
                        <?php foreach ($web_data['item']['item_images'] as $item_image) : ?>
                            <img class="mini-image<?php echo $item_image[1] == $web_data['item']['item_images'][0][1] ? ' selected' : ''; ?>" src="<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/mini' . $item_image[1]; ?>" alt="<?php echo $web_data['item']['item_name']; ?>" data-id="<?php echo $item_image[1]; ?>" data-index="<?php echo $mini_image_index; ?>">
                            <?php $mini_image_index++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-7">
                    <div class="name-favorite-row">
                        <h1 class="item-name"><?php echo $web_data['item']['item_name']; ?></h1>
                        <?php if (!empty($web_data['authenticated_user'])) : ?>
                            <div class="favorite-wrapper">
                                <?php if (!empty($web_data['user_favorite_item'])) : ?>
                                    <form id="form-remove-from-favorites">
                                        <input type="hidden" name="item" value="<?php echo $web_data['item']['item_cart_id']; ?>">
                                        <button id="submit-remove-from-favorites" class="btn-add-to-favorites" type="submit" title="Ürünü favorilerimden kaldır">
                                            <i class="far fa-heart details-favorites-icon selected"></i>
                                        </button>
                                    </form>
                                <?php else : ?>
                                    <form id="form-add-to-favorites">
                                        <input type="hidden" name="item" value="<?php echo $web_data['item']['item_cart_id']; ?>">
                                        <button id="submit-add-to-favorites" class="btn-add-to-favorites" type="submit" title="Ürünü favorilerime ekle">
                                            <i class="far fa-heart details-favorites-icon"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <a class="favorites-container" href="<?php echo URL . URL_LOGIN . '?yonlendir=urun/' . $web_data['item']['item_url']; ?>" title="Ürünü favorilere ekleyebilmek için giriş yapmalısınız">
                                <i class="far fa-heart details-favorites-icon"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="item-price-row">
                        <span class="item-old-price"><?php echo $web_data['item']['item_price']; ?> ₺</span>
                        <span class="item-new-price"><?php echo $web_data['item']['item_discount_price']; ?> ₺</span>
                    </div>
                    <form id="form-add-to-cart">
                        <div class="quantity-row">
                            <span class="text">Adet Seçin</span>
                            <div id="details-item-quantity" class="item-quantity">
                                <select class="item-select" name="item_quantity">
                                    <option id="details-option-1" value="1" selected></option>
                                    <option id="details-option-2" value="2"></option>
                                    <option id="details-option-3" value="3"></option>
                                    <option id="details-option-4" value="4"></option>
                                    <option id="details-option-5" value="5"></option>
                                    <option id="details-option-6" value="6"></option>
                                    <option id="details-option-7" value="7"></option>
                                    <option id="details-option-8" value="8"></option>
                                    <option id="details-option-9" value="9"></option>
                                    <option id="details-option-10" value="10"></option>
                                </select>
                                <span id="select-text" class="select-text">1</span>
                                <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                                <div id="details-select" class="details-select">
                                    <span class="option" data-option="1">1</span>
                                    <span class="option" data-option="2">2</span>
                                    <span class="option" data-option="3">3</span>
                                    <span class="option" data-option="4">4</span>
                                    <span class="option" data-option="5">5</span>
                                    <span class="option" data-option="6">6</span>
                                    <span class="option" data-option="7">7</span>
                                    <span class="option" data-option="8">8</span>
                                    <span class="option" data-option="9">9</span>
                                    <span class="option" data-option="10">10</span>
                                </div>
                            </div>
                        </div>
                        <span class="size-title">Beden Seçin</span>
                        <div class="sizes-row">
                            <?php foreach ($web_data['sizes'] as $size) : ?>
                                <?php if ($web_data['item'][$size['size_url']] > 0) : ?>
                                    <label class="label-size" for="size-<?php echo $size['size_url']; ?>">
                                        <?php if ($web_data['item'][$size['size_url']] <= DETAILS_SIZE_REMAIN_ITEM_LIMIT) : ?>
                                            <div class="size-info">
                                                <span class="text text-margin"><?php echo $size['size_name']; ?></span>
                                                <span class="text">Kalan Adet: <?php echo $web_data['item'][$size['size_url']]; ?></span>
                                            </div>
                                        <?php else : ?>
                                            <div class="size-info">
                                                <span class="text"><?php echo $size['size_name']; ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <input class="size-radio" type="radio" name="item_size" id="size-<?php echo $size['size_url']; ?>" value="<?php echo $size['size_cart_id']; ?>">
                                        <span class="size-circle"><?php echo strtoupper($size['size_url']); ?></span>
                                    </label>
                                <?php else : ?>
                                    <label class="label-size" for="size-<?php echo $size['size_url']; ?>">
                                        <div class="size-info">
                                            <span class="text text-margin"><?php echo $size['size_name']; ?></span>
                                            <span class="text">Tükendi!</span>
                                        </div>
                                        <input class="size-radio" type="radio" id="size-<?php echo $size['size_url']; ?>" disabled>
                                        <span class="size-circle disable"><?php echo strtoupper($size['size_url']); ?></span>
                                    </label>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="item" value="<?php echo $web_data['item']['item_cart_id']; ?>">
                        <input id="submit-add-to-cart" class="btn-add-to-cart" type="submit" value="Sepete Ekle">
                    </form>
                    <ul class="info-nav">
                        <li class="info-item">En geç 20 gün içerisinde kargoya teslim.</li>
                        <li class="info-item">Ücretsiz değişim hakkı. Detaylar için <button id="btn-give-back-policy" class="btn-details-policy" title="Değişim Politakası">ürünümü nasıl değiştiririm?</button></li>
                        <div id="popup-wrapper" class="give-back-wrapper disable">
                            <div class="popup-container">
                                <div id="give-back-exit" class="popup-exit">
                                    <div class="exit">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                                <h4 class="title">İptal ve İade Koşulları</h4>
                                <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet? Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                                <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                                <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                                <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                            </div>
                        </div>
                        <li class="info-item">Bir üründen tek seferde en fazla 10 adet sipariş verilebilir.</li>
                        <li class="info-item">Görseldeki modelin Bedeni (<?php echo $web_data['item']['item_model_size']; ?>) - Uzunluğu (<?php echo $web_data['item']['item_model_height'] . ' cm'; ?>) - Kilosu (<?php echo $web_data['item']['item_model_weight'] . ' kg'; ?>)</li>
                    </ul>
                    <div id="continue-to-bottom" class="continue-to-bottom">
                        <span class="text">Ürünün Özellikleri</span>
                        <div class="arrow-wrapper">
                            <div class="arrow arrow-1"></div>
                            <div class="arrow arrow-2"></div>
                            <div class="arrow arrow-3"></div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="section-details-bottom" class="section-details-bottom">
                <div class="nav-details">
                    <div id="nav-properties-link" class="nav-details-con-1 nav-details-container active" title="Ürünün Özellikleri">
                        <span class="nav-details-text">Detaylar</span>
                        <span class="nav-details-line nav-details-line-1"></span>
                    </div>
                    <div id="nav-comments-link" class="nav-details-con-2 nav-details-container" title="Ürüne Yapılan Yorumlar">
                        <span id="btn-comment-count" class="nav-details-text">Yorumlar (<?php echo !empty($web_data['comment_count']) ? $web_data['comment_count'] : 0; ?>)</span>
                        <span class="nav-details-line nav-details-line-2"></span>
                    </div>
                </div>
                <div class="item-properties">
                    <h2 class="title">Ürünün Özellikleri</h2>
                    <div class="row">
                        <span class="text key">Koleksiyon</span>
                        <span class="text value"><?php echo $web_data['item']['item_collection']; ?></span>
                    </div>
                    <div class="row">
                        <span class="text key">Materyal</span>
                        <span class="text value"><?php echo $web_data['item']['item_material']; ?></span>
                    </div>
                    <div class="row">
                        <span class="text key">Kesim</span>
                        <span class="text value"><?php echo $web_data['item']['item_cut_model']; ?></span>
                    </div>
                    <div class="row">
                        <span class="text key">Kalınlık</span>
                        <span class="text value"><?php echo $web_data['item']['item_thickness']; ?></span>
                    </div>
                    <div class="row">
                        <span class="text key">Desen</span>
                        <span class="text value"><?php echo $web_data['item']['item_pattern']; ?></span>
                    </div>
                    <div class="row">
                        <span class="text key">Yaka</span>
                        <span class="text value"><?php echo $web_data['item']['item_lapel']; ?></span>
                    </div>
                    <div class="row">
                        <span class="text key">Kol Tipi</span>
                        <span class="text value"><?php echo $web_data['item']['item_sleeve_type']; ?></span>
                    </div>
                    <div class="row">
                        <span class="text key">Kol Uzunluğu</span>
                        <span class="text value"><?php echo $web_data['item']['item_sleeve_length']; ?></span>
                    </div>
                    <div class="row">
                        <span class="text key">Yıkma Stili</span>
                        <span class="text value"><?php echo $web_data['item']['item_washing_style']; ?></span>
                    </div>

                </div>
                <?php if (!empty($web_data['authenticated_user'])) : ?>
                    <div id="comment-popup-wrapper" class="comment-create-wrapper disable">
                        <div class="popup-container">
                            <div id="comment-create-exit" class="popup-exit">
                                <div class="exit">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <h3 class="title">Yorum Ekle</h3>
                            <form id="form-comment-create" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php if (!empty($web_data['form_token'])) : ?>
                                    <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                <?php endif; ?>
                                <input type="hidden" name="item_url" value="<?php echo $web_data['item']['item_url']; ?>">
                                <textarea id="textarea-comment-create" class="textarea" name="comment_text" placeholder="Ürün hakkındaki yorumunuzu burdan belirtebilirsiniz..."></textarea>
                                <div class="row-btn">
                                    <button id="btn-comment-create" class="btn-success right">Yorumu Ekle</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="comment-popup-wrapper" class="comment-reply-create-wrapper disable">
                        <div class="popup-container">
                            <div id="comment-reply-create-exit" class="popup-exit">
                                <div class="exit">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <h3 class="title">Yorumu Cevapla</h3>
                            <form id="form-comment-reply-create" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php if (!empty($web_data['form_token'])) : ?>
                                    <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                <?php endif; ?>
                                <input id="input-comment-id-comment-reply-create" type="hidden" name="comment_id">
                                <textarea id="textarea-comment-reply-create" class="textarea" name="comment_reply_text"></textarea>
                                <div class="row-btn">
                                    <button id="btn-comment-reply-create" class="btn-success right">Yorumu Cevapla</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="comment-popup-wrapper" class="comment-update-wrapper disable">
                        <div class="popup-container">
                            <div id="comment-update-exit" class="popup-exit">
                                <div class="exit">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <h3 class="title">Yorumu Güncelle</h3>
                            <form id="form-comment-update" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php if (!empty($web_data['form_token'])) : ?>
                                    <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                <?php endif; ?>
                                <input id="input-comment-id-comment-update" type="hidden" name="comment_id">
                                <textarea id="textarea-comment-update" class="textarea" name="comment_text"></textarea>
                                <div class="row-btn">
                                    <button id="btn-comment-update" class="btn-warning right">Yorumu Güncelle</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="comment-popup-wrapper" class="comment-reply-update-wrapper disable">
                        <div class="popup-container">
                            <div id="comment-reply-update-exit" class="popup-exit">
                                <div class="exit">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <h3 class="title">Yorumu Güncelle</h3>
                            <form id="form-comment-reply-update" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php if (!empty($web_data['form_token'])) : ?>
                                    <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                <?php endif; ?>
                                <input id="input-comment-id-comment-reply-update" type="hidden" name="comment_id">
                                <input id="input-comment-reply-id-comment-reply-update" type="hidden" name="comment_reply_id">
                                <textarea id="textarea-comment-reply-update" class="textarea" name="comment_reply_text"></textarea>
                                <div class="row-btn">
                                    <button id="btn-comment-reply-update" class="btn-warning right">Yorumu Güncelle</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="comment-popup-wrapper" class="comment-delete-wrapper disable">
                        <div class="popup-container">
                            <div id="comment-delete-exit" class="popup-exit">
                                <div class="exit">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <h3 class="title center">Yorumu Silmek İstediğinizden Emin Misiniz?</h3>
                            <form id="form-comment-delete" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php if (!empty($web_data['form_token'])) : ?>
                                    <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                <?php endif; ?>
                                <input id="input-comment-id-comment-delete" type="hidden" name="comment_id">
                                <div class="delete-row">
                                    <button id="btn-comment-delete-cancel" class="btn-warning padding m-right" title="Silme İşlemini İptal Et">İPTAL</button>
                                    <button id="btn-comment-delete" class="btn-danger padding" title="Silme İşlemini Onayla">SİL</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="comment-popup-wrapper" class="comment-reply-delete-wrapper disable">
                        <div class="popup-container">
                            <div id="comment-reply-delete-exit" class="popup-exit">
                                <div class="exit">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <h3 class="title center">Yorumu Silmek İstediğinizden Emin Misiniz?</h3>
                            <form id="form-comment-reply-delete" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php if (!empty($web_data['form_token'])) : ?>
                                    <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                <?php endif; ?>
                                <input id="input-comment-id-comment-reply-delete" type="hidden" name="comment_id">
                                <input id="input-comment-reply-id-comment-reply-delete" type="hidden" name="comment_reply_id">
                                <div class="delete-row">
                                    <button id="btn-comment-reply-delete-cancel" class="btn-warning padding m-right" title="Silme İşlemini İptal Et">İPTAL</button>
                                    <button id="btn-comment-reply-delete" class="btn-danger padding" title="Silme İşlemini Onayla">SİL</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="details-comments disable">
                    <div class="details-comments-row">
                        <h2 id="title-comment-count" class="title">Ürüne Yapılan Yorumlar (<?php echo !empty($web_data['comment_count']) ? $web_data['comment_count'] : 0; ?>)</h2>
                        <?php if (empty($web_data['authenticated_user'])) : ?>
                            <a class="btn-success" href="<?php echo URL . URL_LOGIN . '?yonlendir=urun/' . $web_data['item']['item_url']; ?>">Yorum Ekle</a>
                        <?php else : ?>
                            <button id="btn-popup-new-comment" class="btn-success">Yorum Ekle</button>
                        <?php endif; ?>
                    </div>
                    <div id="insert-new-comment">
                        <?php if (!empty($web_data['comment_not_found'])) : ?>
                            <div class="comment-container comment-not-found-container">
                                <span class="comment-not-found-text">Henüz ürüne yapılmış bir yorum yok</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($web_data['comment_not_found'])) : ?>
                        <div id="comments-loading" class="items-loading-wrapper disable">
                            <div class="items-loading-container">
                                <div class="circle-row">
                                    <div class="circle circle-1"></div>
                                    <div class="circle circle-2"></div>
                                    <div class="circle circle-3"></div>
                                </div>
                                <div class="shadow shadow-1"></div>
                                <div class="shadow shadow-2"></div>
                                <div class="shadow shadow-3"></div>
                                <span class="text">Sıradaki Yorumlar Yükleniyor</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php if (!empty($web_data['relevant_items'])) : ?>
                <section class="section-home-items relevant-items">
                    <h3 class="main-title">Benzer Diğer Ürünler</h3>
                    <div class="row">
                        <span id="arrow-left" class="arrow arrow-left"><i class="fas fa-chevron-left icon"></i></span>
                        <span id="arrow-right" class="arrow arrow-right"><i class="fas fa-chevron-right icon"></i></span>
                        <?php foreach ($web_data['relevant_items'] as $relevant_items) : ?>
                            <div class="col">
                                <a href="<?php echo URL . URL_ITEM_DETAILS . '/' . $relevant_items['item_url']; ?>">
                                    <div class="card-item">
                                        <div class="card-image-container">
                                            <img class="card-image" src="<?php echo URL . 'assets/images/items/' . $relevant_items['item_images_path'] . '/' . $relevant_items['item_images']; ?>" alt="<?php echo $relevant_items['item_name']; ?>">
                                        </div>
                                        <div class="card-infos">
                                            <span class="card-text" title="Ürünün Adı"><?php echo $relevant_items['item_name']; ?></span>
                                            <div class="row-price">
                                                <div class="left">
                                                    <span class="card-price card-old-price" title="Ürünün Eski Fiyatı"><?php echo $relevant_items['item_price']; ?> ₺</span>
                                                    <span class="card-price card-new-price" title="Ürünün İndirimli Güncel Fiyatı"><?php echo $relevant_items['item_discount_price']; ?> ₺</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-go-details-container">
                                            <span class="card-go-details" title="Ürünün Detayları" href="<?php echo URL . URL_ITEM_DETAILS . '/' . $relevant_items['item_url']; ?>">Detaylar<i class="fas fa-angle-right"></i></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
            <div class="details-tree">
                <a href="<?php echo URL; ?>" class="text">Anasayfa</a>
                <span class="icon">></span>
                <a href="<?php echo URL . URL_ITEMS . '/' . $web_data['selected_gender_url']; ?>" class="text"><?php echo $web_data['selected_gender_name']; ?></a>
                <span class="icon">></span>
                <a href="<?php echo URL . URL_ITEM_DETAILS . '/' . $web_data['item']['item_url']; ?>" class="text"><?php echo $web_data['item']['item_name']; ?></a>
            </div>
        </div>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <?php if (!empty($web_data['relevant_items'])) : ?>
        <script src="<?php echo URL; ?>assets/js/item_slider.js"></script>
    <?php endif; ?>
    <?php if (!empty($web_data['cookie_cart'])) : ?>
        <script src="<?php echo URL; ?>assets/js/header_cart.js"></script>
    <?php endif; ?>
    <script>
        function addNoScroll() {
            if (!bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.add('noscroll');
            }
        }

        function removeNoScroll() {
            if (bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.remove('noscroll');
            }
        }
        const zoomFigure = document.getElementById('zoom-figure-id');
        zoomFigure.addEventListener('mousemove', (e) => {
            e.preventDefault();
            var selectedZoomImage = e.currentTarget;
            selectedZoomImage.style.backgroundPosition = (e.offsetX / selectedZoomImage.offsetWidth * 100) + '% ' + (e.offsetY / selectedZoomImage.offsetHeight * 100) + '%';
        });
        var zoomIndex = 0;
        const miniImages = document.querySelectorAll('.mini-image');
        const zoomImage = document.getElementById('zoom-image-id');
        document.getElementById('image-right-slider').addEventListener('click', (e) => {
            e.preventDefault();
            if (miniImages[zoomIndex].classList.contains('selected')) {
                miniImages[zoomIndex].classList.remove('selected');
                if (zoomIndex == miniImages.length - 1) {
                    zoomIndex = 0;
                } else {
                    zoomIndex++;
                }
                miniImages[zoomIndex].classList.add('selected');
                zoomImage.setAttribute('src', '<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/'; ?>' + miniImages[zoomIndex].dataset.id);
                zoomFigure.style.backgroundImage = "url(" + '<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/'; ?>' + miniImages[zoomIndex].dataset.id + ")";
            }
        });
        document.getElementById('image-left-slider').addEventListener('click', (e) => {
            e.preventDefault();
            if (miniImages[zoomIndex].classList.contains('selected')) {
                miniImages[zoomIndex].classList.remove('selected');
                if (zoomIndex == 0) {
                    zoomIndex = miniImages.length - 1;
                } else {
                    zoomIndex--;
                }
                miniImages[zoomIndex].classList.add('selected');
                zoomImage.setAttribute('src', '<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/'; ?>' + miniImages[zoomIndex].dataset.id);
                zoomFigure.style.backgroundImage = "url(" + '<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/'; ?>' + miniImages[zoomIndex].dataset.id + ")";
            }
        });
        miniImages.forEach(miniImage => {
            miniImage.addEventListener('click', (e) => {
                e.preventDefault();
                if (miniImages[zoomIndex].classList.contains('selected')) {
                    miniImages[zoomIndex].classList.remove('selected');
                    zoomIndex = miniImage.dataset.index;
                    miniImages[miniImage.dataset.index].classList.add('selected');
                    zoomImage.setAttribute('src', '<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/'; ?>' + miniImages[zoomIndex].dataset.id);
                    zoomFigure.style.backgroundImage = "url(" + '<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/'; ?>' + miniImages[zoomIndex].dataset.id + ")";
                }
            });
        });
        document.getElementById('details-item-quantity').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('details-select').classList.toggle('active');
        });
        document.querySelectorAll('.item-quantity .details-select .option').forEach(detailsSelectOption => {
            detailsSelectOption.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('details-option-' + detailsSelectOption.dataset.option).selected = true;
                document.getElementById('select-text').innerHTML = detailsSelectOption.dataset.option;
            });
        });
        document.querySelectorAll('.label-size').forEach(labelSize => {
            labelSize.addEventListener('mouseover', () => {
                if (!labelSize.classList.contains('hovered')) {
                    labelSize.classList.add('hovered');
                }
            });
            labelSize.addEventListener('mouseout', () => {
                if (labelSize.classList.contains('hovered')) {
                    labelSize.classList.remove('hovered');
                }
            });
        });
        const giveBackWrapper = document.querySelector('.give-back-wrapper');
        document.getElementById('btn-give-back-policy').addEventListener('click', (e) => {
            e.preventDefault();
            if (giveBackWrapper.classList.contains('disable')) {
                giveBackWrapper.classList.remove('disable')
            }
            addNoScroll();
        });
        document.getElementById('give-back-exit').addEventListener('click', (e) => {
            e.preventDefault();
            if (!giveBackWrapper.classList.contains('disable')) {
                giveBackWrapper.classList.add('disable');
            }
            removeNoScroll();
        });
        giveBackWrapper.addEventListener('mousedown', (e) => {
            e.preventDefault();
            if (e.target.classList == 'give-back-wrapper') {
                if (!giveBackWrapper.classList.contains('disable')) {
                    giveBackWrapper.classList.add('disable');
                }
                removeNoScroll();
            }
        });
        document.getElementById('continue-to-bottom').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('section-details-bottom').scrollIntoView();
        });
        const detailsComments = document.querySelector('.details-comments');
        const itemProperties = document.querySelector('.item-properties');
        const navPropertiesLink = document.getElementById('nav-properties-link');
        const navCommentsLink = document.getElementById('nav-comments-link');
        navPropertiesLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (itemProperties.classList.contains('disable')) {
                itemProperties.classList.remove('disable');
            }
            if (!detailsComments.classList.contains('disable')) {
                detailsComments.classList.add('disable');
            }
            if (!navPropertiesLink.classList.contains('active')) {
                navPropertiesLink.classList.add('active');
            }
            if (navCommentsLink.classList.contains('active')) {
                navCommentsLink.classList.remove('active');
            }
        });
        navCommentsLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (!itemProperties.classList.contains('disable')) {
                itemProperties.classList.add('disable');
            }
            if (detailsComments.classList.contains('disable')) {
                detailsComments.classList.remove('disable');
            }
            if (!navCommentsLink.classList.contains('active')) {
                navCommentsLink.classList.add('active');
            }
            if (navPropertiesLink.classList.contains('active')) {
                navPropertiesLink.classList.remove('active');
            }
        });
        <?php if (!empty($web_data['authenticated_user'])) : ?>
            const commentCreateWrapper = document.querySelector('.comment-create-wrapper');
            document.getElementById('btn-popup-new-comment').addEventListener('click', (e) => {
                e.preventDefault();
                if (commentCreateWrapper.classList.contains('disable')) {
                    commentCreateWrapper.classList.remove('disable');
                    document.getElementById('textarea-comment-create').focus();
                }
                addNoScroll();
            });
            document.getElementById('comment-create-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentCreateWrapper.classList.contains('disable')) {
                    commentCreateWrapper.classList.add('disable');
                }
                removeNoScroll();
            });
            const commentReplyCreateWrapper = document.querySelector('.comment-reply-create-wrapper');
            document.getElementById('comment-reply-create-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentReplyCreateWrapper.classList.contains('disable')) {
                    commentReplyCreateWrapper.classList.add('disable');
                }
                removeNoScroll();
            });
            const commentUpdateWrapper = document.querySelector('.comment-update-wrapper');
            document.getElementById('comment-update-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentUpdateWrapper.classList.contains('disable')) {
                    commentUpdateWrapper.classList.add('disable');
                }
                removeNoScroll();
            });
            const commentReplyUpdateWrapper = document.querySelector('.comment-reply-update-wrapper');
            document.getElementById('comment-reply-update-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentReplyUpdateWrapper.classList.contains('disable')) {
                    commentReplyUpdateWrapper.classList.add('disable');
                }
                removeNoScroll();
            });
            const commentDeleteWrapper = document.querySelector('.comment-delete-wrapper');
            document.getElementById('comment-delete-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentDeleteWrapper.classList.contains('disable')) {
                    commentDeleteWrapper.classList.add('disable');
                }
                removeNoScroll();
            });
            document.getElementById('btn-comment-delete-cancel').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentDeleteWrapper.classList.contains('disable')) {
                    commentDeleteWrapper.classList.add('disable');
                }
                removeNoScroll();
            });
            const commentReplyDeleteWrapper = document.querySelector('.comment-reply-delete-wrapper');
            document.getElementById('comment-reply-delete-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentReplyDeleteWrapper.classList.contains('disable')) {
                    commentReplyDeleteWrapper.classList.add('disable');
                }
                removeNoScroll();
            });
            document.getElementById('btn-comment-reply-delete-cancel').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentReplyDeleteWrapper.classList.contains('disable')) {
                    commentReplyDeleteWrapper.classList.add('disable');
                }
                removeNoScroll();
            });
        <?php endif; ?>
    </script>
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
            var bodyElementjQ = $("body");

            function addNoScrolljQ() {
                if (!bodyElementjQ.hasClass('noscroll')) {
                    bodyElementjQ.addClass('noscroll');
                }
            }

            function removeNoScrolljQ() {
                if (bodyElementjQ.hasClass('noscroll')) {
                    bodyElementjQ.removeClass('noscroll');
                }
            }
            var commentReplyCreateWrapperjQ = $('.comment-reply-create-wrapper');
            var textareaCommentReplyCreatejQ = $('#textarea-comment-reply-create');
            var commentUpdateWrapperjQ = $('.comment-update-wrapper');
            var textareaCommentUpdatejQ = $('#textarea-comment-update');
            var commentReplyUpdateWrapperjQ = $('.comment-reply-update-wrapper');
            var textareaCommentReplyUpdatejQ = $('#textarea-comment-reply-update');
            var commentDeleteWrapperjQ = $('.comment-delete-wrapper');
            var commentReplyDeleteWrapperjQ = $('.comment-reply-delete-wrapper');
            var resetLocation = '<?php echo URL . URL_ITEM_DETAILS . '/' . $web_data['item']['item_url']; ?>';
            var form_token_comment = '<?php echo !empty($web_data['form_token']) ? $web_data['form_token'] : ''; ?>';
            var totalCommentNumber = <?php echo !empty($web_data['comment_count']) ? $web_data['comment_count'] : 0; ?>;
            var request;
            var requestUsable = true;
            var requestScroll = true;

            function setComments(responses) {
                $.each(responses, function(key, response) {
                    let xx1 = $("<div></div>").attr('id', 'comment-wrapper-' + response['id']);
                    let xx2 = $("<div></div>").addClass('comment-wrapper comment-expand-insert');
                    xx1.append(xx2);
                    let xx3 = $("<div></div>").addClass('comment-replies-wrapper disable');
                    xx1.append(xx3);
                    let xx4 = $("<div></div>").addClass('user-wrapper');
                    xx2.append(xx4);
                    let xx5 = $("<img>").addClass('image').attr('title', 'Yorumun Yazarının Profil Fotoğrafı').attr('src', '<?php echo URL . 'assets/images/users/'; ?>' + response['user_profile_image_path'] + '/' + response['user_profile_image']).attr('alt', response['user_first_name'] + ' ' + response['user_last_name']);
                    xx4.append(xx5);
                    let xx6 = $("<span></span>").addClass('name').attr('title', 'Yorumun Yazarı').append(response['user_first_name'] + ' ' + response['user_last_name']);
                    xx4.append(xx6);
                    let xx7 = $("<div></div>").addClass('user-bot-container');
                    xx4.append(xx7);
                    let xx8 = $("<span></span>").addClass('date').attr('title', 'Yorum Oluşturulma Tarihi').text(response['date_comment_created']);
                    xx7.append(xx8);
                    <?php if (!empty($web_data['authenticated_user'])) : ?>
                        let xx9 = $("<button></button>").addClass('btn-success mr-left btn-popup-new-comment-reply').attr('title', 'Yoruma Cevap Yaz').text('Cevapla');
                        xx7.append(xx9);
                        <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                            let xx10 = $("<form></form>").addClass('form-approve-comment');
                            let xx11 = $("<input></input>").addClass('input-token').attr('type', 'hidden').attr('name', 'form_token').attr('value', form_token_comment);
                            xx10.append(xx11);
                            let xx12 = $("<input></input>").attr('type', 'hidden').attr('name', 'comment_id').attr('value', response['id']);
                            xx10.append(xx12);
                            let xx13 = $("<label></label>").attr('for', 'comment-approved-' + response['id']).addClass('btn-warning mr-left label-flex btn-comment-approve').attr('title', 'Yorumun Herkese Görünürlüğünü Değiştir');
                            xx10.append(xx13);
                            if (response['is_comment_approved'] == 1) {
                                let xyx14 = $("<span></span>").addClass('checkmark-comment-text-asd').text('Açık');
                                let xx14 = $("<span></span>").addClass('checkmark-comment');
                                let xx15 = $("<input></input>").attr('type', 'checkbox').attr('id', 'comment-approved-' + response['id']).addClass('checkbox-comment').attr('name', 'is_comment_approved').prop('checked', true);
                                xx13.append(xx15);
                                xx13.append(xx14);
                                xx13.append(xyx14);
                            } else {
                                let xyx14 = $("<span></span>").addClass('checkmark-comment-text-asd').text('Kapalı');
                                let xx14 = $("<span></span>").addClass('checkmark-comment');
                                let xx15 = $("<input></input>").attr('type', 'checkbox').attr('id', 'comment-approved-' + response['id']).addClass('checkbox-comment').attr('name', 'is_comment_approved');
                                xx13.append(xx15);
                                xx13.append(xx14);
                                xx13.append(xyx14);
                            }
                            xx7.append(xx10);
                            if ('<?php echo $web_data['authenticated_user'] ?>' != response['user_id']) {
                                let xx18 = $("<button></button>").addClass('btn-danger mr-left btn-comment-delete-popup').attr('title', 'Yorumu Sil').text('Sil');
                                xx7.append(xx18);
                            }
                        <?php endif; ?>
                        <?php if (!empty($web_data['user_has_comment'])) : ?>
                            if ('<?php echo $web_data['authenticated_user'] ?>' == response['user_id']) {
                                let xx17 = $("<button></button>").addClass('btn-warning mr-left btn-comment-update-popup').attr('data-value', response['comment']).attr('title', 'Yorumu Güncelle').text('Güncelle');
                                xx7.append(xx17);
                                let xx18 = $("<button></button>").addClass('btn-danger mr-left btn-comment-delete-popup').attr('title', 'Yorumu Sil').text('Sil');
                                xx7.append(xx18);
                            }
                        <?php endif; ?>
                    <?php else : ?>
                        let xx19 = $("<a></a>").addClass('btn-success mr-left').attr('href', '<?php echo URL . URL_LOGIN . '?yonlendir=urun/' . $web_data['item']['item_url']; ?>').attr('title', 'Yoruma Cevap Yaz').text('Cevapla');
                        xx7.append(xx19);
                    <?php endif; ?>
                    let xx20 = $("<p></p>").addClass('comment-text').append(response['comment']);
                    xx2.append(xx20);
                    if (response.hasOwnProperty('comments_reply')) {
                        let xx21 = $("<div></div>").addClass('row-expand');
                        xx2.append(xx21);
                        let xx22 = $("<div></div>").addClass('expand-wrapper');
                        xx21.append(xx22);
                        let xx23 = $("<span></span>").addClass('expand-container').text('Yoruma yapılan cevapları göster');
                        xx22.append(xx23);
                        $.each(response['comments_reply'], function(key, comments_reply) {
                            let xx24 = $("<div></div>").attr('id', 'comment-reply-wrapper-' + comments_reply['id']).addClass('comment-wrapper comment-reply-wrapper');
                            let xx25 = $("<div></div>").addClass('user-wrapper');
                            xx24.append(xx25);
                            let xx26 = $("<img>").addClass('image').attr('title', 'Yorumun Yazarının Profil Fotoğrafı').attr('src', '<?php echo URL . 'assets/images/users/'; ?>' + comments_reply['user_profile_image_path'] + '/' + comments_reply['user_profile_image']).attr('alt', comments_reply['user_first_name'] + ' ' + comments_reply['user_last_name']);
                            xx25.append(xx26);
                            let xx27 = $("<span></span>").addClass('name').attr('title', 'Yorumun Yazarı').append(comments_reply['user_first_name'] + ' ' + comments_reply['user_last_name']);
                            xx25.append(xx27);
                            let xx28 = $("<div></div>").addClass('user-bot-container');
                            xx25.append(xx28);
                            let xx29 = $("<span></span>").addClass('date').attr('title', 'Yorum Oluşturulma Tarihi').text(comments_reply['date_comment_reply_created']);
                            xx28.append(xx29);
                            <?php if (!empty($web_data['authenticated_user'])) : ?>
                                <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                                    let xx30 = $("<form></form>").addClass('form-approve-comment-reply');
                                    let xx31 = $("<input></input>").addClass('input-token').attr('type', 'hidden').attr('name', 'form_token').attr('value', form_token_comment);
                                    xx30.append(xx31);
                                    let xx32 = $("<input></input>").attr('type', 'hidden').attr('name', 'comment_reply_id').attr('value', comments_reply['id']);
                                    xx30.append(xx32);
                                    let xx33 = $("<label></label>").attr('for', 'comment-reply-approved-' + comments_reply['id']).addClass('btn-warning mr-left label-flex btn-comment-reply-approve').attr('title', 'Yorumun Herkese Görünürlüğünü Değiştir');
                                    xx30.append(xx33);
                                    if (comments_reply['is_comment_reply_approved'] == 1) {
                                        let xyx34 = $("<span></span>").addClass('checkmark-comment-text-asd').text('Açık');
                                        let xx34 = $("<span></span>").addClass('checkmark-comment');
                                        let xx35 = $("<input></input>").attr('type', 'checkbox').attr('id', 'comment-reply-approved-' + comments_reply['id']).addClass('checkbox-comment').attr('name', 'is_comment_reply_approved').prop('checked', true);
                                        xx33.append(xx35);
                                        xx33.append(xx34);
                                        xx33.append(xyx34);
                                    } else {
                                        let xyx34 = $("<span></span>").addClass('checkmark-comment-text-asd').text('Kapalı');
                                        let xx34 = $("<span></span>").addClass('checkmark-comment');
                                        let xx35 = $("<input></input>").attr('type', 'checkbox').attr('id', 'comment-reply-approved-' + comments_reply['id']).addClass('checkbox-comment').attr('name', 'is_comment_reply_approved');
                                        xx33.append(xx35);
                                        xx33.append(xx34);
                                        xx33.append(xyx34);
                                    }
                                    xx28.append(xx30);
                                    if ('<?php echo $web_data['authenticated_user'] ?>' != comments_reply['user_id']) {
                                        let xx37 = $("<button></button>").addClass('btn-danger mr-left btn-comment-reply-delete-popup').attr('title', 'Yorumu Sil').text('Sil');
                                        xx28.append(xx37);
                                    }
                                <?php endif; ?>
                                <?php if (!empty($web_data['user_has_comment_reply'])) : ?>
                                    if ('<?php echo $web_data['authenticated_user'] ?>' == comments_reply['user_id']) {
                                        let xx38 = $("<button></button>").addClass('btn-warning mr-left btn-comment-reply-update-popup').attr('data-value', comments_reply['comment_reply']).attr('title', 'Yorumu Güncelle').text('Güncelle');
                                        xx28.append(xx38);
                                        let xx39 = $("<button></button>").addClass('btn-danger mr-left btn-comment-reply-delete-popup').attr('title', 'Yorumu Sil').text('Sil');
                                        xx28.append(xx39);
                                    }
                                <?php endif; ?>
                            <?php endif; ?>
                            let xx40 = $("<div></div>").addClass('reply-reference-container');
                            xx24.append(xx40);
                            let xx41 = $("<span></span>").addClass('reply-reference').append('@' + comments_reply['user_first_name'] + ' ' + comments_reply['user_last_name'] + ' isimli kullanıcının yorumunu cevapladı');
                            xx40.append(xx41);
                            let xx42 = $("<p></p>").addClass('comment-reply-text').append(comments_reply['comment_reply']);
                            xx24.append(xx42);
                            xx3.append(xx24);
                        });
                    }
                    $('#insert-new-comment').append(xx1);
                    <?php if (!empty($web_data['authenticated_user'])) : ?>
                        $('#comment-wrapper-' + response['id'] + ' .btn-popup-new-comment-reply').click(function(e) {
                            e.preventDefault();
                            if (commentReplyCreateWrapperjQ.hasClass('disable')) {
                                $('#input-comment-id-comment-reply-create').val(response['id']);
                                textareaCommentReplyCreatejQ.attr('placeholder', response['user_first_name'] + ' ' + response['user_last_name'] + ' isimli kullanıcının yorumuna burdan cevap yazabilirsiniz...');
                                commentReplyCreateWrapperjQ.removeClass('disable');
                                textareaCommentReplyCreatejQ.focus();
                            }
                            addNoScrolljQ();
                        });
                        <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                            $('#comment-wrapper-' + response['id'] + ' .btn-comment-approve').click(function(e) {
                                e.preventDefault();
                                if (requestUsable) {
                                    requestUsable = false;
                                    let approveCommentForm = $('#comment-wrapper-' + response['id'] + ' .form-approve-comment');
                                    let spanTextApproveCommentTextAsd = $('#comment-wrapper-' + response['id'] + ' .checkmark-comment-text-asd');
                                    let checkboxApproveComment = $('#comment-wrapper-' + response['id'] + ' .checkbox-comment');
                                    let inputsApproveCommentForm = approveCommentForm.find('input');
                                    request = $.ajax({
                                        url: '<?php echo URL . URL_ADMIN_COMMENT_APPROVE; ?>',
                                        type: 'POST',
                                        data: approveCommentForm.serialize()
                                    });
                                    inputsApproveCommentForm.prop('disabled', true);
                                    request.done(function(response_comment_approve) {
                                        requestUsable = true;
                                        response_comment_approve = jQuery.parseJSON(response_comment_approve);
                                        if (response_comment_approve.hasOwnProperty('reset') && response_comment_approve['reset'] == false) {
                                            if (response_comment_approve.hasOwnProperty('notification')) {
                                                setClientNotification(response_comment_approve['notification']);
                                            }
                                            if (response_comment_approve.hasOwnProperty('form_token')) {
                                                $('.input-token').val(response_comment_approve['form_token']);
                                            }
                                            if (response_comment_approve.hasOwnProperty('is_approved')) {
                                                if (response_comment_approve['is_approved'] == 1) {
                                                    spanTextApproveCommentTextAsd.text('Açık');
                                                    checkboxApproveComment.prop('checked', true);
                                                } else {
                                                    spanTextApproveCommentTextAsd.text('Kapalı');
                                                    checkboxApproveComment.prop('checked', false);
                                                }
                                            }
                                        } else {
                                            window.location.href = resetLocation;
                                        }
                                    });
                                    request.always(function() {
                                        inputsApproveCommentForm.prop('disabled', false);
                                    });
                                }
                            });
                            if ('<?php echo $web_data['authenticated_user'] ?>' != response['user_id']) {
                                $('#comment-wrapper-' + response['id'] + ' .btn-comment-delete-popup').click(function(e) {
                                    e.preventDefault();
                                    if (commentDeleteWrapperjQ.hasClass('disable')) {
                                        $('#input-comment-id-comment-delete').val(response['id']);
                                        commentDeleteWrapperjQ.removeClass('disable');
                                    }
                                    addNoScrolljQ();
                                });
                            }
                        <?php endif; ?>
                        <?php if (!empty($web_data['user_has_comment'])) : ?>
                            if ('<?php echo $web_data['authenticated_user'] ?>' == response['user_id']) {
                                let btnCommentUpdatePopupjQ = $('#comment-wrapper-' + response['id'] + ' .btn-comment-update-popup');
                                btnCommentUpdatePopupjQ.click(function(e) {
                                    e.preventDefault();
                                    if (commentUpdateWrapperjQ.hasClass('disable')) {
                                        $('#input-comment-id-comment-update').val(response['id']);
                                        textareaCommentUpdatejQ.val(btnCommentUpdatePopupjQ.data('value'));
                                        commentUpdateWrapperjQ.removeClass('disable');
                                        textareaCommentUpdatejQ.focus();
                                    }
                                    addNoScrolljQ();
                                });
                                $('#comment-wrapper-' + response['id'] + ' .btn-comment-delete-popup').click(function(e) {
                                    e.preventDefault();
                                    if (commentDeleteWrapperjQ.hasClass('disable')) {
                                        $('#input-comment-id-comment-delete').val(response['id']);
                                        commentDeleteWrapperjQ.removeClass('disable');
                                    }
                                    addNoScrolljQ();
                                });
                            }
                        <?php endif; ?>
                    <?php endif; ?>
                    if (response.hasOwnProperty('comments_reply')) {
                        let expandCommentRepliesjQ = $('#comment-wrapper-' + response['id'] + ' .expand-container');
                        let commentRepliesWrapperPartjQ = $('#comment-wrapper-' + response['id'] + ' .comment-replies-wrapper');
                        expandCommentRepliesjQ.click(function(e) {
                            e.preventDefault();
                            if (commentRepliesWrapperPartjQ.hasClass('disable')) {
                                commentRepliesWrapperPartjQ.removeClass('disable');
                                expandCommentRepliesjQ.text('Yoruma yapılan cevapları gizle');
                            } else {
                                commentRepliesWrapperPartjQ.addClass('disable');
                                expandCommentRepliesjQ.text('Yoruma yapılan cevapları göster');
                            }
                        });
                        <?php if (!empty($web_data['authenticated_user'])) : ?>
                            $.each(response['comments_reply'], function(key, comments_reply) {
                                <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                                    $('#comment-reply-wrapper-' + comments_reply['id'] + ' .btn-comment-reply-approve').click(function(e) {
                                        e.preventDefault();
                                        if (requestUsable) {
                                            requestUsable = false;
                                            let approveCommentReplyForm = $('#comment-reply-wrapper-' + comments_reply['id'] + ' .form-approve-comment-reply');
                                            let spanTextApproveCommentReplyTextAsd = $('#comment-reply-wrapper-' + comments_reply['id'] + ' .checkmark-comment-text-asd');
                                            let checkboxApproveCommentReply = $('#comment-reply-wrapper-' + comments_reply['id'] + ' .checkbox-comment');
                                            let inputsApproveCommentReplyForm = approveCommentReplyForm.find('input');
                                            request = $.ajax({
                                                url: '<?php echo URL . URL_ADMIN_COMMENT_REPLY_APPROVE; ?>',
                                                type: 'POST',
                                                data: approveCommentReplyForm.serialize()
                                            });
                                            inputsApproveCommentReplyForm.prop('disabled', true);
                                            request.done(function(response_comment_reply_approve) {
                                                requestUsable = true;
                                                response_comment_reply_approve = jQuery.parseJSON(response_comment_reply_approve);
                                                if (response_comment_reply_approve.hasOwnProperty('reset') && response_comment_reply_approve['reset'] == false) {
                                                    if (response_comment_reply_approve.hasOwnProperty('notification')) {
                                                        setClientNotification(response_comment_reply_approve['notification']);
                                                    }
                                                    if (response_comment_reply_approve.hasOwnProperty('form_token')) {
                                                        $('.input-token').val(response_comment_reply_approve['form_token']);
                                                    }
                                                    if (response_comment_reply_approve.hasOwnProperty('is_comment_reply_approved')) {
                                                        if (response_comment_reply_approve['is_comment_reply_approved'] == 1) {
                                                            spanTextApproveCommentReplyTextAsd.text('Açık');
                                                            checkboxApproveCommentReply.prop('checked', true);
                                                        } else {
                                                            spanTextApproveCommentReplyTextAsd.text('Kapalı');
                                                            checkboxApproveCommentReply.prop('checked', false);
                                                        }
                                                    }
                                                } else {
                                                    window.location.href = resetLocation;
                                                }
                                            });
                                            request.always(function() {
                                                inputsApproveCommentReplyForm.prop('disabled', false);
                                            });
                                        }
                                    });
                                    if ('<?php echo $web_data['authenticated_user'] ?>' != comments_reply['user_id']) {
                                        $('#comment-reply-wrapper-' + comments_reply['id'] + ' .btn-comment-reply-delete-popup').click(function(e) {
                                            e.preventDefault();
                                            if (commentReplyDeleteWrapperjQ.hasClass('disable')) {
                                                $('#input-comment-id-comment-reply-delete').val(response['id']);
                                                $('#input-comment-reply-id-comment-reply-delete').val(comments_reply['id']);
                                                commentReplyDeleteWrapperjQ.removeClass('disable');
                                            }
                                            addNoScrolljQ();
                                        });
                                    }
                                <?php endif; ?>
                                <?php if (!empty($web_data['user_has_comment_reply'])) : ?>
                                    if ('<?php echo $web_data['authenticated_user'] ?>' == comments_reply['user_id']) {
                                        let btnCommentReplyUpdatePopupjQ = $('#comment-reply-wrapper-' + comments_reply['id'] + ' .btn-comment-reply-update-popup');
                                        btnCommentReplyUpdatePopupjQ.click(function(e) {
                                            e.preventDefault();
                                            if (commentReplyUpdateWrapperjQ.hasClass('disable')) {
                                                $('#input-comment-id-comment-reply-update').val(comments_reply['comment_id']);
                                                $('#input-comment-reply-id-comment-reply-update').val(comments_reply['id']);
                                                textareaCommentReplyUpdatejQ.val(btnCommentReplyUpdatePopupjQ.data('value'));
                                                commentReplyUpdateWrapperjQ.removeClass('disable');
                                                textareaCommentReplyUpdatejQ.focus();
                                            }
                                            addNoScrolljQ();
                                        });
                                        $('#comment-reply-wrapper-' + comments_reply['id'] + ' .btn-comment-reply-delete-popup').click(function(e) {
                                            e.preventDefault();
                                            if (commentReplyDeleteWrapperjQ.hasClass('disable')) {
                                                $('#input-comment-id-comment-reply-delete').val(response['id']);
                                                $('#input-comment-reply-id-comment-reply-delete').val(comments_reply['id']);
                                                commentReplyDeleteWrapperjQ.removeClass('disable');
                                            }
                                            addNoScrolljQ();
                                        });
                                    }
                                <?php endif; ?>
                            });
                        <?php endif; ?>
                    }
                });
            }
            <?php if (empty($web_data['comment_not_found'])) : ?>
                setComments(<?php echo $web_data['comments']; ?>);
            <?php endif; ?>
            var offSetComment = <?php echo COMMENT_LOAD_LIMIT_IN_ONCE; ?>;
            var commentsLoading = $('#comments-loading');
            $(window).scroll(function() {
                if ($(window).scrollTop() > 0) {
                    notificationClient.addClass('sticky');
                } else {
                    notificationClient.removeClass('sticky');
                }
                <?php if (empty($web_data['comment_not_found'])) : ?>
                    if (!$('.details-comments').hasClass('disable')) {
                        if ($(window).scrollTop() + $(window).height() > $('#comments-loading').offset().top - 300 && requestUsable && requestScroll) {
                            requestUsable = false;
                            requestScroll = false;
                            if (commentsLoading.hasClass('disable')) {
                                commentsLoading.removeClass('disable')
                            }
                            request = $.ajax({
                                url: '<?php echo URL . URL_ITEM_DETAILS . '/' . $web_data['item']['item_url']; ?>',
                                type: 'POST',
                                data: {
                                    loadedCommentOffset: offSetComment
                                }
                            });
                            request.done(function(responses) {
                                requestUsable = true;
                                responses = jQuery.parseJSON(responses);
                                if (!responses.hasOwnProperty('stop')) {
                                    setComments(responses);
                                    offSetComment += <?php echo COMMENT_LOAD_LIMIT_IN_ONCE; ?>;
                                    requestScroll = true;
                                }
                            });
                            request.always(function() {
                                if (!commentsLoading.hasClass('disable')) {
                                    commentsLoading.addClass('disable')
                                }
                            });
                        }
                    }
                <?php endif; ?>
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
            $('#submit-add-to-cart').click(function(e) {
                e.preventDefault();
                if (requestUsable) {
                    requestUsable = false;
                    const formAddToCart = $('#form-add-to-cart');
                    const inputsformAddToCart = formAddToCart.find('input, select');
                    request = $.ajax({
                        url: '<?php echo URL . URL_ADD_CART; ?>',
                        type: 'POST',
                        data: formAddToCart.serialize()
                    });
                    inputsformAddToCart.prop('disabled', true);
                    request.done(function(response) {
                        requestUsable = true;
                        window.location.href = resetLocation;
                    });
                    request.always(function() {
                        inputsformAddToCart.prop('disabled', false);
                    });
                }
            });
            <?php if (!empty($web_data['authenticated_user'])) : ?>

                function AddToFavorites() {
                    $('#submit-add-to-favorites').click(function(e) {
                        e.preventDefault();
                        if (requestUsable) {
                            requestUsable = false;
                            const formAddToFavorites = $('#form-add-to-favorites');
                            const inputsformAddToFavorites = formAddToFavorites.find('input, button');
                            request = $.ajax({
                                url: '<?php echo URL . URL_ADD_FAVORITES; ?>',
                                type: 'POST',
                                data: formAddToFavorites.serialize()
                            });
                            inputsformAddToFavorites.prop('disabled', true);
                            request.done(function(response) {
                                requestUsable = true;
                                response = jQuery.parseJSON(response);
                                if (response.hasOwnProperty('shutdown')) {
                                    window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                                } else if (response.hasOwnProperty('exception')) {
                                    window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                                } else if (response.hasOwnProperty('reset') && response['reset'] == false) {
                                    if (response.hasOwnProperty('notification')) {
                                        setClientNotification(response['notification']);
                                    }
                                    if (response.hasOwnProperty('form_token')) {
                                        $('.input-token').val(response['form_token']);
                                    }
                                    formAddToFavorites.remove();
                                    let y1 = $("<form></form>").attr('id', 'form-remove-from-favorites');
                                    let y2 = $("<input></input>").attr('type', 'hidden').attr('name', 'item').attr('value', '<?php echo $web_data['item']['item_cart_id']; ?>');
                                    y1.append(y2);
                                    let y3 = $("<button></button>").attr('id', 'submit-remove-from-favorites').addClass('btn-add-to-favorites').attr('type', 'submit').attr('title', 'Ürünü favorilerimden kaldır');
                                    y1.append(y3);
                                    let y4 = $("<i></i>").addClass('far fa-heart details-favorites-icon selected');
                                    y3.append(y4);
                                    $('.favorite-wrapper').append(y1);
                                    RemoveFromFavorites();
                                } else {
                                    window.location.href = resetLocation;
                                }
                            });
                            request.always(function() {
                                inputsformAddToFavorites.prop('disabled', false);
                            });
                        }
                    });
                }

                function RemoveFromFavorites() {
                    $('#submit-remove-from-favorites').click(function(e) {
                        e.preventDefault();
                        if (requestUsable) {
                            requestUsable = false;
                            const formRemoveFromFavorites = $('#form-remove-from-favorites');
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
                                if (response.hasOwnProperty('shutdown')) {
                                    window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                                } else if (response.hasOwnProperty('exception')) {
                                    window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                                } else if (response.hasOwnProperty('reset') && response['reset'] == false) {
                                    if (response.hasOwnProperty('notification')) {
                                        setClientNotification(response['notification']);
                                    }
                                    if (response.hasOwnProperty('form_token')) {
                                        $('.input-token').val(response['form_token']);
                                    }
                                    formRemoveFromFavorites.remove();
                                    let y5 = $("<form></form>").attr('id', 'form-add-to-favorites');
                                    let y6 = $("<input></input>").attr('type', 'hidden').attr('name', 'item').attr('value', '<?php echo $web_data['item']['item_cart_id']; ?>');
                                    y5.append(y6);
                                    let y7 = $("<button></button>").attr('id', 'submit-add-to-favorites').addClass('btn-add-to-favorites').attr('type', 'submit').attr('title', 'Ürünü favorilerime ekle');
                                    y5.append(y7);
                                    let y8 = $("<i></i>").addClass('far fa-heart details-favorites-icon');
                                    y7.append(y8);
                                    $('.favorite-wrapper').append(y5);
                                    AddToFavorites();
                                } else {
                                    window.location.href = resetLocation;
                                }
                            });
                            request.always(function() {
                                inputsformRemoveFromFavorites.prop('disabled', false);
                            });
                        }
                    });
                }
                <?php if (!empty($web_data['user_favorite_item'])) : ?>
                    RemoveFromFavorites();
                <?php else : ?>
                    AddToFavorites();
                <?php endif; ?>
                $('#btn-comment-create').click(function(e) {
                    e.preventDefault();
                    const textareaCommentCreatejQ = $('#textarea-comment-create');
                    if (!$.trim(textareaCommentCreatejQ.val())) {
                        setClientNotification('<div class="notification danger"><span class="text">Yorum alanı boş olamaz</span></div>');
                    } else if (textareaCommentCreatejQ.val().length > <?php echo COMMENT_MAX_LIMIT; ?>) {
                        setClientNotification('<div class="notification danger"><span class="text">Yorum <?php echo COMMENT_MAX_LIMIT; ?> karakterden fazla olamaz</span></div>');
                    } else if (requestUsable) {
                        requestUsable = false;
                        const formCommentCreate = $('#form-comment-create');
                        const inputsformCommentCreate = formCommentCreate.find('input, textarea, button');
                        request = $.ajax({
                            url: '<?php echo URL . URL_COMMENT_CREATE; ?>',
                            type: 'POST',
                            data: formCommentCreate.serialize()
                        });
                        inputsformCommentCreate.prop('disabled', true);
                        request.done(function(response) {
                            requestUsable = true;
                            response = jQuery.parseJSON(response);
                            if (response.hasOwnProperty('shutdown')) {
                                window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                            } else if (response.hasOwnProperty('exception')) {
                                window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                            } else if (response.hasOwnProperty('profil')) {
                                window.location.href = '<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_INFORMATIONS; ?>';
                            } else if (response.hasOwnProperty('reset') && response['reset'] == false) {
                                if (response.hasOwnProperty('notification')) {
                                    setClientNotification(response['notification']);
                                }
                                if (response.hasOwnProperty('form_token')) {
                                    $('.input-token').val(response['form_token']);
                                }
                                if (response.hasOwnProperty('comment') && response.hasOwnProperty('comment_user')) {
                                    let xx1 = $("<div></div>").attr('id', 'comment-wrapper-' + response['comment']['id']);
                                    let xx2 = $("<div></div>").addClass('comment-wrapper');
                                    xx1.append(xx2);
                                    let xx3 = $("<div></div>").addClass('comment-replies-wrapper disable');
                                    xx1.append(xx3);
                                    let xx4 = $("<div></div>").addClass('user-wrapper');
                                    xx2.append(xx4);
                                    let xx5 = $("<img>").addClass('image').attr('title', 'Yorumun Yazarının Profil Fotoğrafı').attr('src', '<?php echo URL . 'assets/images/users/'; ?>' + response['comment_user']['user_profile_image_path'] + '/' + response['comment_user']['user_profile_image']).attr('alt', response['comment_user']['user_first_name'] + ' ' + response['comment_user']['user_last_name']);
                                    xx4.append(xx5);
                                    let xx6 = $("<span></span>").addClass('name').attr('title', 'Yorumun Yazarı').append(response['comment_user']['user_first_name'] + ' ' + response['comment_user']['user_last_name']);
                                    xx4.append(xx6);
                                    let xx7 = $("<div></div>").addClass('user-bot-container');
                                    xx4.append(xx7);
                                    let xx8 = $("<span></span>").addClass('date').attr('title', 'Yorum Oluşturulma Tarihi').append(response['comment']['date_comment_created']);
                                    xx7.append(xx8);
                                    let xx9 = $("<button></button>").addClass('btn-success mr-left btn-popup-new-comment-reply').attr('title', 'Yoruma Cevap Yaz').text('Cevapla');
                                    xx7.append(xx9);
                                    <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                                        let xx10 = $("<form></form>").addClass('form-approve-comment');
                                        let xx11 = $("<input></input>").addClass('input-token').attr('type', 'hidden').attr('name', 'form_token').attr('value', response['form_token']);
                                        xx10.append(xx11);
                                        let xx12 = $("<input></input>").attr('type', 'hidden').attr('name', 'comment_id').attr('value', response['comment']['id']);
                                        xx10.append(xx12);
                                        let xx13 = $("<label></label>").attr('for', 'comment-approved-' + response['comment']['id']).addClass('btn-warning mr-left label-flex btn-comment-approve').attr('title', 'Yorumun Herkese Görünürlüğünü Değiştir');
                                        xx10.append(xx13);
                                        let xx15 = $("<input></input>").attr('type', 'checkbox').attr('id', 'comment-approved-' + response['comment']['id']).addClass('checkbox-comment').attr('name', 'is_comment_approved');
                                        xx13.append(xx15);
                                        let xyx14 = $("<span></span>").addClass('checkmark-comment-text-asd').text('Kapalı');
                                        let xx16 = $("<span></span>").addClass('checkmark-comment');
                                        xx13.append(xx16);
                                        xx13.append(xyx14);
                                        xx7.append(xx10);
                                    <?php endif; ?>
                                    let xx17 = $("<button></button>").addClass('btn-warning mr-left btn-comment-update-popup').attr('data-value', response['comment']['comment']).attr('title', 'Yorumu Güncelle').text('Güncelle');
                                    xx7.append(xx17);
                                    let xx18 = $("<button></button>").addClass('btn-danger mr-left btn-comment-delete-popup').attr('title', 'Yorumu Sil').text('Sil');
                                    xx7.append(xx18);
                                    let xx20 = $("<p></p>").addClass('comment-text').append(response['comment']['comment']);
                                    xx2.append(xx20);
                                    $('#insert-new-comment').prepend(xx1);
                                    $('#comment-wrapper-' + response['comment']['id'] + ' .btn-popup-new-comment-reply').click(function(e) {
                                        e.preventDefault();
                                        if (commentReplyCreateWrapperjQ.hasClass('disable')) {
                                            $('#input-comment-id-comment-reply-create').val(response['comment']['id']);
                                            textareaCommentReplyCreatejQ.attr('placeholder', response['comment_user']['user_first_name'] + ' ' + response['comment_user']['user_last_name'] + ' isimli kullanıcının yorumuna burdan cevap yazabilirsiniz...');
                                            commentReplyCreateWrapperjQ.removeClass('disable');
                                            textareaCommentReplyCreatejQ.focus();
                                        }
                                        addNoScrolljQ();
                                    });
                                    <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                                        $('#comment-wrapper-' + response['comment']['id'] + ' .btn-comment-approve').click(function(e) {
                                            e.preventDefault();
                                            if (requestUsable) {
                                                requestUsable = false;
                                                let approveCommentForm = $('#comment-wrapper-' + response['comment']['id'] + ' .form-approve-comment');
                                                let spanTextApproveCommentTextAsd = $('#comment-wrapper-' + response['comment']['id'] + ' .checkmark-comment-text-asd');
                                                let checkboxApproveComment = $('#comment-wrapper-' + response['comment']['id'] + ' .checkbox-comment');
                                                let inputsApproveCommentForm = approveCommentForm.find('input');
                                                request = $.ajax({
                                                    url: '<?php echo URL . URL_ADMIN_COMMENT_APPROVE; ?>',
                                                    type: 'POST',
                                                    data: approveCommentForm.serialize()
                                                });
                                                inputsApproveCommentForm.prop('disabled', true);
                                                request.done(function(response_comment_approve) {
                                                    requestUsable = true;
                                                    response_comment_approve = jQuery.parseJSON(response_comment_approve);
                                                    if (response_comment_approve.hasOwnProperty('reset') && response_comment_approve['reset'] == false) {
                                                        if (response_comment_approve.hasOwnProperty('notification')) {
                                                            setClientNotification(response_comment_approve['notification']);
                                                        }
                                                        if (response_comment_approve.hasOwnProperty('form_token')) {
                                                            $('.input-token').val(response_comment_approve['form_token']);
                                                        }
                                                        if (response_comment_approve.hasOwnProperty('is_approved')) {
                                                            if (response_comment_approve['is_approved'] == 1) {
                                                                spanTextApproveCommentTextAsd.text('Açık');
                                                                checkboxApproveComment.prop('checked', true);
                                                            } else {
                                                                spanTextApproveCommentTextAsd.text('Kapalı');
                                                                checkboxApproveComment.prop('checked', false);
                                                            }
                                                        }
                                                    } else {
                                                        window.location.href = resetLocation;
                                                    }
                                                });
                                                request.always(function() {
                                                    inputsApproveCommentForm.prop('disabled', false);
                                                });
                                            }
                                        });
                                    <?php endif; ?>
                                    let btnCommentUpdatePopupjQ = $('#comment-wrapper-' + response['comment']['id'] + ' .btn-comment-update-popup');
                                    btnCommentUpdatePopupjQ.click(function(e) {
                                        e.preventDefault();
                                        if (commentUpdateWrapperjQ.hasClass('disable')) {
                                            $('#input-comment-id-comment-update').val(response['comment']['id']);
                                            textareaCommentUpdatejQ.val(btnCommentUpdatePopupjQ.data('value'));
                                            commentUpdateWrapperjQ.removeClass('disable');
                                            textareaCommentUpdatejQ.focus();
                                        }
                                        addNoScrolljQ();
                                    });
                                    $('#comment-wrapper-' + response['comment']['id'] + ' .btn-comment-delete-popup').click(function(e) {
                                        e.preventDefault();
                                        if (commentDeleteWrapperjQ.hasClass('disable')) {
                                            $('#input-comment-id-comment-delete').val(response['comment']['id']);
                                            commentDeleteWrapperjQ.removeClass('disable');
                                        }
                                        addNoScrolljQ();
                                    });
                                    totalCommentNumber += 1;
                                    $('#title-comment-count').text('Ürüne Yapılan Yorumlar (' + (totalCommentNumber) + ')');
                                    $('#btn-comment-count').text('Yorumlar (' + (totalCommentNumber) + ')');
                                    const commentCreateWrapperjQ = $('.comment-create-wrapper');
                                    if (!commentCreateWrapperjQ.hasClass('disable')) {
                                        commentCreateWrapperjQ.addClass('disable');
                                        textareaCommentCreatejQ.val('');
                                    }
                                    removeNoScrolljQ();
                                    if ($('.comment-not-found-container').length == 1) {
                                        $('.comment-not-found-container').remove();
                                    }
                                }
                            } else {
                                window.location.href = resetLocation;
                            }
                        });
                        request.always(function() {
                            inputsformCommentCreate.prop('disabled', false);
                        });
                    }
                });
                $('#btn-comment-reply-create').click(function(e) {
                    e.preventDefault();
                    if (!$.trim(textareaCommentReplyCreatejQ.val())) {
                        setClientNotification('<div class="notification danger"><span class="text">Yorum alanı boş olamaz</span></div>');
                    } else if (textareaCommentReplyCreatejQ.val().length > <?php echo COMMENT_MAX_LIMIT; ?>) {
                        setClientNotification('<div class="notification danger"><span class="text">Yorum <?php echo COMMENT_MAX_LIMIT; ?> karakterden fazla olamaz</span></div>');
                    } else if (requestUsable) {
                        requestUsable = false;
                        const formCommentReplyCreate = $('#form-comment-reply-create');
                        const inputsformCommentReplyCreate = formCommentReplyCreate.find('input, textarea, button');
                        request = $.ajax({
                            url: '<?php echo URL . URL_COMMENT_REPLY_CREATE; ?>',
                            type: 'POST',
                            data: formCommentReplyCreate.serialize()
                        });
                        inputsformCommentReplyCreate.prop('disabled', true);
                        request.done(function(response) {
                            requestUsable = true;
                            response = jQuery.parseJSON(response);
                            if (response.hasOwnProperty('shutdown')) {
                                window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                            } else if (response.hasOwnProperty('exception')) {
                                window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                            } else if (response.hasOwnProperty('profil')) {
                                window.location.href = '<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_INFORMATIONS; ?>';
                            } else if (response.hasOwnProperty('reset') && response['reset'] == false) {
                                if (response.hasOwnProperty('notification')) {
                                    setClientNotification(response['notification']);
                                }
                                if (response.hasOwnProperty('form_token')) {
                                    $('.input-token').val(response['form_token']);
                                }
                                if (response.hasOwnProperty('comment_reply') && response.hasOwnProperty('comment_reply_user')) {
                                    if (!$('#comment-wrapper-' + response['comment_reply']['comment_id'] + ' .expand-container')) {
                                        let xx21 = $("<div></div>").addClass('row-expand');
                                        let xx22 = $("<div></div>").addClass('expand-wrapper');
                                        xx21.append(xx22);
                                        let xx23 = $("<span></span>").addClass('expand-container').text('Yoruma yapılan cevapları göster');
                                        xx22.append(xx23);
                                        $('#comment-wrapper-' + response['comment_reply']['comment_id'] + ' .comment-expand-insert').append(xx21)
                                    }
                                    let xx24 = $("<div></div>").attr('id', 'comment-reply-wrapper-' + response['comment_reply']['id']).addClass('comment-wrapper comment-reply-wrapper');
                                    let xx25 = $("<div></div>").addClass('user-wrapper');
                                    xx24.append(xx25);
                                    let xx26 = $("<img>").addClass('image').attr('title', 'Yorumun Yazarının Profil Fotoğrafı').attr('src', '<?php echo URL . 'assets/images/users/'; ?>' + response['comment_reply_user']['user_profile_image_path'] + '/' + response['comment_reply_user']['user_profile_image']).attr('alt', response['comment_reply_user']['user_first_name'] + ' ' + response['comment_reply_user']['user_last_name']);
                                    xx25.append(xx26);
                                    let xx27 = $("<span></span>").addClass('name').attr('title', 'Yorumun Yazarı').append(response['comment_reply_user']['user_first_name'] + ' ' + response['comment_reply_user']['user_last_name']);
                                    xx25.append(xx27);
                                    let xx28 = $("<div></div>").addClass('user-bot-container');
                                    xx25.append(xx28);
                                    let xx29 = $("<span></span>").addClass('date').attr('title', 'Yorum Oluşturulma Tarihi').append(response['comment_reply']['date_comment_reply_created']);
                                    xx28.append(xx29);
                                    <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                                        let xx30 = $("<form></form>").addClass('form-approve-comment-reply');
                                        let xx31 = $("<input></input>").addClass('input-token').attr('type', 'hidden').attr('name', 'form_token').attr('value', response['form_token']);
                                        xx30.append(xx31);
                                        let xx32 = $("<input></input>").attr('type', 'hidden').attr('name', 'comment_reply_id').attr('value', response['comment_reply']['id']);
                                        xx30.append(xx32);
                                        let xx33 = $("<label></label>").attr('for', 'comment-reply-approved-' + response['comment_reply']['id']).addClass('btn-warning mr-left label-flex btn-comment-reply-approve').attr('title', 'Yorumun Herkese Görünürlüğünü Değiştir');
                                        xx30.append(xx33);
                                        let xx35 = $("<input></input>").attr('type', 'checkbox').attr('id', 'comment-reply-approved-' + response['comment_reply']['id']).addClass('checkbox-comment').attr('name', 'is_comment_reply_approved');
                                        xx33.append(xx35);
                                        let xyx14 = $("<span></span>").addClass('checkmark-comment-text-asd').text('Kapalı');
                                        let xx36 = $("<span></span>").addClass('checkmark-comment');
                                        xx33.append(xx36);
                                        xx33.append(xyx14);
                                        xx28.append(xx30);
                                    <?php endif; ?>
                                    let xx38 = $("<button></button>").addClass('btn-warning mr-left btn-comment-reply-update-popup').attr('data-value', response['comment_reply']['comment_reply']).attr('title', 'Yorumu Güncelle').text('Güncelle');
                                    xx28.append(xx38);
                                    let xx39 = $("<button></button>").addClass('btn-danger mr-left btn-comment-reply-delete-popup').attr('title', 'Yorumu Sil').text('Sil');
                                    xx28.append(xx39);
                                    let xx40 = $("<div></div>").addClass('reply-reference-container');
                                    xx24.append(xx40);
                                    let xx41 = $("<span></span>").addClass('reply-reference').append('@' + response['comment_reply_user']['user_first_name'] + ' ' + response['comment_reply_user']['user_last_name'] + ' isimli kullanıcının yorumunu cevapladı');
                                    xx40.append(xx41);
                                    let xx42 = $("<p></p>").addClass('comment-reply-text').append(response['comment_reply']['comment_reply']);
                                    xx24.append(xx42);
                                    $('#comment-wrapper-' + response['comment_reply']['comment_id'] + ' .comment-replies-wrapper').append(xx24);
                                    if (!$('#comment-wrapper-' + response['comment_reply']['comment_id'] + ' .expand-container')) {
                                        let expandCommentRepliesjQ = $('#comment-wrapper-' + response['comment_reply']['comment_id'] + ' .expand-container');
                                        let commentRepliesWrapperPartjQ = $('#comment-wrapper-' + response['comment_reply']['comment_id'] + ' .comment-replies-wrapper');
                                        expandCommentRepliesjQ.click(function(e) {
                                            e.preventDefault();
                                            if (commentRepliesWrapperPartjQ.hasClass('disable')) {
                                                commentRepliesWrapperPartjQ.removeClass('disable');
                                                expandCommentRepliesjQ.text('Yoruma yapılan cevapları gizle');
                                            } else {
                                                commentRepliesWrapperPartjQ.addClass('disable');
                                                expandCommentRepliesjQ.text('Yoruma yapılan cevapları göster');
                                            }
                                        });
                                    }
                                    <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                                        $('#comment-reply-wrapper-' + response['comment_reply']['id'] + ' .btn-comment-reply-approve').click(function(e) {
                                            e.preventDefault();
                                            if (requestUsable) {
                                                requestUsable = false;
                                                let approveCommentReplyForm = $('#comment-reply-wrapper-' + response['comment_reply']['id'] + ' .form-approve-comment-reply');
                                                let spanTextApproveCommentReplyTextAsd = $('#comment-reply-wrapper-' + response['comment_reply']['id'] + ' .checkmark-comment-text-asd');
                                                let checkboxApproveCommentReply = $('#comment-reply-wrapper-' + response['comment_reply']['id'] + ' .checkbox-comment');
                                                let inputsApproveCommentReplyForm = approveCommentReplyForm.find('input');
                                                request = $.ajax({
                                                    url: '<?php echo URL . URL_ADMIN_COMMENT_REPLY_APPROVE; ?>',
                                                    type: 'POST',
                                                    data: approveCommentReplyForm.serialize()
                                                });
                                                inputsApproveCommentReplyForm.prop('disabled', true);
                                                request.done(function(response_comment_reply_approve) {
                                                    requestUsable = true;
                                                    response_comment_reply_approve = jQuery.parseJSON(response_comment_reply_approve);
                                                    if (response_comment_reply_approve.hasOwnProperty('reset') && response_comment_reply_approve['reset'] == false) {
                                                        if (response_comment_reply_approve.hasOwnProperty('notification')) {
                                                            setClientNotification(response_comment_reply_approve['notification']);
                                                        }
                                                        if (response_comment_reply_approve.hasOwnProperty('form_token')) {
                                                            $('.input-token').val(response_comment_reply_approve['form_token']);
                                                        }
                                                        if (response_comment_reply_approve.hasOwnProperty('is_comment_reply_approved')) {
                                                            if (response_comment_reply_approve['is_comment_reply_approved'] == 1) {
                                                                spanTextApproveCommentReplyTextAsd.text('Açık');
                                                                checkboxApproveCommentReply.prop('checked', true);
                                                            } else {
                                                                spanTextApproveCommentReplyTextAsd.text('Kapalı');
                                                                checkboxApproveCommentReply.prop('checked', false);
                                                            }
                                                        }
                                                    } else {
                                                        window.location.href = resetLocation;
                                                    }
                                                });
                                                request.always(function() {
                                                    inputsApproveCommentReplyForm.prop('disabled', false);
                                                });
                                            }
                                        });
                                    <?php endif; ?>
                                    let btnCommentReplyUpdatePopupjQ = $('#comment-reply-wrapper-' + response['comment_reply']['id'] + ' .btn-comment-reply-update-popup');
                                    btnCommentReplyUpdatePopupjQ.click(function(e) {
                                        e.preventDefault();
                                        if (commentReplyUpdateWrapperjQ.hasClass('disable')) {
                                            $('#input-comment-id-comment-reply-update').val(response['comment_reply']['comment_id']);
                                            $('#input-comment-reply-id-comment-reply-update').val(response['comment_reply']['id']);
                                            textareaCommentReplyUpdatejQ.val(btnCommentReplyUpdatePopupjQ.data('value'));
                                            commentReplyUpdateWrapperjQ.removeClass('disable');
                                            textareaCommentReplyUpdatejQ.focus();
                                        }
                                        addNoScrolljQ();
                                    });
                                    $('#comment-reply-wrapper-' + response['comment_reply']['id'] + ' .btn-comment-reply-delete-popup').click(function(e) {
                                        e.preventDefault();
                                        if (commentReplyDeleteWrapperjQ.hasClass('disable')) {
                                            $('#input-comment-id-comment-reply-delete').val(response['comment_reply']['comment_id']);
                                            $('#input-comment-reply-id-comment-reply-delete').val(response['comment_reply']['id']);
                                            commentReplyDeleteWrapperjQ.removeClass('disable');
                                        }
                                        addNoScrolljQ();
                                    });
                                    let commentRepliesWrapperPart = $('#comment-wrapper-' + response['comment_reply']['comment_id'] + ' .comment-replies-wrapper');
                                    if (commentRepliesWrapperPart.hasClass('disable')) {
                                        commentRepliesWrapperPart.removeClass('disable');
                                    }
                                    if (!commentReplyCreateWrapperjQ.hasClass('disable')) {
                                        commentReplyCreateWrapperjQ.addClass('disable');
                                        textareaCommentReplyCreatejQ.val('');
                                    }
                                    removeNoScrolljQ();
                                    $('#comment-reply-wrapper-' + response['comment_reply']['id'])[0].scrollIntoView(false);
                                }
                            } else {
                                window.location.href = resetLocation;
                            }
                        });
                        request.always(function() {
                            inputsformCommentReplyCreate.prop('disabled', false);
                        });
                    }
                });
                $('#btn-comment-update').click(function(e) {
                    e.preventDefault();
                    if (!$.trim(textareaCommentUpdatejQ.val())) {
                        setClientNotification('<div class="notification danger"><span class="text">Yorum alanı boş olamaz</span></div>');
                    } else if (textareaCommentUpdatejQ.val().length > <?php echo COMMENT_MAX_LIMIT; ?>) {
                        setClientNotification('<div class="notification danger"><span class="text">Yorum <?php echo COMMENT_MAX_LIMIT; ?> karakterden fazla olamaz</span></div>');
                    } else if (requestUsable) {
                        requestUsable = false;
                        const formCommentUpdate = $('#form-comment-update');
                        const inputsformCommentUpdate = formCommentUpdate.find('input, textarea, button');
                        request = $.ajax({
                            url: '<?php echo URL . URL_COMMENT_UPDATE; ?>',
                            type: 'POST',
                            data: formCommentUpdate.serialize()
                        });
                        inputsformCommentUpdate.prop('disabled', true);
                        request.done(function(response) {
                            requestUsable = true;
                            response = jQuery.parseJSON(response);
                            if (response.hasOwnProperty('shutdown')) {
                                window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                            } else if (response.hasOwnProperty('exception')) {
                                window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                            } else if (response.hasOwnProperty('reset') && response['reset'] == false) {
                                if (response.hasOwnProperty('notification')) {
                                    setClientNotification(response['notification']);
                                }
                                if (response.hasOwnProperty('form_token')) {
                                    $('.input-token').val(response['form_token']);
                                }
                                if (response.hasOwnProperty('comment')) {
                                    $('#comment-wrapper-' + response['comment']['id'] + ' .comment-text').append(response['comment']['comment']);
                                    $('#comment-wrapper-' + response['comment']['id'] + ' .btn-comment-update-popup').attr('data-value', response['comment']['comment']);
                                    $('#comment-wrapper-' + response['comment']['id'] + ' .btn-comment-update-popup').data('value', response['comment']['comment']);
                                    if (!commentUpdateWrapperjQ.hasClass('disable')) {
                                        commentUpdateWrapperjQ.addClass('disable');
                                        textareaCommentUpdatejQ.val('');
                                    }
                                    removeNoScrolljQ();
                                }
                            } else {
                                window.location.href = resetLocation;
                            }
                        });
                        request.always(function() {
                            inputsformCommentUpdate.prop('disabled', false);
                        });
                    }
                });
                $('#btn-comment-reply-update').click(function(e) {
                    e.preventDefault();
                    if (!$.trim(textareaCommentReplyUpdatejQ.val())) {
                        setClientNotification('<div class="notification danger"><span class="text">Yorum alanı boş olamaz</span></div>');
                    } else if (textareaCommentReplyUpdatejQ.val().length > <?php echo COMMENT_MAX_LIMIT; ?>) {
                        setClientNotification('<div class="notification danger"><span class="text">Yorum <?php echo COMMENT_MAX_LIMIT; ?> karakterden fazla olamaz</span></div>');
                    } else if (requestUsable) {
                        requestUsable = false;
                        const formCommentReplyUpdate = $('#form-comment-reply-update');
                        const inputsformCommentReplyUpdate = formCommentReplyUpdate.find('input, textarea, button');
                        request = $.ajax({
                            url: '<?php echo URL . URL_COMMENT_REPLY_UPDATE; ?>',
                            type: 'POST',
                            data: formCommentReplyUpdate.serialize()
                        });
                        inputsformCommentReplyUpdate.prop('disabled', true);
                        request.done(function(response) {
                            requestUsable = true;
                            response = jQuery.parseJSON(response);
                            if (response.hasOwnProperty('shutdown')) {
                                window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                            } else if (response.hasOwnProperty('exception')) {
                                window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                            } else if (response.hasOwnProperty('reset') && response['reset'] == false) {
                                if (response.hasOwnProperty('notification')) {
                                    setClientNotification(response['notification']);
                                }
                                if (response.hasOwnProperty('form_token')) {
                                    $('.input-token').val(response['form_token']);
                                }
                                if (response.hasOwnProperty('comment_reply')) {
                                    $('#comment-reply-wrapper-' + response['comment_reply']['id'] + ' .comment-reply-text').append(response['comment_reply']['comment_reply']);
                                    $('#comment-reply-wrapper-' + response['comment_reply']['id'] + ' .btn-comment-reply-update-popup').attr('data-value', response['comment_reply']['comment_reply']);
                                    $('#comment-reply-wrapper-' + response['comment_reply']['id'] + ' .btn-comment-reply-update-popup').data('value', response['comment_reply']['comment_reply']);
                                    if (!commentReplyUpdateWrapperjQ.hasClass('disable')) {
                                        commentReplyUpdateWrapperjQ.addClass('disable');
                                        textareaCommentReplyUpdatejQ.val('');
                                    }
                                    removeNoScrolljQ();
                                }
                            } else {
                                window.location.href = resetLocation;
                            }
                        });
                        request.always(function() {
                            inputsformCommentReplyUpdate.prop('disabled', false);
                        });
                    }
                });

                function setCommentDeleteSubmit(param) {
                    $('#btn-comment-delete').click(function(e) {
                        e.preventDefault();
                        if (requestUsable) {
                            requestUsable = false;
                            const formCommentDelete = $('#form-comment-delete');
                            const inputsformCommentDelete = formCommentDelete.find('input, button');
                            request = $.ajax({
                                url: '<?php echo URL; ?>' + param,
                                type: 'POST',
                                data: formCommentDelete.serialize()
                            });
                            inputsformCommentDelete.prop('disabled', true);
                            request.done(function(response) {
                                requestUsable = true;
                                response = jQuery.parseJSON(response);
                                if (response.hasOwnProperty('shutdown')) {
                                    window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                                } else if (response.hasOwnProperty('exception')) {
                                    window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                                } else if (response.hasOwnProperty('reset') && response['reset'] == false) {
                                    if (response.hasOwnProperty('notification')) {
                                        setClientNotification(response['notification']);
                                    }
                                    if (response.hasOwnProperty('form_token')) {
                                        $('.input-token').val(response['form_token']);
                                    }
                                    if (response.hasOwnProperty('comment_id')) {
                                        $('#comment-wrapper-' + response['comment_id']).remove();
                                        totalCommentNumber -= 1;
                                        $('#title-comment-count').text('Ürüne Yapılan Yorumlar (' + (totalCommentNumber) + ')');
                                        $('#btn-comment-count').text('Yorumlar (' + (totalCommentNumber) + ')');
                                        if (!commentDeleteWrapperjQ.hasClass('disable')) {
                                            commentDeleteWrapperjQ.addClass('disable');
                                        }
                                        removeNoScrolljQ();
                                    }
                                } else {
                                    window.location.href = resetLocation;
                                }
                            });
                            request.always(function() {
                                inputsformCommentDelete.prop('disabled', false);
                            });
                        }
                    });
                }
                <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                    setCommentDeleteSubmit('<?php echo URL_ADMIN_COMMENT_DELETE; ?>');
                <?php else : ?>
                    setCommentDeleteSubmit('<?php echo URL_COMMENT_DELETE; ?>');
                <?php endif; ?>

                function setCommentReplyDeleteSubmit(param) {
                    $('#btn-comment-reply-delete').click(function(e) {
                        e.preventDefault();
                        if (requestUsable) {
                            requestUsable = false;
                            const formCommentReplyDelete = $('#form-comment-reply-delete');
                            const inputsformCommentReplyDelete = formCommentReplyDelete.find('input, button');
                            request = $.ajax({
                                url: '<?php echo URL; ?>' + param,
                                type: 'POST',
                                data: formCommentReplyDelete.serialize()
                            });
                            inputsformCommentReplyDelete.prop('disabled', true);
                            request.done(function(response) {
                                requestUsable = true;
                                response = jQuery.parseJSON(response);
                                if (response.hasOwnProperty('shutdown')) {
                                    window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                                } else if (response.hasOwnProperty('exception')) {
                                    window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                                } else if (response.hasOwnProperty('reset') && response['reset'] == false) {
                                    if (response.hasOwnProperty('notification')) {
                                        setClientNotification(response['notification']);
                                    }
                                    if (response.hasOwnProperty('form_token')) {
                                        $('.input-token').val(response['form_token']);
                                    }
                                    if (response.hasOwnProperty('comment')) {
                                        $('#comment-reply-wrapper-' + response['comment']['comment_reply_id']).remove();
                                        if ($('#comment-wrapper-' + response['comment']['comment_id'] + ' .comment-replies-wrapper').children().length == 0) {
                                            $('#comment-wrapper-' + response['comment']['comment_id'] + ' .row-expand').remove();
                                        }
                                        if (!commentReplyDeleteWrapperjQ.hasClass('disable')) {
                                            commentReplyDeleteWrapperjQ.addClass('disable');
                                        }
                                        removeNoScrolljQ();
                                    }
                                } else {
                                    window.location.href = resetLocation;
                                }
                            });
                            request.always(function() {
                                inputsformCommentReplyDelete.prop('disabled', false);
                            });
                        }
                    });
                }
                <?php if ($web_data['authenticated_user'] == ADMIN_ID) : ?>
                    setCommentReplyDeleteSubmit('<?php echo URL_ADMIN_COMMENT_REPLY_DELETE; ?>');
                <?php else : ?>
                    setCommentReplyDeleteSubmit('<?php echo URL_COMMENT_REPLY_DELETE; ?>');
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>