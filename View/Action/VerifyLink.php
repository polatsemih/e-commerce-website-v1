<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo isset($web_data['verify_link_title']) ? $web_data['verify_link_title'] . ' | ' : ''; ?><?php echo BRAND; ?></title>
    <?php require 'View/SharedCommon/_common_meta.php'; ?>
    <meta name="robots" content="none" />
    <?php require 'View/SharedCommon/_common_favicon.php'; ?>
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/home.css">
    <style>
        .h6ewfhuwe-title {
            font-size: 1.8rem;
            color: #000000;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php require 'View/SharedCommon/_common_loader.php'; ?>
    <header>
        <div class="header-container">
            <div class="container row-adjacent">
                <div class="header-brand-container">
                    <a class="header-brand" href="<?php echo URL; ?>"><?php echo BRAND; ?></a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section class="action-section">
            <div class="action-container">
                <?php if (isset($web_data['verify_link_msg'])) : ?>
                    <h1 class="h6ewfhuwe-title"><?php echo $web_data['verify_link_msg']; ?></h1>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php require 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/loader.js"></script>
</body>

</html>