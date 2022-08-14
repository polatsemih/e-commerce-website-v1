<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo $web_data['item']['item_name'] . ' | ' . BRAND; ?></title>
    <meta name="description" content="<?php echo $web_data['item']['item_description']; ?>" />
    <meta name="keywords" content="<?php echo $web_data['item']['item_keywords']; ?>" />
    <?php require 'View/SharedHome/_home_head.php'; ?>
    <?php if (empty($web_data['no_comment_found']) && !empty($web_data['authed_user'])) : ?>
        <script src="<?php echo URL; ?>assets/js/jQuery.js"></script>
    <?php endif; ?>
</head>

<body>
    <div class="notification-instant"></div>
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
        <div class="item-details-container container">
            <section class="section-details-top">
                <div class="col-5-details">
                    <figure id="zoom-figure-id" class="zoom-figure" style="background-image: url(<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/' . $web_data['item']['item_images'][0][1]; ?>);">
                        <img id="zoom-image-id" class="zoom-image" src="<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/' . $web_data['item']['item_images'][0][1]; ?>" alt="<?php echo $web_data['item']['item_name']; ?>">
                    </figure>
                    <div class="mini-images-container">
                        <i class="fas fa-caret-left image-slider-icon image-left-slider"></i>
                        <i class="fas fa-caret-right image-slider-icon image-right-slider"></i>
                        <?php $index = 0; ?>
                        <?php foreach ($web_data['item']['item_images'] as $item_image) : ?>
                            <img class="mini-image<?php echo ($item_image[1] == $web_data['item']['item_images'][0][1]) ? ' selected' : ''; ?>" src="<?php echo URL . 'assets/images/items/' . $web_data['item']['item_images_path'] . '/mini' . $item_image[1]; ?>" alt="<?php echo $web_data['item']['item_name']; ?>" data-id="<?php echo $item_image[1]; ?>" data-index="<?php echo $index; ?>">
                            <?php $index++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-6-5-details">
                    <h1 class="details-item-name"><?php echo $web_data['item']['item_name']; ?></h1>
                    <span class="details-item-old-price"><?php echo $web_data['item']['item_price']; ?> TL</span>
                    <span class="details-item-price"><?php echo $web_data['item']['item_discount_price']; ?> TL</span>
                    <form action="<?php echo URL . URL_ADD_TO_CART; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                        <div class="details-item-quantity-row">
                            <span class="details-item-quantity-text-1">Adet Seçin</span>
                            <div class="details-item-quantity">
                                <select class="details-item-select" name="item_quantity">
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
                                <span class="details-item-quantity-text-2">1</span>
                                <span class="details-select-triangle"><i class="fas fa-angle-down details-select-triangle-icon"></i></span>
                                <div class="details-select">
                                    <span class="details-select-option" data-option="1">1</span>
                                    <span class="details-select-option" data-option="2">2</span>
                                    <span class="details-select-option" data-option="3">3</span>
                                    <span class="details-select-option" data-option="4">4</span>
                                    <span class="details-select-option" data-option="5">5</span>
                                    <span class="details-select-option" data-option="6">6</span>
                                    <span class="details-select-option" data-option="7">7</span>
                                    <span class="details-select-option" data-option="8">8</span>
                                    <span class="details-select-option" data-option="9">9</span>
                                    <span class="details-select-option" data-option="10">10</span>
                                </div>
                            </div>
                        </div>
                        <span class="details-size-title">Beden Seçin</span>
                        <div class="details-sizes">
                            <?php foreach ($web_data['sizes'] as $size) : ?>
                                <?php if ($web_data['item'][$size['size_url']] > 0) : ?>
                                    <label class="label-size" for="size_<?php echo $size['size_url']; ?>">
                                        <?php if ($web_data['item'][$size['size_url']] <= DETAILS_SIZE_LIMIT) : ?>
                                            <div class="size-info">
                                                <span class="size-info-text mb-0-5"><?php echo $size['size_name']; ?></span>
                                                <span class="size-info-text">Kalan Adet: <?php echo $web_data['item'][$size['size_url']]; ?></span>
                                            </div>
                                        <?php else : ?>
                                            <div class="size-info">
                                                <span class="size-info-text"><?php echo $size['size_name']; ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <input class="size-radio" type="radio" name="item_size" id="size_<?php echo $size['size_url']; ?>" value="<?php echo $size['size_url']; ?>">
                                        <span class="size-circle"><?php echo strtoupper($size['size_url']); ?></span>
                                    </label>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="item_id" value="<?php echo $web_data['item']['id']; ?>">
                        <input class="btn-add-to-cart" type="submit" name="submit_addtocart" value="Sepete Ekle">
                    </form>
                    <ul class="details-info-nav">
                        <li class="details-info-item">En geç <?php echo $web_data['item']['item_shipping_time']; ?> gün içerisinde kargoya teslim.</li>
                        <li class="details-info-item"><?php echo $web_data['item']['item_give_back_time']; ?> gün içerisinde ücretsiz iade hakkı. Detaylar için <button id="btn-give-back-policy" class="btn-details-policy" title="İade Politakası">nasıl iade ederim?</button></li>
                        <div class="give-back-wrapper disable">
                            <div class="give-back-container">
                                <div class="give-back-exit-container">
                                    <div class="give-back-exit">
                                        <i class="fas fa-times give-back-exit-icon"></i>
                                    </div>
                                </div>
                                <h3 class="give-back-title">İade Politikası</h3>
                                <p class="give-back-text"><?php echo GIVE_BACK_POLICY; ?></p>
                            </div>
                        </div>
                        <li class="details-info-item">Tek seferde en fazla 10 adet sipariş verilebilir. Daha fazla adette sipariş verebilmek için <button id="btn-details-contact-us" class="btn-details-policy" title="Bizimle İletişime Geçin">bize ulaşın.</button></li>
                        <div class="details-contact-us-wrapper disable">
                            <div class="details-contact-us-container">
                                <div class="details-contact-us-exit-container">
                                    <div class="details-contact-us-exit">
                                        <i class="fas fa-times details-contact-us-exit-icon"></i>
                                    </div>
                                </div>
                                <h3 class="details-contact-us-title">Seçilen Ürünü 10 Adetten Fazla Satın Almak İçin Bizimle İletişime Geçin</h3>
                                <form action="">

                                </form>
                            </div>
                        </div>
                        <li class="details-info-item">Görseldeki modelin Bedeni (<?php echo $web_data['item']['item_model_size']; ?>) - Uzunluğu (<?php echo $web_data['item']['item_model_height'] . ' cm'; ?>) - Kilosu (<?php echo $web_data['item']['item_model_weight'] . ' kg'; ?>)</li>
                    </ul>
                    <div class="details-continue-container">
                        <span class="details-continue-text">Ürünün Özellikleri</span>
                        <div class="column">
                            <div class="details-arrows details-arrow-1"></div>
                            <div class="details-arrows details-arrow-2"></div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section-details-bottom">
                <div class="nav-details">
                    <div id="nav-properties-link" class="nav-details-container nav-details-con-1 active" title="Ürünün Özellikleri">
                        <span class="nav-details-text">Detaylar</span>
                        <span class="nav-details-line nav-details-line-1"></span>
                    </div>
                    <div id="nav-comments-link" class="nav-details-container nav-details-con-2" title="Ürüne Yapılan Yorumlar">
                        <span id="comment-title-number-btn" class="nav-details-text">Yorumlar (<?php echo !empty($web_data['comments']) ? count($web_data['comments']) : 0; ?>)</span>
                        <span class="nav-details-line nav-details-line-2"></span>
                    </div>
                </div>
                <div class="item-properties disable">
                    <h2 class="details-property-title">Ürünün Özellikleri</h2>
                    <div class="details-property">
                        <span class="details-property-text details-property-key">Materyal</span>
                        <span class="details-property-text details-property-value"><?php echo $web_data['item']['item_material']; ?></span>
                    </div>
                    <div class="details-property">
                        <span class="details-property-text details-property-key">Kumaş</span>
                        <span class="details-property-text details-property-value"><?php echo $web_data['item']['item_fabric_type']; ?></span>
                    </div>
                    <div class="details-property">
                        <span class="details-property-text details-property-key">Kesim</span>
                        <span class="details-property-text details-property-value"><?php echo $web_data['item']['item_cut_model']; ?></span>
                    </div>
                    <div class="details-property">
                        <span class="details-property-text details-property-key">Yaka</span>
                        <span class="details-property-text details-property-value"><?php echo $web_data['item']['item_lapel']; ?></span>
                    </div>
                    <div class="details-property">
                        <span class="details-property-text details-property-key">Kalınlık</span>
                        <span class="details-property-text details-property-value"><?php echo $web_data['item']['item_thickness']; ?></span>
                    </div>
                    <div class="details-property">
                        <span class="details-property-text details-property-key">Kol Tipi</span>
                        <span class="details-property-text details-property-value"><?php echo ucwords($web_data['item']['item_sleeve_type']); ?></span>
                    </div>
                    <div class="details-property">
                        <span class="details-property-text details-property-key">Kol Uzunluğu</span>
                        <span class="details-property-text details-property-value"><?php echo ucwords($web_data['item']['item_sleeve_length']); ?></span>
                    </div>
                    <div class="details-property">
                        <span class="details-property-text details-property-key">Yıkma Stili</span>
                        <span class="details-property-text details-property-value"><?php echo $web_data['item']['item_washing_style']; ?></span>
                    </div>

                </div>
                <?php if (empty($web_data['no_comment_found']) && !empty($web_data['authed_user'])) : ?>
                    <div class="comment-create-wrapper disable">
                        <div class="comment-create-container">
                            <div class="comment-create-exit-container">
                                <div class="comment-create-exit">
                                    <i class="fas fa-times comment-create-exit-icon"></i>
                                </div>
                            </div>
                            <h3 class="comment-create-title">Yorum Ekle</h3>
                            <form id="form-comment-create" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php if (!empty($web_data['csrf_token'])) : ?>
                                    <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                                <?php endif; ?>
                                <input type="hidden" name="item_url" value="<?php echo $web_data['item']['item_url']; ?>">
                                <textarea id="textarea-comment-create" class="comment-create-textarea" name="comment_text" placeholder="Ürün hakkındaki yorumunuzu burdan belirtebilirsiniz..."></textarea>
                                <div class="row">
                                    <button id="btn-comment-create-submit" class="btn-add-new-comment row-right">Yorumu Ekle</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="comment-reply-wrapper disable">
                        <div class="comment-reply-container">
                            <div class="comment-reply-exit-container">
                                <div class="comment-reply-exit">
                                    <i class="fas fa-times comment-reply-exit-icon"></i>
                                </div>
                            </div>
                            <h3 class="comment-reply-title">Yorumu Cevapla</h3>
                            <form id="form-comment-reply" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php if (!empty($web_data['csrf_token'])) : ?>
                                    <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                                <?php endif; ?>
                                <input id="comment-reply-create-id" type="hidden" name="comment_id">
                                <textarea id="textarea-comment-reply" class="comment-reply-textarea" name="comment_reply_text"></textarea>
                                <div class="row">
                                    <button id="btn-comment-reply-submit" class="btn-comment-reply right">Yorumu Cevapla</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php if (!empty($web_data['user_has_comment'])) : ?>
                        <div class="comment-update-wrapper disable">
                            <div class="comment-update-container">
                                <div class="comment-update-exit-container">
                                    <div class="comment-update-exit">
                                        <i class="fas fa-times comment-update-exit-icon"></i>
                                    </div>
                                </div>
                                <h3 class="comment-update-title">Yorumu Güncelle</h3>
                                <form id="form-comment-update" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                    <?php if (!empty($web_data['csrf_token'])) : ?>
                                        <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                                    <?php endif; ?>
                                    <input id="comment-update-id" type="hidden" name="comment_id">
                                    <textarea id="textarea-comment-update" class="comment-update-textarea" name="comment_text"></textarea>
                                    <div class="row">
                                        <button id="btn-comment-update-submit" class="btn-comment-update right">Yorumu Güncelle</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($web_data['user_has_comment']) || $web_data['authed_user']['user_role'] == ADMIN_ROLE_ID) : ?>
                        <div class="comment-delete-wrapper disable">
                            <div class="comment-delete-container">
                                <div class="comment-delete-exit-container">
                                    <div class="comment-delete-exit">
                                        <i class="fas fa-times comment-delete-exit-icon"></i>
                                    </div>
                                </div>
                                <h3 class="comment-delete-title">Yorumu Silmek İstediğinizden Emin Misiniz?</h3>
                                <form id="form-comment-delete" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                    <?php if (!empty($web_data['csrf_token'])) : ?>
                                        <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                                    <?php endif; ?>
                                    <input id="comment-delete-id" type="hidden" name="comment_id">
                                    <div class="comment-delete-row-container">
                                        <div class="comment-delete-row">
                                            <button class="btn-comment-delete-cancel" title="Silme İşlemini İptal Et">İPTAL</button>
                                            <button id="btn-comment-delete-submit" class="btn-comment-delete popup" title="Silme İşlemini Onayla">SİL</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="details-comments">
                    <div class="details-comments-row">
                        <h2 id="comment-title-number" class="comment-title">Ürüne Yapılan Yorumlar (<?php echo !empty($web_data['comments']) ? count($web_data['comments']) : 0; ?>)</h2>
                        <?php if (empty($web_data['authed_user'])) : ?>
                            <a class="btn-add-new-comment" href="<?php echo URL . URL_LOGIN . '?yonlendir=urun/' . $web_data['item']['item_url']; ?>">Yorum Ekle</a>
                        <?php else : ?>
                            <button id="btn-new-comment" class="btn-add-new-comment">Yorum Ekle</button>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($web_data['no_comment_found'])) : ?>
                        <?php foreach ($web_data['comments'] as $comment) : ?>
                            <div id="comment-container-<?php echo $comment['id']; ?>" class="comment-container">
                                <div class="comment-user">
                                    <img class="comment-user-image" title="Yorumun Yazarının Profil Fotoğrafı" src="<?php echo URL . 'assets/images/users/' . $comment['user_profile_image_path'] . '/' . $comment['user_profile_image']; ?>" alt="<?php echo ucfirst($comment['user_first_name']) . ' ' . ucfirst($comment['user_last_name']); ?>">
                                    <span class="comment-user-name" title="Yorumun Yazarı"><?php echo ucfirst($comment['user_first_name']) . ' ' . ucfirst($comment['user_last_name']); ?></span>
                                    <div class="row-right">
                                        <div class="comment-bot-container">
                                            <span class="comment-date" title="Yorumun Oluşturulma Tarihi"><?php echo date('d/m/Y', strtotime($comment['date_comment_created'])); ?></span>
                                            <?php if (empty($web_data['authed_user'])) : ?>
                                                <a class="btn-comment-reply" href="<?php echo URL . URL_LOGIN . '?yonlendir=urun/' . $web_data['item']['item_url']; ?>" title="Yoruma Cevap Yaz">Cevapla</a>
                                            <?php else : ?>
                                                <button class="btn-comment-reply btn-reply-to-comment" title="Yoruma Cevap Yaz" data-id="<?php echo $comment['id']; ?>" data-name="'<?php echo ucfirst($comment['user_first_name']) . ' ' . ucfirst($comment['user_last_name']); ?>'">Cevapla</button>
                                            <?php endif; ?>
                                            <?php if (!empty($web_data['authed_user'])) : ?>
                                                <?php if ($web_data['authed_user']['user_role'] == ADMIN_ROLE_ID) : ?>
                                                    <form id="form-approve-comment-<?php echo $comment['id']; ?>" class="form-comment-approve" title="Yorumun Herkese Görünürlüğünü Değiştir">
                                                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                                        <button class="btn-comment-approve" data-id="<?php echo $comment['id']; ?>">
                                                            <label for="comment-approved-<?php echo $comment['id']; ?>" class="label-approve-checkbox">
                                                                <span id="comment-checkmark-<?php echo $comment['id']; ?>" class="comment-checkmark-text"><?php echo (isset($comment['is_comment_approved']) && ($comment['is_comment_approved'] == 1)) ? 'Açık' : 'Kapalı'; ?></span>
                                                                <input type="checkbox" class="checkbox comment-checkbox-<?php echo $comment['id']; ?>" id="comment-approved-<?php echo $comment['id']; ?>" name="is_comment_approved" <?php echo (isset($comment['is_comment_approved']) && ($comment['is_comment_approved'] == 1)) ? 'checked' : '' ?>>
                                                                <span class="checkmark-filter"></span>
                                                            </label>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <?php if (!empty($web_data['user_has_comment']) && !empty($web_data['authed_user']['id'] == $comment['user_id'])) : ?>
                                                    <button class="btn-comment-update btn-comment-update-popup" data-id="<?php echo $comment['id']; ?>" data-value="<?php echo $comment['comment']; ?>" title="Yorumu Güncelle">Güncelle</button>
                                                <?php endif; ?>
                                                <button class="btn-comment-delete btn-comment-delete-popup" data-id="<?php echo $comment['id']; ?>" title="Yorumu Sil">Sil</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <p class="comment-text"><?php echo $comment['comment']; ?></p>
                                <div id="comment-reply-insert">
                                    <?php if (!empty($comment['comments_reply'])) : ?>
                                        <div class="comment-reply-top-container">
                                            <?php foreach ($comment['comments_reply'] as $comment_reply) : ?>
                                                <div class="comment-reply-alt-container">
                                                    <div class="comment-reply-decoration"></div>
                                                    <div class="comment-reply-decoration-2"></div>
                                                    <div class="comment-reply-decoration-3"></div>
                                                    <div class="comment-user comment-reply-user">
                                                        <img class="comment-user-image" title="Yorumun Yazarının Profil Fotoğrafı" src="<?php echo URL . 'assets/images/users/' . $comment_reply['user_profile_image_path'] . '/' . $comment_reply['user_profile_image']; ?>" alt="<?php echo ucfirst($comment_reply['user_first_name']) . ' ' . ucfirst($comment_reply['user_last_name']); ?>">
                                                        <span class="comment-user-name" title="Yorumun Yazarı"><?php echo ucfirst($comment_reply['user_first_name']) . ' ' . ucfirst($comment_reply['user_last_name']); ?></span>
                                                        <div class="row-right">
                                                            <div class="comment-bot-container">
                                                                <span class="comment-date" title="Yorum Oluşturulma Tarihi"><?php echo date('d/m/Y', strtotime($comment_reply['date_comment_reply_created'])); ?></span>
                                                                <?php if (!empty($web_data['authed_user'])) : ?>
                                                                    <?php if ($web_data['authed_user']['user_role'] == ADMIN_ROLE_ID) : ?>
                                                                        <form id="approve_reply_form_<?php echo $comment_reply['id']; ?>" class="form-comment-approve" title="Yorumun Herkese Görünürlüğünü Değiştir">
                                                                            <input type="hidden" name="comment_id" value="<?php echo $comment_reply['id']; ?>">
                                                                            <button class="btn-reply-approves" data-id="<?php echo $comment_reply['id']; ?>">
                                                                                <label for="approved_<?php echo $comment_reply['id']; ?>" class="label-approve-checkbox">
                                                                                    <span id="comment_reply_checkmark_<?php echo $comment_reply['id']; ?>" class="comment-checkmark-text"><?php echo (isset($comment_reply['is_comment_reply_approved']) && ($comment_reply['is_comment_reply_approved'] == 1)) ? 'Açık' : 'Kapalı'; ?></span>
                                                                                    <input type="checkbox" class="checkbox comment_reply_checkbox_<?php echo $comment_reply['id']; ?>" id="approved_<?php echo $comment_reply['id']; ?>" name="is_comment_approved" <?php echo (isset($comment_reply['is_comment_reply_approved']) && ($comment_reply['is_comment_reply_approved'] == 1)) ? 'checked' : '' ?>>
                                                                                    <span class="checkmark-filter"></span>
                                                                                </label>
                                                                            </button>
                                                                        </form>
                                                                        <button class="btn-comment-reply-delete btn-red" data-id="<?php echo $comment_reply['id']; ?>" data-url="<?php echo $web_data['item']['item_url']; ?>" title="Ürüne Yapılan Yorumu Sil">Sil</button>
                                                                    <?php elseif (!empty($web_data['user_has_comment']) && !empty($web_data['authed_user']['id'] == $comment_reply['user_id'])) : ?>
                                                                        <button class="btn-comment-update" data-id="<?php echo $comment_reply['id']; ?>" title="Yorumu Güncelle">Güncelle</button>
                                                                        <button class="btn-comment-reply-delete btn-red" data-id="<?php echo $comment_reply['id']; ?>" data-url="<?php echo $web_data['item']['item_url']; ?>" title="Ürüne Yapılan Yorumu Sil">Sil</button>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <form id="comment_reply_form_<?php echo $comment_reply['id']; ?>" class="comment-text-form">
                                                        <textarea id="comment_reply_text_<?php echo $comment_reply['id']; ?>" class="comment-text disable" name="comment_text" readonly><?php echo $comment_reply['comment_reply']; ?></textarea>
                                                        <input type="hidden" name="comment_reply_id" value="<?php echo $comment_reply['id']; ?>">
                                                        <?php if (!empty($web_data['csrf_token'])) : ?>
                                                            <input class="comment-update-csrf" type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                                                        <?php endif; ?>
                                                        <div class="row">
                                                            <button id="comment-reply-update-btn_<?php echo $comment_reply['id']; ?>" class="btn-comment-update2 row-right">Yorumu Güncelle</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="comment-container">
                            <span class="has-no-comment-text">Henüz ürüne yapılmış bir yorum yok.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php if (!empty($web_data['relevant_items'])) : ?>
                <section class="section-relevant-items">
                    <div class="row-items">
                        <?php foreach ($web_data['relevant_items'] as $item) : ?>
                            <div class="col-items">
                                <a href="<?php echo URL . URL_ITEM_DETAILS . '/' . $item['item_url']; ?>">
                                    <div class="card-item">
                                        <div class="card-image-container">
                                            <img class="card-image" src="<?php echo !empty($item['item_images']) ? URL . 'assets/images/items/' . $item['item_images_path'] . '/' . $item['item_images'] : ''; ?>" alt="<?php echo $item['item_name']; ?>">
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
                                            <span class="card-go-details" title="Ürünün Detayları" href="<?php echo URL . URL_ITEM_DETAILS . '/' . $item['item_url']; ?>">Detaylar<i class="fas fa-angle-right card-details-icon"></i></span>
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
    <?php require 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/home.js"></script>
    <script src="<?php echo URL; ?>assets/js/header.js"></script>
    <script>
        const zoomFigure = document.getElementById('zoom-figure-id');
        const zoomImage = document.getElementById('zoom-image-id');
        zoomFigure.addEventListener('mousemove', (e) => {
            e.preventDefault();
            var selectedZoomImage = e.currentTarget;
            selectedZoomImage.style.backgroundPosition = (e.offsetX / selectedZoomImage.offsetWidth * 100) + '% ' + (e.offsetY / selectedZoomImage.offsetHeight * 100) + '%';
        });
        let zoomIndex = 0;
        const miniImages = document.querySelectorAll('.mini-image');
        document.querySelector('.image-right-slider').addEventListener('click', (e) => {
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
        document.querySelector('.image-left-slider').addEventListener('click', (e) => {
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
        const giveBackWrapper = document.querySelector('.give-back-wrapper');
        document.getElementById('btn-give-back-policy').addEventListener('click', (e) => {
            e.preventDefault();
            if (giveBackWrapper.classList.contains('disable')) {
                giveBackWrapper.classList.remove('disable')
            }
            if (!body.classList.contains('noscroll')) {
                body.classList.add('noscroll');
            }
        });
        document.querySelector('.give-back-exit-container').addEventListener('click', (e) => {
            e.preventDefault();
            if (!giveBackWrapper.classList.contains('disable')) {
                giveBackWrapper.classList.add('disable');
            }
            if (body.classList.contains('noscroll')) {
                body.classList.remove('noscroll');
            }
        });
        giveBackWrapper.addEventListener('mouseup', (e) => {
            e.preventDefault();
            if (e.target.classList == 'give-back-wrapper') {
                if (!giveBackWrapper.classList.contains('disable')) {
                    giveBackWrapper.classList.add('disable');
                }
                if (body.classList.contains('noscroll')) {
                    body.classList.remove('noscroll');
                }
            }
        });
        const detailsContactUsWrapper = document.querySelector('.details-contact-us-wrapper');
        document.getElementById('btn-details-contact-us').addEventListener('click', (e) => {
            e.preventDefault();
            if (detailsContactUsWrapper.classList.contains('disable')) {
                detailsContactUsWrapper.classList.remove('disable')
            }
            if (!body.classList.contains('noscroll')) {
                body.classList.add('noscroll');
            }
        });
        document.querySelector('.details-contact-us-exit-container').addEventListener('click', (e) => {
            e.preventDefault();
            if (!detailsContactUsWrapper.classList.contains('disable')) {
                detailsContactUsWrapper.classList.add('disable');
            }
            if (body.classList.contains('noscroll')) {
                body.classList.remove('noscroll');
            }
        });
        detailsContactUsWrapper.addEventListener('mouseup', (e) => {
            e.preventDefault();
            if (e.target.classList == 'details-contact-us-wrapper') {
                if (!detailsContactUsWrapper.classList.contains('disable')) {
                    detailsContactUsWrapper.classList.add('disable');
                }
                if (body.classList.contains('noscroll')) {
                    body.classList.remove('noscroll');
                }
            }
        });
        document.querySelector('.details-continue-container').addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelector('.section-details-bottom').scrollIntoView();
        });
        document.querySelector('.details-item-quantity').addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelector('.details-select').classList.toggle('active');
        });
        document.querySelectorAll('.details-select-option').forEach(detailsSelectOption => {
            detailsSelectOption.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('details-option-' + detailsSelectOption.dataset.option).selected = true;
                document.querySelector('.details-item-quantity-text-2').innerHTML = detailsSelectOption.dataset.option;
            });
        });
        document.querySelectorAll('.label-size').forEach(labelSize => {
            labelSize.addEventListener('mouseover', () => {
                labelSize.classList.add('hovered');
            });
            labelSize.addEventListener('mouseout', () => {
                labelSize.classList.remove('hovered');
            });
        });
        const detailsComments = document.querySelector('.details-comments');
        const itemProperties = document.querySelector('.item-properties');
        const navPropertiesLink = document.querySelector('#nav-properties-link');
        const navCommentsLink = document.querySelector('#nav-comments-link');
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
        <?php if (empty($web_data['no_comment_found']) && !empty($web_data['authed_user'])) : ?>
            const commentCreateWrapper = document.querySelector('.comment-create-wrapper');
            document.getElementById('btn-new-comment').addEventListener('click', (e) => {
                e.preventDefault();
                if (commentCreateWrapper.classList.contains('disable')) {
                    commentCreateWrapper.classList.remove('disable');
                    document.getElementById('textarea-comment-create').focus();
                }
                if (!body.classList.contains('noscroll')) {
                    body.classList.add('noscroll');
                }
            });
            document.querySelector('.comment-create-exit-container').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentCreateWrapper.classList.contains('disable')) {
                    commentCreateWrapper.classList.add('disable');
                }
                if (body.classList.contains('noscroll')) {
                    body.classList.remove('noscroll');
                }
            });
            commentCreateWrapper.addEventListener('mouseup', (e) => {
                e.preventDefault();
                if (e.target.classList == 'comment-create-wrapper') {
                    if (!commentCreateWrapper.classList.contains('disable')) {
                        commentCreateWrapper.classList.add('disable');
                    }
                    if (body.classList.contains('noscroll')) {
                        body.classList.remove('noscroll');
                    }
                }
            });
            const commentReplyWrapper = document.querySelector('.comment-reply-wrapper');
            const textareaCommentReply = document.getElementById('textarea-comment-reply');
            const btnReplyToComments = document.querySelectorAll('.btn-reply-to-comment');
            btnReplyToComments.forEach(btnReplyToComment => {
                btnReplyToComment.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (commentReplyWrapper.classList.contains('disable')) {
                        document.getElementById('comment-reply-create-id').value = btnReplyToComment.dataset.id;
                        textareaCommentReply.setAttribute('placeholder', btnReplyToComment.dataset.name + ' isimli kullanıcının yorumuna burdan cevap yazabilirsin...');
                        commentReplyWrapper.classList.remove('disable');
                        textareaCommentReply.focus();
                    }
                    if (!body.classList.contains('noscroll')) {
                        body.classList.add('noscroll');
                    }
                });
            });
            document.querySelector('.comment-reply-exit-container').addEventListener('click', (e) => {
                e.preventDefault();
                if (!commentReplyWrapper.classList.contains('disable')) {
                    commentReplyWrapper.classList.add('disable');
                }
                if (body.classList.contains('noscroll')) {
                    body.classList.remove('noscroll');
                }
            });
            commentReplyWrapper.addEventListener('mouseup', (e) => {
                e.preventDefault();
                if (e.target.classList == 'comment-reply-wrapper') {
                    if (!commentReplyWrapper.classList.contains('disable')) {
                        commentReplyWrapper.classList.add('disable');
                    }
                    if (body.classList.contains('noscroll')) {
                        body.classList.remove('noscroll');
                    }
                }
            });
            <?php if (!empty($web_data['user_has_comment'])) : ?>
                const commentUpdateWrapper = document.querySelector('.comment-update-wrapper');
                const textareaCommentUpdate = document.getElementById('textarea-comment-update');
                document.querySelectorAll('.btn-comment-update-popup').forEach(btnCommentUpdate => {
                    btnCommentUpdate.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (commentUpdateWrapper.classList.contains('disable')) {
                            document.getElementById('comment-update-id').value = btnCommentUpdate.dataset.id;
                            textareaCommentUpdate.value = btnCommentUpdate.dataset.value;
                            commentUpdateWrapper.classList.remove('disable');
                            textareaCommentUpdate.focus();
                        }
                        if (!body.classList.contains('noscroll')) {
                            body.classList.add('noscroll');
                        }
                    });
                });
                document.querySelector('.comment-update-exit-container').addEventListener('click', (e) => {
                    e.preventDefault();
                    if (!commentUpdateWrapper.classList.contains('disable')) {
                        commentUpdateWrapper.classList.add('disable');
                    }
                    if (body.classList.contains('noscroll')) {
                        body.classList.remove('noscroll');
                    }
                });
                commentUpdateWrapper.addEventListener('mouseup', (e) => {
                    e.preventDefault();
                    if (e.target.classList == 'comment-update-wrapper') {
                        if (!commentUpdateWrapper.classList.contains('disable')) {
                            commentUpdateWrapper.classList.add('disable');
                        }
                        if (body.classList.contains('noscroll')) {
                            body.classList.remove('noscroll');
                        }
                    }
                });
            <?php endif; ?>
            <?php if (!empty($web_data['user_has_comment']) || $web_data['authed_user']['user_role'] == ADMIN_ROLE_ID) : ?>
                const commentDeleteWrapper = document.querySelector('.comment-delete-wrapper');
                const btnCommentDeletes = document.querySelectorAll('.btn-comment-delete-popup');
                btnCommentDeletes.forEach(btnCommentDelete => {
                    btnCommentDelete.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (commentDeleteWrapper.classList.contains('disable')) {
                            document.getElementById('comment-delete-id').value = btnCommentDelete.dataset.id;
                            commentDeleteWrapper.classList.remove('disable');
                        }
                        if (!body.classList.contains('noscroll')) {
                            body.classList.add('noscroll');
                        }
                    });
                });
                document.querySelector('.comment-delete-exit-container').addEventListener('click', (e) => {
                    e.preventDefault();
                    if (!commentDeleteWrapper.classList.contains('disable')) {
                        commentDeleteWrapper.classList.add('disable');
                    }
                    if (body.classList.contains('noscroll')) {
                        body.classList.remove('noscroll');
                    }
                });
                commentDeleteWrapper.addEventListener('mouseup', (e) => {
                    e.preventDefault();
                    if (e.target.classList == 'comment-delete-wrapper') {
                        if (!commentDeleteWrapper.classList.contains('disable')) {
                            commentDeleteWrapper.classList.add('disable');
                        }
                        if (body.classList.contains('noscroll')) {
                            body.classList.remove('noscroll');
                        }
                    }
                });
                document.querySelector('.btn-comment-delete-cancel').addEventListener('click', (e) => {
                    e.preventDefault();
                    if (!commentDeleteWrapper.classList.contains('disable')) {
                        commentDeleteWrapper.classList.add('disable');
                    }
                    if (body.classList.contains('noscroll')) {
                        body.classList.remove('noscroll');
                    }
                });
            <?php endif; ?>
        <?php endif; ?>
        const notificationClient = document.querySelector('.notification-instant');
        window.addEventListener('scroll', () => {
            notificationClient.classList.toggle('sticky', window.scrollY > 0);
        });
        let notificationHidden = 0;
        let notificationRemoved = 0;

        function setClientNotification(notificationMessage) {
            clearTimeout(notificationHidden);
            clearTimeout(notificationRemoved);
            notificationClient.innerHTML = notificationMessage;
            if (notificationClient.classList.contains('hidden')) {
                notificationClient.classList.remove('hidden');
            }
            if (notificationClient.classList.contains('removed')) {
                notificationClient.classList.remove('removed');
            }
            notificationHidden = setTimeout(() => {
                notificationClient.classList.add('hidden');
                notificationRemoved = setTimeout(() => {
                    notificationClient.classList.add('removed');
                }, 1500);
            }, 5000);
        }













        <?php if (!empty($web_data['authed_user'])) : ?>
            const newCommentCont = document.querySelector('#comment-create');
            const btnNewComment = document.getElementById('btn-new-comment');
            btnNewComment.addEventListener('click', () => {
                if (newCommentCont.classList.contains('active')) {
                    newCommentCont.classList.remove('active');
                    btnNewComment.innerHTML = 'Yorum Ekle';
                } else {
                    document.getElementById('textarea-comment-create').focus();
                    newCommentCont.classList.add('active');
                    btnNewComment.innerHTML = 'Yorum Panelini Gizle';
                }
            });
            <?php if (empty($web_data['no_comment_found'])) : ?>
                var replyCommentConts = document.querySelectorAll('.comment-reply');
                var replycommentTextareas = document.querySelectorAll('.reply-textarea');
                var btnReplycomments = document.querySelectorAll('.btn-reply-comment');
                for (let index = 0; index < btnReplycomments.length; index++) {
                    btnReplycomments[index].addEventListener('click', () => {
                        if (replyCommentConts[index].classList.contains('active')) {
                            replyCommentConts[index].classList.remove('active');
                            btnReplycomments[index].innerHTML = 'Cevapla';
                        } else {
                            replycommentTextareas[index].focus();
                            replyCommentConts[index].classList.add('active');
                            btnReplycomments[index].innerHTML = 'Cevap Panelini Gizle';
                        }
                    });
                }

                var csrf_token = '<?php echo !empty($web_data['csrf_token']) ? $web_data['csrf_token'] : ''; ?>';
                var location_url = '<?php echo URL . URL_ITEM_DETAILS . '/' . $web_data['item']['item_url']; ?>';
                var item_url = '<?php echo $web_data['item']['item_url']; ?>';
                var comment_number = <?php echo count($web_data['comments']); ?>;

                <?php if ($web_data['authed_user']['user_role'] == ADMIN_ROLE_ID || !empty($web_data['user_has_comment'])) : ?>
                    let commentDeleteBtns = document.querySelectorAll('.btn-comment-delete');
                    let commentReplyDeleteBtns = document.querySelectorAll('.btn-comment-reply-delete');
                    let deletePopup = document.querySelector('.delete-popup');
                    let confirmBtns = document.querySelector('.confirm-btns');
                    let btnCancel = document.querySelector('.btn-cancel');
                    let confirmTitle = document.querySelector('.confirm-title');

                    // function deletecomment() {
                    //     commentDeleteBtns.forEach(deleteBtn => {
                    //         deleteBtn.addEventListener('click', (e) => {
                    //             e.preventDefault();
                    //             let createdForm = document.createElement('form');
                    //             createdForm.setAttribute('id', 'delete-comment-form');
                    //             let input1 = document.createElement('input');
                    //             input1.setAttribute('type', 'hidden');
                    //             input1.setAttribute('name', 'comment_id');
                    //             input1.setAttribute('value', deleteBtn.dataset.id);
                    //             input1.setAttribute('id', 'delete-comment-id');
                    //             createdForm.appendChild(input1);
                    //             let input2 = document.createElement('input');
                    //             input2.setAttribute('type', 'hidden');
                    //             input2.setAttribute('name', 'form_token');
                    //             input2.setAttribute('value', csrf_token);
                    //             createdForm.appendChild(input2);
                    //             let input3 = document.createElement('input');
                    //             input3.setAttribute('type', 'hidden');
                    //             input3.setAttribute('name', 'item_url');
                    //             input3.setAttribute('value', deleteBtn.dataset.url);
                    //             createdForm.appendChild(input3);
                    //             confirmBtns.appendChild(createdForm);
                    //             let span = document.createElement('span');
                    //             span.setAttribute('class', 'text-deleting disable');
                    //             span.innerText = 'Yorum Siliniyor...';
                    //             createdForm.appendChild(span);
                    //             confirmTitle.innerText = 'Yorumu Silmek İstediğinizden Emin Misiniz?';
                    //             if (deletePopup.classList.contains('disable')) {
                    //                 deletePopup.classList.remove('disable');
                    //             }
                    //             if (!body.classList.contains('noscroll')) {
                    //                 body.classList.add('noscroll');
                    //             }
                    //             if (document.querySelector('.btn-delete').classList.contains('disable')) {
                    //                 document.querySelector('.btn-delete').classList.remove('disable');
                    //             }
                    //             if (span.classList.contains('disable')) {
                    //                 span.classList.add('disable');
                    //             }
                    //             document.querySelector('.btn-delete').addEventListener('click', () => {
                    //                 document.querySelector('.btn-delete').classList.add('disable');
                    //                 span.classList.remove('disable');
                    //             });
                    //         });
                    //     });
                    //     btnCancel.addEventListener('click', () => {
                    //         let deleteForm = document.getElementById('delete-comment-form');
                    //         deleteForm.remove();
                    //         if (!deletePopup.classList.contains('disable')) {
                    //             deletePopup.classList.add('disable');
                    //         }
                    //         if (body.classList.contains('noscroll')) {
                    //             body.classList.remove('noscroll');
                    //         }
                    //     });
                    //     deletePopup.addEventListener('mouseup', (e) => {
                    //         if (e.target.classList == 'delete-popup') {
                    //             let deleteForm = document.getElementById('delete-comment-form');
                    //             deleteForm.remove();
                    //             if (!deletePopup.classList.contains('disable')) {
                    //                 deletePopup.classList.add('disable');
                    //             }
                    //             if (body.classList.contains('noscroll')) {
                    //                 body.classList.remove('noscroll');
                    //             }
                    //         }
                    //     });
                    // }
                    // deletecomment();

                    function deletecommentreply() {
                        commentReplyDeleteBtns.forEach(deleteBtn => {
                            deleteBtn.addEventListener('click', (e) => {
                                e.preventDefault();
                                let createdForm = document.createElement('form');
                                createdForm.setAttribute('id', 'delete-comment-reply-form');
                                let input1 = document.createElement('input');
                                input1.setAttribute('type', 'hidden');
                                input1.setAttribute('name', 'comment_reply_id');
                                input1.setAttribute('value', deleteBtn.dataset.id);
                                input1.setAttribute('id', 'delete-comment-reply-id');
                                createdForm.appendChild(input1);
                                let input2 = document.createElement('input');
                                input2.setAttribute('type', 'hidden');
                                input2.setAttribute('name', 'form_token');
                                input2.setAttribute('value', csrf_token);
                                createdForm.appendChild(input2);
                                confirmBtns.appendChild(createdForm);
                                let span = document.createElement('span');
                                span.setAttribute('class', 'text-deleting disable');
                                span.innerText = 'Yorum Siliniyor...';
                                createdForm.appendChild(span);
                                confirmTitle.innerText = 'Yorumu Silmek İstediğinizden Emin Misiniz?';
                                if (deletePopup.classList.contains('disable')) {
                                    deletePopup.classList.remove('disable');
                                }
                                if (!body.classList.contains('noscroll')) {
                                    body.classList.add('noscroll');
                                }
                                if (document.querySelector('.btn-delete').classList.contains('disable')) {
                                    document.querySelector('.btn-delete').classList.remove('disable');
                                }
                                if (span.classList.contains('disable')) {
                                    span.classList.add('disable');
                                }
                                document.querySelector('.btn-delete').addEventListener('click', () => {
                                    document.querySelector('.btn-delete').classList.add('disable');
                                    span.classList.remove('disable');
                                });
                            });
                        });
                        btnCancel.addEventListener('click', () => {
                            let deleteForm = document.getElementById('delete-comment-reply-form');
                            deleteForm.remove();
                            if (!deletePopup.classList.contains('disable')) {
                                deletePopup.classList.add('disable');
                            }
                            if (body.classList.contains('noscroll')) {
                                body.classList.remove('noscroll');
                            }
                        });
                        deletePopup.addEventListener('mouseup', (e) => {
                            if (e.target.classList == 'delete-popup') {
                                let deleteForm = document.getElementById('delete-comment-reply-form');
                                deleteForm.remove();
                                if (!deletePopup.classList.contains('disable')) {
                                    deletePopup.classList.add('disable');
                                }
                                if (body.classList.contains('noscroll')) {
                                    body.classList.remove('noscroll');
                                }
                            }
                        });
                    }
                    deletecommentreply();
                <?php endif; ?>

                $(document).ready(function() {
                    var request;
                    var using = false;
                    var perm1 = <?php echo !empty($web_data['authed_user']) ? 1 : 2; ?>;
                    var perm2 = <?php echo $web_data['authed_user']['user_role'] == ADMIN_ROLE_ID ? 1 : 2; ?>;
                    var perm3 = <?php echo !empty($web_data['user_has_comment']) ? 1 : 2; ?>;
                    var perm4 = <?php echo !empty($web_data['authed_user']['id'] == $comment_reply['user_id']) ? 1 : 2; ?>;
                    $('#btn-comment-create').click(function(e) {
                        e.preventDefault();
                        if (!$.trim($('#textarea-comment-create').val())) {
                            setClientNotification('<div class="not not-danger"><span class="not-text">Yorum alanı boş olamaz</span></div>');
                        } else {
                            if (using) {
                                request.abort();
                            }
                            using = true;
                            var comment_create_form = $('#form-comment-create');
                            var inputs = comment_create_form.find('input, textarea, button');
                            var serializedData = comment_create_form.serialize();
                            inputs.prop('disabled', true);
                            request = $.ajax({
                                url: '<?php echo URL . URL_COMMENT_CREATE; ?>',
                                type: 'POST',
                                data: serializedData
                            });
                            request.done(function(response) {
                                response = jQuery.parseJSON(response);
                                if (response.hasOwnProperty('notification')) {
                                    setClientNotification(response['notification']);
                                } else {
                                    setClientNotification('<div class="not not-danger"><span class="not-text">Yorum eklenirken bir hata oldu. Lütfen tekrar deneyiniz.</span></div>');
                                }
                                if (response.hasOwnProperty('comment')) {
                                    let div1 = $("<div></div>").attr('id', 'comment-container-' + response['comment']['id']).addClass('comment-container');
                                    let div2 = $("<div></div>").addClass('comment-user');
                                    div1.append(div2);
                                    let img1 = $("<img>").addClass('comment-user-image').attr('title', 'Yorumun Yazarının Profil Fotoğrafı').attr('src', '<?php echo URL . 'assets/images/users/'; ?>' + response['comment']['user_profile_image_path'] + '/' + response['comment_user']['user_profile_image']).attr('alt', response['comment_user']['user_first_name'] + ' ' + response['comment_user']['user_last_name']);
                                    let span1 = $("<span></span>").addClass('comment-user-name').attr('title', 'Yorumun Yazarı').text(response['comment_user']['user_first_name'] + ' ' + response['comment_user']['user_last_name']);
                                    let div3 = $("<div></div>").addClass('row-right');
                                    div2.append(img1);
                                    div2.append(span1);
                                    div2.append(div3);
                                    let div4 = $("<div></div>").addClass('comment-bot-container');
                                    div3.append(div4);
                                    let span2 = $("<span></span>").addClass('comment-date').attr('title', 'Yorum Oluşturulma Tarihi').text(response['comment']['date_comment_created']);
                                    div4.append(span2);
                                    if (perm1 == 1) {
                                        let btn31 = $("<button></button>").addClass('btn-reply-comment').attr('id', 'btn-reply-comment-' + response['comment']['id']).attr('title', 'Yoruma Cevap Yaz').text('Cevapla');
                                        div4.append(btn31);
                                        if (perm2 == 1) {
                                            let form15 = $("<form></form>").addClass('form-comment-approve').attr('title', 'Yorumun Herkese Görünürlüğünü Değiştir').attr('id', 'form-approve-comment-' + response['comment_reply']['id']);
                                            let input16 = $("<input></input>").attr('type', 'hidden').attr('name', 'comment_id').attr('value', response['comment']['id']);
                                            form15.append(input16);
                                            let btn17 = $("<button></button>").addClass('btn-comment-approve').attr('data-id', response['comment']['id']);
                                            let label18 = $("<label></label>").addClass('label-approve-checkbox').attr('for', 'comment-approved-' + response['comment']['id']);
                                            let span19 = $("<span></span>").addClass('comment-checkmark-text').attr('id', 'comment-checkmark-' + response['comment']['id']).text('Kapalı');
                                            label18.append(span19);
                                            let input17 = $("<input></input>").addClass('checkbox comment-checkbox-' + response['comment']['id']).attr('type', 'checkbox').attr('name', 'is_comment_approved').attr('id', 'comment-approved-' + response['comment']['id']);
                                            label18.append(input17);
                                            let span20 = $("<span></span>").addClass('checkmark-filter');
                                            label18.append(span20);
                                            btn17.append(label18);
                                            form15.append(btn17);
                                            div4.append(form15);
                                            let btn2 = $("<button></button>").addClass('btn-comment-delete btn-red').attr('data-id', response['comment']['id']).attr('data-url', item_url).attr('title', 'Ürüne Yapılan Yorumu Sil').text('Sil');
                                            div4.append(btn2);
                                        } else if (perm3 == 1 && perm4 == 1) {
                                            let btn22 = $("<button></button>").addClass('btn-comment-update').attr('data-id', response['comment']['id']).attr('title', 'Yorumu Güncelle').text('Güncelle');
                                            div4.append(btn22);
                                            let btn2 = $("<button></button>").addClass('btn-comment-delete btn-red').attr('data-id', response['comment']['id']).attr('data-url', item_url).attr('title', 'Ürüne Yapılan Yorumu Sil').text('Sil');
                                            div4.append(btn2);
                                        }
                                    }
                                    let form23 = $("<form></form>").addClass('comment-text-form').attr('id', 'comment_form_' + response['comment']['id']);
                                    let textarea24 = $("<textarea></textarea>").addClass('comment-text disable').attr('id', 'comment_text_' + response['comment']['id']).attr('name', 'comment_text').attr('readonly', true).text(response['comment']['comment']);
                                    form23.append(textarea24);
                                    let input25 = $("<input></input>").attr('type', 'hidden').attr('name', 'comment_id').attr('value', response['comment']['id']);
                                    form23.append(input25);
                                    let input26 = $("<input></input>").addClass('comment-update-csrf').attr('type', 'hidden').attr('name', 'form_token').attr('value', response['csrf_token']);
                                    form23.append(input26);
                                    let div27 = $("<div></div>").addClass('row');
                                    let btn28 = $("<button></button>").addClass('btn-comment-update2 row-right').attr('id', 'comment-update-btn_' + response['comment']['id']).text('Yorumu Güncelle');
                                    div27.append(btn28);
                                    form23.append(div27);
                                    div1.append(form23);
                                    $('#comment-create').append(div1);
                                }
                                if (response.hasOwnProperty('csrf_token')) {
                                    csrf_token = response['csrf_token'];
                                    $('.input-token').val(csrf_token);
                                    $('.comment-update-csrf').val(csrf_token);
                                    $('.comment-reply-create-csrf').val(csrf_token);
                                } else {
                                    window.location.href = location_url;
                                }
                                comment_number = comment_number + +1;
                                $('#comment-title-number').text('Ürüne Yapılan Yorumlar (' + (comment_number) + ')');
                                $('#comment-title-number-btn').text('Yorumlar (' + (comment_number) + ')');
                                var newCommentCont = $('#comment-create');
                                var btnNewComment = $('#btn-new-comment');
                                if (newCommentCont.hasClass('active')) {
                                    newCommentCont.removeClass('active');
                                    btnNewComment.text('Yorum Ekle');
                                    $('#textarea-comment-create').val('');
                                } else {
                                    $('#textarea-comment-create').focus();
                                    newCommentCont.addClass('active');
                                    btnNewComment.text('Yorum Panelini Gizle');
                                }
                                commentDeleteBtns = document.querySelectorAll('.btn-comment-delete');
                                deletecomment();
                            });
                            request.always(function() {
                                inputs.prop('disabled', false);
                            });
                        }
                    });
                    $('.comment-reply-create-btn').each(function() {
                        $(this).click(function(e) {
                            e.preventDefault();
                            var comment_reply_textarea = $('#comment-reply-textarea_' + $(this).data('id'));
                            if (!$.trim(comment_reply_textarea.val())) {
                                setClientNotification('<div class="not not-danger"><span class="not-text">Yorum alanı boş olamaz</span></div>');
                            } else {
                                if (using) {
                                    request.abort();
                                }
                                using = true;
                                var commentReplyCon = $('#comment-reply_' + $(this).data('id'));
                                var comment_reply_create_form = $('#comment-reply-create_' + $(this).data('id'));
                                var replyComment_btn = $('#btn-reply-comment-' + $(this).data('id'));
                                var inputs = comment_reply_create_form.find('input, textarea, button');
                                var serializedData = comment_reply_create_form.serialize();
                                inputs.prop('disabled', true);
                                request = $.ajax({
                                    url: '<?php echo URL . URL_COMMENT_REPLY_CREATE; ?>',
                                    type: 'POST',
                                    data: serializedData
                                });
                                request.done(function(response) {
                                    response = jQuery.parseJSON(response);
                                    if (response.hasOwnProperty('notification')) {
                                        setClientNotification(response['notification']);
                                    } else {
                                        setClientNotification('<div class="not not-danger"><span class="not-text">Yorum eklenirken bir hata oldu. Lütfen tekrar deneyiniz.</span></div>');
                                    }
                                    if (response.hasOwnProperty('comment_reply')) {
                                        let div11 = $("<div></div>").addClass('comment-reply-alt-container');
                                        let div12 = $("<div></div>").addClass('comment-reply-decoration');
                                        let div13 = $("<div></div>").addClass('comment-reply-decoration-2');
                                        let div14 = $("<div></div>").addClass('comment-reply-decoration-3');
                                        div11.append(div12);
                                        div11.append(div13);
                                        div11.append(div14);
                                        let div2 = $("<div></div>").addClass('comment-user comment-reply-user');
                                        div11.append(div2);
                                        let img1 = $("<img>").addClass('comment-user-image').attr('title', 'Yorumun Yazarının Profil Fotoğrafı').attr('src', '<?php echo URL . 'assets/images/users/'; ?>' + response['comment_reply']['user_profile_image_path'] + '/' + response['comment_reply_user']['user_profile_image']).attr('alt', response['comment_reply_user']['user_first_name'] + ' ' + response['comment_reply_user']['user_last_name']);
                                        let span1 = $("<span></span>").addClass('comment-user-name').attr('title', 'Yorumun Yazarı').text(response['comment_reply_user']['user_first_name'] + ' ' + response['comment_reply_user']['user_last_name']);
                                        let div3 = $("<div></div>").addClass('row-right');
                                        div2.append(img1);
                                        div2.append(span1);
                                        div2.append(div3);
                                        let div4 = $("<div></div>").addClass('comment-bot-container');
                                        div3.append(div4);
                                        let span2 = $("<span></span>").addClass('comment-date').attr('title', 'Yorum Oluşturulma Tarihi').text(response['comment_reply']['date_comment_reply_created']);
                                        div4.append(span2);
                                        if (perm1 == 1) {
                                            if (perm2 == 1) {
                                                let form15 = $("<form></form>").addClass('form-comment-approve').attr('title', 'Yorumun Herkese Görünürlüğünü Değiştir').attr('id', 'approve_reply_form_' + response['comment_reply']['id']);
                                                let input16 = $("<input></input>").attr('type', 'hidden').attr('name', 'comment_reply_id').attr('value', response['comment_reply']['id']);
                                                form15.append(input16);
                                                let btn17 = $("<button></button>").addClass('btn-reply-approves').attr('data-id', response['comment_reply']['id']);
                                                let label18 = $("<label></label>").addClass('label-approve-checkbox').attr('for', 'approved_' + response['comment_reply']['id']);
                                                let span19 = $("<span></span>").addClass('comment-checkmark-text').attr('id', 'comment_reply_checkmark_' + response['comment_reply']['id']).text('Kapalı');
                                                label18.append(span19);
                                                let input17 = $("<input></input>").addClass('checkbox comment_reply_checkbox_' + response['comment_reply']['id']).attr('type', 'checkbox').attr('name', 'is_comment_approved').attr('id', 'approved_' + response['comment_reply']['id']);
                                                label18.append(input17);
                                                let span20 = $("<span></span>").addClass('checkmark-filter');
                                                label18.append(span20);
                                                btn17.append(label18);
                                                form15.append(btn17);
                                                div4.append(form15);
                                                let btn2 = $("<button></button>").addClass('btn-comment-reply-delete btn-red').attr('data-id', response['comment_reply']['id']).attr('data-url', item_url).attr('title', 'Ürüne Yapılan Yorumu Sil').text('Sil');
                                                div4.append(btn2);
                                            } else if (perm3 == 1 && perm4 == 1) {
                                                let btn22 = $("<button></button>").addClass('btn-comment-update').attr('data-id', response['comment_reply']['id']).attr('title', 'Yorumu Güncelle').text('Güncelle');
                                                div4.append(btn22);
                                                let btn2 = $("<button></button>").addClass('btn-comment-reply-delete btn-red').attr('data-id', response['comment_reply']['id']).attr('data-url', item_url).attr('title', 'Ürüne Yapılan Yorumu Sil').text('Sil');
                                                div4.append(btn2);
                                            }
                                        }
                                        let form23 = $("<form></form>").addClass('comment-text-form').attr('id', 'comment_reply_form_' + response['comment_reply']['id']);
                                        let textarea24 = $("<textarea></textarea>").addClass('comment-text disable').attr('id', 'comment_reply_text_' + response['comment_reply']['id']).attr('name', 'comment_text').attr('readonly', true).text(response['comment_reply']['comment_reply']);
                                        form23.append(textarea24);
                                        let input25 = $("<input></input>").attr('type', 'hidden').attr('name', 'comment_reply_id').attr('value', response['comment_reply']['id']);
                                        form23.append(input25);
                                        let input26 = $("<input></input>").addClass('comment-update-csrf').attr('type', 'hidden').attr('name', 'form_token').attr('value', response['csrf_token']);
                                        form23.append(input26);
                                        let div27 = $("<div></div>").addClass('row');
                                        let btn28 = $("<button></button>").addClass('btn-comment-update2 row-right').attr('id', 'comment-reply-update-btn_' + response['comment_reply']['id']).text('Yorumu Güncelle');
                                        div27.append(btn28);
                                        form23.append(div27);
                                        div11.append(form23);
                                        $('#comment-reply-insert').append(div11);
                                    }
                                    if (response.hasOwnProperty('csrf_token')) {
                                        csrf_token = response['csrf_token'];
                                        $('#.input-token').val(csrf_token);
                                        $('.comment-update-csrf').val(csrf_token);
                                        $('.comment-reply-create-csrf').val(csrf_token);
                                    } else {
                                        window.location.href = location_url;
                                    }
                                    if (commentReplyCon.hasClass('active')) {
                                        commentReplyCon.removeClass('active');
                                        replyComment_btn.text('Cevapla');
                                        comment_reply_textarea.val('');
                                    } else {
                                        comment_reply_textarea.focus();
                                        commentReplyCon.addClass('active');
                                        replyComment_btn.text('Cevap Panelini Gizle');
                                    }
                                    commentReplyDeleteBtns = document.querySelectorAll('.btn-comment-reply-delete');
                                    deletecommentreply();
                                });
                                request.always(function() {
                                    inputs.prop('disabled', false);
                                });
                            }
                        });
                    });
                    <?php if ($web_data['authed_user']['user_role'] == ADMIN_ROLE_ID) : ?>
                        $('.btn-comment-approve').each(function() {
                            $(this).click(function(e) {
                                e.preventDefault();
                                if (using) {
                                    request.abort();
                                }
                                using = true;
                                var approve_form = $('#form-approve-comment-' + $(this).data('id'));
                                var spanText = $('#comment-checkmark-' + $(this).data('id'));
                                var commentCheckbox = $('.comment_checkbox_' + $(this).data('id'));
                                var inputs = approve_form.find('input, button');
                                var serializedData = approve_form.serialize();
                                inputs.prop('disabled', true);
                                request = $.ajax({
                                    url: '<?php echo URL . URL_ADMIN_COMMENT_APPROVE; ?>',
                                    type: 'POST',
                                    data: serializedData
                                });
                                request.done(function(response) {
                                    if (spanText.text() == 'Açık') {
                                        spanText.text('Kapalı');
                                    } else {
                                        spanText.text('Açık');
                                    }
                                    if (commentCheckbox.is(':checked')) {
                                        commentCheckbox.prop('checked', false);
                                    } else {
                                        commentCheckbox.prop('checked', true);
                                    }
                                    setClientNotification(response);
                                });
                                request.always(function() {
                                    inputs.prop('disabled', false);
                                });
                            });
                        });
                        $('.btn-reply-approves').each(function() {
                            $(this).click(function(e) {
                                e.preventDefault();
                                if (using) {
                                    request.abort();
                                }
                                using = true;
                                var approve_form = $('#approve_reply_form_' + $(this).data('id'));
                                var spanText = $('#comment_reply_checkmark_' + $(this).data('id'));
                                var commentCheckbox = $('.comment_reply_checkbox_' + $(this).data('id'));
                                var inputs = approve_form.find('input, button');
                                var serializedData = approve_form.serialize();
                                inputs.prop('disabled', true);
                                request = $.ajax({
                                    url: '<?php echo URL . URL_ADMIN_COMMENT_REPLY_APPROVE; ?>',
                                    type: 'POST',
                                    data: serializedData
                                });
                                request.done(function(response) {
                                    if (spanText.text() == 'Açık') {
                                        spanText.text('Kapalı');
                                    } else {
                                        spanText.text('Açık');
                                    }
                                    if (commentCheckbox.is(':checked')) {
                                        commentCheckbox.prop('checked', false);
                                    } else {
                                        commentCheckbox.prop('checked', true);
                                    }
                                    setClientNotification(response);
                                });
                                request.always(function() {
                                    inputs.prop('disabled', false);
                                });
                            });
                        });
                        $('.btn-delete').click(function(e) {
                            e.preventDefault();
                            if (using) {
                                request.abort();
                            }
                            using = true;
                            var comment_delete_form = $('#delete-comment-form');
                            var delete_comment_id = $('#delete-comment-id');
                            var inputs = comment_delete_form.find('input');
                            var serializedData = comment_delete_form.serialize();
                            inputs.prop('disabled', true);
                            request = $.ajax({
                                url: '<?php echo URL . URL_ADMIN_COMMENT_DELETE; ?>',
                                type: 'POST',
                                data: serializedData
                            });
                            request.done(function(response) {
                                $('#comment-container-' + delete_comment_id.val()).remove();
                                response = jQuery.parseJSON(response);
                                if (response.hasOwnProperty('notification')) {
                                    setClientNotification(response['notification']);
                                } else {
                                    setClientNotification('<div class="not not-danger"><span class="not-text">Yorum silinirken bir hata oldu. Lütfen tekrar deneyiniz.</span></div>');
                                }
                                if (response.hasOwnProperty('csrf_token')) {
                                    csrf_token = response['csrf_token'];
                                    $('#.input-token').val(csrf_token);
                                    $('.comment-update-csrf').val(csrf_token);
                                    $('.comment-reply-create-csrf').val(csrf_token);
                                } else {
                                    window.location.href = location_url;
                                }
                                comment_number = comment_number - +1;
                                $('#comment-title-number').text('Ürüne Yapılan Yorumlar (' + (comment_number) + ')');
                                $('#comment-title-number-btn').text('Yorumlar (' + (comment_number) + ')');
                                $('#delete-comment-form').remove();
                                var popup = $('.delete-popup');
                                if (!popup.hasClass('disable')) {
                                    popup.addClass('disable');
                                }
                            });
                            request.always(function() {
                                inputs.prop('disabled', false);
                            });
                        });
                        $('.btn-delete-reply').click(function(e) {
                            e.preventDefault();
                            if (using) {
                                request.abort();
                            }
                            using = true;
                            var comment_delete_form = $('#delete-comment-reply-form');
                            var delete_comment_id = $('#delete-comment-reply-id');
                            var inputs = comment_delete_form.find('input');
                            var serializedData = comment_delete_form.serialize();
                            inputs.prop('disabled', true);
                            request = $.ajax({
                                url: '<?php echo URL . URL_ADMIN_COMMENT_REPLY_DELETE; ?>',
                                type: 'POST',
                                data: serializedData
                            });
                            request.done(function(response) {
                                $('#comment-container-' + delete_comment_id.val()).remove();
                                response = jQuery.parseJSON(response);
                                if (response.hasOwnProperty('notification')) {
                                    setClientNotification(response['notification']);
                                } else {
                                    setClientNotification('<div class="not not-danger"><span class="not-text">Yorum silinirken bir hata oldu. Lütfen tekrar deneyiniz.</span></div>');
                                }
                                if (response.hasOwnProperty('csrf_token')) {
                                    csrf_token = response['csrf_token'];
                                    $('#.input-token').val(csrf_token);
                                    $('.comment-update-csrf').val(csrf_token);
                                    $('.comment-reply-create-csrf').val(csrf_token);
                                } else {
                                    window.location.href = location_url;
                                }
                                comment_number = comment_number - +1;
                                $('#comment-title-number').text('Ürüne Yapılan Yorumlar (' + (comment_number) + ')');
                                $('#comment-title-number-btn').text('Yorumlar (' + (comment_number) + ')');
                                $('#delete-comment-form').remove();
                                var popup = $('.delete-popup');
                                if (!popup.hasClass('disable')) {
                                    popup.addClass('disable');
                                }
                            });
                            request.always(function() {
                                inputs.prop('disabled', false);
                            });
                        });
                    <?php elseif (!empty($web_data['user_has_comment'])) : ?>
                        $('.btn-delete').click(function(e) {
                            e.preventDefault();
                            if (using) {
                                request.abort();
                            }
                            using = true;
                            var comment_delete_form = $('#delete-comment-form');
                            var delete_comment_id = $('#delete-comment-id');
                            var inputs = comment_delete_form.find('input');
                            var serializedData = comment_delete_form.serialize();
                            inputs.prop('disabled', true);
                            request = $.ajax({
                                url: '<?php echo URL . URL_COMMENT_DELETE; ?>',
                                type: 'POST',
                                data: serializedData
                            });
                            request.done(function(response) {
                                $('#comment-container-' + delete_comment_id.val()).remove();
                                response = jQuery.parseJSON(response);
                                if (response.hasOwnProperty('notification')) {
                                    setClientNotification(response['notification']);
                                } else {
                                    setClientNotification('<div class="not not-danger"><span class="not-text">Yorum silinirken bir hata oldu. Lütfen tekrar deneyiniz.</span></div>');
                                }
                                if (response.hasOwnProperty('csrf_token')) {
                                    csrf_token = response['csrf_token'];
                                    $('#.input-token').val(csrf_token);
                                    $('.comment-update-csrf').val(csrf_token);
                                    $('.comment-reply-create-csrf').val(csrf_token);
                                } else {
                                    window.location.href = location_url;
                                }
                                comment_number = comment_number - +1;
                                $('#comment-title-number').text('Ürüne Yapılan Yorumlar (' + (comment_number) + ')');
                                $('#comment-title-number-btn').text('Yorumlar (' + (comment_number) + ')');
                                $('#delete-comment-form').remove();
                                var popup = $('.delete-popup');
                                if (!popup.hasClass('disable')) {
                                    popup.addClass('disable');
                                }
                            });
                            request.always(function() {
                                inputs.prop('disabled', false);
                            });
                        });
                        <?php if (!empty($web_data['user_has_comment_reply'])) : ?>

                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!empty($web_data['user_has_comment'])) : ?>
                        // $('.btn-comment-update').each(function() {
                        //     $(this).click(function(e) {
                        //         e.preventDefault();
                        //         var currentBtn = $(this);
                        //         var commentForm = $('#comment_form_' + currentBtn.data('id'));
                        //         var commentText = $('#comment_text_' + currentBtn.data('id'));
                        //         var commentUpdateBtn2 = $('#comment-update-btn_' + currentBtn.data('id'));
                        //         if (commentText.hasClass('error')) {
                        //             commentText.removeClass('error');
                        //         }
                        //         if (commentText.hasClass('disable')) {
                        //             commentText.removeAttr('readonly');
                        //             commentText.removeClass('disable');
                        //             commentText.focus();
                        //             currentBtn.text('Güncelleme Panelini Gizle');
                        //             if (!commentUpdateBtn2.hasClass('active')) {
                        //                 commentUpdateBtn2.addClass('active');
                        //             }
                        //             commentUpdateBtn2.click(function(e) {
                        //                 e.preventDefault();
                        //                 if (using) {
                        //                     request.abort();
                        //                 }
                        //                 using = true;
                        //                 var inputs = commentForm.find('input,textarea');
                        //                 var serializedData = commentForm.serialize();
                        //                 inputs.prop('disabled', true);
                        //                 request = $.ajax({
                        //                     url: '<?php echo URL . URL_COMMENT_UPDATE; ?>',
                        //                     type: 'POST',
                        //                     data: serializedData
                        //                 });
                        //                 request.done(function(response) {
                        //                     response = jQuery.parseJSON(response);
                        //                     if (response.hasOwnProperty('notification_success')) {
                        //                         setClientNotification(response['notification_success']);
                        //                         if (response.hasOwnProperty('csrf_token')) {
                        //                             csrf_token = response['csrf_token'];
                        //                             $('#.input-token').val(csrf_token);
                        //                             $('.comment-update-csrf').val(csrf_token);
                        //                             $('.comment-reply-create-csrf').val(csrf_token);
                        //                         } else {
                        //                             window.location.href = location_url;
                        //                         }
                        //                     } else if (response.hasOwnProperty('notification_warning')) {
                        //                         setClientNotification(response['notification_warning']);
                        //                     } else {
                        //                         setClientNotification('<div class="not not-danger"><span class="not-text">Yorum güncellenirken bir hata oldu. Lütfen tekrar deneyiniz.</span></div>');
                        //                     }
                        //                     if (response.hasOwnProperty('comment')) {
                        //                         commentText.val(response['comment']);
                        //                     } else {
                        //                         commentText.val('Yorum görüntülenirken bir hata oldu. Sayfayı yenileyiniz.');
                        //                         commentText.addClass('error');
                        //                     }
                        //                     commentText.attr('readonly', true);
                        //                     commentText.addClass('disable');
                        //                     currentBtn.text('Güncelle');
                        //                     if (commentUpdateBtn2.hasClass('active')) {
                        //                         commentUpdateBtn2.removeClass('active');
                        //                     }
                        //                 });
                        //                 request.always(function() {
                        //                     inputs.prop('disabled', false);
                        //                 });
                        //             });
                        //         } else {
                        //             commentText.attr('readonly', true);
                        //             commentText.addClass('disable');
                        //             currentBtn.text('Güncelle');
                        //             if (commentUpdateBtn2.hasClass('active')) {
                        //                 commentUpdateBtn2.removeClass('active');
                        //             }
                        //         }
                        //     });
                        // });
                        <?php if (!empty($web_data['user_has_comment_reply'])) : ?>

                        <?php endif; ?>
                    <?php endif; ?>
                });
            <?php endif; ?>
            <?php if (empty($web_data['no_comment_found'])) : ?>
                document.querySelectorAll('.comment-text').forEach(commentText => {
                    if (commentText.clientHeight < commentText.scrollHeight) {
                        commentText.style.height = commentText.scrollHeight + 'px';
                    }
                });
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>

</html>