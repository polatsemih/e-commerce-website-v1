<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo BRAND; ?></title>
    <?php require 'View/SharedHome/_home_head.php'; ?>
</head>

<body>
    <?php require 'View/SharedCommon/_common_loader.php'; ?>
    <header>
        <?php require 'View/SharedHome/_home_body.php'; ?>
        <?php require 'View/SharedHome/_home_search.php'; ?>
    </header>
    <main>
        <section class="notfound-details-container">
            <div class="notfound-item-details">
                <span class="text-details-notfound">Ürün Bulunamadı</span>
                <a href="<?php echo URL; ?>" class="btn-details-goback">Anasayfaya Geri Dön<i class="fas fa-undo-alt icon-details-goback"></i></a>
            </div>
        </section>
    </main>
    <?php require 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/loader.js"></script>
    <script src="<?php echo URL; ?>assets/js/header.js"></script>
</body>

</html>