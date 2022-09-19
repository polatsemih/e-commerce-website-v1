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
                                        <input class="mfa-checkbox" id="mfa-id" type="checkbox" name="mfa"<?php echo $web_data['authenticated_user']['two_fa_enable'] == 0 ? '' : ' checked'; ?>>
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
                        <div id="popup-wrapper" class="create-address-wrapper<?php echo !empty($web_data['address']) ? '' : ' disable'; ?>">
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
                                        <?php if (!empty($web_data['city'])) : ?>
                                            <input class="input" id="address-autofocus" type="text" name="city" value="<?php echo $web_data['city']; ?>">
                                        <?php else : ?>
                                            <input class="input" id="address-autofocus" type="text" name="city">
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">İlçe</span>
                                        <?php if (!empty($web_data['county'])) : ?>
                                            <input class="input" type="text" name="county" value="<?php echo $web_data['county']; ?>">
                                        <?php else : ?>
                                            <input class="input" type="text" name="county">
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">Mahalle</span>
                                        <?php if (!empty($web_data['neighborhood'])) : ?>
                                            <input class="input" type="text" name="neighborhood" value="<?php echo $web_data['neighborhood']; ?>">
                                        <?php else : ?>
                                            <input class="input" type="text" name="neighborhood">
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">Cadde/ Sokak</span>
                                        <?php if (!empty($web_data['street'])) : ?>
                                            <input class="input" type="text" name="street" value="<?php echo $web_data['street']; ?>">
                                        <?php else : ?>
                                            <input class="input" type="text" name="street">
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-row">
                                        <span class="label label-spec">Apartman Numarası</span>
                                        <?php if (!empty($web_data['building_no'])) : ?>
                                            <input class="input" type="text" name="building_no" value="<?php echo $web_data['building_no']; ?>">
                                        <?php else : ?>
                                            <input class="input" type="text" name="building_no">
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">Daire Numarası</span>
                                        <?php if (!empty($web_data['apartment_no'])) : ?>
                                            <input class="input" type="text" name="apartment_no" value="<?php echo $web_data['apartment_no']; ?>">
                                        <?php else : ?>
                                            <input class="input" type="text" name="apartment_no">
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-row">
                                        <span class="label">ZİP Numarası</span>
                                        <?php if (!empty($web_data['zip_no'])) : ?>
                                            <input class="input" type="text" name="zip_no" value="<?php echo $web_data['zip_no']; ?>">
                                        <?php else : ?>
                                            <input class="input" type="text" name="zip_no">
                                        <?php endif; ?>
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
                                        <input id="update-city" class="input" type="text" name="city">
                                    </div>
                                    <div class="form-row">
                                        <span class="label">İlçe</span>
                                        <input id="update-county" class="input" type="text" name="county">
                                    </div>
                                    <div class="form-row">
                                        <span class="label">Mahalle</span>
                                        <input id="update-neighborhood" class="input" type="text" name="neighborhood">
                                    </div>
                                    <div class="form-row">
                                        <span class="label">Cadde/ Sokak</span>
                                        <input id="update-street" class="input" type="text" name="street">
                                    </div>
                                    <div class="form-row">
                                        <span class="label label-spec">Apartman Numarası</span>
                                        <input id="update-building_no" class="input" type="text" name="building_no">
                                    </div>
                                    <div class="form-row">
                                        <span class="label">Daire Numarası</span>
                                        <input id="update-apartment_no" class="input" type="text" name="apartment_no">
                                    </div>
                                    <div class="form-row">
                                        <span class="label">ZİP Numarası</span>
                                        <input id="update-zip_no" class="input" type="text" name="zip_no">
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
                                        <span class="address-text"><?php echo $address['address_neighborhood'] . ', ' . $address['address_street'] . ', Apartman No: ' . $address['address_building_no'] . ' Daire No: ' . $address['address_apartment_no'] . ' ' . $address['address_county'] . '/' . $address['address_city'] . '/' . $address['address_country'] . ' ZIP Kodu:' . $address['address_zip_no']; ?></span>
                                        <div class="address-btn-row">
                                            <span class="btn-update-address btn-warning mr" data-id="<?php echo $address['id']; ?>" data-neighborhood="<?php echo $address['address_neighborhood']; ?>" data-street="<?php echo $address['address_street']; ?>" data-building_no="<?php echo $address['address_building_no']; ?>" data-apartment_no="<?php echo $address['address_apartment_no']; ?>" data-county="<?php echo $address['address_county']; ?>" data-city="<?php echo $address['address_city']; ?>" data-zip_no="<?php echo $address['address_zip_no']; ?>">Düzenle</span>
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
                    document.getElementById('address-autofocus').focus();
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
                    document.getElementById('update-city').value = element.dataset.city;
                    document.getElementById('update-county').value = element.dataset.county;
                    document.getElementById('update-neighborhood').value = element.dataset.neighborhood;
                    document.getElementById('update-street').value = element.dataset.street;
                    document.getElementById('update-building_no').value = element.dataset.building_no;
                    document.getElementById('update-apartment_no').value = element.dataset.apartment_no;
                    document.getElementById('update-zip_no').value = element.dataset.zip_no;
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
                        document.getElementById('address-autofocus').focus();
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