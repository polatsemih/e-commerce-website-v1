<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Siparişler - Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <main>
        <section class="order-section container">
            <h1 class="title">Siparişler</h1>
            <div class="row">
                <div class="box box-1 center th">ID</div>
                <div class="box box-1 center th">Fiyat (₺)</div>
                <div class="box box-1 center th" title="Ödenen İndirimli Fiyat">Ödenen Fiyat (₺)</div>
                <div class="box box-1 center th">Taksit</div>
                <div class="box box-1 center th">Kart Kayıt</div>
                <div class="box box-1 center th">Kullanıcı</div>
                <div class="box box-3 center th">Teslimat ve Fatura Adresi</div>
                <div class="box box-1 center th">İade Durumu</div>
                <div class="box box-1 center th">Sipariş Durumu</div>
                <div class="box box-1 center th">Tarih</div>
                <div class="box box-1 center th">Detaylar</div>
            </div>
            <?php if (!empty($web_data['order_initialize_informations'])) : ?>
                <?php foreach ($web_data['order_initialize_informations'] as $order_initialize_informations) : ?>
                    <div class="row">
                        <div class="box box-1 hovered-id-container">
                            <i class="fas fa-info"></i>
                            <span class="hovered-id">
                                <?php echo $order_initialize_informations['id']; ?>
                                <span class="hovered-id-triangle"></span>
                            </span>
                        </div>
                        <div class="box box-1 center"><?php echo $order_initialize_informations['price']; ?></div>
                        <div class="box box-1 center"><?php echo $order_initialize_informations['paid_price']; ?></div>
                        <div class="box box-1 center"><?php echo $order_initialize_informations['installment']; ?></div>
                        <div class="box box-1 center">
                            <?php if ($order_initialize_informations['register_card'] == 0) : ?>
                                Hayır
                            <?php else : ?>
                                Evet
                            <?php endif; ?>
                        </div>
                        <div class="box box-1 center show-user-hover" data-id="<?php echo $order_initialize_informations['id']; ?>" title="Kullanıcı Detayları İçin Tıklayın"><i class="fas fa-info"></i></div>
                        <div class="box box-3"><?php echo $order_initialize_informations['shipping_address'] . ' ' . $order_initialize_informations['shipping_city'] . '/' . $order_initialize_informations['shipping_country'] . ' ' . $order_initialize_informations['shipping_zip_code'] . ' ' . $order_initialize_informations['shipping_contact_name']; ?></div>
                        <div class="box box-1 center"><?php echo $order_initialize_informations['is_returned']; ?></div>
                        <div class="box box-1 center"><?php echo $order_initialize_informations['status']; ?></div>
                        <div class="box box-1 center"><?php echo date('d/m/Y H:i:s', strtotime($order_initialize_informations['date_order_initialize_created'])); ?></div>
                        <a class="box box-1 center" href="<?php echo URL . URL_ADMIN_ORDER_DETAILS . '/' . $order_initialize_informations['id']; ?>"><i class="fas fa-chevron-right"></i></a>
                    </div>
                    <div class="user-hover-wrapper" id="user_hover_wrapper_<?php echo $order_initialize_informations['id']; ?>">
                        <div class="user-hover-container">
                            <div class="close remove-user-hover" data-id="<?php echo $order_initialize_informations['id']; ?>"><i class="fas fa-times"></i></div>
                            <div class="row-user">
                                <div class="box-user th">Kullanıcı IP</div>
                                <div class="box-user th">Kullanıcı İsim</div>
                                <div class="box-user th">Kullanıcı Soy İsim</div>
                                <div class="box-user th">Kullanıcı Email</div>
                                <div class="box-user th">Kullanıcı Kimlik Numarası</div>
                                <div class="box-user th">Detaylar</div>
                            </div>
                            <div class="row-user">
                                <div class="box-user"><?php echo $order_initialize_informations['user_ip']; ?></div>
                                <div class="box-user"><?php echo $order_initialize_informations['user_first_name']; ?></div>
                                <div class="box-user"><?php echo $order_initialize_informations['user_last_name']; ?></div>
                                <div class="box-user"><?php echo $order_initialize_informations['user_email']; ?></div>
                                <div class="box-user"><?php echo $order_initialize_informations['user_identity_number']; ?></div>
                                <a class="box-user" href="<?php echo URL . URL_ADMIN_USER_DETAILS . '/' . $order_initialize_informations['user_id']; ?>" target="_blanck"><i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
    <script>
        document.querySelectorAll('.show-user-hover').forEach(element => {
            element.addEventListener('click', (e) => {
                var hoverWrapper = document.getElementById('user_hover_wrapper_' + element.dataset.id);
                if (!hoverWrapper.classList.contains('active')) {
                    hoverWrapper.classList.add('active');
                }
            });
        });
        document.querySelectorAll('.remove-user-hover').forEach(element => {
            element.addEventListener('click', (e) => {
                var hoverWrapper = document.getElementById('user_hover_wrapper_' + element.dataset.id);
                if (hoverWrapper.classList.contains('active')) {
                    hoverWrapper.classList.remove('active');
                }
            });
        });
    </script>
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