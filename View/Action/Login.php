<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Giriş | <?php echo BRAND; ?></title>
    <meta name="description" content="<?php echo BRAND; ?> giriş yap" />
    <meta name="keywords" content="blanck, basic, giriş" />
    <meta name="author" content="<?php echo BRAND; ?>" />
    <?php require 'View/SharedHome/_home_head.php'; ?>
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
                <h1 class="action-title">Giriş</h1>
                <?php if (isset($_SESSION[SESSION_MASSAGE])) : ?>
                    <div class="login-message-container">
                        <span class="login-message-text"><?php echo $_SESSION[SESSION_MASSAGE]; ?></span>
                    </div>
                <?php unset($_SESSION[SESSION_MASSAGE]);
                endif; ?>
                <form action="<?php echo URL . URL_LOGIN; ?>" method="POST" id="form-home-login" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <?php if (!empty($web_data['csrf_token'])) : ?>
                        <input type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                    <?php endif; ?>
                    <?php if (!empty($web_data['redirect'])) : ?>
                        <input type="hidden" name="redirect" value="<?php echo $web_data['redirect']; ?>">
                    <?php endif; ?>
                    <div class="action-container-3">
                        <div class="action-group">
                            <?php if (!empty($web_data['email'])) : ?>
                                <input class="input-action" id="input_email" type="email" name="email" value="<?php echo $web_data['email']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input_email" type="email" name="email" autofocus>
                            <?php endif; ?>
                            <span class="input-action-label">Email</span>
                        </div>
                    </div>
                    <div class="action-container-3">
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
                    <div class="action-container-3">
                        <div class="row-space-center">
                            <label for="remember_me" class="label-checkbox">
                                <input type="checkbox" class="checkbox" id="remember_me" name="remember_me" <?php echo !empty($web_data['remember_me']) ? ' checked' : ''; ?>>
                                <span class="checkmark-filter"></span>
                                <span class="checkmark-text">Beni hatırla</span>
                            </label>
                            <a class="action-text" href="<?php echo URL . URL_FORGOT_PASSWORD; ?>"><i>Şifremi unuttum</i></a>
                        </div>
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
                        <input class="btn-action-submit" name="login-submit" type="submit" name="login" value="Giriş Yap">
                    </div>
                </form>
                <div class="action-link-container">
                    <span class="action-text">Üye değil misin? </span>
                    <a href="<?php echo URL . URL_REGISTER; ?>"><span class="action-link-text">Hesap oluştur</span></a>
                </div>
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
        document.querySelector('.btn-action-submit').addEventListener('click', (e) => {
            e.preventDefault();
            notificationClient.classList.add('hidden');
            notificationClient.classList.add('removed');
            let inputEmail = document.getElementById('input_email');
            let inputPassword = document.getElementById('input_password');
            if (inputEmail.value.trim() == '') {
                inputEmail.focus();
                setClientNotification('<?php echo ERROR_MESSAGE_EMPTY_EMAIL; ?>');
            } else if (inputPassword.value.trim() == '') {
                inputPassword.focus();
                setClientNotification('<?php echo ERROR_MESSAGE_EMPTY_PASSWORD; ?>');
            } else {
                document.querySelectorAll('.action-container-3').forEach(element => {
                    element.classList.add('disable');
                });
                document.querySelector('.action-link-container').classList.add('disable');
                document.querySelector('.action-container').classList.add('invisible');
                document.querySelector('.captcha-popup').classList.add('active');
            }
        });
        document.querySelector('.close-popup').addEventListener('click', () => {
            document.querySelectorAll('.action-container-3').forEach(element => {
                element.classList.remove('disable');
            });
            document.querySelector('.action-link-container').classList.remove('disable');
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
            document.getElementById('form-home-login').submit();
        }
    </script>
</body>

</html>