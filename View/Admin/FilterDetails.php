<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title><?php echo (!empty($data['filter']['filter_name'])) ? ucwords($data['filter']['filter_name']).' | ' : ''; ?><?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <?php $filter_name = !empty($data['filter']['filter_name']) ? $data['filter']['filter_name'] : ''; ?>
    <?php if (!empty($data['filter']['id'])): ?>
        <form action="<?php echo URL; ?>/AdminController/FilterSubCreate" method="POST" autocomplete="off" novalidate>
            <input type="hidden" name="filter_id" value="<?php echo $data['filter']['id']; ?>">
            <button class="btn-header row-center" type="submit" name="view_filter_sub">
                <span class="btn-header-text"><?php echo ucwords($filter_name); ?> Ekle</span>
                <div class="btn-header-icon"><i class="fas fa-plus"></i></div>
            </button>
        </form>
    <?php endif; ?>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <?php if (!isset($data['notfound_filter']) && !empty($data['filter']['id'])): ?>
                    <?php date_default_timezone_set('Europe/Istanbul'); ?>
                    <div class="row-space-center">
                        <div class="row-left">
                            <h2 class="admin-panel-title">"<?php echo ucwords($filter_name); ?>" Detayları ve Güncelleme</h2>
                        </div>
                        <?php
                            $persmission = true;
                            foreach (CANT_DELETE_FILTER_IDS as $cant_delete_filter) {
                                if ($data['filter']['id'] == $cant_delete_filter) {
                                    $persmission = false;
                                }
                            } 
                            if ($persmission): ?>
                            <div class="row-right">
                                <form action="<?php echo URL; ?>/AdminController/FilterDelete" method="POST" autocomplete="off" novalidate>
                                    <input type="hidden" name="filter_id" value="<?php echo $data['filter']['id']; ?>">
                                    <input class="btn-form btn-red" type="submit" name="delete_filter" value="Filtreyi Sil" title="<?php echo ucwords($filter_name); ?> Filtresini Sil">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="tree mt-1">
                        <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Filters">filtreler</a>
                        <?php if (!empty($data['filter']['filter_url'])): ?>
                            <span class="seperater">&gt;</span>
                            <a class="tree-guide" href="<?php echo URL; ?>/AdminController/FilterDetails/<?php echo $data['filter']['filter_url']; ?>"><?php echo $data['filter']['filter_url']; ?></a>
                        <?php endif; ?>
                    </div>
                    <form class="mb-5" action="<?php echo URL; ?>/AdminController/FilterUpdate" method="POST" autocomplete="off" novalidate>
                        <input type="hidden" name="id" value="<?php echo $data['filter']['id']; ?>">
                        <input type="hidden" name="filter_ori_name" value="<?php echo $filter_name; ?>">
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
                                <tr>
                                    <td class="table-align-left text-theme-primary">Oluşturulma Tarihi</td>
                                    <td class="table-align-left"><?php echo !empty($data['filter']['filter_date_added']) ? date('d/m/Y H.i.s', strtotime($data['filter']['filter_date_added'])) : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Son Güncellenme Tarihi</td>
                                    <td class="table-align-left"><?php echo !empty($data['filter']['filter_date_updated']) ? date('d/m/Y H.i.s', strtotime($data['filter']['filter_date_updated'])) : '-'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if ($persmission): ?>
                            <div class="row-space">
                                <div class="row-right">
                                    <input class="btn-form btn-yellow" type="submit" name="update_filter" value="Filtreyi Güncelle" title="<?php echo ucwords($filter_name); ?> Filtresini Güncelle">
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                    <?php if (!isset($data['notfound_filter_sub']) && isset($data['filters_sub'])): ?>
                        <h2 class="admin-panel-title">"<?php echo ucwords($filter_name); ?>" Alt Filtreleri (<?php echo count($data['filters_sub']); ?>)</h2>
                        <table class="mt-2 mb-2">
                            <thead>
                                <tr>
                                    <th style="width: 30%;" class="table-align-center"><?php echo ucwords($filter_name); ?> İsmi</th>
                                    <th style="width: 25%;" class="table-align-center">Oluşturulma Tarihi</th>
                                    <th style="width: 25%;" class="table-align-center">Son Güncellenme Tarihi</th>
                                    <th style="width: 20%;" class="table-align-center">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['filters_sub'] as $filter_sub) : ?>
                                    <tr>
                                        <?php $filter_sub_name = !empty($filter_sub['filter_sub_name']) ? ucwords($filter_sub['filter_sub_name']) : '-'; ?>
                                        <td class="table-align-left"><?php echo $filter_sub_name; ?></td>
                                        <td class="table-align-center"><?php echo !empty($filter_sub['filter_sub_date_added']) ? date('d/m/Y', strtotime($filter_sub['filter_sub_date_added'])) : '-'; ?></td>
                                        <td class="table-align-center"><?php echo !empty($filter_sub['filter_sub_date_updated']) ? date('d/m/Y', strtotime($filter_sub['filter_sub_date_updated'])) : '-'; ?></td>
                                        <td>
                                            <div class="row-wrap">
                                                <?php if (!empty($filter_sub['filter_sub_url'])): ?>
                                                    <div class="table-settings">
                                                        <a class="btn-table-setting btn-green" href="<?php echo URL; ?>/AdminController/FilterSubDetails/<?php echo $filter_sub['filter_sub_url']; ?>" title=" <?php echo $filter_sub_name; ?> Detayları ve Güncelleme">Detaylar</a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($filter_sub['id']) && !empty($data['filter']['filter_url'])):
                                                        $persmission2 = true;
                                                        foreach (CANT_DELETE_FILTER_SUB_IDS as $cant_delete_filtersub) {
                                                            if ($filter_sub['id'] == $cant_delete_filtersub) {
                                                                $persmission2 = false;
                                                            }
                                                        }
                                                        if ($persmission2): ?>
                                                        <form class="table-settings" action="<?php echo URL; ?>/AdminController/FilterSubDelete" method="POST">
                                                            <input type="hidden" name="filter_sub_id" value="<?php echo $filter_sub['id']; ?>">
                                                            <input type="hidden" name="filter_url" value="<?php echo $data['filter']['filter_url']; ?>">
                                                            <input class="btn-table-setting btn-red" type="submit" name="delete_filter_sub" value="Sil" title="<?php echo $filter_sub_name; ?> Alt Filtresini Sil">
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
                    <?php else: ?>
                        <div class="notfound">
                            <span class="text-notfound"><?php echo $data['notfound_filter_sub']; ?></span>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="notfound">
                        <span class="text-notfound"><?php echo $data['notfound_filter']; ?></span>
                        <a class="btn-header row-center" href="<?php echo URL; ?>/AdminController/Filters">
                            <span class="btn-header-text">Filtrelere Geri Dön</span>
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