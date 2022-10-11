<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Giriş - Yönetim Paneli | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
    <style>
        .captcha-popup {
            z-index: 20 !important;
        }

        .action-container.black {
            background-color: #000000 !important;
            padding-top: 0 !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .admin-login-title {
            font-size: 2rem;
            color: #ffffff;
            padding-top: 2rem;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            border-width: 1px;
            border-style: solid;
            border-color: #ffffff;
            border-radius: 1rem;
        }

        .admin-login-row {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .input-admin-login {
            width: 100%;
            height: 6rem;
            border-width: 3px;
            border-style: solid;
            border-color: #ffffff;
            background-color: transparent;
            -webkit-box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            -moz-box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            border-radius: 1rem;
            font-size: 1.6rem;
            color: #ffffff;
            text-align: center;
            -webkit-transition-property: border-right-width, border-left-width;
            -moz-transition-property: border-right-width, border-left-width;
            -o-transition-property: border-right-width, border-left-width;
            transition-property: border-right-width, border-left-width;
            -webkit-transition-duration: 0.2s;
            -moz-transition-duration: 0.2s;
            -o-transition-duration: 0.2s;
            transition-duration: 0.2s;
            -webkit-transition-timing-function: ease;
            -moz-transition-timing-function: ease;
            -o-transition-timing-function: ease;
            transition-timing-function: ease;
        }

        .input-admin-login:not(:last-child) {
            margin-bottom: 2rem;
        }

        .input-admin-login:focus {
            outline: 0;
            border-right-width: 2rem !important;
            border-left-width: 2rem !important;
            -webkit-box-shadow: 0 0 15px rgba(255, 255, 255, 1) !important;
            -moz-box-shadow: 0 0 15px rgba(255, 255, 255, 1) !important;
            box-shadow: 0 0 15px rgba(255, 255, 255, 1) !important;
        }

        .input-admin-login::placeholder {
            color: #ffffff;
            opacity: 0.6;
        }

        .input-admin-login-submit {
            z-index: 10;
            position: absolute;
            width: 100%;
            height: 100%;
            border: none;
            background-color: transparent;
            font-size: 1.6rem;
            color: #ffffff;
            text-align: center;
            -webkit-transition-property: color;
            -moz-transition-property: color;
            -o-transition-property: color;
            transition-property: color;
            -webkit-transition-duration: 0.3s;
            -moz-transition-duration: 0.3s;
            -o-transition-duration: 0.3s;
            transition-duration: 0.3s;
            -webkit-transition-timing-function: ease;
            -moz-transition-timing-function: ease;
            -o-transition-timing-function: ease;
            transition-timing-function: ease;
            cursor: pointer;
        }

        .submit-container {
            position: relative;
            width: 100%;
            height: 6rem;
            border-width: 3px;
            border-style: solid;
            border-color: #ffffff;
            -webkit-box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            -moz-box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            border-radius: 1rem;
        }

        .submit-container.disable {
            display: none !important;
        }

        .submit-left-hover {
            z-index: 9;
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #ffffffff;
            -webkit-transition-property: width;
            -moz-transition-property: width;
            -o-transition-property: width;
            transition-property: width;
            -webkit-transition-duration: 0.3s;
            -moz-transition-duration: 0.3s;
            -o-transition-duration: 0.3s;
            transition-duration: 0.3s;
            -webkit-transition-timing-function: ease;
            -moz-transition-timing-function: ease;
            -o-transition-timing-function: ease;
            transition-timing-function: ease;
        }

        .submit-left-hover.disable {
            display: none !important;
        }

        .submit-right-hover {
            z-index: 9;
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background-color: #ffffffff;
            -webkit-transition-property: width;
            -moz-transition-property: width;
            -o-transition-property: width;
            transition-property: width;
            -webkit-transition-duration: 0.3s;
            -moz-transition-duration: 0.3s;
            -o-transition-duration: 0.3s;
            transition-duration: 0.3s;
            -webkit-transition-timing-function: ease;
            -moz-transition-timing-function: ease;
            -o-transition-timing-function: ease;
            transition-timing-function: ease;
        }

        .submit-right-hover.disable {
            display: none !important;
        }

        .submit-container:hover .input-admin-login-submit {
            color: #000000 !important;
        }

        .submit-container:hover .submit-right-hover {
            width: 50% !important;
        }

        .submit-container:hover .submit-left-hover {
            width: 50% !important;
        }
    </style>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="action-section">
            <div class="action-container black">
                <h1 class="admin-login-title">Yönetici Giriş</h1>
                <form id="form-admin-login" action="<?php echo URL . URL_ADMIN_LOGIN; ?>" method="POST" class="admin-login-row" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <?php if (!empty($web_data['form_token'])) : ?>
                        <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                    <?php endif; ?>
                    <input class="input-admin-login" id="input-email" type="email" name="email" placeholder="Email" autofocus>
                    <input class="input-admin-login" id="input-password" type="password" name="password" placeholder="Şifre">
                    <div class="captcha-popup">
                        <div class="captcha-container">
                            <h2 class="captcha-title">Ben Bir İnsanım Testi</h2>
                            <div class="captcha-form-container">
                                <span class="text">Lütfen aşağıdaki testi çözerek insan olduğunuzu doğrulayın</span>
                                <div id="captchaDiv"></div>
                            </div>
                            <div class="close-popup">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                    </div>
                    <div class="submit-container">
                        <div class="submit-left-hover"></div>
                        <button id="btn-admin-login" class="input-admin-login-submit">Giriş</button>
                        <div class="submit-right-hover"></div>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/eyebtn.js"></script>
    <?php if (!empty($web_data['cookie_cart'])) : ?>
        <script src="<?php echo URL; ?>assets/js/header_cart.js"></script>
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
            const captchaPopup = $('.captcha-popup');
            $('#btn-admin-login').click(function(e) {
                e.preventDefault();
                const inputEmail = $('#input-email');
                const inputPassword = $('#input-password');
                if (inputEmail.val() == '') {
                    inputEmail.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_EMAIL; ?></span></div>');
                } else if (inputPassword.val() == '') {
                    inputPassword.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?></span></div>');
                } else {
                    clearTimeout(notificationHidden);
                    clearTimeout(notificationRemoved);
                    if (!notificationClient.hasClass('hidden')) {
                        notificationClient.addClass('hidden');
                    }
                    if (!notificationClient.hasClass('removed')) {
                        notificationClient.addClass('removed');
                    }
                    if (!captchaPopup.hasClass('active')) {
                        captchaPopup.addClass('active');
                    }
                }
            });
            $('.close-popup').click(function(e) {
                e.preventDefault();
                if (captchaPopup.hasClass('active')) {
                    captchaPopup.removeClass('active');
                }
            });
        });
    </script>
    <script src="<?php echo CAPTCHA_SRC; ?>" async defer></script>
    <script type="text/javascript">
        var hCaptcha = function() {
            hcaptcha.render('captchaDiv', {
                sitekey: '<?php echo CAPTCHA_SITE_KEY; ?>',
                theme: 'dark',
                'callback': 'hCaptchaCallback'
            });
        }

        function hCaptchaCallback() {
            if (loaderWrapper.classList.contains('hidden')) {
                loaderWrapper.classList.remove('hidden');
            }
            if (!bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.add('noscroll');
            }
            document.getElementById('form-admin-login').submit();
        }
    </script>
</body>

</html>