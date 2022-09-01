<?php require_once 'View/SharedCommon/_loader.php'; ?>
<div class="notification-wrapper">
    <?php if (!empty($_SESSION[SESSION_NOTIFICATION_NAME])) {
        echo $_SESSION[SESSION_NOTIFICATION_NAME];
        unset($_SESSION[SESSION_NOTIFICATION_NAME]);
    }
    ?>
</div>
<header class="header">
    <?php require_once 'View/SharedHome/_home_header.php'; ?>
    <?php require_once 'View/SharedHome/_home_search.php'; ?>
</header>