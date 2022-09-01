<!DOCTYPE html>
<html lang="tr">

<head>
    title>Kayıt | <?php echo BRAND; ?></title>
    <meta name="robots" content="all" />
    <meta name="description" content="<?php echo BRAND; ?> Kayıt" />
    <meta name="keywords" content="blanck basic, blnckk" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <div class="action-agreement-wrapper disable">
            <div class="give-back-container">
                <div class="action-agreement-exit-container">
                    <div class="give-back-exit">
                        <i class="fas fa-times give-back-exit-icon"></i>
                    </div>
                </div>
                <h3 class="give-back-title">Gizlilik Sözleşmesi</h3>
                <p class="give-back-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet? Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                <p class="give-back-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                <p class="give-back-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                <p class="give-back-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
            </div>
        </div>
        <div class="action-agreement-2-wrapper disable">
            <div class="give-back-container">
                <div class="action-agreement-2-exit-container">
                    <div class="give-back-exit">
                        <i class="fas fa-times give-back-exit-icon"></i>
                    </div>
                </div>
                <h3 class="give-back-title">Kullanım Şartları</h3>
                <p class="give-back-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet? Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                <p class="give-back-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                <p class="give-back-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
                <p class="give-back-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facilis est exercitationem nulla temporibus nostrum? Debitis soluta corrupti esse eos, tempora error explicabo! Doloremque labore consequuntur accusantium autem qui ad amet?</p>
            </div>
        </div>
        <section class="action-section">
            <div class="action-container">
                <h1 class="action-title">Kayıt</h1>
                <form id="form-register" action="<?php echo URL . URL_REGISTER; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <?php if (!empty($web_data['csrf_token'])) : ?>
                        <input type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                    <?php endif; ?>
                    <div class="action-container-1-5">
                        <div class="action-group">
                            <?php if (!empty($web_data['email'])) : ?>
                                <input class="input-action" id="input_email" type="email" name="email" value="<?php echo $web_data['email']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input_email" type="email" name="email" autofocus>
                            <?php endif; ?>
                            <span class="input-action-label">Email</span>
                        </div>
                    </div>
                    <div class="action-container-1-5">
                        <span class="input-message" id="email-message"></span>
                    </div>
                    <div class="action-container-1-5">
                        <div class="action-group">
                            <?php if (!empty($web_data['password'])) : ?>
                                <input class="input-action" id="input_password" type="password" name="password" value="<?php echo $web_data['password']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input_password" type="password" name="password" <?php echo !empty($web_data['email']) ? ' autofocus' : ''; ?>>
                            <?php endif; ?>
                            <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                            <span class="input-action-label">Şifre</span>
                        </div>
                    </div>
                    <div class="action-container-1-5">
                        <span class="input-message" id="password-message"></span>
                    </div>
                    <div class="action-container-1-5">
                        <div class="action-group">
                            <?php if (!empty($web_data['repassword'])) : ?>
                                <input class="input-action" id="input_repassword" type="password" name="repassword" value="<?php echo $web_data['repassword']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input_repassword" type="password" name="repassword" <?php echo (!empty($web_data['email']) && !empty($web_data['password'])) ? ' autofocus' : ''; ?>>
                            <?php endif; ?>
                            <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                            <span class="input-action-label">Şifre Tekrar</span>
                        </div>
                    </div>
                    <div class="action-container-1-5">
                        <span class="input-message" id="repassword-message"></span>
                    </div>
                    <div class="action-container-1-5">
                        <label for="accept_terms" class="label-checkbox">
                            <input type="checkbox" class="checkbox" id="accept_terms" name="accept_terms">
                            <span class="checkmark-filter"></span>
                        </label>
                        <span class="checkmark-text"><?php echo BRAND; ?> <button class="action-privacy-link" id="btn-agreement-privacy">gizlilik sözleşmesi</button> ve <button class="action-privacy-link" id="btn-agreement-2-privacy">kullanım şartlarını</button> okudum ve kabul ediyorum.</span>
                    </div>
                    <div class="captcha-popup">
                        <div class="captcha-container">
                            <div class="captcha-title-container">
                                <h1 class="captcha-title">Ben Bir İnsanım Testi</h1>
                            </div>
                            <div class="captcha-form-container">
                                <span class="captcha-text">Lütfen aşağıdaki testi çözerek insan olduğunuzu doğrulayın.</span>
                                <div id="captchaDiv"></div>
                            </div>
                            <div class="close-popup">
                                <i class="fas fa-times close-popup-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="action-container-1-5">
                        <input class="btn-action-submit" name="register-submit" type="submit" value="Üye Ol">
                    </div>
                </form>
                <div class="action-link-container">
                    <span class="action-text">Zaten üye misin? </span>
                    <a href="<?php echo URL . URL_LOGIN; ?>"><span class="action-link-text">Giriş Yap</span></a>
                </div>
            </div>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/eyebtn.js"></script>
    <?php if (!empty($web_data['cookie_cart'])) : ?>
        <script src="<?php echo URL; ?>assets/js/header_cart.js"></script>
    <?php endif; ?>
    <script>
        let inputEmail = document.getElementById('input_email');
        let emailMessage = document.getElementById('email-message');
        let inputPassword = document.getElementById('input_password');
        let passwordMessage = document.getElementById('password-message');
        let inputRepassword = document.getElementById('input_repassword');
        let repasswordMessage = document.getElementById('repassword-message');
        document.querySelector('.btn-action-submit').addEventListener('click', (e) => {
            e.preventDefault();
            notificationClient.classList.add('hidden');
            notificationClient.classList.add('removed');
            let checkbox = document.getElementById('accept_terms');
            if (inputEmail.value == '') {
                inputEmail.focus();
                setClientNotification('<?php echo ERROR_MESSAGE_EMPTY_EMAIL; ?>');
            } else if (inputEmail.value.indexOf(' ') >= 0) {
                inputEmail.focus();
                setClientNotification('<?php echo ERROR_NOT_VALID_EMAIL; ?>');
            } else if (inputPassword.value == '') {
                inputPassword.focus();
                setClientNotification('<?php echo ERROR_MESSAGE_EMPTY_PASSWORD; ?>');
            } else if (inputPassword.value.indexOf(' ') >= 0) {
                inputPassword.focus();
                setClientNotification('<?php echo ERROR_PASSWORD_WHITE_SPACE; ?>');
            } else if (inputPassword.value.trim().length < <?php echo PASSWORD_LIMIT; ?>) {
                inputPassword.focus();
                setClientNotification('<?php echo ERROR_MAX_LENGTH_PASSWORD; ?>');
            } else if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/.test(inputPassword.value)) {
                inputPassword.focus();
                setClientNotification('<?php echo ERROR_NOT_VALID_PASSWORD; ?>');
            } else if (inputRepassword.value == '') {
                inputRepassword.focus();
                setClientNotification('<?php echo ERROR_MESSAGE_EMPTY_REPASSWORD; ?>');
            } else if (inputPassword.value != inputRepassword.value) {
                inputRepassword.focus();
                setClientNotification('<?php echo PASSWORDS_NOT_SAME; ?>');
            } else if (checkbox.checked == false) {
                setClientNotification('<?php echo ERROR_MESSAGE_EMPTY_ACCEPT_REGISTER_TERMS; ?>');
            } else {
                document.querySelectorAll('.action-container-1-5').forEach(element => {
                    element.classList.add('disable');
                });
                document.querySelector('.action-link-container').classList.add('disable');
                document.querySelector('.action-container').classList.add('invisible');
                document.querySelector('.captcha-popup').classList.add('active');
            }
        });
        inputEmail.addEventListener('keyup', () => {
            if (inputEmail.value == '') {
                emailMessage.innerHTML = '<?php echo ERROR_MESSAGE_EMPTY_EMAIL; ?>';
            } else if (inputEmail.value.indexOf(' ') >= 0) {
                emailMessage.innerHTML = '<?php echo ERROR_NOT_VALID_EMAIL; ?>';
            } else {
                emailMessage.innerHTML = '';
            }
        });
        inputPassword.addEventListener('keyup', () => {
            if (inputPassword.value == '') {
                passwordMessage.innerHTML = '<?php echo ERROR_MESSAGE_EMPTY_PASSWORD; ?>';
            } else if (inputPassword.value.indexOf(' ') >= 0) {
                passwordMessage.innerHTML = '<?php echo ERROR_PASSWORD_WHITE_SPACE; ?>';
            } else if (inputPassword.value.trim().length < <?php echo PASSWORD_LIMIT; ?>) {
                passwordMessage.innerHTML = '<?php echo ERROR_MAX_LENGTH_PASSWORD; ?>';
            } else if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/.test(inputPassword.value)) {
                passwordMessage.innerHTML = '<?php echo ERROR_NOT_VALID_PASSWORD; ?>';
            } else if (inputPassword.value != inputRepassword.value) {
                repasswordMessage.innerHTML = '<?php echo PASSWORDS_NOT_SAME; ?>';
                passwordMessage.innerHTML = '';
            } else {
                repasswordMessage.innerHTML = '';
                passwordMessage.innerHTML = '';
            }
        });
        inputRepassword.addEventListener('keyup', () => {
            if (inputRepassword.value == '') {
                repasswordMessage.innerHTML = '<?php echo ERROR_MESSAGE_EMPTY_REPASSWORD; ?>';
            } else if (inputPassword.value != inputRepassword.value) {
                repasswordMessage.innerHTML = '<?php echo PASSWORDS_NOT_SAME; ?>';
            } else {
                repasswordMessage.innerHTML = '';
            }
        });
        // const actionAgreementWrapper = document.querySelector('.action-agreement-wrapper');
        // document.getElementById('btn-agreement-privacy').addEventListener('click', (e) => {
        //     e.preventDefault();
        //     if (actionAgreementWrapper.classList.contains('disable')) {
        //         actionAgreementWrapper.classList.remove('disable')
        //     }
        //     if (!body.classList.contains('noscroll')) {
        //         body.classList.add('noscroll');
        //     }
        // });
        // document.querySelector('.action-agreement-exit-container').addEventListener('click', (e) => {
        //     e.preventDefault();
        //     if (!actionAgreementWrapper.classList.contains('disable')) {
        //         actionAgreementWrapper.classList.add('disable');
        //     }
        //     if (body.classList.contains('noscroll')) {
        //         body.classList.remove('noscroll');
        //     }
        // });
        // actionAgreementWrapper.addEventListener('mouseup', (e) => {
        //     e.preventDefault();
        //     if (e.target.classList == 'action-agreement-wrapper') {
        //         if (!actionAgreementWrapper.classList.contains('disable')) {
        //             actionAgreementWrapper.classList.add('disable');
        //         }
        //         if (body.classList.contains('noscroll')) {
        //             body.classList.remove('noscroll');
        //         }
        //     }
        // });
        // const actionAgreementWrapper2 = document.querySelector('.action-agreement-2-wrapper');
        // document.getElementById('btn-agreement-2-privacy').addEventListener('click', (e) => {
        //     e.preventDefault();
        //     if (actionAgreementWrapper2.classList.contains('disable')) {
        //         actionAgreementWrapper2.classList.remove('disable')
        //     }
        //     if (!body.classList.contains('noscroll')) {
        //         body.classList.add('noscroll');
        //     }
        // });
        // document.querySelector('.action-agreement-2-exit-container').addEventListener('click', (e) => {
        //     e.preventDefault();
        //     if (!actionAgreementWrapper2.classList.contains('disable')) {
        //         actionAgreementWrapper2.classList.add('disable');
        //     }
        //     if (body.classList.contains('noscroll')) {
        //         body.classList.remove('noscroll');
        //     }
        // });
        // actionAgreementWrapper2.addEventListener('mouseup', (e) => {
        //     e.preventDefault();
        //     if (e.target.classList == 'action-agreement-2-wrapper') {
        //         if (!actionAgreementWrapper2.classList.contains('disable')) {
        //             actionAgreementWrapper2.classList.add('disable');
        //         }
        //         if (body.classList.contains('noscroll')) {
        //             body.classList.remove('noscroll');
        //         }
        //     }
        // });
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
                        url: '<?php echo URL . URL_SEARCH; ?>',
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
                        if (response.hasOwnProperty('not_found_search_item')) {
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
            $('#btn-login').click(function(e) {
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
            document.getElementById('form-register').submit();
        }
    </script>
</body>

</html>