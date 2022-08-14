<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title>Kullanıcı Reklam Verileri | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <h2 class="admin-panel-title mb-2">Kullanıcı Reklam Verileri</h2>
                <table>
                    <thead>
                        <tr>
                            <th class="table-align-center" style="width: 25%;">Ad</th>
                            <th class="table-align-center" style="width: 25%;">SoyAd</th>
                            <th class="table-align-center" style="width: 25%;">Email</th>
                            <th class="table-align-center" style="width: 25%;">Telefon Numarası</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['user_infos'] as $user): ?>
                            <tr>
                                <td class="table-align-center"><?php echo !empty($user['first_name']) ? $user['first_name'] : '-'; ?></td>
                                <td class="table-align-center"><?php echo !empty($user['last_name']) ? $user['last_name'] : '-'; ?></td>
                                <td class="table-align-center"><?php echo !empty($user['email']) ? $user['email'] : '-'; ?></td>
                                <td class="table-align-center"><?php echo !empty($user['tel']) ? $user['tel'] : '-'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
<?php require 'View/SharedAdmin/_admin_footer.php'; ?>
    <script src="<?php echo URL; ?>/assets/js/admin.js"></script>
</body>
</html>