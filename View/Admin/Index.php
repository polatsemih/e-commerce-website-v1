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
        <section class="admin-section container">
            
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