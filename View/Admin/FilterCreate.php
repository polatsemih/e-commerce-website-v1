<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title>Filtre Ekle | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Filters">
        <span class="btn-header-text">Filtrelere Geri Dön</span>
        <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
    </a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <h2 class="admin-panel-title">Filtre Ekle</h2>
                <div class="tree mt-1">
                    <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Filters">filtreler</a>
                    <span class="seperater">&gt;</span>
                    <a class="tree-guide" href="<?php echo URL; ?>/AdminController/FilterCreate">filtre ekle</a>    
                </div>
                <form action="<?php echo URL; ?>/AdminController/FilterCreate" method="POST" autocomplete="off" novalidate>
                    <table class="mb-2 mt-2">
                        <tbody>
                            <tr>
                                <td style="width: 50%;" class="table-align-left text-theme-primary">Filtre Adı</td>
                                <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'filter_name'): ?>
                                    <td style="width: 50%;"><input type="text" class="form-input input-danger" name="filter_name" placeholder="Filtre Adını Tekrar Girin" autofocus></td>
                                <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'filter_duplicate'): ?>
                                    <td style="width: 50%;"><input type="text" class="form-input input-danger" name="filter_name" placeholder="Filtre Adını Tekrar Girin" autofocus></td>
                                <?php elseif (isset($data['filter']['filter_name'])): ?>
                                    <td style="width: 50%;"><input type="text" class="form-input" name="filter_name" value="<?php echo $data['filter']['filter_name']; ?>" placeholder="Filtre Adını Girin"></td>
                                <?php else: ?>
                                    <td style="width: 50%;"><input type="text" class="form-input" name="filter_name" placeholder="Filtre Adını Girin" autofocus></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td class="table-align-left text-theme-primary">Filtre Tipi</td>
                                <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'filter_type'): ?>
                                    <td>
                                        <select class="table-select text-danger" name="filter_type">
                                            <option value="default" selected>Filtre Tipi Seçin</option>
                                            <option value="1">1 (Adet Girilen Filtre)</option>
                                            <option value="2">2 (Tek Seçimli Filtre)</option>
                                            <option value="3">3 (Birden Fazla Seçimli Filtre)</option>
                                        </select>
                                    </td>
                                <?php else: ?>
                                    <td>
                                        <select class="table-select" name="filter_type">
                                            <?php $filter_type = isset($data['filter']['filter_type']) ? $data['filter']['filter_type'] : ''; ?>
                                            <option value="default" selected>Filtre Tipi Seçin</option>
                                            <option value="1" <?php echo $filter_type == 1 ? 'selected' : ''; ?>>1 (Adet Girilen Filtre)</option>
                                            <option value="2" <?php echo $filter_type == 2 ? 'selected' : ''; ?>>2 (Tek Seçimli Filtre)</option>
                                            <option value="3" <?php echo $filter_type == 3 ? 'selected' : ''; ?>>3 (Birden Fazla Seçimli Filtre)</option>
                                        </select>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row-space">
                        <div class="row-right">
                            <input class="btn-form btn-green" type="submit" name="create_filter" value="Filtre Ekle" title="Filtre Ekle">
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