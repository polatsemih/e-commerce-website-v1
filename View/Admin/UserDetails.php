<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title>Kullanıcı Detay | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Users">
        <span class="btn-header-text" title="Kullancılara Geri Dön">Kullanıcılara Geri Dön</span>
        <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
    </a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <?php if (!isset($data['notfound_user'])): ?>
                    <?php $user_id = !empty($data['user']['id']) ? $data['user']['id'] : ''; ?>
                    <?php $user_first_name = !empty($data['user']['first_name']) ? $data['user']['first_name'] : '-'; ?>
                    <?php $user_last_name = !empty($data['user']['last_name']) ? $data['user']['last_name'] : '-'; ?>
                    <div class="row-space-center">
                        <div class="row-left">
                            <h2 class="admin-panel-title">Kullanıcı Detayları ve Güncelleme</h2>
                        </div>
                        <?php
                            $persmission = true;
                            foreach (CANT_DELETE_USER_IDS as $cant_delete_user) {
                                if ($user_id == $cant_delete_user) {
                                    $persmission = false;
                                }
                            } 
                            if ($persmission): ?>
                                <div class="row-right">
                                    <form action="<?php echo URL; ?>/AdminController/UserDelete" method="POST">
                                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                        <input class="btn-form btn-red" type="submit" name="delete_user" value="Kullanıcıyı Sil" title="Kullanıcıyı Sil">
                                    </form>
                                </div>
                            <?php endif; ?>
                    </div>
                    <div class="tree mt-1">
                        <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Users">kullanıcılar</a>
                        <span class="seperater">&gt;</span>
                        <a class="tree-guide" href="<?php echo URL; ?>/AdminController/UserDetails/<?php echo $user_id; ?>"><?php echo $user_id; ?></a>    
                    </div>
                    <?php date_default_timezone_set('Europe/Istanbul'); ?>
                    <form class="mb-5" action="<?php echo URL; ?>/AdminController/UserUpdate" method="POST" autocomplete="off" novalidate>
                        <table class="mt-2 mb-2">
                            <tbody>
                                <tr>
                                    <td style="width: 50%;" class="table-align-left text-theme-primary">İsim</td>
                                    <td style="width: 50%;" class="table-align-left"><?php echo ucwords($user_first_name); ?></td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Soy İsim</td>
                                    <td class="table-align-left"><?php echo ucwords($user_last_name); ?></td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Email</td>
                                    <td class="table-align-left"><?php echo !empty($data['user']['email']) ? $data['user']['email'] : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Email Onay</td>
                                    <td class="table-align-left">
                                        <input type="checkbox" class="checkbox" <?php echo (empty($data['user']['email_confirmed']) || $data['user']['email_confirmed'] == 0) ? '' : 'checked' ?> disabled>
                                        <span class="checkmark"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Telefon Numarası</td>
                                    <td class="table-align-left"><?php echo !empty($data['user']['tel']) ? $data['user']['tel'] : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Telefon Numarası Onay</td>
                                    <td class="table-align-left">
                                        <input type="checkbox" class="checkbox" <?php echo (empty($data['user']['tel_confirmed']) || $data['user']['tel_confirmed'] == 0) ? '' : 'checked' ?> disabled>
                                        <span class="checkmark"></span>
                                    </td>
                                </tr>
                                <?php if (isset($data['roles'])): ?>
                                    <tr>
                                        <td class="table-align-left text-theme-primary">Rol</td>
                                        <?php if ($persmission): ?>
                                            <?php if (isset($data['input_error']) && $data['input_error'] === 'user_role'): ?>
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
                                                        <?php foreach ($data['roles'] as $role): ?>
                                                            <option value="<?php echo $role['role_name']; ?>" <?php echo $role['id']==$data['user']['user_role'] ? 'selected':''; ?>><?php echo ucwords($role['role_name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php foreach ($data['roles'] as $role): if ($role['id'] === $data['user']['user_role']): ?>
                                                <td class="table-align-left"><?php echo ucwords($role['role_name']); ?></td>
                                            <?php endif; endforeach; ?>
                                        <?php endif; ?>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="table-align-left text-theme-primary">İki Aşamalı Doğrulama Aktif</td>
                                    <td class="table-align-left">
                                        <input type="checkbox" class="checkbox" <?php echo (empty($data['user']['two_factor_enabled']) || $data['user']['two_factor_enabled'] == 0) ? "" : "checked" ?> disabled>
                                        <span class="checkmark"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Başarısız Giriş Denemesi</td>
                                    <td class="table-align-left"><?php echo !empty($data['user']['access_failed_count']) ? $data['user']['access_failed_count'] : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Son Giriş Tarihi</td>
                                    <td class="table-align-left"><?php echo !empty($data['user']['date_last_login']) ? date('d/m/Y H.i.s', strtotime($data['user']['date_last_login'])) : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Üye Olma Tarihi</td>
                                    <td class="table-align-left"><?php echo !empty($data['user']['date_registration']) ? date('d/m/Y H.i.s', strtotime($data['user']['date_registration'])) : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Profilini Son Güncellenme Tarihi</td>
                                    <td class="table-align-left"><?php echo !empty($data['user']['date_last_profile_update']) ? date('d/m/Y H.i.s', strtotime($data['user']['date_last_profile_update'])) : '-'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if ($persmission): ?>
                            <input type="hidden" name="id" value="<?php echo $user_id; ?>">
                            <div class="row-space">
                                <div class="row-right">
                                    <input class="btn-form btn-yellow" type="submit" name="update_user" value="Kullanıcıyı Güncelle" title="Kullanıcıyı Güncelle">
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                    <?php if (!isset($data['notfound_comment'])): ?>
                        <h2 class="admin-panel-title">Kullanıcının Yorumları (<?php echo count($data['comments']); ?>)</h2>
                        <?php foreach ($data['comments'] as $comment): ?>
                            <div class="item-comments">
                                <div class="item-comment">
                                    <div class="user-infos">
                                        <?php $item_name = !empty($data['items'][$user_id]['item_name']) ? ucwords($data['items'][$user_id]['item_name']) : '-'; ?>
                                        <img class="user-comment-image" src="<?php echo URL; ?>/assets/images/blanckbasic_users/<?php echo !empty($data['user']['profile_image']) ? $data['user']['profile_image'] : ''; ?>" alt="<?php echo ucwords($user_first_name).' '.ucwords($user_last_name); ?>">
                                        <a class="btn-theme mr-1" href="<?php echo URL; ?>/AdminController/UserDetails/<?php echo $user_id; ?>"><?php echo ucwords($user_first_name).' '.ucwords($user_last_name); ?></a>
                                        <a class="btn-theme" href="<?php echo URL; ?>/AdminController/ItemDetails/<?php echo !empty($data['items'][$user_id]['item_url']) ? $data['items'][$user_id]['item_url'] : ''; ?>"><?php echo $item_name; ?></a>
                                        <div class="row-right">
                                            <div class="row-space-center">
                                                <span class="mr-20 user-comment-date"><?php echo !empty($comment['comment_date_added']) ? $comment['comment_date_added'] : '-'; ?></span>
                                                <form action="<?php echo URL; ?>/AccountController/UserCommentDelete" method="POST">
                                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                                    <input type="hidden" name="comment_id" value="<?php echo !empty($comment['id']) ? $comment['id'] : ''; ?>">
                                                    <input class="btn-form btn-red" type="submit" name="submit_user_comment_delete" value="Yorumu Sil" title="<?php echo $item_name; ?> Ürününe Yapılan Yorumu Sil">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="user-comment-text"><?php echo !empty($comment['comment']) ? $comment['comment'] : ''; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="notfound">
                            <span class="text-notfound"><?php echo $data['notfound_comment']; ?></span>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="notfound">
                        <span class="text-notfound"><?php echo $data['notfound_user']; ?></span>
                        <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Users">
                            <span class="btn-header-text">Kullanıcılara Geri Dön</span>
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