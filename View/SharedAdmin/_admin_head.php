<?php require 'View/SharedCommon/_common_meta.php'; ?>
<meta name="robots" content="none" />
<?php require 'View/SharedCommon/_common_favicon.php'; ?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
<link rel="stylesheet" href="<?php echo URL; ?>assets/css/admin.css">
<script src="<?php echo URL; ?>assets/js/jQuery.js"></script>
<script>
    $(document).ready(function() {
        $('#btn-hamburger').click(function() {
            $.ajax({
                url: '<?php echo URL . 'AdminController/MenuPreference'; ?>',
                type: 'POST'
            });
        });
    });
</script>