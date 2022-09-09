<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Şifre Sıfırlama | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="action-section">
            <div class="action-container">
                <h1 class="forgot-password-title">Yeni Şifrenizi Belirleyin</h1>
                <form id="form-reset-password" action="<?php echo URL . URL_RESET_PASSWORD; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <?php if (!empty($web_data['form_token'])) : ?>
                        <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                    <?php endif; ?>
                    <?php if (!empty($web_data['reset_password_token'])) : ?>
                        <input type="hidden" name="reset_password_token" value="<?php echo $web_data['reset_password_token']; ?>">
                    <?php endif; ?>
                    <div class="form-row">
                        <div class="group">
                            <input class="input-action" id="input-password" type="password" name="password" autofocus>
                            <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                            <span class="input-action-label">Yeni Şifre</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <span class="input-message" id="password-message"></span>
                    </div>
                    <div class="form-row">
                        <div class="group">
                            <input class="input-action" id="input-repassword" type="password" name="repassword">
                            <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                            <span class="input-action-label">Yeni Şifre Tekrar</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <span class="input-message" id="repassword-message"></span>
                    </div>
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
                    <div class="form-row">
                        <button id="btn-reset-password" class="btn-action-submit">Şiremi Yenile</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/action_input.js"></script>
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
            const inputPassword = $('#input-password');
            const passwordMessage = $('#password-message');
            const inputRepassword = $('#input-repassword');
            const repasswordMessage = $('#repassword-message');
            const captchaPopup = $('.captcha-popup');
            $('#btn-reset-password').click(function(e) {
                e.preventDefault();
                if (inputPassword.val() == '') {
                    inputPassword.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?></span></div>');
                } else if ($.trim(inputPassword.val()).indexOf(' ') >= 0) {
                    inputPassword.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD; ?></span></div>');
                } else if ($.trim(inputPassword.val()).length <= <?php echo PASSWORD_MIN_LIMIT; ?>) {
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
            inputPassword.keyup(function(e) {
                if (inputPassword.val() == '') {
                    passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?>');
                } else if ($.trim(inputPassword.val()).indexOf(' ') >= 0) {
                    passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD; ?>');
                } else if ($.trim(inputPassword.val()).length <= <?php echo PASSWORD_MIN_LIMIT; ?>) {
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
            document.getElementById('form-reset-password').submit();
        }
    </script>
</body>

</html>