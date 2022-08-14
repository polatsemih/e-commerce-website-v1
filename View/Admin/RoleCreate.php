<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title>Rol Ekle | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Roles">
        <span class="btn-header-text">Rollere Geri Dön</span>
        <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
    </a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
            <h2 class="admin-panel-title">Rol Ekle</h2>
                <form action="<?php echo URL; ?>/AdminController/RoleCreate" method="POST" autocomplete="off" novalidate>
                        <table class="mt-2 mb-2">
                            <tbody>
                                <tr>
                                    <td style="width: 50%;" class="table-align-left text-theme-primary">Rol Adı</td>
                                    <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'role_name'): ?>
                                        <td style="width: 50%;"><input type="text" class="form-input input-danger" name="role_name" placeholder="Rol Adını Tekrar Girin" autofocus></td>
                                    <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'role_duplicate'): ?>
                                        <td style="width: 50%;"><input type="text" class="form-input input-danger" name="role_name" placeholder="Rol Adını Tekrar Girin" autofocus></td>
                                    <?php elseif (!empty($data['role']['role_name'])): ?>
                                        <td style="width: 50%;"><input type="text" class="form-input" name="role_name" value="<?php echo $data['role']['role_name']; ?>" placeholder="Rol Adını Girin" autofocus></td>
                                    <?php else: ?>
                                        <td style="width: 50%;"><input type="text" class="form-input" name="role_name" placeholder="Rol Adını Girin" autofocus></td>
                                    <?php endif; ?>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row-space">
                            <div class="row-right">
                                <input class="btn-form btn-green" type="submit" name="create_role" value="Rol Ekle" title="Rol Ekle">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php require 'View/SharedAdmin/_admin_footer.php'; ?>
    <script src="<?php echo URL; ?>/assets/js/admin.js"></script>
</body>
</html>