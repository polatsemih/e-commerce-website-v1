<?php if (!empty($web_data['iyzico_form'])) : ?>
    <?php echo $web_data['iyzico_form']; ?>
<?php else : ?>
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
                <?php elseif (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) : ?>
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
                    <div id="popup-wrapper" class="name-wrapper<?php echo !empty($web_data['address']) ? ' disable' : ''; ?>">
                        <div class="popup-container">
                            <div class="row-space">
                                <div class="left">
                                    <h4 class="title">Teslimatın Yapılacağı Adresi Seçin</h4>
                                </div>
                                <div class="right">
                                    <button id="btn-create-address" class="btn-success btn-add-address" title="Yeni Adres Ekle">Adres Ekle</button>
                                </div>
                            </div>
                            <?php if (!empty($web_data['select_address'])) : ?>
                                <form action="<?php echo URL . URL_ORDER_ADDRESS; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                    <?php $i = 0; ?>
                                    <?php foreach ($web_data['select_address'] as $address) : ?>
                                        <?php $i++; ?>
                                        <label for="selected-address-<?php echo $i; ?>">
                                            <input class="selected-address-checkbox" type="radio" name="selected_address" value="<?php echo $address['id']; ?>" id="selected-address-<?php echo $i; ?>">
                                            <div class="address-box">
                                                <span class="selected-address-box"></span>
                                                <span class="address-text"><?php echo $address['address_neighborhood'] . ', ' . $address['address_street'] . ', Apartman No: ' . $address['address_building_no'] . ' Daire No: ' . $address['address_apartment_no'] . ' ' . $address['address_county'] . '/' . $address['address_city'] . '/' . $address['address_country'] . ' ZIP Kodu:' . $address['address_zip_no']; ?></span>
                                                <div class="address-btn-row">
                                                    <span class="btn-update-address btn-warning mr" data-id="<?php echo $address['id']; ?>" data-neighborhood="<?php echo $address['address_neighborhood']; ?>" data-street="<?php echo $address['address_street']; ?>" data-building_no="<?php echo $address['address_building_no']; ?>" data-apartment_no="<?php echo $address['address_apartment_no']; ?>" data-county="<?php echo $address['address_county']; ?>" data-city="<?php echo $address['address_city']; ?>" data-zip_no="<?php echo $address['address_zip_no']; ?>">Düzenle</span>
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
                    <form id="form-order-complete" action="<?php echo URL . URL_ORDER_CREDIT; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
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
                                        <div class="left row-space">
                                            <span class="checkmark selected"></span>
                                            <span class="checkmark-text">3D Güvenli Ödeme Etkin</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
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
                                <input class="btn-submit" id="btn-order-complete" type="submit" value="Siparişi Onayla" title="Siparişi Onayla">
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </section>
        </main>
        <?php require_once 'View/SharedHome/_home_footer.php'; ?>
        <?php if (!empty($_SESSION[SESSION_COMPLETE_PROFILE_NAME]) || !empty($_SESSION[SESSION_COMPLETE_PROFILE_IDENTITY]) || !empty($_SESSION[SESSION_COMPLETE_ADDRESS])) : ?>
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
                        document.getElementById('address-autofocus').focus();
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
                        document.getElementById('update-city').value = element.dataset.city;
                        document.getElementById('update-county').value = element.dataset.county;
                        document.getElementById('update-neighborhood').value = element.dataset.neighborhood;
                        document.getElementById('update-street').value = element.dataset.street;
                        document.getElementById('update-building_no').value = element.dataset.building_no;
                        document.getElementById('update-apartment_no').value = element.dataset.apartment_no;
                        document.getElementById('update-zip_no').value = element.dataset.zip_no;
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
                            document.getElementById('address-autofocus').focus();
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
                <?php endif; ?>
                <?php if (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) : ?>
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
                <?php if (!empty($web_data['ready_to_buy'])) : ?>
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
                    $('#btn-order-term').click(function(e) {
                        e.preventDefault();
                    });
                <?php endif; ?>
            });
        </script>
    </body>

    </html>
<?php endif; ?>