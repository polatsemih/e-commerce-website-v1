<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Sipariş | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="order-section container">
            <?php if (!empty($_SESSION[SESSION_COMPLETE_PROFILE_NAME])) : ?>
                <div id="popup-wrapper" class="name-wrapper">
                    <div class="popup-container">
                        <h4 class="title">Siparişe Devam Etmeden Önce Bilgilerinizi Tamamlayın</h4>
                        <form class="form-name" id="form-user-update" action="<?php echo URL . URL_PROFILE_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-row">
                                <span class="label">İsim</span>
                                <input class="input" type="text" id="first-name" name="user_first_name" placeholder="İsminizi Girin" autofocus>
                            </div>
                            <div class="form-row">
                                <span class="label">Soy İsim</span>
                                <input class="input" type="text" id="last-name" name="user_last_name" placeholder="Soy İsminizi Girin">
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-success" id="btn-user-update" type="submit" value="Devam Et" title="Siparişe Devam Et">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php elseif (!empty($_SESSION[SESSION_COMPLETE_PROFILE_IDENTITY])) : ?>
                <div id="popup-wrapper" class="name-wrapper">
                    <div class="popup-container">
                        <h4 class="title">T.C. Kimlik Numaranız</h4>
                        <form class="form-name" id="form-update-identity" action="<?php echo URL . URL_IDENTITY_NUMBER_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-row">
                                <span class="label">Kimlik Numarası</span>
                                <input class="input" id="user-identity" type="text" name="user_identity_number" maxlength="11" placeholder="Kimlik Numaranızı Girin" autofocus>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-success" id="btn-identity-update" type="submit" value="Devam Et" title="Siparişe Devam Et">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php elseif (!empty($_SESSION[SESSION_COMPLETE_PROFILE_PHONE])) : ?>
                <div id="popup-wrapper" class="name-wrapper">
                    <div class="popup-container">
                        <h4 class="title">Telefon Numaranız</h4>
                        <form class="form-name" id="form-update-phone" action="<?php echo URL . URL_PHONE_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-row">
                                <span class="label">Telefon Numarası</span>
                                <span class="label-phone">(+90)</span>
                                <input class="input input-phone" id="user-phone" type="tel" name="user_phone_number" maxlength="10" placeholder="Telefon Numaranızı Girin" autofocus>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-success" id="btn-phone-update" type="submit" value="Devam Et" title="Siparişe Devam Et">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php elseif (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) : ?>
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
                                    <input class="btn-success" type="submit" name="submit_address_create" value="Adresi Ekle ve Siparişe Devam Et" title="Yeni Adresi Ekle Ve Bu Adres ile Siparişe Devam Et">
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
                                    <input class="btn-warning" type="submit" name="submit_address_update" value="Adresi Düzenle ve Siparişe Devam Et" title="Adresi Düzenle ve Bu Adres ile Siparişe Devam Et">
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
                <div id="popup-wrapper" class="name-wrapper">
                    <div class="popup-container">
                        <div class="row-space">
                            <div class="left">
                                <h4 class="title">Teslimatın Yapılacağı Adresi Seçin</h4>
                            </div>
                            <div class="right">
                                <button id="btn-create-address" class="btn-success btn-add-address" title="Yeni Adres Ekle">Adres Ekle</button>
                            </div>
                        </div>
                        <?php if (!empty($web_data['user_address'])) : ?>
                            <form action="<?php echo URL . URL_ORDER_ADDRESS; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                <?php $i = 0; ?>
                                <?php foreach ($web_data['user_address'] as $address) : ?>
                                    <?php $i++; ?>
                                    <label for="selected-address-<?php echo $i; ?>">
                                        <input class="selected-address-checkbox" type="radio" name="selected_address" value="<?php echo $address['id']; ?>" id="selected-address-<?php echo $i; ?>">
                                        <div class="address-box">
                                            <span class="selected-address-box"></span>
                                            <span class="address-text"><?php echo $address['full_address'] . ' ' . $address['address_county'] . '/' . $address['address_city'] . '/' . $address['address_country']; ?></span>
                                            <div class="address-btn-row">
                                                <?php
                                                $js_data_county = '{';
                                                foreach ($address['update_county'] as $key_update_county => $update_county) {
                                                    $js_data_county .= '"' . $key_update_county . '": "' . $update_county . '", ';
                                                }
                                                $js_data_county = rtrim($js_data_county, ', ') . '}';
                                                ?>
                                                <span class="btn-update-address btn-warning mr" data-id="<?php echo $address['id']; ?>" data-full_address="<?php echo $address['full_address']; ?>" data-address_quick_name="<?php echo $address['address_quick_name']; ?>" data-county="<?php echo $address['address_county']; ?>" data-city="<?php echo $address['address_city']; ?>" data-city_id="<?php echo $address['city_id']; ?>" data-county_id="<?php echo $address['county_id']; ?>" data-update-selected-counties='<?php echo $js_data_county; ?>'>Düzenle</span>
                                                <span class="btn-danger btn-delete-address" data-id="<?php echo $address['id']; ?>">Sil</span>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                                <div class="row-space">
                                    <div class="right">
                                        <input class="btn-success btn-address-continue disable" id="btn-user-update" type="submit" value="Siparişe Devam Et" title="Bir Adres Seçin Veya Adres Ekleyin" disabled>
                                    </div>
                                </div>
                            </form>
                        <?php else : ?>
                            <span class="address-danger-text">Kayıtlı Adresiniz Yok</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif (!empty($web_data['ready_to_buy'])) : ?>
                <form id="form-order-complete" action="<?php echo URL . URL_ORDER_INITIALIZE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <div class="row-order">
                        <div class="col-1">
                            <div class="row-space">
                                <a href="<?php echo URL . URL_CART; ?>" class="go-back-cart"><i class="fas fa-chevron-circle-left"></i>Sepete Geri Dön</a>
                                <h1 class="title">Sipariş Onay</h1>
                            </div>
                            <div class="credit-wrapper">
                                <?php if (!empty($web_data['form_token'])) : ?>
                                    <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                <?php endif; ?>
                                <div class="form-row" title="Kartın Üzerindeki İsim">
                                    <span class="label">Kart Üzerindeki İsim</span>
                                    <input class="input" type="text" id="cart-name" name="cart_name" autofocus>
                                </div>
                                <div class="form-row" title="Kartın Üzerindeki Numara">
                                    <span class="label">Kart Numarası</span>
                                    <input class="input" type="text" id="cart-number" name="cart_number" maxlength="19">
                                </div>
                                <div class="row-credit">
                                    <div class="credit-col" title="Kartın Son Kullanma Tarihi (AY)">
                                        <span class="label">Son Kullanma Tarihi (AY)</span>
                                        <input class="input" type="text" id="cart-expiry-month" name="cart_expiry_month" placeholder="aa" maxlength="2">
                                    </div>
                                    <div class="credit-col" title="Kartın Son Kullanma Tarihi (YIL)">
                                        <span class="label">Son Kullanma Tarihi (YIL)</span>
                                        <input class="input" type="text" id="cart-expiry-year" name="cart_expiry_year" placeholder="yy" maxlength="2">
                                    </div>
                                </div>
                                <div class="form-row" title="Kart Arkasında Yer Alan CVC veya CVV Numarası">
                                    <span class="label">Güvenlik Kodu (CVC)</span>
                                    <input class="input" type="text" id="cart-cvc" name="cart_cvc" maxlength="4">
                                </div>
                                <div class="row-space">
                                    <div class="left">
                                        <div class="row-space">
                                            <span class="checkmark selected"></span>
                                            <span class="checkmark-text">3D Güvenli Ödeme Etkin</span>
                                        </div>
                                    </div>
                                    <div class="right checkout-logo">
                                        <svg viewBox="0 0 210 31" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                                            <title>iyzico_ile_ode_colored_horizontal</title>
                                            <defs>
                                                <linearGradient x1="90.2554899%" y1="50%" x2="0%" y2="50%" id="linearGradient-1">
                                                    <stop stop-color="#1E64FF" offset="0%"></stop>
                                                    <stop stop-color="#1E64FF" stop-opacity="0" offset="100%"></stop>
                                                </linearGradient>
                                            </defs>
                                            <g id="05---Landings" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g id="004.0.0-Brand-Kit" transform="translate(-1015.000000, -1867.000000)">
                                                    <g id="Group-2-Copy" transform="translate(135.000000, 1674.000000)">
                                                        <g id="Group-4-Copy-3" transform="translate(800.000000, 83.000000)">
                                                            <g id="Group" transform="translate(80.000000, 86.000000)">
                                                                <g id="iyzico-ile-Ode_OneLine_TR" transform="translate(0.000000, 24.452381)">
                                                                    <g id="Group-3" transform="translate(-0.000000, 3.761905)" fill="url(#linearGradient-1)">
                                                                        <path d="M2.8125,0 C1.25925,0 0,1.26299683 0,2.82142857 L0,19.75 C0,21.3084317 1.25925,22.5714286 2.8125,22.5714286 L24.0455,22.5714286 C25.09425,22.5714286 26.05575,21.986327 26.54,21.052873 L30.9325,12.5888381 C31.1405,12.1875683 31.24625,11.7489302 31.25,11.3097905 L31.25,11.2616381 C31.24625,10.8224984 31.1405,10.3838603 30.9325,9.98284127 L26.54,1.51855556 C26.05575,0.585352381 25.09425,0 24.0455,0 L2.8125,0 Z" id="Fill-1"></path>
                                                                    </g>
                                                                    <g id="Group-21" transform="translate(39.814286, 0.000000)" fill="#1E64FF">
                                                                        <path d="M20.3121807,8.01261426 C20.8578941,6.95906777 22.1521777,6.54811119 23.2041402,7.09665045 C24.2564599,7.64626456 24.6648521,8.94595505 24.116103,10.0014721 L24.116103,10.0014721 L14.334155,28.9375416 C13.9659413,29.6455191 13.2602282,30.0614917 12.5195152,30.0926628 C12.1554086,30.1105772 11.7832664,30.0355158 11.4379098,29.8522514 C10.3857687,29.3044287 9.97773368,28.0047382 10.5248756,26.949042 L10.5248756,26.949042 L13.2909424,21.5890503 L7.30005957,10.0014721 C6.75291762,8.94595505 7.16380979,7.64626456 8.2147009,7.09665045 C9.26541343,6.54811119 10.562197,6.95906777 11.1070175,8.01261426 L11.1070175,8.01261426 L15.7093313,16.9135683 Z M59.6828651,6.56683175 C62.0035755,6.56683175 64.185179,7.47330093 65.8267835,9.12035184 C66.6614249,9.96143342 66.6614249,11.3241826 65.8267835,12.1654434 C64.9873206,13.0040169 63.6285728,13.0040169 62.7914314,12.1654434 C61.959647,11.3306318 60.8589346,10.8734561 59.6828651,10.8734561 C58.5100099,10.8734561 57.4075118,11.3306318 56.5780489,12.1654434 C55.7476931,12.9948806 55.2914439,14.1028869 55.2914439,15.2809385 C55.2914439,16.4579153 55.7476931,17.5646676 56.5780489,18.3978668 C57.4075118,19.2276623 58.5100099,19.6914664 59.6828651,19.6914664 C60.8589346,19.6914664 61.959647,19.2276623 62.7914314,18.3978668 C63.6285728,17.5558895 64.9873206,17.5558895 65.8267835,18.3978668 C66.6614249,19.238411 66.6614249,20.6020559 65.8267835,21.4391963 C64.185179,23.0866055 62.0035755,23.9945078 59.6828651,23.9945078 C57.3650118,23.9945078 55.1844798,23.0866055 53.5441254,21.4391963 C51.9035924,19.7971614 50.9994867,17.6073039 50.9994867,15.2809385 C50.9994867,12.9520651 51.9035924,10.7674028 53.5441254,9.12035184 C55.1844798,7.47330093 57.3650118,6.56683175 59.6828651,6.56683175 Z M77.0293898,6.56677801 C81.8183104,6.56677801 85.7141967,10.4755233 85.7141967,15.2808848 C85.7141967,20.0853505 81.8183104,23.9946332 77.0293898,23.9946332 C72.2424335,23.9946332 68.3470828,20.0853505 68.3470828,15.2808848 C68.3470828,10.4755233 72.2424335,6.56677801 77.0293898,6.56677801 Z M2.36954993,6.85444761 C3.55436938,6.85444761 4.5149035,7.81824291 4.5149035,9.0038186 L4.5149035,9.0038186 L4.5149035,21.5929915 C4.5149035,22.7826875 3.55436938,23.7439748 2.36954993,23.7439748 C1.18473047,23.7439748 0.223839218,22.7826875 0.223839218,21.5929915 L0.223839218,21.5929915 L0.223839218,9.0038186 C0.223839218,7.81824291 1.18473047,6.85444761 2.36954993,6.85444761 Z M38.4392951,6.85393075 C38.4637799,6.85397691 38.4845421,6.85402542 38.5013999,6.85407635 L38.5542396,6.85441179 C39.0669173,6.83685566 39.584595,7.00095166 40.0072729,7.35816501 C40.9137,8.11827345 41.0322712,9.47815636 40.2722725,10.387671 L40.2722725,10.387671 L32.6990708,19.4394644 L38.6254895,19.4394644 C39.8112018,19.4394644 40.7731645,20.4057677 40.7731645,21.5929557 C40.7731645,22.7828308 39.8112018,23.743939 38.6254895,23.743939 L38.6254895,23.743939 L28.0990785,23.743939 C27.6122936,23.743939 27.1215801,23.5814553 26.7197951,23.2403649 C25.813368,22.4781067 25.6937253,21.1218067 26.4560455,20.2090675 L26.4560455,20.2090675 L34.0279972,11.1603195 L29.0778268,11.1603195 C27.8915788,11.1603195 26.9324733,10.1940162 26.9324733,9.00396191 C26.9324733,7.81820708 27.8915788,6.85441179 29.0778268,6.85441179 L29.0778268,6.85441179 L30.3890569,6.85405101 C30.4611906,6.85403248 30.535603,6.85401357 30.6121121,6.85399436 Z M46.0431021,6.85444761 C47.230243,6.85444761 48.1884557,7.81824291 48.1884557,9.0038186 L48.1884557,9.0038186 L48.1884557,21.5929915 C48.1884557,22.7826875 47.230243,23.7439748 46.0431021,23.7439748 C44.8593541,23.7439748 43.9002486,22.7826875 43.9002486,21.5929915 L43.9002486,21.5929915 L43.9002486,9.0038186 C43.9002486,7.81824291 44.8593541,6.85444761 46.0431021,6.85444761 Z M77.0293898,10.8734023 C74.6077867,10.8734023 72.63904,12.8500784 72.63904,15.2808848 C72.63904,17.7109746 74.6077867,19.6914127 77.0293898,19.6914127 C79.4526,19.6914127 81.4242039,17.7109746 81.4242039,15.2808848 C81.4242039,12.8500784 79.4526,10.8734023 77.0293898,10.8734023 Z M2.36954993,-2.34732868e-15 C3.67776203,-2.34732868e-15 4.74008169,1.06519085 4.74008169,2.37921287 C4.74008169,3.69054773 3.67776203,4.75770917 2.36954993,4.75770917 C1.06133782,4.75770917 -8.89798461e-05,3.69054773 -8.89798461e-05,2.37921287 C-8.89798461e-05,1.06519085 1.06133782,-2.34732868e-15 2.36954993,-2.34732868e-15 Z M46.0431021,-2.34732868e-15 C47.3538142,-2.34732868e-15 48.4150625,1.06519085 48.4150625,2.37921287 C48.4150625,3.69054773 47.3538142,4.75770917 46.0431021,4.75770917 C44.7334615,4.75770917 43.6722132,3.69054773 43.6722132,2.37921287 C43.6722132,1.06519085 44.7334615,-2.34732868e-15 46.0431021,-2.34732868e-15 Z" id="Combined-Shape"></path>
                                                                    </g>
                                                                    <path d="M171.256395,5.40878903 C175.631543,5.40878903 179.956126,9.08690718 179.956126,14.4030587 C179.956126,19.7192102 175.631543,23.3954641 171.256395,23.3954641 C166.881864,23.3954641 162.557281,19.7192102 162.557281,14.4030587 C162.557281,9.08690718 166.881864,5.40878903 171.256395,5.40878903 Z M193.107162,5.23261966 C193.831728,5.23261966 194.457014,5.83787174 194.457014,6.56802698 L194.457014,22.0355112 C194.457014,22.7669092 193.831728,23.3709185 193.107162,23.3709185 C192.381979,23.3709185 191.781976,22.7669092 191.781976,22.0355112 L191.781976,20.6752475 C191.607463,21.808697 190.056583,23.3951534 187.681855,23.3951534 C184.907536,23.3951534 181.583163,21.2289227 181.583163,16.6708898 C181.583163,12.1855617 184.932202,9.96899684 187.681855,9.96899684 C189.531811,9.96899684 191.132024,10.9272091 191.781976,12.3868981 L191.781976,11.3795946 C191.781976,11.3044042 191.781976,11.2031145 191.807259,11.1279241 L191.807259,6.56802698 C191.807259,5.83787174 192.407878,5.23261966 193.107162,5.23261966 Z M135.174825,10.0454301 C135.724262,10.0454301 136.199084,10.5239148 136.199084,11.1036891 L136.199084,22.2871817 C136.199084,22.8930552 135.724262,23.3709185 135.174825,23.3709185 C134.600105,23.3709185 134.124666,22.8930552 134.124666,22.2871817 L134.124666,11.1036891 C134.124666,10.5239148 134.600105,10.0454301 135.174825,10.0454301 Z M140.024302,5.23274394 C140.574973,5.23274394 141.049795,5.71247146 141.049795,6.26614662 L141.049795,22.287306 C141.049795,22.8416026 140.574973,23.3449436 140.024302,23.3449436 C139.448966,23.3449436 138.97476,22.8416026 138.97476,22.287306 L138.97476,6.26614662 C138.97476,5.71247146 139.448966,5.23274394 140.024302,5.23274394 Z M149.70007,9.96912113 C152.549621,9.96912113 155.973274,12.2366415 155.973274,16.3180539 C155.973274,17.1227782 155.498452,17.5018375 154.649322,17.5018375 L145.450103,17.4515034 C145.674564,20.2726989 147.824831,21.6074849 149.974481,21.6074849 C151.123921,21.6074849 152.048899,21.2800025 152.873363,20.6492726 C153.248904,20.3982235 153.473983,20.2726989 153.748393,20.2726989 C154.224448,20.2726989 154.523525,20.6001813 154.523525,21.0531883 C154.523525,21.3303366 154.398961,21.6074849 153.973471,21.9853014 C152.973877,22.8664589 151.500078,23.3449436 149.923915,23.3449436 C146.724106,23.3449436 143.300453,21.229047 143.300453,16.695249 C143.300453,12.1614511 146.849903,9.96912113 149.70007,9.96912113 Z M203.182706,9.96899684 C206.032256,9.96899684 209.457143,12.2365172 209.457143,16.2414964 C209.457143,17.274899 208.881806,17.6781933 207.806981,17.6781933 L199.432843,17.6520941 C199.707253,19.994805 201.532544,21.1543536 203.532347,21.1543536 C204.55784,21.1543536 205.257741,20.9026831 206.032256,20.4241984 C206.457129,20.1973842 206.732156,20.0209041 207.081798,20.0209041 C207.657135,20.0209041 208.007393,20.4241984 208.007393,20.9778736 C208.007393,21.3059773 207.882213,21.6583161 207.332159,22.1107016 C206.382514,22.8930552 205.006763,23.3448193 203.431833,23.3448193 C200.183309,23.3448193 196.708473,21.2550218 196.708473,16.6951247 C196.708473,12.1613268 200.333155,9.96899684 203.182706,9.96899684 Z M188.107345,12.2607522 C186.28267,12.2607522 184.232301,13.6980705 184.232301,16.6708898 C184.232301,19.667944 186.28267,21.1033981 188.107345,21.1033981 C189.807455,21.1033981 191.907156,19.7934685 191.907156,16.6708898 C191.907156,13.5215904 189.807455,12.2607522 188.107345,12.2607522 Z M171.256395,7.97893545 C168.206433,7.97893545 165.382165,10.5745596 165.382165,14.4030587 C165.382165,18.2315578 168.206433,20.8010828 171.256395,20.8010828 C174.306357,20.8010828 177.131242,18.2315578 177.131242,14.4030587 C177.131242,10.5745596 174.331024,7.97893545 171.256395,7.97893545 Z M149.70007,11.8091123 C147.849497,11.8091123 145.674564,13.067465 145.450103,15.8886605 L153.948805,15.8886605 C153.724343,13.067465 151.574077,11.8091123 149.70007,11.8091123 Z M203.182706,12.2868513 C201.532544,12.2868513 199.657921,13.3451104 199.432843,15.7381553 L206.882003,15.7381553 C206.657541,13.3451104 204.85815,12.2868513 203.182706,12.2868513 Z M135.174825,4.98094913 C135.724262,4.98094913 136.224367,5.45943384 136.224367,6.03920816 C136.224367,6.61836108 135.724262,7.0974672 135.174825,7.0974672 C134.600105,7.0974672 134.1,6.61836108 134.1,6.03920816 C134.1,5.45943384 134.600105,4.98094913 135.174825,4.98094913 Z M174.331024,0.824035565 C175.130822,0.824035565 175.780773,1.47900034 175.780773,2.26135391 C175.780773,3.04184325 175.130822,3.69680803 174.331024,3.69680803 C173.531225,3.69680803 172.881274,3.04184325 172.881274,2.26135391 C172.881274,1.47900034 173.531225,0.824035565 174.331024,0.824035565 Z M168.182383,0.824035565 C168.981565,0.824035565 169.6309,1.47900034 169.6309,2.26135391 C169.6309,3.04184325 168.981565,3.69680803 168.182383,3.69680803 C167.381969,3.69680803 166.732017,3.04184325 166.732017,2.26135391 C166.732017,1.47900034 167.381969,0.824035565 168.182383,0.824035565 Z" id="Combined-Shape" fill="#495057"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div id="installment-wrapper"></div>
                        </div>
                        <div class="col-2">
                            <?php if (!empty($web_data['cart_data']) && !empty($web_data['cart_data_price']) && !empty($web_data['cart_data_total_price'])) : ?>
                                <div class="vertical">
                                    <div class="selected-item">
                                        <span class="text">Seçilen Ürün Adedi: <?php echo count($web_data['cart_data']); ?></span>
                                    </div>
                                    <div class="price-infos">
                                        <span class="text">Ücret</span>
                                        <span class="text old-price"><?php echo $web_data['cart_data_price']; ?> ₺</span>
                                    </div>
                                    <div class="price-infos">
                                        <span class="text">İndirimli Ücret</span>
                                        <span class="text"><?php echo $web_data['cart_data_total_price']; ?> ₺</span>
                                    </div>
                                    <div class="price-infos">
                                        <span class="text">Kargo Ücreti</span>
                                        <span class="text">0 ₺</span>
                                    </div>
                                    <div class="price-infos">
                                        <span class="text">Toplam Ücret</span>
                                        <span class="text"><?php echo $web_data['cart_data_total_price']; ?> ₺</span>
                                    </div>
                                </div>
                                <label for="accept-order-terms">
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" class="checkbox" id="accept-order-terms" name="accept_order_terms">
                                        <span class="checkmark"></span>
                                        <span class="checkmark-text"><button class="btn-checkmark" id="btn-order-term">KVKK</button> yı okudum ve kabul ediyorum.</span>
                                    </div>
                                </label>
                                <div id="popup-wrapper-order" class="term2 disable">
                                    <div class="popup-container">
                                        <div id="term2-exit" class="popup-exit">
                                            <div class="exit">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>
                                        <h4 class="title">Gizlilik ve Güvenlik Politikası</h4>
                                        <p class="text">Mağazamızda verilen tüm servisler ve ,………… adresinde kayıtlı ……………….Şti. firmamıza aittir ve firmamız tarafından işletilir.</p>
                                        <p class="text">Firmamız, çeşitli amaçlarla kişisel veriler toplayabilir. Aşağıda, toplanan kişisel verilerin nasıl ve ne şekilde toplandığı, bu verilerin nasıl ve ne şekilde korunduğu belirtilmiştir.</p>
                                        <p class="text">Üyelik veya Mağazamız üzerindeki çeşitli form ve anketlerin doldurulması suretiyle üyelerin kendileriyle ilgili bir takım kişisel bilgileri (isim-soy isim, firma bilgileri, telefon, adres veya e-posta adresleri gibi) Mağazamız tarafından işin doğası gereği toplanmaktadır.</p>
                                        <p class="text">Firmamız bazı dönemlerde müşterilerine ve üyelerine kampanya bilgileri, yeni ürünler hakkında bilgiler, promosyon teklifleri gönderebilir. Üyelerimiz bu gibi bilgileri alıp almama konusunda her türlü seçimi üye olurken yapabilir, sonrasında üye girişi yaptıktan sonra hesap bilgileri bölümünden bu seçimi değiştirilebilir ya da kendisine gelen bilgilendirme iletisindeki linkle bildirim yapabilir.</p>
                                        <p class="text">Mağazamız üzerinden veya eposta ile gerçekleştirilen onay sürecinde, üyelerimiz tarafından mağazamıza elektronik ortamdan iletilen kişisel bilgiler, Üyelerimiz ile yaptığımız "Kullanıcı Sözleşmesi" ile belirlenen amaçlar ve kapsam dışında üçüncü kişilere açıklanmayacaktır.</p>
                                        <p class="text">Sistemle ilgili sorunların tanımlanması ve verilen hizmet ile ilgili çıkabilecek sorunların veya uyuşmazlıkların hızla çözülmesi için, Firmamız, üyelerinin IP adresini kaydetmekte ve bunu kullanmaktadır. IP adresleri, kullanıcıları genel bir şekilde tanımlamak ve kapsamlı demografik bilgi toplamak amacıyla da kullanılabilir.</p>
                                        <p class="text">Firmamız, Üyelik Sözleşmesi ile belirlenen amaçlar ve kapsam dışında da, talep edilen bilgileri kendisi veya işbirliği içinde olduğu kişiler tarafından doğrudan pazarlama yapmak amacıyla kullanabilir. Kişisel bilgiler, gerektiğinde kullanıcıyla temas kurmak için de kullanılabilir. Firmamız tarafından talep edilen bilgiler veya kullanıcı tarafından sağlanan bilgiler veya Mağazamız üzerinden yapılan işlemlerle ilgili bilgiler; Firmamız ve işbirliği içinde olduğu kişiler tarafından, "Üyelik Sözleşmesi" ile belirlenen amaçlar ve kapsam dışında da, üyelerimizin kimliği ifşa edilmeden çeşitli istatistiksel değerlendirmeler, veri tabanı oluşturma ve pazar araştırmalarında kullanılabilir.</p>
                                        <p class="text">Firmamız, gizli bilgileri kesinlikle özel ve gizli tutmayı, bunu bir sır saklama yükümü olarak addetmeyi ve gizliliğin sağlanması ve sürdürülmesi, gizli bilginin tamamının veya herhangi bir kısmının kamu alanına girmesini veya yetkisiz kullanımını veya üçüncü bir kişiye ifşasını önlemek için gerekli tüm tedbirleri almayı ve gerekli özeni göstermeyi taahhüt etmektedir.</p>
                                        <p class="text">KREDİ KARTI GÜVENLİĞİ</p>
                                        <p class="text">Firmamız, alışveriş sitelerimizden alışveriş yapan kredi kartı sahiplerinin güvenliğini ilk planda tutmaktadır. Kredi kartı bilgileriniz hiçbir şekilde sistemimizde saklanmamaktadır.</p>
                                        <p class="text">İşlemler sürecine girdiğinizde güvenli bir sitede olduğunuzu anlamak için dikkat etmeniz gereken iki şey vardır. Bunlardan biri tarayıcınızın en alt satırında bulunan bir anahtar ya da kilit simgesidir. Bu güvenli bir internet sayfasında olduğunuzu gösterir ve her türlü bilgileriniz şifrelenerek korunur. Bu bilgiler, ancak satış işlemleri sürecine bağlı olarak ve verdiğiniz talimat istikametinde kullanılır. Alışveriş sırasında kullanılan kredi kartı ile ilgili bilgiler alışveriş sitelerimizden bağımsız olarak 128 bit SSL (Secure Sockets Layer) protokolü ile şifrelenip sorgulanmak üzere ilgili bankaya ulaştırılır. Kartın kullanılabilirliği onaylandığı takdirde alışverişe devam edilir. Kartla ilgili hiçbir bilgi tarafımızdan görüntülenemediğinden ve kaydedilmediğinden, üçüncü şahısların herhangi bir koşulda bu bilgileri ele geçirmesi engellenmiş olur.</p>
                                        <p class="text">Online olarak kredi kartı ile verilen siparişlerin ödeme/fatura/teslimat adresi bilgilerinin güvenilirliği firmamiz tarafından Kredi Kartları Dolandırıcılığı'na karşı denetlenmektedir. Bu yüzden, alışveriş sitelerimizden ilk defa sipariş veren müşterilerin siparişlerinin tedarik ve teslimat aşamasına gelebilmesi için öncelikle finansal ve adres/telefon bilgilerinin doğruluğunun onaylanması gereklidir. Bu bilgilerin kontrolü için gerekirse kredi kartı sahibi müşteri ile veya ilgili banka ile irtibata geçilmektedir. Üye olurken verdiğiniz tüm bilgilere sadece siz ulaşabilir ve siz değiştirebilirsiniz. Üye giriş bilgilerinizi güvenli koruduğunuz takdirde başkalarının sizinle ilgili bilgilere ulaşması ve bunları değiştirmesi mümkün değildir. Bu amaçla, üyelik işlemleri sırasında 128 bit SSL güvenlik alanı içinde hareket edilir. Bu sistem kırılması mümkün olmayan bir uluslararası bir şifreleme standardıdır.</p>
                                        <p class="text">Bilgi hattı veya müşteri hizmetleri servisi bulunan ve açık adres ve telefon bilgilerinin belirtildiği İnternet alışveriş siteleri günümüzde daha fazla tercih edilmektedir. Bu sayede aklınıza takılan bütün konular hakkında detaylı bilgi alabilir, online alışveriş hizmeti sağlayan firmanın güvenirliği konusunda daha sağlıklı bilgi edinebilirsiniz.</p>
                                        <p class="text">Not: İnternet alışveriş sitelerinde firmanın açık adresinin ve telefonun yer almasına dikkat edilmesini tavsiye ediyoruz. Alışveriş yapacaksanız alışverişinizi yapmadan ürünü aldığınız mağazanın bütün telefon / adres bilgilerini not edin. Eğer güvenmiyorsanız alışverişten önce telefon ederek teyit edin. Firmamıza ait tüm online alışveriş sitelerimizde firmamıza dair tüm bilgiler ve firma yeri belirtilmiştir.</p>
                                        <p class="text">MAİL ORDER KREDİ KART BİLGİLERİ GÜVENLİĞİ</p>
                                        <p class="text">Kredi kartı mail-order yöntemi ile bize göndereceğiniz kimlik ve kredi kart bilgileriniz firmamız tarafından gizlilik prensibine göre saklanacaktır. Bu bilgiler olası banka ile oluşubilecek kredi kartından para çekim itirazlarına karşı 60 gün süre ile bekletilip daha sonrasında imha edilmektedir. Sipariş ettiğiniz ürünlerin bedeli karşılığında bize göndereceğiniz tarafınızdan onaylı mail-order formu bedeli dışında herhangi bir bedelin kartınızdan çekilmesi halinde doğal olarak bankaya itiraz edebilir ve bu tutarın ödenmesini engelleyebileceğiniz için bir risk oluşturmamaktadır.</p>
                                        <p class="text">ÜÇÜNCÜ TARAF WEB SİTELERİ VE UYGULAMALAR</p>
                                        <p class="text">Mağazamız, web sitesi dâhilinde başka sitelere link verebilir. Firmamız, bu linkler vasıtasıyla erişilen sitelerin gizlilik uygulamaları ve içeriklerine yönelik herhangi bir sorumluluk taşımamaktadır. Firmamıza ait sitede yayınlanan reklamlar, reklamcılık yapan iş ortaklarımız aracılığı ile kullanıcılarımıza dağıtılır. İş bu sözleşmedeki Gizlilik Politikası Prensipleri, sadece Mağazamızın kullanımına ilişkindir, üçüncü taraf web sitelerini kapsamaz.</p>
                                        <p class="text">İSTİSNAİ HALLER</p>
                                        <p class="text">Aşağıda belirtilen sınırlı hallerde Firmamız, işbu "Gizlilik Politikası" hükümleri dışında kullanıcılara ait bilgileri üçüncü kişilere açıklayabilir. Bu durumlar sınırlı sayıda olmak üzere;</p>
                                        <p class="text">1.Kanun, Kanun Hükmünde Kararname, Yönetmelik v.b. yetkili hukuki otorite tarafından çıkarılan ve yürürlülükte olan hukuk kurallarının getirdiği zorunluluklara uymak;</p>
                                        <p class="text">2.Mağazamızın kullanıcılarla akdettiği "Üyelik Sözleşmesi"'nin ve diğer sözleşmelerin gereklerini yerine getirmek ve bunları uygulamaya koymak amacıyla;</p>
                                        <p class="text">3.Yetkili idari ve adli otorite tarafından usulüne göre yürütülen bir araştırma veya soruşturmanın yürütümü amacıyla kullanıcılarla ilgili bilgi talep edilmesi;</p>
                                        <p class="text">4.Kullanıcıların hakları veya güvenliklerini korumak için bilgi vermenin gerekli olduğu hallerdir.</p>
                                        <p class="text">E-POSTA GÜVENLİĞİ</p>
                                        <p class="text">Mağazamızın Müşteri Hizmetleri’ne, herhangi bir siparişinizle ilgili olarak göndereceğiniz e-postalarda, asla kredi kartı numaranızı veya şifrelerinizi yazmayınız. E-postalarda yer alan bilgiler üçüncü şahıslar tarafından görülebilir. Firmamız e-postalarınızdan aktarılan bilgilerin güvenliğini hiçbir koşulda garanti edemez.</p>
                                        <p class="text">TARAYICI ÇEREZLERİ</p>
                                        <p class="text">Firmamız, mağazamızı ziyaret eden kullanıcılar ve kullanıcıların web sitesini kullanımı hakkındaki bilgileri teknik bir iletişim dosyası (Çerez-Cookie) kullanarak elde edebilir. Bahsi geçen teknik iletişim dosyaları, ana bellekte saklanmak üzere bir internet sitesinin kullanıcının tarayıcısına (browser) gönderdiği küçük metin dosyalarıdır. Teknik iletişim dosyası site hakkında durum ve tercihleri saklayarak İnternet'in kullanımını kolaylaştırır.</p>
                                        <p class="text">Teknik iletişim dosyası, siteyi kaç kişinin ziyaret ettiğini, bir kişinin siteyi hangi amaçla, kaç kez ziyaret ettiğini ve ne kadar sitede kaldıkları hakkında istatistiksel bilgileri elde etmeye ve kullanıcılar için özel tasarlanmış kullanıcı sayfalarından dinamik olarak reklam ve içerik üretilmesine yardımcı olur. Teknik iletişim dosyası, ana bellekte veya e-postanızdan veri veya başkaca herhangi bir kişisel bilgi almak için tasarlanmamıştır. Tarayıcıların pek çoğu başta teknik iletişim dosyasını kabul eder biçimde tasarlanmıştır ancak kullanıcılar dilerse teknik iletişim dosyasının gelmemesi veya teknik iletişim dosyasının gönderildiğinde uyarı verilmesini sağlayacak biçimde ayarları değiştirebilirler.</p>
                                        <p class="text">Firmamız, işbu "Gizlilik Politikası" hükümlerini dilediği zaman sitede yayınlamak veya kullanıcılara elektronik posta göndermek veya sitesinde yayınlamak suretiyle değiştirebilir. Gizlilik Politikası hükümleri değiştiği takdirde, yayınlandığı tarihte yürürlük kazanır.</p>
                                        <p class="text">Gizlilik politikamız ile ilgili her türlü soru ve önerileriniz için ……………….. adresine email gönderebilirsiniz. Firmamız’a ait aşağıdaki iletişim bilgilerinden ulaşabilirsiniz.</p>
                                        <p class="text">Firma Ünvanı: </p>
                                        <p class="text">Adres: </p>
                                        <p class="text">Eposta: </p>
                                        <p class="text">Tel: </p>
                                        <p class="text">Fax: </p>
                                    </div>
                                </div>
                                <input class="btn-submit" id="btn-order-complete" type="submit" value="Siparişi Onayla" title="Siparişi Onayla">
                            <?php else : ?>
                                <div class="order-out-of-service">
                                    <span class="text text-1">Alışveriş Hizmeti Devre Dışı</span>
                                    <span class="text text-2">Teknik bir hatadan dolayı geçici süreliğine alışveriş hizmeti devre dışıdır. Sorundan haberdarız ve sorunun üzerinden çalışıyoruz. Anlayışınız ve sabrınız için teşekkür ederiz.</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <?php if (!empty($_SESSION[SESSION_COMPLETE_PROFILE_NAME]) || !empty($_SESSION[SESSION_COMPLETE_PROFILE_IDENTITY]) || !empty($_SESSION[SESSION_COMPLETE_PROFILE_PHONE]) || !empty($_SESSION[SESSION_COMPLETE_ADDRESS])) : ?>
        <script>
            window.onload = function() {
                if (!loaderWrapper.classList.contains('hidden')) {
                    loaderWrapper.classList.add('hidden');
                }
                setTimeout(() => {
                    if (!notificationWrapper.classList.contains('hidden')) {
                        notificationWrapper.classList.add('hidden');
                    }
                    setTimeout(() => {
                        notificationWrapper.remove();
                    }, 1500);
                }, 10000);
            };
        </script>
    <?php endif; ?>
    <?php if (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) : ?>
        <script>
            const createAddressWrapper = document.querySelector('.create-address-wrapper');
            const nameWrapper = document.querySelector('.name-wrapper');
            document.getElementById('btn-create-address').addEventListener('click', (e) => {
                e.preventDefault();
                if (!nameWrapper.classList.contains('disable')) {
                    nameWrapper.classList.add('disable');
                }
                if (createAddressWrapper.classList.contains('disable')) {
                    createAddressWrapper.classList.remove('disable');
                }
            });
            document.getElementById('create-address-wrapper-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!createAddressWrapper.classList.contains('disable')) {
                    createAddressWrapper.classList.add('disable');
                }
                if (nameWrapper.classList.contains('disable')) {
                    nameWrapper.classList.remove('disable');
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
                    if (!nameWrapper.classList.contains('disable')) {
                        nameWrapper.classList.add('disable');
                    }
                    if (updateAddressWrapper.classList.contains('disable')) {
                        updateAddressWrapper.classList.remove('disable');
                    }
                });
            });
            document.getElementById('update-address-wrapper-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!updateAddressWrapper.classList.contains('disable')) {
                    updateAddressWrapper.classList.add('disable');
                }
                if (nameWrapper.classList.contains('disable')) {
                    nameWrapper.classList.remove('disable');
                }
            });
            const deleteAddressWrapper = document.querySelector('.delete-address-wrapper');
            document.querySelectorAll('.btn-delete-address').forEach(element => {
                element.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.getElementById('input-address-id').value = element.dataset.id;
                    if (!nameWrapper.classList.contains('disable')) {
                        nameWrapper.classList.add('disable');
                    }
                    if (deleteAddressWrapper.classList.contains('disable')) {
                        deleteAddressWrapper.classList.remove('disable');
                    }
                });
            });
            document.getElementById('delete-address-wrapper-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!deleteAddressWrapper.classList.contains('disable')) {
                    deleteAddressWrapper.classList.add('disable');
                }
                if (nameWrapper.classList.contains('disable')) {
                    nameWrapper.classList.remove('disable');
                }
            });
            document.getElementById('btn-address-delete-cancel').addEventListener('click', (e) => {
                e.preventDefault();
                if (!deleteAddressWrapper.classList.contains('disable')) {
                    deleteAddressWrapper.classList.add('disable');
                }
                if (nameWrapper.classList.contains('disable')) {
                    nameWrapper.classList.remove('disable');
                }
            });
            const btnAddressContinue = document.querySelector('.btn-address-continue');
            document.querySelectorAll('.selected-address-checkbox').forEach(element => {
                element.addEventListener('change', (e) => {
                    if (e.currentTarget.checked) {
                        if (btnAddressContinue.classList.contains('disable')) {
                            btnAddressContinue.classList.remove('disable');
                        }
                        btnAddressContinue.disabled = false;
                    }
                });
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
    <?php elseif (!empty($web_data['ready_to_buy'])) : ?>
        <script>
            const term2 = document.querySelector('.term2');
            document.getElementById('btn-order-term').addEventListener('click', (e) => {
                e.preventDefault();
                if (term2.classList.contains('disable')) {
                    term2.classList.remove('disable')
                }
                if (!bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.add('noscroll');
                }
            });
            document.getElementById('term2-exit').addEventListener('click', (e) => {
                e.preventDefault();
                if (!term2.classList.contains('disable')) {
                    term2.classList.add('disable');
                }
                if (bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.remove('noscroll');
                }
            });
            term2.addEventListener('mousedown', (e) => {
                e.preventDefault();
                if (e.target.classList == 'term2') {
                    if (!term2.classList.contains('disable')) {
                        term2.classList.add('disable');
                    }
                    if (bodyElement.classList.contains('noscroll')) {
                        bodyElement.classList.remove('noscroll');
                    }
                }
            });
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
            <?php if (!empty($_SESSION[SESSION_COMPLETE_PROFILE_NAME])) : ?>
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
            <?php elseif (!empty($_SESSION[SESSION_COMPLETE_PROFILE_IDENTITY])) : ?>
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
            <?php elseif (!empty($_SESSION[SESSION_COMPLETE_PROFILE_PHONE])) : ?>
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
            <?php elseif (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) : ?>
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
            <?php elseif (!empty($web_data['ready_to_buy'])) : ?>
                const cartName = $('#cart-name');
                const cartNumber = $('#cart-number');
                const cartExpiryMonth = $('#cart-expiry-month');
                const cartExpiryYear = $('#cart-expiry-year');
                const cartCvc = $('#cart-cvc');
                $('#btn-order-complete').click(function(e) {
                    e.preventDefault();
                    if (cartName.val() == '') {
                        cartName.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_CART_NAME; ?></span></div>');
                    } else if ($.trim(cartName.val()).indexOf(' ') == -1) {
                        cartName.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_CART_NAME; ?></span></div>');
                    } else if (cartNumber.val() == '') {
                        cartNumber.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_CART_NUMBER; ?></span></div>');
                    } else if ($.trim(cartNumber.val()).indexOf(' ') >= 0) {
                        cartNumber.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_CART_NUMBER; ?></span></div>');
                    } else if ($.trim(cartNumber.val()).length < <?php echo CART_NUMBER_MIN_LIMIT; ?> || $.trim(cartNumber.val()).length > <?php echo CART_NUMBER_MAX_LIMIT; ?>) {
                        cartNumber.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_CART_NUMBER; ?></span></div>');
                    } else if (cartExpiryMonth.val() == '') {
                        cartExpiryMonth.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_CART_EXPIRY_MONTH; ?></span></div>');
                    } else if ($.trim(cartExpiryMonth.val()).indexOf(' ') >= 0) {
                        cartExpiryMonth.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_MONTH; ?></span></div>');
                    } else if ($.trim(cartExpiryMonth.val()).length != <?php echo CART_EXPIRY_MONTH_LIMIT; ?>) {
                        cartExpiryMonth.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_MONTH; ?></span></div>');
                    } else if (cartExpiryYear.val() == '') {
                        cartExpiryYear.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_CART_EXPIRY_YEAR; ?></span></div>');
                    } else if ($.trim(cartExpiryYear.val()).indexOf(' ') >= 0) {
                        cartExpiryYear.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_YEAR; ?></span></div>');
                    } else if ($.trim(cartExpiryYear.val()).length != <?php echo CART_EXPIRY_YEAR_LIMIT; ?>) {
                        cartExpiryYear.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_YEAR; ?></span></div>');
                    } else if (cartCvc.val() == '') {
                        cartCvc.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_CART_CVC; ?></span></div>');
                    } else if ($.trim(cartCvc.val()).indexOf(' ') >= 0) {
                        cartCvc.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_CART_CVC; ?></span></div>');
                    } else if ($.trim(cartCvc.val()).length < <?php echo CART_CVC_MIN_LIMIT; ?> || $.trim(cartCvc.val()).length > <?php echo CART_CVC_MAX_LIMIT; ?>) {
                        cartCvc.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_CART_CVC; ?></span></div>');
                    } else if (!$('#accept-order-terms').is(':checked')) {
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_ORDER_TERMS; ?></span></div>');
                    } else {
                        if ($('.loader-wrapper').hasClass('hidden')) {
                            $('.loader-wrapper').removeClass('hidden');
                        }
                        if (!$('body').hasClass('noscroll')) {
                            $('body').addClass('noscroll');
                        }
                        $('#form-order-complete').submit();
                    }
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>