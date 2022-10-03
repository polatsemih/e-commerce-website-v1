<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Sipariş Detay - Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <main>
        <section class="order-section container">
            <h1 class="first-title">Sipariş Detay</h1>
            <?php if (!empty($web_data['order_initialize_basket'])) : ?>
                <h2 class="second-title">Sepetteki Ürün Detayları</h2>
                <div class="row">
                    <div class="box box-11 center th">Ürün ID</div>
                    <div class="box box-11 center th">İsim</div>
                    <div class="box box-11 center th">Beden</div>
                    <div class="box box-11 center th">Adet</div>
                    <div class="box box-11 center th">Toplam Fiyat</div>
                    <div class="box box-11 center th">Toplam Ödenen Fiyat</div>
                    <div class="box box-11 center th">Kategori</div>
                    <div class="box box-11 center th">Tipi</div>
                    <div class="box box-11 center th">Tarih</div>
                </div>
                <?php foreach ($web_data['order_initialize_basket'] as $order_initialize_basket) : ?>
                    <div class="row">
                        <div class="box box-11 hovered-id-container">
                            <i class="fas fa-info"></i>
                            <span class="hovered-id">
                                <?php echo $order_initialize_basket['item_id']; ?>
                                <span class="hovered-id-triangle"></span>
                            </span>
                        </div>
                        <div class="box box-11"><?php echo $order_initialize_basket['item_name']; ?></div>
                        <div class="box box-11 center"><?php echo $order_initialize_basket['item_size_name']; ?></div>
                        <div class="box box-11 center"><?php echo $order_initialize_basket['item_quantity']; ?></div>
                        <div class="box box-11 center"><?php echo $order_initialize_basket['item_price']; ?> ₺</div>
                        <div class="box box-11 center"><?php echo $order_initialize_basket['item_discount_price']; ?> ₺</div>
                        <div class="box box-11 center"><?php echo $order_initialize_basket['item_category']; ?></div>
                        <?php if ($order_initialize_basket['item_type'] == 'PHYSICAL') : ?>
                            <div class="box box-11 center">FİZİKSEL</div>
                        <?php else : ?>
                            <div class="box box-11 center"><?php echo $order_initialize_basket['item_type']; ?></div>
                        <?php endif; ?>
                        <div class="box box-11 center"><?php echo date('d/m/Y H:i:s', strtotime($order_initialize_basket['date_order_initialize_basket_created'])); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($web_data['order_payment'])) : ?>
                <h2 class="second-title">Genel Ödeme Detayları</h2>
                <div class="row">
                    <div class="box box-12 center th info" title="Ödemeye ait id, ödemenin iptali ve iyzico ile iletişimde kullanılır.">Ödeme ID</div>
                    <div class="box box-12 center th info" title="Ödeme işleminin fraud filtrelerine göre durumu. Eğer ödemenin fraud risk skoru düşük ise ödemeye anında onay verilir bu durumda 1 değeri döner. Eğer fraud risk skoru yüksek ise ödeme işlemi reddedilir ve -1 döner. Eğer ödeme işlemi daha sonradan incelenip karar verilecekse 0 döner. Geçerli değerler: 0, -1 ve 1. <?php echo BRAND; ?> sadece 1 olan işlemlerde ürünü kargoya vermelidir, 0 olan işlemler için bilgilendirme beklenmelidir.">Fraud Durumu</div>
                    <div class="box box-12 center th info" title="Ödeme sepet tutarı.">Fiyat</div>
                    <div class="box box-12 center th info" title="İndirim vade farkı vs. hesaplanmış POS’tan geçen, tahsil edilen, nihai tutar.">Ödenen Fiyat</div>
                    <div class="box box-12 center th info" title="Ödemenin alındığı para birimi.">Para Birimi</div>
                    <div class="box box-12 center th info" title="Ödemenin taksit bilgisi.">Taksit</div>
                    <div class="box box-12 center th info" title="Ödemeye ait iyzico işlem ücreti.">İyzico İşlem Ücreti</div>
                    <div class="box box-12 center th info" title="Ödemeye ait iyzico işlem komisyon tutarı.">İyzico Komisyon Tutarı</div>
                    <div class="box box-12 center th info" title="<?php echo BRAND; ?>'in uyguladığı vade/komisyon oranı. Örneğin ücret 100, ödenen ücret 110 ise <?php echo BRAND; ?> vade/komisyon oranı %10’dur."><?php echo BRAND; ?> Komisyon Oranı</div>
                    <div class="box box-12 center th info" title="<?php echo BRAND; ?>'in uyguladığı vade/komisyon tutarı. Örneğin ücret 100, ödenen ücret 110 ise <?php echo BRAND; ?> vade/komisyon tutarı 10’dur."><?php echo BRAND; ?> Komisyon Tutarı</div>
                    <div class="box box-12 center th">Tarih</div>
                </div>
                <div class="row">
                    <div class="box box-12 hovered-id-container">
                        <i class="fas fa-info"></i>
                        <span class="hovered-id">
                            <?php echo $web_data['order_payment']['payment_id']; ?>
                            <span class="hovered-id-triangle"></span>
                        </span>
                    </div>
                    <div class="box box-12"><?php echo $web_data['order_payment']['fraud_status']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['price']; ?> ₺</div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['paid_price']; ?> ₺</div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['currency']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['installment']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['iyzi_commission_fee']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['iyzi_commission_rate_amount']; ?></div>
                    <div class="box box-12 center">%<?php echo $web_data['order_payment']['merchant_commission_rate']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['merchant_commission_rate_amount']; ?></div>
                    <div class="box box-12 center"><?php echo date('d/m/Y H:i:s', strtotime($web_data['order_payment']['date_order_payment_created'])); ?></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($web_data['order_payment_item_transaction'])) : ?>
                <h2 class="second-title">Ödeme Ürün Detayları</h2>
                <div class="row">
                    <div class="box box-13 center th rotated info" title="Ödeme kırılımına ait id. Ödeme kırılımının iadesi, onayı, onay geri çekmesi ve iyzico ile iletişimde kullanılır.">İşlem ID</div>
                    <div class="box box-13 center th rotated info" title="Ödeme kırılımının durumu. Ödeme fraud kontrolünde ise 0 değeri döner, bu durumda fraudStatus değeri de 0’dır. Ödeme, fraud kontrolünden sonra reddedilirse -1 döner. Pazaryeri modelinde ürüne onay verilene dek bu değer 1 olarak döner. Pazaryeri modelinde ürüne onay verilmişse bu değer 2 olur. Geçerli değerler: 0, -1, 1, 2">İşlem Durumu</div>
                    <div class="box box-13 center th rotated info" title="<?php echo BRAND; ?> tarafından iletilen, sepetteki ürüne ait id.">Ürün ID</div>
                    <div class="box box-13 center th rotated info" title="<?php echo BRAND; ?> tarafındaki sepetteki ürüne ait tutar.">Fiyat (₺)</div>
                    <div class="box box-13 center th rotated info" title="Tahsilat tutarının kırılım bazındaki dağılımı.">Ödenen Fiyat (₺)</div>
                    <div class="box box-13 center th rotated info" title="Kırılım bazında <?php echo BRAND; ?> blokaj oranı. Bu blokaj <?php echo BRAND; ?> fraud riskini önlemek içindir. Blokaj süresi boyunca para iyzico’da tutulur, bu süre sonrasında <?php echo BRAND; ?>'e gönderilir.">Blokaj Oranı</div>
                    <div class="box box-13 center th rotated info" title="Kırılım bazında <?php echo BRAND; ?> blokaj tutarının, <?php echo BRAND; ?>'e yansıyan rakamı. Blokaj tutarı mümkün olduğunca <?php echo BRAND; ?>'e yansıtılır. Eğer blokaj tutarı, <?php echo BRAND; ?> tutarından daha büyükse bu durumda alt <?php echo BRAND; ?>'e de yansıtılır.">Blokaj Tutarı</div>
                    <div class="box box-13 center th rotated info" title="İşlem bazında blokaj çözülme tarihi.">Blokaj Çözülme Tarihi</div>
                    <div class="box box-13 center th rotated info" title="İyzico işlem ücretinin kırılım bazında dağılmış tutarı.">K.B.D.T.</div>
                    <div class="box box-13 center th rotated info" title="İyzico işlem komisyon tutarının kırılım bazında dağılmış tutarı.">K.T.K.B.D.T.</div>
                    <div class="box box-13 center th rotated info" title="<?php echo BRAND; ?>'in uyguladığı vade/komisyon oranının kırılım bazında dağılmış oranı.">Vade/Komisyon Oranı</div>
                    <div class="box box-13 center th rotated info" title="<?php echo BRAND; ?>'in uyguladığı vade/komisyon tutarının, kırılım bazında dağılmış tutarı.">Vade/Komisyon Tutarı</div>
                    <div class="box box-13 center th rotated info" title="Bu kırılım için, iyzico işlem ücreti, komisyon tutarı ve blokajlar düşüldükten sonra <?php echo BRAND; ?>'e gönderilecek tutar.">Ödenecek Tutar</div>
                    <div class="box box-13 center th rotated info" title="Tahsilat tutarının kırılım bazındaki dağılımı.">T.T.K.B.D</div>
                    <div class="box box-13 center th rotated info" title="İyzico işlem ücretinin kırılım bazında dağılmış tutarı.">İ.Ü.K.B.D.T.</div>
                    <div class="box box-13 center th rotated info" title="İyzico işlem komisyon tutarının kırılım bazında dağılmış tutarı.">İ.K.T.K.B.D.T.</div>
                    <div class="box box-13 center th rotated info" title="Kırılım bazında <?php echo BRAND; ?> blokaj tutarının, <?php echo BRAND; ?>'e yansıyan rakamı. Blokaj tutarı mümkün olduğunca <?php echo BRAND; ?>'e yansıtılır. Eğer blokaj tutarı, <?php echo BRAND; ?> tutarından daha büyükse bu durumda alt <?php echo BRAND; ?>'e de yansıtılır.">B.T.</div>
                    <div class="box box-13 center th rotated info" title="Bu kırılım için, iyzico işlem ücreti, komisyon tutarı ve blokajlar düşüldükten sonra <?php echo BRAND; ?>'e gönderilecek tutar.">B.T.2</div>
                    <div class="box box-13 center th rotated info" title="Bu kırılım için, eğer döviz işlemi yapılmışsa çevrim ücreti oranı.">Ç.Ü.O.</div>
                    <div class="box box-13 center th rotated info" title="Bu kırılım için, eğer döviz işlemi yapılmışsa çevrim ücreti tutarı.">Ç.Ü.T.</div>
                    <div class="box box-13 center th rotated info" title="Ödemenin alındığı para birimi.">Birim</div>
                    <div class="box box-13 center th rotated">Tarih</div>
                </div>
                <?php foreach ($web_data['order_payment_item_transaction'] as $order_payment_item_transaction) : ?>
                    <div class="row">
                        <div class="box box-13 hovered-id-container">
                            <i class="fas fa-info"></i>
                            <span class="hovered-id">
                                <?php echo $order_payment_item_transaction['payment_transaction_id']; ?>
                                <span class="hovered-id-triangle"></span>
                            </span>
                        </div>
                        <div class="box box-13"><?php echo $order_payment_item_transaction['transaction_status']; ?></div>
                        <div class="box box-13 hovered-id-container">
                            <i class="fas fa-info"></i>
                            <span class="hovered-id">
                                <?php echo $order_payment_item_transaction['item_id']; ?>
                                <span class="hovered-id-triangle"></span>
                            </span>
                        </div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['price']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['paid_price']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['blockage_rate']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['blockage_rate_amount_merchant']; ?></div>
                        <div class="box box-13 center"><?php echo date('d/m/Y H:i:s', strtotime($order_payment_item_transaction['blockage_resolved_date'])); ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['iyzi_commission_fee']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['iyzi_commission_rate_amount']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['merchant_commission_rate']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['merchant_commission_rate_amount']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['merchant_payout_amount']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['converted_payout_paid_price']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['converted_payout_iyzi_commission_fee']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['converted_payout_iyzi_commission_rate_amount']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['converted_payout_blockage_rate_amount_merchant']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['converted_payout_merchant_payout_amount']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['converted_payout_iyzi_conversation_rate']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['converted_payout_iyzi_conversation_rate_amount']; ?></div>
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['converted_payout_currency']; ?></div>
                        <div class="box box-13 center"><?php echo date('d/m/Y H:i:s', strtotime($order_payment_item_transaction['date_payment_item_transaction_created'])); ?></div>
                    </div>
                <?php endforeach; ?>
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