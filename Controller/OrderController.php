<?php
class OrderController extends ControllerOrder
{
    function __construct()
    {
        parent::__construct();
    }
    function OrderComplete()
    {
        try {
            if (!empty($_COOKIE[COOKIE_AUTHENTICATION_CROSS_SITE_NAME])) {
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
                                    $authenticated_user_from_database = $this->UserModel->GetUserByUserId('id,is_user_blocked', $cookie_authentication_from_database['data']['user_id']);
                                    if ($authenticated_user_from_database['result'] && $this->ActionModel->UpdateCookieAuthenticationCrossSite(array('is_cookie_authentication_cross_site_success' => 1, 'id' => $cookie_authentication_from_database['data']['id']))['result']) {
                                        $cookie_authentication_error = false;
                                        if ($authenticated_user_from_database['data']['is_user_blocked'] == 0) {
                                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                $conversation_id = $this->input_control->GenerateToken();
                                                if ($conversation_id['result']) {
                                                    if (!empty($_POST['mdStatus']) && !empty($_POST['status']) && !empty($_POST['conversationId'])) {
                                                        if ($_POST['mdStatus'] == 1 && $_POST['status'] == 'success') {
                                                            $last_conversation_id = $this->ItemModel->GetOrder1LastConversationId(array($authenticated_user_from_database['data']['id'], $_SERVER['REMOTE_ADDR']));
                                                            if ($last_conversation_id['result']) {
                                                                if ($last_conversation_id['data']['conversation_id'] == $_POST['conversationId']) {
                                                                    if (!empty($_POST['paymentId'])) {
                                                                        if (!empty($_POST['conversationData'])) {
                                                                            $result_order_2_created = $this->ItemModel->CreateOrder2(array(
                                                                                'conversation_id' => $conversation_id['data'],
                                                                                'payment_id' => $this->input_control->SlashAndXSS($_POST['paymentId']),
                                                                                'conversation_data' => $this->input_control->SlashAndXSS($_POST['conversationData']),
                                                                            ));
                                                                        } else {
                                                                            $result_order_2_created = $this->ItemModel->CreateOrder2(array(
                                                                                'conversation_id' => $conversation_id['data'],
                                                                                'payment_id' => $this->input_control->SlashAndXSS($_POST['paymentId'])
                                                                            ));
                                                                        }
                                                                        if ($result_order_2_created['result']) {
                                                                            require_once(IYZIPAY_FOLDER_NAME . '/samples/config.php');

                                                                            $request = new \Iyzipay\Request\CreateThreedsPaymentRequest();
                                                                            $request->setLocale(\Iyzipay\Model\Locale::TR);
                                                                            $request->setConversationId($conversation_id['data']);
                                                                            $request->setPaymentId($_POST['paymentId']);
                                                                            if (!empty($_POST['conversationData'])) {
                                                                                $request->setConversationData($_POST['conversationData']);
                                                                            }

                                                                            $threedsPayment = \Iyzipay\Model\ThreedsPayment::create($request, Config::options());
                                                                            print_r($threedsPayment);
                                                                            // mdtstatus 1 den farklı çıkarsa order status 0 olsun abi başarısız demek




                                                                            $this->web_data['order_result_notification'] = 'Sipariş Sonuçlandı';
                                                                            $this->web_data['order_result_redirect'] = 'profile-orders';
                                                                            parent::LogView('Home-OrderComplete');
                                                                            parent::GetView('Home/OrderComplete', $this->web_data);
                                                                        } else {
                                                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                                            $this->input_control->Redirect(URL_CART);
                                                                        }
                                                                    } else {
                                                                        $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function OrderComplete | Missing Payment ID, Conversation ID : ' . $this->input_control->SlashAndXSS($_POST['mdStatus'])));
                                                                        $this->action_control->SendMail(ADMIN_EMAIL, BRAND . 'Order Error', 'Order missing payment id.');
                                                                        $this->ActionModel->CreateLogEmailSent(array('user_id' => $this->web_data['authenticated_user'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'OrderMissingPaymentId'));
                                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                                        $this->input_control->Redirect(URL_CART);
                                                                    }
                                                                } else {
                                                                    $this->ItemModel->CreateOrder2ConversationError(array(
                                                                        'conversation_id_request' => $last_conversation_id['data']['conversation_id'],
                                                                        'conversation_id_response' => $this->input_control->SlashAndXSS($_POST['conversationId'])
                                                                    ));
                                                                    $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function OrderComplete | Conversation Error, Conversation ID : ' . $this->input_control->SlashAndXSS($_POST['mdStatus'])));
                                                                    $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Order Error', 'Order conversation error occured.');
                                                                    $this->ActionModel->CreateLogEmailSent(array('user_id' => $this->web_data['authenticated_user'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'OrderConversationError'));
                                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                                    $this->input_control->Redirect(URL_CART);
                                                                }
                                                            } else {
                                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                                $this->input_control->Redirect(URL_CART);
                                                            }
                                                        } else {
                                                            $this->ItemModel->CreateOrder2StatusError(array(
                                                                'conversation_id' => $this->input_control->SlashAndXSS($_POST['conversationId']),
                                                                'status' => $this->input_control->SlashAndXSS($_POST['status']),
                                                                'mdStatus' => $this->input_control->SlashAndXSS($_POST['mdStatus'])
                                                            ));
                                                            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function OrderComplete | Status Failed, Conversation ID : ' . $this->input_control->SlashAndXSS($_POST['mdStatus'])));
                                                            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Order Error', 'Order status failed.');
                                                            $this->ActionModel->CreateLogEmailSent(array('user_id' => $this->web_data['authenticated_user'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'OrderStatusError'));
                                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                            $this->input_control->Redirect(URL_CART);
                                                        }
                                                    } else {
                                                        $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function OrderComplete | Missing Post Datas'));
                                                        $this->action_control->SendMail(ADMIN_EMAIL, BRAND . 'Order Error', 'Order missing post datas.');
                                                        $this->ActionModel->CreateLogEmailSent(array('user_id' => $this->web_data['authenticated_user'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'OrderMissingPostDatas'));
                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                        $this->input_control->Redirect(URL_CART);
                                                    }
                                                } else {
                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                    $this->input_control->Redirect(URL_CART);
                                                }
                                            }
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ORDER_USER_BLOCKED);
                                            $this->input_control->Redirect(URL_CART);
                                        }
                                    }
                                }
                            }
                            if ($cookie_authentication_error && $this->ActionModel->UpdateCookieAuthenticationCrossSite(array('is_cookie_authentication_cross_site_killed' => 1, 'date_cookie_authentication_cross_site_killed' => date('Y-m-d H:i:s'), 'cookie_authentication_cross_site_killed_function' => 'HomeController OrderComplete', 'id' => $cookie_authentication_from_database['data']['id']))['result']) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                            }
                        }
                    }
                }
                if ($cookie_authentication_error) {
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                }
            }
            $this->input_control->Redirect(URL_GO_HOME);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class OrderController function OrderComplete | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
}
