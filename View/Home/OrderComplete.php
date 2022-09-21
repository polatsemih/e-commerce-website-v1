<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Sipariş Sonuç | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body>
    <header class="header">
        <div class="header-wrapper">
            <div class="container header-row">
                <div class="brand-container">
                    <a class="header-brand" href="<?php echo URL; ?>"><?php echo BRAND; ?></a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section class="order-complete-section container">
            <h1 class="title">Sipariş Sonucu</h1>
            <div class="notfound-container">
                <?php if (!empty($web_data['order_result_notification'])) : ?>
                    <span class="text"><?php echo $web_data['order_result_notification']; ?></span>
                <?php endif; ?>
                <?php if (!empty($web_data['order_result_redirect'])) : ?>
                    <?php if ($web_data['order_result_redirect'] == 'profile-orders') : ?>
                        <a href="<?php echo URL . URL_PROFILE . '/' . URL_PROFILE_ORDERS; ?>" class="link">Siparişlerim</a>
                    <?php else : ?>
                        <a href="<?php echo URL; ?>" class="link">Ana Sayfaya Dön</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>

</html>