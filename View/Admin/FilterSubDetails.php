<!DOCTYPE html>
<html lang="tr-TR">
<head>
    <title><?php echo (!empty($data['filter_sub']['filter_sub_name']) && !empty($data['filter']['filter_name'])) ? ucwords($data['filter']['filter_name']).'-'.ucwords($data['filter_sub']['filter_sub_name']).' | ' : ''; ?><?php echo BRAND; ?> - Yönetim Paneli</title>
    <?php require 'View/SharedAdmin/_admin_head.php'; ?>
</head>
<?php require 'View/SharedAdmin/_admin_body.php'; ?>
    <?php $filter_id = !empty($data['filter']['id']) ? $data['filter']['id'] : ''; ?>
    <?php $filter_name = !empty($data['filter']['filter_name']) ? $data['filter']['filter_name'] : ''; ?>
    <?php $filter_url = !empty($data['filter']['filter_url']) ? $data['filter']['filter_url'] : ''; ?>
    <form action="<?php echo URL; ?>/AdminController/FilterSubCreate" method="POST" autocomplete="off" novalidate>
        <input type="hidden" name="filter_id" value="<?php echo !empty($data['filter']['id']) ? $data['filter']['id'] : ''; ?>">
        <button class="btn-header row-center" type="submit" name="view_filter_sub">
            <span class="btn-header-text"><?php echo ucwords($filter_name); ?> Ekle</span>
            <div class="btn-header-icon"><i class="fas fa-plus"></i></div>
        </button>
    </form>
<?php require 'View/SharedAdmin/_admin_body_profile.php'; ?>
    <main>
        <?php echo isset($_SESSION[SESSION_NOTIFICATION]) ? $_SESSION[SESSION_NOTIFICATION] : '<div class="notification"></div>'; ?>
        <section>
            <div class="container">
                <?php if (!isset($data['notfound_filter_sub'])): ?>
                    <?php date_default_timezone_set('Europe/Istanbul'); ?>
                    <?php $filter_sub_id = !empty($data['filter_sub']['id']) ? $data['filter_sub']['id'] : ''; ?>
                    <?php $filter_sub_name = !empty($data['filter_sub']['filter_sub_name']) ? $data['filter_sub']['filter_sub_name'] : ''; ?>
                    <?php $filter_sub_url = !empty($data['filter_sub']['filter_sub_url']) ? $data['filter_sub']['filter_sub_url'] : ''; ?>
                    <div class="row-space-center">
                        <div class="row-left">
                            <h2 class="admin-panel-title">"<?php echo ucwords($filter_name).' ('.ucwords($filter_sub_name).')'; ?>" Detayları ve Güncelleme</h2>
                        </div>
                        <?php 
                        $persmission = true;
                        foreach (CANT_DELETE_FILTER_SUB_IDS as $cant_delete_filtersub) {
                            if ($filter_sub_id == $cant_delete_filtersub) {
                                $persmission = false;
                            }
                        } 
                        if ($persmission): ?>
                            <div class="row-right">
                                <form action="<?php echo URL; ?>/AdminController/FilterSubDelete" method="POST">
                                    <input type="hidden" name="filter_sub_id" value="<?php echo $filter_sub_id; ?>">
                                    <input type="hidden" name="filter_id" value="<?php echo $filter_id; ?>">
                                    <input class="btn-form btn-red" type="submit" name="delete_filter_sub" value="Alt Filtreyi Sil" title="<?php echo ucwords($filter_sub_name); ?> Filtresini Sil">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="tree mt-1">
                        <a class="tree-guide" href="<?php echo URL; ?>/AdminController/Filters">filtreler</a>
                        <span class="seperater">&gt;</span>
                        <a class="tree-guide" href="<?php echo URL; ?>/AdminController/FilterDetails/<?php echo $filter_url; ?>"><?php echo $filter_url; ?></a>
                        <span class="seperater">&gt;</span>
                        <a class="tree-guide" href="<?php echo URL; ?>/AdminController/FilterSubDetails/<?php echo $filter_sub_url; ?>"><?php echo $filter_sub_url; ?></a>
                    </div>
                    <form class="mb-5" action="<?php echo URL; ?>/AdminController/FilterSubUpdate" method="POST" autocomplete="off" novalidate>
                        <input type="hidden" name="id" value="<?php echo $filter_sub_id; ?>">
                        <input type="hidden" name="filter_id" value="<?php echo $filter_id; ?>">
                        <table class="mt-2 mb-2">
                            <tbody>
                                <tr>
                                    <td style="width: 50%;" class="table-align-left text-theme-primary"><?php echo ucwords($filter_name); ?> Adı</td>
                                    <?php if (isset($data['input_error_key']) && $data['input_error_key'] === 'filter_sub_name'): ?>
                                        <td style="width: 50%;"><input type="text" class="form-input input-danger" name="filter_sub_name" placeholder="<?php echo ucwords($filter_name); ?> Adını Tekrar Girin" autofocus></td>
                                    <?php elseif (isset($data['input_error_key']) && $data['input_error_key'] === 'sub_filter_duplicate'): ?>
                                        <td style="width: 50%;"><input type="text" class="form-input input-danger" name="filter_sub_name" placeholder="<?php echo ucwords($filter_name); ?> Adını Tekrar Girin" autofocus></td>
                                    <?php elseif (isset($data['filter_sub']['filter_sub_name'])): ?>
                                        <td style="width: 50%;"><input type="text" class="form-input" name="filter_sub_name" value="<?php echo $data['filter_sub']['filter_sub_name']; ?>" placeholder="<?php echo ucwords($filter_name); ?> Adını Girin"></td>
                                    <?php else: ?>
                                        <td style="width: 50%;"><input type="text" class="form-input" name="filter_sub_name" placeholder="<?php echo ucwords($filter_name); ?> Adını Girin" autofocus></td>
                                    <?php endif; ?>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Oluşturulma Tarihi</td>
                                    <td class="table-align-left"><?php echo !empty($data['filter_sub']['filter_sub_date_added']) ? date('d/m/Y H.i.s', strtotime($data['filter_sub']['filter_sub_date_added'])) : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td class="table-align-left text-theme-primary">Son Güncellenme Tarihi</td>
                                    <td class="table-align-left"><?php echo !empty($data['filter_sub']['filter_sub_date_updated']) ? date('d/m/Y H.i.s', strtotime($data['filter_sub']['filter_sub_date_updated'])) : '-'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php
                        if ($persmission): ?>
                            <div class="row-space">
                            <div class="row-right">
                                <input class="btn-form btn-yellow" type="submit" name="update_filter_sub" value="Alt Filtreyi Güncelle" title="<?php echo ucwords($filter_sub_name); ?> Filtresini Güncelle">
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                    <?php if (!isset($data['notfound_item']) && isset($data['items'])): ?>
                        <h2 class="admin-panel-title">"<?php echo ucwords($filter_name).' ('.ucwords($filter_sub_name).')'; ?>" Ait Ürünler (<?php echo count($data['items']); ?>)</h2>
                        <?php $filter_type = !empty($data['filter']['filter_type']) ? $data['filter']['filter_type'] : ''; ?>
                        <table class="mt-2 mb-2">
                            <thead>
                                <tr>
                                    <th style="width: 30%;" class="table-align-center">Ürün Adı</th>
                                    <th style="width: 13%;" class="table-align-center">Fiyat</th>
                                    <th style="width: 13%;" class="table-align-center">İndirimli Fiyat</th>
                                    <th style="width: 10%;" class="table-align-center">Toplam Adet</th>
                                    <?php if ($filter_type === 3): ?>
                                        <th style="width: 14%;" class="table-align-center"><?php echo ucwords($filter_name).' ('.ucwords($filter_sub_name).')'; ?></th>
                                    <?php elseif ($filter_type === 1): ?>
                                        <th style="width: 14%;" class="table-align-center"><?php echo ucwords($filter_name).' ('.ucwords($filter_sub_name).') Adedi'; ?></th>
                                    <?php elseif ($filter_type === 2): ?>
                                        <th style="width: 14%;" class="table-align-center"><?php echo ucwords($filter_name); ?></th>
                                    <?php endif; ?>
                                    <th style="width: 20%;" class="table-align-center">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['items'] as $item) : ?>
                                    <tr>
                                        <?php $item_name = !empty($item['item_name']) ? ucwords($item['item_name']) : '-'; ?>
                                        <td class="table-align-left"><?php echo $item_name; ?></td>
                                        <td class="table-align-center"><?php echo !empty($item['item_price']) ? $item['item_price'].'₺' : '-'; ?></td>
                                        <td class="table-align-center"><?php echo !empty($item['item_discount_price']) ? $item['item_discount_price'].'₺' : '-'; ?></td>
                                        <td class="table-align-center"><?php echo !empty($item['item_total_number']) ? $item['item_total_number'] : '-'; ?></td>
                                        <?php if ($filter_type === 3): ?>
                                            <td class="table-align-center"><?php echo !empty($item[$filter_name.'_'.$filter_sub_name]) ? ucwords($item[$filter_name.'_'.$filter_sub_name]) : '-'; ?></td>
                                        <?php elseif ($filter_type === 1): ?>
                                            <td class="table-align-center"><?php echo !empty($item[$filter_name.'_'.$filter_sub_name]) ? ucwords($item[$filter_name.'_'.$filter_sub_name]) : '0'; ?></td>
                                        <?php elseif ($filter_type === 2): ?>
                                            <td class="table-align-center"><?php echo ucwords($filter_sub_name); ?></td>
                                        <?php endif; ?>
                                        <td>
                                            <div class="row-wrap">
                                                <div class="table-settings">
                                                    <a class="btn-table-setting btn-green" href="<?php echo URL; ?>/AdminController/ItemDetails/<?php echo !empty($item['item_url']) ? $item['item_url'] : ''; ?>" title="<?php echo $item_name; ?> Detayları ve Güncelleme">Detaylar</a>
                                                </div>
                                                <form class="table-settings" action="<?php echo URL; ?>/AdminController/ItemDelete" method="POST">
                                                    <input type="hidden" name="item_id" value="<?php echo !empty($item['id']) ? $item['id'] : ''; ?>">
                                                    <input class="btn-table-setting btn-red" type="submit" name="delete_item" value="Sil" title="<?php echo $item_name; ?> Sil">
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="notfound">
                            <span class="text-notfound"><?php echo $data['notfound_item']; ?></span>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="notfound">
                        <span class="text-notfound"><?php echo $data['notfound_filter_sub']; ?></span>
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