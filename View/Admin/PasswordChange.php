<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title>Şifre Güncelleme | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <div class="row-space">
                    <div class="col-3">
                        <nav>
                        <ul class="nav-profile">
                            <span class="nav-profile-title">Profil Ayarları</span>
                            <li><a class="profile-link <?php echo (isset($data['selected_link']) && $data['selected_link'] === 'Profile') ? 'profile-link-selected' : ''; ?>" href="<?php echo URL; ?>/AdminController/Profile">Profil Bilgilerini Güncelle</a></li>
                                <li><a class="profile-link <?php echo (isset($data['selected_link']) && $data['selected_link'] === 'ProfilePhotoChange') ? 'profile-link-selected' : ''; ?>" href="<?php echo URL; ?>/AdminController/ProfilePhotoChange">Profil Fotoğrafını Değiştir</a></li>
                                <li><a class="profile-link <?php echo (isset($data['selected_link']) && $data['selected_link'] === 'PasswordChange') ? 'profile-link-selected' : ''; ?>" href="<?php echo URL; ?>/AdminController/PasswordChange">Şifre Değiştir</a></li>
                        </ul>
                        </nav>
                    </div>
                    <div class="col-9">
                        <div class="row-space-center">
                            <div class="row-left">
                                <h2 class="admin-panel-title">Yönetici Şifresini Değiştir</h2>
                            </div>
                        </div>
                        <form action="<?php echo URL; ?>/AdminController/PasswordChange" method="POST" autocomplete="off" novalidate>
                            <table class="mt-2 mb-2">
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;" class="table-align-left text-theme-primary">Güncel Kullanılan Şifre</td>
                                        <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'current_password'): ?>
                                            <td style="width: 50%;" class="password-row">
                                                <input type="password" class="form-input input-danger input-danger input-password" name="current_password" placeholder="Şifrenizi Girin" autofocus>
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'wrong_current_password'): ?>
                                            <td style="width: 50%;" class="password-row">
                                                <input type="password" class="form-input input-danger input-password" name="current_password" placeholder="Şifrenizi Girin">
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php elseif (!empty($data['user']['current_password'])): ?>
                                            <td style="width: 50%;" class="password-row">
                                                <input type="password" class="form-input input-password" name="current_password" value="<?php echo $data['user']['current_password'] ?>" placeholder="Şifrenizi Girin">
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php else: ?>
                                            <td style="width: 50%;" class="password-row">
                                                <input type="password" class="form-input input-password" name="current_password" placeholder="Şifrenizi Girin">
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <td class="table-align-left text-theme-primary">Yeni Şifre</td>
                                        <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'new_password'): ?>
                                            <td class="password-row">
                                                <input type="password" class="form-input input-danger input-password" name="new_password" placeholder="Yeni Şifrenizi Girin" autofocus>
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php elseif (!empty($data['user']['new_password'])): ?>
                                            <td class="password-row">
                                                <input type="password" class="form-input input-password" name="new_password" value="<?php echo $data['user']['new_password'] ?>" placeholder="Yeni Şifrenizi Girin">
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php else: ?>
                                            <td class="password-row">
                                                <input type="password" class="form-input input-password" name="new_password" placeholder="Yeni Şifrenizi Girin">
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <td class="table-align-left text-theme-primary">Yeni Şifre Tekrar</td>
                                        <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 're_new_password'): ?>
                                            <td class="password-row">
                                                <input type="password" class="form-input input-danger input-password" name="re_new_password" placeholder="Yeni Şifrenizi Girin" autofocus>
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'password_not_match'): ?>
                                            <td class="password-row">
                                                <input type="password" class="form-input input-danger input-password" name="re_new_password" placeholder="Şifreler Eşleşmiyor" autofocus>
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php elseif (!empty($data['user']['re_new_password'])): ?>
                                            <td class="password-row">
                                                <input type="password" class="form-input input-password" name="re_new_password" value="<?php echo $data['user']['re_new_password'] ?>" placeholder="Yeni Şifrenizi Girin">
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php else: ?>
                                            <td class="password-row">
                                                <input type="password" class="form-input input-password" name="re_new_password" placeholder="Yeni Şifrenizi Girin">
                                                <i class="btn-password fas fa-eye-slash"></i>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row-space">
                                <div class="row-right">
                                    <input class="btn-form btn-yellow" type="submit" name="update_password" value="Şifreyi Değiştir" title="Şifreyi Değiştir">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php require 'View/SharedAdmin/_admin_footer.php'; ?>
    <script src="<?php echo URL; ?>/assets/js/admin.js"></script>
    <script src="<?php echo URL; ?>/assets/js/password.js"></script>
</body>
</html>