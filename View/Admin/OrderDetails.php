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
            <div class="row-top">
                <div class="left">
                    <h1 class="first-title">Sipariş Detay</h1>
                </div>
                <div class="right right-order-row">
                    <?php if (!empty($web_data['order_payment'])) : ?>
                        <?php if ($web_data['order_payment']['fraud_status'] == 1) : ?>
                            <span class="fraud-text success-text" title="Ürün Kargoya Verilebilir">Fraud Riski: 1</span>
                        <?php elseif ($web_data['order_payment']['fraud_status'] == 0) : ?>
                            <span class="fraud-text warning-text" title="Ürünü Kargoya Vermeden Önce, Fraud Risk Skor Bilgilendirmesini Bekleyiniz">Fraud Riski: 0</span>
                        <?php elseif ($web_data['order_payment']['fraud_status'] == -1) : ?>
                            <span class="fraud-text danger-text" title="Ürün Fraud Risk Skoru Yüksek. Ürünü İptal Edin">Fraud Riski: -1</span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!empty($web_data['order_status_id'])) : ?>
                        <form class="order-wrapper" action="<?php echo URL . URL_ADMIN_ORDER_STATUS_CHANGE; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <?php if (!empty($web_data['form_token'])) : ?>
                                <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                            <?php endif; ?>
                            <input type="hidden" name="order_status_id" value="<?php echo $web_data['order_status_id']; ?>">
                            <span class="filter-label">Sipariş Durumu</span>
                            <div class="filter-row" title="Sipariş Durumunu Değiştir">
                                <div id="details-order-status" class="order-status">
                                    <select class="item-select" name="order_status">
                                        <option id="details-option-1" value="1"></option>
                                        <option id="details-option-2" value="2"></option>
                                        <option id="details-option-3" value="3"></option>
                                        <option id="details-option-4" value="4"></option>
                                        <option id="details-option-5" value="5"></option>
                                        <option id="details-option-6" value="6"></option>
                                        <option id="details-option-7" value="7"></option>
                                    </select>
                                    <?php if (!empty($web_data['order_status'])) : ?>
                                        <?php if ($web_data['order_status'] == 1) : ?>
                                            <span id="select-text" class="select-text">Onay Bekliyor</span>
                                        <?php elseif ($web_data['order_status'] == 2) : ?>
                                            <span id="select-text" class="select-text">Onaylandı. Kargoya Verilecek</span>
                                        <?php elseif ($web_data['order_status'] == 3) : ?>
                                            <span id="select-text" class="select-text">Onaylandı ve Kargoya Verildi</span>
                                        <?php elseif ($web_data['order_status'] == 4) : ?>
                                            <span id="select-text" class="select-text">Teslim Edildi</span>
                                        <?php elseif ($web_data['order_status'] == 5) : ?>
                                            <span id="select-text" class="select-text">İptal Edildi</span>
                                        <?php elseif ($web_data['order_status'] == 6) : ?>
                                            <span id="select-text" class="select-text">İade Edilen Sipariş Onaylandı ve Kargoya Verildi</span>
                                        <?php elseif ($web_data['order_status'] == 7) : ?>
                                            <span id="select-text" class="select-text">İade Edilen Sipariş Teslim Edildi</span>
                                        <?php else : ?>
                                            <span id="select-text" class="select-text danger">Bir Hata Oldu</span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span id="select-text" class="select-text danger">Bir Hata Oldu</span>
                                    <?php endif; ?>
                                    <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                                    <div id="details-select" class="details-select">
                                        <span class="option" data-option="1">Onay Bekliyor</span>
                                        <span class="option" data-option="2">Onaylandı. Kargoya Verilecek</span>
                                        <span class="option" data-option="3">Onaylandı ve Kargoya Verildi</span>
                                        <span class="option" data-option="4">Teslim Edildi</span>
                                        <span class="option" data-option="5">İptal Edildi</span>
                                        <span class="option" data-option="6">İade Edilen Sipariş Onaylandı ve Kargoya Verildi</span>
                                        <span class="option" data-option="7">İade Edilen Sipariş Teslim Edildi</span>
                                    </div>
                                </div>
                            </div>
                            <input class="btn-change-status" type="submit" value="Onayla">
                        </form>
                    <?php else : ?>
                        <span class="order-fail-text">Sipariş Başarısız</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($web_data['order_initialize_information'])) : ?>
                <div class="row">
                    <div class="box box-new-1 center th">ID</div>
                    <div class="box box-new-1 center th">Fiyat (₺)</div>
                    <div class="box box-new-1 center th" title="Ödenen İndirimli Fiyat">Ödenen Fiyat (₺)</div>
                    <div class="box box-new-1 center th">Taksit</div>
                    <div class="box box-new-1 center th">Kart Kayıt</div>
                    <div class="box box-new-1 center th">Kullanıcı</div>
                    <div class="box box-new-3 center th">Teslimat ve Fatura Adresi</div>
                    <div class="box box-new-1 center th">İade Durumu</div>
                    <div class="box box-new-1 center th">Sipariş Durumu</div>
                    <div class="box box-new-1 center th">Tarih</div>
                </div>
                <div class="row">
                    <div class="box box-new-1 hovered-id-container">
                        <i class="fas fa-info"></i>
                        <span class="hovered-id">
                            <textarea id="copy-text-id" class="text" readonly><?php echo $web_data['order_initialize_information']['id']; ?></textarea>
                            <i id="copy-to-clipboard-id" class="fas fa-copy" title="ID'yi Kopyala"></i>
                            <span class="hovered-id-triangle"></span>
                        </span>
                    </div>
                    <div class="box box-new-1 center"><?php echo $web_data['order_initialize_information']['price']; ?></div>
                    <div class="box box-new-1 center"><?php echo $web_data['order_initialize_information']['paid_price']; ?></div>
                    <div class="box box-new-1 center"><?php echo $web_data['order_initialize_information']['installment']; ?></div>
                    <div class="box box-new-1 center">
                        <?php if ($web_data['order_initialize_information']['register_card'] == 0) : ?>
                            Hayır
                        <?php else : ?>
                            Evet
                        <?php endif; ?>
                    </div>
                    <div class="box box-new-1 center show-user-hover" data-id="<?php echo $web_data['order_initialize_information']['id']; ?>" title="Kullanıcı Detayları İçin Tıklayın"><i class="fas fa-info"></i></div>
                    <div class="box box-new-3"><?php echo $web_data['order_initialize_information']['shipping_address'] . ' ' . $web_data['order_initialize_information']['shipping_city'] . '/' . $web_data['order_initialize_information']['shipping_country'] . ' ' . $web_data['order_initialize_information']['shipping_zip_code'] . ' ' . $web_data['order_initialize_information']['shipping_contact_name']; ?></div>
                    <div class="box box-new-1 center"><?php echo $web_data['order_initialize_information']['is_returned']; ?></div>
                    <div class="box box-new-1 center"><?php echo $web_data['order_initialize_information']['status']; ?></div>
                    <div class="box box-new-1 center"><?php echo date('d/m/Y H:i:s', strtotime($web_data['order_initialize_information']['date_order_initialize_created'])); ?></div>
                </div>
                <div class="user-hover-wrapper" id="user_hover_wrapper_<?php echo $web_data['order_initialize_information']['id']; ?>">
                    <div class="user-hover-container">
                        <div class="close remove-user-hover" data-id="<?php echo $web_data['order_initialize_information']['id']; ?>"><i class="fas fa-times"></i></div>
                        <div class="row-user">
                            <div class="box-user th">Kullanıcı IP</div>
                            <div class="box-user th">Kullanıcı İsim</div>
                            <div class="box-user th">Kullanıcı Soy İsim</div>
                            <div class="box-user th">Kullanıcı Email</div>
                            <div class="box-user th">Kullanıcı Kimlik Numarası</div>
                            <div class="box-user th">Kullanıcı Telefon Numarası</div>
                            <div class="box-user th">Detaylar</div>
                        </div>
                        <div class="row-user">
                            <div class="box-user"><?php echo $web_data['order_initialize_information']['user_ip']; ?></div>
                            <div class="box-user"><?php echo $web_data['order_initialize_information']['user_first_name']; ?></div>
                            <div class="box-user"><?php echo $web_data['order_initialize_information']['user_last_name']; ?></div>
                            <div class="box-user"><?php echo $web_data['order_initialize_information']['user_email']; ?></div>
                            <div class="box-user"><?php echo $web_data['order_initialize_information']['user_identity_number']; ?></div>
                            <div class="box-user"><?php echo $web_data['order_initialize_information']['user_phone_number']; ?></div>
                            <a class="box-user" href="<?php echo URL . URL_ADMIN_USER_DETAILS . '/' . $web_data['order_initialize_information']['user_id']; ?>" target="_blanck"><i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($web_data['order_initialize_basket'])) : ?>
                <h2 class="second-title">Sepetteki Ürün Detayları</h2>
                <div class="row">
                    <div class="box box-11 center th">Ürün ID</div>
                    <div class="box box-11 center th">İsim</div>
                    <div class="box box-11 center th">Beden</div>
                    <div class="box box-11 center th">Adet</div>
                    <div class="box box-11 center th">Toplam Fiyat (₺)</div>
                    <div class="box box-11 center th">Toplam Ödenen Fiyat (₺)</div>
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
                        <div class="box box-11 center"><?php echo $order_initialize_basket['item_price']; ?></div>
                        <div class="box box-11 center"><?php echo $order_initialize_basket['item_discount_price']; ?></div>
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
                    <div class="box box-12 center th info" title="Ödeme sepet tutarı.">Fiyat (₺)</div>
                    <div class="box box-12 center th info" title="İndirim vade farkı vs. hesaplanmış POS’tan geçen, tahsil edilen, nihai tutar.">Ödenen Fiyat (₺)</div>
                    <div class="box box-12 center th info" title="Ödemenin alındığı para birimi.">Para Birimi</div>
                    <div class="box box-12 center th info" title="Ödemenin taksit bilgisi.">Taksit</div>
                    <div class="box box-12 center th info" title="Ödemeye ait iyzico işlem ücreti.">İyzico İşlem Ücreti (₺)</div>
                    <div class="box box-12 center th info" title="Ödemeye ait iyzico işlem komisyon tutarı.">İyzico Komisyon Tutarı (₺)</div>
                    <div class="box box-12 center th info" title="<?php echo BRAND; ?>'in uyguladığı vade/komisyon oranı. Örneğin ücret 100, ödenen ücret 110 ise <?php echo BRAND; ?> vade/komisyon oranı %10’dur."><?php echo BRAND; ?> Komisyon Oranı</div>
                    <div class="box box-12 center th info" title="<?php echo BRAND; ?>'in uyguladığı vade/komisyon tutarı. Örneğin ücret 100, ödenen ücret 110 ise <?php echo BRAND; ?> vade/komisyon tutarı 10’dur."><?php echo BRAND; ?> Komisyon Tutarı (₺)</div>
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
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['price']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['paid_price']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['currency']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['installment']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['iyzi_commission_fee']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['iyzi_commission_rate_amount']; ?></div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['merchant_commission_rate']; ?>%</div>
                    <div class="box box-12 center"><?php echo $web_data['order_payment']['merchant_commission_rate_amount']; ?></div>
                    <div class="box box-12 center"><?php echo date('d/m/Y H:i:s', strtotime($web_data['order_payment']['date_order_payment_created'])); ?></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($web_data['order_payment_item_transaction'])) : ?>
                <h2 class="second-title">Ödeme Ürün Detayları</h2>
                <div class="row">
                    <div class="box box-13 center th rotated info" title="Ödeme kırılımına ait id. Ödeme kırılımının iadesi, onayı, onay geri çekmesi ve iyzico ile iletişimde kullanılır.">İşlem ID</div>
                    <div class="box box-13 center th rotated info" title="Ödeme kırılımının durumu. Ödeme fraud kontrolünde ise 0 değeri döner, bu durumda fraudStatus değeri de 0’dır. Ödeme, fraud kontrolünden sonra reddedilirse -1 döner. Pazaryeri modelinde ürüne onay verilene dek bu değer 1 olarak döner. Pazaryeri modelinde ürüne onay verilmişse bu değer 2 olur. Geçerli değerler: 0, -1, 1, 2">Fraud Durumu</div>
                    <div class="box box-13 center th rotated info" title="<?php echo BRAND; ?> tarafından iletilen, sepetteki ürüne ait id.">Ürün ID</div>
                    <div class="box box-13 center th rotated info" title="<?php echo BRAND; ?> tarafındaki sepetteki ürüne ait tutar.">Fiyat (₺)</div>
                    <div class="box box-13 center th rotated info" title="Tahsilat tutarının kırılım bazındaki dağılımı.">Ödenen Fiyat (₺)</div>
                    <div class="box box-13 center th rotated info" title="Kırılım bazında <?php echo BRAND; ?> blokaj oranı. Bu blokaj <?php echo BRAND; ?> fraud riskini önlemek içindir. Blokaj süresi boyunca para iyzico’da tutulur, bu süre sonrasında <?php echo BRAND; ?>'e gönderilir.">Blokaj Oranı</div>
                    <div class="box box-13 center th rotated info" title="Kırılım bazında <?php echo BRAND; ?> blokaj tutarının, <?php echo BRAND; ?>'e yansıyan rakamı. Blokaj tutarı mümkün olduğunca <?php echo BRAND; ?>'e yansıtılır. Eğer blokaj tutarı, <?php echo BRAND; ?> tutarından daha büyükse bu durumda alt <?php echo BRAND; ?>'e de yansıtılır.">Blokaj Tutarı</div>
                    <div class="box box-13 center th rotated info" title="İşlem bazında blokaj çözülme tarihi.">Blokaj Çözülme Tarihi</div>
                    <div class="box box-13 center th rotated info" title="İyzico işlem ücretinin kırılım bazında dağılmış tutarı.">İyzico İşlem Ücreti (₺)</div>
                    <div class="box box-13 center th rotated info" title="İyzico işlem komisyon tutarının kırılım bazında dağılmış tutarı.">İyzico Komisyon Tutarı (₺)</div>
                    <div class="box box-13 center th rotated info" title="<?php echo BRAND; ?>'in uyguladığı vade/komisyon oranının kırılım bazında dağılmış oranı."><?php echo BRAND; ?> Vade/Komisyon Oranı</div>
                    <div class="box box-13 center th rotated info" title="<?php echo BRAND; ?>'in uyguladığı vade/komisyon tutarının, kırılım bazında dağılmış tutarı."><?php echo BRAND; ?> Vade/Komisyon Tutarı (₺)</div>
                    <div class="box box-13 center th rotated info" title="Bu kırılım için, iyzico işlem ücreti, komisyon tutarı ve blokajlar düşüldükten sonra <?php echo BRAND; ?>'e gönderilecek tutar."><?php echo BRAND; ?> Payı (₺)</div>
                    <div class="box box-13 center th rotated info" title="Tahsilat tutarının kırılım bazındaki dağılımı.">Çevrilmiş Ödenen Fiyat (₺)</div>
                    <div class="box box-13 center th rotated info" title="İyzico işlem ücretinin kırılım bazında dağılmış tutarı.">Çevrilmiş İyzico İşlem Ücreti (₺)</div>
                    <div class="box box-13 center th rotated info" title="İyzico işlem komisyon tutarının kırılım bazında dağılmış tutarı.">Çevrilmiş İyzico Komisyon Tutarı (₺)</div>
                    <div class="box box-13 center th rotated info" title="Kırılım bazında <?php echo BRAND; ?> blokaj tutarının, <?php echo BRAND; ?>'e yansıyan rakamı. Blokaj tutarı mümkün olduğunca <?php echo BRAND; ?>'e yansıtılır. Eğer blokaj tutarı, <?php echo BRAND; ?> tutarından daha büyükse bu durumda alt <?php echo BRAND; ?>'e de yansıtılır.">Çevrilmiş Blokaj Tutarı</div>
                    <div class="box box-13 center th rotated info" title="Bu kırılım için, iyzico işlem ücreti, komisyon tutarı ve blokajlar düşüldükten sonra <?php echo BRAND; ?>'e gönderilecek tutar.">Çevrilmiş <?php echo BRAND; ?> Payı (₺)</div>
                    <div class="box box-13 center th rotated info" title="Bu kırılım için, eğer döviz işlemi yapılmışsa çevrim ücreti oranı.">Çevrim Ücret Oranı</div>
                    <div class="box box-13 center th rotated info" title="Bu kırılım için, eğer döviz işlemi yapılmışsa çevrim ücreti tutarı.">Çevrim Ücreti Tutarı</div>
                    <div class="box box-13 center th rotated info" title="Ödemenin alındığı para birimi.">Ödemenin Alındığı Para Birimi</div>
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
                        <div class="box box-13 center"><?php echo $order_payment_item_transaction['merchant_commission_rate']; ?>%</div>
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
        document.getElementById('details-order-status').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('details-select').classList.toggle('active');
        });
        document.querySelectorAll('.order-status .details-select .option').forEach(detailsSelectOption => {
            detailsSelectOption.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('details-option-' + detailsSelectOption.dataset.option).selected = true;
                if (detailsSelectOption.dataset.option == 1) {
                    document.getElementById('select-text').innerHTML = 'Onay Bekliyor';
                } else if (detailsSelectOption.dataset.option == 2) {
                    document.getElementById('select-text').innerHTML = 'Onaylandı. Kargoya Verilecek';
                } else if (detailsSelectOption.dataset.option == 3) {
                    document.getElementById('select-text').innerHTML = 'Onaylandı ve Kargoya Verildi';
                } else if (detailsSelectOption.dataset.option == 4) {
                    document.getElementById('select-text').innerHTML = 'Teslim Edildi';
                } else if (detailsSelectOption.dataset.option == 5) {
                    document.getElementById('select-text').innerHTML = 'İptal Edildi';
                } else if (detailsSelectOption.dataset.option == 6) {
                    document.getElementById('select-text').innerHTML = 'İade Edilen Sipariş Onaylandı ve Kargoya Verildi';
                } else if (detailsSelectOption.dataset.option == 7) {
                    document.getElementById('select-text').innerHTML = 'İade Edilen Sipariş Teslim Edildi';
                }
            });
        });
        document.querySelector('.btn-change-status').addEventListener('click', (e) => {
            e.preventDefault();
            if (loaderWrapper.classList.contains('hidden')) {
                loaderWrapper.classList.remove('hidden');
            }
            if (!bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.add('noscroll');
            }
            document.querySelector('.order-wrapper').submit();
        });
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
        document.getElementById('copy-to-clipboard-id').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('copy-text-id').select();
            document.execCommand('copy');

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