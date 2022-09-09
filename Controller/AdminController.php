<?php
class AdminController extends Controller
{
    function __construct()
    {
        parent::__construct();
        if (!empty($_SESSION[SESSION_AUTHENTICATION_NAME]) && !empty($_COOKIE[COOKIE_AUTHENTICATION_NAME])) {
            if ($this->session_control->KillSession() && $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME)) {
                $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_AUTHENTICATION_KILLED);
                $this->input_control->Redirect(URL_LOGIN);
            } else {
                // parent::GetView('Error/NotResponse');
            }
        }
        $session_authentication_error = true;
        if (!empty($_SESSION[SESSION_AUTHENTICATION_NAME]) && $_SERVER['REMOTE_ADDR'] === ADMIN_IP_ADDRESS) {
            $checked_session_authentication_token = $this->input_control->CheckInputWithLength($_SESSION[SESSION_AUTHENTICATION_NAME], 255);
            if (!is_null($checked_session_authentication_token)) {
                $session_authentication_from_database = $this->ActionModel->GetSessionAuthentication(array($_SERVER['REMOTE_ADDR'], $checked_session_authentication_token));
                if (!empty($session_authentication_from_database) && $session_authentication_from_database['date_session_authentication_expiry'] > date('Y-m-d H:i:s') && $session_authentication_from_database['session_authentication_is_logout'] == 0) {
                    $authenticated_user_from_database = $this->UserModel->GetUser('id,user_role', $session_authentication_from_database['user_id']);
                    if (!empty($authenticated_user_from_database) && $authenticated_user_from_database['user_role'] === ADMIN_ROLE_ID) {
                        if (session_regenerate_id(true)) {
                            $session_authentication_error = false;
                            if (empty($_SESSION[COOKIE_ADMIN_MENU_NAME])) {
                                $_SESSION[COOKIE_ADMIN_MENU_NAME] = 'true';
                            }
                            $this->web_data['authenticated_user_id'] = $authenticated_user_from_database['id'];
                        }
                    }
                }
            }
        }
        if ($session_authentication_error) {
            if ($this->session_control->KillSession()) {
                $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_AUTHENTICATION_KILLED);
                $this->input_control->Redirect(URL_LOGIN);
            } else {
                // parent::GetView('Error/NotResponse');
            }
        }
    }


    
    function SetCSRFToken(string $csrf_page)
    {
        $csrf_token = $this->action_control->GenerateCSRFToken();
        if (!empty($csrf_token) && strlen($csrf_token) == 150) {
            $csrf_exist_in_db = $this->ActionModel->GetExistsLogCSRF($_SERVER['REMOTE_ADDR']);
            if (!empty($csrf_exist_in_db)) {
                $result_log_csrf_update = $this->ActionModel->UpdateLogCSRF(array(
                    'csrf_token' => $csrf_token,
                    'csrf_page' => $csrf_page,
                    'date_csrf_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CSRF_TOKEN)),
                    'date_last_csrf_created' => date('Y-m-d H:i:s'),
                    'is_csrf_used' => 0,
                    'id' => $csrf_exist_in_db['id']
                ));
                if ($result_log_csrf_update == 'Updated') {
                    return $csrf_token;
                }
            } else {
                $result_log_csrf_create = $this->ActionModel->CreateLogCSRF(array(
                    'user_ip' => $_SERVER['REMOTE_ADDR'],
                    'csrf_token' => $csrf_token,
                    'csrf_page' => $csrf_page,
                    'date_csrf_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CSRF_TOKEN)),
                    'date_last_csrf_created' => date('Y-m-d H:i:s'),
                    'is_csrf_used' => 0
                ));
                if ($result_log_csrf_create['result'] == 'Created') {
                    return $csrf_token;
                }
            }
        }
        return null;
    }
    function CheckCSRFToken(string $checked_csrf_token, string $get_log_csrf_data_type)
    {
        $log_csrf_from_db = $this->ActionModel->GetLogCSRF('id,csrf_token,date_csrf_expiry,is_csrf_used', array($get_log_csrf_data_type, $_SERVER['REMOTE_ADDR']));
        if (!empty($log_csrf_from_db) && $checked_csrf_token === $log_csrf_from_db['csrf_token'] && $log_csrf_from_db['date_csrf_expiry'] > date('Y-m-d H:i:s') && $log_csrf_from_db['is_csrf_used'] == 0) {
            $this->ActionModel->UpdateLogCSRF(array(
                'is_csrf_used' => 1,
                'date_csrf_used' => date('Y-m-d H:i:s'),
                'id' => $log_csrf_from_db['id']
            ));
            return true;
        }
        return $this->notification_control->Danger(ERROR_MESSAGE_EMPTY_CSRF);
    }
    




























    function MenuPreference()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION[COOKIE_ADMIN_MENU_NAME])) {
                $menu_preference = $_SESSION[COOKIE_ADMIN_MENU_NAME];
                if ($menu_preference === 'true') {
                    $_SESSION[COOKIE_ADMIN_MENU_NAME] = 'false';
                } elseif ($menu_preference === 'false') {
                    $_SESSION[COOKIE_ADMIN_MENU_NAME] = 'true';
                } else {
                    $this->session_control->KillSession();
                    exit(0);
                }
            } else {
                $this->session_control->KillSession();
                exit(0);
            }
        } else {
            $this->session_control->KillSession();
            exit(0);
        }
    }

    function Index()
    {
        parent::GetView('Admin/Index', $this->web_data);
    }

    function Items()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->input_control->CheckUrl(array('filter', 'search', 'max', 'min', 'sort', 'limit', 'page'));
            $prices = $this->ItemModel->GetItemsDiscountPrices();
            if (!empty($prices)) {
                $prices_for_filter = array();
                foreach ($prices as $price) {
                    $prices_for_filter[] = (int)$price['item_discount_price'];
                }
                $this->web_data['min_price'] = min($prices_for_filter);
                $this->web_data['max_price'] = max($prices_for_filter);
            }
            $get_with_param = false;
            $hidden_params = array();
            $cond_params = array();
            $pagination = false;
            if (isset($_GET['filter'])) {
                $checked_filter = $this->input_control->CheckGETInput($_GET['filter']);
                if (!is_null($checked_filter)) {
                    switch ($checked_filter) {
                        case 'butun-urunler':
                            $filter_condition = 'all';
                            break;
                        case 'satistaki-urunler':
                            $filter_condition = 'item_insale=1';
                            break;
                        case 'satista-olmayan-urunler':
                            $filter_condition = 'item_insale=0';
                            break;
                        default:
                            $filter_condition = null;
                            break;
                    }
                    if (!empty($filter_condition)) {
                        if ($filter_condition != 'all') {
                            $get_with_param = true;
                            $this->web_data['filter'] = $checked_filter;
                            $cond_params['filter'] = $filter_condition;
                            $hidden_params[] = array('name' => 'filter', 'value' => $checked_filter);
                        }
                    } else {
                        header('Location: ' . URL . '/AdminController/Items');
                        exit(0);
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Items');
                    exit(0);
                }
            }
            if (isset($_GET['search'])) {
                $checked_search = $this->input_control->CheckGETInput($_GET['search']);
                if (!is_null($checked_search) && strlen($checked_search) <= 200) {
                    $get_with_param = true;
                    $this->web_data['search'] = $checked_search;
                    $cond_params['search'] = $checked_search;
                    $hidden_params[] = array('name' => 'search', 'value' => $checked_search);
                } else {
                    header('Location: ' . URL . '/AdminController/Items');
                    exit(0);
                }
            }
            if (isset($_GET['max']) && isset($_GET['min'])) {
                if (is_numeric($_GET['min']) && is_numeric($_GET['max'])) {
                    $min_price = (int)$this->input_control->CheckGETInput($this->input_control->IsIntegerAndPositive($_GET['min']));
                    $max_price = (int)$this->input_control->CheckGETInput($this->input_control->IsIntegerAndPositive($_GET['max']));
                    if (!is_null($min_price) && !is_null($max_price) && ($min_price < $max_price)) {
                        $get_with_param = true;
                        $this->web_data['selected_min_price'] = $min_price;
                        $this->web_data['selected_max_price'] = $max_price;
                        $cond_params['price'] = array('min' => $min_price, 'max' => $max_price);
                        $hidden_params[] = array('name' => 'min', 'value' => $min_price);
                        $hidden_params[] = array('name' => 'max', 'value' => $max_price);
                    } else {
                        header('Location: ' . URL . '/AdminController/Items');
                        exit(0);
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Items');
                    exit(0);
                }
            }
            if (isset($_GET['sort'])) {
                $checked_sort = $this->input_control->CheckGETInput($_GET['sort']);
                if (!is_null($checked_sort)) {
                    switch ($checked_sort) {
                        case 'isim-azalan':
                            $sort_for_sql = 'item_name DESC';
                            $sort_deg = 'name_0';
                            break;
                        case 'isim-artan':
                            $sort_for_sql = 'item_name ASC';
                            $sort_deg = 'name_1';
                            break;
                        case 'fiyat-azalan':
                            $sort_for_sql = 'item_price DESC';
                            $sort_deg = 'price_0';
                            break;
                        case 'fiyat-artan':
                            $sort_for_sql = 'item_price ASC';
                            $sort_deg = 'price_1';
                            break;
                        case 'indirimli-fiyat-azalan':
                            $sort_for_sql = 'item_discount_price DESC';
                            $sort_deg = 'discount_price_0';
                            break;
                        case 'indirimli-fiyat-artan':
                            $sort_for_sql = 'item_discount_price ASC';
                            $sort_deg = 'discount_price_1';
                            break;
                        case 'adet-azalan':
                            $sort_for_sql = 'item_total_number DESC';
                            $sort_deg = 'number_0';
                            break;
                        case 'adet-artan':
                            $sort_for_sql = 'item_total_number ASC';
                            $sort_deg = 'number_1';
                            break;
                        case 'tarih-azalan':
                            $sort_for_sql = 'item_date_added DESC';
                            $sort_deg = 'date_0';
                            break;
                        case 'tarih-artan':
                            $sort_for_sql = 'item_date_added ASC';
                            $sort_deg = 'date_1';
                            break;
                        default:
                            $sort_for_sql = null;
                            break;
                    }
                    if (!empty($sort_for_sql)) {
                        $get_with_param = true;
                        $this->web_data['sort'] = $checked_sort;
                        $this->web_data['sort_deg'] = $sort_deg;
                        $cond_params['sort'] = $sort_for_sql;
                        $hidden_params[] = array('name' => 'sort', 'value' => $checked_sort);
                    } else {
                        header('Location: ' . URL . '/AdminController/Items');
                        exit(0);
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Items');
                    exit(0);
                }
            }
            if (isset($_GET['limit'])) {
                $checked_limit = $this->input_control->CheckGETInput($_GET['limit']);
                if (!is_null($checked_limit)) {
                    switch ($checked_limit) {
                        case '5':
                            $limit_for_sql = '5';
                            break;
                        case '10':
                            $limit_for_sql = '10';
                            break;
                        case '25':
                            $limit_for_sql = '25';
                            break;
                        case '50':
                            $limit_for_sql = '50';
                            break;
                        default:
                            $limit_for_sql = null;
                            break;
                    }
                    if (!is_null($limit_for_sql)) {
                        $get_with_param = true;
                        $this->web_data['limit'] = $limit_for_sql;
                        $cond_params['limit'] = $limit_for_sql;
                        $hidden_params[] = array('name' => 'limit', 'value' => $limit_for_sql);
                    } else {
                        header('Location: ' . URL . '/AdminController/Items');
                        exit(0);
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Items');
                    exit(0);
                }
            }
            if (isset($_GET['page'])) {
                if (is_numeric($_GET['page'])) {
                    $checked_page = $this->input_control->CheckGETInput($this->input_control->IsIntegerAndPositive($_GET['page']));
                    if (!is_null($checked_page)) {
                        $get_with_param = true;
                        $pagination = true;
                        $this->web_data['page'] = $checked_page;
                        $hidden_params[] = array('name' => 'page', 'value' => $checked_page);
                    } else {
                        header('Location: ' . URL . '/AdminController/Items');
                        exit(0);
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Items');
                    exit(0);
                }
            }
            if (empty($this->web_data['page'])) {
                $this->web_data['page'] = 1;
            }
            if ($get_with_param) {
                $where = '';
                $order = '';
                $limit = '';
                $data_for_sql = array();
                foreach ($cond_params as $key => $cond_param) {
                    switch ($key) {
                        case 'filter':
                            if (empty($where)) {
                                $where .= 'WHERE ' . $cond_param;
                            } else {
                                $where .= ' AND ' . $cond_param;
                            }
                            break;
                        case 'search':
                            if (empty($where)) {
                                $where .= 'WHERE (id LIKE ? OR item_name LIKE ? OR item_url LIKE ? OR item_price LIKE ? OR item_discount_price LIKE ? OR item_total_number LIKE ?)';
                            } else {
                                $where .= ' AND (id LIKE ? OR item_name LIKE ? OR item_url LIKE ? OR item_price LIKE ? OR item_discount_price LIKE ? OR item_total_number LIKE ?)';
                            }
                            for ($i = 0; $i < 6; $i++) {
                                $data_for_sql[] = '%' . $cond_param . '%';
                            }
                            break;
                        case 'price':
                            if (empty($where)) {
                                $where .= 'WHERE item_discount_price BETWEEN ? AND ?';
                            } else {
                                $where .= ' AND item_discount_price BETWEEN ? AND ?';
                            }
                            $data_for_sql[] = $cond_param['min'];
                            $data_for_sql[] = $cond_param['max'];
                            break;
                        case 'sort':
                            $order = 'ORDER BY ' . $cond_param;
                            break;
                        case 'limit':
                            $limit = 'LIMIT ' . $cond_param;
                            $limit_for_page = $cond_param;
                            break;
                    }
                }
                if (empty($order)) {
                    $order = 'ORDER BY item_date_added DESC';
                }
                if (empty($limit)) {
                    $limit = 'LIMIT 5';
                    $limit_for_page = 5;
                    $this->web_data['limit'] = 5;
                }
                if (!empty($where) && empty($data_for_sql)) {
                    $count = $this->ItemModel->CountItemsByCondition($where);
                    $this->web_data['total_page'] = ceil($count['COUNT(id)'] / $limit_for_page);
                    $items = $this->ItemModel->GetItemsByCondition($where . ' ' . $order . ' ' . $limit);
                } elseif (!empty($where) && !empty($data_for_sql)) {
                    $count = $this->ItemModel->CountItemsByConditionAndData($where, $data_for_sql);
                    $this->web_data['total_page'] = ceil($count['COUNT(id)'] / $limit_for_page);
                    if ($pagination) {
                        $limit .= ' OFFSET ?';
                        $limit_jump = ($this->web_data['page'] - 1) * $limit_for_page;
                        $data_for_sql[] = $limit_jump;
                    }
                    $items = $this->ItemModel->GetItemsByConditionAndData($where . ' ' . $order . ' ' . $limit, $data_for_sql);
                } else {
                    $count = $this->ItemModel->CountItems();
                    $this->web_data['total_page'] = ceil($count['COUNT(id)'] / $limit_for_page);
                    if ($pagination) {
                        $limit .= ' OFFSET ?';
                        $limit_jump = ($this->web_data['page'] - 1) * $limit_for_page;
                        $data_for_sql[] = $limit_jump;
                    }
                    if (!empty($data_for_sql)) {
                        $items = $this->ItemModel->GetItemsByConditionAndData($order . ' ' . $limit, $data_for_sql);
                    } else {
                        $items = $this->ItemModel->GetItemsByCondition($order . ' ' . $limit);
                    }
                }
                if (!empty($hidden_params)) {
                    $params_without_filter = '';
                    $params_without_search = '';
                    $params_without_minmax = '';
                    $params_without_sort = '';
                    $params_without_limit = '';
                    $params_search_link = '?';
                    $params_page_link = '?';
                    foreach ($hidden_params as $hidden_param) {
                        if ($hidden_param['name'] != 'filter' && $hidden_param['name'] != 'page') {
                            $params_without_filter .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
                        }
                        if ($hidden_param['name'] != 'search' && $hidden_param['name'] != 'page') {
                            $params_without_search .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
                            $params_search_link .= $hidden_param['name'] . '=' . $hidden_param['value'] . '&';
                        }
                        if ($hidden_param['name'] != 'max' && $hidden_param['name'] != 'min' && $hidden_param['name'] != 'page') {
                            $params_without_minmax .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
                        }
                        if ($hidden_param['name'] != 'sort') {
                            $params_without_sort .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
                        }
                        if (($hidden_param['name'] != 'limit') && $hidden_param['name'] != 'page') {
                            $params_without_limit .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
                        }
                        if ($hidden_param['name'] != 'page') {
                            $params_page_link .= $hidden_param['name'] . '=' . $hidden_param['value'] . '&';
                        }
                    }
                    $this->web_data['params_without_filter'] = $params_without_filter;
                    $this->web_data['params_without_search'] = $params_without_search;
                    $this->web_data['params_without_minmax'] = $params_without_minmax;
                    $this->web_data['params_without_sort'] = $params_without_sort;
                    $this->web_data['params_without_limit'] = $params_without_limit;
                    $this->web_data['params_search_link'] = $params_search_link;
                    $this->web_data['params_page_link'] = $params_page_link;
                }
            } else {
                $count = $this->ItemModel->CountItems();
                $this->web_data['total_page'] = ceil($count['COUNT(id)'] / 5);
                $items = $this->ItemModel->GetItemsByCondition('ORDER BY item_date_added DESC LIMIT 5');
            }
            if (!empty($items)) {
                $this->web_data['items'] = $this->input_control->FormatPriceAndGetFirstImageOfItems($items);
            } else {
                $this->web_data['notfound_item'] = true;
            }
            parent::GetView('Admin/Items', $this->web_data);
        } else {
            $this->session_control->KillSession();
            header('Location: ' . URL);
            exit(0);
        }
    }
    function ItemDetails(string $item_url = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->input_control->CheckUrl();
            $item_url = $this->input_control->CheckGETInput($item_url);
            if (!empty($item_url)) {
                $item = $this->ItemModel->GetItemByUrl($item_url);
                if (!empty($item)) {
                    $item = $this->input_control->GetItemImages($item);
                    if (!empty($item['item_images'])) {
                        $this->web_data['item'] = $item;
                    } else {
                        $this->web_data['notfound_item'] = true;
                    }
                } else {
                    $this->web_data['notfound_item'] = true;
                }
                // aynı-1
                $filters_main = $this->FilterModel->GetFiltersForAdminItem();
                if (!empty($filters_main)) {
                    $filters = array();
                    foreach ($filters_main as $filter_main) {
                        $filters_sub = $this->FilterModel->GetSubFiltersByFilterIdForAdminItem($filter_main['id']);
                        if (!empty($filters_sub)) {
                            $filters[$filter_main['filter_name']] = array(
                                'filters_sub' => $filters_sub,
                                'filter_type' => $filter_main['filter_type']
                            );
                        }
                    }
                    $this->web_data['filters'] = $filters;
                }
                // aynı-1
                parent::GetView('Admin/ItemDetails', $this->web_data);
            } else {
                header('Location: ' . URL . '/AdminController/Items');
                exit(0);
            }
        } else {
            header('Location: ' . URL . '/404');
            exit(0);
        }
    }
    function ItemComments(string $item_url = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->input_control->CheckUrl(array('filter', 'search', 'sort', 'limit', 'page'));
            $checked_url = $this->input_control->CheckGETInput($item_url);
            if (!empty($checked_url)) {
                $item = $this->ItemModel->GetItemByUrlForComment($checked_url);
                if (!empty($item)) {
                    $this->web_data['item'] = $item;
                    $get_with_param = false;
                    $hidden_params = array();
                    $cond_params = array();
                    $pagination = false;
                    if (isset($_GET['filter'])) {
                        $checked_filter = $this->input_control->CheckGETInput($_GET['filter']);
                        if (!is_null($checked_filter)) {
                            switch ($checked_filter) {
                                case 'butun-yorumlar':
                                    $filter_condition = 'all';
                                    break;
                                case 'gorunur-yorumlar':
                                    $filter_condition = 'is_comment_approved=1';
                                    break;
                                case 'gorunur-olmayan-yorumlar':
                                    $filter_condition = 'is_comment_approved=0';
                                    break;
                                default:
                                    $filter_condition = null;
                                    break;
                            }
                            if (!empty($filter_condition)) {
                                if ($filter_condition != 'all') {
                                    $get_with_param = true;
                                    $this->web_data['filter'] = $checked_filter;
                                    $cond_params['filter'] = $filter_condition;
                                    $hidden_params[] = array('name' => 'filter', 'value' => $checked_filter);
                                }
                            } else {
                                header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
                                exit(0);
                            }
                        } else {
                            header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
                            exit(0);
                        }
                    }
                    if (isset($_GET['search'])) {
                        $checked_search = $this->input_control->CheckGETInput($_GET['search']);
                        if (!is_null($checked_search) && strlen($checked_search) <= 200) {
                            $get_with_param = true;
                            $this->web_data['search'] = $checked_search;
                            $cond_params['search'] = $checked_search;
                            $hidden_params[] = array('name' => 'search', 'value' => $checked_search);
                        } else {
                            header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
                            exit(0);
                        }
                    }
                    if (isset($_GET['sort'])) {
                        $checked_sort = $this->input_control->CheckGETInput($_GET['sort']);
                        if (!is_null($checked_sort)) {
                            switch ($checked_sort) {
                                case 'ekleme-tarihi-azalan':
                                    $sort_for_sql = 'comment_date_added DESC';
                                    $sort_deg = 'date_added_0';
                                    break;
                                case 'ekleme-tarihi-artan':
                                    $sort_for_sql = 'comment_date_added ASC';
                                    $sort_deg = 'date_added_1';
                                    break;
                                case 'guncelleme-tarihi-azalan':
                                    $sort_for_sql = 'comment_date_added DESC';
                                    $sort_deg = 'date_update_0';
                                    break;
                                case 'guncelleme-tarihi-artan':
                                    $sort_for_sql = 'comment_date_added ASC';
                                    $sort_deg = 'date_update_1';
                                    break;
                                default:
                                    $sort_for_sql = null;
                                    break;
                            }
                            if (!empty($sort_for_sql)) {
                                $get_with_param = true;
                                $this->web_data['sort'] = $checked_sort;
                                $this->web_data['sort_deg'] = $sort_deg;
                                $cond_params['sort'] = $sort_for_sql;
                                $hidden_params[] = array('name' => 'sort', 'value' => $checked_sort);
                            } else {
                                header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
                                exit(0);
                            }
                        } else {
                            header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
                            exit(0);
                        }
                    }
                    if (isset($_GET['limit'])) {
                        $checked_limit = $this->input_control->CheckGETInput($_GET['limit']);
                        if (!is_null($checked_limit)) {
                            switch ($checked_limit) {
                                case '5':
                                    $limit_for_sql = '5';
                                    break;
                                case '10':
                                    $limit_for_sql = '10';
                                    break;
                                case '25':
                                    $limit_for_sql = '25';
                                    break;
                                case '50':
                                    $limit_for_sql = '50';
                                    break;
                                default:
                                    $limit_for_sql = null;
                                    break;
                            }
                            if (!is_null($limit_for_sql)) {
                                $get_with_param = true;
                                $this->web_data['limit'] = $limit_for_sql;
                                $cond_params['limit'] = $limit_for_sql;
                                $hidden_params[] = array('name' => 'limit', 'value' => $limit_for_sql);
                            } else {
                                header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
                                exit(0);
                            }
                        } else {
                            header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
                            exit(0);
                        }
                    }
                    if (isset($_GET['page'])) {
                        if (is_numeric($_GET['page'])) {
                            $checked_page = $this->input_control->CheckGETInput($this->input_control->IsIntegerAndPositive($_GET['page']));
                            if (!is_null($checked_page)) {
                                $get_with_param = true;
                                $pagination = true;
                                $this->web_data['page'] = $checked_page;
                                $hidden_params[] = array('name' => 'page', 'value' => $checked_page);
                            } else {
                                header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
                                exit(0);
                            }
                        } else {
                            header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
                            exit(0);
                        }
                    }
                    if (empty($this->web_data['page'])) {
                        $this->web_data['page'] = 1;
                    }
                    if ($get_with_param) {
                        $where = 'WHERE (item_id=?) ';
                        $order = '';
                        $limit = '';
                        $data_for_sql = array($item['id']);
                        foreach ($cond_params as $key => $cond_param) {
                            switch ($key) {
                                case 'filter':
                                    $where .= ' AND (' . $cond_param . ')';
                                    break;
                                case 'search':
                                    $where .= ' AND (id LIKE ? OR user_id LIKE ? OR item_id LIKE ? OR comment LIKE ?)';
                                    for ($i = 0; $i < 4; $i++) {
                                        $data_for_sql[] = '%' . $cond_param . '%';
                                    }
                                    break;
                                case 'sort':
                                    $order = 'ORDER BY ' . $cond_param;
                                    break;
                                case 'limit':
                                    $limit = 'LIMIT ' . $cond_param;
                                    $limit_for_page = $cond_param;
                                    break;
                            }
                        }
                        if (empty($order)) {
                            $order = 'ORDER BY comment_date_added DESC';
                        }
                        if (empty($limit)) {
                            $limit = 'LIMIT 5';
                            $limit_for_page = 5;
                            $this->web_data['limit'] = 5;
                        }
                        $count = $this->ItemModel->CountCommentsByConditionAndData($where, $data_for_sql);
                        $this->web_data['total_comment'] = $count['COUNT(id)'];
                        $this->web_data['total_page'] = ceil($count['COUNT(id)'] / $limit_for_page);
                        if ($pagination) {
                            $limit .= ' OFFSET ?';
                            $limit_jump = ($this->web_data['page'] - 1) * $limit_for_page;
                            $data_for_sql[] = $limit_jump;
                        }
                        $comments = $this->ItemModel->GetCommentsByConditionAndData($where . ' ' . $order . ' ' . $limit, $data_for_sql);
                        if (!empty($hidden_params)) {
                            $params_without_filter = '';
                            $params_without_search = '';
                            $params_without_sort = '';
                            $params_without_limit = '';
                            $params_search_link = '?';
                            $params_page_link = '?';
                            foreach ($hidden_params as $hidden_param) {
                                if ($hidden_param['name'] != 'filter' && $hidden_param['name'] != 'page') {
                                    $params_without_filter .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
                                }
                                if ($hidden_param['name'] != 'search' && $hidden_param['name'] != 'page') {
                                    $params_without_search .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
                                    $params_search_link .= $hidden_param['name'] . '=' . $hidden_param['value'] . '&';
                                }
                                if ($hidden_param['name'] != 'sort') {
                                    $params_without_sort .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
                                }
                                if (($hidden_param['name'] != 'limit') && $hidden_param['name'] != 'page') {
                                    $params_without_limit .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
                                }
                                if ($hidden_param['name'] != 'page') {
                                    $params_page_link .= $hidden_param['name'] . '=' . $hidden_param['value'] . '&';
                                }
                            }
                            $this->web_data['params_without_filter'] = $params_without_filter;
                            $this->web_data['params_without_search'] = $params_without_search;
                            $this->web_data['params_without_sort'] = $params_without_sort;
                            $this->web_data['params_without_limit'] = $params_without_limit;
                            $this->web_data['params_search_link'] = $params_search_link;
                            $this->web_data['params_page_link'] = $params_page_link;
                        }
                    } else {
                        $count = $this->ItemModel->CountComments($item['id']);
                        $this->web_data['total_comment'] = $count['COUNT(id)'];
                        $this->web_data['total_page'] = ceil($count['COUNT(id)'] / 5);
                        $comments = $this->ItemModel->GetCommentsByConditionAndData('WHERE item_id=? ORDER BY comment_date_added DESC LIMIT 5', array($item['id']));
                    }
                    if (!empty($comments)) {
                        $users = array();
                        foreach ($comments as $comment) {
                            $comment_user = $this->UserModel->GetCommentUserByUserId($comment['user_id']);
                            if (!empty($comment_user)) {
                                $users[$comment['user_id']] = $comment_user;
                            }
                        }
                        $this->web_data['comments'] = $comments;
                        $this->web_data['comment_users'] = $users;
                    }
                } else {
                    $this->web_data['notfound_item'] = true;
                }
                parent::GetView('Admin/ItemComments', $this->web_data);
            } else {
                header('Location: ' . URL . '/AdminController/Items');
                exit(0);
            }
        } else {
            header('Location: ' . URL . '/404');
            exit(0);
        }
    }
    function ItemCreate()
    {
        // item_cart_id için strtr(sodium_bin2base64(random_bytes(2), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'A', '_' => '9'));
        $this->input_control->CheckUrl();
        // aynı-1
        $filters_main = $this->FilterModel->GetFiltersForAdminItem();
        if (!empty($filters_main)) {
            $filters = array();
            foreach ($filters_main as $filter_main) {
                $filters_sub = $this->FilterModel->GetSubFiltersByFilterIdForAdminItem($filter_main['id']);
                if (!empty($filters_sub)) {
                    $filters[$filter_main['filter_name']] = array(
                        'filters_sub' => $filters_sub,
                        'filter_type' => $filter_main['filter_type']
                    );
                }
            }
            $this->web_data['filters'] = $filters;
        }
        // aynı-1
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_item'])) {
            $posted_inputs = array(
                'item_name' => array('input' => isset($_POST['item_name']) ? $_POST['item_name'] : '', 'length' => 100, 'error_message' => 'Ürünün adı boş olamaz ve karakter sınırını geçemez', 'control_method' => array('control_syntax' => 'IsString')),
                'item_url' => array('input' => isset($_POST['item_name']) ? $_POST['item_name'] : '', 'length' => 100, 'error_message' => 'Ürünün adı boş olamaz ve karakter sınırını geçemez', 'control_method' => array('control_syntax' => 'IsString', 'control_extra' => array('GenerateUrl'))),
                'item_price' => array('input' => isset($_POST['item_price']) ? $_POST['item_price'] : '', 'length' => 9, 'error_message' => 'Ürün fiyatı boş, sıfır, negatif ve 9 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsFloatAndPositive')),
                'item_discount_price' => array('input' => isset($_POST['item_discount_price']) ? $_POST['item_discount_price'] : '', 'length' => 9, 'error_message' => 'Ürünün indirimli fiyatı boş, sıfır, negatif ve 9 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsFloatAndPositive')),
                'item_material' => array('input' => isset($_POST['item_material']) ? $_POST['item_material'] : '', 'length' => 25, 'error_message' => 'Ürünün materyali boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_fabric_type' => array('input' => isset($_POST['item_fabric_type']) ? $_POST['item_fabric_type'] : '', 'length' => 25, 'error_message' => 'Ürünün kumaş tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_model' => array('input' => isset($_POST['item_model']) ? $_POST['item_model'] : '', 'length' => 25, 'error_message' => 'Ürünün modeli boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_lapel' => array('input' => isset($_POST['item_lapel']) ? $_POST['item_lapel'] : '', 'length' => 25, 'error_message' => 'Ürünün yaka tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_thickness' => array('input' => isset($_POST['item_thickness']) ? $_POST['item_thickness'] : '', 'length' => 25, 'error_message' => 'Ürünün kalınlığı boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_pattern' => array('input' => isset($_POST['item_pattern']) ? $_POST['item_pattern'] : '', 'length' => 25, 'error_message' => 'Ürünün deseni boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_sleeve_type' => array('input' => isset($_POST['item_sleeve_type']) ? $_POST['item_sleeve_type'] : '', 'length' => 25, 'error_message' => 'Ürünün kol tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_sleeve_length' => array('input' => isset($_POST['item_sleeve_length']) ? $_POST['item_sleeve_length'] : '', 'length' => 25, 'error_message' => 'Ürünün kol uzunluğu boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_washing_style' => array('input' => isset($_POST['item_washing_style']) ? $_POST['item_washing_style'] : '', 'length' => 50, 'error_message' => 'Ürünün yıkama stili boş ve 50 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_model_size' => array('input' => isset($_POST['item_model_size']) ? $_POST['item_model_size'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin bedeni boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_model_height' => array('input' => isset($_POST['item_model_height']) ? $_POST['item_model_height'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin uzunluğu boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_model_weight' => array('input' => isset($_POST['item_model_weight']) ? $_POST['item_model_weight'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin ağırlığı boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_total_number' => array('input' => isset($_POST['item_total_number']) ? $_POST['item_total_number'] : '', 'length' => 7, 'error_message' => 'Ürünün toplam stok adedi boş ve 7 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsInteger')),
                'item_in_shipping' => array('input' => isset($_POST['item_in_shipping']) ? $_POST['item_in_shipping'] : '', 'length' => 2, 'error_message' => 'Ürünün kargo süresi boş ve 2 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsIntegerAndPositive'))
            );
            if (!empty($filters)) {
                $size_calculator = 0;
                foreach ($filters as $key => $filter) {
                    if ($filter['filter_type'] === 2) {
                        $key_for_db = str_replace(' ', '_', $key);
                        $posted_sub_filter = isset($_POST[$key_for_db]) ? $_POST[$key_for_db] : '';
                        $match = null;
                        foreach ($filter['filters_sub'] as $filter_sub) {
                            if ($filter_sub['filter_sub_name'] === $posted_sub_filter) {
                                $match = $filter_sub['id'];
                                break;
                            }
                        }
                        if (!empty($match)) {
                            $posted_inputs[$key_for_db] = array('input' => $match, 'length' => 50, 'error_message' => ucwords($key) . ' boş olamaz', 'control_method' => array('control_syntax' => 'IsString'));
                        } else {
                            $posted_inputs[$key_for_db] = array('input' => '', 'length' => 50, 'error_message' => ucwords($key) . ' boş olamaz', 'control_method' => array('control_syntax' => 'IsString'));
                            break;
                        }
                    } else {
                        foreach ($filter['filters_sub'] as $filter_sub) {
                            $key_for_db = str_replace(' ', '_', $key) . '_' . str_replace(' ', '_', $filter_sub['filter_sub_name']);
                            if ($filter['filter_type'] === 1) {
                                $posted_sub_filter = isset($_POST[$key_for_db]) ? (int)$_POST[$key_for_db] : '';
                                $posted_inputs[$key_for_db] = array('input' => $posted_sub_filter, 'length' => 7, 'error_message' => ucwords($key) . ' (' . strtoupper($filter_sub['filter_sub_name']) . ') boş ve 7 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsInteger'));
                                $key_for_size = SIZE_NAME . '_' . str_replace(' ', '_', $filter_sub['filter_sub_name']);
                                $posted_size = isset($_POST[$key_for_size]) ? (int)$_POST[$key_for_size] : 0;
                                $size_calculator += $posted_size;
                            }
                            if ($filter['filter_type'] === 3) {
                                $posted_sub_filter = isset($_POST[$key_for_db]) ? 1 : 0;
                                $posted_inputs[$key_for_db] = array('input' => $posted_sub_filter, 'length' => 1, 'error_message' => ucwords($key) . ' (' . ucwords($filter_sub['filter_sub_name']) . ') boş olamaz', 'control_method' => array('control_syntax' => 'IsInteger'));
                            }
                        }
                    }
                }
            }
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            $error = false;
            if (empty($checked_inputs['error_message'])) {
                if (!empty($size_calculator) && $size_calculator !== (int)$checked_inputs['item_total_number']) {
                    $error = true;
                    $this->notification_control->SetNotification('DANGER', 'Beden stok adetleri ile toplam stok adedi uyuşmuyor');
                } elseif (isset($_FILES['item_image'])) {
                    $item_images = $_FILES['item_image'];
                    $image_tmp_names = array();
                    $image_names = array();
                    if (count($item_images['tmp_name']) >= 3 && count($item_images['tmp_name']) <= 6) {
                        for ($i = 0; $i < count($item_images['tmp_name']); $i++) {
                            if ($item_images['error'][$i] == 0) {
                                if ($item_images['size'][$i] <= (1024 * 1024 * ITEM_IMAGE_MAX_SIZE_MB)) {
                                    $accepted_image_types = ITEM_IMAGE_ACCEPTED_TYPES;
                                    if (in_array($item_images['type'][$i], $accepted_image_types)) {
                                        $image_names[] = explode('.', $item_images['name'][$i]);
                                        $image_tmp_names[] = $item_images['tmp_name'][$i];
                                    } else {
                                        $error = true;
                                        $this->web_data['image_error_message'] = 'Görselin uzantısı desteklenmiyor (Görsel ' . ($i + 1) . ')' . ' (Desteklenen uzantılar: ' . ITEM_IMAGE_ACCEPTED_TYPES_STR . ')';
                                        $this->notification_control->SetNotification('DANGER', 'Görselin uzantısı desteklenmiyor (Görsel ' . ($i + 1) . ')' . ' (Desteklenen uzantılar: ' . ITEM_IMAGE_ACCEPTED_TYPES_STR . ')');
                                        break;
                                    }
                                } else {
                                    $error = true;
                                    $this->web_data['image_error_message'] = 'Görselin boyutu ' . ITEM_IMAGE_MAX_SIZE_MB . ' MB dan fazla olamaz (Görsel ' . ($i + 1) . ')';
                                    $this->notification_control->SetNotification('DANGER', 'Görselin boyutu ' . ITEM_IMAGE_MAX_SIZE_MB . ' MB dan fazla olamaz (Görsel ' . ($i + 1) . ')');
                                    break;
                                }
                            } else {
                                $error = true;
                                $this->web_data['image_error_message'] = 'Görsel yüklenirken bir hata oldu (Görsel ' . ($i + 1) . ')';
                                $this->notification_control->SetNotification('DANGER', 'Görsel yüklenirken bir hata oldu (Görsel ' . ($i + 1) . ')');
                                break;
                            }
                        }
                    } else {
                        $error = true;
                        $this->web_data['image_error_message'] = 'En az 3 adet en fazla 6 adet ürün görseli yükleyebilirsiniz';
                        $this->notification_control->SetNotification('DANGER', 'En az 3 adet ürün görseli yükleyin');
                    }
                    if (!$error) {
                        $id = strtolower(substr(strtr(base64_encode(hash_hmac('SHA512', time(), base64_encode(random_bytes(128)), true)), array('+' => '1', '=' => 'j', '/' => 'm', '.' => 's', '_' => 'b')), 30, 50));
                        $new_image_folder = UPLOAD_IMAGES_PATH . $id;
                        if (!is_dir($new_image_folder)) {
                            if (mkdir($new_image_folder, 0777, true)) {
                                $item_images_db = '';
                                for ($i = 0; $i < count($image_tmp_names); $i++) {
                                    $image_random_name = strtolower(substr(strtr(base64_encode(hash_hmac('SHA512', time(), base64_encode(random_bytes(128)), true)), array('+' => '3', '=' => '5', '/' => '7', '.' => '4', '_' => '9')), 21, 10));
                                    $width = IMAGE_ORI_WIDTH_SIZE;
                                    $height = IMAGE_ORI_HEIGHT_SIZE;
                                    $width_mini = IMAGE_MINI_WIDTH_SIZE;
                                    $height_mini = IMAGE_MINI_HEIGHT_SIZE;
                                    $dst_image = imagecreatetruecolor($width, $height);
                                    $dst_image_mini = imagecreatetruecolor($width_mini, $height_mini);
                                    $image_infos = getimagesize($image_tmp_names[$i]);
                                    $image_width = $image_infos[0];
                                    $image_height = $image_infos[1];
                                    $uploadImageType = $image_infos[2];
                                    if ($uploadImageType == 2) {
                                        $src_image = imagecreatefromjpeg($image_tmp_names[$i]);
                                        imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
                                        imagejpeg($dst_image, $new_image_folder . '/' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 100);
                                        imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $width_mini, $height_mini, $image_width, $image_height);
                                        imagejpeg($dst_image_mini, $new_image_folder . '/' . 'mini' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 100);
                                        imagedestroy($src_image);
                                    } elseif ($uploadImageType == 3) {
                                        $src_image = imagecreatefrompng($image_tmp_names[$i]);
                                        imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
                                        imagepng($dst_image, $new_image_folder . '/' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 9);
                                        imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $width_mini, $height_mini, $image_width, $image_height);
                                        imagepng($dst_image_mini, $new_image_folder . '/' . 'mini' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 9);
                                        imagedestroy($src_image);
                                    }
                                    $item_images_db .= ($i + 1) . '-' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1] . '_';
                                }
                                $checked_inputs['item_images'] = rtrim($item_images_db, '_');
                                $checked_inputs['item_ishome'] = isset($_POST['item_ishome']) ? 1 : 0;
                                $checked_inputs['item_insale'] = isset($_POST['item_insale']) ? 1 : 0;
                                $checked_inputs['id'] = $id;
                                $result = $this->ItemModel->CreateItem($checked_inputs);
                                if ($result['result'] == 'Created') {
                                    $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['item_name']) . ' başarıyla eklendi');
                                } else {
                                    $this->notification_control->SetNotification('DANGER', 'Ürün ekleme başarısız');
                                }
                                header('Location: ' . URL . '/AdminController/Items');
                            } else {
                                $error = true;
                                $data['image_error_message'] = 'Ürün görselleri yüklenirken bir hata oldu';
                                $this->notification_control->SetNotification('DANGER', 'Ürün görselleri yüklenirken bir hata oldu');
                            }
                        } else {
                            $error = true;
                            $this->web_data['image_error_message'] = 'Ürün görselleri yüklenirken bir hata oldu';
                            $this->notification_control->SetNotification('DANGER', 'Ürün görselleri yüklenirken bir hata oldu');
                        }
                    }
                } else {
                    $error = true;
                    $this->web_data['image_error_message'] = 'En az 3 adet ürün görseli yükleyin';
                    $this->notification_control->SetNotification('DANGER', 'En az 3 adet ürün görseli yükleyin');
                }
            } else {
                $error = true;
                $this->web_data['error_input'] = $checked_inputs['error_input'];
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
            if ($error) {
                $inputs = array();
                foreach ($posted_inputs as $key => $posted_input) {
                    $inputs[$key] = $posted_input['input'];
                }
                $this->web_data['item'] = $inputs;
                parent::GetView('Admin/ItemCreate', $this->web_data);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            parent::GetView('Admin/ItemCreate', $this->web_data);
        } else {
            $this->session_control->KillSession();
            header('Location: ' . URL);
            exit(0);
        }
    }
    function ItemUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
            // aynı-1
            $filters_main = $this->FilterModel->GetFiltersForAdminItem();
            if (!empty($filters_main)) {
                $filters = array();
                foreach ($filters_main as $filter_main) {
                    $filters_sub = $this->FilterModel->GetSubFiltersByFilterIdForAdminItem($filter_main['id']);
                    if (!empty($filters_sub)) {
                        $filters[$filter_main['filter_name']] = array(
                            'filters_sub' => $filters_sub,
                            'filter_type' => $filter_main['filter_type']
                        );
                    }
                }
                $this->web_data['filters'] = $filters;
            }
            // aynı-1
            $posted_inputs = array(
                'id' => array('input' => isset($_POST['id']) ? $_POST['id'] : '', 'length' => 50, 'error_message' => "Bir hata oldu", 'control_method' => array('control_syntax' => 'IsString')),
                'item_name' => array('input' => isset($_POST['item_name']) ? $_POST['item_name'] : '', 'length' => 100, 'error_message' => 'Ürünün adı boş olamaz ve karakter sınırını geçemez', 'control_method' => array('control_syntax' => 'IsString')),
                'item_url' => array('input' => isset($_POST['item_name']) ? $_POST['item_name'] : '', 'length' => 100, 'error_message' => 'Ürünün adı boş olamaz ve karakter sınırını geçemez', 'control_method' => array('control_syntax' => 'IsString', 'control_extra' => array('GenerateUrl'))),
                'item_price' => array('input' => isset($_POST['item_price']) ? $_POST['item_price'] : '', 'length' => 9, 'error_message' => 'Ürün fiyatı boş, sıfır, negatif ve 9 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsFloatAndPositive')),
                'item_discount_price' => array('input' => isset($_POST['item_discount_price']) ? $_POST['item_discount_price'] : '', 'length' => 9, 'error_message' => 'Ürünün indirimli fiyatı boş, sıfır, negatif ve 9 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsFloatAndPositive')),
                'item_material' => array('input' => isset($_POST['item_material']) ? $_POST['item_material'] : '', 'length' => 25, 'error_message' => 'Ürünün materyali boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_fabric_type' => array('input' => isset($_POST['item_fabric_type']) ? $_POST['item_fabric_type'] : '', 'length' => 25, 'error_message' => 'Ürünün kumaş tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_model' => array('input' => isset($_POST['item_model']) ? $_POST['item_model'] : '', 'length' => 25, 'error_message' => 'Ürünün modeli boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_lapel' => array('input' => isset($_POST['item_lapel']) ? $_POST['item_lapel'] : '', 'length' => 25, 'error_message' => 'Ürünün yaka tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_thickness' => array('input' => isset($_POST['item_thickness']) ? $_POST['item_thickness'] : '', 'length' => 25, 'error_message' => 'Ürünün kalınlığı boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_pattern' => array('input' => isset($_POST['item_pattern']) ? $_POST['item_pattern'] : '', 'length' => 25, 'error_message' => 'Ürünün deseni boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_sleeve_type' => array('input' => isset($_POST['item_sleeve_type']) ? $_POST['item_sleeve_type'] : '', 'length' => 25, 'error_message' => 'Ürünün kol tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_sleeve_length' => array('input' => isset($_POST['item_sleeve_length']) ? $_POST['item_sleeve_length'] : '', 'length' => 25, 'error_message' => 'Ürünün kol uzunluğu boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_washing_style' => array('input' => isset($_POST['item_washing_style']) ? $_POST['item_washing_style'] : '', 'length' => 50, 'error_message' => 'Ürünün yıkama stili boş ve 50 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_model_size' => array('input' => isset($_POST['item_model_size']) ? $_POST['item_model_size'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin bedeni boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_model_height' => array('input' => isset($_POST['item_model_height']) ? $_POST['item_model_height'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin uzunluğu boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_model_weight' => array('input' => isset($_POST['item_model_weight']) ? $_POST['item_model_weight'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin ağırlığı boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
                'item_total_number' => array('input' => isset($_POST['item_total_number']) ? $_POST['item_total_number'] : '', 'length' => 7, 'error_message' => 'Ürünün toplam stok adedi boş ve 7 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsInteger')),
                'item_in_shipping' => array('input' => isset($_POST['item_in_shipping']) ? $_POST['item_in_shipping'] : '', 'length' => 2, 'error_message' => 'Ürünün kargo süresi boş ve 2 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsIntegerAndPositive'))
            );
            if (!empty($filters)) {
                $size_calculator = 0;
                foreach ($filters as $key => $filter) {
                    if ($filter['filter_type'] === 2) {
                        $key_for_db = str_replace(' ', '_', $key);
                        $posted_sub_filter = isset($_POST[$key_for_db]) ? $_POST[$key_for_db] : '';
                        $match = null;
                        foreach ($filter['filters_sub'] as $filter_sub) {
                            if ($filter_sub['filter_sub_name'] === $posted_sub_filter) {
                                $match = $filter_sub['id'];
                                break;
                            }
                        }
                        if (!empty($match)) {
                            $posted_inputs[$key_for_db] = array('input' => $match, 'length' => 50, 'error_message' => ucwords($key) . ' boş olamaz', 'control_method' => array('control_syntax' => 'IsString'));
                        } else {
                            $posted_inputs[$key_for_db] = array('input' => '', 'length' => 50, 'error_message' => ucwords($key) . ' boş olamaz', 'control_method' => array('control_syntax' => 'IsString'));
                            break;
                        }
                    } else {
                        foreach ($filter['filters_sub'] as $filter_sub) {
                            $key_for_db = str_replace(' ', '_', $key) . '_' . str_replace(' ', '_', $filter_sub['filter_sub_name']);
                            if ($filter['filter_type'] === 1) {
                                $posted_sub_filter = isset($_POST[$key_for_db]) ? (int)$_POST[$key_for_db] : '';
                                $posted_inputs[$key_for_db] = array('input' => $posted_sub_filter, 'length' => 7, 'error_message' => ucwords($key) . ' (' . strtoupper($filter_sub['filter_sub_name']) . ') boş ve 7 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsInteger'));
                                $key_for_size = SIZE_NAME . '_' . str_replace(' ', '_', $filter_sub['filter_sub_name']);
                                $posted_size = isset($_POST[$key_for_size]) ? (int)$_POST[$key_for_size] : 0;
                                $size_calculator += $posted_size;
                            }
                            if ($filter['filter_type'] === 3) {
                                $posted_sub_filter = isset($_POST[$key_for_db]) ? 1 : 0;
                                $posted_inputs[$key_for_db] = array('input' => $posted_sub_filter, 'length' => 1, 'error_message' => ucwords($key) . ' (' . ucwords($filter_sub['filter_sub_name']) . ') boş olamaz', 'control_method' => array('control_syntax' => 'IsInteger'));
                            }
                        }
                    }
                }
            }
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            $error = false;
            if (empty($checked_inputs['error_message'])) {
                $image_setted = false;
                $image_setted_position = array();
                if (!empty($_FILES['item_image']['tmp_name'])) {
                    for ($i = 0; $i < count($_FILES['item_image']['tmp_name']); $i++) {
                        if (!empty($_FILES['item_image']['tmp_name'][$i])) {
                            $image_setted = true;
                            $image_setted_position[] = $i;
                        }
                    }
                }
                if (!empty($size_calculator) && $size_calculator !== (int)$checked_inputs['item_total_number']) {
                    $error = true;
                    $this->notification_control->SetNotification('DANGER', 'Beden stok adetleri ile toplam stok adedi uyuşmuyor');
                } elseif ($image_setted) {
                    $item_images = $_FILES['item_image'];
                    $image_tmp_names = array();
                    $image_names = array();
                    if (count($item_images['tmp_name']) >= 3 && count($item_images['tmp_name']) <= 6) {
                        for ($i = 0; $i < count($image_setted_position); $i++) {
                            $index = $image_setted_position[$i];
                            if ($item_images['error'][$index] == 0) {
                                if ($item_images['size'][$index] <= (1024 * 1024 * ITEM_IMAGE_MAX_SIZE_MB)) {
                                    $accepted_image_types = ITEM_IMAGE_ACCEPTED_TYPES;
                                    if (in_array($item_images['type'][$index], $accepted_image_types)) {
                                        $image_names[] = explode('.', $item_images['name'][$index]);
                                        $image_tmp_names[] = $item_images['tmp_name'][$index];
                                    } else {
                                        $error = true;
                                        $this->web_data['image_error_message'] = 'Görselin uzantısı desteklenmiyor (Görsel ' . ($index + 1) . ')' . ' (Desteklenen uzantılar: ' . ITEM_IMAGE_ACCEPTED_TYPES_STR . ')';
                                        $this->notification_control->SetNotification('DANGER', 'Görselin uzantısı desteklenmiyor (Görsel ' . ($index + 1) . ')' . ' (Desteklenen uzantılar: ' . ITEM_IMAGE_ACCEPTED_TYPES_STR . ')');
                                        break;
                                    }
                                } else {
                                    $error = true;
                                    $this->web_data['image_error_message'] = 'Görselin boyutu ' . ITEM_IMAGE_MAX_SIZE_MB . ' MB dan fazla olamaz (Görsel ' . ($index + 1) . ')';
                                    $this->notification_control->SetNotification('DANGER', 'Görselin boyutu ' . ITEM_IMAGE_MAX_SIZE_MB . ' MB dan fazla olamaz (Görsel ' . ($index + 1) . ')');
                                    break;
                                }
                            } else {
                                $error = true;
                                $this->web_data['image_error_message'] = 'Görsel yüklenirken bir hata oldu (Görsel ' . ($index + 1) . ')';
                                $this->notification_control->SetNotification('DANGER', 'Görsel yüklenirken bir hata oldu (Görsel ' . ($index + 1) . ')');
                                break;
                            }
                        }
                    } else {
                        $error = true;
                        $this->web_data['image_error_message'] = 'En az 3 adet en fazla 6 adet ürün görseli yükleyebilirsiniz';
                        $this->notification_control->SetNotification('DANGER', 'En az 3 adet ürün görseli yükleyin');
                    }
                    if (!$error) {
                        $image_folder = UPLOAD_IMAGES_PATH . $checked_inputs['id'];
                        if (is_dir($image_folder)) {
                            $files = glob($image_folder . '/*');
                            $item_images_db = '';
                            $item_images_db_passed = 0;
                            for ($i = 0; $i < count($image_tmp_names); $i++) {
                                $image_random_name = strtolower(substr(strtr(base64_encode(hash_hmac('SHA512', time(), base64_encode(random_bytes(128)), true)), array('+' => '3', '=' => '5', '/' => '7', '.' => '4', '_' => '9')), 21, 10));
                                $item_images_from_db = $this->ItemModel->GetItemImagesForAdminUpdate($checked_inputs['id']);
                                if (!empty($item_images_from_db)) {
                                    $checked_item_images = $this->input_control->GetItemImages($item_images_from_db);
                                    $index = $image_setted_position[$i] + 1;
                                    if ($index < count($checked_item_images['item_images']) + 1) {
                                        foreach ($checked_item_images['item_images'] as $item_image) {
                                            if ($item_images_db_passed >= $item_image[0]) {
                                                continue;
                                            }
                                            if ($item_image[0] == $index) {
                                                foreach ($files as $file) {
                                                    if (is_file($file) && ($file == $image_folder . '/' . $item_image[1] || $file == $image_folder . '/mini' . $item_image[1])) {
                                                        unlink($file);
                                                    }
                                                }
                                                $item_image[1] = $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1];
                                                $item_images_db .= $item_image[0] . '-' . $item_image[1] . '_';
                                            } elseif ($item_image[0] < $index) {
                                                $item_images_db .= $item_image[0] . '-' . $item_image[1] . '_';
                                            } elseif ((($i + 1) == count($image_tmp_names)) && ($item_image[0] > $index)) {
                                                $item_images_db .= $item_image[0] . '-' . $item_image[1] . '_';
                                            }
                                        }
                                        $item_images_db_passed = $index;
                                    } else {
                                        if (!empty($item_images_db)) {
                                            $item_images_db .= $index . '-' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1] . '_';
                                        } else {
                                            $item_images_db .= $item_images_from_db['item_images'] . '_' . $index . '-' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1] . '_';
                                        }
                                    }
                                    $width = IMAGE_ORI_WIDTH_SIZE;
                                    $height = IMAGE_ORI_HEIGHT_SIZE;
                                    $width_mini = IMAGE_MINI_WIDTH_SIZE;
                                    $height_mini = IMAGE_MINI_HEIGHT_SIZE;
                                    $dst_image = imagecreatetruecolor($width, $height);
                                    $dst_image_mini = imagecreatetruecolor($width_mini, $height_mini);
                                    $image_infos = getimagesize($image_tmp_names[$i]);
                                    $image_width = $image_infos[0];
                                    $image_height = $image_infos[1];
                                    $uploadImageType = $image_infos[2];
                                    if ($uploadImageType == 2) {
                                        $src_image = imagecreatefromjpeg($image_tmp_names[$i]);
                                        imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
                                        imagejpeg($dst_image, $image_folder . '/' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 100);
                                        imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $width_mini, $height_mini, $image_width, $image_height);
                                        imagejpeg($dst_image_mini, $image_folder . '/' . 'mini' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 100);
                                        imagedestroy($src_image);
                                    } elseif ($uploadImageType == 3) {
                                        $src_image = imagecreatefrompng($image_tmp_names[$i]);
                                        imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
                                        imagepng($dst_image, $image_folder . '/' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 9);
                                        imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $width_mini, $height_mini, $image_width, $image_height);
                                        imagepng($dst_image_mini, $image_folder . '/' . 'mini' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 9);
                                        imagedestroy($src_image);
                                    }
                                } else {
                                    $error = true;
                                    $this->web_data['image_error_message'] = 'Ürün görselleri yüklenirken bir hata oldu';
                                    $this->notification_control->SetNotification('DANGER', 'Ürün görselleri yüklenirken bir hata oldu');
                                }
                            }
                            $checked_inputs['item_images'] = rtrim($item_images_db, '_');
                            $checked_inputs['item_ishome'] = isset($_POST['item_ishome']) ? 1 : 0;
                            $checked_inputs['item_insale'] = isset($_POST['item_insale']) ? 1 : 0;
                            $temp_id = $checked_inputs['id'];
                            unset($checked_inputs['id']);
                            $checked_inputs['id'] = $temp_id;
                            $result = $this->ItemModel->UpdateItem($checked_inputs);
                            if ($result == 'Updated') {
                                $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['item_name']) . ' başarıyla güncellendi');
                            } else {
                                $this->notification_control->SetNotification('DANGER', 'Ürün güncelleme başarısız');
                            }
                            header('Location: ' . URL . '/AdminController/Items');
                        } else {
                            $error = true;
                            $this->web_data['image_error_message'] = 'Ürün görselleri yüklenirken bir hata oldu';
                            $this->notification_control->SetNotification('DANGER', 'Ürün görselleri yüklenirken bir hata oldu');
                        }
                    }
                } else {
                    $checked_inputs['item_ishome'] = isset($_POST['item_ishome']) ? 1 : 0;
                    $checked_inputs['item_insale'] = isset($_POST['item_insale']) ? 1 : 0;
                    $temp_id = $checked_inputs['id'];
                    unset($checked_inputs['id']);
                    $checked_inputs['id'] = $temp_id;
                    $result = $this->ItemModel->UpdateItem($checked_inputs);
                    if ($result == 'Updated') {
                        $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['item_name']) . ' başarıyla güncellendi');
                    } else {
                        $this->notification_control->SetNotification('DANGER', 'Ürün güncelleme başarısız');
                    }
                    header('Location: ' . URL . '/AdminController/Items');
                    exit(0);
                }
            } else {
                $error = true;
                $this->web_data['error_input'] = $checked_inputs['error_input'];
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
            if ($error) {
                if ((!empty($checked_inputs['error_input']) && ($checked_inputs['error_input'] != 'id')) || (!empty($posted_inputs['id']['input']) && (strlen($posted_inputs['id']['input'] != 50)))) {
                    $item = $this->ItemModel->GetItemForAdminUpdate($posted_inputs['id']['input']);
                    if (!empty($item)) {
                        $inputs = array();
                        foreach ($posted_inputs as $key => $posted_input) {
                            $inputs[$key] = $posted_input['input'];
                        }
                        $inputs['item_ishome'] = isset($_POST['item_ishome']) ? 1 : 0;
                        $inputs['item_insale'] = isset($_POST['item_insale']) ? 1 : 0;
                        $inputs['item_date_added'] = $item['item_date_added'];
                        $inputs['item_date_updated'] = $item['item_date_updated'];
                        $item = $this->input_control->GetItemImages($item);
                        if (!is_null($item['item_images'])) {
                            $inputs['item_images'] = $item['item_images'];
                        } else {
                            $this->web_data['notfound_item'] = $this->notification_control->NotFound("Ürün Bulunamadı");
                        }
                        $this->web_data['item'] = $inputs;
                        $comments = $this->ItemModel->GetCommentsByItemId($posted_inputs['id']['input']);
                        if (!empty($comments)) {
                            $users = array();
                            foreach ($comments as $comment) {
                                $comment_user = $this->UserModel->GetCommentUserByUserId($comment['user_id']);
                                if (!empty($comment_user)) {
                                    $users[$comment['user_id']] = $comment_user;
                                }
                            }
                            $this->web_data['comments'] = $comments;
                            $this->web_data['comment_users'] = $users;
                        }
                    } else {
                        $this->web_data['notfound_item'] = true;
                    }
                } else {
                    $this->web_data['notfound_item'] = true;
                }
                parent::GetView('Admin/ItemDetails', $this->web_data);
            }
        } else {
            $this->session_control->KillSession();
            header('Location: ' . URL);
            exit(0);
        }
    }
    function ItemDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
            $item_id = $this->input_control->IsString(isset($_POST['item_id']) ? $_POST['item_id'] : '');
            if (!is_null($item_id)) {
                $checked_id = $this->input_control->PreventXSS($item_id);
                $result = $this->ItemModel->DeleteItem($checked_id);
                if ($result == 'Deleted') {
                    $image_folder = UPLOAD_IMAGES_PATH . $checked_id;
                    $result_delete_images = false;
                    if (is_dir($image_folder)) {
                        $files = glob($image_folder . '/*');
                        foreach ($files as $file) {
                            unlink($file);
                        }
                        $result_delete_images = rmdir($image_folder);
                    }
                    if ($result_delete_images) {
                        $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', 'Ürün başarıyla silindi');
                    } else {
                        $this->notification_control->SetNotification('DANGER', 'Ürün başarıyla silindi. Ürün görselleri silme başarısız');
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', 'Ürün silme başarısız');
                }
                header('Location: ' . URL . '/AdminController/Items');
            } else {
                $this->session_control->KillSession();
                header('Location: ' . URL);
                exit(0);
            }
        } else {
            $this->session_control->KillSession();
            header('Location: ' . URL);
            exit(0);
        }
    }

    function Filters()
    {
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['s'])) {
            $search_input = $this->input_control->IsString($_GET['s']);
            if ($search_input !== null) {
                $checked_search = $this->input_control->CheckAndGenerateUrl($search_input);
                $data['search'] = $checked_search;
                $searched_filters = $this->FilterModel->SearchFilters($checked_search);
                !empty($searched_filters) ? $data['filters'] = $searched_filters : $data['notfound_search'] = $this->notification_control->NotFound('Aranılan Kriterde Filtre Bulunamadı');
            } else {
                header('Location: ' . URL . '/AdminController/Filters');
            }
        } else {
            $filters = $this->FilterModel->GetFilters();
            !empty($filters) ? $data['filters'] = $filters : $data['notfound_filter'] = $this->notification_control->NotFound('Kayıtlı Filtre Yok');
        }
        parent::GetView('Admin/Filters', $data);
    }
    function FilterDetails(string $filter_url = null)
    {
        $url_input = $this->input_control->IsString($filter_url);
        if ($url_input !== null) {
            $data = array();
            $filter = $this->FilterModel->GetFilterByUrl($this->input_control->CheckAndGenerateUrl($url_input));
            if (!empty($filter)) {
                $data['filter'] = $filter;
                $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
                !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
            } else {
                $data['notfound_filter'] = $this->notification_control->NotFound("Filtre Bulunamadı");
            }
            parent::GetView('Admin/FilterDetails', $data);
        } else {
            header('Location: ' . URL . '/AdminController/Filters');
        }
    }
    function FilterCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_filter'])) {
            $posted_filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : '';
            $posted_inputs = array(
                'filter_name' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Adı-50',
                'filter_url' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Url-100$GenerateUrl',
                'filter_type' => ($posted_filter_type == 1 || $posted_filter_type == 2 || $posted_filter_type == 3) ? $posted_filter_type . '|Filtre&Tipi' : '|Filtre&Tipi'
            );
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            if (empty($checked_inputs['input_error_name'])) {
                $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
                $data = array();
                $filter_names = $this->FilterModel->GetAllFilterWithName();
                $filter_name_match = true;
                if (!empty($filter_names)) {
                    foreach ($filter_names as $filter_name) {
                        if ($filter_name['filter_name'] === $checked_inputs['filter_name']) {
                            $filter_name_match = false;
                            break;
                        }
                    }
                }
                if ($filter_name_match) {
                    $data_redirect = array();
                    $result = $this->FilterModel->CreateFilter($checked_inputs);
                    if ($checked_inputs['filter_type'] == 2) {
                        $result2 = $this->FilterModel->CreateItemColumnCountable($checked_inputs['filter_name']);
                        $_SESSION[SESSION_NOTIFICATION_NAME] = ($result['result'] == 'Created' && $result2 == 'Column Created') ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Eklendi') : $this->notification_control->Danger('Filtre Ekleme Başarısız');
                    } else {
                        $_SESSION[SESSION_NOTIFICATION_NAME] = $result['result'] == 'Created' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Eklendi') : $this->notification_control->Danger('Filtre Ekleme Başarısız');
                    }
                    $filters = $this->FilterModel->GetFilters();
                    !empty($filters) ? $data_redirect['filters'] = $filters : $data_redirect['notfound_filter'] = $this->notification_control->NotFound('Kayıtlı Filtre Yok');
                    parent::GetView('Admin/Filters', $data_redirect);
                } else {
                    $data['filter'] = $checked_inputs;
                    $data['input_error_key'] = 'filter_duplicate';
                    $this->notification_control->SetNotification('DANGER', 'Filtre Adı Zaten Kayıtlı');
                    parent::GetView('Admin/FilterCreate', $data);
                }
            } else {
                $data['filter'] = $checked_inputs['input_datas'];
                $data['input_error_key'] = $checked_inputs['input_error_key'];
                $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
                parent::GetView('Admin/FilterCreate', $data);
            }
        } else {
            parent::GetView('Admin/FilterCreate');
        }
    }
    function FilterUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_filter'])) {
            $posted_filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : '';
            $posted_inputs = array(
                'filter_name' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Adı-50',
                'filter_url' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Url-100$GenerateUrl',
                'filter_type' => ($posted_filter_type == 1 || $posted_filter_type == 2 || $posted_filter_type == 3) ? $posted_filter_type . '|Filtre&Tipi' : '|Filtre&Tipi',
                'id' => (isset($_POST['id']) ? $_POST['id'] : '') . '|Filtre&ID'
            );
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            $data = array();
            if (empty($checked_inputs['input_error_name'])) {
                $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
                $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['id']);
                if (!empty($filter)) {
                    $filter_ori_type = $filter['filter_type'];
                    $data['filter'] = $filter;
                    $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
                    !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $this->notification_control->NotFound($filter['filter_name'] . ' Bulunamadı');
                    $filter_names = $this->FilterModel->GetAllFilterWithName();
                    $filter_name_match = true;
                    $filter_ori_name = $this->input_control->CheckPostedInputForDb($this->input_control->IsString(isset($_POST['filter_ori_name']) ? $_POST['filter_ori_name'] : ''));
                    if (!empty($filter_names)) {
                        foreach ($filter_names as $filter_name) {
                            if ($checked_inputs['filter_name'] == $filter_ori_name) {
                                break;
                            }
                            if ($filter_name['filter_name'] == $checked_inputs['filter_name']) {
                                $filter_name_match = false;
                                break;
                            }
                        }
                    }
                    if ($filter_name_match) {
                        $data_redirect = array();
                        $persmission = true;
                        foreach (CANT_DELETE_FILTER_IDS as $cant_delete_filter) {
                            if ($checked_inputs['id'] == $cant_delete_filter) {
                                $persmission = false;
                            }
                        }
                        if ($persmission) {
                            $result = $this->FilterModel->UpdateFilter($checked_inputs);
                            if ($filter_ori_type == 2 && $checked_inputs['filter_type'] == 2) {
                                $result2 = $this->FilterModel->DeleteItemColumn($filter_ori_name);
                                $result3 = $this->FilterModel->CreateItemColumnCountable($checked_inputs['filter_name']);
                                $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Updated' && $result2 == 'Column Deleted' && $result3 == 'Column Created') ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger('Filtre Güncelleme Başarısız');
                            } elseif ($filter_ori_type == 2 && $checked_inputs['filter_type'] != 2) {
                                $result2 = $this->FilterModel->DeleteItemColumn($filter_ori_name);
                                $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Updated' && $result2 == 'Column Deleted') ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger('Filtre Güncelleme Başarısız');
                            } elseif ($filter_ori_type != 2 && $checked_inputs['filter_type'] == 2) {
                                $result2 = $this->FilterModel->CreateItemColumnCountable($checked_inputs['filter_name']);
                                $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Updated' && $result2 == 'Column Created') ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger('Filtre Güncelleme Başarısız');
                            } else {
                                $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger(ucwords($checked_inputs['filter_name']) . ' Güncelleme Başarısız');
                            }
                            $filters = $this->FilterModel->GetFilters();
                            !empty($filters) ? $data_redirect['filters'] = $filters : $data_redirect['notfound_filter'] =  $this->notification_control->NotFound('Filtre Bulunamadı');
                            parent::GetView('Admin/Filters', $data_redirect);
                        } else {
                            header('Location: ' . URL . '/AdminController/Filters');
                        }
                    } else {
                        $data['filter'] = $filter;
                        $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
                        !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
                        $data['input_error_key'] = 'filter_duplicate';
                        $this->notification_control->SetNotification('DANGER', 'Filtre Adı Zaten Kayıtlı');
                        parent::GetView('Admin/FilterDetails', $data);
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Filters');
                }
            } else {
                $filter = $this->FilterModel->GetFilterById('*', $this->input_control->CheckPostedInputForDb($checked_inputs['input_datas']['id']));
                if (!empty($filter)) {
                    $data['filter'] = $filter;
                    $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
                    !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
                    $data['input_error_key'] = $checked_inputs['input_error_key'];
                    $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
                    parent::GetView('Admin/FilterDetails', $data);
                } else {
                    header('Location: ' . URL . '/AdminController/Filters');
                }
            }
        } else {
            header('Location: ' . URL . '/AdminController/Filters');
        }
    }
    function FilterDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_filter'])) {
            $input_id = $this->input_control->IsString(isset($_POST['filter_id']) ? $_POST['filter_id'] : '');
            if ($input_id !== null) {
                $data = array();
                $checked_id = $this->input_control->CheckPostedInputForDb($input_id);
                $persmission = true;
                foreach (CANT_DELETE_FILTER_IDS as $cant_delete_filter) {
                    if ($checked_id == $cant_delete_filter) {
                        $persmission = false;
                    }
                }
                if ($persmission) {
                    $filter = $this->FilterModel->GetFilterById('*', $checked_id);
                    $result = $this->FilterModel->DeleteFilter($checked_id);
                    if ($filter['filter_type'] == 2) {
                        $result2 = $this->FilterModel->DeleteItemColumn($filter['filter_name']);
                        $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Deleted' && $result2 == 'Column Deleted') ? $this->notification_control->SetNotification('SUCCESS', 'Filtre Başarıyla Silindi') : $this->notification_control->Danger('Filtre Silme Başarısız');
                    } else {
                        $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Deleted' ? $this->notification_control->SetNotification('SUCCESS', 'Filtre Başarıyla Silindi') : $this->notification_control->Danger('Filtre Silme Başarısız');
                    }
                    $filters = $this->FilterModel->GetFilters();
                    !empty($filters) ? $data['filters'] = $filters : $data['notfound_filter'] = $this->notification_control->NotFound('Kayıtlı Filtre Yok');
                    parent::GetView('Admin/Filters', $data);
                } else {
                    header('Location: ' . URL . '/AdminController/Filters');
                }
            } else {
                header('Location: ' . URL . '/AdminController/Filters');
            }
        } else {
            header('Location: ' . URL . '/AdminController/Filters');
        }
    }

    function FilterSubDetails(string $filter_sub_url = null)
    {
        $url_input = $this->input_control->IsString($filter_sub_url);
        if ($url_input !== null) {
            $data = array();
            $filter_sub = $this->FilterModel->GetSubFilterByUrl($this->input_control->CheckAndGenerateUrl($url_input));
            if (!empty($filter_sub)) {
                $data['filter_sub'] = $filter_sub;
                $filter = $this->FilterModel->GetFilterById('*', $filter_sub['filter_id']);
                if (!empty($filter)) {
                    $data['filter'] = $filter;
                    $filter_type = $filter['filter_type'];
                    if ($filter_type == 2) {
                        $items = $this->ItemModel->GetItemBySubFilter($filter['filter_name'], $filter_sub['id']);
                    } elseif ($filter_type == 1 || $filter_type = 3) {
                        $sub_name_fordb = str_replace(' ', '_', $filter_sub['filter_sub_name']);
                        $items = $this->ItemModel->GetAllItemWithSubFilter($filter['filter_name'] . '_' . $sub_name_fordb);
                    }
                    !empty($items) ? $data['items'] = $items : $data['notfound_item'] = $this->notification_control->NotFound(ucwords($filter_sub['filter_sub_name']) . ' Filtresine Ait Ürün Yok');
                } else {
                    $data['notfound_filter'] = $this->notification_control->NotFound('Filtre Bulunamadı');
                }
            } else {
                $data['notfound_filter_sub'] = $this->notification_control->NotFound('Alt Filtre Bulunamadı');
            }
            parent::GetView('Admin/FilterSubDetails', $data);
        } else {
            header('Location: ' . URL . '/AdminController/Filters');
        }
    }
    function FilterSubCreate()
    {
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_filter_sub'])) {
            $posted_inputs = array(
                'filter_id' => (isset($_POST['filter_id']) ? $_POST['filter_id'] : '') . '|Filtre&ID',
                'filter_sub_name' => (isset($_POST['filter_sub_name']) ? $_POST['filter_sub_name'] : '') . '|Adı-50',
                'filter_sub_url' => (isset($_POST['filter_sub_name']) ? $_POST['filter_sub_name'] : '') . '|Alt&Filtre&Url-100$GenerateUrl'
            );
            $posted_filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : '';
            $posted_inputs2 = array(
                'filter_type' => ($posted_filter_type == 1 || $posted_filter_type == 2 || $posted_filter_type == 3) ? $posted_filter_type . '|Filtre&Tipi' : '|Filtre&Tipi',
                'filter_name' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Adı'
            );
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            $checked_inputs2 = $this->input_control->CheckPostedInputs($posted_inputs2);
            if (empty($checked_inputs['input_error_name'])) {
                $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
                if (!empty($checked_inputs2['input_error_name'])) {
                    header('Location: ' . URL . '/AdminController/Filters');
                }
                $checked_inputs2 = $this->input_control->CheckPostedInputsForDb($checked_inputs2['input_datas']);
                $sub_filter_names = $this->FilterModel->GetAllSubFilterWithName();
                $filter_name_match = true;
                if (!empty($sub_filter_names)) {
                    foreach ($sub_filter_names as $sub_filter_name) {
                        if ($sub_filter_name['filter_sub_name'] === $checked_inputs['filter_sub_name']) {
                            $filter_name_match = false;
                            break;
                        }
                    }
                }
                if ($filter_name_match) {
                    $filter = $this->FilterModel->GetValidFilterById($checked_inputs['filter_id']);
                    if (!empty($filter)) {
                        if ($checked_inputs2['filter_type'] == 2) {
                            $result = $this->FilterModel->CreateFilterSub($checked_inputs);
                            if ($filter['filter_has_sub'] == 0) {
                                $result3 = $this->FilterModel->UpdateFilterValidation(array('filter_has_sub' => 1, 'id' => $checked_inputs['filter_id']));
                                $_SESSION[SESSION_NOTIFICATION_NAME] = ($result['result'] == 'Created' && $result3 == 'Updated') ? $this->notification_control->SetNotification('SUCCESS', $checked_inputs['filter_sub_name'] . ' Başarıyla Eklendi') : $this->notification_control->Danger($checked_inputs['filter_sub_name'] . ' Ekleme Başarısız');
                            }
                            if (empty($_SESSION[SESSION_NOTIFICATION_NAME])) {
                                $result['result'] == 'Created' ? $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', $checked_inputs['filter_sub_name'] . ' Başarıyla Eklendi') : $this->notification_control->Danger($checked_inputs['filter_sub_name'] . ' Ekleme Başarısız');
                            }
                        } elseif ($checked_inputs2['filter_type'] == 1 || $checked_inputs2['filter_type'] == 3) {
                            $result = $this->FilterModel->CreateFilterSub($checked_inputs);
                            $sub_name_fordb = str_replace(' ', '_', $checked_inputs['filter_sub_name']);
                            $result2 = $this->FilterModel->CreateItemColumnCountable($checked_inputs2['filter_name'] . '_' . $sub_name_fordb);
                            if ($filter['filter_has_sub'] == 0) {
                                $result3 = $this->FilterModel->UpdateFilterValidation(array('filter_has_sub' => 1, 'id' => $checked_inputs['filter_id']));
                                $_SESSION[SESSION_NOTIFICATION_NAME] = ($result['result'] == 'Created' && $result2 == 'Column Created' && $result3 == 'Updated') ? $this->notification_control->SetNotification('SUCCESS', $checked_inputs['filter_sub_name'] . ' Başarıyla Eklendi') : $this->notification_control->Danger($checked_inputs['filter_sub_name'] . ' Ekleme Başarısız');
                            }
                            $_SESSION[SESSION_NOTIFICATION_NAME] = ($result['result'] == 'Created' && $result2 == 'Column Created') ? $this->notification_control->SetNotification('SUCCESS', $checked_inputs['filter_sub_name'] . ' Başarıyla Eklendi') : $this->notification_control->Danger($checked_inputs['filter_sub_name'] . ' Ekleme Başarısız');
                        } else {
                            header('Location: ' . URL . '/AdminController/Filters');
                        }
                        $url_input = $this->input_control->IsString($this->input_control->GenerateUrl($checked_inputs2['filter_name']));
                        if ($url_input !== null) {
                            $filter = $this->FilterModel->GetFilterByUrl($this->input_control->CheckAndGenerateUrl($url_input));
                            if (!empty($filter)) {
                                $data['filter'] = $filter;
                                $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
                                !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
                            } else {
                                $data['notfound_filter'] = $this->notification_control->NotFound("Filtre Bulunamadı");
                            }
                            parent::GetView('Admin/FilterDetails', $data);
                        } else {
                            header('Location: ' . URL . '/AdminController/Filters');
                        }
                    } else {
                        header('Location: ' . URL . '/AdminController/Filters');
                    }
                } else {
                    $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['filter_id']);
                    if (!empty($filter)) {
                        $data['filter'] = $filter;
                        $data['filter_sub'] = $checked_inputs;
                        $data['input_error_key'] = 'sub_filter_duplicate';
                        $this->notification_control->SetNotification('DANGER', ucwords($filter['filter_name']) . ' Filtre Adı Zaten Kayıtlı');
                        parent::GetView('Admin/FilterSubCreate', $data);
                    } else {
                        header('Location: ' . URL . '/AdminController/Filters');
                    }
                }
            } else {
                $filter = $this->FilterModel->GetFilterById('*', $this->input_control->CheckPostedInputForDb($checked_inputs['input_datas']['filter_id']));
                if (!empty($filter)) {
                    $data['filter'] = $filter;
                    $data['filter_sub'] = $checked_inputs['input_datas'];
                    $data['input_error_key'] = $checked_inputs['input_error_key'];
                    $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger(ucwords($filter['filter_name']) . ' ' . $checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
                    parent::GetView('Admin/FilterSubCreate', $data);
                } else {
                    header('Location: ' . URL . '/AdminController/Filters');
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view_filter_sub'])) {
            $posted_id = $this->input_control->IsString(isset($_POST['filter_id']) ? $_POST['filter_id'] : '');
            if ($posted_id !== null) {
                $filter = $this->FilterModel->GetFilterById('*', $this->input_control->CheckPostedInputForDb($posted_id));
                if (!empty($filter)) {
                    $data['filter'] = $filter;
                    parent::GetView('Admin/FilterSubCreate', $data);
                } else {
                    header('Location: ' . URL . '/AdminController/Filters');
                }
            } else {
                header('Location: ' . URL . '/AdminController/Filters');
            }
        } else {
            header('Location: ' . URL . '/AdminController/Filters');
        }
    }
    function FilterSubUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_filter_sub'])) {
            $posted_inputs = array(
                'filter_id' => (isset($_POST['filter_id']) ? $_POST['filter_id'] : '') . '|Filtre&ID',
                'filter_sub_name' => (isset($_POST['filter_sub_name']) ? $_POST['filter_sub_name'] : '') . '|Alt&Filtre&Adı-50',
                'filter_sub_url' => (isset($_POST['filter_sub_name']) ? $_POST['filter_sub_name'] : '') . '|Alt&Filtre&Url-100$GenerateUrl',
                'id' => (isset($_POST['id']) ? $_POST['id'] : '') . '|Alt&Filtre&ID'
            );
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            $data = array();
            if (empty($checked_inputs['input_error_name'])) {
                $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
                $filter_sub_names = $this->FilterModel->GetAllSubFilterWithName();
                $filter_name_match = true;
                if (!empty($filter_sub_names)) {
                    foreach ($filter_sub_names as $filter_sub_name) {
                        if ($filter_sub_name['filter_sub_name'] === $checked_inputs['filter_sub_name']) {
                            $filter_name_match = false;
                            break;
                        }
                    }
                }
                if ($filter_name_match) {
                    $filter_sub = $this->FilterModel->GetFilterSubById($checked_inputs['id']);
                    $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['filter_id']);
                    $result = $this->FilterModel->UpdateFilterSub($checked_inputs);
                    if ($filter['filter_type'] == 1 || $filter['filter_type'] == 3) {
                        $sub_name_fordb = str_replace(' ', '_', $checked_inputs['filter_sub_name']);
                        $old_sub_name_fordb = str_replace(' ', '_', $filter_sub['filter_sub_name']);
                        $this->FilterModel->RenameItemColumn($filter['filter_name'] . '_' . $old_sub_name_fordb, $filter['filter_name'] . '_' . $sub_name_fordb);
                    }
                    $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_sub_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger('Alt Filtre Güncelleme Başarısız');
                    $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['filter_id']);
                    if (!empty($filter)) {
                        $data['filter'] = $filter;
                        $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
                        !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
                    } else {
                        $data['notfound_filter'] = $this->notification_control->NotFound("Filtre Bulunamadı");
                    }
                    parent::GetView('Admin/FilterDetails', $data);
                } else {
                    $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['filter_id']);
                    $filter_sub = $this->FilterModel->GetFilterSubById($checked_inputs['id']);
                    if (!empty($filter) && !empty($filter_sub)) {
                        $data['filter'] = $filter;
                        $data['filter_sub'] = $filter_sub;
                        $filter_type = $filter['filter_type'];
                        if ($filter_type == 2) {
                            $items = $this->ItemModel->GetItemBySubFilter($filter['filter_name'], $filter_sub['filter_sub_name']);
                        } elseif ($filter_type == 1 || $filter_type = 3) {
                            $items = $this->ItemModel->GetAllItemWithSubFilter($filter['filter_name'] . '_' . $filter_sub['filter_sub_name']);
                        }
                        !empty($items) ? $data['items'] = $items : $data['notfound_item'] = $this->notification_control->NotFound('Alt Filtreye Ait Ürün Yok');
                        $data['input_error_key'] = 'sub_filter_duplicate';
                        $this->notification_control->SetNotification('DANGER', ucwords($filter['filter_name']) . ' Filtre Adı Zaten Kayıtlı');
                        parent::GetView('Admin/FilterSubDetails', $data);
                    } else {
                        header('Location: ' . URL . '/AdminController/Filters');
                    }
                }
            } else {
                $filter = $this->FilterModel->GetFilterById('*', $this->input_control->CheckPostedInputForDb($checked_inputs['input_datas']['filter_id']));
                $filter_sub = $this->FilterModel->GetFilterSubById($checked_inputs['input_datas']['id']);
                if (!empty($filter) && !empty($filter_sub)) {
                    $data['filter'] = $filter;
                    $data['filter_sub'] = $filter_sub;
                    $filter_type = $filter['filter_type'];
                    if ($filter_type == 2) {
                        $items = $this->ItemModel->GetItemBySubFilter($filter['filter_name'], $filter_sub['filter_sub_name']);
                    } elseif ($filter_type == 1 || $filter_type = 3) {
                        $items = $this->ItemModel->GetAllItemWithSubFilter($filter['filter_name'] . '_' . $filter_sub['filter_sub_name']);
                    }
                    !empty($items) ? $data['items'] = $items : $data['notfound_item'] = $this->notification_control->NotFound('Alt Filtreye Ait Ürün Yok');
                    $data['input_error_key'] = $checked_inputs['input_error_key'];
                    $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger(ucwords($filter['filter_name']) . ' ' . $checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
                    parent::GetView('Admin/FilterSubDetails', $data);
                } else {
                    header('Location: ' . URL . '/AdminController/Filters');
                }
            }
        } else {
            header('Location: ' . URL . '/AdminController/Filters');
        }
    }
    function FilterSubDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_filter_sub'])) {
            $input_id = $this->input_control->IsString(isset($_POST['filter_sub_id']) ? $_POST['filter_sub_id'] : '');
            if ($input_id !== null) {
                $data = array();
                $checked_id = $this->input_control->CheckPostedInputForDb($input_id);
                $permission = true;
                foreach (CANT_DELETE_FILTER_SUB_IDS as $cant_delete_filtersub) {
                    if ($checked_id == $cant_delete_filtersub) {
                        $permission = false;
                        break;
                    }
                }
                if ($permission) {
                    $filter_sub = $this->FilterModel->GetFilterSubById($checked_id);
                    if (!empty($filter_sub)) {
                        $filter = $this->FilterModel->GetFilterById('*', $filter_sub['filter_id']);
                        if (!empty($filter)) {
                            $data['filter'] = $filter;
                            $result = $this->FilterModel->DeleteFilterSub($checked_id);
                            $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
                            if (!empty($filters_sub)) {
                                $data['filters_sub'] = $filters_sub;
                            } else {
                                $data['notfound_filter_sub'] = $this->notification_control->NotFound('Kayıtlı Alt Filtre Yok');
                                $this->FilterModel->UpdateFilterHasNoSub(array('filter_has_sub' => 0, 'id' => $filter['id']));
                            }
                            if ($filter['filter_type'] == 2) {
                                $result2 = $this->FilterModel->EmptyItemColumn($filter['filter_name'], $filter_sub['id']);
                                $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Deleted' && $result2 == 'Updated') ? $this->notification_control->SetNotification('SUCCESS', $filter_sub['filter_sub_name'] . ' Filtresi Başarıyla Silindi') : $this->notification_control->Danger($filter_sub['filter_sub_name'] . ' Filtre Silme Başarısız');
                            } elseif ($filter['filter_type'] == 1 || $filter['filter_type'] === 3) {
                                $sub_name_fordb = str_replace(' ', '_', $filter_sub['filter_sub_name']);
                                $result2 = $this->FilterModel->DeleteItemColumn($filter['filter_name'] . '_' . $sub_name_fordb);
                                $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Deleted' && $result2 == 'Column Deleted') ? $this->notification_control->SetNotification('SUCCESS', $filter_sub['filter_sub_name'] . ' Filtresi Başarıyla Silindi') : $this->notification_control->Danger($filter_sub['filter_sub_name'] . ' Filtre Silme Başarısız');
                            } else {
                                header('Location: ' . URL . '/AdminController/Filters');
                            }
                            parent::GetView('Admin/FilterDetails', $data);
                        } else {
                            header('Location: ' . URL . '/AdminController/Filters');
                        }
                    } else {
                        header('Location: ' . URL . '/AdminController/Filters');
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Filters');
                }
            } else {
                header('Location: ' . URL . '/AdminController/Filters');
            }
        } else {
            header('Location: ' . URL . '/AdminController/Filters');
        }
    }

    function Users()
    {
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['s'])) {
            $search_input = $this->input_control->IsString($_GET['s']);
            if ($search_input !== null) {
                $checked_search = $this->input_control->CheckAndGenerateUrl($search_input);
                $data['search'] = $checked_search;
                $searched_users = $this->UserModel->SearchUsers($checked_search);
                if (!empty($searched_users)) {
                    foreach ($searched_users as $key => $user) {
                        $searched_users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
                    }
                    $data['users'] = $searched_users;
                } else {
                    $data['notfound_search'] = $this->notification_control->NotFound('Aranılan Kriterde Kullanıcı Bulunamadı');
                }
            } else {
                header('Location: ' . URL . '/AdminController/Users');
            }
        } else {
            $users = $this->UserModel->GetAllUser();
            if (!empty($users)) {
                foreach ($users as $key => $user) {
                    $users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
                }
                $data['users'] = $users;
            } else {
                $data['notfound_user'] = $this->notification_control->NotFound('Kayıtlı Ürün Yok');
            }
        }
        parent::GetView('Admin/Users', $data);
    }
    function UserDetails(string $user_id = null)
    {
        $url_input = $this->input_control->IsString($user_id);
        if ($url_input !== null) {
            $data = array();
            $user = $this->UserModel->GetUserById('*', $this->input_control->CheckAndGenerateUrl($url_input));
            if (!empty($user)) {
                $user['email'] = $this->input_control->DecodeMail($user['email']);
                $data['user'] = $user;
                parent::GetModel('RoleModel');
                $data['roles'] = $this->RoleModel->GetRoleNamesAndId();
                $comments = $this->UserModel->GetCommentsByUserId($user['id']);
                if (!empty($comments)) {
                    $items = array();
                    foreach ($comments as $comment) {
                        $items[$comment['user_id']] = $this->ItemModel->GetItemNameAndUrlById($comment['item_id']);
                    }
                    $data['comments'] = $comments;
                    $data['items'] = $items;
                } else {
                    $data['notfound_comment'] = $this->notification_control->NotFound("Kullanıcıya Ait Yorum Yok");
                }
            } else {
                $data['notfound_user'] = $this->notification_control->NotFound("Kullanıcı Bulunamadı");
            }
            parent::GetView('Admin/UserDetails', $data);
        } else {
            header('Location: ' . URL . '/AdminController/Users');
        }
    }
    function UserCreate()
    {
        parent::GetModel('RoleModel');
        $roles = $this->RoleModel->GetRoleNamesAndId();
        if (!empty($roles)) {
            $data = array();
            $data['roles'] = $roles;
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
                $posted_user_role = $this->input_control->IsString(isset($_POST['user_role']) ? $_POST['user_role'] : '');
                if ($posted_user_role !== null) {
                    $role_name_match = false;
                    foreach ($roles as $role) {
                        if ($role['role_name'] == $posted_user_role) {
                            $role_name_match = true;
                            break;
                        }
                    }
                    if ($role_name_match) {
                        $user_role = $role['id'];
                        $id = substr(hash('sha256', base64_encode('bKs=!Z1' . time() . 'b@9Alc_h1')), 10, 20);
                        $posted_inputs = array(
                            'id' => $id . '|Kullanıcı&ID',
                            'first_name' => (isset($_POST['first_name']) ? $_POST['first_name'] : '') . '|Ad-20',
                            'last_name' => (isset($_POST['last_name']) ? $_POST['last_name'] : '') . '|SoyAd-20',
                            'email' => (isset($_POST['email']) ? $_POST['email'] : '') . '|Email-345',
                            'email_confirmed' => (isset($_POST['email_confirmed']) ? '1' : '0') . '|Email&Doğrulama',
                            'tel' => (isset($_POST['tel']) ? $_POST['tel'] : '') . '|Telefon&Numarası-10',
                            'tel_confirmed' => (isset($_POST['tel_confirmed']) ? '1' : '0') . '|Telefon&Doğrulama',
                            'user_password' => $this->input_control->EncodePassword('BlanckBasic_123') . '|Şifre',
                            'user_role' => $user_role . '|Kullanıcı&Rolü'
                        );
                        $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
                        if (empty($checked_inputs['input_error_name'])) {
                            $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
                            $permission = true;
                            foreach (CANT_CREATE_USER_EMAILS as $cant_create_user) {
                                if ($checked_inputs['email'] == $cant_create_user) {
                                    $permission = false;
                                    break;
                                }
                            }
                            if ($permission) {
                                $users_emails = $this->UserModel->GetUsersEmails();
                                if (!empty($users_emails)) {
                                    $valid_mail = $this->input_control->CheckAndEncodeMail($checked_inputs['email']);
                                    if ($valid_mail !== null) {
                                        foreach ($users_emails as $user_email) {
                                            if ($user_email['email'] === $checked_inputs['email']) {
                                                $data['input_error_key'] = 'email_duplicate';
                                                $this->notification_control->SetNotification('DANGER', 'Email Zaten Kayıtlı');
                                                break;
                                            }
                                        }
                                    } else {
                                        $data['input_error_key'] = 'not_valid_email';
                                        $this->notification_control->SetNotification('DANGER', 'Email Geçerli Değil');
                                    }
                                }
                            } else {
                                $data['input_error_key'] = 'not_valid_email';
                                $this->notification_control->SetNotification('DANGER', 'Email Geçerli Değil');
                            }
                            if (empty($data['input_error_key'])) {
                                $users_tels = $this->UserModel->GetUsersPhoneNumbers();
                                $tel_duplicate = true;
                                if (!empty($users_tels)) {
                                    foreach ($users_tels as $user_tel) {
                                        if ($user_tel['tel'] === '90' . $checked_inputs['tel']) {
                                            $data['input_error_key'] = 'tel_duplicate';
                                            $this->notification_control->SetNotification('DANGER', 'Telefon Numarası Zaten Kayıtlı');
                                            $tel_duplicate = false;
                                            break;
                                        }
                                    }
                                }
                                if ($tel_duplicate && strlen($checked_inputs['tel']) != 10) {
                                    $data['input_error_key'] = 'tel';
                                    $this->notification_control->SetNotification('DANGER', 'Telefon Numarası Geçersiz');
                                }
                            }
                            if (empty($data['input_error_key'])) {
                                $new_image_folder = USER_IMAGES_PATH . $id;
                                if (!is_dir($new_image_folder)) {
                                    mkdir($new_image_folder, 0777, true);
                                }
                                $checked_inputs['tel'] = '90' . $checked_inputs['tel'];
                                $checked_inputs['email'] = $this->input_control->CheckAndEncodeMail($checked_inputs['email']);
                                $result = $this->UserModel->CreateUser($checked_inputs);
                                $data_redirect = array();
                                $_SESSION[SESSION_NOTIFICATION_NAME] = $result['result'] == 'Created' ? $this->notification_control->SetNotification('SUCCESS', 'Kullanıcı Başarıyla Eklendi') : $this->notification_control->Danger('Kullanıcı Ekleme Başarısız');
                                $users = $this->UserModel->GetAllUser();
                                foreach ($users as $key => $user) {
                                    $users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
                                }
                                !empty($users) ? $data_redirect['users'] = $users : $data_redirect['notfound_user'] = $this->notification_control->NotFound('Kayıtlı Kullanıcı Yok');
                                parent::GetView('Admin/Users', $data_redirect);
                            } else {
                                $data['user'] = $checked_inputs;
                                parent::GetView('Admin/UserCreate', $data);
                            }
                        } else {
                            $data['user'] = $checked_inputs['input_datas'];
                            $data['input_error_key'] = $checked_inputs['input_error_key'];
                            $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
                            parent::GetView('Admin/UserCreate', $data);
                        }
                    } else {
                        header('Location: ' . URL . '/AdminController/Users');
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Users');
                }
            } else {
                parent::GetView('Admin/UserCreate', $data);
            }
        } else {
            parent::GetView('Admin/Users', array('notification' => $this->notification_control->Danger('Önce Bir Rol Eklemelsiniz')));
        }
    }
    function UserUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
            parent::GetModel('RoleModel');
            $roles = $this->RoleModel->GetRoleNamesAndId();
            $posted_user_role = $this->input_control->IsString(isset($_POST['user_role']) ? $_POST['user_role'] : '');
            if ($posted_user_role !== null) {
                $role_name_match = false;
                foreach ($roles as $role) {
                    if ($role['role_name'] == $posted_user_role) {
                        $role_name_match = true;
                        break;
                    }
                }
                if ($role_name_match) {
                    $user_role = $role['id'];
                    $posted_inputs = array(
                        'user_role' => $user_role . '|Kullanıcı&Rolü',
                        'id' => (isset($_POST['id']) ? $_POST['id'] : '') . '|Kullanıcı&ID'
                    );
                    $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
                    if (empty($checked_inputs['input_error_name'])) {
                        $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
                        $data_redirect = array();
                        $result = $this->UserModel->UpdateUser($checked_inputs);
                        $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', 'Kullanıcı Başarıyla Güncellendi') : $this->notification_control->Danger('Kullanıcı Güncelleme Başarısız');
                        $users = $this->UserModel->GetAllUser();
                        foreach ($users as $key => $user) {
                            $users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
                        }
                        !empty($users) ? $data_redirect['users'] = $users : $data_redirect['notfound_user'] = $this->notification_control->NotFound('Kayıtlı Kullanıcı Yok');
                        parent::GetView('Admin/Users', $data_redirect);
                    } else {
                        header('Location: ' . URL . '/AdminController/Users');
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Users');
                }
            } else {
                header('Location: ' . URL . '/AdminController/Users');
            }
        } else {
            header('Location: ' . URL . '/AdminController/Users');
        }
    }
    function UserDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
            $input_id = $this->input_control->IsString(isset($_POST['user_id']) ? $_POST['user_id'] : '');
            if ($input_id !== null) {
                $data = array();
                $checked_id = $this->input_control->CheckPostedInputForDb($input_id);
                $permission = true;
                foreach (CANT_DELETE_USER_IDS as $cant_delete_user) {
                    if ($checked_id == $cant_delete_user) {
                        $permission = false;
                        break;
                    }
                }
                if ($permission) {
                    $new_image_folder = USER_IMAGES_PATH . $checked_id;
                    if (!is_dir($new_image_folder)) {
                        rmdir($new_image_folder, 0777, true);
                    }
                    $result = $this->UserModel->DeleteUser($checked_id);
                    $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Deleted' ? $this->notification_control->SetNotification('SUCCESS', 'Kullanıcı Başarıyla Silindi') : $this->notification_control->Danger('Kullanıcı Silme Başarısız');
                    $users = $this->UserModel->GetAllUser();
                    if (!empty($users)) {
                        foreach ($users as $key => $user) {
                            $users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
                        }
                        $data['users'] = $users;
                    } else {
                        $data['notfound_user'] = $this->notification_control->NotFound('Kayıtlı Ürün Yok');
                    }
                    parent::GetView('Admin/Users', $data);
                } else {
                    header('Location: ' . URL . '/AdminController/Users');
                }
            } else {
                header('Location: ' . URL . '/AdminController/Users');
            }
        } else {
            header('Location: ' . URL . '/AdminController/Users');
        }
    }

    function Roles()
    {
        parent::GetModel('RoleModel');
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['s'])) {
            $search_input = $this->input_control->IsString($_GET['s']);
            if ($search_input !== null) {
                $checked_search = $this->input_control->CheckAndGenerateUrl($search_input);
                $data['search'] = $checked_search;
                $searched_roles = $this->RoleModel->SearchRoles($checked_search);
                !empty($searched_roles) ? $data['roles'] = $searched_roles : $data['notfound_search'] = $this->notification_control->NotFound('Aranılan Kriterde Rol Bulunamadı');
            } else {
                header('Location: ' . URL . '/AdminController/Items');
            }
        } else {
            $roles = $this->RoleModel->GetAllRole();
            !empty($roles) ? $data['roles'] = $roles : $data['notfound_role'] = $this->notification_control->NotFound('Kayıtlı Rol Yok');
        }
        parent::GetView('Admin/Roles', $data);
    }
    function RoleDetails(string $role_url = null)
    {
        $url_input = $this->input_control->IsString($role_url);
        if ($url_input !== null) {
            parent::GetModel('RoleModel');
            $data = array();
            $role = $this->RoleModel->GetRoleByUrl($this->input_control->CheckAndGenerateUrl($url_input));
            if (!empty($role)) {
                $data['role'] = $role;
                $users_in_role = $this->UserModel->GetUsersByRole($role['id']);
                if (!empty($users_in_role)) {
                    foreach ($users_in_role as $key => $user) {
                        $users_in_role[$key]['email'] = $this->input_control->DecodeMail($user['email']);
                    }
                    $data['users'] = $users_in_role;
                } else {
                    $data['notfound_user'] = $this->notification_control->NotFound(ucwords($role['role_name']) . ' Rolüne Ait Kullanıcı Bulunamadı');
                }
            } else {
                $data['notfound_role'] = $this->notification_control->NotFound("Rol Bulunamadı");
            }
            parent::GetView('Admin/RoleDetails', $data);
        } else {
            header('Location: ' . URL . '/AdminController/Roles');
        }
    }
    function RoleCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_role'])) {
            $posted_inputs = array(
                'role_name' => (isset($_POST['role_name']) ? $_POST['role_name'] : '') . '|Rol&Adı-20',
                'role_url' => (isset($_POST['role_name']) ? $_POST['role_name'] : '') . '|Rol&Url-40$GenerateUrl'
            );
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            if (empty($checked_inputs['input_error_name'])) {
                $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
                parent::GetModel('RoleModel');
                $role_urls = $this->RoleModel->GetAllRoleUrl();
                $role_not_match = true;
                foreach ($role_urls as $role_url) {
                    if ($role_url['role_url'] == $checked_inputs['role_url']) {
                        $role_not_match = false;
                        break;
                    }
                }
                if ($role_not_match) {
                    $data_redirect = array();
                    $result = $this->RoleModel->CreateRole($checked_inputs);
                    $_SESSION[SESSION_NOTIFICATION_NAME] = $result['result'] == 'Created' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['role_name']) . ' Rolü Başarıyla Eklendi') : $this->notification_control->Danger('Rol Ekleme Başarısız');
                    $roles = $this->RoleModel->GetAllRole();
                    !empty($roles) ? $data_redirect['roles'] = $roles : $data_redirect['notfound_role'] = $this->notification_control->NotFound('Kayıtlı Rol Yok');
                    parent::GetView('Admin/Roles', $data_redirect);
                } else {
                    $data['role'] = $checked_inputs;
                    $data['input_error_key'] = 'role_duplicate';
                    $this->notification_control->SetNotification('DANGER', 'Rol Zaten Kayıtlı');
                    parent::GetView('Admin/RoleCreate', $data);
                }
            } else {
                $data['role'] = $checked_inputs['input_datas'];
                $data['input_error_key'] = $checked_inputs['input_error_key'];
                $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
                parent::GetView('Admin/RoleCreate', $data);
            }
        } else {
            parent::GetView('Admin/RoleCreate');
        }
    }
    function RoleUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
            $posted_inputs = array(
                'role_name' => (isset($_POST['role_name']) ? $_POST['role_name'] : '') . '|Rol&Adı-20',
                'role_url' => (isset($_POST['role_name']) ? $_POST['role_name'] : '') . '|Rol&Url-40$GenerateUrl',
                'id' => (isset($_POST['id']) ? $_POST['id'] : '') . '|Rol&ID'
            );
            parent::GetModel('RoleModel');
            $data = array();
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            if (empty($checked_inputs['input_error_name'])) {
                $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
                $role_urls = $this->RoleModel->GetAllRoleUrl();
                $role_not_match = true;
                foreach ($role_urls as $role_url) {
                    if ($role_url['role_url'] == $checked_inputs['role_url']) {
                        $role_not_match = false;
                        break;
                    }
                }
                if ($role_not_match) {
                    $data_redirect = array();
                    $result = $this->RoleModel->UpdateRole($checked_inputs);
                    $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['role_name']) . ' Rolü Başarıyla Güncellendi') : $this->notification_control->Danger('Rol Güncelleme Başarısız');
                    $roles = $this->RoleModel->GetAllRole();
                    !empty($roles) ? $data_redirect['roles'] = $roles : $data_redirect['notfound_role'] = $this->notification_control->NotFound('Kayıtlı Rol Yok');
                    parent::GetView('Admin/Roles', $data_redirect);
                } else {
                    if (!empty($checked_inputs['id'])) {
                        $role = $this->RoleModel->GetRoleById($checked_inputs['id']);
                        if (!empty($role)) {
                            $data['role'] = $role;
                            $users_in_role = $this->UserModel->GetUsersByRole($role['id']);
                            if (!empty($users_in_role)) {
                                foreach ($users_in_role as $key => $user) {
                                    $users_in_role[$key]['email'] = $this->input_control->DecodeMail($user['email']);
                                }
                                $data['users'] = $users_in_role;
                            } else {
                                $data['notfound_user'] = $this->notification_control->NotFound(ucwords($role['role_name']) . ' Rolüne Ait Kullanıcı Bulunamadı');
                            }
                            $data['input_error_key'] = 'role_duplicate';
                            $this->notification_control->SetNotification('DANGER', 'Rol Zaten Kayıtlı');
                            parent::GetView('Admin/RoleDetails', $data);
                        } else {
                            header('Location: ' . URL . '/AdminController/Roles');
                        }
                    } else {
                        header('Location: ' . URL . '/AdminController/Roles');
                    }
                }
            } else {
                if (!empty($checked_inputs['input_datas']['id'])) {
                    $role = $this->RoleModel->GetRoleById($checked_inputs['input_datas']['id']);
                    if (!empty($role)) {
                        $data['role'] = $role;
                        $users_in_role = $this->UserModel->GetUsersByRole($role['id']);
                        if (!empty($users_in_role)) {
                            foreach ($users_in_role as $key => $user) {
                                $users_in_role[$key]['email'] = $this->input_control->DecodeMail($user['email']);
                            }
                            $data['users'] = $users_in_role;
                        } else {
                            $data['notfound_user'] = $this->notification_control->NotFound(ucwords($role['role_name']) . ' Rolüne Ait Kullanıcı Bulunamadı');
                        }
                        $data['input_error_key'] = $checked_inputs['input_error_key'];
                        $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
                        parent::GetView('Admin/RoleDetails', $data);
                    } else {
                        header('Location: ' . URL . '/AdminController/Roles');
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Roles');
                }
            }
        } else {
            header('Location: ' . URL . '/AdminController/Roles');
        }









        $posted_role_name = isset($_POST['role_name']) ? $_POST['role_name'] : '';
        $posted_inputs = array(
            'role_name' => $posted_role_name,
            'role_url' => $this->input_control->GenerateUrl($posted_role_name),
            'id' => isset($_POST['id']) ? $_POST['id'] : ''
        );
        $checked_inputs = $this->input_control->CheckPostedInputsForDb($posted_inputs);
        parent::GetModel('RoleModel');
        if (is_array($checked_inputs)) {
            $roles = $this->model->GetRoleNames();
            $role_not_match = true;
            foreach ($roles as $role) {
                if ($role['role_name'] === $checked_inputs['role_name']) {
                    $role_not_match = false;
                    break;
                }
            }
            if ($role_not_match) {
                if ($this->model->UpdateRole($checked_inputs) == 'Updated') {
                    parent::GetView('Admin/Roles', array('roles' => $this->model->GetAllRole(), 'notification' => $this->notification_control->SetNotification('SUCCESS', $checked_inputs['role_name'] . ' Başarıyla Güncellendi')));
                } else {
                    parent::GetView('Admin/Roles', array('roles' => $this->model->GetAllRole(), 'notification' => $this->notification_control->SetNotification('SUCCESS', 'Rol Güncelleme Başarısız')));
                }
            } else {
                $role = $this->model->GetRoleById($this->input_control->CheckPostedInputForDb(isset($_POST['id']) ? $_POST['id'] : ''));
                if (!empty($role)) {
                    $users_in_role = $this->model->GetUsersByRole($role['id']);
                    if (!empty($users_in_role)) {
                        parent::GetView('Admin/RoleDetails', array('role' => $role, 'users' => $users_in_role, 'input_error' => 'role_duplicate'));
                    } else {
                        parent::GetView('Admin/RoleDetails', array('role' => $role, 'notfound_user' => $this->notification_control->NotFound('Role Ait Kullanıcı Bulunamadı'), 'input_error' => 'role_duplicate'));
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Roles');
                }
            }
        } else {
            if ($checked_inputs !== 'id') {
                $role = $this->model->GetRoleById($this->input_control->CheckPostedInputForDb(isset($_POST['id']) ? $_POST['id'] : ''));
                if (!empty($role)) {
                    $users_in_role = $this->model->GetUsersByRole($role['id']);
                    if (!empty($users_in_role)) {
                        parent::GetView('Admin/RoleDetails', array('role' => $role, 'users' => $users_in_role, 'input_error' => $checked_inputs));
                    } else {
                        parent::GetView('Admin/RoleDetails', array('role' => $role, 'notfound_user' => $this->notification_control->NotFound(ucwords($role['role_name']) . ' Rolüne Ait Kullanıcı Bulunamadı'), 'input_error' => $checked_inputs));
                    }
                } else {
                    header('Location: ' . URL . '/AdminController/Roles');
                }
            } else {
                header('Location: ' . URL . '/AdminController/Roles');
            }
        }
    }
    function RoleDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_role'])) {
            $input_id = $this->input_control->IsString(isset($_POST['role_id']) ? $_POST['role_id'] : '');
            if ($input_id !== null) {
                parent::GetModel('RoleModel');
                $data = array();
                $checked_id = $this->input_control->CheckPostedInputForDb($input_id);
                $permission = true;
                foreach (CANT_DELETE_ROLE_IDS as $cant_delete_role) {
                    if ($checked_id == $cant_delete_role) {
                        $permission = false;
                        break;
                    }
                }
                if ($permission) {
                    $result = $this->RoleModel->DeleteRole($checked_id);
                    $result2 = $this->RoleModel->EmptyUserRoleColumn('user_role', $checked_id);
                    $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Deleted' && $result2 == 'Updated') ? $this->notification_control->SetNotification('SUCCESS', 'Rol Başarıyla Silindi') : $this->notification_control->Danger('Rol Silme Başarısız');
                    $roles = $this->RoleModel->GetAllRole();
                    !empty($roles) ? $data['roles'] = $roles : $data['notfound_role'] = $this->notification_control->NotFound('Kayıtlı Rol Yok');
                    parent::GetView('Admin/Roles', $data);
                } else {
                    header('Location: ' . URL . '/AdminController/Roles');
                }
            } else {
                header('Location: ' . URL . '/AdminController/Roles');
            }
        } else {
            header('Location: ' . URL . '/AdminController/Roles');
        }
    }
    function AdvertisingInfos()
    {
        $user_infos = $this->UserModel->GetAdvertisingInfos();
        foreach ($user_infos as $key => $user_info) {
            $user_infos[$key]['email'] = $this->input_control->DecodeMail($user_info['email']);
        }
        parent::GetView('Admin/AdvertisingInfos', array('user_infos' => $user_infos));
    }

    function Profile()
    {
        $data = array();
        $data['selected_link'] = 'Profile';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            $posted_inputs = array(
                'first_name' => (isset($_POST['first_name']) ? $_POST['first_name'] : '') . '|Ad-20',
                'last_name' => (isset($_POST['last_name']) ? $_POST['last_name'] : '') . '|Soyad-20',
                'tel' => (isset($_POST['tel']) ? $_POST['tel'] : '') . '|Telefon&Numarası-10$IsNumeric'
            );
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            if (empty($checked_inputs['input_error_name'])) {
                if (strlen($checked_inputs['input_datas']['tel']) != 10) {
                    $checked_inputs['input_datas']['email'] = isset($_POST['email']) ? $_POST['email'] : '';
                    $data['user'] = $checked_inputs['input_datas'];
                    $data['input_error_key'] = 'tel';
                    $this->notification_control->SetNotification('DANGER', 'Telefon Numarası Geçersiz');
                } else {
                    $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
                    $checked_inputs['tel'] = '90' . $checked_inputs['tel'];
                    $result = $this->UserModel->UpdateUser($checked_inputs);
                    $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', 'Yönetici Profili Başarıyla Güncellendi') : $this->notification_control->Danger('Yönetici Profilini Güncelleme Başarısız');
                    $user = $this->UserModel->GetUserById('*', $this->authenticated_user['id']);
                    if (!empty($user)) {
                        $user['email'] = $this->input_control->DecodeMail($user['email']);
                        $data['user'] = $user;
                        // $user_cookie = array(
                        //     'user_id' => $user['id'],
                        //     'user_first_name' => $user['first_name'],
                        //     'user_last_name' => $user['last_name'],
                        //     'user_profile_image' => $user['profile_image'],
                        //     'user_role' => $user['user_role']
                        // );
                        // $encrypted_data = $this->input_control->EncrypteData(json_encode($user_cookie), $this->key, 128);
                        // Cookie::SetCookie(0, '/', COOKIE_AUTHENTICATION_NAME, $encrypted_data);
                    } else {
                        header('Location: ' . URL);
                    }
                }
            } else {
                $checked_inputs['input_datas']['email'] = isset($_POST['email']) ? $_POST['email'] : '';
                $data['user'] = $checked_inputs['input_datas'];
                $data['input_error_key'] = $checked_inputs['input_error_key'];
                $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
            }
        } else {
            $user = $this->UserModel->GetUserById('*', $this->authenticated_user['id'], 0);
            if (!empty($user)) {
                $user['email'] = $this->input_control->DecodeMail($user['email']);
                $data['user'] = $user;
            } else {
                header('Location: ' . URL);
            }
        }
        parent::GetView('Admin/Profile', $data);
    }
    function PasswordChange()
    {
        $data = array();
        $data['selected_link'] = 'PasswordChange';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
            $posted_inputs = array(
                'current_password' => (isset($_POST['current_password']) ? $_POST['current_password'] : '') . '|Güncel&Şifreniz',
                'new_password' => (isset($_POST['new_password']) ? $_POST['new_password'] : '') . '|Yeni&Şifreniz',
                're_new_password' => (isset($_POST['re_new_password']) ? $_POST['re_new_password'] : '') . '|Yeni&Şifre&Tekrar'
            );
            $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
            if (empty($checked_inputs['input_error_name']) && strlen($checked_inputs['input_datas']['new_password']) > 11 && strlen($checked_inputs['input_datas']['new_password']) < 4000) {
                $bcrypt_options = $this->input_control->BcryptOptions();
                $new_peppered_pwd = $this->input_control->PepperPassword($checked_inputs['input_datas']['new_password']);
                $new_peppered_repwd = $this->input_control->PepperPassword($checked_inputs['input_datas']['re_new_password']);
                $new_hashed_pwd = password_hash($new_peppered_pwd, PASSWORD_BCRYPT, $bcrypt_options);
                $verified_pwd = $this->input_control->VerifyPassword($new_peppered_repwd, $new_hashed_pwd);
                if ($verified_pwd) {
                    $current_peppered_pwd = $this->input_control->PepperPassword($checked_inputs['input_datas']['current_password']);
                    $user_password = $this->UserModel->GetUserPassword($this->authenticated_user['id']);
                    $verified_pwd2 = $this->input_control->VerifyPassword($current_peppered_pwd, $user_password);
                    if ($verified_pwd2) {
                        $current_hashed_pwd = password_hash($current_peppered_pwd, PASSWORD_BCRYPT, $bcrypt_options);
                        $result = $this->UserModel->UpdateUser(array('user_password' => $current_hashed_pwd, 'id' => $this->authenticated_user['id']));
                        $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', 'Yönetici Şifresi Başarıyla Güncellendi') : $this->notification_control->Danger('Yönetici Şifresi Güncelleme Başarısız');
                    } else {
                        $data['user'] = $checked_inputs;
                        $data['input_error_key'] = 'wrong_current_password';
                        $this->notification_control->SetNotification('DANGER', 'Güncel Şifre Yanlış');
                    }
                } else {
                    $data['user'] = $checked_inputs['input_datas'];
                    $data['input_error_key'] = 'password_not_match';
                    $this->notification_control->SetNotification('DANGER', 'Şifreler Uyuşmuyor');
                }
            } else {
                $data['user'] = $checked_inputs['input_datas'];
                if ($checked_inputs['input_error_key'] == 'current_password') {
                    $this->notification_control->SetNotification('DANGER', 'Güncel Şifrenizi Girin');
                } elseif ($checked_inputs['input_error_key'] == 'new_password') {
                    $this->notification_control->SetNotification('DANGER', 'Şifrenizi Girin');
                } elseif (strlen($checked_inputs['input_datas']['new_password']) < 12) {
                    $checked_inputs['input_error_key'] = 'new_password';
                    $this->notification_control->SetNotification('DANGER', 'Şifre En Az 12 Karakterden Oluşmalıdır');
                } elseif ($checked_inputs['input_error_key'] == 're_new_password') {
                    $this->notification_control->SetNotification('DANGER', 'Şifrenizi Tekrardan Girin');
                }
                $data['input_error_key'] = $checked_inputs['input_error_key'];
            }
        }
        parent::GetView('Admin/PasswordChange', $data);
    }
    function ProfilePhotoChange()
    {
        $data = array();
        $data['selected_link'] = 'ProfilePhotoChange';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_photo'])) {
            $success = false;
            if (isset($_FILES['user_image'])) {
                $user_image = $_FILES['user_image'];
                if ($user_image['error'] == 0) {
                    if ($user_image['size'] <= (1024 * 1024 * 20)) {
                        $accepted_image_types = array('image/png', 'image/jpeg');
                        if (in_array($user_image['type'], $accepted_image_types)) {
                            $user_name = explode(".", $user_image['name']);
                            $new_image_folder = USER_IMAGES_PATH . $this->authenticated_user['id'];
                            $filename = $user_image['tmp_name'];
                            if (!is_dir($new_image_folder)) {
                                mkdir($new_image_folder, 0777, true);
                            }
                            $image_random_name = strtolower(substr(strtr(base64_encode(hash_hmac('SHA512', time(), base64_encode(random_bytes(128)), true)), array('+' => 't', '=' => 's', '/' => '9', '.' => '2', '_' => 'g')), 21, 30));
                            $width = 100;
                            $height = 100;
                            $dst_image = imagecreatetruecolor($width, $height);
                            $image_infos = getimagesize($filename);
                            $image_width = $image_infos[0];
                            $image_height = $image_infos[1];
                            $uploadImageType = $image_infos[2];
                            if ($uploadImageType == 2) {
                                $src_image = imagecreatefromjpeg($filename);
                                imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
                                imagejpeg($dst_image, $new_image_folder . '/' . $image_random_name . '.' . $user_name[count($user_name) - 1], 100);
                            } elseif ($uploadImageType == 3) {
                                $src_image = imagecreatefrompng($filename);
                                imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
                                imagepng($dst_image, $new_image_folder . '/' . $image_random_name . '.' . $user_name[count($user_name) - 1], 9);
                            }
                            imagedestroy($src_image);
                            $user_images_db = $image_random_name . '.' . $user_name[count($user_name) - 1] . '-';
                            $success = true;
                        } else {
                            $data['image_error_message'] = 'Fotoğrafın Uzantısı Desteklenmiyor (Desteklenen Uzantılar: jpeg, png)';
                        }
                    } else {
                        $data['image_error_message'] = 'Fotoğrafın Boyutu 20mb dan Fazla Olamaz';
                    }
                } else {
                    $data['image_error_message'] = 'Profil Fotoğrafını Yükleyin';
                }
            } else {
                $data['image_error_message'] = 'Profil Fotoğrafını Yükleyin';
            }
            if ($success) {
                $checked_inputs = array();
                $checked_inputs['profile_image'] = rtrim($user_images_db, '-');
                $checked_inputs['id'] = $this->authenticated_user['id'];
                $data_redirect = array();
                $data_redirect['selected_link'] = 'ProfilePhotoChange';
                $result = $this->UserModel->UpdateUser($checked_inputs);
                $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', 'Profil Fotoğrafı Başarıyla Güncellendi') : $this->notification_control->Danger('Profil Fotoğrafı Güncelleme Başarısız');
                $user = $this->UserModel->GetUserById('*', $this->authenticated_user['id'], 0);
                if (!empty($user)) {
                    // $user_cookie = array(
                    //     'user_id' => $user['id'],
                    //     'user_first_name' => $user['first_name'],
                    //     'user_last_name' => $user['last_name'],
                    //     'user_profile_image' => $user['profile_image'],
                    //     'user_role' => $user['user_role']
                    // );
                    // $encrypted_data = $this->input_control->EncrypteData(json_encode($user_cookie), $this->key, 128);
                    // Cookie::SetCookie(0, '/', COOKIE_AUTHENTICATION_NAME, $encrypted_data);
                    parent::GetView('Admin/ProfilePhotoChange', $data_redirect);
                } else {
                    header('Location: ' . URL);
                }
            } else {
                $data['input_error_key'] = 'user_image_problem';
                parent::GetView('Admin/ProfilePhotoChange', $data);
            }
        } else {
            parent::GetView('Admin/ProfilePhotoChange', $data);
        }
    }
    function Settings()
    {
        parent::GetView('Admin/Settings');
    }
}
