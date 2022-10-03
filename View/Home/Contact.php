<!DOCTYPE html>
<html lang="tr">

<head>
    <title>İletişim | <?php echo BRAND; ?></title>
    <meta name="robots" content="all" />
    <meta name="description" content="<?php echo BRAND; ?> İletişim" />
    <meta name="keywords" content="blanck basic, blnckk" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="action-section contact">
            <div class="action-container">
                <h1 class="title">İletişim</h1>
                <?php if (!empty($web_data['contact_notification'])) : ?>
                    <span class="contact-not"><?php echo $web_data['contact_notification']; ?></span>
                <?php endif; ?>
                <form id="form-contact" action="<?php echo URL . URL_CONTACT; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <?php if (!empty($web_data['form_token'])) : ?>
                        <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                    <?php endif; ?>
                    <div class="form-row">
                        <div class="group">
                            <?php if (!empty($web_data['first_name'])) : ?>
                                <input class="input-action" id="input-first-name" type="text" name="first_name" value="<?php echo $web_data['first_name']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input-first-name" type="text" name="first_name" autofocus>
                            <?php endif; ?>
                            <span class="input-action-label">İsim</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="group">
                            <?php if (!empty($web_data['last_name'])) : ?>
                                <input class="input-action" id="input-last-name" type="text" name="last_name" value="<?php echo $web_data['last_name']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input-last-name" type="text" name="last_name">
                            <?php endif; ?>
                            <span class="input-action-label">Soy İsim</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="group">
                            <?php if (!empty($web_data['email'])) : ?>
                                <input class="input-action" id="input-email" type="email" name="email" value="<?php echo $web_data['email']; ?>">
                            <?php else : ?>
                                <input class=" input-action" id="input-email" type="email" name="email">
                            <?php endif; ?>
                            <span class="input-action-label">Email</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="group">
                            <?php if (!empty($web_data['email'])) : ?>
                                <textarea class="input-textarea" id="input-message" name="message" placeholder="Mesaj"><?php echo $web_data['email']; ?></textarea>
                            <?php else : ?>
                                <textarea class="input-textarea" id="input-message" name="message" placeholder="Mesaj"></textarea>
                            <?php endif; ?>
                        </div>
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
                        <button id="btn-contact" class="btn-action-submit">Gönder</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/action_input.js"></script>
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
            $('#btn-contact').click(function(e) {
                e.preventDefault();
                const inputFirstName = $('#input-first-name');
                const inputLastName = $('#input-last-name');
                const inputEmail = $('#input-email');
                const inputMessage = $('#input-message');
                if (inputFirstName.val() == '') {
                    inputFirstName.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_USER_NAME; ?></span></div>');
                } else if (inputLastName.val() == '') {
                    inputLastName.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_USER_LAST_NAME; ?></span></div>');
                } else if (inputEmail.val() == '') {
                    inputEmail.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_EMAIL; ?></span></div>');
                } else if (inputMessage.val() == '') {
                    inputMessage.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_CONTACT_MESSAGE; ?></span></div>');
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
            document.getElementById('form-contact').submit();
        }
    </script>
</body>

</html>