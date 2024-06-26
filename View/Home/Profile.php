<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo $web_data['profile_title'] . ' | ' . BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="profile-section container">
            <div class="row">
                <div class="nav-menu">
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_INFORMATIONS ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_INFORMATIONS; ?>">Hesabım</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_IDENTITY_NUMBER ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_IDENTITY_NUMBER; ?>">Kimlik Numaram</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_ADDRESS ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_ADDRESS; ?>">Adreslerim</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_PASSWORD ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_PASSWORD; ?>">Şifremi Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_EMAIL ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_EMAIL; ?>">Emailimi Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_PHONE ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_PHONE; ?>">Telefon Numaramı Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_PHOTO ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_PHOTO; ?>">Profil Fotoğrafımı Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_ORDERS ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_ORDERS; ?>">Siparişlerim</a>
                </div>
                <div class="profile-container">
                    <?php if ($web_data['profile_type'] == URL_PROFILE_INFORMATIONS) : ?>
                        <div class="row-space">
                            <div class="left">
                                <h1 class="title mb">Hesabım</h1>
                            </div>
                            <?php if ($web_data['authenticated_user']['user_delete_able'] == 1) : ?>
                                <div class="right">
                                    <form action="<?php echo URL . URL_PROFILE_DELETE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                        <?php if (!empty($web_data['form_token'])) : ?>
                                            <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                        <?php endif; ?>
                                        <input class="btn-danger btn-delete-account mb" type="submit" name="submit_user_delete" value="Hesabımı Sil" title="Hesabımı Sil">
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        <form id="form-user-update" action="<?php echo URL . URL_PROFILE_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-row">
                                <span class="label">İsim</span>
                                <input class="input" type="text" id="first-name" name="user_first_name" value="<?php echo $web_data['authenticated_user']['first_name']; ?>" placeholder="İsminizi Girin" autofocus>
                            </div>
                            <div class="form-row">
                                <span class="label">Soy İsim</span>
                                <input class="input" type="text" id="last-name" name="user_last_name" value="<?php echo $web_data['authenticated_user']['last_name']; ?>" placeholder="Soy İsminizi Girin">
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" id="btn-user-update" type="submit" value="Bilgilerimi Güncelle" title="Bilgilerimi Güncelle">
                                </div>
                            </div>
                        </form>
                        <div class="row-space">
                            <div class="left">
                                <form id="form-two-fa" action="<?php echo URL . URL_PROFILE_2FA; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                    <label for="mfa-id" class="mfa-checkbox-wrapper">
                                        <?php if (!empty($web_data['form_token'])) : ?>
                                            <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                        <?php endif; ?>
                                        <input class="mfa-checkbox" id="mfa-id" type="checkbox" name="mfa" <?php echo $web_data['authenticated_user']['two_fa_enable'] == 0 ? '' : ' checked'; ?>>
                                        <div class="mfa-bg">
                                            <?php if ($web_data['authenticated_user']['two_fa_enable'] == 0) : ?>
                                                <span class="mfa-inner-text">Kapalı</span>
                                            <?php else : ?>
                                                <span class="mfa-inner-text">Açık</span>
                                            <?php endif; ?>
                                            <div class="mfa-ball"></div>
                                        </div>
                                        <span class="mfa-text">2 Aşamalı Doğrulama</span>
                                    </label>
                                </form>
                            </div>
                        </div>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_IDENTITY_NUMBER) : ?>
                        <h1 class="title mb">T.C. Kimlik Numarası</h1>
                        <form id="form-update-identity" action="<?php echo URL . URL_IDENTITY_NUMBER_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-update-wrapper">
                                <div class="form-row">
                                    <span class="label">Kimlik Numarası</span>
                                    <input class="input" id="user-identity" type="text" name="user_identity_number" maxlength="11" value="<?php echo $web_data['authenticated_user']['identity_number']; ?>" placeholder="Kimlik Numaranızı Girin" autofocus>
                                </div>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" id="btn-identity-update" type="submit" value="Kimlik Numaramı Değiştir" title="Kimlik Numaramı Değiştir">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_ADDRESS) : ?>
                        <div class="row-space">
                            <div class="left">
                                <h1 class="title mb">Adreslerim</h1>
                            </div>
                            <div class="right">
                                <button id="btn-create-address" class="btn-success mb" title="Yeni Adres Ekle">Adres Ekle</button>
                            </div>
                        </div>
                        <div id="popup-wrapper" class="create-address-wrapper disable">
                            <div class="popup-container">
                                <div id="create-address-wrapper-exit" class="popup-exit">
                                    <div class="exit">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                                <h4 class="title">Yeni Adres Ekle</h4>
                                <form class="form-creare-address" action="<?php echo URL . URL_ADDRESS_CREATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                    <?php if (!empty($web_data['form_token'])) : ?>
                                        <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                    <?php endif; ?>
                                    <div class="form-row">
                                        <span class="label">Ülke</span>
                                        <input class="input" type="text" value="Türkiye" readonly>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">İl</span>
                                        <div class="city-row">
                                            <div id="details-city" class="item-city">
                                                <select class="city-select" name="city">
                                                    <option id="details-option-0"></option>
                                                    <?php foreach ($web_data['address_city'] as $address_city) : ?>
                                                        <option id="details-option-<?php echo $address_city['id']; ?>" value="<?php echo $address_city['id']; ?>"></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span id="select-text" class="select-text">İl Seçin</span>
                                                <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                                                <div id="details-select-city" class="details-select-city">
                                                    <?php foreach ($web_data['address_city'] as $address_city) : ?>
                                                        <span class="option" data-option="<?php echo $address_city['id']; ?>" data-url="<?php echo $address_city['city_name']; ?>"><?php echo $address_city['city_name']; ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">İlçe</span>
                                        <div class="county-row">
                                            <div id="details-county" class="item-county disable">
                                                <select class="county-select" name="county">
                                                    <option id="details-county-option-0"></option>
                                                </select>
                                                <span id="select-text-county" class="select-text">İlçe Seçin</span>
                                                <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                                                <div id="details-select-county" class="details-select-county">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <span class="label textarea-label">Açık Adres</span>
                                        <textarea class="input textarea-input" name="full_address"></textarea>
                                        <span class="info">
                                            <i class="fas fa-info icon"></i>
                                            <span class="info-tri"></span>
                                        </span>
                                        <span class="info-text"><?php echo SAMPLE_ADDRESS; ?></span>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">Adres Adı</span>
                                        <input type="text" class="input" name="address_quick_name" placeholder="Örnek: Ev">
                                    </div>
                                    <div class="row-space">
                                        <div class="right">
                                            <input class="btn-success" type="submit" name="submit_address_create" value="Adres Ekle" title="Yeni Adres Ekle">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="popup-wrapper" class="update-address-wrapper disable">
                            <div class="popup-container">
                                <div id="update-address-wrapper-exit" class="popup-exit">
                                    <div class="exit">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                                <h4 class="title">Adresi Düzenle</h4>
                                <form class="form-creare-address" action="<?php echo URL . URL_ADDRESS_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                    <?php if (!empty($web_data['form_token'])) : ?>
                                        <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                    <?php endif; ?>
                                    <input id="update-id" class="input" type="hidden" name="id">
                                    <div class="form-row">
                                        <span class="label">Ülke</span>
                                        <input class="input" type="text" value="Türkiye" readonly>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">İl</span>
                                        <div class="city-row">
                                            <div id="update-details-city" class="item-city">
                                                <select class="city-select" name="city">
                                                    <option id="selected-update-city" selected></option>
                                                    <?php foreach ($web_data['address_city'] as $address_city) : ?>
                                                        <option id="update-details-option-<?php echo $address_city['id']; ?>" value="<?php echo $address_city['id']; ?>"></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span id="update-city" class="select-text"></span>
                                                <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                                                <div id="update-details-select-city" class="update-details-select-city">
                                                    <?php foreach ($web_data['address_city'] as $address_city) : ?>
                                                        <span class="option" data-option="<?php echo $address_city['id']; ?>" data-url="<?php echo $address_city['city_name']; ?>"><?php echo $address_city['city_name']; ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">İlçe</span>
                                        <div class="county-row">
                                            <div id="update-details-county" class="item-county">
                                                <select class="update-county-select" name="county">
                                                    <option id="selected-update-county" selected></option>
                                                </select>
                                                <span id="update-county" class="select-text"></span>
                                                <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                                                <div id="update-details-select-county" class="update-details-select-county">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <span class="label textarea-label">Açık Adres</span>
                                        <textarea id="update-full_address" class="input textarea-input" name="full_address"></textarea>
                                        <span class="info">
                                            <i class="fas fa-info icon"></i>
                                            <span class="info-tri"></span>
                                        </span>
                                        <span class="info-text"><?php echo SAMPLE_ADDRESS; ?></span>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">Adres Adı</span>
                                        <input type="text" id="update-address_quick_name" class="input" name="address_quick_name" placeholder="Örnek: Ev">
                                    </div>
                                    <div class="row-space">
                                        <div class="right">
                                            <input class="btn-warning" type="submit" name="submit_address_update" value="Adresi Düzenle" title="Adresi Düzenle">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="popup-wrapper" class="delete-address-wrapper disable">
                            <div class="popup-container">
                                <div id="delete-address-wrapper-exit" class="popup-exit">
                                    <div class="exit">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                                <h4 class="title center">Adresi Silmek İstediğinizden Emin Misiniz?</h4>
                                <form id="form-address-delete" action="<?php echo URL . URL_ADDRESS_DELETE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                    <?php if (!empty($web_data['form_token'])) : ?>
                                        <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                    <?php endif; ?>
                                    <input id="input-address-id" type="hidden" name="address_id">
                                    <div class="delete-row">
                                        <button id="btn-address-delete-cancel" class="btn-warning padding m-right" title="Silme İşlemini İptal Et">İPTAL</button>
                                        <button type="submit" name="submit-delete-address" class="btn-danger padding btn-address-delete" title="Silme İşlemini Onayla">SİL</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="address-container">
                            <?php if (!empty($web_data['user_address'])) : ?>
                                <?php foreach ($web_data['user_address'] as $address) : ?>
                                    <div class="address-box">
                                        <div class="address-quick-name-wrapper">
                                            <span class="quick-name"><?php echo $address['address_quick_name']; ?></span>
                                            <span class="address-text"><?php echo $address['full_address'] . ' ' . $address['address_county'] . '/' . $address['address_city'] . '/' . $address['address_country']; ?></span>
                                        </div>
                                        <div class="address-btn-row">
                                            <?php
                                                $js_data_county = '{';
                                                foreach ($address['update_county'] as $key_update_county => $update_county) {
                                                    $js_data_county .= '"' . $key_update_county . '": "' . $update_county . '", ';
                                                }
                                                $js_data_county = rtrim($js_data_county, ', ') . '}';
                                            ?>
                                            <span class="btn-update-address btn-warning mr" data-id="<?php echo $address['id']; ?>" data-full_address="<?php echo $address['full_address']; ?>" data-address_quick_name="<?php echo $address['address_quick_name']; ?>" data-county="<?php echo $address['address_county']; ?>" data-city="<?php echo $address['address_city']; ?>" data-city_id="<?php echo $address['city_id']; ?>" data-county_id="<?php echo $address['county_id']; ?>" data-update-selected-counties='<?php echo $js_data_county; ?>'>Düzenle</span>
                                            <span class="btn-danger btn-address-remove btn-delete-address" data-id="<?php echo $address['id']; ?>">Sil</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <span class="address-danger-text">Kayıtlı Adresiniz Yok</span>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_PASSWORD) : ?>
                        <h1 class="title">Şifremi Değiştir</h1>
                        <form id="form-password-update" action="<?php echo URL . URL_PASSWORD_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-update-wrapper">
                                <div class="form-row form-password-row relative-password-row">
                                    <span class="label">Güncel Şifre</span>
                                    <input class="input" id="input-oldpassword" type="password" name="user_old_password" placeholder="Güncel Şifrenizi Girin" autofocus>
                                    <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                                </div>
                                <div class="form-row form-password-row">
                                    <span class="input-message" id="oldpassword-message"></span>
                                </div>
                                <div class="form-row form-password-row relative-password-row">
                                    <span class="label">Yeni Şifre</span>
                                    <input class="input" id="input-password" type="password" name="user_new_password" placeholder="Yeni Şifrenizi Girin">
                                    <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                                </div>
                                <div class="form-row form-password-row">
                                    <span class="input-message" id="password-message"></span>
                                </div>
                                <div class="form-row form-password-row relative-password-row">
                                    <span class="label">Yeni Şifre Tekrar</span>
                                    <input class="input" id="input-repassword" type="password" name="user_new_re_password" placeholder="Yeni Şifrenizi Tekrar Girin">
                                    <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                                </div>
                                <div class="form-row form-password-row">
                                    <span class="input-message" id="repassword-message"></span>
                                </div>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" id="btn-password-update" type="submit" value="Şifremi Değiştir" title="Şifremi Değiştir">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_EMAIL) : ?>
                        <h1 class="title">Emailimi Değiştir</h1>
                        <form id="form-email-update" action="<?php echo URL . URL_EMAIL_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-update-wrapper">
                                <div class="form-row">
                                    <span class="label">Email</span>
                                    <input class="input" type="email" id="user-email" name="user_email" value="<?php echo $web_data['authenticated_user']['email']; ?>" placeholder="Yeni Emailinizi Girin" autofocus>
                                </div>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" id="btn-email-update" type="submit" value="Emailimi Değiştir" title="Emailimi Değiştir">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_PHONE) : ?>
                        <h1 class="title">Telefon Numaramı Değiştir</h1>
                        <form id="form-update-phone" action="<?php echo URL . URL_PHONE_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-update-wrapper">
                                <div class="form-row">
                                    <span class="label">Telefon Numarası</span>
                                    <span class="label-phone">(+90)</span>
                                    <input class="input input-phone" id="user-phone" type="tel" name="user_phone_number" maxlength="10" value="<?php echo $web_data['authenticated_user']['phone_number']; ?>" placeholder="Telefon Numaranızı Girin" autofocus>
                                </div>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" id="btn-phone-update" type="submit" value="Telefon Numaramı Değiştir" title="Telefon Numaramı Değiştir">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_PHOTO) : ?>
                        <h1 class="title">Profil Fotoğrafımı Değiştir</h1>
                        <form action="<?php echo URL . URL_PROFILE_PHOTO_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate enctype="multipart/form-data">
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <label for="upload-image-1" class="image-label">
                                <img class="profile-image" src="<?php echo URL . 'assets/images/users/' . $web_data['authenticated_user']['profile_image_path'] . '/' . $web_data['authenticated_user']['profile_image']; ?>" alt="Profil Fotoğrafı">
                                <div class="upload-image-icon-container">
                                    <i class="fas fa-camera upload-image-icon"></i>
                                </div>
                            </label>
                            <input id="upload-image-1" class="input-user-image" type="file" name="user_image" accept=".jpg, .jpeg, .png">
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning mt-2" type="submit" name="update_profile_photo" value="Profil Fotoğrafını Güncelle" title="Profil Fotoğrafını Güncelle">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_ORDERS) : ?>
                        <h1 class="title">Siparişlerim</h1>
                        <?php if (!empty($web_data['orders'])) : ?>
                            <div class="order-wrapper">
                                <div class="order-container">
                                    <span class="box-header box-m">Alıcının İsmi</span>
                                    <span class="box-header box-l-2">Alıcının Adresi</span>
                                    <span class="box-header box-xs">Ödenen Ücret</span>
                                    <span class="box-header box-xs" title="Sipariş Durumu">Durum</span>
                                    <span class="box-header">Detaylar</span>
                                </div>
                                <?php $i = 0; ?>
                                <?php foreach ($web_data['orders'] as $key => $order) : ?>
                                    <?php $i++; ?>
                                    <div class="order-container">
                                        <span class="box box-m"><?php echo $order['order_informations']['shipping_contact_name']; ?></span>
                                        <span class="box box-l-2"><?php echo $order['order_informations']['shipping_address'] . ' ' . $order['order_informations']['shipping_city'] . '/' . $order['order_informations']['shipping_country']; ?></span>
                                        <span class="box box-xs"><?php echo $order['order_informations']['paid_price']; ?> ₺</span>
                                        <?php if ($order['order_informations']['status'] == 1) : ?>
                                            <span class="box box-xs">Onay Bekliyor</span>
                                        <?php elseif ($order['order_informations']['status'] == 2) : ?>
                                            <span class="box box-xs">Onaylandı. Kargoya Verilecek</span>
                                        <?php elseif ($order['order_informations']['status'] == 3) : ?>
                                            <span class="box box-xs">Onaylandı ve Kargoya Verildi</span>
                                        <?php elseif ($order['order_informations']['status'] == 4) : ?>
                                            <span class="box box-xs">Teslim Edildi</span>
                                        <?php elseif ($order['order_informations']['status'] == 5) : ?>
                                            <span class="box box-xs">İptal Edildi</span>
                                        <?php elseif ($order['order_informations']['status'] == 6) : ?>
                                            <span class="box box-xs">İade Edilen Sipariş Onaylandı ve Kargoya Verildi</span>
                                        <?php elseif ($order['order_informations']['status'] == 7) : ?>
                                            <span class="box box-xs">İade Edilen Sipariş Teslim Edildi</span>
                                        <?php endif; ?>
                                        <span class="box-details extend-order-details-<?php echo $i; ?>"><i class="fas fa-chevron-right"></i></span>
                                    </div>
                                    <div id="popup-wrapper" class="order-details-popup-wrapper-<?php echo $i; ?> disable">
                                        <div class="popup-order-container">
                                            <div id="order-details-popup-wrapper-exit-<?php echo $i; ?>" class="popup-exit">
                                                <div class="exit">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                            </div>
                                            <div class="order-title-row">
                                                <div class="left">
                                                    <h4 class="title">Sipariş Detayı</h4>
                                                </div>
                                                <div class="right">
                                                    <span class="order-date" title="Sipariş Verilme Tarihi"><?php echo $order['order_informations']['date_order_initialize_created']; ?></span>
                                                </div>
                                            </div>
                                            <div class="order-basket-wrapper">
                                                <div class="order-container">
                                                    <span class="box-header box-l">Ürün İsimi</span>
                                                    <span class="box-header box-xs">Kategori</span>
                                                    <span class="box-header box-s">Beden</span>
                                                    <span class="box-header box-xs">Adet</span>
                                                    <span class="box-header box-xs" title="Adet Sayısı Dahil Edilmiş Ürünün Toplam Ücreti">Ücret</span>
                                                </div>
                                                <?php foreach ($order['order_basket'] as $order_basket) : ?>
                                                    <div class="order-container">
                                                        <span class="box box-l"><?php echo $order_basket['item_name']; ?></span>
                                                        <span class="box box-xs"><?php echo $order_basket['item_category']; ?></span>
                                                        <span class="box box-s"><?php echo $order_basket['item_size_name']; ?></span>
                                                        <span class="box box-xs">x<?php echo $order_basket['item_quantity']; ?></span>
                                                        <span class="box box-xs"><?php echo $order_basket['item_discount_price']; ?> ₺</span>
                                                    </div>
                                                <?php endforeach; ?>
                                                <div class="hr-line"></div>
                                                <div class="order-container mt">
                                                    <span class="box-header box-m">Alıcının İsmi</span>
                                                    <span class="box-header box-l">Alıcının Adresi</span>
                                                    <span class="box-header box-xs-2">Ödenen Toplam Ücret</span>
                                                    <span class="box-header box-xs-2" title="Sipariş Durumu">Durum</span>
                                                </div>
                                                <div class="order-container">
                                                    <span class="box box-m"><?php echo $order['order_informations']['shipping_contact_name']; ?></span>
                                                    <span class="box box-l"><?php echo $order['order_informations']['shipping_address'] . ' ' . $order['order_informations']['shipping_city'] . '/' . $order['order_informations']['shipping_country']; ?></span>
                                                    <span class="box box-xs-2"><?php echo $order['order_informations']['paid_price']; ?> ₺</span>
                                                    <?php if ($order['order_informations']['status'] == 1) : ?>
                                                        <span class="box box-xs-2">Onay Bekliyor</span>
                                                    <?php elseif ($order['order_informations']['status'] == 2) : ?>
                                                        <span class="box box-xs-2">Onaylandı. Kargoya Verilecek</span>
                                                    <?php elseif ($order['order_informations']['status'] == 3) : ?>
                                                        <span class="box box-xs-2">Onaylandı ve Kargoya Verildi</span>
                                                    <?php elseif ($order['order_informations']['status'] == 4) : ?>
                                                        <span class="box box-xs-2">Teslim Edildi</span>
                                                    <?php elseif ($order['order_informations']['status'] == 5) : ?>
                                                        <span class="box box-xs-2">İptal Edildi</span>
                                                    <?php elseif ($order['order_informations']['status'] == 6) : ?>
                                                        <span class="box box-xs-2">İade Edilen Sipariş Onaylandı ve Kargoya Verildi</span>
                                                    <?php elseif ($order['order_informations']['status'] == 7) : ?>
                                                        <span class="box box-xs-2">İade Edilen Sipariş Teslim Edildi</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="basket-bot">
                                                    <div class="right">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <span class="not-found-order">Verilmiş siparişiniz yok</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <?php if (!empty($web_data['cookie_cart'])) : ?>
        <script src="<?php echo URL; ?>assets/js/header_cart.js"></script>
    <?php endif; ?>
    <?php if ($web_data['profile_type'] == URL_PROFILE_ADDRESS) : ?>
        <script>
            const createAddressWrapper = document.querySelector('.create-address-wrapper');
            document.getElementById('btn-create-address').addEventListener('click', (e) => {
                e.preventDefault();
                if (createAddressWrapper.classList.contains('disable')) {
                    createAddressWrapper.classList.remove('disable');
                }
                if (!bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.add('noscroll');
                }
            });
            document.getElementById('create-address-wrapper-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!createAddressWrapper.classList.contains('disable')) {
                    createAddressWrapper.classList.add('disable');
                }
                if (bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.remove('noscroll');
                }
            });
            const updateAddressWrapper = document.querySelector('.update-address-wrapper');
            document.querySelectorAll('.btn-update-address').forEach(element => {
                element.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.getElementById('update-id').value = element.dataset.id;
                    document.getElementById('update-city').innerHTML = element.dataset.city;
                    document.getElementById('update-county').innerHTML = element.dataset.county;
                    document.getElementById('update-full_address').value = element.dataset.full_address;
                    document.getElementById('update-address_quick_name').value = element.dataset.address_quick_name;
                    document.getElementById('selected-update-city').value = element.dataset.city_id;
                    console.log(element.dataset.county_id);
                    document.getElementById('selected-update-county').value = element.dataset.county_id;
                    if (updateAddressWrapper.classList.contains('disable')) {
                        updateAddressWrapper.classList.remove('disable');
                    }
                    if (!bodyElement.classList.contains('noscroll')) {
                        bodyElement.classList.add('noscroll');
                    }
                });
            });
            document.getElementById('update-address-wrapper-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!updateAddressWrapper.classList.contains('disable')) {
                    updateAddressWrapper.classList.add('disable');
                }
                if (bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.remove('noscroll');
                }
            });
            const deleteAddressWrapper = document.querySelector('.delete-address-wrapper');
            document.querySelectorAll('.btn-delete-address').forEach(element => {
                element.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.getElementById('input-address-id').value = element.dataset.id;
                    if (deleteAddressWrapper.classList.contains('disable')) {
                        deleteAddressWrapper.classList.remove('disable');
                    }
                    if (!bodyElement.classList.contains('noscroll')) {
                        bodyElement.classList.add('noscroll');
                    }
                });
            });
            document.getElementById('delete-address-wrapper-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!deleteAddressWrapper.classList.contains('disable')) {
                    deleteAddressWrapper.classList.add('disable');
                }
                if (bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.remove('noscroll');
                }
            });
            document.getElementById('btn-address-delete-cancel').addEventListener('click', (e) => {
                e.preventDefault();
                if (!deleteAddressWrapper.classList.contains('disable')) {
                    deleteAddressWrapper.classList.add('disable');
                }
                if (bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.remove('noscroll');
                }
            });
            const detailsSelectCity = document.getElementById('details-select-city');
            const detailsSelectCounty = document.getElementById('details-select-county');
            document.getElementById('details-city').addEventListener('click', (e) => {
                e.preventDefault();
                if (detailsSelectCounty.classList.contains('active')) {
                    detailsSelectCounty.classList.remove('active');
                }
                detailsSelectCity.classList.toggle('active');
            });
            document.querySelectorAll('.item-city .details-select-city .option').forEach(detailsSelectOption => {
                detailsSelectOption.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.getElementById('details-option-' + detailsSelectOption.dataset.option).selected = true;
                    document.getElementById('select-text').innerHTML = detailsSelectOption.dataset.url;
                });
            });
            document.getElementById('details-county').addEventListener('click', (e) => {
                e.preventDefault();
                if (detailsSelectCity.classList.contains('active')) {
                    detailsSelectCity.classList.remove('active');
                }
                detailsSelectCounty.classList.toggle('active');
            });
            const updateDetailsSelectCity = document.getElementById('update-details-select-city');
            const updateDetailsSelectCounty = document.getElementById('update-details-select-county');
            document.getElementById('update-details-city').addEventListener('click', (e) => {
                e.preventDefault();
                if (updateDetailsSelectCounty.classList.contains('active')) {
                    updateDetailsSelectCounty.classList.remove('active');
                }
                updateDetailsSelectCity.classList.toggle('active');
            });
            document.querySelectorAll('.item-city .update-details-select-city .option').forEach(detailsSelectOption => {
                detailsSelectOption.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.getElementById('update-details-option-' + detailsSelectOption.dataset.option).selected = true;
                    document.getElementById('update-city').innerHTML = detailsSelectOption.dataset.url;
                    document.getElementById('update-county').innerHTML = 'İlçe Seçin';
                });
            });
            document.getElementById('update-details-county').addEventListener('click', (e) => {
                e.preventDefault();
                if (updateDetailsSelectCity.classList.contains('active')) {
                    updateDetailsSelectCity.classList.remove('active');
                }
                updateDetailsSelectCounty.classList.toggle('active');
            });
        </script>
    <?php endif; ?>
    <?php if ($web_data['profile_type'] == URL_PROFILE_PASSWORD) : ?>
        <script>
            const eyeBtns = document.querySelectorAll('.btn-action-password');
            eyeBtns.forEach((eyeBtn) => {
                eyeBtn.addEventListener('click', () => {
                    let inputPassword = eyeBtn.parentElement.children[1];
                    if (inputPassword.type === 'password') {
                        inputPassword.type = 'text';
                        eyeBtn.classList.remove('fa-eye-slash');
                        eyeBtn.classList.add('fa-eye');
                    } else {
                        inputPassword.type = 'password';
                        eyeBtn.classList.remove('fa-eye');
                        eyeBtn.classList.add('fa-eye-slash');
                    }
                });
            });
        </script>
    <?php endif; ?>
    <?php if ($web_data['profile_type'] == URL_PROFILE_PHOTO) : ?>
        <script>
            document.querySelector('.input-user-image').onchange = function() {
                var user_image = this.files[0];
                var reader = new FileReader();
                reader.readAsDataURL(user_image);
                reader.onload = function(e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function(e2) {
                        var created_image = document.createElement('canvas');
                        created_image.width = 200;
                        created_image.height = 200;
                        var context = created_image.getContext('2d');
                        context.drawImage(e2.target, 0, 0, created_image.width, created_image.height);
                        document.querySelector('.profile-image').src = context.canvas.toDataURL('image/png', 1);
                    }
                }
            };
        </script>
    <?php endif; ?>
    <?php if ($web_data['profile_type'] == URL_PROFILE_ORDERS && !empty($i)) : ?>
        <script>
            var j = <?php echo $i; ?>;
            for (let index = 1; index < j + 1; index++) {
                const orderDetailsPopup = document.querySelector('.order-details-popup-wrapper-' + index);
                document.querySelector('.extend-order-details-' + index).addEventListener('click', (e) => {
                    e.preventDefault();
                    if (orderDetailsPopup.classList.contains('disable')) {
                        orderDetailsPopup.classList.remove('disable');
                    }
                    if (!bodyElement.classList.contains('noscroll')) {
                        bodyElement.classList.add('noscroll');
                    }
                });
                document.getElementById('order-details-popup-wrapper-exit-' + index).addEventListener('click', (e) => {
                    e.preventDefault();
                    if (!orderDetailsPopup.classList.contains('disable')) {
                        orderDetailsPopup.classList.add('disable');
                    }
                    if (bodyElement.classList.contains('noscroll')) {
                        bodyElement.classList.remove('noscroll');
                    }
                });
            }
        </script>
    <?php endif; ?>
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
            $(window).scroll(function() {
                if ($(window).scrollTop() > 0) {
                    notificationClient.addClass('sticky');
                } else {
                    notificationClient.removeClass('sticky');
                }
            });
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
            <?php if ($web_data['profile_type'] == URL_PROFILE_INFORMATIONS) : ?>
                const firstName = $('#first-name');
                const lastName = $('#last-name');
                $('#btn-user-update').click(function(e) {
                    e.preventDefault();
                    if (firstName.val() == '') {
                        firstName.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_USER_NAME; ?></span></div>');
                    } else if (lastName.val() == '') {
                        lastName.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_USER_LAST_NAME; ?></span></div>');
                    } else {
                        if ($('.loader-wrapper').hasClass('hidden')) {
                            $('.loader-wrapper').removeClass('hidden');
                        }
                        if (!$('body').hasClass('noscroll')) {
                            $('body').addClass('noscroll');
                        }
                        $('#form-user-update').submit();
                    }
                });
                $('.mfa-checkbox').change(function() {
                    if ($('.loader-wrapper').hasClass('hidden')) {
                        $('.loader-wrapper').removeClass('hidden');
                    }
                    if (!$('body').hasClass('noscroll')) {
                        $('body').addClass('noscroll');
                    }
                    $('#form-two-fa').submit();
                });
            <?php endif; ?>
            <?php if ($web_data['profile_type'] == URL_PROFILE_IDENTITY_NUMBER) : ?>
                const userIdentity = $('#user-identity');
                $('#btn-identity-update').click(function(e) {
                    e.preventDefault();
                    if (userIdentity.val() == '') {
                        userIdentity.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_IDENTITY_NUMBER; ?></span></div>');
                    } else if ($.trim(userIdentity.val()).indexOf(' ') >= 0) {
                        userIdentity.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_IDENTITY_NUMBER; ?></span></div>');
                    } else if ($.trim(userIdentity.val()).length != <?php echo IDENTITY_NUMBER_LIMIT; ?>) {
                        userIdentity.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_IDENTITY_NUMBER; ?></span></div>');
                    } else {
                        if ($('.loader-wrapper').hasClass('hidden')) {
                            $('.loader-wrapper').removeClass('hidden');
                        }
                        if (!$('body').hasClass('noscroll')) {
                            $('body').addClass('noscroll');
                        }
                        $('#form-update-identity').submit();
                    }
                });
            <?php endif; ?>
            <?php if ($web_data['profile_type'] == URL_PROFILE_ADDRESS) : ?>
                $.each($('.item-city .details-select-city .option'), function(key, option) {
                    $(this).click(function() {
                        if ($.isNumeric($(this).data('option')) && $(this).data('option') > 0 && $(this).data('option') < 82) {
                            requestUsable = false;
                            request = $.ajax({
                                url: '<?php echo URL . URL_GET_COUNTY; ?>',
                                type: 'POST',
                                data: 'city=' + $(this).data('option')
                            });
                            request.done(function(response) {
                                requestUsable = true;
                                response = jQuery.parseJSON(response);
                                if (response.hasOwnProperty('shutdown')) {
                                    window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                                } else if (response.hasOwnProperty('exception')) {
                                    window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                                } else if (response.hasOwnProperty('stop')) {

                                } else if (response.hasOwnProperty('address_county')) {
                                    if ($('.county-select').children().length > 0) {
                                        $('.county-select').children().remove();
                                        let uu1 = $("<option></option>").attr('id', 'details-county-option-0');
                                        $('.county-select').append(uu1);
                                        $('.option-county:selected').selected = false;
                                    }
                                    if ($('.details-select-county').children().length > 0) {
                                        $('.details-select-county').children().remove();
                                        $('#select-text-county').text('İlçe Seçin');
                                    }
                                    $.each(response['address_county'], function(key, address_count) {
                                        let u1 = $("<option></option>").addClass('option-county').attr('id', 'details-county-option-' + address_count['id']).attr('value', address_count['id']);
                                        $('.county-select').append(u1);
                                        let u2 = $("<span></span>").addClass('option').attr('data-option', address_count['id']).attr('data-url', address_count['county_name']).text(address_count['county_name']);
                                        $('.details-select-county').append(u2);
                                        if ($('#details-county').hasClass('disable')) {
                                            $('#details-county').removeClass('disable');
                                        }
                                    });
                                    $.each($('.item-county .details-select-county .option'), function(key, optionValue) {
                                        $(this).click(function(e) {
                                            e.preventDefault();
                                            console.log($('#details-county-option-' + $(this).data('option')));
                                            $('#details-county-option-' + $(this).data('option')).attr('selected', true);
                                            $('#select-text-county').text($(this).data('url'));
                                        });
                                    });
                                }
                            });
                        }
                    });
                });
                $.each($('.item-city .update-details-select-city .option'), function(key, option) {
                    $(this).click(function() {
                        if ($.isNumeric($(this).data('option')) && $(this).data('option') > 0 && $(this).data('option') < 82) {
                            requestUsable = false;
                            request = $.ajax({
                                url: '<?php echo URL . URL_GET_COUNTY; ?>',
                                type: 'POST',
                                data: 'city=' + $(this).data('option')
                            });
                            request.done(function(response) {
                                requestUsable = true;
                                response = jQuery.parseJSON(response);
                                if (response.hasOwnProperty('shutdown')) {
                                    window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                                } else if (response.hasOwnProperty('exception')) {
                                    window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                                } else if (response.hasOwnProperty('stop')) {

                                } else if (response.hasOwnProperty('address_county')) {
                                    if ($('.update-county-select').children().length > 0) {
                                        $('.update-county-select').children().remove();
                                        let uu1 = $("<option></option>").attr('id', 'details-county-option-0');
                                        $('.update-county-select').append(uu1);
                                        $('.option-county:selected').selected = false;
                                    }
                                    if ($('.update-details-select-county').children().length > 0) {
                                        $('.update-details-select-county').children().remove();
                                        $('#update-county').text('İlçe Seçin');
                                    }
                                    $.each(response['address_county'], function(key, address_count) {
                                        let u1 = $("<option></option>").addClass('option-county').attr('id', 'update-details-county-option-' + address_count['id']).attr('value', address_count['id']);
                                        $('.update-county-select').append(u1);
                                        let u2 = $("<span></span>").addClass('option').attr('data-option', address_count['id']).attr('data-url', address_count['county_name']).text(address_count['county_name']);
                                        $('.update-details-select-county').append(u2);
                                    });
                                    $.each($('.item-county .update-details-select-county .option'), function(key, optionValue) {
                                        $(this).click(function(e) {
                                            e.preventDefault();
                                            $('#update-details-county-option-' + $(this).data('option')).attr('selected', true);
                                            $('#update-county').text($(this).data('url'));
                                        });
                                    });
                                }
                            });
                        }
                    });
                });
                $.each($('.btn-update-address'), function(key1, value1) {
                    $(this).click(function(e) {
                        e.preventDefault();
                        if ($('.update-county-select').children().length > 0) {
                            $('.update-county-select').children().remove();
                            let uu1 = $("<option></option>").attr('id', 'selected-update-county');
                            $('.update-county-select').append(uu1);
                            $('.option-county:selected').selected = false;
                        }
                        if ($('.update-details-select-county').children().length > 0) {
                            $('.update-details-select-county').children().remove();
                        }
                        $.each($(this).data('update-selected-counties'), function(key, value) {
                            let u1 = $("<option></option>").addClass('option-county').attr('id', 'update-details-county-option-' + key).attr('value', key);
                            $('.update-county-select').append(u1);
                            let u2 = $("<span></span>").addClass('option').attr('data-option', key).attr('data-url', value).text(value);
                            $('.update-details-select-county').append(u2);
                        });
                        $.each($('.item-county .update-details-select-county .option'), function(key, optionValue) {
                            $(this).click(function(e) {
                                e.preventDefault();
                                $('#update-details-county-option-' + $(this).data('option')).attr('selected', true);
                                $('#update-county').text($(this).data('url'));
                            });
                        });
                    });
                });
            <?php endif; ?>
            <?php if ($web_data['profile_type'] == URL_PROFILE_PASSWORD) : ?>
                const inputOldPassword = $('#input-oldpassword');
                const oldPasswordMessage = $('#oldpassword-message');
                const inputPassword = $('#input-password');
                const passwordMessage = $('#password-message');
                const inputRepassword = $('#input-repassword');
                const repasswordMessage = $('#repassword-message');
                $('#btn-password-update').click(function(e) {
                    e.preventDefault();
                    if (inputOldPassword.val() == '') {
                        inputOldPassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?></span></div>');
                    } else if (inputPassword.val() == '') {
                        inputPassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?></span></div>');
                    } else if ($.trim(inputPassword.val()).indexOf(' ') >= 0) {
                        inputPassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD; ?></span></div>');
                    } else if ($.trim(inputPassword.val()).length < <?php echo PASSWORD_MIN_LIMIT; ?>) {
                        inputPassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD; ?></span></div>');
                    } else if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/.test(inputPassword.val())) {
                        inputPassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_PATTERN_PASSWORD; ?></span></div>');
                    } else if (inputRepassword.val() == '') {
                        inputRepassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD; ?></span></div>');
                    } else if (inputPassword.val() != inputRepassword.val()) {
                        inputRepassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS; ?></span></div>');
                    } else {
                        if ($('.loader-wrapper').hasClass('hidden')) {
                            $('.loader-wrapper').removeClass('hidden');
                        }
                        if (!$('body').hasClass('noscroll')) {
                            $('body').addClass('noscroll');
                        }
                        $('#form-password-update').submit();
                    }
                });
                inputOldPassword.keyup(function(e) {
                    if (inputOldPassword.val() == '') {
                        oldPasswordMessage.text('<?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?>');
                    } else {
                        oldPasswordMessage.text('');
                    }
                });
                inputPassword.keyup(function(e) {
                    if (inputPassword.val() == '') {
                        passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?>');
                    } else if ($.trim(inputPassword.val()).indexOf(' ') >= 0) {
                        passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD; ?>');
                    } else if ($.trim(inputPassword.val()).length < <?php echo PASSWORD_MIN_LIMIT; ?>) {
                        passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD; ?>');
                    } else if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/.test(inputPassword.val())) {
                        passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_PATTERN_PASSWORD; ?>');
                    } else if (inputRepassword.val() != '' && inputPassword.val() != inputRepassword.val()) {
                        passwordMessage.text('');
                        repasswordMessage.text('<?php echo TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS; ?>');
                    } else {
                        passwordMessage.text('');
                        repasswordMessage.text('');
                    }
                });
                inputRepassword.keyup(function(e) {
                    if (inputRepassword.val() == '') {
                        repasswordMessage.text('<?php echo TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD; ?>');
                    } else if (inputPassword.val() != inputRepassword.val()) {
                        repasswordMessage.text('<?php echo TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS; ?>');
                    } else {
                        repasswordMessage.text('');
                    }
                });
            <?php endif; ?>
            <?php if ($web_data['profile_type'] == URL_PROFILE_EMAIL) : ?>
                const userEmail = $('#user-email');
                $('#btn-email-update').click(function(e) {
                    e.preventDefault();
                    if (userEmail.val() == '') {
                        userEmail.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_EMAIL; ?></span></div>');
                    } else if ($.trim(userEmail.val()).indexOf(' ') >= 0) {
                        userEmail.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL; ?></span></div>');
                    } else {
                        if ($('.loader-wrapper').hasClass('hidden')) {
                            $('.loader-wrapper').removeClass('hidden');
                        }
                        if (!$('body').hasClass('noscroll')) {
                            $('body').addClass('noscroll');
                        }
                        $('#form-email-update').submit();
                    }
                });
            <?php endif; ?>
            <?php if ($web_data['profile_type'] == URL_PROFILE_PHONE) : ?>
                const userPhone = $('#user-phone');
                $('#btn-phone-update').click(function(e) {
                    e.preventDefault();
                    if (userPhone.val() == '') {
                        userPhone.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_PHONE; ?></span></div>');
                    } else if ($.trim(userPhone.val()).indexOf(' ') >= 0) {
                        userPhone.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_PHONE; ?></span></div>');
                    } else if ($.trim(userPhone.val()).length != <?php echo PHONE_LIMIT; ?>) {
                        userPhone.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_PHONE; ?></span></div>');
                    } else {
                        if ($('.loader-wrapper').hasClass('hidden')) {
                            $('.loader-wrapper').removeClass('hidden');
                        }
                        if (!$('body').hasClass('noscroll')) {
                            $('body').addClass('noscroll');
                        }
                        $('#form-update-phone').submit();
                    }
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>