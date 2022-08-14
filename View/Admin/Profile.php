<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title><?php echo ucwords($data['user']['first_name']).' '.ucwords($data['user']['last_name']).' | '; ?><?php echo BRAND; ?> - Yönetim Paneli</title>
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
                                <h2 class="admin-panel-title">Yönetici Profilini Güncelle</h2>
                            </div>
                        </div>
                        <form action="<?php echo URL; ?>/AdminController/Profile" method="POST" autocomplete="off" novalidate>
                            <table class="mt-2 mb-2">
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;" class="table-align-left text-theme-primary">Ad</td>
                                        <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'first_name'): ?>
                                            <td style="width: 50%;"><input type="text" class="form-input input-danger" name="first_name" placeholder="Adınızı Girin" autofocus></td>
                                        <?php elseif (!empty($data['user']['first_name'])): ?>
                                            <td style="width: 50%;"><input type="text" class="form-input" name="first_name" value="<?php echo $data['user']['first_name']; ?>" placeholder="Adınızı Girin"></td>
                                        <?php else: ?>
                                            <td style="width: 50%;"><input type="text" class="form-input" name="first_name" placeholder="Adınızı Girin"></td>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <td class="table-align-left text-theme-primary">Soyad</td>
                                        <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'last_name'): ?>
                                            <td><input type="text" class="form-input input-danger" name="last_name" placeholder="Soyadınızı Girin" autofocus></td>
                                        <?php elseif (!empty($data['user']['last_name'])): ?>
                                            <td><input type="text" class="form-input" name="last_name" value="<?php echo $data['user']['last_name']; ?>" placeholder="Soyadınızı Girin"></td>
                                        <?php else: ?>
                                            <td><input type="text" class="form-input" name="last_name" placeholder="Soyadınızı Girin"></td>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <td class="table-align-left text-theme-primary">Email</td>
                                        <?php if (!empty($data['user']['email'])): ?>
                                            <td class="table-align-left"><?php echo $data['user']['email']; ?></td>
                                            <input type="hidden" class="form-input" name="email" value="<?php echo $data['user']['email']; ?>">
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <td class="table-align-left text-theme-primary">Telefon Numarası (+90)</td>
                                        <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'tel'): ?>
                                            <td><input type="tel" class="form-input input-danger" name="tel" placeholder="Telefon Numaranızı Girin" autofocus></td>
                                        <?php elseif (!empty($data['user']['tel'])): ?>
                                            <td><input type="tel" class="form-input" name="tel" value="<?php echo ltrim($data['user']['tel'], '90'); ?>" placeholder="Telefon Numaranızı Girin"></td>
                                        <?php else: ?>
                                            <td><input type="tel" class="form-input" name="tel" placeholder="Telefon Numaranızı Girin"></td>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <td class="table-align-left text-theme-primary">İki Aşamalı Doğrulama Aktif</td>
                                        <td class="table-align-left">
                                            <input type="checkbox" class="checkbox" checked>
                                            <span class="checkmark"></span>
                                        </td>                                            
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row-space">
                                <div class="row-right">
                                    <input class="btn-form btn-yellow" type="submit" name="update_profile" value="Profili Güncelle" title="Profili Güncelle">
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
</body>
</html>