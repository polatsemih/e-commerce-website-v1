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
            <h1 class="title">Kullanıcılar (1)</h1>
            <?php if (!empty($web_data['users'])) : ?>

            <?php else : ?>
                <span class="user-not-found">Kayıtlı Kullanıcı Bulunamadı</span>
            <?php endif; ?>
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