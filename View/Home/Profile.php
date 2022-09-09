<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo $web_data['profile_title'] . ' | ' . BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="profile-section container">
            <div class="row">
                <div class="nav-menu">
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_INFORMATIONS ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_INFORMATIONS; ?>">Profil Bilgilerim</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_PASSWORD ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_PASSWORD; ?>">Şifremi Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_EMAIL ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_EMAIL; ?>">Emailimi Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_PHONE ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_PHONE; ?>">Telefon Numaramı Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_PHOTO ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_PHOTO; ?>">Profil Fotoğrafımı Değiştir</a>
                    <a class="link<?php echo $web_data['profile_type'] == URL_PROFILE_ORDERS ? ' active' : ''; ?>" href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_ORDERS; ?>">Siparişlerim</a>
                </div>
                <div class="profile-container">
                    <?php if ($web_data['profile_type'] == URL_PROFILE_INFORMATIONS) : ?>
                        <div class="row-space">
                            <div class="left">
                                <h1 class="title mb">Profil Bilgilerim</h1>
                            </div>
                            <?php if ($web_data['authenticated_user']['user_delete_able'] == 1) : ?>
                                <div class="right">
                                    <form action="<?php echo URL . URL_PROFILE_DELETE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                                        <?php if (!empty($web_data['form_token'])) : ?>
                                            <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                                        <?php endif; ?>
                                        <input type="hidden" name="user_id" value="<?php echo $web_data['authenticated_user']['id']; ?>">
                                        <input class="btn-danger mb" type="submit" name="submit_user_delete" value="Hesabımı Sil" title="Hesabımı Sil">
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        <form action="<?php echo URL . URL_PROFILE_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <input type="hidden" name="user_id" value="<?php echo $web_data['authenticated_user']['id']; ?>">
                            <div class="form-row">
                                <span class="label">İsim</span>
                                <input class="input" type="text" name="user_first_name" value="<?php echo $web_data['authenticated_user']['first_name']; ?>" placeholder="İsminizi Girin">
                            </div>
                            <div class="form-row">
                                <span class="label">Soy İsim</span>
                                <input class="input" type="text" name="user_last_name" value="<?php echo $web_data['authenticated_user']['last_name']; ?>" placeholder="Soy İsminizi Girin">
                            </div>
                            <div class="form-row">
                                <span class="label-textarea">Adres</span>
                                <textarea class="textarea" name="user_address" placeholder="Adresinizi Girin"><?php echo $web_data['authenticated_user']['address']; ?></textarea>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" type="submit" name="submit_user_update" value="Bilgilerimi Güncelle" title="Bilgilerimi Güncelle">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_PASSWORD) : ?>
                        <h1 class="title">Şifremi Değiştir</h1>
                        <form action="<?php echo URL . URL_PASSWORD_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <input type="hidden" name="user_id" value="<?php echo $web_data['authenticated_user']['id']; ?>">
                            <div class="form-update-wrapper">
                                <div class="form-row">
                                    <span class="label">Güncel Şifre</span>
                                    <input class="input" type="password" name="user_old_password" placeholder="Güncel Şifrenizi Girin">
                                </div>
                                <div class="form-row">
                                    <span class="label">Yeni Şifre</span>
                                    <input class="input" type="password" name="user_new_password" placeholder="Yeni Şifrenizi Girin">
                                </div>
                                <div class="form-row">
                                    <span class="label">Yeni Şifre Tekrar</span>
                                    <input class="input" type="password" name="user_new_re_password" placeholder="Yeni Şifrenizi Tekrar Girin">
                                </div>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" type="submit" name="submit_password_update" value="Şifremi Değiştir" title="Şifremi Değiştir">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_EMAIL) : ?>
                        <h1 class="title">Emailimi Değiştir</h1>
                        <form action="<?php echo URL . URL_EMAIL_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <input type="hidden" name="user_id" value="<?php echo $web_data['authenticated_user']['id']; ?>">
                            <div class="form-update-wrapper">
                                <div class="form-row">
                                    <span class="label">Email</span>
                                    <input class="input" type="email" name="user_email" value="<?php echo $web_data['authenticated_user']['email']; ?>" placeholder="Yeni Emailinizi Girin">
                                </div>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" type="submit" name="submit_email_update" value="Emailimi Değiştir" title="Emailimi Değiştir">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_PHONE) : ?>
                        <h1 class="title">Telefon Numaramı Değiştir</h1>
                        <form action="<?php echo URL . URL_EMAIL_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input class="input-token" type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <input type="hidden" name="user_id" value="<?php echo $web_data['authenticated_user']['id']; ?>">
                            <div class="form-update-wrapper">
                                <div class="form-row">
                                    <span class="label">Telefon Numarası</span>
                                    <input class="input" type="tel" name="user_phone_number" value="<?php echo $web_data['authenticated_user']['phone_number']; ?>" placeholder="Yeni Telefon Numaranızı Girin">
                                </div>
                            </div>
                            <div class="row-space">
                                <div class="right">
                                    <input class="btn-warning" type="submit" name="submit_phone_update" value="Telefon Numaramı Değiştir" title="Telefon Numaramı Değiştir">
                                </div>
                            </div>
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_PHOTO) : ?>
                        <h1 class="title">Profil Fotoğrafımı Değiştir</h1>
                        <form action="<?php echo URL . URL_PROFILE_PHOTO_UPDATE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate enctype="multipart/form-data">
                            <img src="<?php echo URL . 'assets/images/users/' . $web_data['authenticated_user']['profile_image_path'] . '/' . $web_data['authenticated_user']['profile_image']; ?>" alt="Profil Fotoğrafı">
                        </form>
                    <?php elseif ($web_data['profile_type'] == URL_PROFILE_ORDERS) : ?>
                        <h1 class="title">Siparişlerim</h1>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <?php if (!empty($web_data['cookie_cart'])) : ?>
        <script src="<?php echo URL; ?>assets/js/header_cart.js"></script>
    <?php endif; ?>
    <script>
        $(document).ready(function() {
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
        });
    </script>
</body>

</html>