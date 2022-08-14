<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo BRAND; ?></title>
    <meta name="description" content="<?php echo BRAND; ?>'in yeni koleksiyonu ile tarzını yenile. En trend tasarımlar" />
    <meta name="keywords" content="blanck, basic, blnckk" />
    <?php require 'View/SharedHome/_home_head.php'; ?>
</head>

<body>
    <?php require 'View/SharedCommon/_common_loader.php'; ?>
    <header>
        <?php require 'View/SharedHome/_home_body.php'; ?>
        <?php require 'View/SharedHome/_home_search.php'; ?>
    </header>
    <main>
        <div class="notification">
            <?php if (isset($_SESSION[SESSION_NOTIFICATION])) {
                echo $_SESSION[SESSION_NOTIFICATION];
                unset($_SESSION[SESSION_NOTIFICATION]);
            } ?>
        </div>
        <section id="home-section" style="height: 2200px;">
            <div class="container">

            </div>
        </section>
    </main>
    <?php require 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/home.js"></script>
    <script src="<?php echo URL; ?>assets/js/header.js"></script>
</body>

</html>