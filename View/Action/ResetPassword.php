<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Şifre Sıfırlama | <?php echo BRAND; ?></title>
    <?php require 'View/SharedCommon/_common_meta.php'; ?>
    <meta name="robots" content="none" />
    <?php require 'View/SharedCommon/_common_favicon.php'; ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/home.css">
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/captcha.css">
</head>

<body>
    <?php require 'View/SharedCommon/_common_loader.php'; ?>
    <header>
        <div class="header-container">
            <div class="container row-adjacent">
                <div class="header-brand-container">
                    <a class="header-brand" href="<?php echo URL; ?>"><?php echo BRAND; ?></a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="notification">
            <?php if (isset($_SESSION[SESSION_NOTIFICATION])) {
                echo $_SESSION[SESSION_NOTIFICATION];
                unset($_SESSION[SESSION_NOTIFICATION]);
            } ?>
        </div>
        <div class="notification-client"></div>
        <section class="action-section">
            <div class="action-container">
                <h1 class="forgot-password-title">Yeni Şifrenizi Belirleyin</h1>
                <form action="<?php echo URL . URL_RESET_PASSWORD; ?>" method="POST" id="form-password-reset" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <?php if (!empty($web_data['csrf_token'])) : ?>
                        <input type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                    <?php endif; ?>
                    <?php if (!empty($web_data['reset_pwd_token'])) : ?>
                        <input type="hidden" name="reset_pwd_token" value="<?php echo $web_data['reset_pwd_token']; ?>">
                    <?php endif; ?>
                    <div class="action-container-3">
                        <div class="action-group">
                            <?php if (!empty($web_data['password'])) : ?>
                                <input class="input-action" id="input_password" type="password" name="password" value="<?php echo $web_data['password']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input_password" type="password" name="password" autofocus>
                            <?php endif; ?>
                            <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                            <span class="input-action-label">Yeni Şifre</span>
                        </div>
                    </div>
                    <div class="action-container-3">
                        <span class="input-message" id="password-message"></span>
                    </div>
                    <div class="action-container-3">
                        <div class="action-group">
                            <?php if (!empty($web_data['password'])) : ?>
                                <input class="input-action" id="input_repassword" type="password" name="repassword" value="<?php echo $web_data['repassword']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input_repassword" type="password" name="repassword" <?php echo !empty($web_data['password']) ? ' autofocus' : ''; ?>>
                            <?php endif; ?>
                            <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                            <span class="input-action-label">Yeni Şifre Tekrar</span>
                        </div>
                    </div>
                    <div class="action-container-3">
                        <span class="input-message" id="repassword-message"></span>
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
                    <div class="action-container-3">
                        <input class="btn-action-submit" type="submit" name="reset-password-submit" value="Şiremi Yenile">
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/loader_notification_action.js"></script>
    <script src="<?php echo URL; ?>assets/js/eyebtn.js"></script>
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
        let inputPassword = document.getElementById('input_password');
        let passwordMessage = document.getElementById('password-message');
        let inputRepassword = document.getElementById('input_repassword');
        let repasswordMessage = document.getElementById('repassword-message');
        document.querySelector('.btn-action-submit').addEventListener('click', (e) => {
            e.preventDefault();
            notificationClient.classList.add('hidden');
            notificationClient.classList.add('removed');
            if (inputPassword.value == '') {
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
            } else {
                document.querySelectorAll('.action-container-3').forEach(element => {
                    element.classList.add('disable');
                });
                document.querySelector('.action-container').classList.add('invisible');
                document.querySelector('.captcha-popup').classList.add('active');
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
            } else {
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
        document.querySelector('.close-popup').addEventListener('click', () => {
            document.querySelectorAll('.action-container-3').forEach(element => {
                element.classList.remove('disable');
            });
            document.querySelector('.action-container').classList.remove('invisible');
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
            document.getElementById('form-password-reset').submit();
        }
    </script>
</body>

</html>