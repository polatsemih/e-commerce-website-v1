<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <?php $id = !empty($data['user']['id']) ? $data['user']['id'] : ''; ?>
    <?php $user_first_name = !empty($data['user']['user_first_name']) ? $data['user']['user_first_name'] : ''; ?>
    <?php $user_last_name = !empty($data['user']['user_last_name']) ? $data['user']['user_last_name'] : ''; ?>
    <title><?php echo $user_first_name.' '.$user_last_name; ?> | <?php echo BRAND; ?></title>
    <?php require 'Views/Shared/_admin_head.php'; ?>
</head>
<?php require 'Views/Shared/_admin_body.php'; ?>
<?php require 'Views/Shared/_admin_body_profile.php'; ?>
    <main>
        <section>
            <div class="container">
                <?php if (isset($data['notfound_user'])): echo $data['notfound_user']; else: ?>
                    <div class="row-space-center">
                        <div class="row-left">
                            <h2 class="admin-panel-title">Profil Güncelleme</h2>
                        </div>
                        <div class="row-right">
                            <form action="<?php echo URL; ?>AccountController/UserDelete" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $id; ?>">
                                <input class="btn-table-setting btn-update-item btn-red" type="submit" name="submit_user_delete" value="Hesabımı Sil" title="Hesabımı Sil">
                            </form>
                        </div>
                    </div>
                    <form class="form-item-update" action="<?php echo URL; ?>AccountController/UserUpdate" method="POST" autocomplete="off" novalidate>
                        <input type="hidden" value="<?php echo $id; ?>">
                        <div class="table-wrapper">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="table-cell-padding text-theme-primary">İsim</td>
                                        <td><input type="text" class="input-update-item" name="user_first_name" value="<?php echo !empty($data['user']['first_name']) ? ucwords($data['user']['first_name']) : ''; ?>" placeholder="İsim"></td>
                                    </tr>
                                    <tr>
                                        <td class="table-cell-padding text-theme-primary">Soyİsim</td>
                                        <td><input type="text" class="input-update-item" name="user_last_name" value="<?php echo !empty($data['user']['last_name']) ? ucwords($data['user']['last_name']) : ''; ?>" placeholder="Soyİsim"></td>
                                    </tr>
                                    <tr>
                                        <td class="table-cell-padding text-theme-primary">Email</td>
                                        <td><input type="text" class="input-update-item" name="user_email" value="<?php echo !empty($data['user']['email']) ? $data['user']['email'] : ''; ?>" placeholder="Email"></td>
                                    </tr>
                                    <tr>
                                        <td class="table-cell-padding text-theme-primary">Telefon Numarası</td>
                                        <td><input type="text" class="input-update-item" name="user_email" value="<?php echo !empty($data['user']['phone_number']) ? ucwords($data['user']['phone_number']) : ''; ?>" placeholder="Telefon Numarası"></td>
                                    </tr>
                                    <tr>
                                        <td class="table-cell-padding text-theme-primary">İki Aşamalı Doğrulama Aktif</td>
                                        <td class="table-cell-padding">
                                            <label for="two_factor_enabled">
                                                <input type="checkbox" class="checkbox" id="two_factor_enabled" name="two_factor_enabled" <?php echo ($data['user']['two_factor_enabled'] == 0 || empty($data['user']['two_factor_enabled'])) ? "" : "checked" ?>>
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>                                            
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row-space">
                            <div class="row-right">
                                <input class="btn-table-setting btn-update-item btn-yellow" type="submit" name="submit_user_update" value="Hesabımı Güncelle" title="Hesabımı Güncelle">
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>
<?php require 'Views/Shared/_admin_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/adminpanel.js"></script>
</body>
</html>