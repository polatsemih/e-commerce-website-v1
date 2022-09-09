<?php
class Controller
{
    function __construct()
    {
        try {
            if (session_name(SESSION_NAME) != false && session_set_cookie_params(array('lifetime' => SESSION_LIFETIME, 'path' => SESSION_PATH, 'domain' => SESSION_DOMAIN, 'secure' => SESSION_SECURE, 'httponly' => SESSION_HTTP_ONLY, 'samesite' => SESSION_SAMESITE)) && session_start() && date_default_timezone_set('Europe/Istanbul')) {
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
            $this->input_control->Redirect(URL_SHUTDOWN);
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
}
