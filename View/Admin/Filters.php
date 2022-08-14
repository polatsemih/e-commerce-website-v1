<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title>Filtreler | <?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/FilterCreate">
        <span class="btn-header-text">Filtre Ekle</span>
        <div class="btn-header-icon"><i class="fas fa-plus"></i></div>
    </a>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <?php if (!isset($data['notfound_filter'])): ?>
                    <?php date_default_timezone_set('Europe/Istanbul'); ?>
                    <div class="row-space-center">
                        <?php if (isset($data['search'])): ?>
                            <h2 class="admin-panel-title row-left">Filtreler (<?php echo $data['search']; ?>)</h2>
                        <?php else: ?>
                            <h2 class="admin-panel-title row-left">Filtreler</h2>
                        <?php endif; ?>
                        <form class="row row-right" action="<?php echo URL; ?>/AdminController/Filters" method="GET" autocomplete="off" novalidate>
                            <?php if (isset($data['notfound_search']) || isset($data['search'])): ?>
                                <input class="input-table-search" type="text" name="s" placeholder="Başka Bir Filtre Ara" tabindex="1">
                            <?php else: ?>
                                <input class="input-table-search" type="text" name="s" placeholder="Filtre Ara" tabindex="1">
                            <?php endif; ?>
                            <button class="btn-table-search" type="submit"><i class="fas fa-search table-search-icon"></i></button>
                        </form>
                    </div>
                    <?php if (!isset($data['notfound_search'])): ?>
                        <?php if (isset($data['search'])): ?>
                            <div class="tree mt-2 mb-2">
                                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Filters">filtreler</a>
                                <span class="seperater">&gt;</span>
                                <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Filters?s=<?php echo $data['search']; ?>"><?php echo $data['search']; ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($data['filters'])): ?>
                            <table class="mt-2 mb-2">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;" class="table-align-center">Filtre İsmi</th>
                                        <th style="width: 20%;" class="table-align-center">Filtre Tipi</th>
                                        <th style="width: 20%;" class="table-align-center">Oluşturulma Tarihi</th>
                                        <th style="width: 20%;" class="table-align-center">Son Güncellenme Tarihi</th>
                                        <th style="width: 20%;" class="table-align-center">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['filters'] as $filter) : ?>
                                        <tr>
                                            <?php $filter_name = !empty($filter['filter_name']) ? ucwords($filter['filter_name']) : '-'; ?>
                                            <td class="table-align-center"><?php echo $filter_name; ?></td>
                                            <td class="table-align-center"><?php echo !empty($filter['filter_type']) ? ucwords($filter['filter_type']) : '-'; ?></td>
                                            <td class="table-align-center"><?php echo !empty($filter['filter_date_added']) ? date('d/m/Y', strtotime($filter['filter_date_added'])) : '-'; ?></td>
                                            <td class="table-align-center"><?php echo !empty($filter['filter_date_updated']) ? date('d/m/Y', strtotime($filter['filter_date_updated'])) : '-'; ?></td>
                                            <td>
                                                <div class="row-wrap">
                                                    <?php if (!empty($filter['filter_url'])): ?>
                                                        <div class="table-settings">
                                                            <a class="btn-table-setting btn-green" href="<?php echo URL; ?>/AdminController/FilterDetails/<?php echo $filter['filter_url']; ?>" title="<?php echo $filter_name; ?> Detayları ve Güncelleme">Detaylar</a>
                                                        </div>
                                                    <?php endif; if (!empty($filter['id'])):
                                                            $persmission = true;
                                                            foreach (CANT_DELETE_FILTER_IDS as $cant_delete_filter) {
                                                                if ($filter['id'] == $cant_delete_filter) {
                                                                    $persmission = false;
                                                                }
                                                            } 
                                                            if ($persmission): ?>
                                                                <form class="table-settings" action="<?php echo URL; ?>/AdminController/FilterDelete" method="POST">
                                                                    <input type="hidden" name="filter_id" value="<?php echo $filter['id']; ?>">
                                                                    <input class="btn-table-setting btn-red" type="submit" name="delete_filter" value="Sil" title="<?php echo $filter_name; ?> Filtresini Sil">
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
                            <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Filters">
                                <span class="btn-header-text">Filtrelere Geri Dön</span>
                                <div class="btn-header-icon"><i class="fas fa-undo-alt"></i></div>
                            </a>
                        </div>
                    <?php endif ?>
                <?php else: ?>
                    <div class="notfound">
                    <span class="text-notfound"><?php echo $data['notfound_filter']; ?></span>
                        <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/FilterCreate">
                            <span class="btn-header-text">İlk Filtreyi Ekle</span>
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