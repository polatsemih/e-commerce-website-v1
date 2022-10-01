<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo $web_data['profile_title'] . ' | ' . BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <main>
        <section class="profile-section container">
            <div class="row">
                <div class="nav-menu">
                    <a class="link<?php echo $web_data['profile_type'] == URL_ADMIN_PROFILE_INFORMATIONS ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_PROFILE . '/' . URL_ADMIN_PROFILE_INFORMATIONS; ?>">İsmimi Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_ADMIN_PROFILE_PASSWORD ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_PROFILE . '/' . URL_ADMIN_PROFILE_PASSWORD; ?>">Şifremi Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_ADMIN_PROFILE_PHOTO ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_PROFILE . '/' . URL_ADMIN_PROFILE_PHOTO; ?>">Profil Fotoğrafımı Değiştir</a>
                </div>
                <div class="profile-container">
                    <?php if ($web_data['profile_type'] == URL_ADMIN_PROFILE_INFORMATIONS) : ?>
                        <h1 class="title">Hesabım</h1>
                        <form id="form-user-update" action="<?php echo URL . URL_ADMIN_PROFILE_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-row">
                                <span class="label">İsim</span>
                                <input class="input" type="text" id="first-name" name="user_first_name" value="<?php echo $web_data['authenticated_admin']['first_name']; ?>" placeholder="İsminizi Girin" autofocus>
                            </div>
                            <div class="form-row">
                                <span class="label">Soy İsim</span>
                                <input class="input" type="text" id="last-name" name="user_last_name" value="<?php echo $web_data['authenticated_admin']['last_name']; ?>" placeholder="Soy İsminizi Girin">
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" id="btn-user-update" type="submit" value="Bilgilerimi Güncelle" title="Bilgilerimi Güncelle">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_ADMIN_PROFILE_PASSWORD) : ?>
                        <h1 class="title">Şifremi Değiştir</h1>
                        <form id="form-password-update" action="<?php echo URL . URL_ADMIN_PASSWORD_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <div class="form-update-wrapper">
                                <div class="form-row form-password-row relative-password-row">
                                    <span class="label">Güncel Şifre</span>
                                    <input class="input" id="input-oldpassword" type="password" name="user_old_password" placeholder="Güncel Şifrenizi Girin" autofocus>
                                    <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                                </div>
                                <div class="form-row form-password-row">
                                    <span class="input-message" id="oldpassword-message"></span>
                                </div>
                                <div class="form-row form-password-row relative-password-row">
                                    <span class="label">Yeni Şifre</span>
                                    <input class="input" id="input-password" type="password" name="user_new_password" placeholder="Yeni Şifrenizi Girin">
                                    <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                                </div>
                                <div class="form-row form-password-row">
                                    <span class="input-message" id="password-message"></span>
                                </div>
                                <div class="form-row form-password-row relative-password-row">
                                    <span class="label">Yeni Şifre Tekrar</span>
                                    <input class="input" id="input-repassword" type="password" name="user_new_re_password" placeholder="Yeni Şifrenizi Tekrar Girin">
                                    <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                                </div>
                                <div class="form-row form-password-row">
                                    <span class="input-message" id="repassword-message"></span>
                                </div>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" id="btn-password-update" type="submit" value="Şifremi Değiştir" title="Şifremi Değiştir">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_ADMIN_PROFILE_PHOTO) : ?>
                        <h1 class="title">Profil Fotoğrafımı Değiştir</h1>
                        <form action="<?php echo URL . URL_ADMIN_PROFILE_PHOTO_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate enctype="multipart/form-data">
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <label for="upload-image-1" class="image-label">
                                <img class="profile-image" src="<?php echo URL . 'assets/images/users/' . $web_data['authenticated_admin']['profile_image_path'] . '/' . $web_data['authenticated_admin']['profile_image']; ?>" alt="Profil Fotoğrafı">
                                <div class="upload-image-icon-container">
                                    <i class="fas fa-camera upload-image-icon"></i>
                                </div>
                            </label>
                            <input id="upload-image-1" class="input-user-image" type="file" name="user_image" accept=".jpg, .jpeg, .png">
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning mt-2" type="submit" name="update_profile_photo" value="Profil Fotoğrafını Güncelle" title="Profil Fotoğrafını Güncelle">
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
    <?php if ($web_data['profile_type'] == URL_ADMIN_PROFILE_PASSWORD) : ?>
        <script>
            const eyeBtns = document.querySelectorAll('.btn-action-password');
            eyeBtns.forEach((eyeBtn) => {
                eyeBtn.addEventListener('click', () => {
                    let inputPassword = eyeBtn.parentElement.children[1];
                    if (inputPassword.type === 'password') {
                        inputPassword.type = 'text';
                        eyeBtn.classList.remove('fa-eye-slash');
                        eyeBtn.classList.add('fa-eye');
                    } else {
                        inputPassword.type = 'password';
                        eyeBtn.classList.remove('fa-eye');
                        eyeBtn.classList.add('fa-eye-slash');
                    }
                });
            });
        </script>
    <?php endif; ?>
    <?php if ($web_data['profile_type'] == URL_ADMIN_PROFILE_PHOTO) : ?>
        <script>
            document.querySelector('.input-user-image').onchange = function() {
                var user_image = this.files[0];
                var reader = new FileReader();
                reader.readAsDataURL(user_image);
                reader.onload = function(e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function(e2) {
                        var created_image = document.createElement('canvas');
                        created_image.width = 200;
                        created_image.height = 200;
                        var context = created_image.getContext('2d');
                        context.drawImage(e2.target, 0, 0, created_image.width, created_image.height);
                        document.querySelector('.profile-image').src = context.canvas.toDataURL('image/png', 1);
                    }
                }
            };
        </script>
    <?php endif; ?>
    <script>
        $(document).ready(function() {
            $('#btn-hamburger').click(function() {
                $.ajax({
                    url: '<?php echo URL . URL_ADMIN_MENU; ?>',
                    type: 'POST'
                });
            });
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
            <?php if ($web_data['profile_type'] == URL_ADMIN_PROFILE_INFORMATIONS) : ?>
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
                $('.mfa-checkbox').change(function() {
                    if ($('.loader-wrapper').hasClass('hidden')) {
                        $('.loader-wrapper').removeClass('hidden');
                    }
                    if (!$('body').hasClass('noscroll')) {
                        $('body').addClass('noscroll');
                    }
                    $('#form-two-fa').submit();
                });
            <?php endif; ?>
            <?php if ($web_data['profile_type'] == URL_ADMIN_PROFILE_PASSWORD) : ?>
                const inputOldPassword = $('#input-oldpassword');
                const oldPasswordMessage = $('#oldpassword-message');
                const inputPassword = $('#input-password');
                const passwordMessage = $('#password-message');
                const inputRepassword = $('#input-repassword');
                const repasswordMessage = $('#repassword-message');
                $('#btn-password-update').click(function(e) {
                    e.preventDefault();
                    if (inputOldPassword.val() == '') {
                        inputOldPassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?></span></div>');
                    } else if (inputPassword.val() == '') {
                        inputPassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?></span></div>');
                    } else if ($.trim(inputPassword.val()).indexOf(' ') >= 0) {
                        inputPassword.focus();
                        setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD; ?></span></div>');
                    } else if ($.trim(inputPassword.val()).length < <?php echo PASSWORD_MIN_LIMIT; ?>) {
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
                        if ($('.loader-wrapper').hasClass('hidden')) {
                            $('.loader-wrapper').removeClass('hidden');
                        }
                        if (!$('body').hasClass('noscroll')) {
                            $('body').addClass('noscroll');
                        }
                        $('#form-password-update').submit();
                    }
                });
                inputOldPassword.keyup(function(e) {
                    if (inputOldPassword.val() == '') {
                        oldPasswordMessage.text('<?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?>');
                    } else {
                        oldPasswordMessage.text('');
                    }
                });
                inputPassword.keyup(function(e) {
                    if (inputPassword.val() == '') {
                        passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?>');
                    } else if ($.trim(inputPassword.val()).indexOf(' ') >= 0) {
                        passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD; ?>');
                    } else if ($.trim(inputPassword.val()).length < <?php echo PASSWORD_MIN_LIMIT; ?>) {
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
            <?php endif; ?>
        });
    </script>
</body>

</html>