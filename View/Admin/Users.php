<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title>Kullanıcılar | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/UserCreate">
        <span class="btn-header-text">Kullanıcı Ekle</span>
        <div class="btn-header-icon"><i class="fas fa-plus"></i></div>
    </a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <?php if (!isset($data['notfound_user'])): ?>
                    <div class="row-space-center">
                        <?php if (isset($data['search'])): ?>
                            <h2 class="admin-panel-title row-left">Kullanıcılar (<?php echo $data['search']; ?>)</h2>
                        <?php else: ?>
                            <h2 class="admin-panel-title row-left">Kullanıcılar</h2>
                        <?php endif; ?>
                        <form class="row row-right" action="<?php echo URL; ?>/AdminController/Users" method="GET" autocomplete="off" novalidate>
                            <?php if (isset($data['notfound_search']) || isset($data['search'])): ?>
                                <input class="input-table-search" type="text" name="s" placeholder="Başka Bir Kullanıcı Ara" tabindex="1">
                            <?php else: ?>
                                <input class="input-table-search" type="text" name="s" placeholder="Kullanıcı Ara" tabindex="1">
                            <?php endif; ?>
                            <button class="btn-table-search" type="submit"><i class="fas fa-search table-search-icon"></i></button>
                        </form>
                    </div>
                    <?php if (!isset($data['notfound_search'])): ?>
                        <?php if (isset($data['search'])): ?>
                            <div class="tree mt-2 mb-2">
                                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Users">kullanıcılar</a>
                                <span class="seperater">&gt;</span>
                                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Users?s=<?php echo $data['search']; ?>"><?php echo $data['search']; ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if (isset ($data['users'])): ?>
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
                                                        <?php
                                                            $persmission = true;
                                                            foreach (CANT_DELETE_USER_IDS as $cant_delete_user) {
                                                                if ($user['id'] == $cant_delete_user) {
                                                                    $persmission = false;
                                                                }
                                                            } 
                                                            if ($persmission): ?>
                                                                <form class="table-settings" action="<?php echo URL; ?>/AdminController/UserDelete" method="POST">
                                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                                    <input class="btn-table-setting btn-red" type="submit" name="delete_user" value="Sil" title="Kullanıcıyı Sil">
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
                            <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Users">
                                <span class="btn-header-text">Kullanıcılara Geri Dön</span>
                                <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="notfound">
                        <span class="text-notfound"><?php echo $data['notfound_user']; ?></span>
                        <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/UserCreate">
                            <span class="btn-header-text">İlk Kullanıcıyı Ekle</span>
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