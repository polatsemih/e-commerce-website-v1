<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Kullanıcı Detayları - Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <section class="item-create-section container">
        <div class="row">
            <div class="left">
                <h1 class="title mb">Kullanıcı Detayları</h1>
            </div>
            <?php if (!empty($web_data['user'])) : ?>
                <div class="right">
                    <div class="row">
                        <a class="btn-user-past-orders" href="<?php echo URL . URL_ADMIN_USER_COMMENT . '/' . $web_data['user']['id']; ?>" title="Ürüne Yapılan Yorumlar">Yorumlar</a>
                        <a class="btn-user-past-orders" href="<?php echo URL . URL_ADMIN_USER_PAST_ORDERS . '/' . $web_data['user']['id']; ?>" title="Kullanıcının Geçmiş Siparişleri">Geçmiş Siparişler</a>
                        <form action="<?php echo URL . URL_ADMIN_USER_BLOCK; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <input type="hidden" name="id" value="<?php echo $web_data['user']['id']; ?>">
                            <input class="btn-user-block" type="submit" name="submit_block_user" value="<?php echo $web_data['user']['is_user_blocked'] == 0 ? 'Engelle' : 'Engellemeyi Kaldır'; ?>" title="Kullanıcıyı engellemek, kullanıcının yorum yapmasını ve alışveriş yapmasını engeller.">
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($web_data['user'])) : ?>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">İsim</span>
                </div>
                <div class="col-input">
                    <span class="create-input span-input"><?php echo $web_data['user']['first_name']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Soy İsim</span>
                </div>
                <div class="col-input">
                    <span class="create-input span-input"><?php echo $web_data['user']['last_name']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">T.C. Kimlik Numarası</span>
                </div>
                <div class="col-input">
                    <span class="create-input span-input"><?php echo $web_data['user']['identity_number']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Profil Fotoğrafı</span>
                </div>
                <div class="col-input">
                    <span class="create-input span-input img-input">
                        <img class="input-img" src="<?php echo URL . 'assets/images/users/' . $web_data['user']['profile_image_path'] . '/' . $web_data['user']['profile_image']; ?>" alt="<?php echo $web_data['user']['first_name'] . ' ' . $web_data['user']['last_name']; ?>">
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Email</span>
                </div>
                <div class="col-input">
                    <span class="create-input span-input"><?php echo $web_data['user']['email']; ?><span>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Email Onay</span>
                </div>
                <div class="col-input">
                    <div class="checkbox-wrapper create-checkbox">
                        <input type="checkbox" class="checkbox" <?php echo !empty($web_data['user']['email_confirmed']) ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Telefon Numarası</span>
                </div>
                <div class="col-input">
                    <span class="create-input span-input"><?php echo $web_data['user']['phone_number']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Telefon Numarası Onay</span>
                </div>
                <div class="col-input">
                    <div class="checkbox-wrapper create-checkbox">
                        <input type="checkbox" class="checkbox" <?php echo !empty($web_data['user']['phone_number_confirmed']) ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">İki Aşamalı Doğrulama</span>
                </div>
                <div class="col-input">
                    <div class="checkbox-wrapper create-checkbox">
                        <input type="checkbox" class="checkbox" <?php echo !empty($web_data['user']['two_fa_enable']) ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Alışveriş Yapmış</span>
                </div>
                <div class="col-input">
                    <div class="checkbox-wrapper create-checkbox">
                        <input type="checkbox" class="checkbox" <?php echo !empty($web_data['user']['is_user_shopped']) ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Kampanya Maili İzni</span>
                </div>
                <div class="col-input">
                    <div class="checkbox-wrapper create-checkbox">
                        <input type="checkbox" class="checkbox" <?php echo !empty($web_data['user']['is_user_shopped']) ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <label class="create-label">Profil Son Güncelleme Tarihi</label>
                </div>
                <div class="col-input">
                    <?php if (!empty($web_data['user']['date_last_profile_update'])) : ?>
                        <span class="create-input span-input"><?php echo date('d/m/Y H:i:s', strtotime($web_data['user']['date_last_profile_update'])); ?></span>
                    <?php else : ?>
                        <span class="create-input span-input">-</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <label class="create-label">Son Giriş Tarihi</label>
                </div>
                <div class="col-input">
                    <span class="create-input span-input"><?php echo date('d/m/Y H:i:s', strtotime($web_data['user']['date_last_login'])); ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Hatalı Erişim</span>
                </div>
                <div class="col-input">
                    <span class="create-input span-input"><?php echo $web_data['user']['fail_access_count']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <label class="create-label">Son Hatalı Erişim</label>
                </div>
                <div class="col-input">
                    <?php if (!empty($web_data['user']['date_last_fail_access_attempt'])) : ?>
                        <span class="create-input span-input"><?php echo date('d/m/Y H:i:s', strtotime($web_data['user']['date_last_fail_access_attempt'])); ?></span>
                    <?php else : ?>
                        <span class="create-input span-input">-</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <label class="create-label">Kayıt Tarihi</label>
                </div>
                <div class="col-input">
                    <span class="create-input span-input"><?php echo date('d/m/Y H:i:s', strtotime($web_data['user']['date_registered'])); ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Kayıt İptal</span>
                </div>
                <div class="col-input">
                    <div class="checkbox-wrapper create-checkbox">
                        <input type="checkbox" class="checkbox" <?php echo !empty($web_data['user']['is_register_canceled']) ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <label class="create-label">Kayıt İptal Tarihi</label>
                </div>
                <div class="col-input">
                    <?php if (!empty($web_data['user']['date_register_canceled'])) : ?>
                        <span class="create-input span-input"><?php echo date('d/m/Y H:i:s', strtotime($web_data['user']['date_register_canceled'])); ?></span>
                    <?php else : ?>
                        <span class="create-input span-input">-</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Hesap Kaldırılabilir</span>
                </div>
                <div class="col-input">
                    <div class="checkbox-wrapper create-checkbox">
                        <input type="checkbox" class="checkbox" <?php echo !empty($web_data['user']['user_delete_able']) ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Hesap Kaldırılmış</span>
                </div>
                <div class="col-input">
                    <div class="checkbox-wrapper create-checkbox">
                        <input type="checkbox" class="checkbox" <?php echo !empty($web_data['user']['is_user_deleted']) ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <label class="create-label">Hesap Kaldırılma Tarihi</label>
                </div>
                <div class="col-input">
                    <?php if (!empty($web_data['user']['date_user_deleted'])) : ?>
                        <span class="create-input span-input"><?php echo date('d/m/Y H:i:s', strtotime($web_data['user']['date_user_deleted'])); ?></span>
                    <?php else : ?>
                        <span class="create-input span-input">-</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <span class="create-label">Hesap Bloklanmış</span>
                </div>
                <div class="col-input">
                    <div class="checkbox-wrapper create-checkbox">
                        <input type="checkbox" class="checkbox" <?php echo !empty($web_data['user']['is_user_blocked']) ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-label">
                    <label class="create-label">Hesap Bloklanma Tarihi</label>
                </div>
                <div class="col-input">
                    <?php if (!empty($web_data['user']['date_user_blocked'])) : ?>
                        <span class="create-input span-input"><?php echo date('d/m/Y H:i:s', strtotime($web_data['user']['date_user_blocked'])); ?></span>
                    <?php else : ?>
                        <span class="create-input span-input">-</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php else : ?>
            <span class="not-found">Kullanıcı Bulunamadı</span>
        <?php endif; ?>
    </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
    <script>
        $(document).ready(function() {
            $('#btn-hamburger').click(function() {
                $.ajax({
                    url: '<?php echo URL . URL_ADMIN_MENU; ?>',
                    type: 'POST'
                });
            });
        });
    </script>
</body>

</html>