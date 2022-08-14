<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title>Roller | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/RoleCreate">
        <span class="btn-header-text">Rol Ekle</span>
        <div class="btn-header-icon"><i class="fas fa-plus"></i></div>
    </a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <?php if (!isset($data['notfound_role'])): ?>
                    <?php date_default_timezone_set('Europe/Istanbul'); ?>
                    <div class="row-space-center">
                        <?php if (isset($data['search'])): ?>
                            <h2 class="admin-panel-title row-left">Roller (<?php echo $data['search']; ?>)</h2>
                        <?php else: ?>
                            <h2 class="admin-panel-title row-left">Roller</h2>
                        <?php endif; ?>
                        <form class="row row-right" action="<?php echo URL; ?>/AdminController/Roles" method="GET" autocomplete="off" novalidate>
                            <?php if (isset($data['notfound_search']) || isset($data['search'])): ?>
                                <input class="input-table-search" type="text" name="s" placeholder="Başka Bir Rol Ara" tabindex="1">
                            <?php else: ?>
                                <input class="input-table-search" type="text" name="s" placeholder="Rol Ara" tabindex="1">
                            <?php endif; ?>
                            <button class="btn-table-search" type="submit"><i class="fas fa-search table-search-icon"></i></button>
                        </form>
                    </div>
                    <?php if (!isset($data['notfound_search'])): ?>
                        <?php if (isset($data['search'])): ?>
                            <div class="tree mt-2 mb-2">
                                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Roles">roller</a>
                                <span class="seperater">&gt;</span>
                                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Roles?s=<?php echo $data['search']; ?>"><?php echo $data['search']; ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($data['roles'])): ?>
                            <table class="mt-2 mb-2">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;" class="table-align-center">Rol İsmi</th>
                                        <th style="width: 25%;" class="table-align-center">Oluşturulma Tarihi</th>
                                        <th style="width: 25%;" class="table-align-center">Son Güncellenme Tarihi</th>
                                        <th style="width: 20%;" class="table-align-center">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['roles'] as $role) : ?>
                                        <tr>
                                            <?php $role_name = !empty($role['role_name']) ? $role['role_name'] : '-'; ?>
                                            <td class="table-align-center"><?php echo ucwords($role_name); ?></td>
                                            <td class="table-align-center"><?php echo !empty($role['role_date_added']) ? date('d/m/Y', strtotime($role['role_date_added'])) : '-'; ?></td>
                                            <td class="table-align-center"><?php echo !empty($role['role_date_updated']) ? date('d/m/Y', strtotime($role['role_date_updated'])) : '-'; ?></td>
                                            <td>
                                                <div class="row-wrap">
                                                    <?php if (!empty($role['role_url'])): ?>
                                                        <div class="table-settings">
                                                            <a class="btn-table-setting btn-green" href="<?php echo URL; ?>/AdminController/RoleDetails/<?php echo $role['role_url']; ?>" title="<?php echo ucwords($role_name); ?> Detayları ve Güncelleme">Detaylar</a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($role['id'])): ?>
                                                        <?php
                                                            $persmission = true;
                                                            foreach (CANT_DELETE_ROLE_IDS as $cant_delete_role) {
                                                                if ($role['id'] == $cant_delete_role) {
                                                                    $persmission = false;
                                                                }
                                                            } 
                                                            if ($persmission): ?>
                                                                <form class="table-settings" action="<?php echo URL; ?>/AdminController/RoleDelete" method="POST">
                                                                    <input type="hidden" name="role_id" value="<?php echo $role['id']; ?>">
                                                                    <input class="btn-table-setting btn-red" type="submit" name="delete_role" value="Sil" title="<?php echo ucwords($role_name); ?> Rolünü Sil">
                                                                </form>
                                                            <?php else: ?>
                                                                <div class="table-settings">
                                                                    <span class="btn-disable">Silinemez</span>
                                                                </div>
                                                            <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="notfound mt-4">
                            <span class="text-notfound"><?php echo $data['notfound_search']; ?></span>
                            <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Roles">
                                <span class="btn-header-text">Rollere Geri Dön</span>
                                <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="notfound">
                        <span class="text-notfound"><?php echo $data['notfound_role']; ?></span>
                        <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/RoleCreate">
                            <span class="btn-header-text">İlk Rolü Ekle</span>
                            <div class="btn-header-icon"><i class="fas fa-plus"></i></div>
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