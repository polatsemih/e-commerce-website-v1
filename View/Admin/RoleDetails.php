<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title><?php echo !empty($data['role']['role_name']) ? ucwords($data['role']['role_name']).' | ' : ''; ?><?php echo BRAND; ?> - Yönetim Paneli</title>
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
                <?php if (!isset($data['notfound_role'])): ?>
                    <?php date_default_timezone_set('Europe/Istanbul'); ?>
                    <?php $role_id = !empty($data['role']['id']) ? $data['role']['id'] : ''; ?>
                    <?php $role_name = !empty($data['role']['role_name']) ? $data['role']['role_name'] : ''; ?>
                    <div class="row-space-center">
                        <div class="row-left">
                            <h2 class="admin-panel-title">"<?php echo ucwords($role_name); ?>" Detayları ve Güncelleme</h2>
                        </div>
                        <?php 
                        $persmission = true;
                        foreach (CANT_DELETE_ROLE_IDS as $cant_delete_role) {
                            if ($role_id == $cant_delete_role) {
                                $persmission = false;
                            }
                        } 
                        if ($persmission): ?>
                            <div class="row-right">
                                <form action="<?php echo URL; ?>/AdminController/RoleDelete" method="POST" autocomplete="off" novalidate>
                                    <input type="hidden" name="role_id" value="<?php echo $role_id; ?>">
                                    <input class="btn-form btn-red" type="submit" name="delete_role" value="Rolü Sil" title="<?php echo ucwords($role_name); ?> Rolünü Sil">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="tree mt-1">
                        <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Roles">roller</a>
                        <?php if (!empty($data['role']['role_url'])): ?>
                            <span class="seperater">&gt;</span>
                            <a class="tree-guide" href="<?php echo URL; ?>/AdminController/RoleDetails/<?php echo $data['role']['role_url']; ?>"><?php echo $data['role']['role_url']; ?></a>
                        <?php endif; ?>
                    </div>
                    <form class="mb-5" action="<?php echo URL; ?>/AdminController/RoleUpdate" method="POST" autocomplete="off" novalidate>
                        <table class="mt-2 mb-2">
                            <tbody>
                                <tr>
                                    <td style="width: 50%;" class="table-align-left text-theme-primary">Rol Adı</td>
                                    <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'role_name'): ?>
                                        <td style="width: 50%;"><input type="text" class="form-input input-danger" name="role_name" placeholder="Rol Adını Girin" autofocus></td>
                                    <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'role_duplicate'): ?>
                                        <td style="width: 50%;"><input type="text" class="form-input input-danger" name="role_name" placeholder="Rol Adını Girin" autofocus></td>
                                    <?php else: ?>
                                        <?php if ($persmission): ?>
                                            <td style="width: 50%;" class="table-align-left"><?php echo ucwords($role_name); ?></td>
                                        <?php else: ?>
                                            <td style="width: 50%;"><input type="text" class="form-input" name="role_name" value="<?php echo $role_name; ?>" placeholder="Rol Adını Girin"></td>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Oluşturulma Tarihi</td>
                                    <td class="table-align-left"><?php echo !empty($data['role']['role_date_added']) ? date('d/m/Y H.i.s', strtotime($data['role']['role_date_added'])) : '-'; ?></td>
                                </tr>
                                <?php if ($persmission): ?>
                                    <input type="hidden" name="id" value="<?php echo $role_id; ?>">
                                    <tr>
                                        <td class="table-align-left text-theme-primary">Son Güncellenme Tarihi</td>
                                        <td class="table-align-left"><?php echo !empty($data['role']['role_date_updated']) ? date('d/m/Y H.i.s', strtotime($data['role']['role_date_updated'])) : '-'; ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if ($persmission): ?>
                            <div class="row-space">
                                <div class="row-right">
                                    <input class="btn-form btn-yellow" type="submit" name="update_role" value="Rolü Güncelle" title="<?php echo ucwords($role_name); ?> Rolünü Güncelle">
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                    <?php if (!isset($data['notfound_user']) && isset($data['users'])): ?>
                        <h2 class="admin-panel-title">"<?php echo ucwords($role_name); ?>" Rolündeki Kullanıcılar (<?php echo count($data['users']); ?>)</h2>
                        <table class="mt-2 mb-2">
                            <thead>
                                <tr>
                                    <th style="width: 25%;" class="table-align-center">İsim</th>
                                    <th style="width: 25%;" class="table-align-center">Email</th>
                                    <th style="width: 5%;" class="table-align-center">Email Onay</th>
                                    <th style="width: 20%;" class="table-align-center">Telefon Numarası</th>
                                    <th style="width: 5%;" class="table-align-center">Telefon Onay</th>
                                    <th style="width: 20%;" class="table-align-center">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['users'] as $user): ?>
                                    <tr>
                                        <td class="table-align-left"><?php echo (!empty($user['first_name']) ? ucwords($user['first_name']) : '-').' '.(!empty($user['last_name']) ? ucwords($user['last_name']) : '-'); ?></td>
                                        <td class="table-align-left"><?php echo !empty($user['email']) ? $user['email'] : '-'; ?></td>
                                        <td class="table-align-center">
                                            <input type="checkbox" class="checkbox" <?php echo (empty($user['email_confirmed']) || $user['email_confirmed'] == 0) ? "" : "checked" ?> disabled>
                                            <span class="checkmark"></span>
                                        </td>
                                        <td class="table-align-center"><?php echo !empty($user['tel']) ? $user['tel'] : '-'; ?></td>
                                        <td class="table-align-center">
                                            <input type="checkbox" class="checkbox" <?php echo (empty($user['tel_confirmed']) || $user['tel_confirmed'] == 0) ? "" : "checked" ?> disabled>
                                            <span class="checkmark"></span>
                                        </td>
                                        <td>
                                            <div class="row-wrap">
                                                <?php if (!empty($user['id'])): ?>
                                                    <div class="table-settings">
                                                        <a class="btn-table-setting btn-green" href="<?php echo URL; ?>/AdminController/UserDetails/<?php echo $user['id']; ?>" title="Kullanıcı Detayları ve Güncelleme">Detaylar</a>
                                                    </div>
                                                    <?php if ($persmission): ?>
                                                        <div class="table-settings">
                                                            <span class="btn-disable">Silinemez</span>
                                                        </div>
                                                    <?php else: ?>
                                                        <form class="table-settings" action="<?php echo URL; ?>/AdminController/UserDelete" method="POST">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <input class="btn-table-setting btn-red" type="submit" name="delete_user" value="Sil" title="Kullanıcıyı Sil">
                                                        </form>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="notfound">
                            <span class="text-notfound"><?php echo $data['notfound_user']; ?></span>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="notfound">
                        <span class="text-notfound"><?php echo $data['notfound_role']; ?></span>
                        <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Roles">
                            <span class="btn-header-text">Rollere Geri Dön</span>
                            <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
<?php require 'View/SharedAdmin/_admin_footer.php'; ?>
    <script src="<?php echo URL; ?>/assets/js/admin.js"></script>
</body>
</html>