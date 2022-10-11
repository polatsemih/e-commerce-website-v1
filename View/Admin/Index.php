<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <main>
        <section class="index-section container">
            <div class="row">
                <div class="left">
                    <h1 class="title">Yönetici Paneli</h1>
                </div>
                <div class="right">
                    <div class="filter-row" title="Siteye Yapılan Ziyaret Sayısını Filtrele">
                        <span class="filter-label">Filtrele</span>
                        <div id="details-filter-main" class="filter-main">
                            <select class="item-select">
                                <option value="0"></option>
                                <option value="1"></option>
                                <option value="2"></option>
                                <option value="3"></option>
                                <option value="4"></option>
                            </select>
                            <?php if (isset($web_data['selected_filter'])) : ?>
                                <?php if ($web_data['selected_filter'] == 1) : ?>
                                    <span id="select-text-sale" class="select-text">1 Ay</span>
                                <?php elseif ($web_data['selected_filter'] == 2) : ?>
                                    <span id="select-text-sale" class="select-text">3 Ay</span>
                                <?php elseif ($web_data['selected_filter'] == 3) : ?>
                                    <span id="select-text-sale" class="select-text">6 Ay</span>
                                <?php elseif ($web_data['selected_filter'] == 4) : ?>
                                    <span id="select-text-sale" class="select-text">1 Yıl</span>
                                <?php else : ?>
                                    <span id="select-text-sale" class="select-text">1 Hafta</span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span id="select-text-sale" class="select-text">1 Hafta</span>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="filter-select" class="filter-select">
                                <span class="option" data-url="0">1 Hafta</span>
                                <span class="option" data-url="1">1 Ay</span>
                                <span class="option" data-url="2">3 Ay</span>
                                <span class="option" data-url="3">6 Ay</span>
                                <span class="option" data-url="4">1 Yıl</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!empty($web_data['view_count']) && !empty($web_data['row_distance'])) : ?>
                <span class="row-column-title">Siteyi Ziyaret Eden Kişi Sayısı</span>
                <div class="column-wrapper">
                    <div class="row-column">
                        <?php for ($i = count($web_data['row_distance']) - 1; $i >= 0; $i--) : ?>
                            <span class="view"><?php echo $web_data['row_distance'][$i]; ?></span>
                        <?php endfor; ?>
                    </div>
                    <div class="column-container">
                        <?php foreach ($web_data['view_count'] as $view_count) : ?>
                            <div class="column-rect-wrapper">
                                <div class="column-rect" style="height: <?php echo ($view_count == 0) ? '0%' : ($view_count / $web_data['max']) * 100 . '%'; ?>;<?php echo ($view_count == 0) ? ' display: none' : ''; ?>">
                                    <span class="column-rect-view"><?php echo $view_count; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="row-wrapper">
                    <div class="space-row"></div>
                    <div class="row-date">
                        <?php foreach ($web_data['view_count'] as $key => $view_count) : ?>
                            <?php if (isset($web_data['selected_filter']) && ($web_data['selected_filter'] == 3 ||$web_data['selected_filter'] == 4)) : ?>
                                <?php if ($key == 0 || $key == (count($web_data['view_count']) - 1)) : ?>
                                    <span class="date" <?php echo (isset($web_data['selected_filter'])) ? ' style="writing-mode: vertical-rl; width: auto; font-size: 1.2rem;"' : ''; ?>><?php echo date('d-m-Y', strtotime(date('d-m-Y') . ' -' . $key . ' day')); ?></span>
                                <?php endif; ?>
                            <?php elseif (isset($web_data['selected_filter']) && $web_data['selected_filter'] == 1) : ?>
                                <span class="date" <?php echo (isset($web_data['selected_filter'])) ? ' style="writing-mode: vertical-rl;font-size: 1.2rem;"' : ''; ?>><?php echo date('d-m-Y', strtotime(date('d-m-Y') . ' -' . $key . ' day')); ?></span>
                            <?php elseif (isset($web_data['selected_filter']) && $web_data['selected_filter'] == 2) : ?>
                                <span class="date" <?php echo (isset($web_data['selected_filter'])) ? ' style="writing-mode: vertical-rl;"' : ''; ?>><?php echo date('d-m-Y', strtotime(date('d-m-Y') . ' -' . $key . ' day')); ?></span>
                            <?php else : ?>
                                <span class="date" style="font-size: 1.2rem;"><?php echo date('d-m-Y', strtotime(date('d-m-Y') . ' -' . $key . ' day')); ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
    <script>
        document.getElementById('details-filter-main').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('filter-select').classList.toggle('active');
        });
        document.querySelectorAll('.filter-main .filter-select .option').forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                <?php if (!empty($web_data['url_filter'])) : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_INDEX . '?filtrele=' ?>' + element.dataset.url + '&<?php echo $web_data['url_filter']; ?>';
                <?php else : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_INDEX . '?filtrele=' ?>' + element.dataset.url;
                <?php endif; ?>
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#btn-hamburger').click(function() {
                $.ajax({
                    url: '<?php echo URL . URL_ADMIN_MENU; ?>',
                    type: 'POST'
                });
            });
        });
    </script>
</body>

</html>