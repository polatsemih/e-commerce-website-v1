<?php
class HomeController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->GetModel('ActionModel');
        // print_r($_SERVER);
        if (isset($_SESSION[SESSION_AUTH])) {
            $session_auth_error = true;
            if (strlen($_SESSION[SESSION_AUTH]) === 255) {
                $session_auth_from_db = $this->ActionModel->GetSessionAuth(array($_SESSION[SESSION_AUTH], $_SERVER['REMOTE_ADDR']));
                if (!empty($session_auth_from_db) && $session_auth_from_db['date_session_auth_expiry'] > date('Y-m-d H:i:s') && $session_auth_from_db['session_auth_is_logout'] == 0) {
                    $user_from_db = $this->ActionModel->GetUserById('id,first_name,last_name,profile_image_path,profile_image,user_role', $session_auth_from_db['user_id']);
                    if (!empty($user_from_db)) {
                        $session_auth_error = false;
                        session_regenerate_id(true);
                        $this->web_data['authed_user'] = $user_from_db;
                    }
                }
            }
            if ($session_auth_error) {
                $this->session_control->KillSession();
                $this->input_control->Redirect(URL_LOGIN);
            }
        } elseif (isset($_COOKIE[COOKIE_AUTH_NAME])) {
            $is_cookie_not_valid = true;
            $cookie_auth = $this->cookie_control->GetCookie($_COOKIE[COOKIE_AUTH_NAME]);
            if (!empty($cookie_auth) && (strlen($cookie_auth) === 500)) {
                $cookie_auth_token2 = substr($cookie_auth, 0, 247);
                $cookie_auth_from_db = $this->ActionModel->GetCookieAuth(array($cookie_auth_token2, $_SERVER['REMOTE_ADDR']));
                if (!empty($cookie_auth_from_db) && $cookie_auth_from_db['date_cookie_auth_expiry'] > date('Y-m-d H:i:s') && $cookie_auth_from_db['cookie_auth_is_logout'] == 0) {
                    $cookie_auth_token1 = hash_hmac('SHA512', substr($cookie_auth, 247, 253), $cookie_auth_from_db['cookie_auth_salt'], false);
                    $is_cookie_auth_tokens_same = hash_equals($cookie_auth_from_db['cookie_auth_token1'], $cookie_auth_token1);
                    if ($is_cookie_auth_tokens_same) {
                        $user_from_db = $this->ActionModel->GetUserById('id,first_name,last_name,profile_image_path,profile_image,user_role', $cookie_auth_from_db['user_id']);
                        if (!empty($user_from_db)) {
                            $is_cookie_not_valid = false;
                            session_regenerate_id(true);
                            $this->web_data['authed_user'] = $user_from_db;
                        }
                    }
                }
            }
            if ($is_cookie_not_valid) {
                $this->cookie_control->EmptyCookie(COOKIE_AUTH_NAME);
                $this->input_control->Redirect(URL_LOGIN);
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
                    'date_csrf_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CSRF)),
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
                    'date_csrf_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CSRF)),
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
    function Index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->input_control->CheckUrl();
            parent::GetModel('FilterModel');
            $genders_from_db = $this->FilterModel->GetAllGenders('gender_name,gender_url');
            if (!empty($genders_from_db)) {
                $this->web_data['filter_genders'] = $genders_from_db;
                $this->GetView('Home/Index', $this->web_data);
            }
        }
        $this->input_control->Redirect(URL_NOTFOUND);
    }
    function Items(string $input_gender)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->input_control->CheckUrl(array('renk', 'beden', 'kategori', 'min-fiyat', 'max-fiyat'));
            parent::GetModel('FilterModel');
            $genders_from_db = $this->FilterModel->GetAllGenders('id,gender_name,gender_url');
            if (!empty($genders_from_db)) {
                foreach ($genders_from_db as $key => $gender_from_db) {
                    if ($gender_from_db['gender_url'] == $input_gender) {
                        $selected_gender = $gender_from_db;
                    }
                    unset($genders_from_db[$key]['id']);
                }
                if (!empty($selected_gender)) {
                    $this->web_data['filter_genders'] = $genders_from_db;
                    $this->web_data['selected_gender_name'] = $selected_gender['gender_name'];
                    $this->web_data['selected_gender_url'] = $selected_gender['gender_url'];
                    $log_view_gender_from_db = $this->FilterModel->GetLogViewGender(array($selected_gender['id'], $_SERVER['REMOTE_ADDR']));
                    if (empty($log_view_gender_from_db)) {
                        $this->FilterModel->CreateLogViewGender(array(
                            'gender_id' => $selected_gender['id'],
                            'user_ip' => $_SERVER['REMOTE_ADDR']
                        ));
                    }
                    $item_columns = 'item_name,item_url,item_price,item_discount_price,item_total_number,item_images_path,item_images';
                    $item_conditions = 'WHERE gender=?';
                    $item_bind_params = array($selected_gender['id']);
                    $get_hidden_inputs = array();
                    $url_for_selected_filters_category = '';
                    $url_for_selected_filters_color = '';
                    $url_for_selected_filters_size = '';
                    $url_for_selected_filters_price = '';
                    $this->web_data['selected_filters'] = array();

                    $categories_from_db = $this->FilterModel->GetAllCategories();
                    if (!empty($categories_from_db)) {
                        if (!empty($_GET['kategori'])) {
                            $category_from_get_form = $this->input_control->Check_GET_Input($_GET['kategori']);
                        }
                        if (!empty($category_from_get_form)) {
                            $category_get_matched_success = false;
                            foreach ($categories_from_db as $key => $category_from_db) {
                                if ($category_from_get_form == $category_from_db['category_url']) {
                                    $category_get_matched_success = true;
                                    $get_hidden_inputs['kategori'] = $category_from_db['category_url'];
                                    $url_for_selected_filters_category = 'kategori=' . $category_from_db['category_url'] . '&';
                                    $this->web_data['selected_filters']['Kategori'] = array('name' => $category_from_db['category_name'], 'url_name' => $category_from_db['category_url']);
                                    $categories_from_db[$key]['selected'] = true;
                                    $item_conditions .= ' AND category=?';
                                    $item_bind_params[] = $category_from_db['id'];
                                    break;
                                }
                            }
                            if ($category_get_matched_success) {
                                $this->web_data['filter_categories'] = array('categories' => $categories_from_db, 'selected' => true);
                            } else {
                                $this->input_control->CheckUrl(array('renk', 'beden', 'kategori', 'min-fiyat', 'max-fiyat'));
                            }
                        } else {
                            $this->web_data['filter_categories'] = array('categories' => $categories_from_db);
                        }
                    }
                    $colors_from_db = $this->FilterModel->GetAllColors();
                    if (!empty($colors_from_db)) {
                        if (!empty($_GET['renk'])) {
                            $color_from_get_form = $this->input_control->Check_GET_Input($_GET['renk']);
                        }
                        $color_get_matched_success = false;
                        foreach ($colors_from_db as $key => $color_from_db) {
                            $item_columns .= ',' . $color_from_db['color_url'];
                            if (!empty($color_from_get_form) && $color_from_get_form == $color_from_db['color_url']) {
                                $color_get_matched_success = true;
                                $get_hidden_inputs['renk'] = $color_from_db['color_url'];
                                $url_for_selected_filters_color = 'renk=' . $color_from_db['color_url'] . '&';
                                $this->web_data['selected_filters']['Renk'] = array('name' => $color_from_db['color_name'], 'url_name' => $color_from_db['color_url']);
                                $colors_from_db[$key]['selected'] = true;
                                $item_conditions .= ' AND ' . $color_from_db['color_url'] . '=1';
                            }
                        }
                        if (!empty($color_from_get_form)) {
                            if ($color_get_matched_success) {
                                $this->web_data['filter_colors'] = array('colors' => $colors_from_db, 'selected' => true);
                            } else {
                                $this->input_control->CheckUrl(array('beden', 'kategori', 'min-fiyat', 'max-fiyat'));
                            }
                        } else {
                            $this->web_data['filter_colors'] = array('colors' => $colors_from_db);
                        }
                    }
                    $sizes_from_db = $this->FilterModel->GetSizes();
                    if (!empty($sizes_from_db)) {
                        if (!empty($_GET['beden'])) {
                            $size_from_get_form = $this->input_control->Check_GET_Input($_GET['beden']);
                        }
                        if (!empty($size_from_get_form)) {
                            $size_get_matched_success = false;
                            foreach ($sizes_from_db as $key => $size_from_db) {
                                if ($size_from_get_form == $size_from_db['size_url']) {
                                    $size_get_matched_success = true;
                                    $get_hidden_inputs['beden'] = $size_from_db['size_url'];
                                    $url_for_selected_filters_size = 'beden=' . $size_from_db['size_url'] . '&';
                                    $this->web_data['selected_filters']['Beden'] = array('name' => $size_from_db['size_name'], 'url_name' => $size_from_db['size_url']);
                                    $sizes_from_db[$key]['selected'] = true;
                                    $item_conditions .= ' AND ' . $size_from_db['size_url'] . '>=1';
                                    break;
                                }
                            }
                            if ($size_get_matched_success) {
                                $this->web_data['filter_sizes'] = array('sizes' => $sizes_from_db, 'selected' => true);
                            } else {
                                $this->input_control->CheckUrl(array('renk', 'kategori', 'min-fiyat', 'max-fiyat'));
                            }
                        } else {
                            $this->web_data['filter_sizes'] = array('sizes' => $sizes_from_db);
                        }
                    }
                    parent::GetModel('ItemModel');
                    $prices_from_db = $this->ItemModel->GetMinAndMaxPrices();
                    if (!empty($prices_from_db['MAX(item_discount_price)'])) {
                        $int_max_price = ceil($prices_from_db['MAX(item_discount_price)']);
                        $price_coefficient = ceil(ceil($int_max_price / 5) / 100) * 100;
                        $temp_min_price = 0;
                        $new_prices_from_db = array();
                        while ($temp_min_price < $int_max_price) {
                            $temp_max_price = $temp_min_price + $price_coefficient;
                            if ($temp_max_price > $int_max_price) {
                                $temp_max_price = $int_max_price;
                            }
                            $new_prices_from_db[] = array('price_name' => $temp_min_price . ' - ' . $temp_max_price, 'min_price_url' => $temp_min_price, 'max_price_url' => $temp_max_price);
                            $temp_min_price = $temp_max_price;
                        }
                        if (!empty($new_prices_from_db)) {
                            if (isset($_GET['min-fiyat']) && isset($_GET['max-fiyat'])) {
                                if ($_GET['min-fiyat'] == 0) {
                                    $min_price_from_get_form = 0;
                                } else {
                                    $min_price_from_get_form = $this->input_control->Check_GET_Input($_GET['min-fiyat']);
                                    if (!is_null($min_price_from_get_form)) {
                                        $min_price_from_get_form = $this->input_control->IsIntegerAndPositive($min_price_from_get_form);
                                    }
                                }
                                if ($_GET['max-fiyat'] == 0) {
                                    $max_price_from_get_form = 0;
                                } else {
                                    $max_price_from_get_form = $this->input_control->Check_GET_Input($_GET['max-fiyat']);
                                    if (!is_null($max_price_from_get_form)) {
                                        $max_price_from_get_form = $this->input_control->IsIntegerAndPositive($max_price_from_get_form);
                                    }
                                }
                                if (!empty($min_price_from_get_form) && !empty($max_price_from_get_form)) {
                                    $get_hidden_inputs['min-fiyat'] = $min_price_from_get_form;
                                    $get_hidden_inputs['max-fiyat'] = $max_price_from_get_form;
                                    $url_for_selected_filters_price = 'min-fiyat=' . $min_price_from_get_form . '&max-fiyat=' . $max_price_from_get_form . '&';
                                    $this->web_data['selected_filters']['Fiyat'] = array('name' => $min_price_from_get_form . ' - ' . $max_price_from_get_form, 'url_name' => $min_price_from_get_form);
                                    $item_conditions .= ' AND item_discount_price>=? AND item_discount_price<=?';
                                    $item_bind_params[] = $min_price_from_get_form;
                                    $item_bind_params[] = $max_price_from_get_form;
                                    $this->web_data['filter_prices']['selected'] = true;
                                    foreach ($new_prices_from_db as $key => $price_from_db) {
                                        if ($price_from_db['min_price_url'] == $min_price_from_get_form && $price_from_db['max_price_url'] == $max_price_from_get_form) {
                                            $new_prices_from_db[$key]['selected'] = true;
                                            break;
                                        }
                                    }
                                } else {
                                    $this->input_control->CheckUrl(array('renk', 'beden', 'kategori'));
                                }
                            }
                            $this->web_data['filter_prices']['prices'] = $new_prices_from_db;
                        }
                    }
                    $url_for_selected_filters = URL . URL_ITEMS . '/' . $selected_gender['gender_url'] . '?';
                    if (!empty($this->web_data['selected_filters']['Kategori'])) {
                        $selected_filters_remove_url = $url_for_selected_filters . $url_for_selected_filters_color . $url_for_selected_filters_size . $url_for_selected_filters_price;
                        if (substr($selected_filters_remove_url, -1) == '?') {
                            $this->web_data['selected_filters']['Kategori']['url'] = rtrim($selected_filters_remove_url, '?');
                        } elseif (substr($selected_filters_remove_url, -1) == '&') {
                            $this->web_data['selected_filters']['Kategori']['url'] = rtrim($selected_filters_remove_url, '&');
                        } else {
                            $this->web_data['selected_filters']['Kategori']['url'] = $selected_filters_remove_url;
                        }
                    }
                    if (!empty($this->web_data['selected_filters']['Renk'])) {
                        $selected_filters_remove_url = $url_for_selected_filters . $url_for_selected_filters_category . $url_for_selected_filters_size . $url_for_selected_filters_price;
                        if (substr($selected_filters_remove_url, -1) == '?') {
                            $this->web_data['selected_filters']['Renk']['url'] = rtrim($selected_filters_remove_url, '?');
                        } elseif (substr($selected_filters_remove_url, -1) == '&') {
                            $this->web_data['selected_filters']['Renk']['url'] = rtrim($selected_filters_remove_url, '&');
                        } else {
                            $this->web_data['selected_filters']['Renk']['url'] = $selected_filters_remove_url;
                        }
                    }
                    if (!empty($this->web_data['selected_filters']['Beden'])) {
                        $selected_filters_remove_url = $url_for_selected_filters . $url_for_selected_filters_category . $url_for_selected_filters_color . $url_for_selected_filters_price;
                        if (substr($selected_filters_remove_url, -1) == '?') {
                            $this->web_data['selected_filters']['Beden']['url'] = rtrim($selected_filters_remove_url, '?');
                        } elseif (substr($selected_filters_remove_url, -1) == '&') {
                            $this->web_data['selected_filters']['Beden']['url'] = rtrim($selected_filters_remove_url, '&');
                        } else {
                            $this->web_data['selected_filters']['Beden']['url'] = $selected_filters_remove_url;
                        }
                    }
                    if (!empty($this->web_data['selected_filters']['Fiyat'])) {
                        $selected_filters_remove_url = $url_for_selected_filters . $url_for_selected_filters_category . $url_for_selected_filters_size . $url_for_selected_filters_color;
                        if (substr($selected_filters_remove_url, -1) == '?') {
                            $this->web_data['selected_filters']['Fiyat']['url'] = rtrim($selected_filters_remove_url, '?');
                        } elseif (substr($selected_filters_remove_url, -1) == '&') {
                            $this->web_data['selected_filters']['Fiyat']['url'] = rtrim($selected_filters_remove_url, '&');
                        } else {
                            $this->web_data['selected_filters']['Fiyat']['url'] = $selected_filters_remove_url;
                        }
                    }
                    if (!empty($get_hidden_inputs)) {
                        $this->web_data['get_hidden_inputs'] = $get_hidden_inputs;
                    }
                    $items_from_db = $this->ItemModel->GetItems($item_columns, $item_conditions, $item_bind_params);
                    if (!empty($items_from_db)) {
                        foreach ($items_from_db as $key => $item_from_db) {
                            $item_images = explode('_', $item_from_db['item_images']);
                            $item_image_name = array();
                            for ($i = 0; $i < count($item_images); $i++) {
                                $item_image_name[] = explode('-', $item_images[$i]);
                            }
                            $items_from_db[$key]['item_images'] = $item_image_name[0][1];
                        }
                        $this->web_data['items'] = $items_from_db;
                    } else {
                        $this->web_data['items_not_found'] = true;
                    }
                    $this->GetView('Home/Items', $this->web_data);
                }
            }
        }
        $this->input_control->Redirect();
    }
    function ItemDetails(string $item_url)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            parent::GetModel('FilterModel');
            $genders_from_db = $this->FilterModel->GetAllGenders('gender_name,gender_url');
            if (!empty($genders_from_db)) {
                $this->web_data['filter_genders'] = $genders_from_db;
            }
            $this->input_control->CheckUrl();
            parent::GetModel('ItemModel');
            $checked_item_url = $this->input_control->Check_GET_Input($item_url);
            if (!is_null($checked_item_url)) {
                $item_details_from_db = $this->ItemModel->GetItemDetails($checked_item_url);
                if (!empty($item_details_from_db)) {
                    $this->web_data['item'] = $this->input_control->GetItemsImages($item_details_from_db);
                    $relevant_items_from_db = $this->ItemModel->GetRelevantItems(array($item_details_from_db['category'], $item_details_from_db['id']));
                    if (!empty($relevant_items_from_db)) {
                        $this->web_data['relevant_items'] = $this->input_control->GetItemsMainImage($relevant_items_from_db);
                    }
                    $sizes_from_db = $this->FilterModel->GetSizes();
                    if (!empty($sizes_from_db)) {
                        $this->web_data['sizes'] = $sizes_from_db;
                        parent::GetModel('CommentModel');
                        if (!empty($this->web_data['authed_user'])) {
                            if ($this->web_data['authed_user']['user_role'] == ADMIN_ROLE_ID) {
                                $comments_from_db = $this->CommentModel->GetCommentsForAdminByItemId($item_details_from_db['id']);
                            } else {
                                $comments_from_db = $this->CommentModel->GetCommentsForUserByItemId(array($item_details_from_db['id'], $this->web_data['authed_user']['id']));
                            }
                        } else {
                            $comments_from_db = $this->CommentModel->GetCommentsForAnonymousByItemId($item_details_from_db['id']);
                        }
                        if (!empty($comments_from_db)) {
                            foreach ($comments_from_db as $key => $comment_from_db) {
                                if (!empty($this->web_data['authed_user']) && $comment_from_db['user_id'] == $this->web_data['authed_user']['id']) {
                                    $this->web_data['user_has_comment'] = true;
                                }
                                parent::GetModel('UserModel');
                                $user_comment_from_db = $this->UserModel->GetUserById('first_name,last_name,profile_image_path,profile_image', $comment_from_db['user_id']);
                                if (!empty($user_comment_from_db)) {
                                    $comments_from_db[$key]['user_id'] = $comment_from_db['user_id'];
                                    $comments_from_db[$key]['user_first_name'] = $user_comment_from_db['first_name'];
                                    $comments_from_db[$key]['user_last_name'] = $user_comment_from_db['last_name'];
                                    $comments_from_db[$key]['user_profile_image_path'] = $user_comment_from_db['profile_image_path'];
                                    $comments_from_db[$key]['user_profile_image'] = $user_comment_from_db['profile_image'];
                                    if (!empty($this->web_data['authed_user'])) {
                                        if ($this->web_data['authed_user']['user_role'] == ADMIN_ROLE_ID) {
                                            $comments_reply_from_db = $this->CommentModel->GetCommentsReplyForAdminByCommentId($comment_from_db['id']);
                                        } else {
                                            $comments_reply_from_db = $this->CommentModel->GetCommentsReplyForUserByCommentId(array($comment_from_db['id'], $this->web_data['authed_user']['id']));
                                        }
                                    } else {
                                        $comments_reply_from_db = $this->CommentModel->GetCommentsReplyForAnonymousByCommentId($comment_from_db['id']);
                                    }
                                    if (!empty($comments_reply_from_db)) {
                                        foreach ($comments_reply_from_db as $key2 => $comment_reply_from_db) {
                                            if (!empty($this->web_data['authed_user']) && $comment_reply_from_db['user_id'] == $this->web_data['authed_user']['id']) {
                                                $this->web_data['user_has_comment_reply'] = true;
                                            }
                                            $user_comment_reply_from_db = $this->UserModel->GetUserById('first_name,last_name,profile_image_path,profile_image', $comment_reply_from_db['user_id']);
                                            if (!empty($user_comment_reply_from_db)) {
                                                $comments_reply_from_db[$key2]['user_id'] = $comment_reply_from_db['user_id'];
                                                $comments_reply_from_db[$key2]['user_first_name'] = $user_comment_reply_from_db['first_name'];
                                                $comments_reply_from_db[$key2]['user_last_name'] = $user_comment_reply_from_db['last_name'];
                                                $comments_reply_from_db[$key2]['user_profile_image_path'] = $user_comment_reply_from_db['profile_image_path'];
                                                $comments_reply_from_db[$key2]['user_profile_image'] = $user_comment_reply_from_db['profile_image'];
                                            } else {
                                                unset($comments_reply_from_db[$key2]);
                                            }
                                        }
                                        if (!empty($comments_reply_from_db)) {
                                            $comments_from_db[$key]['comments_reply'] = $comments_reply_from_db;
                                        }
                                    }
                                } else {
                                    unset($comments_from_db[$key]);
                                }
                            }
                            if (!empty($comments_from_db)) {
                                $this->web_data['comments'] = $comments_from_db;
                            } else {
                                $this->web_data['no_comment_found'] = true;
                            }
                        } else {
                            $this->web_data['no_comment_found'] = true;
                        }
                        $csrf_token = $this->SetCSRFToken('ItemDetails');
                        if (!is_null($csrf_token)) {
                            $this->web_data['csrf_token'] = $csrf_token;
                        } else {
                            $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(SET_CSRF_ERROR);
                        }
                        $this->GetView('Home/ItemDetails', $this->web_data);
                    }
                }
            }
            $this->GetView('Home/ItemDetailsNotFound');
        }
        $this->input_control->Redirect();
    }
    function ShoppingCart()
    {
        if (isset($_COOKIE[COOKIE_CART_NAME])) {
            $cookie_items = $this->cookie_control->GetCookie($_COOKIE[COOKIE_CART_NAME]);
            if (!empty($cookie_items)) {
                $cookie_items = json_decode($cookie_items, true);
                if (!empty($cookie_items)) {
                    $cart_items = array();
                    foreach ($cookie_items as $key => $cookie_item) {
                        if ($cookie_item['item_quantity'] > 0 && $cookie_item['item_quantity'] <= 10) {
                        } else {
                            $this->cookie_control->EmptyCookie(COOKIE_AUTH_NAME);
                            $this->web_data['notfound_cookie'] = $this->notification_control->NotFound("Sepetinizde Ürün Yok");
                        }
                        parent::GetModel('ItemModel');
                        $item_in_cart = $this->ItemModel->GetItemById('id,item_name,item_url,item_price,item_discount_price,item_images,beden_' . $cookie_item['item_size'], $cookie_item['item_id']);
                        if (!empty($item_in_cart)) {
                            $item_images = explode('_', $item_in_cart['item_images']);
                            $item_image_name = array();
                            for ($i = 0; $i < count($item_images); $i++) {
                                $item_image_name[] = explode('-', $item_images[$i]);
                            }
                            $item_in_cart['item_images'] = $item_image_name[0][1];
                            $item = array(
                                'item_propeties' => $item_in_cart,
                                'item_size' => $cookie_item['item_size'],
                                'item_quantity' => $cookie_item['item_quantity'],
                            );
                            $cart_items[] = $item;
                        } else {
                            $this->input_control->Redirect();
                        }
                    }
                    if (!empty($cart_items)) {
                        $this->web_data['cart_items'] = $cart_items;
                    } else {
                        $this->cookie_control->EmptyCookie(COOKIE_AUTH_NAME);
                        $this->web_data['notfound_cookie'] = $this->notification_control->NotFound("Sepetinizde Ürün Yok");
                    }
                } else {
                    $this->cookie_control->EmptyCookie(COOKIE_AUTH_NAME);
                    $this->web_data['notfound_cookie'] = $this->notification_control->NotFound("Sepetinizde Ürün Yok");
                }
            } else {
                $this->cookie_control->EmptyCookie(COOKIE_AUTH_NAME);
                $this->web_data['notfound_cookie'] = $this->notification_control->NotFound("Sepetinizde Ürün Yok");
            }
        } else {
            $this->web_data['notfound_cookie'] = $this->notification_control->NotFound("Sepetinizde Ürün Yok");
        }
        $this->GetView('Home/ShoppingCart', $this->web_data);
    }
}
