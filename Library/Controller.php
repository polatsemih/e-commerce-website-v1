<?php
class Controller
{
    function __construct()
    {
        $error_session_set = true;
        $this->GetModel('LogModel');
        $result_session_name = session_name(SESSION_NAME);
        if ($result_session_name) {
            $result_session_set_cookie_params = session_set_cookie_params(array('lifetime' => SESSION_LIFETIME, 'path' => SESSION_PATH, 'domain' => SESSION_DOMAIN, 'secure' => SESSION_SECURE, 'httponly' => SESSION_HTTP_ONLY, 'samesite' => SESSION_SAMESITE));
            if ($result_session_set_cookie_params) {
                $result_session_start = session_start();
                if ($result_session_start) {
                    $error_session_set = false;
                    date_default_timezone_set('Europe/Istanbul');
                    $this->web_data = array();
                    $this->session_control = new Session();
                    $this->cookie_control = new Cookie();
                    $this->input_control = new Input();
                    $this->action_control = new Action();
                    $this->password_control = new Password();
                    $this->notification_control = new Notification();
                    $this->GetModel('ActionModel');
                    $this->GetModel('UserModel');
                    $this->GetModel('FilterModel');
                    $this->GetModel('ItemModel');
                    $search_popular_items_from_database = $this->ItemModel->GetSearchPopularItems();
                    if (!empty($search_popular_items_from_database)) {
                        $this->web_data['search_popular_items'] = $search_popular_items_from_database;
                    }
                    if (!empty($_COOKIE[COOKIE_CART_NAME])) {
                        $cart_cookie = $this->input_control->IsString($_COOKIE[COOKIE_CART_NAME]);
                        if (!is_null($cart_cookie)) {
                            try {
                                $decrypted_cart_items = $this->input_control->DecrypteData($cart_cookie, CART_PEPPER);
                                if (!is_null($decrypted_cart_items)) {
                                    $cart_items = json_decode($decrypted_cart_items, true);
                                    if (json_last_error() === JSON_ERROR_NONE && !empty($cart_items) && is_array($cart_items)) {
                                        $this->web_data['cookie_cart_view'] = array();
                                        $cart_total_price = 0;
                                        foreach ($cart_items as $key => $value) {
                                            $cookie_item_not_matched = true;
                                            if (!empty($cart_items[$key]['item_cart_id']) && !empty($cart_items[$key]['size_cart_id']) && !empty($cart_items[$key]['item_quantity'])) {
                                                $size_in_cart = $this->ItemModel->GetSizeBySizeCartIdForConstructor($cart_items[$key]['size_cart_id']);
                                                if (!empty($size_in_cart)) {
                                                    $item_in_cart = $this->ItemModel->GetItemByItemCartIdForConstructor($size_in_cart['size_url'], $cart_items[$key]['item_cart_id']);
                                                    if (!empty($item_in_cart)) {
                                                        $cookie_item_not_matched = false;
                                                        $cart_total_price += $item_in_cart['item_discount_price'] * $cart_items[$key]['item_quantity'];
                                                        $this->web_data['cookie_cart_view'][] = array('item' => $this->input_control->GetItemMainImageAndFormatedPrice($item_in_cart), 'size' => $size_in_cart, 'quantity' => $cart_items[$key]['item_quantity']);
                                                    }
                                                }
                                            }
                                            if ($cookie_item_not_matched) {
                                                unset($cart_items[$key]);
                                            }
                                        }
                                        if (!empty($this->web_data['cookie_cart_view'])) {
                                            $this->web_data['cart_total_price'] = $this->input_control->FormatPrice($cart_total_price);
                                        }
                                        if (!empty($cart_items)) {
                                            $this->web_data['cookie_cart'] = $cart_items;
                                        }
                                    }
                                }
                            } catch (\Throwable $th) {
                                $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function __construct COOKIE CART | ' . $th));
                            }
                        }
                    }
                }
            }
        }
        if ($error_session_set) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class Controller function __construct | SESSION INIT FAILED'));
            $this->GetView('Error/NotResponse');
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
    function LogView(string $view)
    {
        $this->LogModel->CreateLogView(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'view' => $view));
        if (empty($this->LogModel->GetLogViewCount(array($_SERVER['REMOTE_ADDR'], $view)))) {
            $this->LogModel->CreateLogViewCount(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'view' => $view));
        }
    }
    function SetCSRFToken(string $csrf_form)
    {
        try {
            $csrf_token = strtr(sodium_bin2base64(random_bytes(112), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'B', '_' => 'U'));
            if (!empty($csrf_token) && strlen($csrf_token) == 150 && $this->LogModel->CreateLogCSRF(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'csrf_token' => $csrf_token, 'csrf_form' => $csrf_form, 'date_csrf_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CSRF_TOKEN)))) == 'Created') {
                return $csrf_token;
            }
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class Controller function SetCSRFToken | ' . $th));
            return false;
        }
    }
    function CheckCSRFToken(string $csrf_token, string $csrf_form)
    {
        $log_csrf_from_database = $this->LogModel->GetLogCSRF(array($_SERVER['REMOTE_ADDR'], $csrf_form));
        if (!empty($log_csrf_from_database) && $csrf_token === $log_csrf_from_database['csrf_token'] && $log_csrf_from_database['date_csrf_expiry'] > date('Y-m-d H:i:s') && $log_csrf_from_database['is_csrf_used'] == 0 && $this->LogModel->UpdateLogCSRF(array('is_csrf_used' => 1, 'date_csrf_used' => date('Y-m-d H:i:s'), 'id' => $log_csrf_from_database['id'])) == 'Updated') {
            return true;
        }
        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
        return false;
    }
    function GetGenders(string $columns)
    {
        $genders_from_database = $this->FilterModel->GetGenders($columns);
        if (!empty($genders_from_database)) {
            return $genders_from_database;
        }
        $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function GetGenders'));
        $this->GetView('Error/NotResponse');
    }
}
