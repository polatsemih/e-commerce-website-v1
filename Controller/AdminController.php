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
                        $this->session_control->KillAllSessions();
                        $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
                        $this->input_control->Redirect(URL_LOGIN);
                    }
                }
                if ($session_authentication_error) {
                    $this->session_control->KillAllSessions();
                    $this->input_control->Redirect(URL_LOGIN);
                }
            }
            if ($session_authentication_error) {
                $this->session_control->KillAllSessions();
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
                $view_count = $this->AdminModel->GetCountViewOnceIpForIndex();
                if ($view_count['result']) {
                    $this->web_data['view_count'] = $view_count['data'];
                }
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
                    } else {
                        $this->input_control->CheckUrl(array('cinsiyet', 'ara', 'sayfa', 'anasayfada', 'satista', 'eklenme-tarihi', 'isim-azalan', 'isim-artan', 'fiyat-azalan', 'fiyat-artan', 'indirimli-fiyat-azalan', 'indirimli-fiyat-artan', 'adet-azalan', 'adet-artan'));
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
    function ItemComments(string $item_url)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $item = $this->AdminModel->GetItemForComment($item_url);
                if ($item['result']) {
                    $item_comments = $this->AdminModel->GetItemCommentsByItemId($item['data']['id']);
                    if ($item_comments['result']) {
                        foreach ($item_comments['data'] as $key => $item_comment) {
                            $item_url = $this->AdminModel->GetItemUrlForComment($item_comment['item_id']);
                            if ($item_url['result']) {
                                $item_comments['data'][$key]['item_url'] = $item_url['data']['item_url'];
                                $item_comment_reply = $this->AdminModel->GetItemCommentReply($item_comment['id']);
                                if ($item_comment_reply['result']) {
                                    $item_comments['data'][$key]['comment_reply'] = $item_comment_reply['data'];
                                }
                            } else {
                                unset($item_comments['data'][$key]);
                            }
                        }
                        $this->web_data['item_comments'] = $item_comments['data'];
                    }
                }
                parent::GetView('Admin/Comments', $this->web_data);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function ItemComments | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Comments()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $item_comments = $this->AdminModel->GetItemComments();
                if ($item_comments['result']) {
                    foreach ($item_comments['data'] as $key => $item_comment) {
                        $item_url = $this->AdminModel->GetItemUrlForComment($item_comment['item_id']);
                        if ($item_url['result']) {
                            $item_comments['data'][$key]['item_url'] = $item_url['data']['item_url'];
                            $item_comment_reply = $this->AdminModel->GetItemCommentReply($item_comment['id']);
                            if ($item_comment_reply['result']) {
                                $item_comments['data'][$key]['comment_reply'] = $item_comment_reply['data'];
                            }
                        } else {
                            unset($item_comments['data'][$key]);
                        }
                    }
                    $this->web_data['item_comments'] = $item_comments['data'];
                }
                parent::GetView('Admin/Comments', $this->web_data);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function ItemComments | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    // function AdminCommentDelete()
    // {
    //     try {
    //         $response = array();
    //         $response['reset'] = true;
    //         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //             $checked_inputs = $this->input_control->CheckPostedInputs(array(
    //                 'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
    //                 'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
    //             ));
    //             if (empty($checked_inputs['error_message'])) {
    //                 if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminItemComments')) {
    //                     parent::GetModel('CommentModel');
    //                     $comment_has_reply = $this->CommentModel->CommentHasReply($checked_inputs['comment_id']);
    //                     if ($comment_has_reply['result']) {
    //                         foreach ($comment_has_reply['data'] as $comment_reply) {
    //                             $this->CommentModel->UpdateCommentReply(array('is_comment_reply_deleted' => 1, 'date_comment_reply_deleted' => date('Y-m-d H:i:s'), 'id' => $comment_reply['id']));
    //                         }
    //                     }
    //                     if ($this->CommentModel->UpdateComment(array('is_comment_deleted' => 1, 'date_comment_deleted' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_id']))['result']) {
    //                         $result_set_csrf_token = parent::SetCSRFTokenjQ('AdminItemComments');
    //                         if ($result_set_csrf_token['result']) {
    //                             $response['reset'] = false;
    //                             $response['form_token'] = $result_set_csrf_token['csrf_token'];
    //                             $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_DELETE);
    //                             $response['comment_id'] = $checked_inputs['comment_id'];
    //                         } else {
    //                             echo '{"shutdown":"shutdown"}';
    //                             exit(0);
    //                         }
    //                     } else {
    //                         $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
    //                     }
    //                 }
    //             } else {
    //                 $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
    //             }
    //         }
    //         $jsoned_response = json_encode($response);
    //         if (!empty($jsoned_response)) {
    //             echo $jsoned_response;
    //             exit(0);
    //         }
    //     } catch (\Throwable $th) {
    //         if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function AdminCommentDelete | ' . $th))['result']) {
    //             echo '{"exception":"exception"}';
    //             exit(0);
    //         } else {
    //             echo '{"shutdown":"shutdown"}';
    //             exit(0);
    //         }
    //     }
    // }
    // function AdminCommentReplyDelete()
    // {
    //     try {
    //         $response = array();
    //         $response['reset'] = true;
    //         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //             $checked_inputs = $this->input_control->CheckPostedInputs(array(
    //                 'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
    //                 'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
    //                 'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
    //             ));
    //             if (empty($checked_inputs['error_message'])) {
    //                 if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminItemComments')) {
    //                     parent::GetModel('CommentModel');
    //                     if ($this->CommentModel->UpdateCommentReply(array('is_comment_reply_deleted' => 1, 'date_comment_reply_deleted' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_reply_id']))['result']) {
    //                         $result_set_csrf_token = parent::SetCSRFTokenjQ('AdminItemComments');
    //                         if ($result_set_csrf_token['result']) {
    //                             $response['reset'] = false;
    //                             $response['form_token'] = $result_set_csrf_token['csrf_token'];
    //                             $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_DELETE);
    //                             $response['comment'] = array('comment_reply_id' => $checked_inputs['comment_reply_id'], 'comment_id' => $checked_inputs['comment_id']);
    //                         } else {
    //                             echo '{"shutdown":"shutdown"}';
    //                             exit(0);
    //                         }
    //                     } else {
    //                         $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
    //                     }
    //                 }
    //             } else {
    //                 $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
    //             }
    //         }
    //         $jsoned_response = json_encode($response);
    //         if (!empty($jsoned_response)) {
    //             echo $jsoned_response;
    //             exit(0);
    //         }
    //     } catch (\Throwable $th) {
    //         if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function AdminCommentReplyDelete | ' . $th))['result']) {
    //             echo '{"exception":"exception"}';
    //             exit(0);
    //         } else {
    //             echo '{"shutdown":"shutdown"}';
    //             exit(0);
    //         }
    //     }
    // }
    // function AdminCommentApprove()
    // {
    //     try {
    //         $response = array();
    //         $response['reset'] = true;
    //         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //             $is_comment_approved = isset($_POST['is_comment_approved']) ? 0 : 1;
    //             $checked_inputs = $this->input_control->CheckPostedInputs(array(
    //                 'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
    //                 'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
    //             ));
    //             if (empty($checked_inputs['error_message'])) {
    //                 if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminItemComments')) {
    //                     parent::GetModel('CommentModel');
    //                     if ($is_comment_approved == 1) {
    //                         $result_approve_comment = $this->CommentModel->UpdateComment(array('is_comment_approved' => 1, 'date_comment_approved' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_id']))['result'];
    //                     } else {
    //                         $result_approve_comment = $this->CommentModel->UpdateComment(array('is_comment_approved' => 0, 'date_comment_approved' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_id']))['result'];
    //                     }
    //                     if ($result_approve_comment) {
    //                         $result_set_csrf_token = parent::SetCSRFTokenjQ('AdminItemComments');
    //                         if ($result_set_csrf_token['result']) {
    //                             $response['reset'] = false;
    //                             $response['form_token'] = $result_set_csrf_token['csrf_token'];
    //                             if ($is_comment_approved == 1) {
    //                                 $response['notification'] = $this->notification_control->SetNotificationForjQ('WARNING', TR_NOTIFICATION_SUCCESS_COMMENT_APPROVED);
    //                                 $response['is_approved'] = 1;
    //                             } else {
    //                                 $response['notification'] = $this->notification_control->SetNotificationForjQ('WARNING', TR_NOTIFICATION_SUCCESS_COMMENT_DISAPPROVED);
    //                                 $response['is_approved'] = 0;
    //                             }
    //                         } else {
    //                             echo '{"shutdown":"shutdown"}';
    //                             exit(0);
    //                         }
    //                     } else {
    //                         $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
    //                     }
    //                 }
    //             } else {
    //                 $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
    //             }
    //         }
    //         $jsoned_response = json_encode($response);
    //         if (!empty($jsoned_response)) {
    //             echo $jsoned_response;
    //             exit(0);
    //         }
    //     } catch (\Throwable $th) {
    //         if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function AdminCommentApprove | ' . $th))['result']) {
    //             echo '{"exception":"exception"}';
    //             exit(0);
    //         } else {
    //             echo '{"shutdown":"shutdown"}';
    //             exit(0);
    //         }
    //     }
    // }
    // function AdminCommentReplyApprove()
    // {
    //     try {
    //         $response = array();
    //         $response['reset'] = true;
    //         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //             $is_comment_reply_approved = isset($_POST['is_comment_reply_approved']) ? 0 : 1;
    //             $checked_inputs = $this->input_control->CheckPostedInputs(array(
    //                 'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
    //                 'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
    //             ));
    //             if (empty($checked_inputs['error_message'])) {
    //                 if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminItemComments')) {
    //                     parent::GetModel('CommentModel');
    //                     if ($is_comment_reply_approved == 1) {
    //                         $result_approve_comment_reply = $this->CommentModel->UpdateCommentReply(array('is_comment_reply_approved' => 1, 'date_comment_reply_approved' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_reply_id']))['result'];
    //                     } else {
    //                         $result_approve_comment_reply = $this->CommentModel->UpdateCommentReply(array('is_comment_reply_approved' => 0, 'date_comment_reply_approved' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_reply_id']))['result'];
    //                     }
    //                     if ($result_approve_comment_reply) {
    //                         $result_set_csrf_token = parent::SetCSRFTokenjQ('AdminItemComments');
    //                         if ($result_set_csrf_token['result']) {
    //                             $response['reset'] = false;
    //                             $response['form_token'] = $result_set_csrf_token['csrf_token'];
    //                             if ($is_comment_reply_approved == 1) {
    //                                 $response['notification'] = $this->notification_control->SetNotificationForjQ('WARNING', TR_NOTIFICATION_SUCCESS_COMMENT_APPROVED);
    //                                 $response['is_comment_reply_approved'] = 1;
    //                             } else {
    //                                 $response['notification'] = $this->notification_control->SetNotificationForjQ('WARNING', TR_NOTIFICATION_SUCCESS_COMMENT_DISAPPROVED);
    //                                 $response['is_comment_reply_approved'] = 0;
    //                             }
    //                         } else {
    //                             echo '{"shutdown":"shutdown"}';
    //                             exit(0);
    //                         }
    //                     } else {
    //                         $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
    //                     }
    //                 }
    //             } else {
    //                 $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
    //             }
    //         }
    //         $jsoned_response = json_encode($response);
    //         if (!empty($jsoned_response)) {
    //             echo $jsoned_response;
    //             exit(0);
    //         }
    //     } catch (\Throwable $th) {
    //         if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function AdminCommentReplyApprove | ' . $th))['result']) {
    //             echo '{"exception":"exception"}';
    //             exit(0);
    //         } else {
    //             echo '{"shutdown":"shutdown"}';
    //             exit(0);
    //         }
    //     }
    // }
    function Orders()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $order_initialize_informations = $this->AdminModel->GetOrderInitializeInformations();
                if ($order_initialize_informations['result']) {
                    $this->web_data['order_initialize_informations'] = $order_initialize_informations['data'];
                }
                parent::GetView('Admin/Orders', $this->web_data);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function Orders | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function OrderDetails(string $order_initialize_information_id)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $order_initialize_basket = $this->AdminModel->GetOrderInitializeBasket($order_initialize_information_id);
                $order_payment = $this->AdminModel->GetOrderPayment($order_initialize_information_id);
                if ($order_initialize_basket['result'] && $order_payment['result']) {
                    $this->web_data['order_initialize_basket'] = $order_initialize_basket['data'];
                    $this->web_data['order_payment'] = $order_payment['data'];
                    $order_payment_item_transaction = $this->AdminModel->GetOrderPaymentItemTransaction($order_payment['data']['payment_id']);
                    if ($order_payment_item_transaction['result']) {
                        $this->web_data['order_payment_item_transaction'] = $order_payment_item_transaction['data'];
                    }
                }
                parent::GetView('Admin/OrderDetails', $this->web_data);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function OrderDetails | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function OrderErrors(string $order_error_url)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $case_matched = false;
                switch ($order_error_url) {
                    case URL_ADMIN_ORDER_CONVERSATION_ERROR:
                        $case_matched = true;
                        $this->web_data['order_error_type'] = URL_ADMIN_ORDER_CONVERSATION_ERROR;
                        $this->web_data['order_error_title'] = URL_ADMIN_ORDER_CONVERSATION_ERROR_TITLE;
                        $order_conversation_errors = $this->AdminModel->GetOrderConversationErrors();
                        if ($order_conversation_errors['result']) {
                            $this->web_data['order_conversation_errors'] = $order_conversation_errors['data'];
                        }
                        break;
                    case URL_ADMIN_ORDER_STATUS_ERROR:
                        $case_matched = true;
                        $this->web_data['order_error_type'] = URL_ADMIN_ORDER_STATUS_ERROR;
                        $this->web_data['order_error_title'] = URL_ADMIN_ORDER_STATUS_ERROR_TITLE;
                        $order_status_errors = $this->AdminModel->GetOrderStatusErrors();
                        if ($order_status_errors['result']) {
                            $this->web_data['order_status_errors'] = $order_status_errors['data'];
                        }
                        break;
                    case URL_ADMIN_ORDER_STATUS_CODES:
                        $case_matched = true;
                        $this->web_data['order_error_type'] = URL_ADMIN_ORDER_STATUS_CODES;
                        $this->web_data['order_error_title'] = URL_ADMIN_ORDER_STATUS_CODES_TITLE;
                        $order_status_codes = $this->AdminModel->GetOrderStatusCodes();
                        if ($order_status_codes['result']) {
                            $this->web_data['order_status_codes'] = $order_status_codes['data'];
                        }
                        break;
                    case URL_ADMIN_ORDER_MD_STATUS_CODES:
                        $case_matched = true;
                        $this->web_data['order_error_type'] = URL_ADMIN_ORDER_MD_STATUS_CODES;
                        $this->web_data['order_error_title'] = URL_ADMIN_ORDER_MD_STATUS_CODES_TITLE;
                        $order_md_status_codes = $this->AdminModel->GetOrderMDStatusCodes();
                        if ($order_md_status_codes['result']) {
                            $this->web_data['order_md_status_codes'] = $order_md_status_codes['data'];
                        }
                        break;
                }
                if ($case_matched) {
                    parent::GetView('Admin/OrderErrors', $this->web_data);
                }
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function OrderErrors | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Users()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl(array('limit', 'ara', 'silinen-kullanicilar'));
                $item_conditions = '';
                $item_bind_params = array();
                $url_for_selected_filters_deleted = '';
                $url_for_selected_filters_search = '';
                $url_for_selected_filters_limit = '';
                if (isset($_GET['silinen-kullanicilar'])) {
                    $deleted_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['silinen-kullanicilar']);
                    if (isset($deleted_from_get_form) && ($deleted_from_get_form == 0 || $deleted_from_get_form == 1)) {
                        $this->web_data['selected_deleted'] = $deleted_from_get_form;
                        $url_for_selected_filters_deleted = 'silinen-kullanicilar=' . $deleted_from_get_form . '&';
                        if (!empty($item_conditions)) {
                            $item_conditions .= ' AND is_user_deleted=?';
                        } else {
                            $item_conditions .= 'WHERE is_user_deleted=?';
                        }
                        $item_bind_params[] = $deleted_from_get_form;
                    } else {
                        $this->input_control->CheckUrl(array('limit', 'ara'));
                    }
                }
                $user_count = $this->AdminModel->GetUsersCount($item_conditions, $item_bind_params);
                if ($user_count['result']) {
                    $this->web_data['user_count'] = $user_count['data'];
                }
                if (isset($_GET['ara'])) {
                    $search_from_get_form = $this->input_control->CheckGETInputWithMaxLength($_GET['ara'], 250);
                    if (isset($search_from_get_form)) {
                        $this->web_data['selected_search'] = $search_from_get_form;
                        $url_for_selected_filters_search = 'ara=' . $search_from_get_form . '&';
                        if (!empty($item_conditions)) {
                            $item_conditions .= ' AND (id LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone_number LIKE ? OR identity_number LIKE ?)';
                        } else {
                            $item_conditions .= 'WHERE (id LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone_number LIKE ? OR identity_number LIKE ?)';
                        }
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                        $item_bind_params[] = '%' . $search_from_get_form . '%';
                    } else {
                        $this->input_control->CheckUrl(array('limit', 'silinen-kullanicilar'));
                    }
                }
                if (!empty($item_conditions)) {
                    $item_conditions .= ' LIMIT ?';
                } else {
                    $item_conditions .= 'LIMIT ?';
                }
                $limit_not_used = true;
                if (isset($_GET['limit'])) {
                    $limit_from_get_form = $this->input_control->CheckPositiveNonZeroGETInput($_GET['limit']);
                    if (!empty($limit_from_get_form)) {
                        $limit_not_used = false;
                        $this->web_data['selected_limit'] = $limit_from_get_form;
                        $url_for_selected_filters_limit = 'limit=' . $limit_from_get_form . '&';
                        $item_bind_params[] = $limit_from_get_form;
                    } else {
                        $this->input_control->CheckUrl(array('ara', 'silinen-kullanicilar'));
                    }
                }
                if ($limit_not_used) {
                    $this->web_data['selected_limit'] = 20;
                    $item_bind_params[] = 20;
                }
                $this->web_data['url_search'] = rtrim($url_for_selected_filters_deleted . $url_for_selected_filters_limit, '&');
                $this->web_data['url_limit'] = rtrim($url_for_selected_filters_deleted . $url_for_selected_filters_search, '&');
                $this->web_data['url_deleted'] = rtrim($url_for_selected_filters_limit . $url_for_selected_filters_search, '&');
                $users = $this->AdminModel->GetUsers($item_conditions, $item_bind_params);
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
    }
    function UserDetails(string $user_url)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $user = $this->AdminModel->GetUsersDetails($user_url);
                if ($user['result']) {
                    $this->web_data['user'] = $user['data'];
                }
                $this->web_data['form_token'] = parent::SetCSRFToken('AdminUserBlock');
                parent::GetView('Admin/UserDetails', $this->web_data);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function UserDetails | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function UserPastOrders(string $user_id)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $user = $this->AdminModel->GetUserForPastOrders($user_id);
                if ($user['result']) {
                    $user_order_initialize_informations = $this->AdminModel->GetOrderInitializeInformationsByUserId($user_id);
                    if ($user_order_initialize_informations['result']) {
                        $this->web_data['user_order_initialize_informations'] = $user_order_initialize_informations['data'];
                        $this->web_data['user'] = $user['data'];
                    }
                }
                parent::GetView('Admin/UserPastOrders', $this->web_data);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function UserPastOrders | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function UserBlock()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'id' => array('input' => isset($_POST['id']) ? $_POST['id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'length_control' => true, 'max_length' => 250, 'error_message_max_length' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true, 'length_limit' => 250, 'error_message_length_limit' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminUserBlock')) {
                        $user_from_database = $this->AdminModel->GetUserForBlock($checked_inputs['id']);
                        if ($user_from_database['result']) {
                            if ($user_from_database['data']['is_user_blocked'] == 1) {
                                if ($this->AdminModel->UpdateUser(array('is_user_blocked' => 0, 'id' => $checked_inputs['id']))['result']) {
                                    $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_ADMIN_SUCCESS_USER_BLOCK_REMOVE);
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_USER_BLOCK_REMOVE);
                                }
                            } else {
                                if ($this->AdminModel->UpdateUser(array('is_user_blocked' => 1, 'date_user_blocked' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['id']))['result']) {
                                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_ADMIN_SUCCESS_USER_BLOCK);
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_USER_BLOCK);
                                }
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ADMIN_ERROR_USER_BLOCK);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $this->input_control->Redirect(URL_ADMIN_USER_DETAILS . '/' . $checked_inputs['id']);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function UserDetails | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function SendEmail()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $this->web_data['form_token'] = parent::SetCSRFToken('AdminSendEmail');
                parent::GetView('Admin/SendEmail', $this->web_data);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'user_email' => array('input' => isset($_POST['user_email']) ? $_POST['user_email'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'is_email' => true, 'error_message_is_email' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'length_control' => true, 'max_length' => EMAIL_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'preventxss' => true, 'length_limit' => EMAIL_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminSendEmail')) {
                        $mail_send_success = false;
                        if (!empty($_POST['email_ready_message'])) {
                            switch ($_POST['email_ready_message']) {
                                case '1':
                                    if (!empty($_POST['shipping_number'])) {
                                        $mail_send_success = true;
                                        // Sipariş Kargoya Verildi
                                    }
                                    break;
                                case '2':
                                    $mail_send_success = true;
                                    // Sipariş Teslim Edildi
                                    break;
                                case '3':
                                    $mail_send_success = true;
                                    // Sipariş İptal Edildi
                                    break;
                                case '4':
                                    if (!empty($_POST['shipping_number'])) {
                                        $mail_send_success = true;
                                        // İade Edilen Sipariş Kargoya Verildi
                                    }
                                    break;
                                case '5':
                                    $mail_send_success = true;
                                    // İade Edilen Sipariş Teslim Edildi
                                    break;
                            }
                        } elseif (!empty($_POST['email_manuel_message'])) {
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $this->input_control->Redirect(URL_ADMIN_SEND_EMAIL);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function SendEmail | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
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
                            $this->web_data['home_contact'] = 0;
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
                                    } elseif ($log_view_once['viewed_page'] == 'Home-Contact') {
                                        $this->web_data['home_contact'] += 1;
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
                            $this->web_data['home_contact_all'] = 0;
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
                                    } elseif ($log_view_all['viewed_page'] == 'Home-Contact') {
                                        $this->web_data['home_contact_all'] += 1;
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
                    case URL_ADMIN_LOGS_MESSAGE:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_MESSAGE;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_MESSAGE_TITLE;
                        $log_contact = $this->AdminModel->GetLogContact();
                        if ($log_contact['result']) {
                            $this->web_data['log_contact'] = $log_contact['data'];
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
                    case URL_ADMIN_LOGS_EMAIL_ORDER:
                        $case_matched = true;
                        $this->web_data['statistics_type'] = URL_ADMIN_LOGS_EMAIL_ORDER;
                        $this->web_data['statistics_title'] = URL_ADMIN_LOGS_EMAIL_ORDER_TITLE;
                        $log_order_email = $this->AdminModel->GetLogEmailOrder();
                        if ($log_order_email['result']) {
                            $this->web_data['log_order_email'] = $log_order_email['data'];
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
    function Profile(string $profile_url)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                $case_matched = false;
                switch ($profile_url) {
                    case URL_ADMIN_PROFILE_INFORMATIONS:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_ADMIN_PROFILE_INFORMATIONS;
                        $this->web_data['profile_title'] = URL_ADMIN_PROFILE_INFO_TITLE;
                        $user_from_database = $this->AdminModel->GetAdminByAdminId('first_name,last_name', $this->web_data['authenticated_user']);
                        break;
                    case URL_ADMIN_PROFILE_PASSWORD:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_ADMIN_PROFILE_PASSWORD;
                        $this->web_data['profile_title'] = URL_ADMIN_PROFILE_PWD_TITLE;
                        break;
                    case URL_ADMIN_PROFILE_PHOTO:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_ADMIN_PROFILE_PHOTO;
                        $this->web_data['profile_title'] = URL_ADMIN_PROFILE_PHOTO_TITLE;
                        $user_from_database = $this->AdminModel->GetAdminByAdminId('profile_image_path,profile_image', $this->web_data['authenticated_user']);
                        break;
                }
                if ($case_matched) {
                    if (!empty($user_from_database) && $user_from_database['result']) {
                        $this->web_data['authenticated_admin'] = $user_from_database['data'];
                    }
                    $this->web_data['form_token'] = parent::SetCSRFToken('AdminProfile');
                    parent::GetView('Admin/Profile', $this->web_data);
                }
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function Profile | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfileInformationsUpdate()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'user_first_name' => array('input' => isset($_POST['user_first_name']) ? $_POST['user_first_name'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_USER_NAME, 'length_control' => true, 'max_length' => USER_NAME_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_NAME, 'preventxss' => true, 'length_limit' => USER_NAME_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_NAME),
                    'user_last_name' => array('input' => isset($_POST['user_last_name']) ? $_POST['user_last_name'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_USER_LAST_NAME, 'length_control' => true, 'max_length' => USER_NAME_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_LAST_NAME, 'preventxss' => true, 'length_limit' => USER_NAME_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_LAST_NAME),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminProfile')) {
                        $confirmed_user_from_db = $this->AdminModel->GetAdminByAdminId('id,first_name,last_name', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            if ($confirmed_user_from_db['data']['first_name'] == $checked_inputs['user_first_name'] && $confirmed_user_from_db['data']['last_name'] == $checked_inputs['user_last_name']) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NEW_USER_NAME);
                            } else {
                                if ($this->AdminModel->UpdateAdmin(array('first_name' => $checked_inputs['user_first_name'], 'last_name' => $checked_inputs['user_last_name'], 'date_last_profile_update' => date('Y-m-d H:i:s'), 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_PROFILE_USER_NAME_UPDATE);
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                }
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $this->input_control->Redirect(URL_ADMIN_PROFILE . '/' . URL_ADMIN_PROFILE_INFORMATIONS);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function ProfileInformationsUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfilePasswordUpdate()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'user_old_password' => array('input' => isset($_POST['user_old_password']) ? $_POST['user_old_password'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD),
                    'user_new_password' => array('input' => isset($_POST['user_new_password']) ? $_POST['user_new_password'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'user_new_re_password' => array('input' => isset($_POST['user_new_re_password']) ? $_POST['user_new_re_password'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminProfile')) {
                        $confirmed_user_from_db = $this->AdminModel->GetAdminByAdminId('id,password,password_salt', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            $salted_old_password = hash_hmac('sha512', $confirmed_user_from_db['data']['password_salt'] . str_replace("\0", "", $checked_inputs['user_old_password']), PASSWORD_SECRET_KEY, true);
                            $decrypted_old_hashed_password = $this->input_control->DecrypteData($confirmed_user_from_db['data']['password'], PASSWORD_PEPPER);
                            if ($decrypted_old_hashed_password['result'] && !empty($salted_old_password)) {
                                if (password_verify($salted_old_password, $decrypted_old_hashed_password['data'])) {
                                    $password_salt = strtr(sodium_bin2base64(random_bytes(75), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => '2', '_' => '4'));
                                    $salted_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['user_new_password']), PASSWORD_SECRET_KEY, true);
                                    $salted_re_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['user_new_re_password']), PASSWORD_SECRET_KEY, true);
                                    if (!empty($salted_password) && !empty($salted_re_password)) {
                                        $hashed_password = password_hash($salted_password, PASSWORD_BCRYPT, $this->password_control->BcryptOptions());
                                        if (password_verify($salted_re_password, $hashed_password)) {
                                            if ($this->AdminModel->UpdateAdmin(array('password' => $this->input_control->EncrypteData($hashed_password, PASSWORD_PEPPER), 'password_salt' => $password_salt, 'date_last_profile_update' => date('Y-m-d H:i:s'), 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                                                $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_PROFILE_PASSWORD_UPDATE);
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                            }
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS);
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_PROFILE_PASSWORD_UPDATE);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_WRONG_OLD_PASSWORD);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_PROFILE_PASSWORD_UPDATE);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $this->input_control->Redirect(URL_ADMIN_PROFILE . '/' . URL_ADMIN_PROFILE_PASSWORD);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function ProfilePasswordUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfilePhotoUpdate()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_photo'])) {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminProfile')) {
                        $confirmed_user_from_db = $this->AdminModel->GetAdminByAdminId('id,profile_image_path,profile_image', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
                                if ($_FILES['user_image']['size'] <= (1024 * 1024 * 2)) {
                                    if ($_FILES['user_image']['type'] == 'image/png') {
                                        $image_type = 'png';
                                    } elseif ($_FILES['user_image']['type'] == 'image/jpeg') {
                                        $image_type = 'jpg';
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EXTENSION_PROFILE_PHOTO_UPDATE);
                                    }
                                    if (!empty($image_type)) {
                                        do {
                                            $folder_name = $this->input_control->GenerateFolderName();
                                            $image_file_name = $this->input_control->GenerateFileName();
                                            if ($folder_name['result'] && $image_file_name['result']) {
                                                $is_folder_unique = $this->UserModel->IsProfileImagePathUnique($folder_name['data']);
                                                if (!$is_folder_unique['result'] && !empty($is_folder_unique['empty'])) {
                                                    $new_image_folder_name = 'assets/images/users/' . $folder_name['data'];
                                                    if (!is_dir($new_image_folder_name)) {
                                                        $dst_width = 200;
                                                        $dst_height = 200;
                                                        $dst_image = imagecreatetruecolor($dst_width, $dst_height);
                                                        $image_infos = getimagesize($_FILES['user_image']['tmp_name']);
                                                        if (!empty($dst_image) && !empty($image_infos)) {
                                                            if ($image_infos[2] == 2) {
                                                                $src_image = imagecreatefromjpeg($_FILES['user_image']['tmp_name']);
                                                                $error = true;
                                                                if (!empty($src_image) && imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_width, $dst_height, $image_infos[0], $image_infos[1])) {
                                                                    $success = true;
                                                                    if ($confirmed_user_from_db['data']['profile_image'] != 'b6lfjkh5q9qfmfq.jpg' && $confirmed_user_from_db['data']['profile_image'] != 'etflxet4l6tlifm.jpg' && $confirmed_user_from_db['data']['profile_image_path'] != '1qiunjdt0p8cao66xoz' && $confirmed_user_from_db['data']['profile_image_path'] != '2cp61vrjuyraawv68ve') {
                                                                        if (unlink('assets/images/users/' . $confirmed_user_from_db['data']['profile_image_path'] . '/' . $confirmed_user_from_db['data']['profile_image']) && rmdir('assets/images/users/' . $confirmed_user_from_db['data']['profile_image_path'])) {
                                                                            $success = true;
                                                                        } else {
                                                                            $success = false;
                                                                        }
                                                                    }
                                                                    if ($success && mkdir($new_image_folder_name, 0777, true) && imagejpeg($dst_image, $new_image_folder_name . '/' . $image_file_name['data'] . '.' . $image_type, 100)) {
                                                                        $error = false;
                                                                    }
                                                                }
                                                                imagedestroy($src_image);
                                                                if ($error) {
                                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE);
                                                                    break;
                                                                }
                                                            } elseif ($image_infos[2] == 3) {
                                                                $src_image = imagecreatefrompng($_FILES['user_image']['tmp_name']);
                                                                $error = true;
                                                                if (!empty($src_image) && imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_width, $dst_height, $image_infos[0], $image_infos[1])) {
                                                                    $success = true;
                                                                    if ($confirmed_user_from_db['data']['profile_image'] != 'b6lfjkh5q9qfmfq.jpg' && $confirmed_user_from_db['data']['profile_image'] != 'etflxet4l6tlifm.jpg' &&  $confirmed_user_from_db['data']['profile_image_path'] != '1qiunjdt0p8cao66xoz' && $confirmed_user_from_db['data']['profile_image_path'] != '2cp61vrjuyraawv68ve') {
                                                                        if (unlink('assets/images/users/' . $confirmed_user_from_db['data']['profile_image_path'] . '/' . $confirmed_user_from_db['data']['profile_image']) && rmdir('assets/images/users/' . $confirmed_user_from_db['data']['profile_image_path'])) {
                                                                            $success = true;
                                                                        } else {
                                                                            $success = false;
                                                                        }
                                                                    }
                                                                    if ($success && mkdir($new_image_folder_name, 0777, true) && imagepng($dst_image, $new_image_folder_name . '/' . $image_file_name['data'] . '.' . $image_type, 9)) {
                                                                        $error = false;
                                                                    }
                                                                }
                                                                imagedestroy($src_image);
                                                                if ($error) {
                                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE);
                                                                    break;
                                                                }
                                                            } else {
                                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE);
                                                                break;
                                                            }
                                                            if ($this->AdminModel->UpdateAdmin(array('profile_image_path' => $folder_name['data'], 'profile_image' => $image_file_name['data'] . '.' . $image_type, 'date_last_profile_update' => date('Y-m-d H:i:s'), 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                                                                $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_PROFILE_PHOTO_UPDATE);
                                                                break;
                                                            } else {
                                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE);
                                                                break;
                                                            }
                                                        } else {
                                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE);
                                                            break;
                                                        }
                                                    } else {
                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE);
                                                        break;
                                                    }
                                                }
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE);
                                                break;
                                            }
                                        } while (true);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LIMIT_PROFILE_PHOTO_UPDATE);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_PROFILE_PHOTO_UPDATE);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $this->input_control->Redirect(URL_ADMIN_PROFILE . '/' . URL_ADMIN_PROFILE_PHOTO);
            }
            $this->input_control->Redirect(URL_ADMIN_INDEX);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AdminController function ProfilePhotoUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
}
