<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo $web_data['statistics_title'] . ' - Yönetici | ' . BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <section class="statistics-section container">
        <div class="row">
            <div class="nav-menu">
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_PAGE ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_PAGE; ?>">Görüntülenen Sayfalar</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_ITEM ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_ITEM; ?>">Görüntülenen Ürünler</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_USER ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_USER; ?>">Kullanıcılar</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_ERROR ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_ERROR; ?>">Sistem Hataları</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_LOGIN ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_LOGIN; ?>">Girişler</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_EMAIL ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_EMAIL; ?>">Email Geçmişi</a>
                <a class="link<?php echo $web_data['statistics_type'] == URL_ADMIN_LOGS_CAPTCHA ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_LOGS . '/' . URL_ADMIN_LOGS_CAPTCHA; ?>">Robot Testi</a>
            </div>
            <div class="statistics-container">
                <?php if ($web_data['statistics_type'] == URL_ADMIN_LOGS_PAGE) : ?>
                    <h1 class="title mb">Görüntülenen Sayfalar</h1>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_ITEM) : ?>
                    <h1 class="title mb">Görüntülenen Ürünler</h1>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_USER) : ?>
                    <h1 class="title mb">Kullanıcılar</h1>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_ERROR) : ?>
                    <h1 class="title mb">Sistem Hataları</h1>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_LOGIN) : ?>
                    <h1 class="title mb">Girişler</h1>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_EMAIL) : ?>
                    <h1 class="title mb">Email Geçmişi</h1>
                <?php elseif ($web_data['statistics_type'] == URL_ADMIN_LOGS_CAPTCHA) : ?>
                    <h1 class="title mb">Robot Testi</h1>
                <?php endif; ?>
            </div>
        </div>
    </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
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