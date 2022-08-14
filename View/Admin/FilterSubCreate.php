<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <?php $filter_name = !empty($data['filter']['filter_name']) ? $data['filter']['filter_name'] : ''; ?>
    <title><?php echo ucwords($filter_name); ?> Ekle | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/FilterDetails/<?php echo !empty($data['filter']['filter_url']) ? $data['filter']['filter_url'] : ''; ?>">
        <span class="btn-header-text"><?php echo ucwords($filter_name); ?> Filtresine Geri Dön</span>
        <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
    </a>   
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <h2 class="admin-panel-title"><?php echo ucwords($filter_name); ?> Ekle</h2>
                <form action="<?php echo URL; ?>/AdminController/FilterSubCreate" method="POST" autocomplete="off" novalidate>
                    <input type="hidden" name="filter_id" value="<?php echo !empty($data['filter']['id']) ? $data['filter']['id'] : ''; ?>">
                    <input type="hidden" name="filter_name" value="<?php echo !empty($data['filter']['filter_name']) ? $data['filter']['filter_name'] : ''; ?>">
                    <input type="hidden" name="filter_type" value="<?php echo !empty($data['filter']['filter_type']) ? $data['filter']['filter_type'] : ''; ?>">
                    <table class="mt-2 mb-2">
                        <tbody>
                            <tr>
                                <td style="width: 50%;" class="table-align-left text-theme-primary"><?php echo ucwords($filter_name); ?> Adı</td>
                                <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'filter_sub_name'): ?>
                                    <td style="width: 50%;"><input type="text" class="form-input input-danger" name="filter_sub_name" placeholder="<?php echo ucwords($filter_name); ?> Adını Tekrar Girin" autofocus></td>
                                <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'sub_filter_duplicate'): ?>
                                    <td style="width: 50%;"><input type="text" class="form-input input-danger" name="filter_sub_name" placeholder="<?php echo ucwords($filter_name); ?> Adını Tekrar Girin" autofocus></td>
                                <?php elseif (isset($data['filter_sub']['filter_sub_name'])): ?>
                                    <td style="width: 50%;"><input type="text" class="form-input" name="filter_name" value="<?php echo $data['filter_sub']['filter_sub_name']; ?>" placeholder="<?php echo ucwords($filter_name); ?> Adını Girin"></td>
                                <?php else: ?>
                                    <td style="width: 50%;"><input type="text" class="form-input" name="filter_sub_name" placeholder="<?php echo ucwords($filter_name); ?> Adını Girin" autofocus></td>
                                <?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row-space">
                        <div class="row-right">
                            <input class="btn-form btn-green" type="submit" name="create_filter_sub" value="<?php echo ucwords($filter_name); ?> Ekle" title="<?php echo ucwords($filter_name); ?> Filtresi Ekle">
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