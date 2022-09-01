<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Şifremi Unuttum | <?php echo BRAND; ?></title>
    <meta name="description" content="<?php echo BRAND; ?> şifremi unuttum" />
    <meta name="keywords" content="blanck, basic, şifremi unuttum" />
    <meta name="author" content="<?php echo BRAND; ?>" />
    <?php require 'View/SharedHome/_home_head.php'; ?>
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/captcha.css">
</head>

<body>
    <div class="notification">
        <?php if (isset($_SESSION[SESSION_NOTIFICATION_NAME])) {
            echo $_SESSION[SESSION_NOTIFICATION_NAME];
            unset($_SESSION[SESSION_NOTIFICATION_NAME]);
        } ?>
    </div>
    <div class="notification-client"></div>
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
        <section class="action-section">
            <div class="action-container">
                <h1 class="forgot-password-title">Şifrenizi sıfırlamak için email adresinizi girin</h1>
                <form action="<?php echo URL . URL_FORGOT_PASSWORD; ?>" method="POST" id="form-password-reset" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <?php if (!empty($web_data['csrf_token'])) : ?>
                        <input type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                    <?php endif; ?>
                    <div class="action-container-3">
                        <div class="action-group">
                            <input class="input-action" id="input_email" type="email" name="email" autofocus>
                            <span class="input-action-label">Email</span>
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
                        <input class="btn-action-submit" name="forgot-password-submit" type="submit" value="Şifremi Sıfırla">
                    </div>
                </form>
                <div class="action-link-container">
                    <a href="<?php echo URL . URL_LOGIN; ?>"><span class="action-link-text">Giriş Yap | </span></a>
                    <a href="<?php echo URL . URL_REGISTER; ?>"><span class="action-link-text">Hesap oluştur</span></a>
                </div>
            </div>
        </section>
    </main>
    <?php require 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/loader_notification_action.js"></script>
    <script>
        const notificationClient = document.querySelector('.notification-client');
        window.addEventListener('scroll', () => {
            notificationClient.classList.toggle('sticky', window.scrollY > 0);
        });
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
            document.getElementById('form-password-reset').submit();
        }
    </script>
</body>

</html>