<?php
class AdminController extends ControllerAdmin
{
    function __construct()
    {
        parent::__construct();
        if ($_SERVER['REMOTE_ADDR'] == ADMIN_IP_ADDRESS) {
            if (!empty($_SESSION[SESSION_AUTHENTICATION_NAME])) {
                $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
                $this->input_control->Redirect();
            }
            if (!empty($_COOKIE[COOKIE_AUTHENTICATION_NAME])) {
                $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
                $this->input_control->Redirect();
            }
            $session_authentication_error = true;
            if (!empty($_SESSION[SESSION_ADMIN_AUTHENTICATION_NAME])) {
                $session_authentication_from_database = $this->ActionModel->GetSessionAuthentication(array($_SERVER['REMOTE_ADDR'], $_SESSION[SESSION_ADMIN_AUTHENTICATION_NAME]));
                if ($session_authentication_from_database['result'] && $session_authentication_from_database['data']['date_session_authentication_expiry'] > date('Y-m-d H:i:s') && $session_authentication_from_database['data']['is_session_authentication_logout'] == 0) {
                    $authenticated_user_from_database = $this->UserModel->GetAdminByAdminId('id,user_role', $session_authentication_from_database['data']['user_id']);
                    if ($authenticated_user_from_database['result'] && $authenticated_user_from_database['data']['user_role'] == ADMIN_ROLE_ID && $authenticated_user_from_database['data']['id'] == ADMIN_MAIN_ID) {
                        $session_authentication_error = false;
                        $this->web_data['authenticated_user'] = $authenticated_user_from_database['data']['id'];
                        $this->web_data['session_authentication_id'] = $session_authentication_from_database['data']['id'];
                    }
                    if ($session_authentication_error && $this->ActionModel->UpdateSessionAuthentication(array('is_session_authentication_killed' => 1, 'date_session_authentication_killed' => date('Y-m-d H:i:s'), 'session_authentication_killed_function' => 'AdminController __construct', 'id' => $session_authentication_from_database['data']['id']))['result']) {
                        $this->session_control->KillSession(SESSION_ADMIN_AUTHENTICATION_NAME);
                        $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
                        $this->input_control->Redirect(URL_LOGIN);
                    }
                }
                if ($session_authentication_error) {
                    $this->session_control->KillSession(SESSION_ADMIN_AUTHENTICATION_NAME);
                    $this->input_control->Redirect(URL_LOGIN);
                }
            }
            if ($session_authentication_error) {
                $this->input_control->Redirect();
            }
        } else {
            $this->input_control->Redirect();
        }
    }
    function LogOut()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($this->web_data['session_authentication_id'])) {
                    $this->ActionModel->UpdateSessionAuthentication(array('is_session_authentication_logout' => 1, 'date_session_authentication_logout' => date('Y-m-d H:i:s'), 'id' => $this->web_data['session_authentication_id']));
                    $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
                    $_SESSION[SESSION_OBSOLETE_NAME] = time() + (60 * 5);
                    if (session_regenerate_id()) {
                        $this->session_control->KillSession(SESSION_OBSOLETE_NAME);
                        $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                        $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_LOG_OUT);
                        $this->input_control->Redirect();
                    } else {
                        $this->input_control->Redirect(URL_EXCEPTION);
                    }
                }
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function LogOut | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Menu()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->web_data['admin_menu'] == 1) {
                $_SESSION[SESSION_ADMIN_MENU_NAME] = false;
            } else {
                $_SESSION[SESSION_ADMIN_MENU_NAME] = true;
            }
        }
    }
    function Index()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::GetView('Admin/Index', $this->web_data);
            }
            $this->input_control->Redirect(URL_EXCEPTION);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function Index | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Items()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi', 'isim-azalan', 'isim-artan', 'fiyat-azalan', 'fiyat-artan', 'indirimli-fiyat-azalan', 'indirimli-fiyat-artan', 'adet-azalan', 'adet-artan'));
                $items_count_from_database = $this->AdminModel->GetItemsCount();
                if ($items_count_from_database['result']) {
                    $this->web_data['item_total_count'] = $items_count_from_database['data']['COUNT(id)'];
                }
                $item_conditions = 'WHERE is_item_deleted=0';
                $item_bind_params = array();
                $url_for_selected_filters_gender = '';
                $url_for_selected_filters_home = '';
                $url_for_selected_filters_sale = '';
                $url_for_selected_filters_date = '';
                $url_for_selected_filters_search = '';
                $url_for_selected_filters_name_desc = '';
                $url_for_selected_filters_name_asc = '';
                $url_for_selected_filters_price_desc = '';
                $url_for_selected_filters_price_asc = '';
                $url_for_selected_filters_discount_price_desc = '';
                $url_for_selected_filters_discount_price_asc = '';
                $url_for_selected_filters_quantity_desc = '';
                $url_for_selected_filters_quantity_asc = '';
                $url_for_selected_filters_limit = '';

                if (!empty($_GET['cinsiyet'])) {
                    $genders_from_database = $this->FilterModel->GetGenders('id,gender_url');
                    if ($genders_from_database['result']) {
                        $gender_from_get_form = $this->input_control->CheckGETInput($_GET['cinsiyet']);
                        $gender_get_matched_error = true;
                        foreach ($genders_from_database['data'] as $gender_from_database) {
                            if (!empty($gender_from_get_form) && $gender_from_get_form == $gender_from_database['gender_url']) {
                                $gender_get_matched_error = false;
                                $this->web_data['selected_gender'] = $gender_from_database['gender_url'];
                                $url_for_selected_filters_gender = 'cinsiyet=' . $gender_from_database['gender_url'] . '&';
                                $item_conditions .= ' AND gender=?';
                                $item_bind_params[] = $gender_from_database['id'];
                            }
                        }
                        if ($gender_get_matched_error) {
                            $this->input_control->CheckUrl(array('limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi', 'isim-azalan', 'isim-artan', 'fiyat-azalan', 'fiyat-artan', 'indirimli-fiyat-azalan', 'indirimli-fiyat-artan', 'adet-azalan', 'adet-artan'));
                        }
                    }
                }
                if (isset($_GET['anasayfada'])) {
                    $home_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['anasayfada']);
                    if (isset($home_from_get_form) && ($home_from_get_form == 0 || $home_from_get_form == 1)) {
                        $this->web_data['selected_home'] = $home_from_get_form;
                        $url_for_selected_filters_home = 'anasayfada=' . $home_from_get_form . '&';
                        $item_conditions .= ' AND is_item_home=?';
                        $item_bind_params[] = $home_from_get_form;
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'satista', 'eklenme-tarihi', 'isim-azalan', 'isim-artan', 'fiyat-azalan', 'fiyat-artan', 'indirimli-fiyat-azalan', 'indirimli-fiyat-artan', 'adet-azalan', 'adet-artan'));
                    }
                }
                if (isset($_GET['satista'])) {
                    $sale_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['satista']);
                    if (isset($sale_from_get_form) && ($sale_from_get_form == 0 || $sale_from_get_form == 1)) {
                        $this->web_data['selected_sale'] = $sale_from_get_form;
                        $url_for_selected_filters_sale = 'satista=' . $sale_from_get_form . '&';
                        $item_conditions .= ' AND is_item_for_sale=?';
                        $item_bind_params[] = $sale_from_get_form;
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'eklenme-tarihi', 'isim-azalan', 'isim-artan', 'fiyat-azalan', 'fiyat-artan', 'indirimli-fiyat-azalan', 'indirimli-fiyat-artan', 'adet-azalan', 'adet-artan'));
                    }
                }
                if (isset($_GET['ara'])) {
                    $search_from_get_form = $this->input_control->CheckGETInputWithMaxLength($_GET['ara'], 250);
                    if (isset($search_from_get_form)) {
                        $this->web_data['selected_search'] = $search_from_get_form;
                        $url_for_selected_filters_search = 'ara=' . $search_from_get_form . '&';
                        $item_conditions .= ' AND (id LIKE ? OR item_name LIKE ? OR item_url LIKE ? OR item_price LIKE ? OR item_discount_price LIKE ? OR item_total_quantity LIKE ?)';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi', 'isim-azalan', 'isim-artan', 'fiyat-azalan', 'fiyat-artan', 'indirimli-fiyat-azalan', 'indirimli-fiyat-artan', 'adet-azalan', 'adet-artan'));
                    }
                }
                $date_get_matched_error = true;
                if (isset($_GET['eklenme-tarihi'])) {
                    $date_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['eklenme-tarihi']);
                    if (isset($date_from_get_form) && $date_from_get_form == 1 && $date_get_matched_error) {
                        $date_get_matched_error = false;
                        $this->web_data['selected_date'] = $date_from_get_form;
                        $url_for_selected_filters_date = 'eklenme-tarihi=' . $date_from_get_form . '&';
                        $item_conditions .= ' ORDER BY date_item_created ASC';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'isim-azalan', 'isim-artan', 'fiyat-azalan', 'fiyat-artan', 'indirimli-fiyat-azalan', 'indirimli-fiyat-artan', 'adet-azalan', 'adet-artan'));
                    }
                }
                if (isset($_GET['isim-azalan'])) {
                    $date_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['isim-azalan']);
                    if (isset($date_from_get_form) && $date_from_get_form == 1 && $date_get_matched_error) {
                        $date_get_matched_error = false;
                        $this->web_data['selected_name_desc'] = $date_from_get_form;
                        $url_for_selected_filters_name_desc = 'isim-azalan=' . $date_from_get_form . '&';
                        $item_conditions .= ' ORDER BY item_name DESC';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi'));
                    }
                }
                if (isset($_GET['isim-artan'])) {
                    $date_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['isim-artan']);
                    if (isset($date_from_get_form) && $date_from_get_form == 1 && $date_get_matched_error) {
                        $date_get_matched_error = false;
                        $this->web_data['selected_name_asc'] = $date_from_get_form;
                        $url_for_selected_filters_name_asc = 'isim-artan=' . $date_from_get_form . '&';
                        $item_conditions .= ' ORDER BY item_name ASC';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi'));
                    }
                }
                if (isset($_GET['fiyat-azalan'])) {
                    $date_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['fiyat-azalan']);
                    if (isset($date_from_get_form) && $date_from_get_form == 1 && $date_get_matched_error) {
                        $date_get_matched_error = false;
                        $this->web_data['selected_price_desc'] = $date_from_get_form;
                        $url_for_selected_filters_price_desc = 'fiyat-azalan=' . $date_from_get_form . '&';
                        $item_conditions .= ' ORDER BY item_price DESC';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi'));
                    }
                }
                if (isset($_GET['fiyat-artan'])) {
                    $date_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['fiyat-artan']);
                    if (isset($date_from_get_form) && $date_from_get_form == 1 && $date_get_matched_error) {
                        $date_get_matched_error = false;
                        $this->web_data['selected_price_asc'] = $date_from_get_form;
                        $url_for_selected_filters_price_asc = 'fiyat-artan=' . $date_from_get_form . '&';
                        $item_conditions .= ' ORDER BY item_price ASC';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi'));
                    }
                }
                if (isset($_GET['indirimli-fiyat-azalan'])) {
                    $date_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['indirimli-fiyat-azalan']);
                    if (isset($date_from_get_form) && $date_from_get_form == 1 && $date_get_matched_error) {
                        $date_get_matched_error = false;
                        $this->web_data['selected_discount_price_desc'] = $date_from_get_form;
                        $url_for_selected_filters_discount_price_desc = 'indirimli-fiyat-azalan=' . $date_from_get_form . '&';
                        $item_conditions .= ' ORDER BY item_discount_price DESC';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi'));
                    }
                }
                if (isset($_GET['indirimli-fiyat-artan'])) {
                    $date_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['indirimli-fiyat-artan']);
                    if (isset($date_from_get_form) && $date_from_get_form == 1 && $date_get_matched_error) {
                        $date_get_matched_error = false;
                        $this->web_data['selected_discount_price_asc'] = $date_from_get_form;
                        $url_for_selected_filters_discount_price_asc = 'indirimli-fiyat-artan=' . $date_from_get_form . '&';
                        $item_conditions .= ' ORDER BY item_discount_price ASC';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi'));
                    }
                }
                if (isset($_GET['adet-azalan'])) {
                    $date_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['adet-azalan']);
                    if (isset($date_from_get_form) && $date_from_get_form == 1 && $date_get_matched_error) {
                        $date_get_matched_error = false;
                        $this->web_data['selected_quantity_desc'] = $date_from_get_form;
                        $url_for_selected_filters_quantity_desc = 'adet-azalan=' . $date_from_get_form . '&';
                        $item_conditions .= ' ORDER BY item_total_quantity DESC';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi'));
                    }
                }
                if (isset($_GET['adet-artan'])) {
                    $date_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['adet-artan']);
                    if (isset($date_from_get_form) && $date_from_get_form == 1 && $date_get_matched_error) {
                        $date_get_matched_error = false;
                        $this->web_data['selected_quantity_asc'] = $date_from_get_form;
                        $url_for_selected_filters_quantity_asc = 'adet-artan=' . $date_from_get_form . '&';
                        $item_conditions .= ' ORDER BY item_total_quantity ASC';
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'limit', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi'));
                    }
                }
                if ($date_get_matched_error) {
                    $item_conditions .= ' ORDER BY date_item_created DESC';
                }
                $item_conditions .= ' LIMIT ?';
                $limit_not_used = true;
                if (isset($_GET['limit'])) {
                    $limit_from_get_form = $this->input_control->CheckPositiveNonZeroGETInput($_GET['limit']);
                    if (!empty($limit_from_get_form)) {
                        $limit_not_used = false;
                        $this->web_data['selected_limit'] = $limit_from_get_form;
                        $url_for_selected_filters_limit = 'limit=' . $limit_from_get_form . '&';
                        $item_bind_params[] = $limit_from_get_form;
                    }
                }
                if ($limit_not_used) {
                    $this->web_data['selected_limit'] = 20;
                    $item_bind_params[] = 20;
                }
                $this->web_data['url_gender'] = rtrim($url_for_selected_filters_home . $url_for_selected_filters_sale . $url_for_selected_filters_date . $url_for_selected_filters_search . $url_for_selected_filters_limit . $url_for_selected_filters_name_desc . $url_for_selected_filters_name_asc . $url_for_selected_filters_price_desc . $url_for_selected_filters_price_asc . $url_for_selected_filters_discount_price_desc . $url_for_selected_filters_discount_price_asc . $url_for_selected_filters_quantity_desc . $url_for_selected_filters_quantity_asc, '&');
                $this->web_data['url_home'] = rtrim($url_for_selected_filters_gender . $url_for_selected_filters_sale . $url_for_selected_filters_date . $url_for_selected_filters_search . $url_for_selected_filters_limit . $url_for_selected_filters_name_desc . $url_for_selected_filters_name_asc . $url_for_selected_filters_price_desc . $url_for_selected_filters_price_asc . $url_for_selected_filters_discount_price_desc . $url_for_selected_filters_discount_price_asc . $url_for_selected_filters_quantity_desc . $url_for_selected_filters_quantity_asc, '&');
                $this->web_data['url_sale'] = rtrim($url_for_selected_filters_home . $url_for_selected_filters_gender . $url_for_selected_filters_date . $url_for_selected_filters_search . $url_for_selected_filters_limit . $url_for_selected_filters_name_desc . $url_for_selected_filters_name_asc . $url_for_selected_filters_price_desc . $url_for_selected_filters_price_asc . $url_for_selected_filters_discount_price_desc . $url_for_selected_filters_discount_price_asc . $url_for_selected_filters_quantity_desc . $url_for_selected_filters_quantity_asc, '&');
                $this->web_data['url_date'] = rtrim($url_for_selected_filters_home . $url_for_selected_filters_sale . $url_for_selected_filters_gender . $url_for_selected_filters_search . $url_for_selected_filters_limit . $url_for_selected_filters_name_desc . $url_for_selected_filters_name_asc . $url_for_selected_filters_price_desc . $url_for_selected_filters_price_asc . $url_for_selected_filters_discount_price_desc . $url_for_selected_filters_discount_price_asc . $url_for_selected_filters_quantity_desc . $url_for_selected_filters_quantity_asc, '&');
                $this->web_data['url_search'] = rtrim($url_for_selected_filters_home . $url_for_selected_filters_sale . $url_for_selected_filters_date . $url_for_selected_filters_gender . $url_for_selected_filters_limit . $url_for_selected_filters_name_desc . $url_for_selected_filters_name_asc . $url_for_selected_filters_price_desc . $url_for_selected_filters_price_asc . $url_for_selected_filters_discount_price_desc . $url_for_selected_filters_discount_price_asc . $url_for_selected_filters_quantity_desc . $url_for_selected_filters_quantity_asc, '&');
                $this->web_data['url_limit'] = rtrim($url_for_selected_filters_home . $url_for_selected_filters_sale . $url_for_selected_filters_date . $url_for_selected_filters_search . $url_for_selected_filters_gender . $url_for_selected_filters_name_desc . $url_for_selected_filters_name_asc . $url_for_selected_filters_price_desc . $url_for_selected_filters_price_asc . $url_for_selected_filters_discount_price_desc . $url_for_selected_filters_discount_price_asc . $url_for_selected_filters_quantity_desc . $url_for_selected_filters_quantity_asc, '&');
                $this->web_data['url_sort'] = rtrim($url_for_selected_filters_home . $url_for_selected_filters_sale . $url_for_selected_filters_date . $url_for_selected_filters_search . $url_for_selected_filters_limit . $url_for_selected_filters_gender, '&');
                $items_from_database = $this->AdminModel->GetItems($item_conditions, $item_bind_params);
                if ($items_from_database['result']) {
                    $formatted_items_from_database = $this->input_control->GetItemsMainImageAndFormatedPrice($items_from_database['data']);
                    if ($formatted_items_from_database['result']) {
                        $this->web_data['items'] = $formatted_items_from_database['data'];
                        parent::GetView('Admin/Items', $this->web_data);
                    }
                } elseif ($items_from_database['empty']) {
                    parent::GetView('Admin/Items', $this->web_data);
                }
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function Items | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ItemDetails(string $item_url)
    {
        try {
            $sizes_from_database = $this->AdminModel->GetSizes();
            $genders_from_database = $this->AdminModel->GetGenders();
            $categories_from_database = $this->AdminModel->GetCategories();
            $colors_from_database = $this->AdminModel->GetColors();
            if ($sizes_from_database['result'] && $genders_from_database['result'] && $categories_from_database['result'] && $colors_from_database['result']) {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $this->input_control->CheckUrl();
                    $item_details_from_database = $this->AdminModel->GetItemDetails($item_url);
                    if ($item_details_from_database['result']) {
                        $this->web_data['item'] = $this->input_control->GetItemImages($item_details_from_database['data']);
                    }
                    $this->web_data['sizes'] = $sizes_from_database['data'];
                    $this->web_data['genders'] = $genders_from_database['data'];
                    $this->web_data['categories'] = $categories_from_database['data'];
                    $this->web_data['colors'] = $colors_from_database['data'];
                    if (!empty($_SESSION[SESSION_WEB_DATA_NAME])) {
                        if (isset($_SESSION[SESSION_WEB_DATA_NAME]['item_name']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_keywords']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_description']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_price']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_discount_price']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_collection'])  && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_material']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_cut_model']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_thickness']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_pattern']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_lapel']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_type']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_length']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_length']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_washing_style']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_model_size']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_model_height']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_model_weight']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_total_quantity']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['gender']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['category']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['sizes']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['colors']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['is_item_for_sale']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['is_item_home'])) {
                            $this->web_data['item_name'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_name'];
                            $this->web_data['item_keywords'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_keywords'];
                            $this->web_data['item_description'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_description'];
                            $this->web_data['item_price'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_price'];
                            $this->web_data['item_discount_price'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_discount_price'];
                            $this->web_data['item_collection'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_collection'];
                            $this->web_data['item_material'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_material'];
                            $this->web_data['item_cut_model'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_cut_model'];
                            $this->web_data['item_thickness'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_thickness'];
                            $this->web_data['item_pattern'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_pattern'];
                            $this->web_data['item_lapel'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_lapel'];
                            $this->web_data['item_sleeve_type'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_type'];
                            $this->web_data['item_sleeve_length'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_length'];
                            $this->web_data['item_washing_style'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_washing_style'];
                            $this->web_data['item_model_size'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_model_size'];
                            $this->web_data['item_model_height'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_model_height'];
                            $this->web_data['item_model_weight'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_model_weight'];
                            $this->web_data['item_total_quantity'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_total_quantity'];
                            $this->web_data['posted_gender'] = $_SESSION[SESSION_WEB_DATA_NAME]['gender'];
                            $this->web_data['posted_category'] = $_SESSION[SESSION_WEB_DATA_NAME]['category'];
                            $this->web_data['posted_sizes'] = $_SESSION[SESSION_WEB_DATA_NAME]['sizes'];
                            $this->web_data['posted_colors'] = $_SESSION[SESSION_WEB_DATA_NAME]['colors'];
                            $this->web_data['is_item_for_sale'] = $_SESSION[SESSION_WEB_DATA_NAME]['is_item_for_sale'];
                            $this->web_data['is_item_home'] = $_SESSION[SESSION_WEB_DATA_NAME]['is_item_home'];
                        }
                        $this->session_control->KillSession(SESSION_WEB_DATA_NAME);
                    }
                    $this->web_data['form_token'] = parent::SetCSRFToken('AdminItemUpdate');
                    parent::GetView('Admin/ItemDetails', $this->web_data);
                }
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function ItemDetails | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ItemCreate()
    {
        try {
            $sizes_from_database = $this->AdminModel->GetSizes();
            $genders_from_database = $this->AdminModel->GetGenders();
            $categories_from_database = $this->AdminModel->GetCategories();
            $colors_from_database = $this->AdminModel->GetColors();
            if ($sizes_from_database['result'] && $genders_from_database['result'] && $categories_from_database['result'] && $colors_from_database['result']) {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $this->input_control->CheckUrl();
                    $this->web_data['sizes'] = $sizes_from_database['data'];
                    $this->web_data['genders'] = $genders_from_database['data'];
                    $this->web_data['categories'] = $categories_from_database['data'];
                    $this->web_data['colors'] = $colors_from_database['data'];
                    $this->web_data['form_token'] = parent::SetCSRFToken('AdminItemCreate');
                    if (!empty($_SESSION[SESSION_WEB_DATA_NAME])) {
                        if (isset($_SESSION[SESSION_WEB_DATA_NAME]['item_name']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_keywords']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_description']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_price']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_discount_price']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_collection'])  && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_material']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_cut_model']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_thickness']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_pattern']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_lapel']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_type']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_length']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_length']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_washing_style']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_model_size']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_model_height']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_model_weight']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['item_total_quantity']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['gender']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['category']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['sizes']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['colors']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['is_item_for_sale']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['is_item_home'])) {
                            $this->web_data['item_name'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_name'];
                            $this->web_data['item_keywords'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_keywords'];
                            $this->web_data['item_description'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_description'];
                            $this->web_data['item_price'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_price'];
                            $this->web_data['item_discount_price'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_discount_price'];
                            $this->web_data['item_collection'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_collection'];
                            $this->web_data['item_material'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_material'];
                            $this->web_data['item_cut_model'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_cut_model'];
                            $this->web_data['item_thickness'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_thickness'];
                            $this->web_data['item_pattern'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_pattern'];
                            $this->web_data['item_lapel'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_lapel'];
                            $this->web_data['item_sleeve_type'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_type'];
                            $this->web_data['item_sleeve_length'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_sleeve_length'];
                            $this->web_data['item_washing_style'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_washing_style'];
                            $this->web_data['item_model_size'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_model_size'];
                            $this->web_data['item_model_height'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_model_height'];
                            $this->web_data['item_model_weight'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_model_weight'];
                            $this->web_data['item_total_quantity'] = $_SESSION[SESSION_WEB_DATA_NAME]['item_total_quantity'];
                            $this->web_data['posted_gender'] = $_SESSION[SESSION_WEB_DATA_NAME]['gender'];
                            $this->web_data['posted_category'] = $_SESSION[SESSION_WEB_DATA_NAME]['category'];
                            $this->web_data['posted_sizes'] = $_SESSION[SESSION_WEB_DATA_NAME]['sizes'];
                            $this->web_data['posted_colors'] = $_SESSION[SESSION_WEB_DATA_NAME]['colors'];
                            $this->web_data['is_item_for_sale'] = $_SESSION[SESSION_WEB_DATA_NAME]['is_item_for_sale'];
                            $this->web_data['is_item_home'] = $_SESSION[SESSION_WEB_DATA_NAME]['is_item_home'];
                        }
                        $this->session_control->KillSession(SESSION_WEB_DATA_NAME);
                    }
                    parent::GetView('Admin/ItemCreate', $this->web_data);
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
                    $item_keywords = isset($_POST['item_keywords']) ? $_POST['item_keywords'] : '';
                    $item_description = isset($_POST['item_description']) ? $_POST['item_description'] : '';
                    $item_price = isset($_POST['item_price']) ? $_POST['item_price'] : '';
                    $item_discount_price = isset($_POST['item_discount_price']) ? $_POST['item_discount_price'] : '';
                    $item_collection = isset($_POST['item_collection']) ? $_POST['item_collection'] : '';
                    $item_material = isset($_POST['item_material']) ? $_POST['item_material'] : '';
                    $item_cut_model = isset($_POST['item_cut_model']) ? $_POST['item_cut_model'] : '';
                    $item_thickness = isset($_POST['item_thickness']) ? $_POST['item_thickness'] : '';
                    $item_pattern = isset($_POST['item_pattern']) ? $_POST['item_pattern'] : '';
                    $item_lapel = isset($_POST['item_lapel']) ? $_POST['item_lapel'] : '';
                    $item_sleeve_type = isset($_POST['item_sleeve_type']) ? $_POST['item_sleeve_type'] : '';
                    $item_sleeve_length = isset($_POST['item_sleeve_length']) ? $_POST['item_sleeve_length'] : '';
                    $item_washing_style = isset($_POST['item_washing_style']) ? $_POST['item_washing_style'] : '';
                    $item_model_size = isset($_POST['item_model_size']) ? $_POST['item_model_size'] : '';
                    $item_model_height = isset($_POST['item_model_height']) ? $_POST['item_model_height'] : '';
                    $item_model_weight = isset($_POST['item_model_weight']) ? $_POST['item_model_weight'] : '';
                    $item_total_quantity = isset($_POST['item_total_quantity']) ? $_POST['item_total_quantity'] : '';
                    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                    $category = isset($_POST['category']) ? $_POST['category'] : '';
                    $is_item_for_sale = isset($_POST['is_item_for_sale']) ? 1 : 0;
                    $is_item_home = isset($_POST['is_item_home']) ? 1 : 0;
                    $check_posted_inputs = array(
                        'item_name' => array('input' => $item_name, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_NAME, 'length_control' => true, 'max_length' => 45, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_NAME_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 45, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_NAME_MAX_LENGTH),
                        'item_keywords' => array('input' => $item_keywords, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_KEYWORDS, 'length_control' => true, 'max_length' => 100, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_KEYWORDS_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 100, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_KEYWORDS_MAX_LENGTH),
                        'item_description' => array('input' => $item_description, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_DESCRIPTION, 'length_control' => true, 'max_length' => 160, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_DESCRIPTION_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 200, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_DESCRIPTION_MAX_LENGTH),
                        'item_price' => array('input' => $item_price, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_PRICE, 'preventxss' => true, 'is_float_and_positive' => true, 'error_message_is_float_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_PRICE_NOT_POSITIVE),
                        'item_discount_price' => array('input' => $item_discount_price, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_DISCOUNT_PRICE, 'preventxss' => true, 'is_float_and_positive' => true, 'error_message_is_float_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_DISCOUNT_PRICE_NOT_POSITIVE),
                        'item_collection' => array('input' => $item_collection, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_COLLECTION, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_COLLECTION_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_COLLECTION_MAX_LENGTH),
                        'item_material' => array('input' => $item_material, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MATERIAL, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MATERIAL_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MATERIAL_MAX_LENGTH),
                        'item_cut_model' => array('input' => $item_cut_model, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_CUTMODEL, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_CUTMODEL_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_CUTMODEL_MAX_LENGTH),
                        'item_thickness' => array('input' => $item_thickness, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_THICKNESS, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_THICKNESS_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_THICKNESS_MAX_LENGTH),
                        'item_pattern' => array('input' => $item_pattern, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_PATTERN, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_PATTERN_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_PATTERN_MAX_LENGTH),
                        'item_lapel' => array('input' => $item_lapel, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_LAPEL, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_LAPEL_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_LAPEL_MAX_LENGTH),
                        'item_sleeve_type' => array('input' => $item_sleeve_type, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_SLEEVE_TYPE, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_TYPE_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_TYPE_MAX_LENGTH),
                        'item_sleeve_length' => array('input' => $item_sleeve_length, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_SLEEVE_LENGTH, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_LENGTH_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_LENGTH_MAX_LENGTH),
                        'item_washing_style' => array('input' => $item_washing_style, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_WASHING_STYLE, 'length_control' => true, 'max_length' => 200, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_WASHING_STYLE_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 200, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_WASHING_STYLE_MAX_LENGTH),
                        'item_model_size' => array('input' => $item_model_size, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MODEL_SIZE, 'length_control' => true, 'max_length' => 3, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_SIZE_NOT_VALID, 'preventxss' => true, 'length_limit' => 3, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_SIZE_NOT_VALID),
                        'item_model_height' => array('input' => $item_model_height, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MODEL_HEIGHT, 'length_control' => true, 'max_length' => 3, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_HEIGHT_NOT_VALID, 'preventxss' => true, 'length_limit' => 3, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_HEIGHT_NOT_VALID, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_HEIGHT_NOT_VALID),
                        'item_model_weight' => array('input' => $item_model_weight, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MODEL_WEIGHT, 'length_control' => true, 'max_length' => 3, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_WEIGHT_NOT_VALID, 'preventxss' => true, 'length_limit' => 3, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_WEIGHT_NOT_VALID, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_WEIGHT_NOT_VALID),
                        'item_total_quantity' => array('input' => $item_total_quantity, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_TOTAL_QUANTITY, 'length_control' => true, 'max_length' => 8, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_TOTAL_QUANTITY_NOT_VALID, 'preventxss' => true, 'length_limit' => 8, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_TOTAL_QUANTITY_NOT_VALID, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_TOTAL_QUANTITY_NOT_VALID),
                        'gender' => array('input' => $gender, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_GENDER, 'length_control' => true, 'max_length' => 250, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_GENDER_MAX_LENGTH, 'preventxssforid' => true, 'length_limit' => 250, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_GENDER_MAX_LENGTH),
                        'category' => array('input' => $category, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_CATEGORY, 'length_control' => true, 'max_length' => 250, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_CATEGORY_MAX_LENGTH, 'preventxssforid' => true, 'length_limit' => 250, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_CATEGORY_MAX_LENGTH),
                        'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                    );
                    $posted_sizes = array();
                    foreach ($sizes_from_database['data'] as $size) {
                        $posted_sizes[$size['size_url']] = isset($_POST[$size['size_url']]) ? $_POST[$size['size_url']] : '';
                        $check_posted_inputs[$size['size_url']] = array('input' => isset($_POST[$size['size_url']]) ? $_POST[$size['size_url']] : '', 'numeric_is_string' => true, 'length_control' => true, 'max_length' => 8, 'error_message_max_length' => 'Ürün ' . $size['size_name'] . ' adedi geçerli değil', 'preventxss' => true, 'length_limit' => 8, 'error_message_length_limit' => 'Ürün ' . $size['size_name'] . ' adedi geçerli değil', 'is_integer_or_zero' => true, 'error_message_is_integer_or_zero' => 'Ürün ' . $size['size_name'] . ' adedi geçerli değil');
                    }
                    $posted_colors = array();
                    foreach ($colors_from_database['data'] as $color) {
                        $posted_colors[$color['color_url']] = isset($_POST[$color['color_url']]) ? 1 : 0;
                    }
                    $checked_inputs = $this->input_control->CheckPostedInputs($check_posted_inputs);
                    if (empty($checked_inputs['error_message'])) {
                        if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminItemCreate')) {
                            $size_quantity = 0;
                            foreach ($sizes_from_database['data'] as $size) {
                                $size_quantity += $checked_inputs[$size['size_url']];
                            }
                            if ($checked_inputs['item_total_quantity'] == $size_quantity) {
                                if (!empty($_FILES['item_images']) && is_array($_FILES['item_images']) && count($_FILES['item_images']['name']) >= 3 && count($_FILES['item_images']['name']) <= 6) {
                                    $item_image_folder_create = false;
                                    do {
                                        $folder_name = $this->input_control->GenerateFolderName();
                                        if ($folder_name['result']) {
                                            $is_folder_unique = $this->AdminModel->IsItemImagePathUnique($folder_name['data']);
                                            if (!$is_folder_unique['result'] && !empty($is_folder_unique['empty'])) {
                                                $new_image_folder_name = 'assets/images/items/' . $folder_name['data'];
                                                if (!is_dir($new_image_folder_name) && mkdir($new_image_folder_name, 0777, true)) {
                                                    $item_image_folder_create = true;
                                                    break;
                                                }
                                            }
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                            break;
                                        }
                                    } while (true);
                                    $image_file_name = $this->input_control->GenerateItemFileName();
                                    if (!$image_file_name['result']) {
                                        $item_image_folder_create = false;
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                    }
                                    $item_image_create = true;
                                    $item_images_for_db = '';
                                    if ($item_image_folder_create) {
                                        for ($i = 0; $i < count($_FILES['item_images']['name']); $i++) {
                                            if ($_FILES['item_images']['error'][$i] == 0) {
                                                if ($_FILES['item_images']['size'][$i] <= (1024 * 1024 * 10)) {
                                                    if ($_FILES['item_images']['type'][$i] == 'image/png') {
                                                        $image_type = 'png';
                                                    } elseif ($_FILES['item_images']['type'][$i] == 'image/jpeg') {
                                                        $image_type = 'jpg';
                                                    } else {
                                                        $item_image_create = false;
                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ITEM_IMAGES_EXTENSION);
                                                        break;
                                                    }
                                                    if (!empty($image_type)) {
                                                        $dst_width = 1200;
                                                        $dst_width_mini = 129;
                                                        $dst_height = 1800;
                                                        $dst_height_mini = 193;
                                                        $dst_image = imagecreatetruecolor($dst_width, $dst_height);
                                                        $dst_image_mini = imagecreatetruecolor($dst_width_mini, $dst_height_mini);
                                                        $image_infos = getimagesize($_FILES['item_images']['tmp_name'][$i]);
                                                        if (!empty($dst_image) && !empty($dst_image_mini) && !empty($image_infos)) {
                                                            if ($image_infos[2] == 2) {
                                                                $src_image = imagecreatefromjpeg($_FILES['item_images']['tmp_name'][$i]);
                                                                if (!empty($src_image) && imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_width, $dst_height, $image_infos[0], $image_infos[1]) && imagejpeg($dst_image, $new_image_folder_name . '/' . $image_file_name['data'] . $i . '.' . $image_type, 100) && imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $dst_width_mini, $dst_height_mini, $image_infos[0], $image_infos[1]) && imagejpeg($dst_image_mini, $new_image_folder_name . '/mini' . $image_file_name['data'] . $i . '.' . $image_type, 100)) {
                                                                    $item_images_for_db .= ($i + 1) . '-' . $image_file_name['data'] . $i . '.' . $image_type . '_';
                                                                } else {
                                                                    $item_image_create = false;
                                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                                    break;
                                                                }
                                                                imagedestroy($src_image);
                                                            } elseif ($image_infos[2] == 3) {
                                                                $src_image = imagecreatefrompng($_FILES['item_images']['tmp_name'][$i]);
                                                                if (!empty($src_image) && imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_width, $dst_height, $image_infos[0], $image_infos[1]) && imagepng($dst_image, $new_image_folder_name . '/' . $image_file_name['data'] . $i . '.' . $image_type, 9) && imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $dst_width_mini, $dst_height_mini, $image_infos[0], $image_infos[1]) && imagepng($dst_image_mini, $new_image_folder_name . '/mini' . $image_file_name['data'] . $i . '.' . $image_type, 9)) {
                                                                    $item_images_for_db .= ($i + 1) . '-' . $image_file_name['data'] . $i . '.' . $image_type . '_';
                                                                } else {
                                                                    $item_image_create = false;
                                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                                    break;
                                                                }
                                                                imagedestroy($src_image);
                                                            } else {
                                                                $item_image_create = false;
                                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                                break;
                                                            }
                                                        } else {
                                                            $item_image_create = false;
                                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    $item_image_create = false;
                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES_SIZE_LIMIT);
                                                    break;
                                                }
                                            } else {
                                                $item_image_create = false;
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                break;
                                            }
                                        }
                                    }
                                    if ($item_image_create && !empty($item_images_for_db)) {
                                        $cart_id = $this->input_control->GenerateCartId();
                                        if ($cart_id['result']) {
                                            $is_item_cart_id_unique = $this->AdminModel->IsItemCartIdUnique($cart_id['data']);
                                            if (!$is_item_cart_id_unique['result'] && !empty($is_item_cart_id_unique['empty'])) {
                                                $item_url = $this->input_control->GenerateUrl($checked_inputs['item_name']);
                                                $is_item_url_unique = $this->AdminModel->IsItemUrlUnique($item_url);
                                                if (!$is_item_url_unique['result'] && !empty($is_item_url_unique['empty'])) {
                                                    $create_item_array = array(
                                                        'item_cart_id' => $cart_id['data'],
                                                        'is_item_home' => $is_item_home,
                                                        'is_item_for_sale' => $is_item_for_sale,
                                                        'item_keywords' => $checked_inputs['item_keywords'],
                                                        'item_description' => $checked_inputs['item_description'],
                                                        'item_name' => $checked_inputs['item_name'],
                                                        'item_name' => $checked_inputs['item_name'],
                                                        'item_url' => $item_url,
                                                        'item_price' => $checked_inputs['item_price'],
                                                        'item_discount_price' => $checked_inputs['item_discount_price'],
                                                        'item_collection' => $checked_inputs['item_collection'],
                                                        'item_material' => $checked_inputs['item_material'],
                                                        'item_cut_model' => $checked_inputs['item_cut_model'],
                                                        'item_thickness' => $checked_inputs['item_thickness'],
                                                        'item_pattern' => $checked_inputs['item_pattern'],
                                                        'item_lapel' => $checked_inputs['item_lapel'],
                                                        'item_sleeve_type' => $checked_inputs['item_sleeve_type'],
                                                        'item_sleeve_length' => $checked_inputs['item_sleeve_length'],
                                                        'item_washing_style' => $checked_inputs['item_washing_style'],
                                                        'item_model_size' => $checked_inputs['item_model_size'],
                                                        'item_model_height' => $checked_inputs['item_model_height'],
                                                        'item_model_weight' => $checked_inputs['item_model_weight'],
                                                        'item_images_path' => $folder_name['data'],
                                                        'item_images' => rtrim($item_images_for_db, '_'),
                                                        'item_total_quantity' => $checked_inputs['item_total_quantity'],
                                                        'gender' => $checked_inputs['gender'],
                                                        'category' => $checked_inputs['category'],
                                                    );
                                                    foreach ($sizes_from_database['data'] as $size) {
                                                        $create_item_array[$size['size_url']] = $checked_inputs[$size['size_url']];
                                                    }
                                                    foreach ($colors_from_database['data'] as $color) {
                                                        $create_item_array[$color['color_url']] = isset($_POST[$color['color_url']]) ? 1 : 0;
                                                    }
                                                    $result_item_create = $this->AdminModel->CreateItem($create_item_array);
                                                    if ($result_item_create['result']) {
                                                        $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_ADMIN_SUCCESS_ITEM_CREATE);
                                                        $this->input_control->Redirect(URL_ADMIN_ITEMS);
                                                    } else {
                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_CREATE);
                                                    }
                                                } else {
                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_NOT_UNIQUE_URL);
                                                }
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_CREATE);
                                            }
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_CREATE);
                                        }
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_IMAGES);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_QUNATITY_NOT_EQUAL);
                            }
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                    }
                    $_SESSION[SESSION_WEB_DATA_NAME] = array(
                        'item_name' => $item_name,
                        'item_keywords' => $item_keywords,
                        'item_description' => $item_description,
                        'item_price' => $item_price,
                        'item_discount_price' => $item_discount_price,
                        'item_collection' => $item_collection,
                        'item_material' => $item_material,
                        'item_cut_model' => $item_cut_model,
                        'item_thickness' => $item_thickness,
                        'item_pattern' => $item_pattern,
                        'item_lapel' => $item_lapel,
                        'item_sleeve_type' => $item_sleeve_type,
                        'item_sleeve_length' => $item_sleeve_length,
                        'item_washing_style' => $item_washing_style,
                        'item_model_size' => $item_model_size,
                        'item_model_height' => $item_model_height,
                        'item_model_weight' => $item_model_weight,
                        'item_total_quantity' => $item_total_quantity,
                        'gender' => $gender,
                        'category' => $category,
                        'sizes' => $posted_sizes,
                        'colors' => $posted_colors,
                        'is_item_for_sale' => $is_item_for_sale,
                        'is_item_home' => $is_item_home,
                    );
                    $this->input_control->Redirect(URL_ADMIN_ITEM_CREATE);
                }
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function ItemCreate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ItemUpdate()
    {
        try {
            $sizes_from_database = $this->AdminModel->GetSizes();
            $genders_from_database = $this->AdminModel->GetGenders();
            $categories_from_database = $this->AdminModel->GetCategories();
            $colors_from_database = $this->AdminModel->GetColors();
            if ($sizes_from_database['result'] && $genders_from_database['result'] && $categories_from_database['result'] && $colors_from_database['result']) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $item_hidden_url = $this->input_control->CheckGETInput(isset($_POST['item_hidden_url']) ? $_POST['item_hidden_url'] : '');
                    $item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
                    $item_keywords = isset($_POST['item_keywords']) ? $_POST['item_keywords'] : '';
                    $item_description = isset($_POST['item_description']) ? $_POST['item_description'] : '';
                    $item_price = isset($_POST['item_price']) ? $_POST['item_price'] : '';
                    $item_discount_price = isset($_POST['item_discount_price']) ? $_POST['item_discount_price'] : '';
                    $item_collection = isset($_POST['item_collection']) ? $_POST['item_collection'] : '';
                    $item_material = isset($_POST['item_material']) ? $_POST['item_material'] : '';
                    $item_cut_model = isset($_POST['item_cut_model']) ? $_POST['item_cut_model'] : '';
                    $item_thickness = isset($_POST['item_thickness']) ? $_POST['item_thickness'] : '';
                    $item_pattern = isset($_POST['item_pattern']) ? $_POST['item_pattern'] : '';
                    $item_lapel = isset($_POST['item_lapel']) ? $_POST['item_lapel'] : '';
                    $item_sleeve_type = isset($_POST['item_sleeve_type']) ? $_POST['item_sleeve_type'] : '';
                    $item_sleeve_length = isset($_POST['item_sleeve_length']) ? $_POST['item_sleeve_length'] : '';
                    $item_washing_style = isset($_POST['item_washing_style']) ? $_POST['item_washing_style'] : '';
                    $item_model_size = isset($_POST['item_model_size']) ? $_POST['item_model_size'] : '';
                    $item_model_height = isset($_POST['item_model_height']) ? $_POST['item_model_height'] : '';
                    $item_model_weight = isset($_POST['item_model_weight']) ? $_POST['item_model_weight'] : '';
                    $item_total_quantity = isset($_POST['item_total_quantity']) ? $_POST['item_total_quantity'] : '';
                    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                    $category = isset($_POST['category']) ? $_POST['category'] : '';
                    $is_item_for_sale = isset($_POST['is_item_for_sale']) ? 1 : 0;
                    $is_item_home = isset($_POST['is_item_home']) ? 1 : 0;
                    $check_posted_inputs = array(
                        'item_name' => array('input' => $item_name, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_NAME, 'length_control' => true, 'max_length' => 45, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_NAME_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 45, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_NAME_MAX_LENGTH),
                        'item_keywords' => array('input' => $item_keywords, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_KEYWORDS, 'length_control' => true, 'max_length' => 100, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_KEYWORDS_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 100, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_KEYWORDS_MAX_LENGTH),
                        'item_description' => array('input' => $item_description, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_DESCRIPTION, 'length_control' => true, 'max_length' => 160, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_DESCRIPTION_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 200, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_DESCRIPTION_MAX_LENGTH),
                        'item_price' => array('input' => $item_price, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_PRICE, 'preventxss' => true, 'is_float_and_positive' => true, 'error_message_is_float_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_PRICE_NOT_POSITIVE),
                        'item_discount_price' => array('input' => $item_discount_price, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_DISCOUNT_PRICE, 'preventxss' => true, 'is_float_and_positive' => true, 'error_message_is_float_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_DISCOUNT_PRICE_NOT_POSITIVE),
                        'item_collection' => array('input' => $item_collection, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_COLLECTION, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_COLLECTION_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_COLLECTION_MAX_LENGTH),
                        'item_material' => array('input' => $item_material, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MATERIAL, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MATERIAL_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MATERIAL_MAX_LENGTH),
                        'item_cut_model' => array('input' => $item_cut_model, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_CUTMODEL, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_CUTMODEL_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_CUTMODEL_MAX_LENGTH),
                        'item_thickness' => array('input' => $item_thickness, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_THICKNESS, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_THICKNESS_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_THICKNESS_MAX_LENGTH),
                        'item_pattern' => array('input' => $item_pattern, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_PATTERN, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_PATTERN_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_PATTERN_MAX_LENGTH),
                        'item_lapel' => array('input' => $item_lapel, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_LAPEL, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_LAPEL_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_LAPEL_MAX_LENGTH),
                        'item_sleeve_type' => array('input' => $item_sleeve_type, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_SLEEVE_TYPE, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_TYPE_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_TYPE_MAX_LENGTH),
                        'item_sleeve_length' => array('input' => $item_sleeve_length, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_SLEEVE_LENGTH, 'length_control' => true, 'max_length' => 50, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_LENGTH_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 50, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_LENGTH_MAX_LENGTH),
                        'item_washing_style' => array('input' => $item_washing_style, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_WASHING_STYLE, 'length_control' => true, 'max_length' => 200, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_WASHING_STYLE_MAX_LENGTH, 'preventxss' => true, 'length_limit' => 200, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_WASHING_STYLE_MAX_LENGTH),
                        'item_model_size' => array('input' => $item_model_size, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MODEL_SIZE, 'length_control' => true, 'max_length' => 3, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_SIZE_NOT_VALID, 'preventxss' => true, 'length_limit' => 3, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_SIZE_NOT_VALID),
                        'item_model_height' => array('input' => $item_model_height, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MODEL_HEIGHT, 'length_control' => true, 'max_length' => 3, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_HEIGHT_NOT_VALID, 'preventxss' => true, 'length_limit' => 3, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_HEIGHT_NOT_VALID, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_HEIGHT_NOT_VALID),
                        'item_model_weight' => array('input' => $item_model_weight, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MODEL_WEIGHT, 'length_control' => true, 'max_length' => 3, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_WEIGHT_NOT_VALID, 'preventxss' => true, 'length_limit' => 3, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_WEIGHT_NOT_VALID, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_WEIGHT_NOT_VALID),
                        'item_total_quantity' => array('input' => $item_total_quantity, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_TOTAL_QUANTITY, 'length_control' => true, 'max_length' => 8, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_TOTAL_QUANTITY_NOT_VALID, 'preventxss' => true, 'length_limit' => 8, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_TOTAL_QUANTITY_NOT_VALID, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_TOTAL_QUANTITY_NOT_VALID),
                        'gender' => array('input' => $gender, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_GENDER, 'length_control' => true, 'max_length' => 250, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_GENDER_MAX_LENGTH, 'preventxssforid' => true, 'length_limit' => 250, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_GENDER_MAX_LENGTH),
                        'category' => array('input' => $category, 'error_message_empty' => TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_CATEGORY, 'length_control' => true, 'max_length' => 250, 'error_message_max_length' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_CATEGORY_MAX_LENGTH, 'preventxssforid' => true, 'length_limit' => 250, 'error_message_length_limit' => TR_NOTIFICATION_ADMIN_ERROR_ITEM_CATEGORY_MAX_LENGTH),
                        'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                    );
                    $posted_sizes = array();
                    foreach ($sizes_from_database['data'] as $size) {
                        $posted_sizes[$size['size_url']] = isset($_POST[$size['size_url']]) ? $_POST[$size['size_url']] : '';
                        $check_posted_inputs[$size['size_url']] = array('input' => isset($_POST[$size['size_url']]) ? $_POST[$size['size_url']] : '', 'numeric_is_string' => true, 'length_control' => true, 'max_length' => 8, 'error_message_max_length' => 'Ürün ' . $size['size_name'] . ' adedi geçerli değil', 'preventxss' => true, 'length_limit' => 8, 'error_message_length_limit' => 'Ürün ' . $size['size_name'] . ' adedi geçerli değil', 'is_integer_or_zero' => true, 'error_message_is_integer_or_zero' => 'Ürün ' . $size['size_name'] . ' adedi geçerli değil');
                    }
                    $posted_colors = array();
                    foreach ($colors_from_database['data'] as $color) {
                        $posted_colors[$color['color_url']] = isset($_POST[$color['color_url']]) ? 1 : 0;
                    }
                    $checked_inputs = $this->input_control->CheckPostedInputs($check_posted_inputs);
                    if (empty($checked_inputs['error_message'])) {
                        if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminItemUpdate')) {
                            $size_quantity = 0;
                            foreach ($sizes_from_database['data'] as $size) {
                                $size_quantity += $checked_inputs[$size['size_url']];
                            }
                            if ($checked_inputs['item_total_quantity'] == $size_quantity) {
                                if (!empty($item_hidden_url)) {
                                    $item_details_from_db = $this->AdminModel->GetItemForUpdate($item_hidden_url);
                                    if ($item_details_from_db['result']) {
                                        $item_image_create = true;
                                        $item_images_for_db = '';
                                        $item_image_change = false;
                                        if (!empty($_FILES['item_images']) && is_array($_FILES['item_images']) && count($_FILES['item_images']['name']) >= 3 && count($_FILES['item_images']['name']) <= 6) {
                                            $item_images_exploded_from_db = $this->input_control->GetItemImagesDirect($item_details_from_db['data']['item_images']);
                                            $new_image_folder_name = 'assets/images/items/' . $item_details_from_db['data']['item_images_path'];
                                            $image_file_name = $this->input_control->GenerateItemFileName();
                                            if ($image_file_name['result']) {
                                                for ($i = 0; $i < count($_FILES['item_images']['name']); $i++) {
                                                    if (!empty($_FILES['item_images']['name'][$i])) {
                                                        $item_image_change = true;
                                                        if ($_FILES['item_images']['error'][$i] == 0) {
                                                            if ($_FILES['item_images']['size'][$i] <= (1024 * 1024 * 10)) {
                                                                if ($_FILES['item_images']['type'][$i] == 'image/png') {
                                                                    $image_type = 'png';
                                                                } elseif ($_FILES['item_images']['type'][$i] == 'image/jpeg') {
                                                                    $image_type = 'jpg';
                                                                } else {
                                                                    $item_image_create = false;
                                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ITEM_IMAGES_EXTENSION);
                                                                    break;
                                                                }
                                                                if (!empty($image_type)) {
                                                                    $dst_width = 1200;
                                                                    $dst_width_mini = 129;
                                                                    $dst_height = 1800;
                                                                    $dst_height_mini = 193;
                                                                    $dst_image = imagecreatetruecolor($dst_width, $dst_height);
                                                                    $dst_image_mini = imagecreatetruecolor($dst_width_mini, $dst_height_mini);
                                                                    $image_infos = getimagesize($_FILES['item_images']['tmp_name'][$i]);
                                                                    if (!empty($dst_image) && !empty($dst_image_mini) && !empty($image_infos)) {
                                                                        if ($image_infos[2] == 2) {
                                                                            $src_image = imagecreatefromjpeg($_FILES['item_images']['tmp_name'][$i]);
                                                                            if (!empty($src_image) && unlink($new_image_folder_name . '/' . $item_images_exploded_from_db[$i][1]) && unlink($new_image_folder_name . '/mini' . $item_images_exploded_from_db[$i][1]) && imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_width, $dst_height, $image_infos[0], $image_infos[1]) && imagejpeg($dst_image, $new_image_folder_name . '/' . $image_file_name['data'] . $i . '.' . $image_type, 100) && imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $dst_width_mini, $dst_height_mini, $image_infos[0], $image_infos[1]) && imagejpeg($dst_image_mini, $new_image_folder_name . '/mini' . $image_file_name['data'] . $i . '.' . $image_type, 100)) {
                                                                                $item_images_for_db .= ($i + 1) . '-' . $image_file_name['data'] . $i . '.' . $image_type . '_';
                                                                            } else {
                                                                                $item_image_create = false;
                                                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                                                break;
                                                                            }
                                                                            imagedestroy($src_image);
                                                                        } elseif ($image_infos[2] == 3) {
                                                                            $src_image = imagecreatefrompng($_FILES['item_images']['tmp_name'][$i]);
                                                                            if (!empty($src_image) && unlink($new_image_folder_name . '/' . $item_images_exploded_from_db[$i][1]) && unlink($new_image_folder_name . '/mini' . $item_images_exploded_from_db[$i][1]) && imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_width, $dst_height, $image_infos[0], $image_infos[1]) && imagepng($dst_image, $new_image_folder_name . '/' . $image_file_name['data'] . $i . '.' . $image_type, 9) && imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $dst_width_mini, $dst_height_mini, $image_infos[0], $image_infos[1]) && imagepng($dst_image_mini, $new_image_folder_name . '/mini' . $image_file_name['data'] . $i . '.' . $image_type, 9)) {
                                                                                $item_images_for_db .= ($i + 1) . '-' . $image_file_name['data'] . $i . '.' . $image_type . '_';
                                                                            } else {
                                                                                $item_image_create = false;
                                                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                                                break;
                                                                            }
                                                                            imagedestroy($src_image);
                                                                        } else {
                                                                            $item_image_create = false;
                                                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                                            break;
                                                                        }
                                                                    } else {
                                                                        $item_image_create = false;
                                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                                        break;
                                                                    }
                                                                }
                                                            } else {
                                                                $item_image_create = false;
                                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES_SIZE_LIMIT);
                                                                break;
                                                            }
                                                        } else {
                                                            $item_image_create = false;
                                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                                            break;
                                                        }
                                                    } else {
                                                        $item_images_for_db .= $item_images_exploded_from_db[$i][0] . '-' . $item_images_exploded_from_db[$i][1] . '_';
                                                    }
                                                }
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES);
                                            }
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_UPDATE);
                                        }
                                        if ($item_image_create) {
                                            $url_unique = false;
                                            $item_url = $this->input_control->GenerateUrl($checked_inputs['item_name']);
                                            if ($item_details_from_db['data']['item_url'] == $item_url) {
                                                $url_unique = true;
                                            } else {
                                                $is_item_url_unique = $this->AdminModel->IsItemUrlUnique($item_url);
                                                if (!$is_item_url_unique['result'] && !empty($is_item_url_unique['empty'])) {
                                                    $url_unique = true;
                                                } else {
                                                    $url_unique = false;
                                                }
                                            }
                                            if ($url_unique) {
                                                $create_item_array = array(
                                                    'is_item_home' => $is_item_home,
                                                    'is_item_for_sale' => $is_item_for_sale,
                                                    'item_keywords' => $checked_inputs['item_keywords'],
                                                    'item_description' => $checked_inputs['item_description'],
                                                    'item_name' => $checked_inputs['item_name'],
                                                    'item_url' => $item_url,
                                                    'item_price' => $checked_inputs['item_price'],
                                                    'item_discount_price' => $checked_inputs['item_discount_price'],
                                                    'item_collection' => $checked_inputs['item_collection'],
                                                    'item_material' => $checked_inputs['item_material'],
                                                    'item_cut_model' => $checked_inputs['item_cut_model'],
                                                    'item_thickness' => $checked_inputs['item_thickness'],
                                                    'item_pattern' => $checked_inputs['item_pattern'],
                                                    'item_lapel' => $checked_inputs['item_lapel'],
                                                    'item_sleeve_type' => $checked_inputs['item_sleeve_type'],
                                                    'item_sleeve_length' => $checked_inputs['item_sleeve_length'],
                                                    'item_washing_style' => $checked_inputs['item_washing_style'],
                                                    'item_model_size' => $checked_inputs['item_model_size'],
                                                    'item_model_height' => $checked_inputs['item_model_height'],
                                                    'item_model_weight' => $checked_inputs['item_model_weight'],
                                                    'item_total_quantity' => $checked_inputs['item_total_quantity'],
                                                    'gender' => $checked_inputs['gender'],
                                                    'category' => $checked_inputs['category'],
                                                );
                                                foreach ($sizes_from_database['data'] as $size) {
                                                    $create_item_array[$size['size_url']] = $checked_inputs[$size['size_url']];
                                                }
                                                foreach ($colors_from_database['data'] as $color) {
                                                    $create_item_array[$color['color_url']] = isset($_POST[$color['color_url']]) ? 1 : 0;
                                                }
                                                if ($item_image_change && !empty($item_images_for_db)) {
                                                    $create_item_array['item_images'] = rtrim($item_images_for_db, '_');
                                                }
                                                $create_item_array['id'] = $item_details_from_db['data']['id'];
                                                $result_item_create = $this->AdminModel->UpdateItem($create_item_array);
                                                if ($result_item_create['result']) {
                                                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_ADMIN_SUCCESS_ITEM_UPDATE);
                                                    $this->input_control->Redirect(URL_ADMIN_ITEMS);
                                                } else {
                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_UPDATE);
                                                }
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_NOT_UNIQUE_URL);
                                            }
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_UPDATE);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_UPDATE);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_QUNATITY_NOT_EQUAL);
                            }
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                    }
                    $_SESSION[SESSION_WEB_DATA_NAME] = array(
                        'item_name' => $item_name,
                        'item_keywords' => $item_keywords,
                        'item_description' => $item_description,
                        'item_price' => $item_price,
                        'item_discount_price' => $item_discount_price,
                        'item_collection' => $item_collection,
                        'item_material' => $item_material,
                        'item_cut_model' => $item_cut_model,
                        'item_thickness' => $item_thickness,
                        'item_pattern' => $item_pattern,
                        'item_lapel' => $item_lapel,
                        'item_sleeve_type' => $item_sleeve_type,
                        'item_sleeve_length' => $item_sleeve_length,
                        'item_washing_style' => $item_washing_style,
                        'item_model_size' => $item_model_size,
                        'item_model_height' => $item_model_height,
                        'item_model_weight' => $item_model_weight,
                        'item_total_quantity' => $item_total_quantity,
                        'gender' => $gender,
                        'category' => $category,
                        'sizes' => $posted_sizes,
                        'colors' => $posted_colors,
                        'is_item_for_sale' => $is_item_for_sale,
                        'is_item_home' => $is_item_home,
                    );
                    if (!empty($item_hidden_url)) {
                        $this->input_control->Redirect(URL_ADMIN_ITEM_DETAILS . '/' . $item_hidden_url);
                    } else {
                        $this->input_control->Redirect(URL_ADMIN_ITEMS);
                    }
                }
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function ItemUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ItemDelete()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'id' => array('input' => isset($_POST['id']) ? $_POST['id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'length_control' => true, 'max_length' => 250, 'error_message_max_length' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true, 'length_limit' => 250, 'error_message_length_limit' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminItemUpdate')) {
                        if ($this->AdminModel->UpdateItem(array('is_item_deleted' => 1, 'date_item_deleted' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['id']))['result']) {
                            $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_ADMIN_SUCCESS_ITEM_DELETE);
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_ITEM_DELETE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $this->input_control->Redirect(URL_ADMIN_ITEMS);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function ItemDelete | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Orders()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function Orders | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
        parent::GetView('Admin/Orders', $this->web_data);
    }
    function Users()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $users = $this->AdminModel->GetUsers();
                if ($users['result']) {
                    $this->web_data['users'] = $users['data'];
                }
                parent::GetView('Admin/Users', $this->web_data);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function Users | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
        parent::GetView('Admin/Orders', $this->web_data);
    }
    function SendEmail()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $this->web_data['form_token'] = parent::SetCSRFToken('AdminSendEmail');
                parent::GetView('Admin/SendEmail', $this->web_data);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function SendEmail | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
        parent::GetView('Admin/Orders', $this->web_data);
    }
    function Statistics(string $statistics_url)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $case_matched = false;
                switch ($statistics_url) {
                    case URL_ADMIN_LOGS_PAGE:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_PAGE;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_PAGE_TITLE;
                        $logs_view_once = $this->AdminModel->GetLogsViewOnce();
                        $logs_view_all = $this->AdminModel->GetLogsViewAll();
                        $genders_from_database = $this->AdminModel->GetGendersForCount();
                        $items_from_database = $this->AdminModel->GetItemsForCount();
                        if ($logs_view_once['result'] && $logs_view_all['result'] && $genders_from_database['result'] && $items_from_database['result']) {
                            $this->web_data['genders_count'] = array();
                            $this->web_data['genders_count_all'] = array();
                            foreach ($genders_from_database['data'] as $gender_from_database) {
                                $this->web_data['genders_count'][$gender_from_database['gender_name']] = 0;
                                $this->web_data['genders_count_all'][$gender_from_database['gender_name']] = 0;
                            }
                            $this->web_data['items_count'] = array();
                            $this->web_data['items_count_all'] = array();
                            foreach ($items_from_database['data'] as $item_from_database) {
                                $this->web_data['items_count'][$item_from_database['item_name']] = 0;
                                $this->web_data['items_count_all'][$item_from_database['item_name']] = 0;
                            }
                            $this->web_data['agreements_count'] = array();
                            $this->web_data['agreements_count'][URL_TERMS_TITLE] = 0;
                            $this->web_data['agreements_count'][URL_PRIVACY_TITLE] = 0;
                            $this->web_data['agreements_count'][URL_RETURN_POLICY_TITLE] = 0;
                            $this->web_data['profile_count'] = array();
                            $this->web_data['profile_count'][URL_PROFILE_INFO_TITLE] = 0;
                            $this->web_data['profile_count'][URL_PROFILE_IDENTITY_TITLE] = 0;
                            $this->web_data['profile_count'][URL_PROFILE_ADDRESS_TITLE] = 0;
                            $this->web_data['profile_count'][URL_PROFILE_PWD_TITLE] = 0;
                            $this->web_data['profile_count'][URL_PROFILE_EMAIL_TITLE] = 0;
                            $this->web_data['profile_count'][URL_PROFILE_TEL_TITLE] = 0;
                            $this->web_data['profile_count'][URL_PROFILE_PHOTO_TITLE] = 0;
                            $this->web_data['profile_count'][URL_PROFILE_ORDERS_TITLE] = 0;
                            $this->web_data['home_index'] = 0;
                            $this->web_data['home_cart'] = 0;
                            $this->web_data['home_favorites'] = 0;
                            $this->web_data['home_emailupdateconfirm'] = 0;
                            $this->web_data['home_orderinitialize_name'] = 0;
                            $this->web_data['home_orderinitialize_identity'] = 0;
                            $this->web_data['home_orderinitialize_address'] = 0;
                            $this->web_data['home_orderinitialize_credit'] = 0;
                            $this->web_data['home_orderinitialize_3D'] = 0;
                            $this->web_data['home_orderpayment'] = 0;
                            $this->web_data['action_twofa'] = 0;
                            $this->web_data['action_login'] = 0;
                            $this->web_data['action_register_confirm'] = 0;
                            $this->web_data['action_register_cancel'] = 0;
                            $this->web_data['action_register'] = 0;
                            $this->web_data['action_reset_password'] = 0;
                            $this->web_data['action_admin_login'] = 0;
                            foreach ($logs_view_once['data'] as $log_view_once) {
                                if (str_contains($log_view_once['viewed_page'], '_')) {
                                    $exploded_log_view_once = explode('_', $log_view_once['viewed_page']);
                                    if ($exploded_log_view_once[0] == 'Home-Items') {
                                        foreach ($genders_from_database['data'] as $gender_from_database) {
                                            if ($gender_from_database['gender_url'] == $exploded_log_view_once[1]) {
                                                $this->web_data['genders_count'][$gender_from_database['gender_name']] += 1;
                                            }
                                        }
                                    } elseif ($exploded_log_view_once[0] == 'Home-ItemDetails') {
                                        foreach ($items_from_database['data'] as $item_from_database) {
                                            if ($item_from_database['item_url'] == $exploded_log_view_once[1]) {
                                                $this->web_data['items_count'][$item_from_database['item_name']] += 1;
                                            }
                                        }
                                    } elseif ($exploded_log_view_once[0] == 'Home-Agreements') {
                                        if ($exploded_log_view_once[1] == URL_TERMS_TITLE) {
                                            $this->web_data['agreements_count'][URL_TERMS_TITLE] += 1;
                                        } elseif ($exploded_log_view_once[1] == URL_PRIVACY_TITLE) {
                                            $this->web_data['agreements_count'][URL_PRIVACY_TITLE] += 1;
                                        } elseif ($exploded_log_view_once[1] == URL_RETURN_POLICY_TITLE) {
                                            $this->web_data['agreements_count'][URL_RETURN_POLICY_TITLE] += 1;
                                        }
                                    } elseif ($exploded_log_view_once[0] == 'Home-Profile') {
                                        if ($exploded_log_view_once[1] == URL_PROFILE_INFO_TITLE) {
                                            $this->web_data['profile_count'][URL_PROFILE_INFO_TITLE] += 1;
                                        } elseif ($exploded_log_view_once[1] == URL_PROFILE_IDENTITY_TITLE) {
                                            $this->web_data['profile_count'][URL_PROFILE_IDENTITY_TITLE] += 1;
                                        } elseif ($exploded_log_view_once[1] == URL_PROFILE_ADDRESS_TITLE) {
                                            $this->web_data['profile_count'][URL_PROFILE_ADDRESS_TITLE] += 1;
                                        } elseif ($exploded_log_view_once[1] == URL_PROFILE_PWD_TITLE) {
                                            $this->web_data['profile_count'][URL_PROFILE_PWD_TITLE] += 1;
                                        } elseif ($exploded_log_view_once[1] == URL_PROFILE_EMAIL_TITLE) {
                                            $this->web_data['profile_count'][URL_PROFILE_EMAIL_TITLE] += 1;
                                        } elseif ($exploded_log_view_once[1] == URL_PROFILE_TEL_TITLE) {
                                            $this->web_data['profile_count'][URL_PROFILE_TEL_TITLE] += 1;
                                        } elseif ($exploded_log_view_once[1] == URL_PROFILE_PHOTO_TITLE) {
                                            $this->web_data['profile_count'][URL_PROFILE_PHOTO_TITLE] += 1;
                                        } elseif ($exploded_log_view_once[1] == URL_PROFILE_ORDERS_TITLE) {
                                            $this->web_data['profile_count'][URL_PROFILE_ORDERS_TITLE] += 1;
                                        }
                                    }
                                } else {
                                    if ($log_view_once['viewed_page'] == 'Home-Index') {
                                        $this->web_data['home_index'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Home-Cart') {
                                        $this->web_data['home_cart'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Home-Favorites') {
                                        $this->web_data['home_favorites'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Home-EmailUpdateConfirm') {
                                        $this->web_data['home_emailupdateconfirm'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Home-OrderInitialize-Name') {
                                        $this->web_data['home_orderinitialize_name'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Home-OrderInitialize-Identity') {
                                        $this->web_data['home_orderinitialize_identity'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Home-OrderInitialize-Address') {
                                        $this->web_data['home_orderinitialize_address'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Home-OrderInitialize-Credit') {
                                        $this->web_data['home_orderinitialize_credit'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Home-OrderInitialize-3D') {
                                        $this->web_data['home_orderinitialize_3D'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Home-OrderPayment') {
                                        $this->web_data['home_orderpayment'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Action-TwoFA') {
                                        $this->web_data['action_twofa'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Action-Login') {
                                        $this->web_data['action_login'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Action-RegisterConfirm') {
                                        $this->web_data['action_register_confirm'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Action-RegisterCancel') {
                                        $this->web_data['action_register_cancel'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Action-Register') {
                                        $this->web_data['action_register'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Action-ResetPassword') {
                                        $this->web_data['action_reset_password'] += 1;
                                    } elseif ($log_view_once['viewed_page'] == 'Action-AdminLogin') {
                                        $this->web_data['action_admin_login'] += 1;
                                    }
                                }
                            }
                            $this->web_data['agreements_count_all'] = array();
                            $this->web_data['agreements_count_all'][URL_TERMS_TITLE] = 0;
                            $this->web_data['agreements_count_all'][URL_PRIVACY_TITLE] = 0;
                            $this->web_data['agreements_count_all'][URL_RETURN_POLICY_TITLE] = 0;
                            $this->web_data['profile_count_all'] = array();
                            $this->web_data['profile_count_all'][URL_PROFILE_INFO_TITLE] = 0;
                            $this->web_data['profile_count_all'][URL_PROFILE_IDENTITY_TITLE] = 0;
                            $this->web_data['profile_count_all'][URL_PROFILE_ADDRESS_TITLE] = 0;
                            $this->web_data['profile_count_all'][URL_PROFILE_PWD_TITLE] = 0;
                            $this->web_data['profile_count_all'][URL_PROFILE_EMAIL_TITLE] = 0;
                            $this->web_data['profile_count_all'][URL_PROFILE_TEL_TITLE] = 0;
                            $this->web_data['profile_count_all'][URL_PROFILE_PHOTO_TITLE] = 0;
                            $this->web_data['profile_count_all'][URL_PROFILE_ORDERS_TITLE] = 0;
                            $this->web_data['home_index_all'] = 0;
                            $this->web_data['home_cart_all'] = 0;
                            $this->web_data['home_favorites_all'] = 0;
                            $this->web_data['home_emailupdateconfirm_all'] = 0;
                            $this->web_data['home_orderinitialize_name_all'] = 0;
                            $this->web_data['home_orderinitialize_identity_all'] = 0;
                            $this->web_data['home_orderinitialize_address_all'] = 0;
                            $this->web_data['home_orderinitialize_credit_all'] = 0;
                            $this->web_data['home_orderinitialize_3D_all'] = 0;
                            $this->web_data['home_orderpayment_all'] = 0;
                            $this->web_data['action_twofa_all'] = 0;
                            $this->web_data['action_login_all'] = 0;
                            $this->web_data['action_register_confirm_all'] = 0;
                            $this->web_data['action_register_cancel_all'] = 0;
                            $this->web_data['action_register_all'] = 0;
                            $this->web_data['action_reset_password_all'] = 0;
                            $this->web_data['action_admin_login_all'] = 0;
                            foreach ($logs_view_all['data'] as $log_view_all) {
                                if (str_contains($log_view_all['viewed_page'], '_')) {
                                    $exploded_log_view_all = explode('_', $log_view_all['viewed_page']);
                                    if ($exploded_log_view_all[0] == 'Home-Items') {
                                        foreach ($genders_from_database['data'] as $gender_from_database) {
                                            if ($gender_from_database['gender_url'] == $exploded_log_view_all[1]) {
                                                $this->web_data['genders_count_all'][$gender_from_database['gender_name']] += 1;
                                            }
                                        }
                                    } elseif ($exploded_log_view_all[0] == 'Home-ItemDetails') {
                                        foreach ($items_from_database['data'] as $item_from_database) {
                                            if ($item_from_database['item_url'] == $exploded_log_view_all[1]) {
                                                $this->web_data['items_count_all'][$item_from_database['item_name']] += 1;
                                            }
                                        }
                                    } elseif ($exploded_log_view_all[0] == 'Home-Agreements') {
                                        if ($exploded_log_view_all[1] == URL_TERMS_TITLE) {
                                            $this->web_data['agreements_count_all'][URL_TERMS_TITLE] += 1;
                                        } elseif ($exploded_log_view_all[1] == URL_PRIVACY_TITLE) {
                                            $this->web_data['agreements_count_all'][URL_PRIVACY_TITLE] += 1;
                                        } elseif ($exploded_log_view_all[1] == URL_RETURN_POLICY_TITLE) {
                                            $this->web_data['agreements_count_all'][URL_RETURN_POLICY_TITLE] += 1;
                                        }
                                    } elseif ($exploded_log_view_all[0] == 'Home-Profile') {
                                        if ($exploded_log_view_all[1] == URL_PROFILE_INFO_TITLE) {
                                            $this->web_data['profile_count_all'][URL_PROFILE_INFO_TITLE] += 1;
                                        } elseif ($exploded_log_view_all[1] == URL_PROFILE_IDENTITY_TITLE) {
                                            $this->web_data['profile_count_all'][URL_PROFILE_IDENTITY_TITLE] += 1;
                                        } elseif ($exploded_log_view_all[1] == URL_PROFILE_ADDRESS_TITLE) {
                                            $this->web_data['profile_count_all'][URL_PROFILE_ADDRESS_TITLE] += 1;
                                        } elseif ($exploded_log_view_all[1] == URL_PROFILE_PWD_TITLE) {
                                            $this->web_data['profile_count_all'][URL_PROFILE_PWD_TITLE] += 1;
                                        } elseif ($exploded_log_view_all[1] == URL_PROFILE_EMAIL_TITLE) {
                                            $this->web_data['profile_count_all'][URL_PROFILE_EMAIL_TITLE] += 1;
                                        } elseif ($exploded_log_view_all[1] == URL_PROFILE_TEL_TITLE) {
                                            $this->web_data['profile_count_all'][URL_PROFILE_TEL_TITLE] += 1;
                                        } elseif ($exploded_log_view_all[1] == URL_PROFILE_PHOTO_TITLE) {
                                            $this->web_data['profile_count_all'][URL_PROFILE_PHOTO_TITLE] += 1;
                                        } elseif ($exploded_log_view_all[1] == URL_PROFILE_ORDERS_TITLE) {
                                            $this->web_data['profile_count_all'][URL_PROFILE_ORDERS_TITLE] += 1;
                                        }
                                    }
                                } else {
                                    if ($log_view_all['viewed_page'] == 'Home-Index') {
                                        $this->web_data['home_index_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Home-Cart') {
                                        $this->web_data['home_cart_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Home-Favorites') {
                                        $this->web_data['home_favorites_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Home-EmailUpdateConfirm') {
                                        $this->web_data['home_emailupdateconfirm_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Home-OrderInitialize-Name') {
                                        $this->web_data['home_orderinitialize_name_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Home-OrderInitialize-Identity') {
                                        $this->web_data['home_orderinitialize_identity_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Home-OrderInitialize-Address') {
                                        $this->web_data['home_orderinitialize_address_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Home-OrderInitialize-Credit') {
                                        $this->web_data['home_orderinitialize_credit_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Home-OrderInitialize-3D') {
                                        $this->web_data['home_orderinitialize_3D_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Home-OrderPayment') {
                                        $this->web_data['home_orderpayment_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Action-TwoFA') {
                                        $this->web_data['action_twofa_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Action-Login') {
                                        $this->web_data['action_login_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Action-RegisterConfirm') {
                                        $this->web_data['action_register_confirm_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Action-RegisterCancel') {
                                        $this->web_data['action_register_cancel_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Action-Register') {
                                        $this->web_data['action_register_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Action-ResetPassword') {
                                        $this->web_data['action_reset_password_all'] += 1;
                                    } elseif ($log_view_all['viewed_page'] == 'Action-AdminLogin') {
                                        $this->web_data['action_admin_login_all'] += 1;
                                    }
                                }
                            }
                        }
                        break;
                    case URL_ADMIN_LOGS_USER:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_USER;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_USER_TITLE;
                        $users = $this->AdminModel->GetUsersForCount();
                        $admins = $this->AdminModel->GetAdminsForCount();
                        if ($users['result'] && $admins['result']) {
                            $this->web_data['user_count'] = count($users['data']);
                            $this->web_data['admin_count'] = count($admins['data']);
                        }
                        break;
                    case URL_ADMIN_LOGS_ERROR:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_ERROR;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_ERROR_TITLE;
                        $log_error = $this->AdminModel->GetLogError();
                        if ($log_error['result']) {
                            $this->web_data['log_error'] = $log_error['data'];
                        }
                        break;
                    case URL_ADMIN_LOGS_LOGIN_ACCOUNT:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_LOGIN_ACCOUNT;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_LOGIN_ACCOUNT_TITLE;
                        $log_login = $this->AdminModel->GetLogLogin();
                        if ($log_login['result']) {
                            $this->web_data['log_login'] = $log_login['data'];
                        }
                        break;
                    case URL_ADMIN_LOGS_LOGIN:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_LOGIN;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_LOGIN_TITLE;
                        $log_login_fail = $this->AdminModel->GetLogLoginFail();
                        if ($log_login_fail['result']) {
                            $this->web_data['log_login_fail'] = $log_login_fail['data'];
                        }
                        break;
                    case URL_ADMIN_LOGS_EMAIL:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_EMAIL;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_EMAIL_TITLE;
                        $log_email_sent = $this->AdminModel->GetLogEmailSent();
                        if ($log_email_sent['result']) {
                            $this->web_data['log_email_sent'] = $log_email_sent['data'];
                        }
                        break;
                    case URL_ADMIN_LOGS_CAPTCHA:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_CAPTCHA;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_CAPTCHA_TITLE;
                        $log_captcha = $this->AdminModel->GetLogCaptcha();
                        if ($log_captcha['result']) {
                            $this->web_data['log_captcha'] = $log_captcha['data'];
                        }
                        break;
                    case URL_ADMIN_LOGS_CAPTCHA_TIMEOUT:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_CAPTCHA_TIMEOUT;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_CAPTCHA_TIMEOUT_TITLE;
                        $log_captcha_timeout = $this->AdminModel->GetLogCaptchaTimeout();
                        if ($log_captcha_timeout['result']) {
                            $this->web_data['log_captcha_timeout'] = $log_captcha_timeout['data'];
                        }
                        break;
                }
                if ($case_matched) {
                    parent::GetView('Admin/Statistics', $this->web_data);
                }
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function Statistics | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }





























    // function Items()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         $this->input_control->CheckUrl(array('filter', 'search', 'max', 'min', 'sort', 'limit', 'page'));
    //         $prices = $this->ItemModel->GetItemsDiscountPrices();
    //         if (!empty($prices)) {
    //             $prices_for_filter = array();
    //             foreach ($prices as $price) {
    //                 $prices_for_filter[] = (int)$price['item_discount_price'];
    //             }
    //             $this->web_data['min_price'] = min($prices_for_filter);
    //             $this->web_data['max_price'] = max($prices_for_filter);
    //         }
    //         $get_with_param = false;
    //         $hidden_params = array();
    //         $cond_params = array();
    //         $pagination = false;
    //         if (isset($_GET['filter'])) {
    //             $checked_filter = $this->input_control->CheckGETInput($_GET['filter']);
    //             if (!is_null($checked_filter)) {
    //                 switch ($checked_filter) {
    //                     case 'butun-urunler':
    //                         $filter_condition = 'all';
    //                         break;
    //                     case 'satistaki-urunler':
    //                         $filter_condition = 'item_insale=1';
    //                         break;
    //                     case 'satista-olmayan-urunler':
    //                         $filter_condition = 'item_insale=0';
    //                         break;
    //                     default:
    //                         $filter_condition = null;
    //                         break;
    //                 }
    //                 if (!empty($filter_condition)) {
    //                     if ($filter_condition != 'all') {
    //                         $get_with_param = true;
    //                         $this->web_data['filter'] = $checked_filter;
    //                         $cond_params['filter'] = $filter_condition;
    //                         $hidden_params[] = array('name' => 'filter', 'value' => $checked_filter);
    //                     }
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Items');
    //                     exit(0);
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Items');
    //                 exit(0);
    //             }
    //         }
    //         if (isset($_GET['search'])) {
    //             $checked_search = $this->input_control->CheckGETInput($_GET['search']);
    //             if (!is_null($checked_search) && strlen($checked_search) <= 200) {
    //                 $get_with_param = true;
    //                 $this->web_data['search'] = $checked_search;
    //                 $cond_params['search'] = $checked_search;
    //                 $hidden_params[] = array('name' => 'search', 'value' => $checked_search);
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Items');
    //                 exit(0);
    //             }
    //         }
    //         if (isset($_GET['max']) && isset($_GET['min'])) {
    //             if (is_numeric($_GET['min']) && is_numeric($_GET['max'])) {
    //                 $min_price = (int)$this->input_control->CheckGETInput($this->input_control->IsIntegerAndPositive($_GET['min']));
    //                 $max_price = (int)$this->input_control->CheckGETInput($this->input_control->IsIntegerAndPositive($_GET['max']));
    //                 if (!is_null($min_price) && !is_null($max_price) && ($min_price < $max_price)) {
    //                     $get_with_param = true;
    //                     $this->web_data['selected_min_price'] = $min_price;
    //                     $this->web_data['selected_max_price'] = $max_price;
    //                     $cond_params['price'] = array('min' => $min_price, 'max' => $max_price);
    //                     $hidden_params[] = array('name' => 'min', 'value' => $min_price);
    //                     $hidden_params[] = array('name' => 'max', 'value' => $max_price);
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Items');
    //                     exit(0);
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Items');
    //                 exit(0);
    //             }
    //         }
    //         if (isset($_GET['sort'])) {
    //             $checked_sort = $this->input_control->CheckGETInput($_GET['sort']);
    //             if (!is_null($checked_sort)) {
    //                 switch ($checked_sort) {
    //                     case 'isim-azalan':
    //                         $sort_for_sql = 'item_name DESC';
    //                         $sort_deg = 'name_0';
    //                         break;
    //                     case 'isim-artan':
    //                         $sort_for_sql = 'item_name ASC';
    //                         $sort_deg = 'name_1';
    //                         break;
    //                     case 'fiyat-azalan':
    //                         $sort_for_sql = 'item_price DESC';
    //                         $sort_deg = 'price_0';
    //                         break;
    //                     case 'fiyat-artan':
    //                         $sort_for_sql = 'item_price ASC';
    //                         $sort_deg = 'price_1';
    //                         break;
    //                     case 'indirimli-fiyat-azalan':
    //                         $sort_for_sql = 'item_discount_price DESC';
    //                         $sort_deg = 'discount_price_0';
    //                         break;
    //                     case 'indirimli-fiyat-artan':
    //                         $sort_for_sql = 'item_discount_price ASC';
    //                         $sort_deg = 'discount_price_1';
    //                         break;
    //                     case 'adet-azalan':
    //                         $sort_for_sql = 'item_total_number DESC';
    //                         $sort_deg = 'number_0';
    //                         break;
    //                     case 'adet-artan':
    //                         $sort_for_sql = 'item_total_number ASC';
    //                         $sort_deg = 'number_1';
    //                         break;
    //                     case 'tarih-azalan':
    //                         $sort_for_sql = 'item_date_added DESC';
    //                         $sort_deg = 'date_0';
    //                         break;
    //                     case 'tarih-artan':
    //                         $sort_for_sql = 'item_date_added ASC';
    //                         $sort_deg = 'date_1';
    //                         break;
    //                     default:
    //                         $sort_for_sql = null;
    //                         break;
    //                 }
    //                 if (!empty($sort_for_sql)) {
    //                     $get_with_param = true;
    //                     $this->web_data['sort'] = $checked_sort;
    //                     $this->web_data['sort_deg'] = $sort_deg;
    //                     $cond_params['sort'] = $sort_for_sql;
    //                     $hidden_params[] = array('name' => 'sort', 'value' => $checked_sort);
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Items');
    //                     exit(0);
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Items');
    //                 exit(0);
    //             }
    //         }
    //         if (isset($_GET['limit'])) {
    //             $checked_limit = $this->input_control->CheckGETInput($_GET['limit']);
    //             if (!is_null($checked_limit)) {
    //                 switch ($checked_limit) {
    //                     case '5':
    //                         $limit_for_sql = '5';
    //                         break;
    //                     case '10':
    //                         $limit_for_sql = '10';
    //                         break;
    //                     case '25':
    //                         $limit_for_sql = '25';
    //                         break;
    //                     case '50':
    //                         $limit_for_sql = '50';
    //                         break;
    //                     default:
    //                         $limit_for_sql = null;
    //                         break;
    //                 }
    //                 if (!is_null($limit_for_sql)) {
    //                     $get_with_param = true;
    //                     $this->web_data['limit'] = $limit_for_sql;
    //                     $cond_params['limit'] = $limit_for_sql;
    //                     $hidden_params[] = array('name' => 'limit', 'value' => $limit_for_sql);
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Items');
    //                     exit(0);
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Items');
    //                 exit(0);
    //             }
    //         }
    //         if (isset($_GET['page'])) {
    //             if (is_numeric($_GET['page'])) {
    //                 $checked_page = $this->input_control->CheckGETInput($this->input_control->IsIntegerAndPositive($_GET['page']));
    //                 if (!is_null($checked_page)) {
    //                     $get_with_param = true;
    //                     $pagination = true;
    //                     $this->web_data['page'] = $checked_page;
    //                     $hidden_params[] = array('name' => 'page', 'value' => $checked_page);
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Items');
    //                     exit(0);
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Items');
    //                 exit(0);
    //             }
    //         }
    //         if (empty($this->web_data['page'])) {
    //             $this->web_data['page'] = 1;
    //         }
    //         if ($get_with_param) {
    //             $where = '';
    //             $order = '';
    //             $limit = '';
    //             $data_for_sql = array();
    //             foreach ($cond_params as $key => $cond_param) {
    //                 switch ($key) {
    //                     case 'filter':
    //                         if (empty($where)) {
    //                             $where .= 'WHERE ' . $cond_param;
    //                         } else {
    //                             $where .= ' AND ' . $cond_param;
    //                         }
    //                         break;
    //                     case 'search':
    //                         if (empty($where)) {
    //                             $where .= 'WHERE (id LIKE ? OR item_name LIKE ? OR item_url LIKE ? OR item_price LIKE ? OR item_discount_price LIKE ? OR item_total_number LIKE ?)';
    //                         } else {
    //                             $where .= ' AND (id LIKE ? OR item_name LIKE ? OR item_url LIKE ? OR item_price LIKE ? OR item_discount_price LIKE ? OR item_total_number LIKE ?)';
    //                         }
    //                         for ($i = 0; $i < 6; $i++) {
    //                             $data_for_sql[] = '%' . $cond_param . '%';
    //                         }
    //                         break;
    //                     case 'price':
    //                         if (empty($where)) {
    //                             $where .= 'WHERE item_discount_price BETWEEN ? AND ?';
    //                         } else {
    //                             $where .= ' AND item_discount_price BETWEEN ? AND ?';
    //                         }
    //                         $data_for_sql[] = $cond_param['min'];
    //                         $data_for_sql[] = $cond_param['max'];
    //                         break;
    //                     case 'sort':
    //                         $order = 'ORDER BY ' . $cond_param;
    //                         break;
    //                     case 'limit':
    //                         $limit = 'LIMIT ' . $cond_param;
    //                         $limit_for_page = $cond_param;
    //                         break;
    //                 }
    //             }
    //             if (empty($order)) {
    //                 $order = 'ORDER BY item_date_added DESC';
    //             }
    //             if (empty($limit)) {
    //                 $limit = 'LIMIT 5';
    //                 $limit_for_page = 5;
    //                 $this->web_data['limit'] = 5;
    //             }
    //             if (!empty($where) && empty($data_for_sql)) {
    //                 $count = $this->ItemModel->CountItemsByCondition($where);
    //                 $this->web_data['total_page'] = ceil($count['COUNT(id)'] / $limit_for_page);
    //                 $items = $this->ItemModel->GetItemsByCondition($where . ' ' . $order . ' ' . $limit);
    //             } elseif (!empty($where) && !empty($data_for_sql)) {
    //                 $count = $this->ItemModel->CountItemsByConditionAndData($where, $data_for_sql);
    //                 $this->web_data['total_page'] = ceil($count['COUNT(id)'] / $limit_for_page);
    //                 if ($pagination) {
    //                     $limit .= ' OFFSET ?';
    //                     $limit_jump = ($this->web_data['page'] - 1) * $limit_for_page;
    //                     $data_for_sql[] = $limit_jump;
    //                 }
    //                 $items = $this->ItemModel->GetItemsByConditionAndData($where . ' ' . $order . ' ' . $limit, $data_for_sql);
    //             } else {
    //                 $count = $this->ItemModel->CountItems();
    //                 $this->web_data['total_page'] = ceil($count['COUNT(id)'] / $limit_for_page);
    //                 if ($pagination) {
    //                     $limit .= ' OFFSET ?';
    //                     $limit_jump = ($this->web_data['page'] - 1) * $limit_for_page;
    //                     $data_for_sql[] = $limit_jump;
    //                 }
    //                 if (!empty($data_for_sql)) {
    //                     $items = $this->ItemModel->GetItemsByConditionAndData($order . ' ' . $limit, $data_for_sql);
    //                 } else {
    //                     $items = $this->ItemModel->GetItemsByCondition($order . ' ' . $limit);
    //                 }
    //             }
    //             if (!empty($hidden_params)) {
    //                 $params_without_filter = '';
    //                 $params_without_search = '';
    //                 $params_without_minmax = '';
    //                 $params_without_sort = '';
    //                 $params_without_limit = '';
    //                 $params_search_link = '?';
    //                 $params_page_link = '?';
    //                 foreach ($hidden_params as $hidden_param) {
    //                     if ($hidden_param['name'] != 'filter' && $hidden_param['name'] != 'page') {
    //                         $params_without_filter .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
    //                     }
    //                     if ($hidden_param['name'] != 'search' && $hidden_param['name'] != 'page') {
    //                         $params_without_search .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
    //                         $params_search_link .= $hidden_param['name'] . '=' . $hidden_param['value'] . '&';
    //                     }
    //                     if ($hidden_param['name'] != 'max' && $hidden_param['name'] != 'min' && $hidden_param['name'] != 'page') {
    //                         $params_without_minmax .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
    //                     }
    //                     if ($hidden_param['name'] != 'sort') {
    //                         $params_without_sort .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
    //                     }
    //                     if (($hidden_param['name'] != 'limit') && $hidden_param['name'] != 'page') {
    //                         $params_without_limit .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
    //                     }
    //                     if ($hidden_param['name'] != 'page') {
    //                         $params_page_link .= $hidden_param['name'] . '=' . $hidden_param['value'] . '&';
    //                     }
    //                 }
    //                 $this->web_data['params_without_filter'] = $params_without_filter;
    //                 $this->web_data['params_without_search'] = $params_without_search;
    //                 $this->web_data['params_without_minmax'] = $params_without_minmax;
    //                 $this->web_data['params_without_sort'] = $params_without_sort;
    //                 $this->web_data['params_without_limit'] = $params_without_limit;
    //                 $this->web_data['params_search_link'] = $params_search_link;
    //                 $this->web_data['params_page_link'] = $params_page_link;
    //             }
    //         } else {
    //             $count = $this->ItemModel->CountItems();
    //             $this->web_data['total_page'] = ceil($count['COUNT(id)'] / 5);
    //             $items = $this->ItemModel->GetItemsByCondition('ORDER BY item_date_added DESC LIMIT 5');
    //         }
    //         if (!empty($items)) {
    //             $this->web_data['items'] = $this->input_control->FormatPriceAndGetFirstImageOfItems($items);
    //         } else {
    //             $this->web_data['notfound_item'] = true;
    //         }
    //         parent::GetView('Admin/Items', $this->web_data);
    //     } else {
    //         $this->session_control->KillSession();
    //         header('Location: ' . URL);
    //         exit(0);
    //     }
    // }
    // function ItemDetails(string $item_url = null)
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         $this->input_control->CheckUrl();
    //         $item_url = $this->input_control->CheckGETInput($item_url);
    //         if (!empty($item_url)) {
    //             $item = $this->ItemModel->GetItemByUrl($item_url);
    //             if (!empty($item)) {
    //                 $item = $this->input_control->GetItemImages($item);
    //                 if (!empty($item['item_images'])) {
    //                     $this->web_data['item'] = $item;
    //                 } else {
    //                     $this->web_data['notfound_item'] = true;
    //                 }
    //             } else {
    //                 $this->web_data['notfound_item'] = true;
    //             }
    //             // aynı-1
    //             $filters_main = $this->FilterModel->GetFiltersForAdminItem();
    //             if (!empty($filters_main)) {
    //                 $filters = array();
    //                 foreach ($filters_main as $filter_main) {
    //                     $filters_sub = $this->FilterModel->GetSubFiltersByFilterIdForAdminItem($filter_main['id']);
    //                     if (!empty($filters_sub)) {
    //                         $filters[$filter_main['filter_name']] = array(
    //                             'filters_sub' => $filters_sub,
    //                             'filter_type' => $filter_main['filter_type']
    //                         );
    //                     }
    //                 }
    //                 $this->web_data['filters'] = $filters;
    //             }
    //             // aynı-1
    //             parent::GetView('Admin/ItemDetails', $this->web_data);
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Items');
    //             exit(0);
    //         }
    //     } else {
    //         header('Location: ' . URL . '/404');
    //         exit(0);
    //     }
    // }
    // function ItemComments(string $item_url = null)
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         $this->input_control->CheckUrl(array('filter', 'search', 'sort', 'limit', 'page'));
    //         $checked_url = $this->input_control->CheckGETInput($item_url);
    //         if (!empty($checked_url)) {
    //             $item = $this->ItemModel->GetItemByUrlForComment($checked_url);
    //             if (!empty($item)) {
    //                 $this->web_data['item'] = $item;
    //                 $get_with_param = false;
    //                 $hidden_params = array();
    //                 $cond_params = array();
    //                 $pagination = false;
    //                 if (isset($_GET['filter'])) {
    //                     $checked_filter = $this->input_control->CheckGETInput($_GET['filter']);
    //                     if (!is_null($checked_filter)) {
    //                         switch ($checked_filter) {
    //                             case 'butun-yorumlar':
    //                                 $filter_condition = 'all';
    //                                 break;
    //                             case 'gorunur-yorumlar':
    //                                 $filter_condition = 'is_comment_approved=1';
    //                                 break;
    //                             case 'gorunur-olmayan-yorumlar':
    //                                 $filter_condition = 'is_comment_approved=0';
    //                                 break;
    //                             default:
    //                                 $filter_condition = null;
    //                                 break;
    //                         }
    //                         if (!empty($filter_condition)) {
    //                             if ($filter_condition != 'all') {
    //                                 $get_with_param = true;
    //                                 $this->web_data['filter'] = $checked_filter;
    //                                 $cond_params['filter'] = $filter_condition;
    //                                 $hidden_params[] = array('name' => 'filter', 'value' => $checked_filter);
    //                             }
    //                         } else {
    //                             header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
    //                             exit(0);
    //                         }
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
    //                         exit(0);
    //                     }
    //                 }
    //                 if (isset($_GET['search'])) {
    //                     $checked_search = $this->input_control->CheckGETInput($_GET['search']);
    //                     if (!is_null($checked_search) && strlen($checked_search) <= 200) {
    //                         $get_with_param = true;
    //                         $this->web_data['search'] = $checked_search;
    //                         $cond_params['search'] = $checked_search;
    //                         $hidden_params[] = array('name' => 'search', 'value' => $checked_search);
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
    //                         exit(0);
    //                     }
    //                 }
    //                 if (isset($_GET['sort'])) {
    //                     $checked_sort = $this->input_control->CheckGETInput($_GET['sort']);
    //                     if (!is_null($checked_sort)) {
    //                         switch ($checked_sort) {
    //                             case 'ekleme-tarihi-azalan':
    //                                 $sort_for_sql = 'comment_date_added DESC';
    //                                 $sort_deg = 'date_added_0';
    //                                 break;
    //                             case 'ekleme-tarihi-artan':
    //                                 $sort_for_sql = 'comment_date_added ASC';
    //                                 $sort_deg = 'date_added_1';
    //                                 break;
    //                             case 'guncelleme-tarihi-azalan':
    //                                 $sort_for_sql = 'comment_date_added DESC';
    //                                 $sort_deg = 'date_update_0';
    //                                 break;
    //                             case 'guncelleme-tarihi-artan':
    //                                 $sort_for_sql = 'comment_date_added ASC';
    //                                 $sort_deg = 'date_update_1';
    //                                 break;
    //                             default:
    //                                 $sort_for_sql = null;
    //                                 break;
    //                         }
    //                         if (!empty($sort_for_sql)) {
    //                             $get_with_param = true;
    //                             $this->web_data['sort'] = $checked_sort;
    //                             $this->web_data['sort_deg'] = $sort_deg;
    //                             $cond_params['sort'] = $sort_for_sql;
    //                             $hidden_params[] = array('name' => 'sort', 'value' => $checked_sort);
    //                         } else {
    //                             header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
    //                             exit(0);
    //                         }
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
    //                         exit(0);
    //                     }
    //                 }
    //                 if (isset($_GET['limit'])) {
    //                     $checked_limit = $this->input_control->CheckGETInput($_GET['limit']);
    //                     if (!is_null($checked_limit)) {
    //                         switch ($checked_limit) {
    //                             case '5':
    //                                 $limit_for_sql = '5';
    //                                 break;
    //                             case '10':
    //                                 $limit_for_sql = '10';
    //                                 break;
    //                             case '25':
    //                                 $limit_for_sql = '25';
    //                                 break;
    //                             case '50':
    //                                 $limit_for_sql = '50';
    //                                 break;
    //                             default:
    //                                 $limit_for_sql = null;
    //                                 break;
    //                         }
    //                         if (!is_null($limit_for_sql)) {
    //                             $get_with_param = true;
    //                             $this->web_data['limit'] = $limit_for_sql;
    //                             $cond_params['limit'] = $limit_for_sql;
    //                             $hidden_params[] = array('name' => 'limit', 'value' => $limit_for_sql);
    //                         } else {
    //                             header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
    //                             exit(0);
    //                         }
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
    //                         exit(0);
    //                     }
    //                 }
    //                 if (isset($_GET['page'])) {
    //                     if (is_numeric($_GET['page'])) {
    //                         $checked_page = $this->input_control->CheckGETInput($this->input_control->IsIntegerAndPositive($_GET['page']));
    //                         if (!is_null($checked_page)) {
    //                             $get_with_param = true;
    //                             $pagination = true;
    //                             $this->web_data['page'] = $checked_page;
    //                             $hidden_params[] = array('name' => 'page', 'value' => $checked_page);
    //                         } else {
    //                             header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
    //                             exit(0);
    //                         }
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/ItemComments/' . $checked_url);
    //                         exit(0);
    //                     }
    //                 }
    //                 if (empty($this->web_data['page'])) {
    //                     $this->web_data['page'] = 1;
    //                 }
    //                 if ($get_with_param) {
    //                     $where = 'WHERE (item_id=?) ';
    //                     $order = '';
    //                     $limit = '';
    //                     $data_for_sql = array($item['id']);
    //                     foreach ($cond_params as $key => $cond_param) {
    //                         switch ($key) {
    //                             case 'filter':
    //                                 $where .= ' AND (' . $cond_param . ')';
    //                                 break;
    //                             case 'search':
    //                                 $where .= ' AND (id LIKE ? OR user_id LIKE ? OR item_id LIKE ? OR comment LIKE ?)';
    //                                 for ($i = 0; $i < 4; $i++) {
    //                                     $data_for_sql[] = '%' . $cond_param . '%';
    //                                 }
    //                                 break;
    //                             case 'sort':
    //                                 $order = 'ORDER BY ' . $cond_param;
    //                                 break;
    //                             case 'limit':
    //                                 $limit = 'LIMIT ' . $cond_param;
    //                                 $limit_for_page = $cond_param;
    //                                 break;
    //                         }
    //                     }
    //                     if (empty($order)) {
    //                         $order = 'ORDER BY comment_date_added DESC';
    //                     }
    //                     if (empty($limit)) {
    //                         $limit = 'LIMIT 5';
    //                         $limit_for_page = 5;
    //                         $this->web_data['limit'] = 5;
    //                     }
    //                     $count = $this->ItemModel->CountCommentsByConditionAndData($where, $data_for_sql);
    //                     $this->web_data['total_comment'] = $count['COUNT(id)'];
    //                     $this->web_data['total_page'] = ceil($count['COUNT(id)'] / $limit_for_page);
    //                     if ($pagination) {
    //                         $limit .= ' OFFSET ?';
    //                         $limit_jump = ($this->web_data['page'] - 1) * $limit_for_page;
    //                         $data_for_sql[] = $limit_jump;
    //                     }
    //                     $comments = $this->ItemModel->GetCommentsByConditionAndData($where . ' ' . $order . ' ' . $limit, $data_for_sql);
    //                     if (!empty($hidden_params)) {
    //                         $params_without_filter = '';
    //                         $params_without_search = '';
    //                         $params_without_sort = '';
    //                         $params_without_limit = '';
    //                         $params_search_link = '?';
    //                         $params_page_link = '?';
    //                         foreach ($hidden_params as $hidden_param) {
    //                             if ($hidden_param['name'] != 'filter' && $hidden_param['name'] != 'page') {
    //                                 $params_without_filter .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
    //                             }
    //                             if ($hidden_param['name'] != 'search' && $hidden_param['name'] != 'page') {
    //                                 $params_without_search .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
    //                                 $params_search_link .= $hidden_param['name'] . '=' . $hidden_param['value'] . '&';
    //                             }
    //                             if ($hidden_param['name'] != 'sort') {
    //                                 $params_without_sort .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
    //                             }
    //                             if (($hidden_param['name'] != 'limit') && $hidden_param['name'] != 'page') {
    //                                 $params_without_limit .= '<input type="hidden" name="' . $hidden_param['name'] . '" value="' . $hidden_param['value'] . '">';
    //                             }
    //                             if ($hidden_param['name'] != 'page') {
    //                                 $params_page_link .= $hidden_param['name'] . '=' . $hidden_param['value'] . '&';
    //                             }
    //                         }
    //                         $this->web_data['params_without_filter'] = $params_without_filter;
    //                         $this->web_data['params_without_search'] = $params_without_search;
    //                         $this->web_data['params_without_sort'] = $params_without_sort;
    //                         $this->web_data['params_without_limit'] = $params_without_limit;
    //                         $this->web_data['params_search_link'] = $params_search_link;
    //                         $this->web_data['params_page_link'] = $params_page_link;
    //                     }
    //                 } else {
    //                     $count = $this->ItemModel->CountComments($item['id']);
    //                     $this->web_data['total_comment'] = $count['COUNT(id)'];
    //                     $this->web_data['total_page'] = ceil($count['COUNT(id)'] / 5);
    //                     $comments = $this->ItemModel->GetCommentsByConditionAndData('WHERE item_id=? ORDER BY comment_date_added DESC LIMIT 5', array($item['id']));
    //                 }
    //                 if (!empty($comments)) {
    //                     $users = array();
    //                     foreach ($comments as $comment) {
    //                         $comment_user = $this->UserModel->GetCommentUserByUserId($comment['user_id']);
    //                         if (!empty($comment_user)) {
    //                             $users[$comment['user_id']] = $comment_user;
    //                         }
    //                     }
    //                     $this->web_data['comments'] = $comments;
    //                     $this->web_data['comment_users'] = $users;
    //                 }
    //             } else {
    //                 $this->web_data['notfound_item'] = true;
    //             }
    //             parent::GetView('Admin/ItemComments', $this->web_data);
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Items');
    //             exit(0);
    //         }
    //     } else {
    //         header('Location: ' . URL . '/404');
    //         exit(0);
    //     }
    // }
    // function ItemCreate()
    // {
    //     // item_cart_id için strtr(sodium_bin2base64(random_bytes(2), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'A', '_' => '9'));
    //     $this->input_control->CheckUrl();
    //     // aynı-1
    //     $filters_main = $this->FilterModel->GetFiltersForAdminItem();
    //     if (!empty($filters_main)) {
    //         $filters = array();
    //         foreach ($filters_main as $filter_main) {
    //             $filters_sub = $this->FilterModel->GetSubFiltersByFilterIdForAdminItem($filter_main['id']);
    //             if (!empty($filters_sub)) {
    //                 $filters[$filter_main['filter_name']] = array(
    //                     'filters_sub' => $filters_sub,
    //                     'filter_type' => $filter_main['filter_type']
    //                 );
    //             }
    //         }
    //         $this->web_data['filters'] = $filters;
    //     }
    //     // aynı-1
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_item'])) {
    //         $posted_inputs = array(
    //             'item_name' => array('input' => isset($_POST['item_name']) ? $_POST['item_name'] : '', 'length' => 100, 'error_message' => 'Ürünün adı boş olamaz ve karakter sınırını geçemez', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_url' => array('input' => isset($_POST['item_name']) ? $_POST['item_name'] : '', 'length' => 100, 'error_message' => 'Ürünün adı boş olamaz ve karakter sınırını geçemez', 'control_method' => array('control_syntax' => 'IsString', 'control_extra' => array('GenerateUrl'))),
    //             'item_price' => array('input' => isset($_POST['item_price']) ? $_POST['item_price'] : '', 'length' => 9, 'error_message' => 'Ürün fiyatı boş, sıfır, negatif ve 9 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsFloatAndPositive')),
    //             'item_discount_price' => array('input' => isset($_POST['item_discount_price']) ? $_POST['item_discount_price'] : '', 'length' => 9, 'error_message' => 'Ürünün indirimli fiyatı boş, sıfır, negatif ve 9 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsFloatAndPositive')),
    //             'item_material' => array('input' => isset($_POST['item_material']) ? $_POST['item_material'] : '', 'length' => 25, 'error_message' => 'Ürünün materyali boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_fabric_type' => array('input' => isset($_POST['item_fabric_type']) ? $_POST['item_fabric_type'] : '', 'length' => 25, 'error_message' => 'Ürünün kumaş tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_model' => array('input' => isset($_POST['item_model']) ? $_POST['item_model'] : '', 'length' => 25, 'error_message' => 'Ürünün modeli boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_lapel' => array('input' => isset($_POST['item_lapel']) ? $_POST['item_lapel'] : '', 'length' => 25, 'error_message' => 'Ürünün yaka tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_thickness' => array('input' => isset($_POST['item_thickness']) ? $_POST['item_thickness'] : '', 'length' => 25, 'error_message' => 'Ürünün kalınlığı boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_pattern' => array('input' => isset($_POST['item_pattern']) ? $_POST['item_pattern'] : '', 'length' => 25, 'error_message' => 'Ürünün deseni boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_sleeve_type' => array('input' => isset($_POST['item_sleeve_type']) ? $_POST['item_sleeve_type'] : '', 'length' => 25, 'error_message' => 'Ürünün kol tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_sleeve_length' => array('input' => isset($_POST['item_sleeve_length']) ? $_POST['item_sleeve_length'] : '', 'length' => 25, 'error_message' => 'Ürünün kol uzunluğu boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_washing_style' => array('input' => isset($_POST['item_washing_style']) ? $_POST['item_washing_style'] : '', 'length' => 50, 'error_message' => 'Ürünün yıkama stili boş ve 50 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_model_size' => array('input' => isset($_POST['item_model_size']) ? $_POST['item_model_size'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin bedeni boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_model_height' => array('input' => isset($_POST['item_model_height']) ? $_POST['item_model_height'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin uzunluğu boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_model_weight' => array('input' => isset($_POST['item_model_weight']) ? $_POST['item_model_weight'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin ağırlığı boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_total_number' => array('input' => isset($_POST['item_total_number']) ? $_POST['item_total_number'] : '', 'length' => 7, 'error_message' => 'Ürünün toplam stok adedi boş ve 7 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsInteger')),
    //             'item_in_shipping' => array('input' => isset($_POST['item_in_shipping']) ? $_POST['item_in_shipping'] : '', 'length' => 2, 'error_message' => 'Ürünün kargo süresi boş ve 2 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsIntegerAndPositive'))
    //         );
    //         if (!empty($filters)) {
    //             $size_calculator = 0;
    //             foreach ($filters as $key => $filter) {
    //                 if ($filter['filter_type'] === 2) {
    //                     $key_for_db = str_replace(' ', '_', $key);
    //                     $posted_sub_filter = isset($_POST[$key_for_db]) ? $_POST[$key_for_db] : '';
    //                     $match = null;
    //                     foreach ($filter['filters_sub'] as $filter_sub) {
    //                         if ($filter_sub['filter_sub_name'] === $posted_sub_filter) {
    //                             $match = $filter_sub['id'];
    //                             break;
    //                         }
    //                     }
    //                     if (!empty($match)) {
    //                         $posted_inputs[$key_for_db] = array('input' => $match, 'length' => 50, 'error_message' => ucwords($key) . ' boş olamaz', 'control_method' => array('control_syntax' => 'IsString'));
    //                     } else {
    //                         $posted_inputs[$key_for_db] = array('input' => '', 'length' => 50, 'error_message' => ucwords($key) . ' boş olamaz', 'control_method' => array('control_syntax' => 'IsString'));
    //                         break;
    //                     }
    //                 } else {
    //                     foreach ($filter['filters_sub'] as $filter_sub) {
    //                         $key_for_db = str_replace(' ', '_', $key) . '_' . str_replace(' ', '_', $filter_sub['filter_sub_name']);
    //                         if ($filter['filter_type'] === 1) {
    //                             $posted_sub_filter = isset($_POST[$key_for_db]) ? (int)$_POST[$key_for_db] : '';
    //                             $posted_inputs[$key_for_db] = array('input' => $posted_sub_filter, 'length' => 7, 'error_message' => ucwords($key) . ' (' . strtoupper($filter_sub['filter_sub_name']) . ') boş ve 7 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsInteger'));
    //                             $key_for_size = SIZE_NAME . '_' . str_replace(' ', '_', $filter_sub['filter_sub_name']);
    //                             $posted_size = isset($_POST[$key_for_size]) ? (int)$_POST[$key_for_size] : 0;
    //                             $size_calculator += $posted_size;
    //                         }
    //                         if ($filter['filter_type'] === 3) {
    //                             $posted_sub_filter = isset($_POST[$key_for_db]) ? 1 : 0;
    //                             $posted_inputs[$key_for_db] = array('input' => $posted_sub_filter, 'length' => 1, 'error_message' => ucwords($key) . ' (' . ucwords($filter_sub['filter_sub_name']) . ') boş olamaz', 'control_method' => array('control_syntax' => 'IsInteger'));
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         $error = false;
    //         if (empty($checked_inputs['error_message'])) {
    //             if (!empty($size_calculator) && $size_calculator !== (int)$checked_inputs['item_total_number']) {
    //                 $error = true;
    //                 $this->notification_control->SetNotification('DANGER', 'Beden stok adetleri ile toplam stok adedi uyuşmuyor');
    //             } elseif (isset($_FILES['item_image'])) {
    //                 $item_images = $_FILES['item_image'];
    //                 $image_tmp_names = array();
    //                 $image_names = array();
    //                 if (count($item_images['tmp_name']) >= 3 && count($item_images['tmp_name']) <= 6) {
    //                     for ($i = 0; $i < count($item_images['tmp_name']); $i++) {
    //                         if ($item_images['error'][$i] == 0) {
    //                             if ($item_images['size'][$i] <= (1024 * 1024 * ITEM_IMAGE_MAX_SIZE_MB)) {
    //                                 $accepted_image_types = ITEM_IMAGE_ACCEPTED_TYPES;
    //                                 if (in_array($item_images['type'][$i], $accepted_image_types)) {
    //                                     $image_names[] = explode('.', $item_images['name'][$i]);
    //                                     $image_tmp_names[] = $item_images['tmp_name'][$i];
    //                                 } else {
    //                                     $error = true;
    //                                     $this->web_data['image_error_message'] = 'Görselin uzantısı desteklenmiyor (Görsel ' . ($i + 1) . ')' . ' (Desteklenen uzantılar: ' . ITEM_IMAGE_ACCEPTED_TYPES_STR . ')';
    //                                     $this->notification_control->SetNotification('DANGER', 'Görselin uzantısı desteklenmiyor (Görsel ' . ($i + 1) . ')' . ' (Desteklenen uzantılar: ' . ITEM_IMAGE_ACCEPTED_TYPES_STR . ')');
    //                                     break;
    //                                 }
    //                             } else {
    //                                 $error = true;
    //                                 $this->web_data['image_error_message'] = 'Görselin boyutu ' . ITEM_IMAGE_MAX_SIZE_MB . ' MB dan fazla olamaz (Görsel ' . ($i + 1) . ')';
    //                                 $this->notification_control->SetNotification('DANGER', 'Görselin boyutu ' . ITEM_IMAGE_MAX_SIZE_MB . ' MB dan fazla olamaz (Görsel ' . ($i + 1) . ')');
    //                                 break;
    //                             }
    //                         } else {
    //                             $error = true;
    //                             $this->web_data['image_error_message'] = 'Görsel yüklenirken bir hata oldu (Görsel ' . ($i + 1) . ')';
    //                             $this->notification_control->SetNotification('DANGER', 'Görsel yüklenirken bir hata oldu (Görsel ' . ($i + 1) . ')');
    //                             break;
    //                         }
    //                     }
    //                 } else {
    //                     $error = true;
    //                     $this->web_data['image_error_message'] = 'En az 3 adet en fazla 6 adet ürün görseli yükleyebilirsiniz';
    //                     $this->notification_control->SetNotification('DANGER', 'En az 3 adet ürün görseli yükleyin');
    //                 }
    //                 if (!$error) {
    //                     $id = strtolower(substr(strtr(base64_encode(hash_hmac('SHA512', time(), base64_encode(random_bytes(128)), true)), array('+' => '1', '=' => 'j', '/' => 'm', '.' => 's', '_' => 'b')), 30, 50));
    //                     $new_image_folder = UPLOAD_IMAGES_PATH . $id;
    //                     if (!is_dir($new_image_folder)) {
    //                         if (mkdir($new_image_folder, 0777, true)) {
    //                             $item_images_db = '';
    //                             for ($i = 0; $i < count($image_tmp_names); $i++) {
    //                                 $image_random_name = strtolower(substr(strtr(base64_encode(hash_hmac('SHA512', time(), base64_encode(random_bytes(128)), true)), array('+' => '3', '=' => '5', '/' => '7', '.' => '4', '_' => '9')), 21, 10));
    //                                 $width = IMAGE_ORI_WIDTH_SIZE;
    //                                 $height = IMAGE_ORI_HEIGHT_SIZE;
    //                                 $width_mini = IMAGE_MINI_WIDTH_SIZE;
    //                                 $height_mini = IMAGE_MINI_HEIGHT_SIZE;
    //                                 $dst_image = imagecreatetruecolor($width, $height);
    //                                 $dst_image_mini = imagecreatetruecolor($width_mini, $height_mini);
    //                                 $image_infos = getimagesize($image_tmp_names[$i]);
    //                                 $image_width = $image_infos[0];
    //                                 $image_height = $image_infos[1];
    //                                 $uploadImageType = $image_infos[2];
    //                                 if ($uploadImageType == 2) {
    //                                     $src_image = imagecreatefromjpeg($image_tmp_names[$i]);
    //                                     imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
    //                                     imagejpeg($dst_image, $new_image_folder . '/' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 100);
    //                                     imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $width_mini, $height_mini, $image_width, $image_height);
    //                                     imagejpeg($dst_image_mini, $new_image_folder . '/' . 'mini' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 100);
    //                                     imagedestroy($src_image);
    //                                 } elseif ($uploadImageType == 3) {
    //                                     $src_image = imagecreatefrompng($image_tmp_names[$i]);
    //                                     imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
    //                                     imagepng($dst_image, $new_image_folder . '/' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 9);
    //                                     imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $width_mini, $height_mini, $image_width, $image_height);
    //                                     imagepng($dst_image_mini, $new_image_folder . '/' . 'mini' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 9);
    //                                     imagedestroy($src_image);
    //                                 }
    //                                 $item_images_db .= ($i + 1) . '-' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1] . '_';
    //                             }
    //                             $checked_inputs['item_images'] = rtrim($item_images_db, '_');
    //                             $checked_inputs['item_ishome'] = isset($_POST['item_ishome']) ? 1 : 0;
    //                             $checked_inputs['item_insale'] = isset($_POST['item_insale']) ? 1 : 0;
    //                             $checked_inputs['id'] = $id;
    //                             $result = $this->ItemModel->CreateItem($checked_inputs);
    //                             if ($result['result'] == 'Created') {
    //                                 $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['item_name']) . ' başarıyla eklendi');
    //                             } else {
    //                                 $this->notification_control->SetNotification('DANGER', 'Ürün ekleme başarısız');
    //                             }
    //                             header('Location: ' . URL . '/AdminController/Items');
    //                         } else {
    //                             $error = true;
    //                             $data['image_error_message'] = 'Ürün görselleri yüklenirken bir hata oldu';
    //                             $this->notification_control->SetNotification('DANGER', 'Ürün görselleri yüklenirken bir hata oldu');
    //                         }
    //                     } else {
    //                         $error = true;
    //                         $this->web_data['image_error_message'] = 'Ürün görselleri yüklenirken bir hata oldu';
    //                         $this->notification_control->SetNotification('DANGER', 'Ürün görselleri yüklenirken bir hata oldu');
    //                     }
    //                 }
    //             } else {
    //                 $error = true;
    //                 $this->web_data['image_error_message'] = 'En az 3 adet ürün görseli yükleyin';
    //                 $this->notification_control->SetNotification('DANGER', 'En az 3 adet ürün görseli yükleyin');
    //             }
    //         } else {
    //             $error = true;
    //             $this->web_data['error_input'] = $checked_inputs['error_input'];
    //             $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
    //         }
    //         if ($error) {
    //             $inputs = array();
    //             foreach ($posted_inputs as $key => $posted_input) {
    //                 $inputs[$key] = $posted_input['input'];
    //             }
    //             $this->web_data['item'] = $inputs;
    //             parent::GetView('Admin/ItemCreate', $this->web_data);
    //         }
    //     } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         parent::GetView('Admin/ItemCreate', $this->web_data);
    //     } else {
    //         $this->session_control->KillSession();
    //         header('Location: ' . URL);
    //         exit(0);
    //     }
    // }
    // function ItemUpdate()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    //         // aynı-1
    //         $filters_main = $this->FilterModel->GetFiltersForAdminItem();
    //         if (!empty($filters_main)) {
    //             $filters = array();
    //             foreach ($filters_main as $filter_main) {
    //                 $filters_sub = $this->FilterModel->GetSubFiltersByFilterIdForAdminItem($filter_main['id']);
    //                 if (!empty($filters_sub)) {
    //                     $filters[$filter_main['filter_name']] = array(
    //                         'filters_sub' => $filters_sub,
    //                         'filter_type' => $filter_main['filter_type']
    //                     );
    //                 }
    //             }
    //             $this->web_data['filters'] = $filters;
    //         }
    //         // aynı-1
    //         $posted_inputs = array(
    //             'id' => array('input' => isset($_POST['id']) ? $_POST['id'] : '', 'length' => 50, 'error_message' => "Bir hata oldu", 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_name' => array('input' => isset($_POST['item_name']) ? $_POST['item_name'] : '', 'length' => 100, 'error_message' => 'Ürünün adı boş olamaz ve karakter sınırını geçemez', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_url' => array('input' => isset($_POST['item_name']) ? $_POST['item_name'] : '', 'length' => 100, 'error_message' => 'Ürünün adı boş olamaz ve karakter sınırını geçemez', 'control_method' => array('control_syntax' => 'IsString', 'control_extra' => array('GenerateUrl'))),
    //             'item_price' => array('input' => isset($_POST['item_price']) ? $_POST['item_price'] : '', 'length' => 9, 'error_message' => 'Ürün fiyatı boş, sıfır, negatif ve 9 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsFloatAndPositive')),
    //             'item_discount_price' => array('input' => isset($_POST['item_discount_price']) ? $_POST['item_discount_price'] : '', 'length' => 9, 'error_message' => 'Ürünün indirimli fiyatı boş, sıfır, negatif ve 9 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsFloatAndPositive')),
    //             'item_material' => array('input' => isset($_POST['item_material']) ? $_POST['item_material'] : '', 'length' => 25, 'error_message' => 'Ürünün materyali boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_fabric_type' => array('input' => isset($_POST['item_fabric_type']) ? $_POST['item_fabric_type'] : '', 'length' => 25, 'error_message' => 'Ürünün kumaş tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_model' => array('input' => isset($_POST['item_model']) ? $_POST['item_model'] : '', 'length' => 25, 'error_message' => 'Ürünün modeli boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_lapel' => array('input' => isset($_POST['item_lapel']) ? $_POST['item_lapel'] : '', 'length' => 25, 'error_message' => 'Ürünün yaka tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_thickness' => array('input' => isset($_POST['item_thickness']) ? $_POST['item_thickness'] : '', 'length' => 25, 'error_message' => 'Ürünün kalınlığı boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_pattern' => array('input' => isset($_POST['item_pattern']) ? $_POST['item_pattern'] : '', 'length' => 25, 'error_message' => 'Ürünün deseni boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_sleeve_type' => array('input' => isset($_POST['item_sleeve_type']) ? $_POST['item_sleeve_type'] : '', 'length' => 25, 'error_message' => 'Ürünün kol tipi boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_sleeve_length' => array('input' => isset($_POST['item_sleeve_length']) ? $_POST['item_sleeve_length'] : '', 'length' => 25, 'error_message' => 'Ürünün kol uzunluğu boş ve 25 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_washing_style' => array('input' => isset($_POST['item_washing_style']) ? $_POST['item_washing_style'] : '', 'length' => 50, 'error_message' => 'Ürünün yıkama stili boş ve 50 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_model_size' => array('input' => isset($_POST['item_model_size']) ? $_POST['item_model_size'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin bedeni boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_model_height' => array('input' => isset($_POST['item_model_height']) ? $_POST['item_model_height'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin uzunluğu boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_model_weight' => array('input' => isset($_POST['item_model_weight']) ? $_POST['item_model_weight'] : '', 'length' => 3, 'error_message' => 'Ürün görselindeki modelin ağırlığı boş ve 3 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsString')),
    //             'item_total_number' => array('input' => isset($_POST['item_total_number']) ? $_POST['item_total_number'] : '', 'length' => 7, 'error_message' => 'Ürünün toplam stok adedi boş ve 7 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsInteger')),
    //             'item_in_shipping' => array('input' => isset($_POST['item_in_shipping']) ? $_POST['item_in_shipping'] : '', 'length' => 2, 'error_message' => 'Ürünün kargo süresi boş ve 2 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsIntegerAndPositive'))
    //         );
    //         if (!empty($filters)) {
    //             $size_calculator = 0;
    //             foreach ($filters as $key => $filter) {
    //                 if ($filter['filter_type'] === 2) {
    //                     $key_for_db = str_replace(' ', '_', $key);
    //                     $posted_sub_filter = isset($_POST[$key_for_db]) ? $_POST[$key_for_db] : '';
    //                     $match = null;
    //                     foreach ($filter['filters_sub'] as $filter_sub) {
    //                         if ($filter_sub['filter_sub_name'] === $posted_sub_filter) {
    //                             $match = $filter_sub['id'];
    //                             break;
    //                         }
    //                     }
    //                     if (!empty($match)) {
    //                         $posted_inputs[$key_for_db] = array('input' => $match, 'length' => 50, 'error_message' => ucwords($key) . ' boş olamaz', 'control_method' => array('control_syntax' => 'IsString'));
    //                     } else {
    //                         $posted_inputs[$key_for_db] = array('input' => '', 'length' => 50, 'error_message' => ucwords($key) . ' boş olamaz', 'control_method' => array('control_syntax' => 'IsString'));
    //                         break;
    //                     }
    //                 } else {
    //                     foreach ($filter['filters_sub'] as $filter_sub) {
    //                         $key_for_db = str_replace(' ', '_', $key) . '_' . str_replace(' ', '_', $filter_sub['filter_sub_name']);
    //                         if ($filter['filter_type'] === 1) {
    //                             $posted_sub_filter = isset($_POST[$key_for_db]) ? (int)$_POST[$key_for_db] : '';
    //                             $posted_inputs[$key_for_db] = array('input' => $posted_sub_filter, 'length' => 7, 'error_message' => ucwords($key) . ' (' . strtoupper($filter_sub['filter_sub_name']) . ') boş ve 7 karakterden fazla olamaz', 'control_method' => array('control_syntax' => 'IsInteger'));
    //                             $key_for_size = SIZE_NAME . '_' . str_replace(' ', '_', $filter_sub['filter_sub_name']);
    //                             $posted_size = isset($_POST[$key_for_size]) ? (int)$_POST[$key_for_size] : 0;
    //                             $size_calculator += $posted_size;
    //                         }
    //                         if ($filter['filter_type'] === 3) {
    //                             $posted_sub_filter = isset($_POST[$key_for_db]) ? 1 : 0;
    //                             $posted_inputs[$key_for_db] = array('input' => $posted_sub_filter, 'length' => 1, 'error_message' => ucwords($key) . ' (' . ucwords($filter_sub['filter_sub_name']) . ') boş olamaz', 'control_method' => array('control_syntax' => 'IsInteger'));
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         $error = false;
    //         if (empty($checked_inputs['error_message'])) {
    //             $image_setted = false;
    //             $image_setted_position = array();
    //             if (!empty($_FILES['item_image']['tmp_name'])) {
    //                 for ($i = 0; $i < count($_FILES['item_image']['tmp_name']); $i++) {
    //                     if (!empty($_FILES['item_image']['tmp_name'][$i])) {
    //                         $image_setted = true;
    //                         $image_setted_position[] = $i;
    //                     }
    //                 }
    //             }
    //             if (!empty($size_calculator) && $size_calculator !== (int)$checked_inputs['item_total_number']) {
    //                 $error = true;
    //                 $this->notification_control->SetNotification('DANGER', 'Beden stok adetleri ile toplam stok adedi uyuşmuyor');
    //             } elseif ($image_setted) {
    //                 $item_images = $_FILES['item_image'];
    //                 $image_tmp_names = array();
    //                 $image_names = array();
    //                 if (count($item_images['tmp_name']) >= 3 && count($item_images['tmp_name']) <= 6) {
    //                     for ($i = 0; $i < count($image_setted_position); $i++) {
    //                         $index = $image_setted_position[$i];
    //                         if ($item_images['error'][$index] == 0) {
    //                             if ($item_images['size'][$index] <= (1024 * 1024 * ITEM_IMAGE_MAX_SIZE_MB)) {
    //                                 $accepted_image_types = ITEM_IMAGE_ACCEPTED_TYPES;
    //                                 if (in_array($item_images['type'][$index], $accepted_image_types)) {
    //                                     $image_names[] = explode('.', $item_images['name'][$index]);
    //                                     $image_tmp_names[] = $item_images['tmp_name'][$index];
    //                                 } else {
    //                                     $error = true;
    //                                     $this->web_data['image_error_message'] = 'Görselin uzantısı desteklenmiyor (Görsel ' . ($index + 1) . ')' . ' (Desteklenen uzantılar: ' . ITEM_IMAGE_ACCEPTED_TYPES_STR . ')';
    //                                     $this->notification_control->SetNotification('DANGER', 'Görselin uzantısı desteklenmiyor (Görsel ' . ($index + 1) . ')' . ' (Desteklenen uzantılar: ' . ITEM_IMAGE_ACCEPTED_TYPES_STR . ')');
    //                                     break;
    //                                 }
    //                             } else {
    //                                 $error = true;
    //                                 $this->web_data['image_error_message'] = 'Görselin boyutu ' . ITEM_IMAGE_MAX_SIZE_MB . ' MB dan fazla olamaz (Görsel ' . ($index + 1) . ')';
    //                                 $this->notification_control->SetNotification('DANGER', 'Görselin boyutu ' . ITEM_IMAGE_MAX_SIZE_MB . ' MB dan fazla olamaz (Görsel ' . ($index + 1) . ')');
    //                                 break;
    //                             }
    //                         } else {
    //                             $error = true;
    //                             $this->web_data['image_error_message'] = 'Görsel yüklenirken bir hata oldu (Görsel ' . ($index + 1) . ')';
    //                             $this->notification_control->SetNotification('DANGER', 'Görsel yüklenirken bir hata oldu (Görsel ' . ($index + 1) . ')');
    //                             break;
    //                         }
    //                     }
    //                 } else {
    //                     $error = true;
    //                     $this->web_data['image_error_message'] = 'En az 3 adet en fazla 6 adet ürün görseli yükleyebilirsiniz';
    //                     $this->notification_control->SetNotification('DANGER', 'En az 3 adet ürün görseli yükleyin');
    //                 }
    //                 if (!$error) {
    //                     $image_folder = UPLOAD_IMAGES_PATH . $checked_inputs['id'];
    //                     if (is_dir($image_folder)) {
    //                         $files = glob($image_folder . '/*');
    //                         $item_images_db = '';
    //                         $item_images_db_passed = 0;
    //                         for ($i = 0; $i < count($image_tmp_names); $i++) {
    //                             $image_random_name = strtolower(substr(strtr(base64_encode(hash_hmac('SHA512', time(), base64_encode(random_bytes(128)), true)), array('+' => '3', '=' => '5', '/' => '7', '.' => '4', '_' => '9')), 21, 10));
    //                             $item_images_from_db = $this->ItemModel->GetItemImagesForAdminUpdate($checked_inputs['id']);
    //                             if (!empty($item_images_from_db)) {
    //                                 $checked_item_images = $this->input_control->GetItemImages($item_images_from_db);
    //                                 $index = $image_setted_position[$i] + 1;
    //                                 if ($index < count($checked_item_images['item_images']) + 1) {
    //                                     foreach ($checked_item_images['item_images'] as $item_image) {
    //                                         if ($item_images_db_passed >= $item_image[0]) {
    //                                             continue;
    //                                         }
    //                                         if ($item_image[0] == $index) {
    //                                             foreach ($files as $file) {
    //                                                 if (is_file($file) && ($file == $image_folder . '/' . $item_image[1] || $file == $image_folder . '/mini' . $item_image[1])) {
    //                                                     unlink($file);
    //                                                 }
    //                                             }
    //                                             $item_image[1] = $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1];
    //                                             $item_images_db .= $item_image[0] . '-' . $item_image[1] . '_';
    //                                         } elseif ($item_image[0] < $index) {
    //                                             $item_images_db .= $item_image[0] . '-' . $item_image[1] . '_';
    //                                         } elseif ((($i + 1) == count($image_tmp_names)) && ($item_image[0] > $index)) {
    //                                             $item_images_db .= $item_image[0] . '-' . $item_image[1] . '_';
    //                                         }
    //                                     }
    //                                     $item_images_db_passed = $index;
    //                                 } else {
    //                                     if (!empty($item_images_db)) {
    //                                         $item_images_db .= $index . '-' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1] . '_';
    //                                     } else {
    //                                         $item_images_db .= $item_images_from_db['item_images'] . '_' . $index . '-' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1] . '_';
    //                                     }
    //                                 }
    //                                 $width = IMAGE_ORI_WIDTH_SIZE;
    //                                 $height = IMAGE_ORI_HEIGHT_SIZE;
    //                                 $width_mini = IMAGE_MINI_WIDTH_SIZE;
    //                                 $height_mini = IMAGE_MINI_HEIGHT_SIZE;
    //                                 $dst_image = imagecreatetruecolor($width, $height);
    //                                 $dst_image_mini = imagecreatetruecolor($width_mini, $height_mini);
    //                                 $image_infos = getimagesize($image_tmp_names[$i]);
    //                                 $image_width = $image_infos[0];
    //                                 $image_height = $image_infos[1];
    //                                 $uploadImageType = $image_infos[2];
    //                                 if ($uploadImageType == 2) {
    //                                     $src_image = imagecreatefromjpeg($image_tmp_names[$i]);
    //                                     imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
    //                                     imagejpeg($dst_image, $image_folder . '/' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 100);
    //                                     imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $width_mini, $height_mini, $image_width, $image_height);
    //                                     imagejpeg($dst_image_mini, $image_folder . '/' . 'mini' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 100);
    //                                     imagedestroy($src_image);
    //                                 } elseif ($uploadImageType == 3) {
    //                                     $src_image = imagecreatefrompng($image_tmp_names[$i]);
    //                                     imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
    //                                     imagepng($dst_image, $image_folder . '/' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 9);
    //                                     imagecopyresampled($dst_image_mini, $src_image, 0, 0, 0, 0, $width_mini, $height_mini, $image_width, $image_height);
    //                                     imagepng($dst_image_mini, $image_folder . '/' . 'mini' . $image_random_name . '.' . $image_names[$i][count($image_names[$i]) - 1], 9);
    //                                     imagedestroy($src_image);
    //                                 }
    //                             } else {
    //                                 $error = true;
    //                                 $this->web_data['image_error_message'] = 'Ürün görselleri yüklenirken bir hata oldu';
    //                                 $this->notification_control->SetNotification('DANGER', 'Ürün görselleri yüklenirken bir hata oldu');
    //                             }
    //                         }
    //                         $checked_inputs['item_images'] = rtrim($item_images_db, '_');
    //                         $checked_inputs['item_ishome'] = isset($_POST['item_ishome']) ? 1 : 0;
    //                         $checked_inputs['item_insale'] = isset($_POST['item_insale']) ? 1 : 0;
    //                         $temp_id = $checked_inputs['id'];
    //                         unset($checked_inputs['id']);
    //                         $checked_inputs['id'] = $temp_id;
    //                         $result = $this->ItemModel->UpdateItem($checked_inputs);
    //                         if ($result == 'Updated') {
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['item_name']) . ' başarıyla güncellendi');
    //                         } else {
    //                             $this->notification_control->SetNotification('DANGER', 'Ürün güncelleme başarısız');
    //                         }
    //                         header('Location: ' . URL . '/AdminController/Items');
    //                     } else {
    //                         $error = true;
    //                         $this->web_data['image_error_message'] = 'Ürün görselleri yüklenirken bir hata oldu';
    //                         $this->notification_control->SetNotification('DANGER', 'Ürün görselleri yüklenirken bir hata oldu');
    //                     }
    //                 }
    //             } else {
    //                 $checked_inputs['item_ishome'] = isset($_POST['item_ishome']) ? 1 : 0;
    //                 $checked_inputs['item_insale'] = isset($_POST['item_insale']) ? 1 : 0;
    //                 $temp_id = $checked_inputs['id'];
    //                 unset($checked_inputs['id']);
    //                 $checked_inputs['id'] = $temp_id;
    //                 $result = $this->ItemModel->UpdateItem($checked_inputs);
    //                 if ($result == 'Updated') {
    //                     $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['item_name']) . ' başarıyla güncellendi');
    //                 } else {
    //                     $this->notification_control->SetNotification('DANGER', 'Ürün güncelleme başarısız');
    //                 }
    //                 header('Location: ' . URL . '/AdminController/Items');
    //                 exit(0);
    //             }
    //         } else {
    //             $error = true;
    //             $this->web_data['error_input'] = $checked_inputs['error_input'];
    //             $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
    //         }
    //         if ($error) {
    //             if ((!empty($checked_inputs['error_input']) && ($checked_inputs['error_input'] != 'id')) || (!empty($posted_inputs['id']['input']) && (strlen($posted_inputs['id']['input'] != 50)))) {
    //                 $item = $this->ItemModel->GetItemForAdminUpdate($posted_inputs['id']['input']);
    //                 if (!empty($item)) {
    //                     $inputs = array();
    //                     foreach ($posted_inputs as $key => $posted_input) {
    //                         $inputs[$key] = $posted_input['input'];
    //                     }
    //                     $inputs['item_ishome'] = isset($_POST['item_ishome']) ? 1 : 0;
    //                     $inputs['item_insale'] = isset($_POST['item_insale']) ? 1 : 0;
    //                     $inputs['item_date_added'] = $item['item_date_added'];
    //                     $inputs['item_date_updated'] = $item['item_date_updated'];
    //                     $item = $this->input_control->GetItemImages($item);
    //                     if (!is_null($item['item_images'])) {
    //                         $inputs['item_images'] = $item['item_images'];
    //                     } else {
    //                         $this->web_data['notfound_item'] = $this->notification_control->NotFound("Ürün Bulunamadı");
    //                     }
    //                     $this->web_data['item'] = $inputs;
    //                     $comments = $this->ItemModel->GetCommentsByItemId($posted_inputs['id']['input']);
    //                     if (!empty($comments)) {
    //                         $users = array();
    //                         foreach ($comments as $comment) {
    //                             $comment_user = $this->UserModel->GetCommentUserByUserId($comment['user_id']);
    //                             if (!empty($comment_user)) {
    //                                 $users[$comment['user_id']] = $comment_user;
    //                             }
    //                         }
    //                         $this->web_data['comments'] = $comments;
    //                         $this->web_data['comment_users'] = $users;
    //                     }
    //                 } else {
    //                     $this->web_data['notfound_item'] = true;
    //                 }
    //             } else {
    //                 $this->web_data['notfound_item'] = true;
    //             }
    //             parent::GetView('Admin/ItemDetails', $this->web_data);
    //         }
    //     } else {
    //         $this->session_control->KillSession();
    //         header('Location: ' . URL);
    //         exit(0);
    //     }
    // }
    // function ItemDelete()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    //         $item_id = $this->input_control->IsString(isset($_POST['item_id']) ? $_POST['item_id'] : '');
    //         if (!is_null($item_id)) {
    //             $checked_id = $this->input_control->PreventXSS($item_id);
    //             $result = $this->ItemModel->DeleteItem($checked_id);
    //             if ($result == 'Deleted') {
    //                 $image_folder = UPLOAD_IMAGES_PATH . $checked_id;
    //                 $result_delete_images = false;
    //                 if (is_dir($image_folder)) {
    //                     $files = glob($image_folder . '/*');
    //                     foreach ($files as $file) {
    //                         unlink($file);
    //                     }
    //                     $result_delete_images = rmdir($image_folder);
    //                 }
    //                 if ($result_delete_images) {
    //                     $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', 'Ürün başarıyla silindi');
    //                 } else {
    //                     $this->notification_control->SetNotification('DANGER', 'Ürün başarıyla silindi. Ürün görselleri silme başarısız');
    //                 }
    //             } else {
    //                 $this->notification_control->SetNotification('DANGER', 'Ürün silme başarısız');
    //             }
    //             header('Location: ' . URL . '/AdminController/Items');
    //         } else {
    //             $this->session_control->KillSession();
    //             header('Location: ' . URL);
    //             exit(0);
    //         }
    //     } else {
    //         $this->session_control->KillSession();
    //         header('Location: ' . URL);
    //         exit(0);
    //     }
    // }

    // function Filters()
    // {
    //     $data = array();
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['s'])) {
    //         $search_input = $this->input_control->IsString($_GET['s']);
    //         if ($search_input !== null) {
    //             $checked_search = $this->input_control->CheckAndGenerateUrl($search_input);
    //             $data['search'] = $checked_search;
    //             $searched_filters = $this->FilterModel->SearchFilters($checked_search);
    //             !empty($searched_filters) ? $data['filters'] = $searched_filters : $data['notfound_search'] = $this->notification_control->NotFound('Aranılan Kriterde Filtre Bulunamadı');
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Filters');
    //         }
    //     } else {
    //         $filters = $this->FilterModel->GetFilters();
    //         !empty($filters) ? $data['filters'] = $filters : $data['notfound_filter'] = $this->notification_control->NotFound('Kayıtlı Filtre Yok');
    //     }
    //     parent::GetView('Admin/Filters', $data);
    // }
    // function FilterDetails(string $filter_url = null)
    // {
    //     $url_input = $this->input_control->IsString($filter_url);
    //     if ($url_input !== null) {
    //         $data = array();
    //         $filter = $this->FilterModel->GetFilterByUrl($this->input_control->CheckAndGenerateUrl($url_input));
    //         if (!empty($filter)) {
    //             $data['filter'] = $filter;
    //             $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
    //             !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
    //         } else {
    //             $data['notfound_filter'] = $this->notification_control->NotFound("Filtre Bulunamadı");
    //         }
    //         parent::GetView('Admin/FilterDetails', $data);
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Filters');
    //     }
    // }
    // function FilterCreate()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_filter'])) {
    //         $posted_filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : '';
    //         $posted_inputs = array(
    //             'filter_name' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Adı-50',
    //             'filter_url' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Url-100$GenerateUrl',
    //             'filter_type' => ($posted_filter_type == 1 || $posted_filter_type == 2 || $posted_filter_type == 3) ? $posted_filter_type . '|Filtre&Tipi' : '|Filtre&Tipi'
    //         );
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         if (empty($checked_inputs['input_error_name'])) {
    //             $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
    //             $data = array();
    //             $filter_names = $this->FilterModel->GetAllFilterWithName();
    //             $filter_name_match = true;
    //             if (!empty($filter_names)) {
    //                 foreach ($filter_names as $filter_name) {
    //                     if ($filter_name['filter_name'] === $checked_inputs['filter_name']) {
    //                         $filter_name_match = false;
    //                         break;
    //                     }
    //                 }
    //             }
    //             if ($filter_name_match) {
    //                 $data_redirect = array();
    //                 $result = $this->FilterModel->CreateFilter($checked_inputs);
    //                 if ($checked_inputs['filter_type'] == 2) {
    //                     $result2 = $this->FilterModel->CreateItemColumnCountable($checked_inputs['filter_name']);
    //                     $_SESSION[SESSION_NOTIFICATION_NAME] = ($result['result'] == 'Created' && $result2 == 'Column Created') ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Eklendi') : $this->notification_control->Danger('Filtre Ekleme Başarısız');
    //                 } else {
    //                     $_SESSION[SESSION_NOTIFICATION_NAME] = $result['result'] == 'Created' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Eklendi') : $this->notification_control->Danger('Filtre Ekleme Başarısız');
    //                 }
    //                 $filters = $this->FilterModel->GetFilters();
    //                 !empty($filters) ? $data_redirect['filters'] = $filters : $data_redirect['notfound_filter'] = $this->notification_control->NotFound('Kayıtlı Filtre Yok');
    //                 parent::GetView('Admin/Filters', $data_redirect);
    //             } else {
    //                 $data['filter'] = $checked_inputs;
    //                 $data['input_error_key'] = 'filter_duplicate';
    //                 $this->notification_control->SetNotification('DANGER', 'Filtre Adı Zaten Kayıtlı');
    //                 parent::GetView('Admin/FilterCreate', $data);
    //             }
    //         } else {
    //             $data['filter'] = $checked_inputs['input_datas'];
    //             $data['input_error_key'] = $checked_inputs['input_error_key'];
    //             $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
    //             parent::GetView('Admin/FilterCreate', $data);
    //         }
    //     } else {
    //         parent::GetView('Admin/FilterCreate');
    //     }
    // }
    // function FilterUpdate()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_filter'])) {
    //         $posted_filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : '';
    //         $posted_inputs = array(
    //             'filter_name' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Adı-50',
    //             'filter_url' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Url-100$GenerateUrl',
    //             'filter_type' => ($posted_filter_type == 1 || $posted_filter_type == 2 || $posted_filter_type == 3) ? $posted_filter_type . '|Filtre&Tipi' : '|Filtre&Tipi',
    //             'id' => (isset($_POST['id']) ? $_POST['id'] : '') . '|Filtre&ID'
    //         );
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         $data = array();
    //         if (empty($checked_inputs['input_error_name'])) {
    //             $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
    //             $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['id']);
    //             if (!empty($filter)) {
    //                 $filter_ori_type = $filter['filter_type'];
    //                 $data['filter'] = $filter;
    //                 $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
    //                 !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $this->notification_control->NotFound($filter['filter_name'] . ' Bulunamadı');
    //                 $filter_names = $this->FilterModel->GetAllFilterWithName();
    //                 $filter_name_match = true;
    //                 $filter_ori_name = $this->input_control->CheckPostedInputForDb($this->input_control->IsString(isset($_POST['filter_ori_name']) ? $_POST['filter_ori_name'] : ''));
    //                 if (!empty($filter_names)) {
    //                     foreach ($filter_names as $filter_name) {
    //                         if ($checked_inputs['filter_name'] == $filter_ori_name) {
    //                             break;
    //                         }
    //                         if ($filter_name['filter_name'] == $checked_inputs['filter_name']) {
    //                             $filter_name_match = false;
    //                             break;
    //                         }
    //                     }
    //                 }
    //                 if ($filter_name_match) {
    //                     $data_redirect = array();
    //                     $persmission = true;
    //                     foreach (CANT_DELETE_FILTER_IDS as $cant_delete_filter) {
    //                         if ($checked_inputs['id'] == $cant_delete_filter) {
    //                             $persmission = false;
    //                         }
    //                     }
    //                     if ($persmission) {
    //                         $result = $this->FilterModel->UpdateFilter($checked_inputs);
    //                         if ($filter_ori_type == 2 && $checked_inputs['filter_type'] == 2) {
    //                             $result2 = $this->FilterModel->DeleteItemColumn($filter_ori_name);
    //                             $result3 = $this->FilterModel->CreateItemColumnCountable($checked_inputs['filter_name']);
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Updated' && $result2 == 'Column Deleted' && $result3 == 'Column Created') ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger('Filtre Güncelleme Başarısız');
    //                         } elseif ($filter_ori_type == 2 && $checked_inputs['filter_type'] != 2) {
    //                             $result2 = $this->FilterModel->DeleteItemColumn($filter_ori_name);
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Updated' && $result2 == 'Column Deleted') ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger('Filtre Güncelleme Başarısız');
    //                         } elseif ($filter_ori_type != 2 && $checked_inputs['filter_type'] == 2) {
    //                             $result2 = $this->FilterModel->CreateItemColumnCountable($checked_inputs['filter_name']);
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Updated' && $result2 == 'Column Created') ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger('Filtre Güncelleme Başarısız');
    //                         } else {
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger(ucwords($checked_inputs['filter_name']) . ' Güncelleme Başarısız');
    //                         }
    //                         $filters = $this->FilterModel->GetFilters();
    //                         !empty($filters) ? $data_redirect['filters'] = $filters : $data_redirect['notfound_filter'] =  $this->notification_control->NotFound('Filtre Bulunamadı');
    //                         parent::GetView('Admin/Filters', $data_redirect);
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/Filters');
    //                     }
    //                 } else {
    //                     $data['filter'] = $filter;
    //                     $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
    //                     !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
    //                     $data['input_error_key'] = 'filter_duplicate';
    //                     $this->notification_control->SetNotification('DANGER', 'Filtre Adı Zaten Kayıtlı');
    //                     parent::GetView('Admin/FilterDetails', $data);
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Filters');
    //             }
    //         } else {
    //             $filter = $this->FilterModel->GetFilterById('*', $this->input_control->CheckPostedInputForDb($checked_inputs['input_datas']['id']));
    //             if (!empty($filter)) {
    //                 $data['filter'] = $filter;
    //                 $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
    //                 !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
    //                 $data['input_error_key'] = $checked_inputs['input_error_key'];
    //                 $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
    //                 parent::GetView('Admin/FilterDetails', $data);
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Filters');
    //             }
    //         }
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Filters');
    //     }
    // }
    // function FilterDelete()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_filter'])) {
    //         $input_id = $this->input_control->IsString(isset($_POST['filter_id']) ? $_POST['filter_id'] : '');
    //         if ($input_id !== null) {
    //             $data = array();
    //             $checked_id = $this->input_control->CheckPostedInputForDb($input_id);
    //             $persmission = true;
    //             foreach (CANT_DELETE_FILTER_IDS as $cant_delete_filter) {
    //                 if ($checked_id == $cant_delete_filter) {
    //                     $persmission = false;
    //                 }
    //             }
    //             if ($persmission) {
    //                 $filter = $this->FilterModel->GetFilterById('*', $checked_id);
    //                 $result = $this->FilterModel->DeleteFilter($checked_id);
    //                 if ($filter['filter_type'] == 2) {
    //                     $result2 = $this->FilterModel->DeleteItemColumn($filter['filter_name']);
    //                     $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Deleted' && $result2 == 'Column Deleted') ? $this->notification_control->SetNotification('SUCCESS', 'Filtre Başarıyla Silindi') : $this->notification_control->Danger('Filtre Silme Başarısız');
    //                 } else {
    //                     $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Deleted' ? $this->notification_control->SetNotification('SUCCESS', 'Filtre Başarıyla Silindi') : $this->notification_control->Danger('Filtre Silme Başarısız');
    //                 }
    //                 $filters = $this->FilterModel->GetFilters();
    //                 !empty($filters) ? $data['filters'] = $filters : $data['notfound_filter'] = $this->notification_control->NotFound('Kayıtlı Filtre Yok');
    //                 parent::GetView('Admin/Filters', $data);
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Filters');
    //             }
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Filters');
    //         }
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Filters');
    //     }
    // }

    // function FilterSubDetails(string $filter_sub_url = null)
    // {
    //     $url_input = $this->input_control->IsString($filter_sub_url);
    //     if ($url_input !== null) {
    //         $data = array();
    //         $filter_sub = $this->FilterModel->GetSubFilterByUrl($this->input_control->CheckAndGenerateUrl($url_input));
    //         if (!empty($filter_sub)) {
    //             $data['filter_sub'] = $filter_sub;
    //             $filter = $this->FilterModel->GetFilterById('*', $filter_sub['filter_id']);
    //             if (!empty($filter)) {
    //                 $data['filter'] = $filter;
    //                 $filter_type = $filter['filter_type'];
    //                 if ($filter_type == 2) {
    //                     $items = $this->ItemModel->GetItemBySubFilter($filter['filter_name'], $filter_sub['id']);
    //                 } elseif ($filter_type == 1 || $filter_type = 3) {
    //                     $sub_name_fordb = str_replace(' ', '_', $filter_sub['filter_sub_name']);
    //                     $items = $this->ItemModel->GetAllItemWithSubFilter($filter['filter_name'] . '_' . $sub_name_fordb);
    //                 }
    //                 !empty($items) ? $data['items'] = $items : $data['notfound_item'] = $this->notification_control->NotFound(ucwords($filter_sub['filter_sub_name']) . ' Filtresine Ait Ürün Yok');
    //             } else {
    //                 $data['notfound_filter'] = $this->notification_control->NotFound('Filtre Bulunamadı');
    //             }
    //         } else {
    //             $data['notfound_filter_sub'] = $this->notification_control->NotFound('Alt Filtre Bulunamadı');
    //         }
    //         parent::GetView('Admin/FilterSubDetails', $data);
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Filters');
    //     }
    // }
    // function FilterSubCreate()
    // {
    //     $data = array();
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_filter_sub'])) {
    //         $posted_inputs = array(
    //             'filter_id' => (isset($_POST['filter_id']) ? $_POST['filter_id'] : '') . '|Filtre&ID',
    //             'filter_sub_name' => (isset($_POST['filter_sub_name']) ? $_POST['filter_sub_name'] : '') . '|Adı-50',
    //             'filter_sub_url' => (isset($_POST['filter_sub_name']) ? $_POST['filter_sub_name'] : '') . '|Alt&Filtre&Url-100$GenerateUrl'
    //         );
    //         $posted_filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : '';
    //         $posted_inputs2 = array(
    //             'filter_type' => ($posted_filter_type == 1 || $posted_filter_type == 2 || $posted_filter_type == 3) ? $posted_filter_type . '|Filtre&Tipi' : '|Filtre&Tipi',
    //             'filter_name' => (isset($_POST['filter_name']) ? $_POST['filter_name'] : '') . '|Filtre&Adı'
    //         );
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         $checked_inputs2 = $this->input_control->CheckPostedInputs($posted_inputs2);
    //         if (empty($checked_inputs['input_error_name'])) {
    //             $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
    //             if (!empty($checked_inputs2['input_error_name'])) {
    //                 header('Location: ' . URL . '/AdminController/Filters');
    //             }
    //             $checked_inputs2 = $this->input_control->CheckPostedInputsForDb($checked_inputs2['input_datas']);
    //             $sub_filter_names = $this->FilterModel->GetAllSubFilterWithName();
    //             $filter_name_match = true;
    //             if (!empty($sub_filter_names)) {
    //                 foreach ($sub_filter_names as $sub_filter_name) {
    //                     if ($sub_filter_name['filter_sub_name'] === $checked_inputs['filter_sub_name']) {
    //                         $filter_name_match = false;
    //                         break;
    //                     }
    //                 }
    //             }
    //             if ($filter_name_match) {
    //                 $filter = $this->FilterModel->GetValidFilterById($checked_inputs['filter_id']);
    //                 if (!empty($filter)) {
    //                     if ($checked_inputs2['filter_type'] == 2) {
    //                         $result = $this->FilterModel->CreateFilterSub($checked_inputs);
    //                         if ($filter['filter_has_sub'] == 0) {
    //                             $result3 = $this->FilterModel->UpdateFilterValidation(array('filter_has_sub' => 1, 'id' => $checked_inputs['filter_id']));
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = ($result['result'] == 'Created' && $result3 == 'Updated') ? $this->notification_control->SetNotification('SUCCESS', $checked_inputs['filter_sub_name'] . ' Başarıyla Eklendi') : $this->notification_control->Danger($checked_inputs['filter_sub_name'] . ' Ekleme Başarısız');
    //                         }
    //                         if (empty($_SESSION[SESSION_NOTIFICATION_NAME])) {
    //                             $result['result'] == 'Created' ? $_SESSION[SESSION_NOTIFICATION_NAME] = $this->notification_control->SetNotification('SUCCESS', $checked_inputs['filter_sub_name'] . ' Başarıyla Eklendi') : $this->notification_control->Danger($checked_inputs['filter_sub_name'] . ' Ekleme Başarısız');
    //                         }
    //                     } elseif ($checked_inputs2['filter_type'] == 1 || $checked_inputs2['filter_type'] == 3) {
    //                         $result = $this->FilterModel->CreateFilterSub($checked_inputs);
    //                         $sub_name_fordb = str_replace(' ', '_', $checked_inputs['filter_sub_name']);
    //                         $result2 = $this->FilterModel->CreateItemColumnCountable($checked_inputs2['filter_name'] . '_' . $sub_name_fordb);
    //                         if ($filter['filter_has_sub'] == 0) {
    //                             $result3 = $this->FilterModel->UpdateFilterValidation(array('filter_has_sub' => 1, 'id' => $checked_inputs['filter_id']));
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = ($result['result'] == 'Created' && $result2 == 'Column Created' && $result3 == 'Updated') ? $this->notification_control->SetNotification('SUCCESS', $checked_inputs['filter_sub_name'] . ' Başarıyla Eklendi') : $this->notification_control->Danger($checked_inputs['filter_sub_name'] . ' Ekleme Başarısız');
    //                         }
    //                         $_SESSION[SESSION_NOTIFICATION_NAME] = ($result['result'] == 'Created' && $result2 == 'Column Created') ? $this->notification_control->SetNotification('SUCCESS', $checked_inputs['filter_sub_name'] . ' Başarıyla Eklendi') : $this->notification_control->Danger($checked_inputs['filter_sub_name'] . ' Ekleme Başarısız');
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/Filters');
    //                     }
    //                     $url_input = $this->input_control->IsString($this->input_control->GenerateUrl($checked_inputs2['filter_name']));
    //                     if ($url_input !== null) {
    //                         $filter = $this->FilterModel->GetFilterByUrl($this->input_control->CheckAndGenerateUrl($url_input));
    //                         if (!empty($filter)) {
    //                             $data['filter'] = $filter;
    //                             $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
    //                             !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
    //                         } else {
    //                             $data['notfound_filter'] = $this->notification_control->NotFound("Filtre Bulunamadı");
    //                         }
    //                         parent::GetView('Admin/FilterDetails', $data);
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/Filters');
    //                     }
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Filters');
    //                 }
    //             } else {
    //                 $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['filter_id']);
    //                 if (!empty($filter)) {
    //                     $data['filter'] = $filter;
    //                     $data['filter_sub'] = $checked_inputs;
    //                     $data['input_error_key'] = 'sub_filter_duplicate';
    //                     $this->notification_control->SetNotification('DANGER', ucwords($filter['filter_name']) . ' Filtre Adı Zaten Kayıtlı');
    //                     parent::GetView('Admin/FilterSubCreate', $data);
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Filters');
    //                 }
    //             }
    //         } else {
    //             $filter = $this->FilterModel->GetFilterById('*', $this->input_control->CheckPostedInputForDb($checked_inputs['input_datas']['filter_id']));
    //             if (!empty($filter)) {
    //                 $data['filter'] = $filter;
    //                 $data['filter_sub'] = $checked_inputs['input_datas'];
    //                 $data['input_error_key'] = $checked_inputs['input_error_key'];
    //                 $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger(ucwords($filter['filter_name']) . ' ' . $checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
    //                 parent::GetView('Admin/FilterSubCreate', $data);
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Filters');
    //             }
    //         }
    //     } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view_filter_sub'])) {
    //         $posted_id = $this->input_control->IsString(isset($_POST['filter_id']) ? $_POST['filter_id'] : '');
    //         if ($posted_id !== null) {
    //             $filter = $this->FilterModel->GetFilterById('*', $this->input_control->CheckPostedInputForDb($posted_id));
    //             if (!empty($filter)) {
    //                 $data['filter'] = $filter;
    //                 parent::GetView('Admin/FilterSubCreate', $data);
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Filters');
    //             }
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Filters');
    //         }
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Filters');
    //     }
    // }
    // function FilterSubUpdate()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_filter_sub'])) {
    //         $posted_inputs = array(
    //             'filter_id' => (isset($_POST['filter_id']) ? $_POST['filter_id'] : '') . '|Filtre&ID',
    //             'filter_sub_name' => (isset($_POST['filter_sub_name']) ? $_POST['filter_sub_name'] : '') . '|Alt&Filtre&Adı-50',
    //             'filter_sub_url' => (isset($_POST['filter_sub_name']) ? $_POST['filter_sub_name'] : '') . '|Alt&Filtre&Url-100$GenerateUrl',
    //             'id' => (isset($_POST['id']) ? $_POST['id'] : '') . '|Alt&Filtre&ID'
    //         );
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         $data = array();
    //         if (empty($checked_inputs['input_error_name'])) {
    //             $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
    //             $filter_sub_names = $this->FilterModel->GetAllSubFilterWithName();
    //             $filter_name_match = true;
    //             if (!empty($filter_sub_names)) {
    //                 foreach ($filter_sub_names as $filter_sub_name) {
    //                     if ($filter_sub_name['filter_sub_name'] === $checked_inputs['filter_sub_name']) {
    //                         $filter_name_match = false;
    //                         break;
    //                     }
    //                 }
    //             }
    //             if ($filter_name_match) {
    //                 $filter_sub = $this->FilterModel->GetFilterSubById($checked_inputs['id']);
    //                 $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['filter_id']);
    //                 $result = $this->FilterModel->UpdateFilterSub($checked_inputs);
    //                 if ($filter['filter_type'] == 1 || $filter['filter_type'] == 3) {
    //                     $sub_name_fordb = str_replace(' ', '_', $checked_inputs['filter_sub_name']);
    //                     $old_sub_name_fordb = str_replace(' ', '_', $filter_sub['filter_sub_name']);
    //                     $this->FilterModel->RenameItemColumn($filter['filter_name'] . '_' . $old_sub_name_fordb, $filter['filter_name'] . '_' . $sub_name_fordb);
    //                 }
    //                 $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['filter_sub_name']) . ' Başarıyla Güncellendi') : $this->notification_control->Danger('Alt Filtre Güncelleme Başarısız');
    //                 $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['filter_id']);
    //                 if (!empty($filter)) {
    //                     $data['filter'] = $filter;
    //                     $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
    //                     !empty($filters_sub) ? $data['filters_sub'] = $filters_sub : $data['notfound_filter_sub'] = $this->notification_control->NotFound('"' . ucwords($filter['filter_name']) . '" Filtresinin Alt Filtresi Yok');
    //                 } else {
    //                     $data['notfound_filter'] = $this->notification_control->NotFound("Filtre Bulunamadı");
    //                 }
    //                 parent::GetView('Admin/FilterDetails', $data);
    //             } else {
    //                 $filter = $this->FilterModel->GetFilterById('*', $checked_inputs['filter_id']);
    //                 $filter_sub = $this->FilterModel->GetFilterSubById($checked_inputs['id']);
    //                 if (!empty($filter) && !empty($filter_sub)) {
    //                     $data['filter'] = $filter;
    //                     $data['filter_sub'] = $filter_sub;
    //                     $filter_type = $filter['filter_type'];
    //                     if ($filter_type == 2) {
    //                         $items = $this->ItemModel->GetItemBySubFilter($filter['filter_name'], $filter_sub['filter_sub_name']);
    //                     } elseif ($filter_type == 1 || $filter_type = 3) {
    //                         $items = $this->ItemModel->GetAllItemWithSubFilter($filter['filter_name'] . '_' . $filter_sub['filter_sub_name']);
    //                     }
    //                     !empty($items) ? $data['items'] = $items : $data['notfound_item'] = $this->notification_control->NotFound('Alt Filtreye Ait Ürün Yok');
    //                     $data['input_error_key'] = 'sub_filter_duplicate';
    //                     $this->notification_control->SetNotification('DANGER', ucwords($filter['filter_name']) . ' Filtre Adı Zaten Kayıtlı');
    //                     parent::GetView('Admin/FilterSubDetails', $data);
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Filters');
    //                 }
    //             }
    //         } else {
    //             $filter = $this->FilterModel->GetFilterById('*', $this->input_control->CheckPostedInputForDb($checked_inputs['input_datas']['filter_id']));
    //             $filter_sub = $this->FilterModel->GetFilterSubById($checked_inputs['input_datas']['id']);
    //             if (!empty($filter) && !empty($filter_sub)) {
    //                 $data['filter'] = $filter;
    //                 $data['filter_sub'] = $filter_sub;
    //                 $filter_type = $filter['filter_type'];
    //                 if ($filter_type == 2) {
    //                     $items = $this->ItemModel->GetItemBySubFilter($filter['filter_name'], $filter_sub['filter_sub_name']);
    //                 } elseif ($filter_type == 1 || $filter_type = 3) {
    //                     $items = $this->ItemModel->GetAllItemWithSubFilter($filter['filter_name'] . '_' . $filter_sub['filter_sub_name']);
    //                 }
    //                 !empty($items) ? $data['items'] = $items : $data['notfound_item'] = $this->notification_control->NotFound('Alt Filtreye Ait Ürün Yok');
    //                 $data['input_error_key'] = $checked_inputs['input_error_key'];
    //                 $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger(ucwords($filter['filter_name']) . ' ' . $checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
    //                 parent::GetView('Admin/FilterSubDetails', $data);
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Filters');
    //             }
    //         }
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Filters');
    //     }
    // }
    // function FilterSubDelete()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_filter_sub'])) {
    //         $input_id = $this->input_control->IsString(isset($_POST['filter_sub_id']) ? $_POST['filter_sub_id'] : '');
    //         if ($input_id !== null) {
    //             $data = array();
    //             $checked_id = $this->input_control->CheckPostedInputForDb($input_id);
    //             $permission = true;
    //             foreach (CANT_DELETE_FILTER_SUB_IDS as $cant_delete_filtersub) {
    //                 if ($checked_id == $cant_delete_filtersub) {
    //                     $permission = false;
    //                     break;
    //                 }
    //             }
    //             if ($permission) {
    //                 $filter_sub = $this->FilterModel->GetFilterSubById($checked_id);
    //                 if (!empty($filter_sub)) {
    //                     $filter = $this->FilterModel->GetFilterById('*', $filter_sub['filter_id']);
    //                     if (!empty($filter)) {
    //                         $data['filter'] = $filter;
    //                         $result = $this->FilterModel->DeleteFilterSub($checked_id);
    //                         $filters_sub = $this->FilterModel->GetSubFiltersByFilterId('*', $filter['id']);
    //                         if (!empty($filters_sub)) {
    //                             $data['filters_sub'] = $filters_sub;
    //                         } else {
    //                             $data['notfound_filter_sub'] = $this->notification_control->NotFound('Kayıtlı Alt Filtre Yok');
    //                             $this->FilterModel->UpdateFilterHasNoSub(array('filter_has_sub' => 0, 'id' => $filter['id']));
    //                         }
    //                         if ($filter['filter_type'] == 2) {
    //                             $result2 = $this->FilterModel->EmptyItemColumn($filter['filter_name'], $filter_sub['id']);
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Deleted' && $result2 == 'Updated') ? $this->notification_control->SetNotification('SUCCESS', $filter_sub['filter_sub_name'] . ' Filtresi Başarıyla Silindi') : $this->notification_control->Danger($filter_sub['filter_sub_name'] . ' Filtre Silme Başarısız');
    //                         } elseif ($filter['filter_type'] == 1 || $filter['filter_type'] === 3) {
    //                             $sub_name_fordb = str_replace(' ', '_', $filter_sub['filter_sub_name']);
    //                             $result2 = $this->FilterModel->DeleteItemColumn($filter['filter_name'] . '_' . $sub_name_fordb);
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Deleted' && $result2 == 'Column Deleted') ? $this->notification_control->SetNotification('SUCCESS', $filter_sub['filter_sub_name'] . ' Filtresi Başarıyla Silindi') : $this->notification_control->Danger($filter_sub['filter_sub_name'] . ' Filtre Silme Başarısız');
    //                         } else {
    //                             header('Location: ' . URL . '/AdminController/Filters');
    //                         }
    //                         parent::GetView('Admin/FilterDetails', $data);
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/Filters');
    //                     }
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Filters');
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Filters');
    //             }
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Filters');
    //         }
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Filters');
    //     }
    // }

    // function Users()
    // {
    //     $data = array();
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['s'])) {
    //         $search_input = $this->input_control->IsString($_GET['s']);
    //         if ($search_input !== null) {
    //             $checked_search = $this->input_control->CheckAndGenerateUrl($search_input);
    //             $data['search'] = $checked_search;
    //             $searched_users = $this->UserModel->SearchUsers($checked_search);
    //             if (!empty($searched_users)) {
    //                 foreach ($searched_users as $key => $user) {
    //                     $searched_users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
    //                 }
    //                 $data['users'] = $searched_users;
    //             } else {
    //                 $data['notfound_search'] = $this->notification_control->NotFound('Aranılan Kriterde Kullanıcı Bulunamadı');
    //             }
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Users');
    //         }
    //     } else {
    //         $users = $this->UserModel->GetAllUser();
    //         if (!empty($users)) {
    //             foreach ($users as $key => $user) {
    //                 $users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
    //             }
    //             $data['users'] = $users;
    //         } else {
    //             $data['notfound_user'] = $this->notification_control->NotFound('Kayıtlı Ürün Yok');
    //         }
    //     }
    //     parent::GetView('Admin/Users', $data);
    // }
    // function UserDetails(string $user_id = null)
    // {
    //     $url_input = $this->input_control->IsString($user_id);
    //     if ($url_input !== null) {
    //         $data = array();
    //         $user = $this->UserModel->GetUserById('*', $this->input_control->CheckAndGenerateUrl($url_input));
    //         if (!empty($user)) {
    //             $user['email'] = $this->input_control->DecodeMail($user['email']);
    //             $data['user'] = $user;
    //             parent::GetModel('RoleModel');
    //             $data['roles'] = $this->RoleModel->GetRoleNamesAndId();
    //             $comments = $this->UserModel->GetCommentsByUserId($user['id']);
    //             if (!empty($comments)) {
    //                 $items = array();
    //                 foreach ($comments as $comment) {
    //                     $items[$comment['user_id']] = $this->ItemModel->GetItemNameAndUrlById($comment['item_id']);
    //                 }
    //                 $data['comments'] = $comments;
    //                 $data['items'] = $items;
    //             } else {
    //                 $data['notfound_comment'] = $this->notification_control->NotFound("Kullanıcıya Ait Yorum Yok");
    //             }
    //         } else {
    //             $data['notfound_user'] = $this->notification_control->NotFound("Kullanıcı Bulunamadı");
    //         }
    //         parent::GetView('Admin/UserDetails', $data);
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Users');
    //     }
    // }
    // function UserCreate()
    // {
    //     parent::GetModel('RoleModel');
    //     $roles = $this->RoleModel->GetRoleNamesAndId();
    //     if (!empty($roles)) {
    //         $data = array();
    //         $data['roles'] = $roles;
    //         if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    //             $posted_user_role = $this->input_control->IsString(isset($_POST['user_role']) ? $_POST['user_role'] : '');
    //             if ($posted_user_role !== null) {
    //                 $role_name_match = false;
    //                 foreach ($roles as $role) {
    //                     if ($role['role_name'] == $posted_user_role) {
    //                         $role_name_match = true;
    //                         break;
    //                     }
    //                 }
    //                 if ($role_name_match) {
    //                     $user_role = $role['id'];
    //                     $id = substr(hash('sha256', base64_encode('bKs=!Z1' . time() . 'b@9Alc_h1')), 10, 20);
    //                     $posted_inputs = array(
    //                         'id' => $id . '|Kullanıcı&ID',
    //                         'first_name' => (isset($_POST['first_name']) ? $_POST['first_name'] : '') . '|Ad-20',
    //                         'last_name' => (isset($_POST['last_name']) ? $_POST['last_name'] : '') . '|SoyAd-20',
    //                         'email' => (isset($_POST['email']) ? $_POST['email'] : '') . '|Email-345',
    //                         'email_confirmed' => (isset($_POST['email_confirmed']) ? '1' : '0') . '|Email&Doğrulama',
    //                         'tel' => (isset($_POST['tel']) ? $_POST['tel'] : '') . '|Telefon&Numarası-10',
    //                         'tel_confirmed' => (isset($_POST['tel_confirmed']) ? '1' : '0') . '|Telefon&Doğrulama',
    //                         'user_password' => $this->input_control->EncodePassword('BlanckBasic_123') . '|Şifre',
    //                         'user_role' => $user_role . '|Kullanıcı&Rolü'
    //                     );
    //                     $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //                     if (empty($checked_inputs['input_error_name'])) {
    //                         $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
    //                         $permission = true;
    //                         foreach (CANT_CREATE_USER_EMAILS as $cant_create_user) {
    //                             if ($checked_inputs['email'] == $cant_create_user) {
    //                                 $permission = false;
    //                                 break;
    //                             }
    //                         }
    //                         if ($permission) {
    //                             $users_emails = $this->UserModel->GetUsersEmails();
    //                             if (!empty($users_emails)) {
    //                                 $valid_mail = $this->input_control->CheckAndEncodeMail($checked_inputs['email']);
    //                                 if ($valid_mail !== null) {
    //                                     foreach ($users_emails as $user_email) {
    //                                         if ($user_email['email'] === $checked_inputs['email']) {
    //                                             $data['input_error_key'] = 'email_duplicate';
    //                                             $this->notification_control->SetNotification('DANGER', 'Email Zaten Kayıtlı');
    //                                             break;
    //                                         }
    //                                     }
    //                                 } else {
    //                                     $data['input_error_key'] = 'not_valid_email';
    //                                     $this->notification_control->SetNotification('DANGER', 'Email Geçerli Değil');
    //                                 }
    //                             }
    //                         } else {
    //                             $data['input_error_key'] = 'not_valid_email';
    //                             $this->notification_control->SetNotification('DANGER', 'Email Geçerli Değil');
    //                         }
    //                         if (empty($data['input_error_key'])) {
    //                             $users_tels = $this->UserModel->GetUsersPhoneNumbers();
    //                             $tel_duplicate = true;
    //                             if (!empty($users_tels)) {
    //                                 foreach ($users_tels as $user_tel) {
    //                                     if ($user_tel['tel'] === '90' . $checked_inputs['tel']) {
    //                                         $data['input_error_key'] = 'tel_duplicate';
    //                                         $this->notification_control->SetNotification('DANGER', 'Telefon Numarası Zaten Kayıtlı');
    //                                         $tel_duplicate = false;
    //                                         break;
    //                                     }
    //                                 }
    //                             }
    //                             if ($tel_duplicate && strlen($checked_inputs['tel']) != 10) {
    //                                 $data['input_error_key'] = 'tel';
    //                                 $this->notification_control->SetNotification('DANGER', 'Telefon Numarası Geçersiz');
    //                             }
    //                         }
    //                         if (empty($data['input_error_key'])) {
    //                             $new_image_folder = USER_IMAGES_PATH . $id;
    //                             if (!is_dir($new_image_folder)) {
    //                                 mkdir($new_image_folder, 0777, true);
    //                             }
    //                             $checked_inputs['tel'] = '90' . $checked_inputs['tel'];
    //                             $checked_inputs['email'] = $this->input_control->CheckAndEncodeMail($checked_inputs['email']);
    //                             $result = $this->UserModel->CreateUser($checked_inputs);
    //                             $data_redirect = array();
    //                             $_SESSION[SESSION_NOTIFICATION_NAME] = $result['result'] == 'Created' ? $this->notification_control->SetNotification('SUCCESS', 'Kullanıcı Başarıyla Eklendi') : $this->notification_control->Danger('Kullanıcı Ekleme Başarısız');
    //                             $users = $this->UserModel->GetAllUser();
    //                             foreach ($users as $key => $user) {
    //                                 $users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
    //                             }
    //                             !empty($users) ? $data_redirect['users'] = $users : $data_redirect['notfound_user'] = $this->notification_control->NotFound('Kayıtlı Kullanıcı Yok');
    //                             parent::GetView('Admin/Users', $data_redirect);
    //                         } else {
    //                             $data['user'] = $checked_inputs;
    //                             parent::GetView('Admin/UserCreate', $data);
    //                         }
    //                     } else {
    //                         $data['user'] = $checked_inputs['input_datas'];
    //                         $data['input_error_key'] = $checked_inputs['input_error_key'];
    //                         $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
    //                         parent::GetView('Admin/UserCreate', $data);
    //                     }
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Users');
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Users');
    //             }
    //         } else {
    //             parent::GetView('Admin/UserCreate', $data);
    //         }
    //     } else {
    //         parent::GetView('Admin/Users', array('notification' => $this->notification_control->Danger('Önce Bir Rol Eklemelsiniz')));
    //     }
    // }
    // function UserUpdate()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    //         parent::GetModel('RoleModel');
    //         $roles = $this->RoleModel->GetRoleNamesAndId();
    //         $posted_user_role = $this->input_control->IsString(isset($_POST['user_role']) ? $_POST['user_role'] : '');
    //         if ($posted_user_role !== null) {
    //             $role_name_match = false;
    //             foreach ($roles as $role) {
    //                 if ($role['role_name'] == $posted_user_role) {
    //                     $role_name_match = true;
    //                     break;
    //                 }
    //             }
    //             if ($role_name_match) {
    //                 $user_role = $role['id'];
    //                 $posted_inputs = array(
    //                     'user_role' => $user_role . '|Kullanıcı&Rolü',
    //                     'id' => (isset($_POST['id']) ? $_POST['id'] : '') . '|Kullanıcı&ID'
    //                 );
    //                 $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //                 if (empty($checked_inputs['input_error_name'])) {
    //                     $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
    //                     $data_redirect = array();
    //                     $result = $this->UserModel->UpdateUser($checked_inputs);
    //                     $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', 'Kullanıcı Başarıyla Güncellendi') : $this->notification_control->Danger('Kullanıcı Güncelleme Başarısız');
    //                     $users = $this->UserModel->GetAllUser();
    //                     foreach ($users as $key => $user) {
    //                         $users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
    //                     }
    //                     !empty($users) ? $data_redirect['users'] = $users : $data_redirect['notfound_user'] = $this->notification_control->NotFound('Kayıtlı Kullanıcı Yok');
    //                     parent::GetView('Admin/Users', $data_redirect);
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Users');
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Users');
    //             }
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Users');
    //         }
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Users');
    //     }
    // }
    // function UserDelete()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    //         $input_id = $this->input_control->IsString(isset($_POST['user_id']) ? $_POST['user_id'] : '');
    //         if ($input_id !== null) {
    //             $data = array();
    //             $checked_id = $this->input_control->CheckPostedInputForDb($input_id);
    //             $permission = true;
    //             foreach (CANT_DELETE_USER_IDS as $cant_delete_user) {
    //                 if ($checked_id == $cant_delete_user) {
    //                     $permission = false;
    //                     break;
    //                 }
    //             }
    //             if ($permission) {
    //                 $new_image_folder = USER_IMAGES_PATH . $checked_id;
    //                 if (!is_dir($new_image_folder)) {
    //                     rmdir($new_image_folder, 0777, true);
    //                 }
    //                 $result = $this->UserModel->DeleteUser($checked_id);
    //                 $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Deleted' ? $this->notification_control->SetNotification('SUCCESS', 'Kullanıcı Başarıyla Silindi') : $this->notification_control->Danger('Kullanıcı Silme Başarısız');
    //                 $users = $this->UserModel->GetAllUser();
    //                 if (!empty($users)) {
    //                     foreach ($users as $key => $user) {
    //                         $users[$key]['email'] = $this->input_control->DecodeMail($user['email']);
    //                     }
    //                     $data['users'] = $users;
    //                 } else {
    //                     $data['notfound_user'] = $this->notification_control->NotFound('Kayıtlı Ürün Yok');
    //                 }
    //                 parent::GetView('Admin/Users', $data);
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Users');
    //             }
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Users');
    //         }
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Users');
    //     }
    // }

    // function Roles()
    // {
    //     parent::GetModel('RoleModel');
    //     $data = array();
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['s'])) {
    //         $search_input = $this->input_control->IsString($_GET['s']);
    //         if ($search_input !== null) {
    //             $checked_search = $this->input_control->CheckAndGenerateUrl($search_input);
    //             $data['search'] = $checked_search;
    //             $searched_roles = $this->RoleModel->SearchRoles($checked_search);
    //             !empty($searched_roles) ? $data['roles'] = $searched_roles : $data['notfound_search'] = $this->notification_control->NotFound('Aranılan Kriterde Rol Bulunamadı');
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Items');
    //         }
    //     } else {
    //         $roles = $this->RoleModel->GetAllRole();
    //         !empty($roles) ? $data['roles'] = $roles : $data['notfound_role'] = $this->notification_control->NotFound('Kayıtlı Rol Yok');
    //     }
    //     parent::GetView('Admin/Roles', $data);
    // }
    // function RoleDetails(string $role_url = null)
    // {
    //     $url_input = $this->input_control->IsString($role_url);
    //     if ($url_input !== null) {
    //         parent::GetModel('RoleModel');
    //         $data = array();
    //         $role = $this->RoleModel->GetRoleByUrl($this->input_control->CheckAndGenerateUrl($url_input));
    //         if (!empty($role)) {
    //             $data['role'] = $role;
    //             $users_in_role = $this->UserModel->GetUsersByRole($role['id']);
    //             if (!empty($users_in_role)) {
    //                 foreach ($users_in_role as $key => $user) {
    //                     $users_in_role[$key]['email'] = $this->input_control->DecodeMail($user['email']);
    //                 }
    //                 $data['users'] = $users_in_role;
    //             } else {
    //                 $data['notfound_user'] = $this->notification_control->NotFound(ucwords($role['role_name']) . ' Rolüne Ait Kullanıcı Bulunamadı');
    //             }
    //         } else {
    //             $data['notfound_role'] = $this->notification_control->NotFound("Rol Bulunamadı");
    //         }
    //         parent::GetView('Admin/RoleDetails', $data);
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Roles');
    //     }
    // }
    // function RoleCreate()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_role'])) {
    //         $posted_inputs = array(
    //             'role_name' => (isset($_POST['role_name']) ? $_POST['role_name'] : '') . '|Rol&Adı-20',
    //             'role_url' => (isset($_POST['role_name']) ? $_POST['role_name'] : '') . '|Rol&Url-40$GenerateUrl'
    //         );
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         if (empty($checked_inputs['input_error_name'])) {
    //             $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
    //             parent::GetModel('RoleModel');
    //             $role_urls = $this->RoleModel->GetAllRoleUrl();
    //             $role_not_match = true;
    //             foreach ($role_urls as $role_url) {
    //                 if ($role_url['role_url'] == $checked_inputs['role_url']) {
    //                     $role_not_match = false;
    //                     break;
    //                 }
    //             }
    //             if ($role_not_match) {
    //                 $data_redirect = array();
    //                 $result = $this->RoleModel->CreateRole($checked_inputs);
    //                 $_SESSION[SESSION_NOTIFICATION_NAME] = $result['result'] == 'Created' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['role_name']) . ' Rolü Başarıyla Eklendi') : $this->notification_control->Danger('Rol Ekleme Başarısız');
    //                 $roles = $this->RoleModel->GetAllRole();
    //                 !empty($roles) ? $data_redirect['roles'] = $roles : $data_redirect['notfound_role'] = $this->notification_control->NotFound('Kayıtlı Rol Yok');
    //                 parent::GetView('Admin/Roles', $data_redirect);
    //             } else {
    //                 $data['role'] = $checked_inputs;
    //                 $data['input_error_key'] = 'role_duplicate';
    //                 $this->notification_control->SetNotification('DANGER', 'Rol Zaten Kayıtlı');
    //                 parent::GetView('Admin/RoleCreate', $data);
    //             }
    //         } else {
    //             $data['role'] = $checked_inputs['input_datas'];
    //             $data['input_error_key'] = $checked_inputs['input_error_key'];
    //             $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
    //             parent::GetView('Admin/RoleCreate', $data);
    //         }
    //     } else {
    //         parent::GetView('Admin/RoleCreate');
    //     }
    // }
    // function RoleUpdate()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    //         $posted_inputs = array(
    //             'role_name' => (isset($_POST['role_name']) ? $_POST['role_name'] : '') . '|Rol&Adı-20',
    //             'role_url' => (isset($_POST['role_name']) ? $_POST['role_name'] : '') . '|Rol&Url-40$GenerateUrl',
    //             'id' => (isset($_POST['id']) ? $_POST['id'] : '') . '|Rol&ID'
    //         );
    //         parent::GetModel('RoleModel');
    //         $data = array();
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         if (empty($checked_inputs['input_error_name'])) {
    //             $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
    //             $role_urls = $this->RoleModel->GetAllRoleUrl();
    //             $role_not_match = true;
    //             foreach ($role_urls as $role_url) {
    //                 if ($role_url['role_url'] == $checked_inputs['role_url']) {
    //                     $role_not_match = false;
    //                     break;
    //                 }
    //             }
    //             if ($role_not_match) {
    //                 $data_redirect = array();
    //                 $result = $this->RoleModel->UpdateRole($checked_inputs);
    //                 $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', ucwords($checked_inputs['role_name']) . ' Rolü Başarıyla Güncellendi') : $this->notification_control->Danger('Rol Güncelleme Başarısız');
    //                 $roles = $this->RoleModel->GetAllRole();
    //                 !empty($roles) ? $data_redirect['roles'] = $roles : $data_redirect['notfound_role'] = $this->notification_control->NotFound('Kayıtlı Rol Yok');
    //                 parent::GetView('Admin/Roles', $data_redirect);
    //             } else {
    //                 if (!empty($checked_inputs['id'])) {
    //                     $role = $this->RoleModel->GetRoleById($checked_inputs['id']);
    //                     if (!empty($role)) {
    //                         $data['role'] = $role;
    //                         $users_in_role = $this->UserModel->GetUsersByRole($role['id']);
    //                         if (!empty($users_in_role)) {
    //                             foreach ($users_in_role as $key => $user) {
    //                                 $users_in_role[$key]['email'] = $this->input_control->DecodeMail($user['email']);
    //                             }
    //                             $data['users'] = $users_in_role;
    //                         } else {
    //                             $data['notfound_user'] = $this->notification_control->NotFound(ucwords($role['role_name']) . ' Rolüne Ait Kullanıcı Bulunamadı');
    //                         }
    //                         $data['input_error_key'] = 'role_duplicate';
    //                         $this->notification_control->SetNotification('DANGER', 'Rol Zaten Kayıtlı');
    //                         parent::GetView('Admin/RoleDetails', $data);
    //                     } else {
    //                         header('Location: ' . URL . '/AdminController/Roles');
    //                     }
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Roles');
    //                 }
    //             }
    //         } else {
    //             if (!empty($checked_inputs['input_datas']['id'])) {
    //                 $role = $this->RoleModel->GetRoleById($checked_inputs['input_datas']['id']);
    //                 if (!empty($role)) {
    //                     $data['role'] = $role;
    //                     $users_in_role = $this->UserModel->GetUsersByRole($role['id']);
    //                     if (!empty($users_in_role)) {
    //                         foreach ($users_in_role as $key => $user) {
    //                             $users_in_role[$key]['email'] = $this->input_control->DecodeMail($user['email']);
    //                         }
    //                         $data['users'] = $users_in_role;
    //                     } else {
    //                         $data['notfound_user'] = $this->notification_control->NotFound(ucwords($role['role_name']) . ' Rolüne Ait Kullanıcı Bulunamadı');
    //                     }
    //                     $data['input_error_key'] = $checked_inputs['input_error_key'];
    //                     $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
    //                     parent::GetView('Admin/RoleDetails', $data);
    //                 } else {
    //                     header('Location: ' . URL . '/AdminController/Roles');
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Roles');
    //             }
    //         }
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Roles');
    //     }









    //     $posted_role_name = isset($_POST['role_name']) ? $_POST['role_name'] : '';
    //     $posted_inputs = array(
    //         'role_name' => $posted_role_name,
    //         'role_url' => $this->input_control->GenerateUrl($posted_role_name),
    //         'id' => isset($_POST['id']) ? $_POST['id'] : ''
    //     );
    //     $checked_inputs = $this->input_control->CheckPostedInputsForDb($posted_inputs);
    //     parent::GetModel('RoleModel');
    //     if (is_array($checked_inputs)) {
    //         $roles = $this->model->GetRoleNames();
    //         $role_not_match = true;
    //         foreach ($roles as $role) {
    //             if ($role['role_name'] === $checked_inputs['role_name']) {
    //                 $role_not_match = false;
    //                 break;
    //             }
    //         }
    //         if ($role_not_match) {
    //             if ($this->model->UpdateRole($checked_inputs) == 'Updated') {
    //                 parent::GetView('Admin/Roles', array('roles' => $this->model->GetAllRole(), 'notification' => $this->notification_control->SetNotification('SUCCESS', $checked_inputs['role_name'] . ' Başarıyla Güncellendi')));
    //             } else {
    //                 parent::GetView('Admin/Roles', array('roles' => $this->model->GetAllRole(), 'notification' => $this->notification_control->SetNotification('SUCCESS', 'Rol Güncelleme Başarısız')));
    //             }
    //         } else {
    //             $role = $this->model->GetRoleById($this->input_control->CheckPostedInputForDb(isset($_POST['id']) ? $_POST['id'] : ''));
    //             if (!empty($role)) {
    //                 $users_in_role = $this->model->GetUsersByRole($role['id']);
    //                 if (!empty($users_in_role)) {
    //                     parent::GetView('Admin/RoleDetails', array('role' => $role, 'users' => $users_in_role, 'input_error' => 'role_duplicate'));
    //                 } else {
    //                     parent::GetView('Admin/RoleDetails', array('role' => $role, 'notfound_user' => $this->notification_control->NotFound('Role Ait Kullanıcı Bulunamadı'), 'input_error' => 'role_duplicate'));
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Roles');
    //             }
    //         }
    //     } else {
    //         if ($checked_inputs !== 'id') {
    //             $role = $this->model->GetRoleById($this->input_control->CheckPostedInputForDb(isset($_POST['id']) ? $_POST['id'] : ''));
    //             if (!empty($role)) {
    //                 $users_in_role = $this->model->GetUsersByRole($role['id']);
    //                 if (!empty($users_in_role)) {
    //                     parent::GetView('Admin/RoleDetails', array('role' => $role, 'users' => $users_in_role, 'input_error' => $checked_inputs));
    //                 } else {
    //                     parent::GetView('Admin/RoleDetails', array('role' => $role, 'notfound_user' => $this->notification_control->NotFound(ucwords($role['role_name']) . ' Rolüne Ait Kullanıcı Bulunamadı'), 'input_error' => $checked_inputs));
    //                 }
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Roles');
    //             }
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Roles');
    //         }
    //     }
    // }
    // function RoleDelete()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_role'])) {
    //         $input_id = $this->input_control->IsString(isset($_POST['role_id']) ? $_POST['role_id'] : '');
    //         if ($input_id !== null) {
    //             parent::GetModel('RoleModel');
    //             $data = array();
    //             $checked_id = $this->input_control->CheckPostedInputForDb($input_id);
    //             $permission = true;
    //             foreach (CANT_DELETE_ROLE_IDS as $cant_delete_role) {
    //                 if ($checked_id == $cant_delete_role) {
    //                     $permission = false;
    //                     break;
    //                 }
    //             }
    //             if ($permission) {
    //                 $result = $this->RoleModel->DeleteRole($checked_id);
    //                 $result2 = $this->RoleModel->EmptyUserRoleColumn('user_role', $checked_id);
    //                 $_SESSION[SESSION_NOTIFICATION_NAME] = ($result == 'Deleted' && $result2 == 'Updated') ? $this->notification_control->SetNotification('SUCCESS', 'Rol Başarıyla Silindi') : $this->notification_control->Danger('Rol Silme Başarısız');
    //                 $roles = $this->RoleModel->GetAllRole();
    //                 !empty($roles) ? $data['roles'] = $roles : $data['notfound_role'] = $this->notification_control->NotFound('Kayıtlı Rol Yok');
    //                 parent::GetView('Admin/Roles', $data);
    //             } else {
    //                 header('Location: ' . URL . '/AdminController/Roles');
    //             }
    //         } else {
    //             header('Location: ' . URL . '/AdminController/Roles');
    //         }
    //     } else {
    //         header('Location: ' . URL . '/AdminController/Roles');
    //     }
    // }
    // function AdvertisingInfos()
    // {
    //     $user_infos = $this->UserModel->GetAdvertisingInfos();
    //     foreach ($user_infos as $key => $user_info) {
    //         $user_infos[$key]['email'] = $this->input_control->DecodeMail($user_info['email']);
    //     }
    //     parent::GetView('Admin/AdvertisingInfos', array('user_infos' => $user_infos));
    // }

    // function Profile()
    // {
    //     $data = array();
    //     $data['selected_link'] = 'Profile';
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    //         $posted_inputs = array(
    //             'first_name' => (isset($_POST['first_name']) ? $_POST['first_name'] : '') . '|Ad-20',
    //             'last_name' => (isset($_POST['last_name']) ? $_POST['last_name'] : '') . '|Soyad-20',
    //             'tel' => (isset($_POST['tel']) ? $_POST['tel'] : '') . '|Telefon&Numarası-10$IsNumeric'
    //         );
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         if (empty($checked_inputs['input_error_name'])) {
    //             if (strlen($checked_inputs['input_datas']['tel']) != 10) {
    //                 $checked_inputs['input_datas']['email'] = isset($_POST['email']) ? $_POST['email'] : '';
    //                 $data['user'] = $checked_inputs['input_datas'];
    //                 $data['input_error_key'] = 'tel';
    //                 $this->notification_control->SetNotification('DANGER', 'Telefon Numarası Geçersiz');
    //             } else {
    //                 $checked_inputs = $this->input_control->CheckPostedInputsForDb($checked_inputs['input_datas']);
    //                 $checked_inputs['tel'] = '90' . $checked_inputs['tel'];
    //                 $result = $this->UserModel->UpdateUser($checked_inputs);
    //                 $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', 'Yönetici Profili Başarıyla Güncellendi') : $this->notification_control->Danger('Yönetici Profilini Güncelleme Başarısız');
    //                 $user = $this->UserModel->GetUserById('*', $this->authenticated_user['id']);
    //                 if (!empty($user)) {
    //                     $user['email'] = $this->input_control->DecodeMail($user['email']);
    //                     $data['user'] = $user;
    //                     // $user_cookie = array(
    //                     //     'user_id' => $user['id'],
    //                     //     'user_first_name' => $user['first_name'],
    //                     //     'user_last_name' => $user['last_name'],
    //                     //     'user_profile_image' => $user['profile_image'],
    //                     //     'user_role' => $user['user_role']
    //                     // );
    //                     // $encrypted_data = $this->input_control->EncrypteData(json_encode($user_cookie), $this->key, 128);
    //                     // Cookie::SetCookie(0, '/', COOKIE_AUTHENTICATION_NAME, $encrypted_data);
    //                 } else {
    //                     header('Location: ' . URL);
    //                 }
    //             }
    //         } else {
    //             $checked_inputs['input_datas']['email'] = isset($_POST['email']) ? $_POST['email'] : '';
    //             $data['user'] = $checked_inputs['input_datas'];
    //             $data['input_error_key'] = $checked_inputs['input_error_key'];
    //             $_SESSION[SESSION_NOTIFICATION_NAME] = !empty($checked_inputs['input_error_length']) ? $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Ve ' . $checked_inputs['input_error_length'] . ' Karakterden Fazla Olamaz') : $this->notification_control->Danger($checked_inputs['input_error_name'] . ' Boş Olamaz');
    //         }
    //     } else {
    //         $user = $this->UserModel->GetUserById('*', $this->authenticated_user['id'], 0);
    //         if (!empty($user)) {
    //             $user['email'] = $this->input_control->DecodeMail($user['email']);
    //             $data['user'] = $user;
    //         } else {
    //             header('Location: ' . URL);
    //         }
    //     }
    //     parent::GetView('Admin/Profile', $data);
    // }
    // function PasswordChange()
    // {
    //     $data = array();
    //     $data['selected_link'] = 'PasswordChange';
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    //         $posted_inputs = array(
    //             'current_password' => (isset($_POST['current_password']) ? $_POST['current_password'] : '') . '|Güncel&Şifreniz',
    //             'new_password' => (isset($_POST['new_password']) ? $_POST['new_password'] : '') . '|Yeni&Şifreniz',
    //             're_new_password' => (isset($_POST['re_new_password']) ? $_POST['re_new_password'] : '') . '|Yeni&Şifre&Tekrar'
    //         );
    //         $checked_inputs = $this->input_control->CheckPostedInputs($posted_inputs);
    //         if (empty($checked_inputs['input_error_name']) && strlen($checked_inputs['input_datas']['new_password']) > 11 && strlen($checked_inputs['input_datas']['new_password']) < 4000) {
    //             $bcrypt_options = $this->input_control->BcryptOptions();
    //             $new_peppered_pwd = $this->input_control->PepperPassword($checked_inputs['input_datas']['new_password']);
    //             $new_peppered_repwd = $this->input_control->PepperPassword($checked_inputs['input_datas']['re_new_password']);
    //             $new_hashed_pwd = password_hash($new_peppered_pwd, PASSWORD_BCRYPT, $bcrypt_options);
    //             $verified_pwd = $this->input_control->VerifyPassword($new_peppered_repwd, $new_hashed_pwd);
    //             if ($verified_pwd) {
    //                 $current_peppered_pwd = $this->input_control->PepperPassword($checked_inputs['input_datas']['current_password']);
    //                 $user_password = $this->UserModel->GetUserPassword($this->authenticated_user['id']);
    //                 $verified_pwd2 = $this->input_control->VerifyPassword($current_peppered_pwd, $user_password);
    //                 if ($verified_pwd2) {
    //                     $current_hashed_pwd = password_hash($current_peppered_pwd, PASSWORD_BCRYPT, $bcrypt_options);
    //                     $result = $this->UserModel->UpdateUser(array('user_password' => $current_hashed_pwd, 'id' => $this->authenticated_user['id']));
    //                     $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', 'Yönetici Şifresi Başarıyla Güncellendi') : $this->notification_control->Danger('Yönetici Şifresi Güncelleme Başarısız');
    //                 } else {
    //                     $data['user'] = $checked_inputs;
    //                     $data['input_error_key'] = 'wrong_current_password';
    //                     $this->notification_control->SetNotification('DANGER', 'Güncel Şifre Yanlış');
    //                 }
    //             } else {
    //                 $data['user'] = $checked_inputs['input_datas'];
    //                 $data['input_error_key'] = 'password_not_match';
    //                 $this->notification_control->SetNotification('DANGER', 'Şifreler Uyuşmuyor');
    //             }
    //         } else {
    //             $data['user'] = $checked_inputs['input_datas'];
    //             if ($checked_inputs['input_error_key'] == 'current_password') {
    //                 $this->notification_control->SetNotification('DANGER', 'Güncel Şifrenizi Girin');
    //             } elseif ($checked_inputs['input_error_key'] == 'new_password') {
    //                 $this->notification_control->SetNotification('DANGER', 'Şifrenizi Girin');
    //             } elseif (strlen($checked_inputs['input_datas']['new_password']) < 12) {
    //                 $checked_inputs['input_error_key'] = 'new_password';
    //                 $this->notification_control->SetNotification('DANGER', 'Şifre En Az 12 Karakterden Oluşmalıdır');
    //             } elseif ($checked_inputs['input_error_key'] == 're_new_password') {
    //                 $this->notification_control->SetNotification('DANGER', 'Şifrenizi Tekrardan Girin');
    //             }
    //             $data['input_error_key'] = $checked_inputs['input_error_key'];
    //         }
    //     }
    //     parent::GetView('Admin/PasswordChange', $data);
    // }
    // function ProfilePhotoChange()
    // {
    //     $data = array();
    //     $data['selected_link'] = 'ProfilePhotoChange';
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_photo'])) {
    //         $success = false;
    //         if (isset($_FILES['user_image'])) {
    //             $user_image = $_FILES['user_image'];
    //             if ($user_image['error'] == 0) {
    //                 if ($user_image['size'] <= (1024 * 1024 * 20)) {
    //                     $accepted_image_types = array('image/png', 'image/jpeg');
    //                     if (in_array($user_image['type'], $accepted_image_types)) {
    //                         $user_name = explode(".", $user_image['name']);
    //                         $new_image_folder = USER_IMAGES_PATH . $this->authenticated_user['id'];
    //                         $filename = $user_image['tmp_name'];
    //                         if (!is_dir($new_image_folder)) {
    //                             mkdir($new_image_folder, 0777, true);
    //                         }
    //                         $image_random_name = strtolower(substr(strtr(base64_encode(hash_hmac('SHA512', time(), base64_encode(random_bytes(128)), true)), array('+' => 't', '=' => 's', '/' => '9', '.' => '2', '_' => 'g')), 21, 30));
    //                         $width = 100;
    //                         $height = 100;
    //                         $dst_image = imagecreatetruecolor($width, $height);
    //                         $image_infos = getimagesize($filename);
    //                         $image_width = $image_infos[0];
    //                         $image_height = $image_infos[1];
    //                         $uploadImageType = $image_infos[2];
    //                         if ($uploadImageType == 2) {
    //                             $src_image = imagecreatefromjpeg($filename);
    //                             imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
    //                             imagejpeg($dst_image, $new_image_folder . '/' . $image_random_name . '.' . $user_name[count($user_name) - 1], 100);
    //                         } elseif ($uploadImageType == 3) {
    //                             $src_image = imagecreatefrompng($filename);
    //                             imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
    //                             imagepng($dst_image, $new_image_folder . '/' . $image_random_name . '.' . $user_name[count($user_name) - 1], 9);
    //                         }
    //                         imagedestroy($src_image);
    //                         $user_images_db = $image_random_name . '.' . $user_name[count($user_name) - 1] . '-';
    //                         $success = true;
    //                     } else {
    //                         $data['image_error_message'] = 'Fotoğrafın Uzantısı Desteklenmiyor (Desteklenen Uzantılar: jpeg, png)';
    //                     }
    //                 } else {
    //                     $data['image_error_message'] = 'Fotoğrafın Boyutu 20mb dan Fazla Olamaz';
    //                 }
    //             } else {
    //                 $data['image_error_message'] = 'Profil Fotoğrafını Yükleyin';
    //             }
    //         } else {
    //             $data['image_error_message'] = 'Profil Fotoğrafını Yükleyin';
    //         }
    //         if ($success) {
    //             $checked_inputs = array();
    //             $checked_inputs['profile_image'] = rtrim($user_images_db, '-');
    //             $checked_inputs['id'] = $this->authenticated_user['id'];
    //             $data_redirect = array();
    //             $data_redirect['selected_link'] = 'ProfilePhotoChange';
    //             $result = $this->UserModel->UpdateUser($checked_inputs);
    //             $_SESSION[SESSION_NOTIFICATION_NAME] = $result == 'Updated' ? $this->notification_control->SetNotification('SUCCESS', 'Profil Fotoğrafı Başarıyla Güncellendi') : $this->notification_control->Danger('Profil Fotoğrafı Güncelleme Başarısız');
    //             $user = $this->UserModel->GetUserById('*', $this->authenticated_user['id'], 0);
    //             if (!empty($user)) {
    //                 // $user_cookie = array(
    //                 //     'user_id' => $user['id'],
    //                 //     'user_first_name' => $user['first_name'],
    //                 //     'user_last_name' => $user['last_name'],
    //                 //     'user_profile_image' => $user['profile_image'],
    //                 //     'user_role' => $user['user_role']
    //                 // );
    //                 // $encrypted_data = $this->input_control->EncrypteData(json_encode($user_cookie), $this->key, 128);
    //                 // Cookie::SetCookie(0, '/', COOKIE_AUTHENTICATION_NAME, $encrypted_data);
    //                 parent::GetView('Admin/ProfilePhotoChange', $data_redirect);
    //             } else {
    //                 header('Location: ' . URL);
    //             }
    //         } else {
    //             $data['input_error_key'] = 'user_image_problem';
    //             parent::GetView('Admin/ProfilePhotoChange', $data);
    //         }
    //     } else {
    //         parent::GetView('Admin/ProfilePhotoChange', $data);
    //     }
    // }
    // function Settings()
    // {
    //     parent::GetView('Admin/Settings');
    // }
}
