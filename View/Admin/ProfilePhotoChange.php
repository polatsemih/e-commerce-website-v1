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
                                <h2 class="admin-panel-title">Profil Fotoğrafını Değiştir</h2>
                            </div>
                        </div>
                        <form action="<?php echo URL; ?>/AdminController/ProfilePhotoChange" method="POST" autocomplete="off" novalidate enctype="multipart/form-data">
                            <div class="item-image-conteiner unselectable">
                                <label for="upload-image-1" class="image-label">
                                    <span class="image-label-text">Profil Fotoğrafını Değiştirmek İçin Tıklayın</span>
                                    <?php //if (isset($_COOKIE[USER_COOKIE_NAME])): $user_cookie = Cookie::GetCookie($_COOKIE[USER_COOKIE_NAME], USER_COOKIE_SALT_ONE, USER_COOKIE_SALT_TWO); ?>
                                        <img src="<?php //echo URL; ?>/assets/images/users/<?php //echo $user_cookie['user_id']; ?>/<?php //echo $user_cookie['user_profile_image']; ?>" class="uploaded-image">
                                    <?php //endif; ?>
                                    
                                    <div class="upload-image-icon-container">
                                        <i class="fas fa-camera upload-image-icon"></i>
                                    </div>
                                </label>
                                <input id="upload-image-1" class="item-image-input" type="file" name="user_image" accept=".jpg, .jpeg, .png">
                            </div>
                            <div class="mt-2 mb-2">
                                <?php if (isset($data['input_error_key']) && isset($data['image_error_message'])): if ($data['input_error_key'] === 'user_image_problem'): ?>
                                    <span class="text-danger"><?php echo $data['image_error_message']; ?></span>
                                <?php endif; endif; ?>
                            </div>
                            <div class="row-space">
                                <div class="row-right">
                                    <input class="btn-form btn-green" type="submit" name="update_profile_photo" value="Profil Fotoğrafını Güncelle" title="Profil Fotoğrafını Güncelle">
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
    <script src="<?php echo URL; ?>/assets/js/uploaduserimage.js"></script>
</body>
</html>