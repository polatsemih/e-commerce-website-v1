<!DOCTYPE html>
<html lang="tr">

<head>
    <title><?php echo $web_data['order_error_title'] . ' | ' . BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <section class="statistics-section container">
        <div class="row">
            <div class="nav-menu">
                <a class="link<?php echo $web_data['order_error_type'] == URL_ADMIN_ORDER_CONVERSATION_ERROR ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_ORDER_ERRORS . '/' . URL_ADMIN_ORDER_CONVERSATION_ERROR; ?>">Sipariş Görüşme Hataları</a>
                <a class="link<?php echo $web_data['order_error_type'] == URL_ADMIN_ORDER_STATUS_ERROR ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_ORDER_ERRORS . '/' . URL_ADMIN_ORDER_STATUS_ERROR; ?>">Sipariş Durum Hataları</a>
                <a class="link<?php echo $web_data['order_error_type'] == URL_ADMIN_ORDER_STATUS_CODES ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_ORDER_ERRORS . '/' . URL_ADMIN_ORDER_STATUS_CODES; ?>">Durum Kodları</a>
                <a class="link<?php echo $web_data['order_error_type'] == URL_ADMIN_ORDER_MD_STATUS_CODES ? ' active' : ''; ?>" href="<?php echo URL . URL_ADMIN_ORDER_ERRORS . '/' . URL_ADMIN_ORDER_MD_STATUS_CODES; ?>">MD Durum Kodları</a>
            </div>
            <div class="statistics-container">
                <?php if ($web_data['order_error_type'] == URL_ADMIN_ORDER_CONVERSATION_ERROR) : ?>
                    <h1 class="title mb">Sipariş Görüşme Hataları</h1>
                    <?php if (!empty($web_data['order_conversation_errors'])) : ?>
                        <div class="order-error-row">
                            <div class="box one">Görüşme İsteği</div>
                            <div class="box one">Görüşme Cevabı</div>
                            <div class="box one">Sistem Zamanı</div>
                            <div class="box one">Kullanıcı ID</div>
                            <div class="box one">Kullanıcı IP</div>
                            <div class="box one">Fonksiyon Tipi</div>
                            <div class="box one">Tarih</div>
                        </div>
                        <?php foreach ($web_data['order_conversation_errors'] as $order_conversation_errors) : ?>
                            <div class="order-error-row">
                                <span class="box-td one hovered-id-container">
                                    <i class="fas fa-info"></i>
                                    <span class="hovered-id">
                                        <?php echo $order_conversation_errors['conversation_id_request']; ?>
                                        <span class="hovered-id-triangle"></span>
                                    </span>
                                </span>
                                <span class="box-td one hovered-id-container">
                                    <i class="fas fa-info"></i>
                                    <span class="hovered-id">
                                        <?php echo $order_conversation_errors['conversation_id_response']; ?>
                                        <span class="hovered-id-triangle"></span>
                                    </span>
                                </span>
                                <div class="box-td one"><?php echo $order_conversation_errors['system_time']; ?></div>
                                <span class="box-td one hovered-id-container">
                                    <i class="fas fa-info"></i>
                                    <span class="hovered-id">
                                        <?php echo $order_conversation_errors['user_id']; ?>
                                        <span class="hovered-id-triangle"></span>
                                    </span>
                                </span>
                                <div class="box-td one"><?php echo $order_conversation_errors['user_ip']; ?></div>
                                <div class="box-td one"><?php echo $order_conversation_errors['function_type']; ?></div>
                                <div class="box-td one"><?php echo date('d/m/Y H:i:s', strtotime($order_conversation_errors['date_error_occured'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($web_data['order_error_type'] == URL_ADMIN_ORDER_STATUS_ERROR) : ?>
                    <h1 class="title mb">Sipariş Durum Hataları</h1>
                    <?php if (!empty($web_data['order_status_errors'])) : ?>
                        <div class="order-error-row">
                            <div class="box two">Görüşme İsteği</div>
                            <div class="box two">Görüşme Cevabı</div>
                            <div class="box two">Sistem Zamanı</div>
                            <div class="box two">Kullanıcı ID</div>
                            <div class="box two">Kullanıcı IP</div>
                            <div class="box two">Fonksiyon Tipi</div>
                            <div class="box two">Durum</div>
                            <div class="box two">MD Durumu</div>
                            <div class="box two">Hata Kodu</div>
                            <div class="box two">Hata Mesajı</div>
                            <div class="box two">Hata Grubu</div>
                            <div class="box two">Tarih</div>
                        </div>
                        <?php foreach ($web_data['order_status_errors'] as $order_status_errors) : ?>
                            <div class="order-error-row">
                                <span class="box-td two hovered-id-container">
                                    <i class="fas fa-info"></i>
                                    <span class="hovered-id">
                                        <?php echo $order_status_errors['conversation_id_request']; ?>
                                        <span class="hovered-id-triangle"></span>
                                    </span>
                                </span>
                                <span class="box-td two hovered-id-container">
                                    <i class="fas fa-info"></i>
                                    <span class="hovered-id">
                                        <?php echo $order_status_errors['conversation_id_response']; ?>
                                        <span class="hovered-id-triangle"></span>
                                    </span>
                                </span>
                                <div class="box-td two"><?php echo $order_status_errors['system_time']; ?></div>
                                <span class="box-td two hovered-id-container">
                                    <i class="fas fa-info"></i>
                                    <span class="hovered-id">
                                        <?php echo $order_status_errors['user_id']; ?>
                                        <span class="hovered-id-triangle"></span>
                                    </span>
                                </span>
                                <div class="box-td two"><?php echo $order_status_errors['user_ip']; ?></div>
                                <div class="box-td two"><?php echo $order_status_errors['function_type']; ?></div>
                                <div class="box-td two"><?php echo $order_status_errors['status']; ?></div>
                                <div class="box-td two"><?php echo $order_status_errors['mdStatus']; ?></div>
                                <div class="box-td two"><?php echo $order_status_errors['error_code']; ?></div>
                                <div class="box-td two"><?php echo $order_status_errors['error_message']; ?></div>
                                <div class="box-td two break-word"><?php echo $order_status_errors['error_group']; ?></div>
                                <div class="box-td two"><?php echo date('d/m/Y H:i:s', strtotime($order_status_errors['date_error_occred'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($web_data['order_error_type'] == URL_ADMIN_ORDER_STATUS_CODES) : ?>
                    <h1 class="title mb">Durum Kodları</h1>
                    <?php if (!empty($web_data['order_status_codes'])) : ?>
                        <div class="order-error-row">
                            <div class="box three center">Durum Kodu</div>
                            <div class="box three center">Durum Kod Mesajı</div>
                        </div>
                        <?php foreach ($web_data['order_status_codes'] as $order_status_codes) : ?>
                            <div class="order-error-row">
                                <div class="box-td three center"><?php echo $order_status_codes['status_number']; ?></div>
                                <div class="box-td three"><?php echo $order_status_codes['status_message']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($web_data['order_error_type'] == URL_ADMIN_ORDER_MD_STATUS_CODES) : ?>
                    <h1 class="title mb">MD Durum Kodları</h1>
                    <?php if (!empty($web_data['order_md_status_codes'])) : ?>
                        <div class="order-error-row">
                            <div class="box three center">MD Durum Kodu</div>
                            <div class="box three center">MD Durum Kod Mesajı</div>
                        </div>
                        <?php foreach ($web_data['order_md_status_codes'] as $order_md_status_codes) : ?>
                            <div class="order-error-row">
                                <div class="box-td three center"><?php echo $order_md_status_codes['md_status_number']; ?></div>
                                <div class="box-td three"><?php echo $order_md_status_codes['md_status_message']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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