<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo $web_data['statistics_title'] . ' - Yönetici | ' . BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <section class="statistics-section container">
        <div class="row">
            <div class="nav-menu">
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_PAGE ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_PAGE; ?>">Görüntülenen Sayfalar</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_USER ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_USER; ?>">Kullanıcılar</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_ERROR ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_ERROR; ?>">Sistem Hataları</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_LOGIN_ACCOUNT ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_LOGIN_ACCOUNT; ?>">Hesaba Girişler</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_LOGIN ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_LOGIN; ?>">Hatalı Giriş Denemeleri</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_EMAIL ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_EMAIL; ?>">Sistem Emailleri</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_EMAIL_ORDER ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_EMAIL_ORDER; ?>">Sipariş Emailleri</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_CAPTCHA ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_CAPTCHA; ?>">Robot Testi</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_CAPTCHA_TIMEOUT ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_CAPTCHA_TIMEOUT; ?>">Robot Testi Kısıtlananlar</a>
            </div>
            <div class="statistics-container">
                <?php if ($web_data['statistics_type'] == URL_ADMIN_LOGS_PAGE) : ?>
                    <h1 class="title mb">Görüntülenen Sayfalar</h1>
                    <h2 class="second-title">Ana Sayfalar</h2>
                    <div class="row-log">
                        <?php if (isset($web_data['home_index']) && isset($web_data['home_index_all'])) : ?>
                            <div class="log-box home">
                                <span class="text text-1">Ana Sayfa</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_index']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_index_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['home_cart']) && isset($web_data['home_cart_all'])) : ?>
                            <div class="log-box home">
                                <span class="text text-1">Sepet</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_cart']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_cart_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['home_favorites']) && isset($web_data['home_favorites_all'])) : ?>
                            <div class="log-box home">
                                <span class="text text-1">Favoriler</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_favorites']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_favorites_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['home_contact']) && isset($web_data['home_contact_all'])) : ?>
                            <div class="log-box home">
                                <span class="text text-1">İletişim</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_contact']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_contact_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h2 class="second-title">Sipariş Sayfaları</h2>
                    <div class="row-log">
                        <?php if (isset($web_data['home_orderinitialize_name']) && isset($web_data['home_orderinitialize_name_all'])) : ?>
                            <div class="log-box order">
                                <span class="text text-1">Sipariş İsim Tanımlama</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_name']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_name_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['home_orderinitialize_identity']) && isset($web_data['home_orderinitialize_identity_all'])) : ?>
                            <div class="log-box order">
                                <span class="text text-1">Sipariş Kimlik Numarası Tanımlama</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_identity']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_identity_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['home_orderinitialize_address']) && isset($web_data['home_orderinitialize_address_all'])) : ?>
                            <div class="log-box order">
                                <span class="text text-1">Sipariş Adres Tanımlama</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_address']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_address_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['home_orderinitialize_credit']) && isset($web_data['home_orderinitialize_credit_all'])) : ?>
                            <div class="log-box order">
                                <span class="text text-1">Sipariş Kredi Kart</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_credit']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_credit_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['home_orderinitialize_3D']) && isset($web_data['home_orderinitialize_3D_all'])) : ?>
                            <div class="log-box order">
                                <span class="text text-1">Sipariş 3D</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_3D']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderinitialize_3D_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['home_orderpayment']) && isset($web_data['home_orderpayment_all'])) : ?>
                            <div class="log-box order">
                                <span class="text text-1">Sipariş Ödeme Sonuç</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderpayment']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_orderpayment_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h2 class="second-title">Aksiyon Sayfaları</h2>
                    <div class="row-log">
                        <?php if (isset($web_data['action_login']) && isset($web_data['action_login_all'])) : ?>
                            <div class="log-box action">
                                <span class="text text-1">Giriş</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_login']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_login_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['action_register']) && isset($web_data['action_register_all'])) : ?>
                            <div class="log-box action">
                                <span class="text text-1">Kayıt</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_register']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_register_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['action_reset_password']) && isset($web_data['action_reset_password_all'])) : ?>
                            <div class="log-box action">
                                <span class="text text-1">Şifremi Unuttum Güncelleme</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_reset_password']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_reset_password_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['action_twofa']) && isset($web_data['action_twofa_all'])) : ?>
                            <div class="log-box action">
                                <span class="text text-1">İki Aşamalı Doğrulama</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_twofa']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_twofa_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['action_register_confirm']) && isset($web_data['action_register_confirm_all'])) : ?>
                            <div class="log-box action">
                                <span class="text text-1">Kayıt Onay</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_register_confirm']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_register_confirm_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['action_register_cancel']) && isset($web_data['action_register_cancel_all'])) : ?>
                            <div class="log-box action">
                                <span class="text text-1">Kayıt İptal</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_register_cancel']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_register_cancel_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['action_admin_login']) && isset($web_data['action_admin_login_all'])) : ?>
                            <div class="log-box action">
                                <span class="text text-1">Yönetici Giriş</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_admin_login']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['action_admin_login_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h2 class="second-title">Profil Sayfaları</h2>
                    <div class="row-log">
                        <?php if (isset($web_data['profile_count']) && isset($web_data['profile_count_all'])) : ?>
                            <?php foreach ($web_data['profile_count'] as $key => $profile_count) : ?>
                                <div class="log-box profile">
                                    <span class="text text-1"><?php echo $key; ?></span>
                                    <div class="view-eye-row">
                                        <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $profile_count; ?></span>
                                        <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['profile_count_all'][$key]; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (isset($web_data['home_emailupdateconfirm']) && isset($web_data['home_emailupdateconfirm_all'])) : ?>
                            <div class="log-box profile">
                                <span class="text text-1">Email Güncelleme</span>
                                <div class="view-eye-row">
                                    <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_emailupdateconfirm']; ?></span>
                                    <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['home_emailupdateconfirm_all']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h2 class="second-title">Sözleşme Sayfaları</h2>
                    <div class="row-log">
                        <?php if (isset($web_data['agreements_count']) && isset($web_data['agreements_count_all'])) : ?>
                            <?php foreach ($web_data['agreements_count'] as $key => $agreements_count) : ?>
                                <div class="log-box agreements">
                                    <span class="text text-1"><?php echo $key; ?></span>
                                    <div class="view-eye-row">
                                        <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $agreements_count; ?></span>
                                        <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['agreements_count_all'][$key]; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <h2 class="second-title">Kategori Sayfaları</h2>
                    <div class="row-log">
                        <?php if (isset($web_data['genders_count']) && isset($web_data['genders_count_all'])) : ?>
                            <?php foreach ($web_data['genders_count'] as $key => $genders_count) : ?>
                                <div class="log-box item">
                                    <span class="text text-1"><?php echo $key; ?></span>
                                    <div class="view-eye-row">
                                        <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $genders_count; ?></span>
                                        <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['genders_count_all'][$key]; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <h2 class="second-title">Ürün Sayfaları</h2>
                    <div class="row-log">
                        <?php if (isset($web_data['items_count']) && isset($web_data['items_count_all'])) : ?>
                            <?php foreach ($web_data['items_count'] as $key => $items_count) : ?>
                                <div class="log-box item">
                                    <span class="text text-1"><?php echo $key; ?></span>
                                    <div class="view-eye-row">
                                        <span class="text" title="Farklı Görüntülenme"><i class="far fa-eye"></i><?php echo $items_count; ?></span>
                                        <span class="text" title="Toplam Görüntülenme"><i class="far fa-eye"></i><?php echo $web_data['items_count_all'][$key]; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_USER) : ?>
                    <h1 class="title mb">Kullanıcılar</h1>
                    <div class="row-log">
                        <?php if (isset($web_data['user_count'])) : ?>
                            <div class="log-box home">
                                <span class="text text-1">Kayıtlı Kullanıcılar</span>
                                <div class="view-eye-row">
                                    <span class="text text-2" title="Toplam Kullanıcı Sayısı"><i class="far fa-eye"></i><?php echo $web_data['user_count']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($web_data['admin_count'])) : ?>
                            <div class="log-box home">
                                <span class="text text-1">Kayıtlı Yöneticilier</span>
                                <div class="view-eye-row">
                                    <span class="text text-2" title="Toplam Yönetici Sayısı"><i class="far fa-eye"></i><?php echo $web_data['admin_count']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_ERROR) : ?>
                    <h1 class="title mb">Sistem Hataları</h1>
                    <?php if (!empty($web_data['log_error'])) : ?>
                        <div class="table-header">
                            <span class="th box-s theme">Kullanıcı IP</span>
                            <span class="th box-m theme">Mesaj</span>
                            <span class="th box-s theme">Tarih</span>
                        </div>
                        <?php foreach ($web_data['log_error'] as $log_error) : ?>
                            <div class="table-header">
                                <span class="th box-s center"><?php echo $log_error['user_ip']; ?></span>
                                <span class="th box-m"><?php echo $log_error['error_message']; ?></span>
                                <span class="th box-s center"><?php echo date('d/m/Y H:i:s', strtotime($log_error['date_error_occurred'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_LOGIN_ACCOUNT) : ?>
                    <h1 class="title mb">Hesaba Girişler</h1>
                    <?php if (!empty($web_data['log_login'])) : ?>
                        <div class="table-header">
                            <span class="th box-ms theme">Kullanıcı ID</span>
                            <span class="th box-ms theme">Kullanıcı IP</span>
                            <span class="th box-ms theme">Başarılı Mı?</span>
                            <span class="th box-ms theme">Tarih</span>
                        </div>
                        <?php foreach ($web_data['log_login'] as $log_login) : ?>
                            <div class="table-header">
                                <span class="th box-ms center hovered-id-container">
                                    <i class="fas fa-info"></i>
                                    <span class="hovered-id">
                                        <?php echo $log_login['user_id']; ?>
                                        <span class="hovered-id-triangle"></span>
                                    </span>
                                </span>
                                <span class="th box-ms center"><?php echo $log_login['user_ip']; ?></span>
                                <span class="th box-ms center">
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" class="checkbox" <?php echo $log_login['login_success'] == 1 ? ' checked' : ''; ?> disabled>
                                        <span class="checkmark"></span>
                                    </div>
                                </span>
                                <span class="th box-ms center"><?php echo date('d/m/Y H:i:s', strtotime($log_login['date_login'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_LOGIN) : ?>
                    <h1 class="title mb">Hatalı Giriş Denemeleri</h1>
                    <?php if (!empty($web_data['log_login_fail'])) : ?>
                        <div class="table-header">
                            <span class="th box-l theme">Kullanıcı IP</span>
                            <span class="th box-l theme">Tarih</span>
                        </div>
                        <?php foreach ($web_data['log_login_fail'] as $log_login_fail) : ?>
                            <div class="table-header">
                                <span class="th box-l center"><?php echo $log_login_fail['user_ip']; ?></span>
                                <span class="th box-l center"><?php echo date('d/m/Y H:i:s', strtotime($log_login_fail['date_fail_login'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_EMAIL) : ?>
                    <h1 class="title mb">Sistem Emailleri</h1>
                    <?php if (!empty($web_data['log_email_sent'])) : ?>
                        <div class="table-header">
                            <span class="th box-xxs theme">Kullanıcı ID</span>
                            <span class="th box-xxs theme">Kullanıcı IP</span>
                            <span class="th box-xys theme">Tipi</span>
                            <span class="th box-xxs theme">Tarih</span>
                        </div>
                        <?php foreach ($web_data['log_email_sent'] as $log_email_sent) : ?>
                            <div class="table-header">
                                <span class="th box-xxs center hovered-id-container">
                                    <i class="fas fa-info"></i>
                                    <span class="hovered-id">
                                        <?php echo $log_email_sent['user_id']; ?>
                                        <span class="hovered-id-triangle"></span>
                                    </span>
                                </span>
                                <span class="th box-xxs center"><?php echo $log_email_sent['user_ip']; ?></span>
                                <span class="th box-xys center"><?php echo $log_email_sent['email_type']; ?></span>
                                <span class="th box-xxs center"><?php echo date('d/m/Y H:i:s', strtotime($log_email_sent['date_email_sent'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_EMAIL_ORDER) : ?>
                    <h1 class="title mb">Sipariş Emailleri</h1>
                    <?php if (!empty($web_data['log_order_email'])) : ?>
                        <div class="table-header">
                            <span class="th box-ms theme">Kime</span>
                            <span class="th box-ms theme">Mesaj</span>
                            <span class="th box-ms theme">Kargo Numarası</span>
                            <span class="th box-ms theme">Tarih</span>
                        </div>
                        <?php foreach ($web_data['log_order_email'] as $log_order_email) : ?>
                            <div class="table-header">
                                <span class="th box-ms center"><?php echo $log_order_email['email_to']; ?></span>
                                <span class="th box-ms center"><?php echo $log_order_email['email_message']; ?></span>
                                <span class="th box-ms center"><?php echo $log_order_email['email_shipping_number']; ?></span>
                                <span class="th box-ms center"><?php echo date('d/m/Y H:i:s', strtotime($log_order_email['date_email_sent'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_CAPTCHA) : ?>
                    <h1 class="title mb">Robot Testi</h1>
                    <?php if (!empty($web_data['log_captcha'])) : ?>
                        <div class="table-header">
                            <span class="th box-ms theme">Kullanıcı ID</span>
                            <span class="th box-ms theme">Başarı</span>
                            <span class="th box-ms theme">Kazanç</span>
                            <span class="th box-ms theme">Tarih</span>
                        </div>
                        <?php foreach ($web_data['log_captcha'] as $log_captcha) : ?>
                            <div class="table-header">
                                <span class="th box-ms center"><?php echo $log_captcha['user_ip']; ?></span>
                                <span class="th box-ms center">
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" class="checkbox" <?php echo $log_captcha['success'] == 1 ? ' checked' : ''; ?> disabled>
                                        <span class="checkmark"></span>
                                    </div>
                                </span>
                                <span class="th box-ms center">
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" class="checkbox" <?php echo $log_captcha['credit'] == 1 ? ' checked' : ''; ?> disabled>
                                        <span class="checkmark"></span>
                                    </div>
                                </span>
                                <span class="th box-ms center"><?php echo date('d/m/Y H:i:s', strtotime($log_captcha['date_captcha_used'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_CAPTCHA_TIMEOUT) : ?>
                    <h1 class="title mb">Robot Testi Kısıtlananlar</h1>
                    <?php if (!empty($web_data['log_captcha_timeout'])) : ?>
                        <div class="table-header">
                            <span class="th box-xs theme">Kullanıcı ID</span>
                            <span class="th box-xs theme">Hatalı Deneme</span>
                            <span class="th box-xs theme">Toplam Hatalı Deneme</span>
                            <span class="th box-xs theme">Kısıtlama Bitiş Tarihi</span>
                            <span class="th box-xs theme">Oluşturulma Tarihi</span>
                        </div>
                        <?php foreach ($web_data['log_captcha_timeout'] as $log_captcha_timeout) : ?>
                            <div class="table-header">
                                <span class="th box-xs center"><?php echo $log_captcha_timeout['user_ip']; ?></span>
                                <span class="th box-xs center"><?php echo $log_captcha_timeout['captcha_error_count']; ?></span>
                                <span class="th box-xs center"><?php echo $log_captcha_timeout['captcha_total_error_count']; ?></span>
                                <span class="th box-xs center"><?php echo date('d/m/Y H:i:s', strtotime($log_captcha_timeout['date_captcha_timeout_expiry'])); ?></span>
                                <span class="th box-xs center"><?php echo date('d/m/Y H:i:s', strtotime($log_captcha_timeout['date_captcha_timeout_created'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
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