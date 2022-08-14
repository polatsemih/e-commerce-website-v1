<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title>Kullanıcı Ekle | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Users">
        <span class="btn-header-text">Kullanıcılara Geri Dön</span>
        <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
    </a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <h2 class="admin-panel-title">Kullanıcı Ekle</h2>
                <form action="<?php echo URL; ?>/AdminController/UserCreate" method="POST" autocomplete="off" novalidate>
                    <table class="mt-2 mb-2">
                        <tbody>
                            <tr>
                                <td style="width: 50%;" class="table-align-left text-theme-primary">Ad</td>
                                <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'first_name'): ?>
                                    <td style="width: 50%;"><input type="text" class="form-input input-danger" name="first_name" placeholder="Adı Tekrar Girin" autofocus></td>
                                <?php elseif (isset($data['user']['first_name'])): ?>
                                    <td style="width: 50%;"><input type="text" class="form-input" name="first_name" value="<?php echo $data['user']['first_name']; ?>" placeholder="Kullanıcı Adını Girin"></td>
                                <?php else: ?>
                                    <td style="width: 50%;"><input type="text" class="form-input" name="first_name" placeholder="Kullanıcı Adını Girin" autofocus></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td class="table-align-left text-theme-primary">Soyad</td>
                                <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'last_name'): ?>
                                    <td><input type="text" class="form-input input-danger" name="last_name" placeholder="Soyadı Tekrar Girin" autofocus></td>
                                <?php elseif (!empty($data['user']['last_name'])): ?>
                                    <td><input type="text" class="form-input" name="last_name" value="<?php echo $data['user']['last_name']; ?>" placeholder="Kullanıcı Soyadını Girin"></td>
                                <?php else: ?>
                                    <td><input type="text" class="form-input" name="last_name" placeholder="Kullanıcı Soyadını Girin"></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td class="table-align-left text-theme-primary">Email</td>
                                <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'email'): ?>
                                    <td><input type="text" class="form-input input-danger" name="email" placeholder="Emaili Tekrar Girin" autofocus></td>
                                <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'email_duplicate'): ?>
                                    <td><input type="text" class="form-input input-danger" name="email" placeholder="Email Zaten Kayıtlı" autofocus></td>
                                <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'not_valid_email'): ?>
                                    <td><input type="text" class="form-input input-danger" name="email" placeholder="Email Geçerli Değil" autofocus></td>
                                <?php elseif (!empty($data['user']['email'])): ?>
                                    <td><input type="text" class="form-input" name="email" value="<?php echo $data['user']['email']; ?>" placeholder="Kullanıcının Emaili Girin"></td>
                                <?php else: ?>
                                    <td><input type="text" class="form-input" name="email" placeholder="Kullanıcının Emaili Girin"></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td class="table-align-left text-theme-primary">Email Onay</td>
                                <td class="form-input">
                                    <?php $email_confirmed = !empty($data['user']['email_confirmed']) ? $data['user']['email_confirmed'] : ''; ?>
                                    <label for="email_confirmed">
                                        <input type="checkbox" class="checkbox" name="email_confirmed" id="email_confirmed" <?php echo $email_confirmed == 1 ? 'checked' : '' ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-align-left text-theme-primary">Telefon Numarası (+90)</td>
                                <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'tel'): ?>
                                    <td><input type="text" class="form-input input-danger" name="tel" placeholder="Telefon Numarasını Girin" autofocus></td>
                                <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'tel_duplicate'): ?>
                                    <td><input type="text" class="form-input input-danger" name="tel" placeholder="Telefon Numarası Zaten Kayıtlı" autofocus></td>
                                <?php elseif (!empty($data['user']['tel'])): ?>
                                    <td><input type="text" class="form-input" name="tel" value="<?php echo $data['user']['tel']; ?>" placeholder="Kullanıcının Telefon Numarası"></td>
                                <?php else: ?>
                                    <td><input type="text" class="form-input" name="tel" placeholder="Kullanıcının Telefon Numarası"></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td class="table-align-left text-theme-primary">Telefon Numarası Onay</td>
                                <td class="form-input">
                                    <?php $tel_confirmed = !empty($data['user']['tel_confirmed']) ? $data['user']['tel_confirmed'] : ''; ?>
                                    <label for="tel_confirmed">
                                        <input type="checkbox" class="checkbox" name="tel_confirmed" id="tel_confirmed" <?php echo $tel_confirmed == 1 ? 'checked' : '' ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                            <?php if (isset($data['roles'])): ?>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Rol</td>
                                    <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'user_role'): ?>
                                        <td>
                                            <select class="table-select text-danger" name="user_role">
                                                <option selected>Rol Seçin</option>
                                                <?php foreach ($data['roles'] as $role): ?>
                                                    <option value="<?php echo $role['role_name']; ?>"><?php echo ucwords($role['role_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    <?php else: ?>
                                        <td>
                                            <select class="table-select" name="user_role">
                                                <?php foreach ($data['roles'] as $role): $user_role = !empty($data['user']['user_role']) ? $data['user']['user_role'] : ''; ?>
                                                    <option value="<?php echo $role['role_name']; ?>" <?php echo $role['id']==$user_role ? 'selected':''; ?>><?php echo ucwords($role['role_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="table-align-left text-theme-primary">Şifre</td>
                                <td class="table-align-left">BlanckBasic_123</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row-space">
                        <div class="row-right">
                            <input class="btn-form btn-green" type="submit" name="create_user" value="Kullanıcı Ekle" title="Kullanıcı Ekle">
                        </div>
                    </div>
                    </form>
                </div>
        </section>
    </main>
<?php require 'View/SharedAdmin/_admin_footer.php'; ?>
    <script src="<?php echo URL; ?>/assets/js/admin.js"></script>
</body>
</html>