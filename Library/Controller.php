<?php
class Controller
{
    function __construct()
    {
        try {
            if (!empty(session_name(SESSION_NAME)) && session_set_cookie_params(array('lifetime' => SESSION_LIFETIME, 'path' => SESSION_PATH, 'domain' => SESSION_DOMAIN, 'secure' => SESSION_SECURE, 'httponly' => SESSION_HTTP_ONLY, 'samesite' => SESSION_SAMESITE)) && session_start() && date_default_timezone_set('Europe/Istanbul')) {
                $this->web_data = array();
                $this->action_control = new Action();
                $this->cookie_control = new Cookie();
                $this->input_control = new Input();
                $this->notification_control = new Notification();
                $this->password_control = new Password();
                $this->session_control = new Session();
                $this->GetModel('ActionModel');
                $this->GetModel('FilterModel');
                $this->GetModel('ItemModel');
                $this->GetModel('LogModel');
                $this->GetModel('UserModel');
                if (!empty($_SESSION[SESSION_OBSOLETE_NAME]) && $_SESSION[SESSION_OBSOLETE_NAME] < time()) {
                    if ($this->session_control->KillAllSessions() && session_regenerate_id()) {
                        $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                    } else {
                        $this->input_control->Redirect(URL_EXCEPTION);
                    }
                }
                if (!empty($_SESSION[SESSION_REFRESH_NAME])) {
                    if ($_SESSION[SESSION_REFRESH_NAME] < time()) {
                        $_SESSION[SESSION_OBSOLETE_NAME] = time() + (60 * 5);
                        if (session_regenerate_id()) {
                            $this->session_control->KillSession(SESSION_OBSOLETE_NAME);
                            $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                        } else {
                            $this->input_control->Redirect(URL_EXCEPTION);
                        }
                    }
                } else {
                    $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                }
                if (!empty($_SESSION[SESSION_AUTHENTICATION_NAME]) && !empty($_COOKIE[COOKIE_AUTHENTICATION_NAME])) {
                    $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
                    $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
                    $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
                    $this->input_control->Redirect(URL_LOGIN);
                } elseif (!empty($_SESSION[SESSION_AUTHENTICATION_NAME])) {
                    $session_authentication_error = true;
                    $session_authentication_from_database = $this->ActionModel->GetSessionAuthentication(array($_SERVER['REMOTE_ADDR'], $_SESSION[SESSION_AUTHENTICATION_NAME]));
                    if ($session_authentication_from_database['result'] && $session_authentication_from_database['data']['date_session_authentication_expiry'] > date('Y-m-d H:i:s') && $session_authentication_from_database['data']['is_session_authentication_logout'] == 0) {
                        $authenticated_user_from_database = $this->UserModel->GetUserByUserId('id', $session_authentication_from_database['data']['user_id']);
                        if ($authenticated_user_from_database['result']) {
                            $session_authentication_error = false;
                            $this->web_data['authenticated_user'] = $authenticated_user_from_database['data']['id'];
                            $this->web_data['session_authentication_id'] = $session_authentication_from_database['data']['id'];
                        }
                        if ($session_authentication_error && $this->ActionModel->UpdateSessionAuthentication(array('is_session_authentication_killed' => 1, 'date_session_authentication_killed' => date('Y-m-d H:i:s'), 'session_authentication_killed_function' => 'Controller __construct', 'id' => $session_authentication_from_database['data']['id']))['result']) {
                            $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
                            $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
                            $this->input_control->Redirect(URL_LOGIN);
                        }
                    }
                    if ($session_authentication_error) {
                        $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
                        $this->input_control->Redirect(URL_LOGIN);
                    }
                } elseif (!empty($_COOKIE[COOKIE_AUTHENTICATION_NAME])) {
                    $cookie_authentication_error = true;
                    $checked_cookie_authentication = $this->input_control->CheckGETInputWithLength($_COOKIE[COOKIE_AUTHENTICATION_NAME], 400);
                    if (!empty($checked_cookie_authentication)) {
                        $extracted_cookie_authentication_token1 = substr($checked_cookie_authentication, 200, 200);
                        $extracted_cookie_authentication_token2 = substr($checked_cookie_authentication, 0, 200);
                        if (!empty($extracted_cookie_authentication_token1) && !empty($extracted_cookie_authentication_token2)) {
                            $cookie_authentication_from_database = $this->ActionModel->GetCookieAuthentication(array($_SERVER['REMOTE_ADDR'], $extracted_cookie_authentication_token2));
                            if ($cookie_authentication_from_database['result'] && $cookie_authentication_from_database['data']['date_cookie_authentication_expiry'] > date('Y-m-d H:i:s') && $cookie_authentication_from_database['data']['is_cookie_authentication_logout'] == 0) {
                                $cookie_authentication_token1 = hash_hmac('SHA512', $extracted_cookie_authentication_token1, $cookie_authentication_from_database['data']['cookie_authentication_salt'], false);
                                if (hash_equals($cookie_authentication_from_database['data']['cookie_authentication_token1'], $cookie_authentication_token1)) {
                                    $authenticated_user_from_database = $this->UserModel->GetUserByUserId('id', $cookie_authentication_from_database['data']['user_id']);
                                    if ($authenticated_user_from_database['result']) {
                                        $cookie_authentication_error = false;
                                        $this->web_data['authenticated_user'] = $authenticated_user_from_database['data']['id'];
                                        $this->web_data['cookie_authentication_id'] = $cookie_authentication_from_database['data']['id'];
                                    }
                                }
                                if ($cookie_authentication_error && $this->ActionModel->UpdateCookieAuthentication(array('is_cookie_authentication_killed' => 1, 'date_cookie_authentication_killed' => date('Y-m-d H:i:s'), 'cookie_authentication_killed_function' => 'Controller __construct', 'id' => $cookie_authentication_from_database['data']['id']))['result']) {
                                    $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
                                    $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
                                    $this->input_control->Redirect(URL_LOGIN);
                                }
                            }
                        }
                    }
                    if ($cookie_authentication_error) {
                        $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
                        $this->input_control->Redirect(URL_LOGIN);
                    }
                }
                $popular_search_items = $this->ItemModel->GetPopularSearchItems();
                if ($popular_search_items['result']) {
                    $this->web_data['popular_search_items'] = $popular_search_items['data'];
                }
                $cookie_cart = $this->cookie_control->GetCookie(COOKIE_CART_NAME);
                if ($cookie_cart['result']) {
                    $decrypted_cookie_cart = $this->input_control->DecrypteData($cookie_cart['data'], COOKIE_CART_PEPPER);
                    if ($decrypted_cookie_cart['result']) {
                        $cart_items = json_decode($decrypted_cookie_cart['data'], true);
                        if (json_last_error() === JSON_ERROR_NONE && !empty($cart_items) && is_array($cart_items) && count($cart_items) > 0) {
                            $cart_items_start_length = count($cart_items);
                            $this->web_data['cart_data'] = array();
                            $cart_total_price = 0;
                            foreach ($cart_items as $key => $value) {
                                $cookie_item_not_matched = true;
                                if (!empty($cart_items[$key]['item_cart_id']) && !empty($cart_items[$key]['size_cart_id']) && !empty($cart_items[$key]['item_quantity'])) {
                                    $size_in_cart = $this->FilterModel->GetSizeBySizeCartId('size_cart_id,size_name,size_url', $cart_items[$key]['size_cart_id']);
                                    if ($size_in_cart['result']) {
                                        $item_in_cart = $this->ItemModel->GetItemByItemCartId('item_cart_id,item_name,item_url,item_price,item_discount_price,item_images_path,item_images,' . $size_in_cart['data']['size_url'], $cart_items[$key]['item_cart_id']);
                                        if ($item_in_cart['result']) {
                                            $cart_total_price += $item_in_cart['data']['item_discount_price'] * $cart_items[$key]['item_quantity'];
                                            $formatted_item = $this->input_control->GetItemMainImageAndFormatedPrice($item_in_cart['data']);
                                            if ($formatted_item['result']) {
                                                $cookie_item_not_matched = false;
                                                $this->web_data['cart_data'][] = array('item' => $formatted_item['data'], 'size' => $size_in_cart['data'], 'quantity' => $cart_items[$key]['item_quantity']);
                                            }
                                        }
                                    }
                                }
                                if ($cookie_item_not_matched) {
                                    unset($cart_items[$key]);
                                }
                            }
                            if (!empty($this->web_data['cart_data'])) {
                                $formatted_cart_total_price = $this->input_control->FormatPrice($cart_total_price);
                                if ($formatted_cart_total_price['result']) {
                                    $this->web_data['cart_data_total_price'] = $formatted_cart_total_price['data'];
                                }
                            }
                            if (!empty($cart_items)) {
                                $this->web_data['cookie_cart'] = $cart_items;
                                if (count($cart_items) != $cart_items_start_length) {
                                    $update_cookie_cart_error = true;
                                    $encoded_cart_items = json_encode($cart_items);
                                    if (!empty($encoded_cart_items)) {
                                        $setted_cart_cookie = $this->input_control->EncrypteData($encoded_cart_items, COOKIE_CART_PEPPER);
                                        if (strlen($setted_cart_cookie) <= 4000) {
                                            if ($this->cookie_control->SetCookie(COOKIE_CART_NAME, $setted_cart_cookie, time() + (COOKIE_CART_EXPIRY), COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTP_ONLY, COOKIE_SAMESITE)) {
                                                $update_cookie_cart_error = false;
                                            }
                                        }
                                    }
                                    if ($update_cookie_cart_error) {
                                        $this->cookie_control->EmptyCookie(COOKIE_CART_NAME);
                                    }
                                }
                            } else {
                                $this->cookie_control->EmptyCookie(COOKIE_CART_NAME);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            require_once 'View/Error/Shutdown.php';
            exit(0);
        }
    }
    function GetModel(string $model)
    {
        require_once 'Model/' . $model . '.php';
        $this->$model = new $model();
    }
    function GetView(string $view, array $web_data = null)
    {
        require_once 'View/' . $view . '.php';
        exit(0);
    }
    function LogView(string $viewed_page)
    {
        $log_view_error = true;
        if ($this->LogModel->CreateLogViewAll(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'viewed_page' => $viewed_page))['result']) {
            $logViewOnce = $this->LogModel->GetLogViewOnce(array($_SERVER['REMOTE_ADDR'], $viewed_page));
            if ($logViewOnce['result']) {
                $log_view_error = false;
            } elseif (!empty($logViewOnce['empty']) && $this->LogModel->CreateLogViewOnce(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'viewed_page' => $viewed_page))['result']) {
                $log_view_error = false;
            }
        }
        if ($log_view_error) {
            $this->input_control->Redirect(URL_SHUTDOWN);
        }
    }
    function SetCSRFTokenjQ(string $csrf_form)
    {
        $csrf_token = $this->input_control->GenerateToken();
        if ($csrf_token['result'] && $this->LogModel->CreateLogCSRF(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'csrf_token' => $csrf_token['data'], 'csrf_form' => $csrf_form, 'date_csrf_expiry' => date('Y-m-d H:i:s', time() + (CSRF_TOKEN_EXPIRY))))['result']) {
            return array('result' => true, 'csrf_token' => $csrf_token['data']);
        }
        return array('result' => false);
    }
    function SetCSRFToken(string $csrf_form)
    {
        $csrf_token = $this->input_control->GenerateToken();
        if ($csrf_token['result'] && $this->LogModel->CreateLogCSRF(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'csrf_token' => $csrf_token['data'], 'csrf_form' => $csrf_form, 'date_csrf_expiry' => date('Y-m-d H:i:s', time() + (CSRF_TOKEN_EXPIRY))))['result']) {
            return $csrf_token['data'];
        }
        $this->input_control->Redirect(URL_SHUTDOWN);
    }
    function CheckCSRFToken(string $csrf_token, string $csrf_form)
    {
        $log_csrf = $this->LogModel->GetLogCSRF(array($_SERVER['REMOTE_ADDR'], $csrf_form));
        if ($log_csrf['result'] && $csrf_token == $log_csrf['data']['csrf_token'] && $log_csrf['data']['date_csrf_expiry'] > date('Y-m-d H:i:s') && $log_csrf['data']['is_csrf_used'] == 0 && $this->LogModel->UpdateLogCSRF(array('is_csrf_used' => 1, 'date_csrf_used' => date('Y-m-d H:i:s'), 'id' => $log_csrf['data']['id']))['result']) {
            return true;
        }
        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
        return false;
    }
    function GetGenders(string $columns)
    {
        $genders = $this->FilterModel->GetGenders($columns);
        if ($genders['result']) {
            return $genders['data'];
        }
        $this->input_control->Redirect(URL_SHUTDOWN);
    }
    function KillAuthentication(string $killed_function)
    {
        if (!empty($this->web_data['session_authentication_id'])) {
            $this->ActionModel->UpdateSessionAuthentication(array('is_session_authentication_killed' => 1, 'date_session_authentication_killed' => date('Y-m-d H:i:s'), 'session_authentication_killed_function' => $killed_function, 'id' => $this->web_data['session_authentication_id']));
            $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
            $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
        }
        if (!empty($this->web_data['cookie_authentication_id'])) {
            $this->ActionModel->UpdateCookieAuthentication(array('is_cookie_authentication_killed' => 1, 'date_cookie_authentication_killed' => date('Y-m-d H:i:s'), 'cookie_authentication_killed_function' => $killed_function, 'id' => $this->web_data['cookie_authentication_id']));
            $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
            $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
        }
    }
}
