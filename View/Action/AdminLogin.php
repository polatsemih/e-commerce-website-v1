<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Giriş | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedCommon/_common_meta.php'; ?>
    <meta name="robots" content="none" />
    <?php require 'View/SharedCommon/_common_favicon.php'; ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/home.css">
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/captcha.css">
    <style>
        .admin-login-container {
            width: 100vw;
            height: 100vh;
            background-color: #000000;
        }

        .form-admin {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .input-admin-login {
            width: 90%;
            height: 6rem;
            border-width: 3px;
            border-style: solid;
            border-color: #ffffff;
            background-color: transparent;
            -webkit-box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            -moz-box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            border-radius: 0.5rem;
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

        .input-admin-login.disable {
            display: none !important;
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
            width: 90%;
            height: 6rem;
            border-width: 3px;
            border-style: solid;
            border-color: #ffffff;
            -webkit-box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            -moz-box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            border-radius: 0.5rem;
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

        @media only screen and (min-width: 576px) {
            .input-admin-login {
                width: 50%;
            }

            .submit-container {
                width: 50%;
            }
        }

        @media only screen and (min-width: 768px) {
            .input-admin-login {
                width: 40%;
            }

            .submit-container {
                width: 40%;
            }
        }

        @media only screen and (min-width: 992px) {
            .input-admin-login {
                width: 30%;
                height: 7rem;
            }

            .submit-container {
                width: 30%;
                height: 7rem;
            }
        }

        @media only screen and (min-width: 1650px) {
            .input-admin-login {
                height: 8rem;
            }

            .submit-container {
                height: 8rem;
            }
        }
    </style>
</head>

<body>
    <?php require 'View/SharedCommon/_common_loader.php'; ?>
    <div class="notification">
        <?php if (isset($_SESSION[SESSION_NOTIFICATION])) {
            echo $_SESSION[SESSION_NOTIFICATION];
            unset($_SESSION[SESSION_NOTIFICATION]);
        } ?>
    </div>
    <div class="notification-client"></div>
    <div class="admin-login-container">
        <form action="<?php echo URL . URL_ADMIN_LOGIN; ?>" method="POST" class="form-admin" id="form-admin-login" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
            <?php if (!empty($web_data['csrf_token'])) : ?>
                <input type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
            <?php endif; ?>
            <?php if (!empty($web_data['email'])) : ?>
                <input class="input-admin-login" id="input_email" type="email" name="email" placeholder="Email" value="<?php echo $web_data['email']; ?>">
                <input class="input-admin-login" id="input_password" type="password" name="password" placeholder="Şifre" <?php echo !empty($web_data['password']) ? 'value="' . $web_data['password'] . '"' : 'autofocus' ?>>
            <?php else : ?>
                <input class="input-admin-login" id="input_email" type="email" name="email" placeholder="Email" autofocus>
                <input class="input-admin-login" id="input_password" type="password" name="password" placeholder="Şifre" <?php echo !empty($web_data['password']) ? 'value="' . $web_data['password'] . '"' : '' ?>>
            <?php endif; ?>
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
            <div class="submit-container">
                <div class="submit-left-hover"></div>
                <input class="input-admin-login-submit" type="submit" value="Giriş">
                <div class="submit-right-hover"></div>
            </div>
        </form>
    </div>
    <script src="<?php echo URL; ?>assets/js/loader_notification.js"></script>
    <script>
        const notificationClient = document.querySelector('.notification-client');
        let notificationHidden = 0;
        let notificationRemoved = 0;
        function setClientNotification(notificationMessage) {
            clearTimeout(notificationHidden);
            clearTimeout(notificationRemoved);
            notificationClient.innerHTML = '<div class="not not-danger"><span class="not-text">' + notificationMessage + '</span></div>';
            if (notificationClient.classList.contains('hidden') && notificationClient.classList.contains('removed')) {
                notificationClient.classList.remove('hidden');
                notificationClient.classList.remove('removed');
            }
            notificationHidden = setTimeout(() => {
                notificationClient.classList.add('hidden');
                notificationRemoved = setTimeout(() => {
                    notificationClient.classList.add('removed');
                }, 1500);
            }, 5000);
        }
        document.querySelector('.input-admin-login-submit').addEventListener('click', (e) => {
            e.preventDefault();
            notification.classList.add('hidden');
            notification.classList.add('removed');
            let inputEmail = document.getElementById('input_email');
            let inputPassword = document.getElementById('input_password');
            if (inputEmail.value.trim() == '') {
                inputEmail.focus();
                setClientNotification('<?php echo ERROR_MESSAGE_EMPTY_EMAIL; ?>');
            } else if (inputPassword.value.trim() == '') {
                inputPassword.focus();
                setClientNotification('<?php echo ERROR_MESSAGE_EMPTY_PASSWORD; ?>');
            } else {
                document.querySelectorAll('.input-admin-login').forEach(element => {
                    element.classList.add('disable');
                });
                document.querySelector('.submit-container').classList.add('disable');
                document.querySelector('.captcha-popup').classList.add('active');
            }
        });
        document.querySelector('.close-popup').addEventListener('click', () => {
            document.querySelectorAll('.input-admin-login').forEach(element => {
                element.classList.remove('disable');
            });
            document.querySelector('.submit-container').classList.remove('disable');
            document.querySelector('.captcha-popup').classList.remove('active');
        });
    </script>
    <script src="https://js.hcaptcha.com/1/api.js?onload=hCaptcha&render=explicit&hl=tr" async defer></script>
    <script type="text/javascript">
        var hCaptcha = function() {
            hcaptcha.render('captchaDiv', {
                sitekey: '<?php echo SITE_KEY; ?>',
                theme: 'dark',
                'callback': 'hCaptchaCallback'
            });
        }

        function hCaptchaCallback() {
            if (loader.classList.contains('disable') && loader.classList.contains('loading')) {
                loader.classList.remove('disable');
                loader.classList.remove('loading');
            }
            document.getElementById('form-admin-login').submit();
        }
    </script>
</body>

</html>