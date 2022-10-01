<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Kullanıcılar - Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <main>
        <section class="users-section container">
            <div class="row-top">
                <div class="left">
                    <?php if (!empty($web_data['user_count'])) : ?>
                        <h1 class="title">Kullanıcılar (<?php echo count($web_data['user_count']); ?>)</h1>
                    <?php else : ?>
                        <h1 class="title">Kullanıcılar (0)</h1>
                    <?php endif; ?>
                </div>
                <div class="right">
                    <div class="filter-row" title="Kaldırılımış Hesapları Göster">
                        <span class="filter-label">Kaldırılımış Hesaplar</span>
                        <div id="details-item-deleted" class="item-deleted">
                            <select class="item-select">
                                <option value="all"></option>
                                <option value="1"></option>
                                <option value="0"></option>
                            </select>
                            <?php if (isset($web_data['selected_deleted'])) : ?>
                                <?php if ($web_data['selected_deleted'] == 1) : ?>
                                    <span id="select-text-deleted" class="select-text">Evet</span>
                                <?php elseif ($web_data['selected_deleted'] == 0) : ?>
                                    <span id="select-text-deleted" class="select-text">Hayır</span>
                                <?php else : ?>
                                    <span id="select-text-deleted" class="select-text">Tümü</span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span id="select-text-deleted" class="select-text">Tümü</span>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="deleted-select" class="deleted-select">
                                <span class="option" data-url="all">Tümü</span>
                                <span class="option" data-url="1">Evet</span>
                                <span class="option" data-url="0">Hayır</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-row" title="Tek Seferde Kaç Kullanıcı Gösterileceğini Belirtir">
                        <div id="details-item-limit" class="item-limit">
                            <select class="item-select">
                                <option value="20" selected></option>
                                <option value="50"></option>
                                <option value="100"></option>
                                <option value="500"></option>
                            </select>
                            <?php if (!empty($web_data['selected_limit'])) : ?>
                                <span id="select-text" class="select-text"><?php echo $web_data['selected_limit']; ?></span>
                            <?php else : ?>
                                <span id="select-text" class="select-text">20</span>
                            <?php endif; ?>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="details-select" class="details-select">
                                <span class="option" data-url="20">20</span>
                                <span class="option" data-url="50">50</span>
                                <span class="option" data-url="100">100</span>
                                <span class="option" data-url="500">500</span>
                            </div>
                        </div>
                    </div>
                    <div class="search-container">
                        <input type="search" class="admin-search" placeholder="Kullanıcı Ara">
                        <i id="btn-search" class="fas fa-search"></i>
                    </div>
                </div>
            </div>
            <?php if (!empty($web_data['selected_search'])) : ?>
                <div class="search-tree">
                    <?php if (!empty($web_data['url_search'])) : ?>
                        <a class="text underline" href="<?php echo URL . URL_ADMIN_USERS . '?' . $web_data['url_search']; ?>">Kullancılar</a>
                    <?php else : ?>
                        <a class="text underline" href="<?php echo URL . URL_ADMIN_USERS; ?>">Kullancılar</a>
                    <?php endif; ?>
                    <span class="text">></span>
                    <span class="link text underline"><?php echo $web_data['selected_search']; ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($web_data['users'])) : ?>
                <div class="row">
                    <span class="table-th l">
                        İsim
                    </span>
                    <span class="table-th m">
                        Email
                    </span>
                    <span class="table-th s">
                        Emaili Onaylanmış
                    </span>
                    <span class="table-th s">
                        Alışveriş Yapmış
                    </span>
                    <span class="table-th s">
                        Detaylar
                    </span>
                </div>
                <?php foreach ($web_data['users'] as $user) ?>
                <div class="row">
                    <span class="table-td l">
                        <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                    </span>
                    <span class="table-td m">
                        <?php echo $user['email']; ?>
                    </span>
                    <span class="table-td s">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" class="checkbox" <?php echo $user['email_confirmed'] == 1 ? ' checked' : ''; ?> disabled>
                            <span class="checkmark"></span>
                        </div>
                    </span>
                    <span class="table-td s">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" class="checkbox" <?php echo $user['is_user_shopped'] == 1 ? ' checked' : ''; ?> disabled>
                            <span class="checkmark"></span>
                        </div>
                    </span>
                    <span class="table-td s">
                        <a class="btn-user-success" href="<?php echo URL . URL_ADMIN_USER_DETAILS . '/' . $user['id']; ?>">Detaylar</a>
                    </span>
                </div>
            <?php else : ?>
                <span class="user-not-found">Kayıtlı Kullanıcı Bulunamadı</span>
            <?php endif; ?>
        </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
    <script>
        document.getElementById('btn-search').addEventListener('click', (e) => {
            e.preventDefault();
            <?php if (!empty($web_data['url_search'])) : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_USERS . '?ara=' ?>' + document.querySelector('.admin-search').value + '&<?php echo $web_data['url_search']; ?>';
            <?php else : ?>
                window.location.href = '<?php echo URL . URL_ADMIN_USERS . '?ara=' ?>' + document.querySelector('.admin-search').value;
            <?php endif; ?>
        });
        document.getElementById('details-item-deleted').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('deleted-select').classList.toggle('active');
        });
        document.querySelectorAll('.item-deleted .deleted-select .option').forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                <?php if (!empty($web_data['url_deleted'])) : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_USERS . '?silinen-kullanicilar=' ?>' + element.dataset.url + '&<?php echo $web_data['url_deleted']; ?>';
                <?php else : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_USERS . '?silinen-kullanicilar=' ?>' + element.dataset.url;
                <?php endif; ?>
            });
        });
        document.getElementById('details-item-limit').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('details-select').classList.toggle('active');
        });
        document.querySelectorAll('.item-limit .details-select .option').forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                <?php if (!empty($web_data['url_limit'])) : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_USERS . '?limit=' ?>' + element.dataset.url + '&<?php echo $web_data['url_limit']; ?>';
                <?php else : ?>
                    window.location.href = '<?php echo URL . URL_ADMIN_USERS . '?limit=' ?>' + element.dataset.url;
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