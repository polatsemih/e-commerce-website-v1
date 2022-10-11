<?php
class OrderController extends ControllerOrder
{
    function __construct()
    {
        parent::__construct();
    }
    function OrderPayment()
    {
        try {
            if (!WEB_SHOPPING_PERMISSION) {
                if ($_SERVER['REMOTE_ADDR'] != ADMIN_IP_ADDRESS) {
                    $this->notification_control->SetNotification('DANGER', WEB_SHOPPING_PERMISSION_FALSE);
                    $this->input_control->Redirect(URL_CART);
                }
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_COOKIE[COOKIE_AUTHENTICATION_CROSS_SITE_NAME])) {
                $cookie_authentication_error = true;
                $checked_cookie_authentication = $this->input_control->CheckGETInputWithLength($_COOKIE[COOKIE_AUTHENTICATION_CROSS_SITE_NAME], 400);
                if (!empty($checked_cookie_authentication)) {
                    $extracted_cookie_authentication_token1 = substr($checked_cookie_authentication, 200, 200);
                    $extracted_cookie_authentication_token2 = substr($checked_cookie_authentication, 0, 200);
                    if (!empty($extracted_cookie_authentication_token1) && !empty($extracted_cookie_authentication_token2)) {
                        $cookie_authentication_from_database = $this->ActionModel->GetCookieAuthenticationCrossSite(array($_SERVER['REMOTE_ADDR'], $extracted_cookie_authentication_token2));
                        if ($cookie_authentication_from_database['result']) {
                            if ($cookie_authentication_from_database['data']['date_cookie_authentication_cross_site_expiry'] > date('Y-m-d H:i:s') && $cookie_authentication_from_database['data']['is_cookie_authentication_cross_site_used'] == 0 && $this->ActionModel->UpdateCookieAuthenticationCrossSite(array('is_cookie_authentication_cross_site_used' => 1, 'id' => $cookie_authentication_from_database['data']['id']))['result']) {
                                $cookie_authentication_token1 = hash_hmac('SHA512', $extracted_cookie_authentication_token1, $cookie_authentication_from_database['data']['cookie_authentication_cross_site_salt'], false);
                                if (hash_equals($cookie_authentication_from_database['data']['cookie_authentication_cross_site_token1'], $cookie_authentication_token1)) {
                                    $authenticated_user_from_database = $this->UserModel->GetUserByUserId('id,email,is_user_blocked', $cookie_authentication_from_database['data']['user_id']);
                                    if ($authenticated_user_from_database['result'] && $this->ActionModel->UpdateCookieAuthenticationCrossSite(array('is_cookie_authentication_cross_site_success' => 1, 'id' => $cookie_authentication_from_database['data']['id']))['result']) {
                                        $cookie_authentication_error = false;
                                        if ($authenticated_user_from_database['data']['is_user_blocked'] == 0) {
                                            $conversation_id = $this->input_control->GenerateToken();
                                            $last_conversation_id = $this->ItemModel->GetOrderInitializeLastConversationId(array($_SERVER['REMOTE_ADDR'], $authenticated_user_from_database['data']['id']));
                                            if ($conversation_id['result'] && $last_conversation_id['result']) {
                                                if (isset($_POST['mdStatus']) && isset($_POST['status']) && isset($_POST['conversationId'])) {
                                                    if ($_POST['mdStatus'] == 1 && $_POST['status'] == 'success') {
                                                        if ($last_conversation_id['data']['conversation_id'] == $_POST['conversationId']) {
                                                            if (isset($_POST['paymentId'])) {
                                                                if (isset($_POST['conversationData'])) {
                                                                    $result_order_verify_created = $this->ItemModel->CreateOrderVerify(array(
                                                                        'conversation_id_initialize_request' => $last_conversation_id['data']['conversation_id'],
                                                                        'conversation_id_payment_request' => $conversation_id['data'],
                                                                        'payment_id' => $this->input_control->SlashAndXSSForId($_POST['paymentId']),
                                                                        'conversation_data' => $this->input_control->SlashAndXSSForId($_POST['conversationData']),
                                                                    ));
                                                                } else {
                                                                    $result_order_verify_created = $this->ItemModel->CreateOrderVerify(array(
                                                                        'conversation_id_initialize_request' => $last_conversation_id['data']['conversation_id'],
                                                                        'conversation_id_payment_request' => $conversation_id['data'],
                                                                        'payment_id' => $this->input_control->SlashAndXSSForId($_POST['paymentId'])
                                                                    ));
                                                                }
                                                                if ($result_order_verify_created['result']) {
                                                                    require_once(IYZIPAY_FOLDER_NAME . '/samples/config.php');
                                                                    $request = new \Iyzipay\Request\CreateThreedsPaymentRequest();
                                                                    $request->setLocale(\Iyzipay\Model\Locale::TR);
                                                                    $request->setConversationId($conversation_id['data']);
                                                                    $request->setPaymentId($_POST['paymentId']);
                                                                    if (isset($_POST['conversationData'])) {
                                                                        $request->setConversationData($_POST['conversationData']);
                                                                    }
                                                                    $threedsPayment = \Iyzipay\Model\ThreedsPayment::create($request, Config::options());
                                                                    if ($threedsPayment->getStatus() == 'success') {
                                                                        if ($threedsPayment->getConversationId() == $conversation_id['data']) {
                                                                            $result_create_order_payment = $this->ItemModel->CreateOrderPayment(array(
                                                                                'conversation_id_request' => $conversation_id['data'],
                                                                                'order_initialize_information_id' => $this->input_control->SlashAndXSSForId($threedsPayment->getBasketId()),
                                                                                'payment_id' => $this->input_control->SlashAndXSSForId($threedsPayment->getPaymentId()),
                                                                                'fraud_status' => $this->input_control->SlashAndXSSForId($threedsPayment->getFraudStatus()),
                                                                                'price' => $this->input_control->SlashAndXSSForId($threedsPayment->getPrice()),
                                                                                'paid_price' => $this->input_control->SlashAndXSSForId($threedsPayment->getPaidPrice()),
                                                                                'currency' => $this->input_control->SlashAndXSSForId($threedsPayment->getCurrency()),
                                                                                'installment' => $this->input_control->SlashAndXSSForId($threedsPayment->getInstallment()),
                                                                                'iyzi_commission_fee' => $this->input_control->SlashAndXSSForId($threedsPayment->getIyziCommissionFee()),
                                                                                'iyzi_commission_rate_amount' => $this->input_control->SlashAndXSSForId($threedsPayment->getIyziCommissionRateAmount()),
                                                                                'merchant_commission_rate' => $this->input_control->SlashAndXSSForId($threedsPayment->getMerchantCommissionRate()),
                                                                                'merchant_commission_rate_amount' => $this->input_control->SlashAndXSSForId($threedsPayment->getMerchantCommissionRateAmount())
                                                                            ));
                                                                            if (!$result_create_order_payment['result']) {
                                                                                $this->action_control->SendMail(ADMIN_EMAIL, BRAND . 'Order Payment Error', 'Order payment create database error. Payment ID : ' . $this->input_control->SlashAndXSSForId($threedsPayment->getPaymentId()));
                                                                                $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class OrderController function OrderPayment | Database Create Payment Error, Payment ID : ' . $this->input_control->SlashAndXSSForId($threedsPayment->getPaymentId())));
                                                                                $this->ActionModel->CreateLogEmailSent(array('user_id' => $authenticated_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'Order Payment DB Error'));
                                                                            }
                                                                            $this->UserModel->UpdateUser(array(
                                                                                'is_user_shopped' => 1,
                                                                                'id' => $authenticated_user_from_database['data']['id']
                                                                            ));
                                                                            $this->ItemModel->UpdateOrder(array(
                                                                                'status' => 1,
                                                                                'id' => $this->input_control->SlashAndXSSForId($threedsPayment->getBasketId())
                                                                            ));
                                                                            $order_mail_items = '';
                                                                            foreach ($threedsPayment->getPaymentItems() as $key => $payment_item) {
                                                                                $result_create_item_transaction = $this->ItemModel->CreateOrderPaymentItemTransaction(array(
                                                                                    'payment_id' => $this->input_control->SlashAndXSSForId($threedsPayment->getPaymentId()),
                                                                                    'transaction_status' => $this->input_control->SlashAndXSSForId($payment_item->getTransactionStatus()),
                                                                                    'payment_transaction_id ' => $this->input_control->SlashAndXSSForId($payment_item->getPaymentTransactionId()),
                                                                                    'item_id' => $this->input_control->SlashAndXSSForId($payment_item->getItemId()),
                                                                                    'price' => $this->input_control->SlashAndXSSForId($payment_item->getPrice()),
                                                                                    'paid_price' => $this->input_control->SlashAndXSSForId($payment_item->getPaidPrice()),
                                                                                    'blockage_rate' => $this->input_control->SlashAndXSSForId($payment_item->getBlockageRate()),
                                                                                    'blockage_rate_amount_merchant' => $this->input_control->SlashAndXSSForId($payment_item->getBlockageRateAmountMerchant()),
                                                                                    'blockage_resolved_date' => $this->input_control->SlashAndXSSForId($payment_item->getBlockageResolvedDate()),
                                                                                    'iyzi_commission_fee' => $this->input_control->SlashAndXSSForId($payment_item->getIyziCommissionFee()),
                                                                                    'iyzi_commission_rate_amount' => $this->input_control->SlashAndXSSForId($payment_item->getIyziCommissionRateAmount()),
                                                                                    'merchant_commission_rate' => $this->input_control->SlashAndXSSForId($payment_item->getMerchantCommissionRate()),
                                                                                    'merchant_commission_rate_amount' => $this->input_control->SlashAndXSSForId($payment_item->getMerchantCommissionRateAmount()),
                                                                                    'merchant_payout_amount' => $this->input_control->SlashAndXSSForId($payment_item->getMerchantPayoutAmount()),
                                                                                    'converted_payout_paid_price' => $this->input_control->SlashAndXSSForId($payment_item->getConvertedPayout()->getPaidPrice()),
                                                                                    'converted_payout_iyzi_commission_fee' => $this->input_control->SlashAndXSSForId($payment_item->getConvertedPayout()->getIyziCommissionFee()),
                                                                                    'converted_payout_iyzi_commission_rate_amount' => $this->input_control->SlashAndXSSForId($payment_item->getConvertedPayout()->getIyziCommissionRateAmount()),
                                                                                    'converted_payout_blockage_rate_amount_merchant' => $this->input_control->SlashAndXSSForId($payment_item->getConvertedPayout()->getBlockageRateAmountMerchant()),
                                                                                    'converted_payout_merchant_payout_amount' => $this->input_control->SlashAndXSSForId($payment_item->getConvertedPayout()->getMerchantPayoutAmount()),
                                                                                    'converted_payout_iyzi_conversation_rate' => $this->input_control->SlashAndXSSForId($payment_item->getConvertedPayout()->getIyziConversionRate()),
                                                                                    'converted_payout_iyzi_conversation_rate_amount' => $this->input_control->SlashAndXSSForId($payment_item->getConvertedPayout()->getIyziConversionRateAmount()),
                                                                                    'converted_payout_currency' => $this->input_control->SlashAndXSSForId($payment_item->getConvertedPayout()->getCurrency()),
                                                                                ));
                                                                                if (!$result_create_item_transaction['result']) {
                                                                                    $this->action_control->SendMail(ADMIN_EMAIL, BRAND . 'Order Payment Error', 'Order payment transaction create database error. Payment ID : ' . $this->input_control->SlashAndXSSForId($threedsPayment->getPaymentId()) . ' Payment Transaction ID : ' . $this->input_control->SlashAndXSSForId($payment_item->getPaymentTransactionId()));
                                                                                    $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class OrderController function OrderPayment | Database Create Payment Transaction Error, Payment ID : ' . $this->input_control->SlashAndXSSForId($threedsPayment->getPaymentId()) . ' Payment Transaction ID : ' . $this->input_control->SlashAndXSSForId($payment_item->getPaymentTransactionId())));
                                                                                    $this->ActionModel->CreateLogEmailSent(array('user_id' => $authenticated_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'Order Payment Transaction DB Error'));
                                                                                }
                                                                                $ordered_item = $this->ItemModel->GetItemForOrderEmail(array($this->input_control->SlashAndXSSForId($threedsPayment->getBasketId()), $this->input_control->SlashAndXSSForId($payment_item->getItemId())));
                                                                                $ordered_item_price = $this->input_control->FormatPrice($this->input_control->SlashAndXSSForId($payment_item->getPaidPrice()));
                                                                                if ($ordered_item['result'] && $ordered_item_price['result']) {
                                                                                    $order_mail_items .= '<div class="confirm-wrapper"><div class="confirm-container"><span class="confirm">' . $ordered_item['data']['item_name'] . '</span><span class="confirm">' . $ordered_item['data']['item_size_name'] . '</span><span class="confirm">x' . $ordered_item['data']['item_quantity'] . ' Adet</span><span class="confirm">' . $ordered_item_price['data'] . ' ₺</span></div></div>';
                                                                                    $item_sold = $this->ItemModel->GetItemForOrder($ordered_item['data']['item_size_url'], $this->input_control->SlashAndXSSForId($payment_item->getItemId()));
                                                                                    if ($item_sold['result']) {
                                                                                        $result_update_item = $this->ItemModel->UpdateItem(array(
                                                                                            'item_total_quantity' => $item_sold['data']['item_total_quantity'] - $ordered_item['data']['item_quantity'],
                                                                                            $ordered_item['data']['item_size_url'] => $item_sold['data'][$ordered_item['data']['item_size_url']] - $ordered_item['data']['item_quantity'],
                                                                                            'id' => $this->input_control->SlashAndXSSForId($payment_item->getItemId())
                                                                                        ));
                                                                                        if (!$result_update_item['result']) {
                                                                                            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . 'Order Payment Error', 'Order update item quantity database error. Payment ID : ' . $this->input_control->SlashAndXSSForId($threedsPayment->getPaymentId()) . ' Payment Transaction ID : ' . $this->input_control->SlashAndXSSForId($payment_item->getPaymentTransactionId()));
                                                                                            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class OrderController function OrderPayment | Database Update Item Quantity Error, Payment ID : ' . $this->input_control->SlashAndXSSForId($threedsPayment->getPaymentId()) . ' Payment Transaction ID : ' . $this->input_control->SlashAndXSSForId($payment_item->getPaymentTransactionId())));
                                                                                            $this->ActionModel->CreateLogEmailSent(array('user_id' => $authenticated_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'Order Payment Update DB Error'));
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Yeni Sipariş', 'Bir yeni siparişiniz var.');
                                                                            if (!empty($order_mail_items)) {
                                                                                $this->action_control->SendMail($this->input_control->DecodePreventXSS($authenticated_user_from_database['data']['email']), BRAND . ' Sipariş Detayı', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Sipariş Detayı | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;}.confirm-wrapper {width: 95%;margin-left: auto;margin-right: auto;padding-top: 20px;background-color: #000000;}.confirm-container {background-color: #ffffff;}.confirm {display: inline-block;width: 24.5%;text-align: center;font-size: 12px;color: #000000;padding-top: 10px;padding-bottom: 10px;vertical-align: middle;}.text-2 {text-align: center;font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {text-align: center;font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Sipariş Özeti</p></div><div class="main">' . $order_mail_items . '<p class="text-2">Siparişiniz başarıyla tamamlanmıştır</p><p class="text-3">Siparişiniz ile ilgili detaylara ve siparişinizin son durumuna, profil bilgilerinizin altında, siparişlerim bölümünden ulaşabilirsiniz.</p></div><footer class="footer"><p class="footer-text">Bu mesaj bilgilendirme amacıyla gönderilmiştir.</p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>');
                                                                                $this->ActionModel->CreateLogEmailSent(array('user_id' => $authenticated_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'Kullanıcı Yeni Sipariş'));
                                                                            }
                                                                            $this->ActionModel->CreateLogEmailSent(array('user_id' => $authenticated_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'Yönetici Yeni Sipariş'));
                                                                            $this->web_data['order_result_title'] = 'Sipariş Sonucu Başarılı';
                                                                            $this->web_data['order_result_message'] = 'Siparişiniz ile ilgili detaylara ve siparişinizin son durumuna, profil bilgilerinizin altında, siparişlerim bölümünden ulaşabilirsiniz.';
                                                                            $this->web_data['order_result_redirect'] = 'profile-orders';
                                                                            parent::LogView('Home-OrderPayment');
                                                                            parent::GetView('Home/OrderPayment', $this->web_data);
                                                                        } else {
                                                                            $this->ItemModel->CreateOrderConversationError(array(
                                                                                'conversation_id_request' => $conversation_id['data'],
                                                                                'conversation_id_response' => $this->input_control->SlashAndXSSForId($threedsPayment->getConversationId()),
                                                                                'system_time' => $this->input_control->SlashAndXSSForId($threedsPayment->getSystemTime()),
                                                                                'user_id' => $authenticated_user_from_database['data']['id'],
                                                                                'user_ip' => $_SERVER['REMOTE_ADDR'],
                                                                                'function_type' => 'Payment'
                                                                            ));
                                                                            $this->input_control->Redirect(URL_ORDER_FAILURE);
                                                                        }
                                                                    } else {
                                                                        $this->ItemModel->CreateOrderStatusError(array(
                                                                            'conversation_id_request' => $conversation_id['data'],
                                                                            'conversation_id_response' => $this->input_control->SlashAndXSSForId($threedsPayment->getConversationId()),
                                                                            'status' => $this->input_control->SlashAndXSSForId($threedsPayment->getStatus()),
                                                                            'error_code' => $this->input_control->SlashAndXSSForId($threedsPayment->getErrorCode()),
                                                                            'error_message' => $this->input_control->SlashAndXSSForId($threedsPayment->getErrorMessage()),
                                                                            'error_group' => $this->input_control->SlashAndXSSForId($threedsPayment->getErrorGroup()),
                                                                            'system_time' => $this->input_control->SlashAndXSSForId($threedsPayment->getSystemTime()),
                                                                            'user_id' => $authenticated_user_from_database['data']['id'],
                                                                            'user_ip' => $_SERVER['REMOTE_ADDR'],
                                                                            'function_type' => 'Payment'
                                                                        ));
                                                                        $this->input_control->Redirect(URL_ORDER_FAILURE);
                                                                    }
                                                                } else {
                                                                    $this->input_control->Redirect(URL_ORDER_FAILURE);
                                                                }
                                                            } else {
                                                                $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Order Verify Error', 'Order verify missing payment id. Conversation Request ID : ' . $last_conversation_id['data']['conversation_id']);
                                                                $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class OrderController function OrderVerify | Missing Payment ID, Conversation Request ID : ' . $last_conversation_id['data']['conversation_id']));
                                                                $this->ActionModel->CreateLogEmailSent(array('user_id' => $authenticated_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'Order Verify Missing PaymentId'));
                                                                $this->input_control->Redirect(URL_ORDER_FAILURE);
                                                            }
                                                        } else {
                                                            $this->ItemModel->CreateOrderConversationError(array(
                                                                'conversation_id_request' => $last_conversation_id['data']['conversation_id'],
                                                                'conversation_id_response' => $this->input_control->SlashAndXSSForId($_POST['conversationId']),
                                                                'user_id' => $authenticated_user_from_database['data']['id'],
                                                                'user_ip' => $_SERVER['REMOTE_ADDR'],
                                                                'function_type' => 'Verify'
                                                            ));
                                                            $this->input_control->Redirect(URL_ORDER_FAILURE);
                                                        }
                                                    } else {
                                                        $this->ItemModel->CreateOrderStatusError(array(
                                                            'conversation_id_request' => $last_conversation_id['data']['conversation_id'],
                                                            'conversation_id_response' => $this->input_control->SlashAndXSSForId($_POST['conversationId']),
                                                            'status' => $this->input_control->SlashAndXSSForId($_POST['status']),
                                                            'mdStatus' => $this->input_control->SlashAndXSSForId($_POST['mdStatus']),
                                                            'user_id' => $authenticated_user_from_database['data']['id'],
                                                            'user_ip' => $_SERVER['REMOTE_ADDR'],
                                                            'function_type' => 'Verify'
                                                        ));
                                                        $this->input_control->Redirect(URL_ORDER_FAILURE);
                                                    }
                                                } else {
                                                    $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Order Verify Error', 'Order verify missing post datas. Conversation Request ID : ' . $last_conversation_id['data']['conversation_id']);
                                                    $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class OrderController function OrderVerify | Missing Post Datas, Conversation Request ID : ' . $last_conversation_id['data']['conversation_id']));
                                                    $this->ActionModel->CreateLogEmailSent(array('user_id' => $authenticated_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'Order Verify Missing Post Datas'));
                                                    $this->input_control->Redirect(URL_ORDER_FAILURE);
                                                }
                                            } else {
                                                $this->input_control->Redirect(URL_ORDER_FAILURE);
                                            }
                                        } else {
                                            $this->input_control->Redirect(URL_ORDER_FAILURE);
                                        }
                                    }
                                }
                            }
                            if ($cookie_authentication_error && $this->ActionModel->UpdateCookieAuthenticationCrossSite(array('is_cookie_authentication_cross_site_killed' => 1, 'date_cookie_authentication_cross_site_killed' => date('Y-m-d H:i:s'), 'cookie_authentication_cross_site_killed_function' => 'OrderController OrderPayment', 'id' => $cookie_authentication_from_database['data']['id']))['result']) {
                                $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_CROSS_SITE_NAME);
                                $this->input_control->Redirect(URL_ORDER_FAILURE);
                            }
                        }
                    }
                }
                if ($cookie_authentication_error) {
                    $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_CROSS_SITE_NAME);
                    $this->input_control->Redirect(URL_ORDER_FAILURE);
                }
            }
            $this->input_control->Redirect(URL_GO_HOME);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class OrderController function OrderPayment | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function OrderFailure()
    {
        try {
            parent::GetView('Error/OrderFailure');
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ErrorController function OrderFailure | ' . $th));
            $this->input_control->Redirect(URL_SHUTDOWN);
        }
    }
}
