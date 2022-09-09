<?php
class HomeController extends Controller
{
    function __construct()
    {
        parent::__construct();
        // user_block
        // if (!empty($_SESSION[SESSION_AUTHENTICATION_NAME]) && !empty($_COOKIE[COOKIE_AUTHENTICATION_NAME])) {
        //     $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
        //     $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
        //     $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
        //     $this->input_control->Redirect(URL_LOGIN);
        // } elseif (!empty($_SESSION[SESSION_AUTHENTICATION_NAME])) {
        //     $session_authentication_error = true;
        //     $checked_session_authentication_token = $this->input_control->CheckInputWithLength($_SESSION[SESSION_AUTHENTICATION_NAME], 255);
        //     if (!empty($checked_session_authentication_token)) {
        //         $session_authentication_from_database = $this->ActionModel->GetSessionAuthentication(array($_SERVER['REMOTE_ADDR'], $checked_session_authentication_token));
        //         if (!empty($session_authentication_from_database) && $session_authentication_from_database['date_session_authentication_expiry'] > date('Y-m-d H:i:s') && $session_authentication_from_database['session_authentication_is_logout'] == 0) {
        //             $authenticated_user_from_database = $this->UserModel->GetUserByUserId('id,user_role', $session_authentication_from_database['user_id']);
        //             if (!empty($authenticated_user_from_database)) {
        //                 if (session_regenerate_id(true)) {
        //                     $session_authentication_error = false;
        //                     $this->web_data['authenticated_user'] = $authenticated_user_from_database;
        //                 }
        //             }
        //             if ($session_authentication_error && $this->ActionModel->UpdateSessionAuthentication(array('is_session_authentication_killed' => 1, 'session_authentication_killed_function' => 'HomeController __construct', 'date_session_authentication_killed' => date('Y-m-d H:i:s'), 'id' => $session_authentication_from_database['id'])) == 'Updated') {
        //                 $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
        //                 $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
        //                 $this->input_control->Redirect(URL_LOGIN);
        //             }
        //         }
        //     }
        //     if ($session_authentication_error) {
        //         $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
        //         $this->input_control->Redirect();
        //     }
        // } elseif (!empty($_COOKIE[COOKIE_AUTHENTICATION_NAME])) {
        //     $cookie_authentication_error = true;
        //     $checked_cookie_authentication = $this->input_control->CheckInputWithLength($_COOKIE[COOKIE_AUTHENTICATION_NAME], 500);
        //     if (!empty($checked_cookie_authentication)) {
        //         $cookie_authentication_from_database = $this->ActionModel->GetCookieAuthentication(array($_SERVER['REMOTE_ADDR'], substr($checked_cookie_authentication, 0, 247)));
        //         if (!empty($cookie_authentication_from_database) && $cookie_authentication_from_database['date_cookie_authentication_expiry'] > date('Y-m-d H:i:s') && $cookie_authentication_from_database['is_cookie_authentication_logout'] == 0) {
        //             try {
        //                 $cookie_authentication_token1 = hash_hmac('SHA512', substr($checked_cookie_authentication, 247, 253), $cookie_authentication_from_database['cookie_authentication_salt'], false);
        //                 if (hash_equals($cookie_authentication_from_database['cookie_authentication_token1'], $cookie_authentication_token1)) {
        //                     $authenticated_user_from_database = $this->UserModel->GetUserByUserId('id,user_role', $cookie_authentication_from_database['user_id']);
        //                     if (!empty($authenticated_user_from_database)) {
        //                         if (session_regenerate_id(true)) {
        //                             $cookie_authentication_error = false;
        //                             $this->web_data['authenticated_user'] = $authenticated_user_from_database;
        //                         }
        //                     }
        //                 }
        //                 if ($cookie_authentication_error && $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME) && $this->ActionModel->UpdateCookieAuthentication(array('is_cookie_authentication_killed' => 1, 'cookie_authentication_killed_function' => 'HomeController __construct', 'date_cookie_authentication_killed' => date('Y-m-d H:i:s'), 'id' => $cookie_authentication_from_database['id'])) == 'Updated') {
        //                     $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
        //                     $this->input_control->Redirect(URL_LOGIN);
        //                 }
        //             } catch (\Throwable $th) {
        //                 $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function __construct COOKIE | ' . $th));
        //                 $this->GetView('Error/NotResponse');
        //             }
        //         }
        //     }
        //     if ($cookie_authentication_error) {
        //         $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
        //         $this->input_control->Redirect();
        //     }
        // }

        // $this->web_data['authenticated_user'] = '2BEV2kDDjyZ1Qb3APgxurvkDrW7biovabxUNnNJgd1B1X4sF12oVCKd9xKNIKcDEuzZzODIw7IcuEqYFgmudVWxnLPA5OJyZexfrTpQTqbntSMHx2um2j740phOJqmQERfUqkj0JobJyeiS5G0k7LySjiBEVtAbvhQm8HgdrlPG6KS4vthDRUIo9GuJhooVBBlJgyrJxCrPjJCclTgNUvHLJlOCiF7ddGaz3JX8LjE2NTg1Njk4Mzc4NTU';
    }
    function Index()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::LogView('Home-Index');
                $home_items = $this->ItemModel->GetHomeItems();
                if ($home_items['result']) {
                    $formatted_home_items = $this->input_control->GetItemsMainImageAndFormatedPrice($home_items['data']);
                    if ($formatted_home_items['result']) {
                        $this->web_data['home_items'] = $formatted_home_items['data'];
                    }
                }
                $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url,gender_home_image');
                parent::GetView('Home/Index', $this->web_data);
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function Index | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Items(string $input_gender)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
                $checked_input_gender = $this->input_control->CheckGETInput($input_gender);
                if (!is_null($checked_input_gender)) {
                    $this->input_control->CheckUrl(array('renk', 'beden', 'kategori', 'min-fiyat', 'max-fiyat'));
                    $genders_from_database = parent::GetGenders('id,gender_keywords,gender_description,gender_name,gender_url');
                    foreach ($genders_from_database as $key => $gender_from_database) {
                        if ($gender_from_database['gender_url'] == $checked_input_gender) {
                            $selected_gender = $gender_from_database;
                        }
                        unset($genders_from_database[$key]['id']);
                        unset($genders_from_database[$key]['gender_description']);
                        unset($genders_from_database[$key]['gender_keywords']);
                    }
                    if (!empty($selected_gender)) {
                        parent::LogView('Home-Items-' . $selected_gender['gender_url']);
                        $this->web_data['genders'] = $genders_from_database;
                        $this->web_data['selected_gender_name'] = $selected_gender['gender_name'];
                        $this->web_data['selected_gender_url'] = $selected_gender['gender_url'];
                        $this->web_data['selected_gender_description'] = $selected_gender['gender_description'];
                        $this->web_data['selected_gender_keywords'] = $selected_gender['gender_keywords'];
                        $item_columns = 'item_name,item_url,item_price,item_discount_price,item_images_path,item_images';
                        $item_conditions = 'WHERE gender=?';
                        $item_bind_params = array($selected_gender['id']);
                        $get_hidden_inputs = array();
                        $url_for_selected_filters_category = '';
                        $url_for_selected_filters_color = '';
                        $url_for_selected_filters_size = '';
                        $url_for_selected_filters_price = '';
                        $this->web_data['selected_filters'] = array();
                        $categories_from_database = $this->FilterModel->GetCategories();
                        if ($categories_from_database['result']) {
                            if (!empty($_GET['kategori'])) {
                                $category_from_get_form = $this->input_control->CheckGETInput($_GET['kategori']);
                            }
                            if (!empty($category_from_get_form)) {
                                $category_get_matched_success = false;
                                foreach ($categories_from_database['data'] as $key => $category_from_db) {
                                    if ($category_from_get_form == $category_from_db['category_url']) {
                                        $category_get_matched_success = true;
                                        $get_hidden_inputs['kategori'] = $category_from_db['category_url'];
                                        $url_for_selected_filters_category = 'kategori=' . $category_from_db['category_url'] . '&';
                                        $this->web_data['selected_filters']['Kategori'] = array('name' => $category_from_db['category_name'], 'url_name' => $category_from_db['category_url']);
                                        $categories_from_database['data'][$key]['selected'] = true;
                                        $item_conditions .= ' AND category=?';
                                        $item_bind_params[] = $category_from_db['id'];
                                        break;
                                    }
                                }
                                if ($category_get_matched_success) {
                                    $this->web_data['filter_categories'] = array('categories' => $categories_from_database['data'], 'selected' => true);
                                } else {
                                    $this->input_control->CheckUrl(array('renk', 'beden', 'min-fiyat', 'max-fiyat'));
                                }
                            } else {
                                $this->web_data['filter_categories'] = array('categories' => $categories_from_database['data']);
                            }
                        }
                        $colors_from_database = $this->FilterModel->GetColors();
                        if ($colors_from_database['result']) {
                            if (!empty($_GET['renk'])) {
                                $color_from_get_form = $this->input_control->CheckGETInput($_GET['renk']);
                            }
                            $color_get_matched_success = false;
                            foreach ($colors_from_database['data'] as $key => $color_from_database) {
                                $item_columns .= ',' . $color_from_database['color_url'];
                                if (!empty($color_from_get_form) && $color_from_get_form == $color_from_database['color_url']) {
                                    $color_get_matched_success = true;
                                    $get_hidden_inputs['renk'] = $color_from_database['color_url'];
                                    $url_for_selected_filters_color = 'renk=' . $color_from_database['color_url'] . '&';
                                    $this->web_data['selected_filters']['Renk'] = array('name' => $color_from_database['color_name'], 'url_name' => $color_from_database['color_url']);
                                    $colors_from_database['data'][$key]['selected'] = true;
                                    $item_conditions .= ' AND ' . $color_from_database['color_url'] . '=1';
                                }
                            }
                            if (!empty($color_from_get_form)) {
                                if ($color_get_matched_success) {
                                    $this->web_data['filter_colors'] = array('colors' => $colors_from_database['data'], 'selected' => true);
                                } else {
                                    $this->input_control->CheckUrl(array('beden', 'kategori', 'min-fiyat', 'max-fiyat'));
                                }
                            } else {
                                $this->web_data['filter_colors'] = array('colors' => $colors_from_database['data']);
                            }
                        }
                        $sizes_from_database = $this->FilterModel->GetSizes();
                        if ($sizes_from_database['result']) {
                            if (!empty($_GET['beden'])) {
                                $size_from_get_form = $this->input_control->CheckGETInput($_GET['beden']);
                            }
                            if (!empty($size_from_get_form)) {
                                $size_get_matched_success = false;
                                foreach ($sizes_from_database['data'] as $key => $size_from_database) {
                                    if ($size_from_get_form == $size_from_database['size_url']) {
                                        $size_get_matched_success = true;
                                        $get_hidden_inputs['beden'] = $size_from_database['size_url'];
                                        $url_for_selected_filters_size = 'beden=' . $size_from_database['size_url'] . '&';
                                        $this->web_data['selected_filters']['Beden'] = array('name' => $size_from_database['size_name'], 'url_name' => $size_from_database['size_url']);
                                        $sizes_from_database['data'][$key]['selected'] = true;
                                        $item_conditions .= ' AND ' . $size_from_database['size_url'] . '>=1';
                                        break;
                                    }
                                }
                                if ($size_get_matched_success) {
                                    $this->web_data['filter_sizes'] = array('sizes' => $sizes_from_database['data'], 'selected' => true);
                                } else {
                                    $this->input_control->CheckUrl(array('renk', 'kategori', 'min-fiyat', 'max-fiyat'));
                                }
                            } else {
                                $this->web_data['filter_sizes'] = array('sizes' => $sizes_from_database['data']);
                            }
                        }
                        $prices_from_database = $this->FilterModel->GetMaxPrice();
                        if ($prices_from_database['result'] && !empty($prices_from_database['data']['MAX(item_discount_price)'])) {
                            $int_max_price = ceil($prices_from_database['data']['MAX(item_discount_price)']);
                            $price_coefficient = ceil(ceil($int_max_price / 5) / 100) * 100;
                            $temp_min_price = 0;
                            $new_prices_from_database = array();
                            while ($temp_min_price < $int_max_price) {
                                $temp_max_price = $temp_min_price + $price_coefficient;
                                if ($temp_max_price > $int_max_price) {
                                    $temp_max_price = $int_max_price;
                                }
                                $new_prices_from_database[] = array('price_name' => $temp_min_price . ' - ' . $temp_max_price, 'min_price_url' => $temp_min_price, 'max_price_url' => $temp_max_price);
                                $temp_min_price = $temp_max_price;
                            }
                            if (!empty($new_prices_from_database)) {
                                if (isset($_GET['min-fiyat']) && isset($_GET['max-fiyat'])) {
                                    $min_price_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['min-fiyat']);
                                    $max_price_from_get_form = $this->input_control->CheckPositiveGETInput($_GET['max-fiyat']);
                                    if (!is_null($min_price_from_get_form) && !is_null($max_price_from_get_form)) {
                                        $get_hidden_inputs['min-fiyat'] = $min_price_from_get_form;
                                        $get_hidden_inputs['max-fiyat'] = $max_price_from_get_form;
                                        $url_for_selected_filters_price = 'min-fiyat=' . $min_price_from_get_form . '&max-fiyat=' . $max_price_from_get_form . '&';
                                        $this->web_data['selected_filters']['Fiyat'] = array('name' => $min_price_from_get_form . ' - ' . $max_price_from_get_form, 'url_name' => $min_price_from_get_form);
                                        $item_conditions .= ' AND item_discount_price>=? AND item_discount_price<=?';
                                        $item_bind_params[] = $min_price_from_get_form;
                                        $item_bind_params[] = $max_price_from_get_form;
                                        $this->web_data['filter_prices']['selected'] = true;
                                        foreach ($new_prices_from_database as $key => $price_from_db) {
                                            if ($price_from_db['min_price_url'] == $min_price_from_get_form && $price_from_db['max_price_url'] == $max_price_from_get_form) {
                                                $new_prices_from_database[$key]['selected'] = true;
                                                break;
                                            }
                                        }
                                    } else {
                                        $this->input_control->CheckUrl(array('renk', 'beden', 'kategori'));
                                    }
                                }
                                $this->web_data['filter_prices']['prices'] = $new_prices_from_database;
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
                        $home_jquery_url = $url_for_selected_filters . $url_for_selected_filters_category . $url_for_selected_filters_size . $url_for_selected_filters_color . $url_for_selected_filters_price;
                        if (substr($home_jquery_url, -1) == '?') {
                            $this->web_data['home_jquery_url'] = rtrim($home_jquery_url, '?');
                        } elseif (substr($home_jquery_url, -1) == '&') {
                            $this->web_data['home_jquery_url'] = rtrim($home_jquery_url, '&');
                        } else {
                            $this->web_data['home_jquery_url'] = $home_jquery_url;
                        }
                        if (!empty($get_hidden_inputs)) {
                            $this->web_data['get_hidden_inputs'] = $get_hidden_inputs;
                        }
                        $item_conditions .= ' AND is_item_for_sale=1 AND is_item_deleted=0 ORDER BY date_item_created DESC LIMIT ?';
                        $item_bind_params[] = ITEM_LOAD_LIMIT_IN_ONCE;
                        if (isset($_POST['loadedItemOffSet'])) {
                            $loadedItemOffSet = $this->input_control->CheckPositivePOSTInput($_POST['loadedItemOffSet']);
                            if (!is_null($loadedItemOffSet)) {
                                $item_conditions .= ' OFFSET ?';
                                $item_bind_params[] = $loadedItemOffSet;
                                $items_from_database = $this->ItemModel->GetItems($item_columns, $item_conditions, $item_bind_params);
                                if ($items_from_database['result']) {
                                    $formatted_items = $this->input_control->GetItemsMainImageAndFormatedPrice($items_from_database['data']);
                                    if ($formatted_items['result']) {
                                        $jsoned_items = json_encode($formatted_items['data']);
                                        if (!empty($jsoned_items)) {
                                            echo $jsoned_items;
                                            exit(0);
                                        }
                                    }
                                }
                            }
                            echo '{"stop":"stop"}';
                            exit(0);
                        } else {
                            $items_from_database = $this->ItemModel->GetItems($item_columns, $item_conditions, $item_bind_params);
                            if ($items_from_database['result']) {
                                if ($items_from_database['result']) {
                                    $formatted_items = $this->input_control->GetItemsMainImageAndFormatedPrice($items_from_database['data']);
                                    if ($formatted_items['result']) {
                                        $jsoned_items = json_encode($formatted_items['data']);
                                        if (!empty($jsoned_items)) {
                                            $this->web_data['items'] = $jsoned_items;
                                        }
                                    }
                                }
                            } else {
                                $this->web_data['items_not_found'] = true;
                            }
                            parent::GetView('Home/Items', $this->web_data);
                        }
                    }
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function Items | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ItemDetails(string $item_url)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
                $checked_item_url = $this->input_control->CheckGETInput($item_url);
                if (!is_null($checked_item_url)) {
                    $this->input_control->CheckUrl();
                    $item_details_from_database = $this->ItemModel->GetItemDetails($checked_item_url);
                    $sizes_from_database = $this->FilterModel->GetSizes();
                    if ($item_details_from_database['result'] && $sizes_from_database['result']) {
                        $genders_from_database = parent::GetGenders('id,gender_name,gender_url');
                        foreach ($genders_from_database as $key => $gender_from_database) {
                            if ($gender_from_database['id'] == $item_details_from_database['data']['gender']) {
                                $selected_gender = $gender_from_database;
                            }
                            unset($genders_from_database[$key]['id']);
                        }
                        if (!empty($selected_gender)) {
                            parent::LogView('Home-ItemDetails-' . $item_details_from_database['data']['item_url']);
                            $this->web_data['genders'] = $genders_from_database;
                            $formatted_item = $this->input_control->GetItemImagesAndFormatedPrice($item_details_from_database['data']);
                            if ($formatted_item['result']) {
                                $this->web_data['item'] = $formatted_item['data'];
                                $this->web_data['sizes'] = $sizes_from_database['data'];
                                $this->web_data['selected_gender_name'] = $selected_gender['gender_name'];
                                $this->web_data['selected_gender_url'] = $selected_gender['gender_url'];
                                $relevant_items_from_db = $this->ItemModel->GetRelevantItems(array($item_details_from_database['data']['id'], $item_details_from_database['data']['category']));
                                if ($relevant_items_from_db['result']) {
                                    $formatted_relevant_items = $this->input_control->GetItemsMainImageAndFormatedPrice($relevant_items_from_db['data']);
                                    if ($formatted_relevant_items['result']) {
                                        $this->web_data['relevant_items'] = $formatted_relevant_items['data'];
                                    }
                                }
                                parent::GetModel('CommentModel');
                                if (!empty($this->web_data['authenticated_user'])) {
                                    if ($this->ItemModel->GetFavorite(array($item_details_from_database['data']['id'], $this->web_data['authenticated_user']))['result']) {
                                        $this->web_data['user_favorite_item'] = true;
                                    }
                                    if ($this->web_data['authenticated_user'] == ADMIN_ID) {
                                        $comment_condition = 'WHERE item_id=? AND is_comment_deleted=0 ORDER BY date_comment_created DESC';
                                        $comments_from_db_count = $this->CommentModel->GetCommentsCount($comment_condition, $item_details_from_database['data']['id']);
                                        if ($comments_from_db_count['result'] && $comments_from_db_count['data']['COUNT(id)'] > 0) {
                                            $this->web_data['comment_count'] = $comments_from_db_count['data']['COUNT(id)'];
                                        }
                                        if (!empty($_POST['loadedCommentOffset'])) {
                                            $loadedCommentOffset = $this->input_control->CheckPositivePOSTInput($_POST['loadedCommentOffset']);
                                            if (!is_null($loadedCommentOffset)) {
                                                $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE . ' OFFSET ' . $loadedCommentOffset;
                                                $comments_from_db = $this->CommentModel->GetComments('id,user_id,comment,is_comment_approved,date_comment_created', $comment_condition, $item_details_from_database['data']['id']);
                                                if ($comments_from_db['result'] && !empty($comments_from_db['empty'])) {
                                                    echo '{"stop":"stop"}';
                                                    exit(0);
                                                }
                                            } else {
                                                echo '{"stop":"stop"}';
                                                exit(0);
                                            }
                                        } else {
                                            $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE;
                                            $comments_from_db = $this->CommentModel->GetComments('id,user_id,comment,is_comment_approved,date_comment_created', $comment_condition, $item_details_from_database['data']['id']);
                                        }
                                    } else {
                                        $comment_condition = 'WHERE item_id=? AND is_comment_deleted=0 AND ((user_id=? AND is_comment_approved=0) OR is_comment_approved=1) ORDER BY date_comment_created DESC';
                                        $comments_from_db_count = $this->CommentModel->GetCommentsCount($comment_condition, array($item_details_from_database['data']['id'], $this->web_data['authenticated_user']));
                                        if ($comments_from_db_count['result'] && $comments_from_db_count['data']['COUNT(id)'] > 0) {
                                            $this->web_data['comment_count'] = $comments_from_db_count['data']['COUNT(id)'];
                                        }
                                        if (!empty($_POST['loadedCommentOffset'])) {
                                            $loadedCommentOffset = $this->input_control->CheckPositivePOSTInput($_POST['loadedCommentOffset']);
                                            if (!is_null($loadedCommentOffset)) {
                                                $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE . ' OFFSET ' . $loadedCommentOffset;
                                                $comments_from_db = $this->CommentModel->GetComments('id,user_id,comment,date_comment_created', $comment_condition, array($item_details_from_database['data']['id'], $this->web_data['authenticated_user']));
                                                if ($comments_from_db['result'] && !empty($comments_from_db['empty'])) {
                                                    echo '{"stop":"stop"}';
                                                    exit(0);
                                                }
                                            } else {
                                                echo '{"stop":"stop"}';
                                                exit(0);
                                            }
                                        } else {
                                            $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE;
                                            $comments_from_db = $this->CommentModel->GetComments('id,user_id,comment,date_comment_created', $comment_condition, array($item_details_from_database['data']['id'], $this->web_data['authenticated_user']));
                                        }
                                    }
                                } else {
                                    $comment_condition = 'WHERE item_id=? AND is_comment_approved=1 AND is_comment_deleted=0 ORDER BY date_comment_created DESC';
                                    $comments_from_db_count = $this->CommentModel->GetCommentsCount($comment_condition, $item_details_from_database['data']['id']);
                                    if ($comments_from_db_count['result'] && $comments_from_db_count['data']['COUNT(id)'] > 0) {
                                        $this->web_data['comment_count'] = $comments_from_db_count['data']['COUNT(id)'];
                                    }
                                    if (!empty($_POST['loadedCommentOffset'])) {
                                        $loadedCommentOffset = $this->input_control->CheckPositivePOSTInput($_POST['loadedCommentOffset']);
                                        if (!is_null($loadedCommentOffset)) {
                                            $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE . ' OFFSET ' . $loadedCommentOffset;
                                            $comments_from_db = $this->CommentModel->GetComments('id,user_id,comment,date_comment_created', $comment_condition, $item_details_from_database['data']['id']);
                                            if ($comments_from_db['result'] && !empty($comments_from_db['empty'])) {
                                                echo '{"stop":"stop"}';
                                                exit(0);
                                            }
                                        } else {
                                            echo '{"stop":"stop"}';
                                            exit(0);
                                        }
                                    } else {
                                        $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE;
                                        $comments_from_db = $this->CommentModel->GetComments('id,user_id,comment,date_comment_created', $comment_condition, $item_details_from_database['data']['id']);
                                    }
                                }
                                if ($comments_from_db['result']) {
                                    foreach ($comments_from_db['data'] as $key => $comment_from_db) {
                                        if (!empty($this->web_data['authenticated_user']) && $comment_from_db['user_id'] == $this->web_data['authenticated_user']) {
                                            $this->web_data['user_has_comment'] = true;
                                        }
                                        $user_comment_from_db = $this->UserModel->GetUserByUserId('first_name,last_name,profile_image_path,profile_image', $comment_from_db['user_id']);
                                        if ($user_comment_from_db['result']) {
                                            $comments_from_db['data'][$key]['date_comment_created'] = date('d/m/Y', strtotime($comments_from_db['data'][$key]['date_comment_created']));
                                            $comments_from_db['data'][$key]['user_id'] = $comment_from_db['user_id'];
                                            $comments_from_db['data'][$key]['user_first_name'] = $user_comment_from_db['data']['first_name'];
                                            $comments_from_db['data'][$key]['user_last_name'] = $user_comment_from_db['data']['last_name'];
                                            $comments_from_db['data'][$key]['user_profile_image_path'] = $user_comment_from_db['data']['profile_image_path'];
                                            $comments_from_db['data'][$key]['user_profile_image'] = $user_comment_from_db['data']['profile_image'];
                                            if (!empty($this->web_data['authenticated_user'])) {
                                                if ($this->web_data['authenticated_user'] == ADMIN_ID) {
                                                    $comments_reply_from_db = $this->CommentModel->GetCommentsReply('id,comment_id,user_id,comment_reply,is_comment_reply_approved,date_comment_reply_created', 'WHERE comment_id=? AND is_comment_reply_deleted=0 ORDER BY date_comment_reply_created ASC', $comment_from_db['id']);
                                                } else {
                                                    $comments_reply_from_db = $this->CommentModel->GetCommentsReply('id,comment_id,user_id,comment_reply,date_comment_reply_created', 'WHERE comment_id=? AND ((user_id=? AND is_comment_reply_approved=0) OR is_comment_reply_approved=1) AND is_comment_reply_deleted=0 ORDER BY date_comment_reply_created ASC', array($comment_from_db['id'], $this->web_data['authenticated_user']));
                                                }
                                            } else {
                                                $comments_reply_from_db = $this->CommentModel->GetCommentsReply('id,comment_id,user_id,comment_reply,date_comment_reply_created', 'WHERE comment_id=? AND is_comment_reply_approved=1 AND is_comment_reply_deleted=0 ORDER BY date_comment_reply_created ASC', $comment_from_db['id']);
                                            }
                                            if ($comments_reply_from_db['result']) {
                                                foreach ($comments_reply_from_db['data'] as $key2 => $comment_reply_from_db) {
                                                    if (!empty($this->web_data['authenticated_user']) && $comment_reply_from_db['user_id'] == $this->web_data['authenticated_user']) {
                                                        $this->web_data['user_has_comment_reply'] = true;
                                                    }
                                                    $user_comment_reply_from_db = $this->UserModel->GetUserByUserId('first_name,last_name,profile_image_path,profile_image', $comment_reply_from_db['user_id']);
                                                    if ($user_comment_reply_from_db['result']) {
                                                        $comments_reply_from_db['data'][$key2]['date_comment_reply_created'] = date('d/m/Y', strtotime($comments_reply_from_db['data'][$key2]['date_comment_reply_created']));
                                                        $comments_reply_from_db['data'][$key2]['user_id'] = $comment_reply_from_db['user_id'];
                                                        $comments_reply_from_db['data'][$key2]['user_first_name'] = $user_comment_reply_from_db['data']['first_name'];
                                                        $comments_reply_from_db['data'][$key2]['user_last_name'] = $user_comment_reply_from_db['data']['last_name'];
                                                        $comments_reply_from_db['data'][$key2]['user_profile_image_path'] = $user_comment_reply_from_db['data']['profile_image_path'];
                                                        $comments_reply_from_db['data'][$key2]['user_profile_image'] = $user_comment_reply_from_db['data']['profile_image'];
                                                    } else {
                                                        unset($comments_reply_from_db['data'][$key2]);
                                                    }
                                                }
                                                if (!empty($comments_reply_from_db['data'])) {
                                                    $comments_from_db['data'][$key]['comments_reply'] = $comments_reply_from_db['data'];
                                                }
                                            }
                                        } else {
                                            unset($comments_from_db['data'][$key]);
                                        }
                                    }
                                    if (!empty($_POST['loadedCommentOffset']) && !empty($comments_from_db['data'])) {
                                        $jsoned_comment = json_encode($comments_from_db['data']);
                                        if (!empty($jsoned_comment)) {
                                            echo $jsoned_comment;
                                            exit(0);
                                        }
                                        echo '{"stop":"stop"}';
                                        exit(0);
                                    } else {
                                        if (!empty($comments_from_db['data'])) {
                                            $jsoned_comment = json_encode($comments_from_db['data']);
                                            if (!empty($jsoned_comment)) {
                                                $this->web_data['comments'] = $jsoned_comment;
                                            }
                                        } else {
                                            $this->web_data['comment_not_found'] = true;
                                        }
                                    }
                                } else {
                                    if (!empty($_POST['loadedCommentOffset'])) {
                                        echo '{"stop":"stop"}';
                                        exit(0);
                                    } else {
                                        $this->web_data['comment_not_found'] = true;
                                    }
                                }
                                $this->web_data['form_token'] = parent::SetCSRFToken('ItemDetails');
                                parent::GetView('Home/ItemDetails', $this->web_data);
                            }
                        }
                    }
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ItemDetails | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ItemSearch()
    {
        try {
            $response = array();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'search_item' => array('input' => isset($_POST['search_item']) ? $_POST['search_item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    $searched_items_from_database = $this->ItemModel->SearchItem($checked_inputs['search_item']);
                    if ($searched_items_from_database['result']) {
                        $response['searched_items'] = $searched_items_from_database['data'];
                    } else {
                        $response['not_found_search_item'] = true;
                    }
                }
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
            echo '{"stop":"stop"}';
            exit(0);
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ItemSearch | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function Cart()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::LogView('Home-Cart');
                $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                parent::GetView('Home/Cart', $this->web_data);
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function Cart | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function AddCart()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'item_cart_id' => array('input' => isset($_POST['item']) ? $_POST['item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'size_cart_id' => array('input' => isset($_POST['item_size']) ? $_POST['item_size'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_CART_SIZE, 'preventxssforid' => true),
                    'item_quantity' => array('input' => isset($_POST['item_quantity']) ? $_POST['item_quantity'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT)
                ));
                if (empty($checked_inputs['error_message'])) {
                    $confirmed_size = $this->FilterModel->GetSizeBySizeCartId('size_url', $checked_inputs['size_cart_id']);
                    if ($confirmed_size['result']) {
                        $confirmed_item = $this->ItemModel->GetItemByItemCartId($confirmed_size['data']['size_url'], $checked_inputs['item_cart_id']);
                        if ($confirmed_item['result']) {
                            $cart_item_not_setted = true;
                            $cart_items = array();
                            if (!empty($this->web_data['cookie_cart'])) {
                                $cart_items = $this->web_data['cookie_cart'];
                                foreach ($cart_items as $key => $value) {
                                    if (!empty(count($cart_items[$key])) && count($cart_items[$key]) == 3 && !empty($cart_items[$key]['item_cart_id']) && !empty($cart_items[$key]['size_cart_id']) && !empty($cart_items[$key]['item_quantity'])) {
                                        if ($cart_items[$key]['item_cart_id'] == $checked_inputs['item_cart_id'] && $cart_items[$key]['size_cart_id'] == $checked_inputs['size_cart_id']) {
                                            $cart_item_new_quantity = $cart_items[$key]['item_quantity'] + $checked_inputs['item_quantity'];
                                            if ($confirmed_item['data'][$confirmed_size['data']['size_url']] < $cart_item_new_quantity) {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_STOCK_LIMIT);
                                                exit(0);
                                            } elseif ($cart_item_new_quantity <= 10 && $cart_item_new_quantity > 0) {
                                                $cart_item_not_setted = false;
                                                $cart_items[$key]['item_quantity'] = $cart_item_new_quantity;
                                                break;
                                            } elseif ($cart_item_new_quantity > 10) {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_FULL);
                                                exit(0);
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART);
                                                exit(0);
                                            }
                                        }
                                    } else {
                                        unset($cart_items[$key]);
                                    }
                                }
                            }
                            if ($cart_item_not_setted) {
                                if ($confirmed_item['data'][$confirmed_size['data']['size_url']] < $checked_inputs['item_quantity']) {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_STOCK_LIMIT);
                                    exit(0);
                                } elseif ($checked_inputs['item_quantity'] <= 10 && $checked_inputs['item_quantity'] > 0) {
                                    $cart_items[] = array(
                                        'item_cart_id' => $checked_inputs['item_cart_id'],
                                        'size_cart_id' => $checked_inputs['size_cart_id'],
                                        'item_quantity' => $checked_inputs['item_quantity']
                                    );
                                } elseif ($checked_inputs['item_quantity'] > 10) {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_FULL);
                                    exit(0);
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART);
                                    exit(0);
                                }
                            }
                            if (!empty($cart_items)) {
                                $encoded_cart_items = json_encode($cart_items);
                                if (!empty($encoded_cart_items)) {
                                    $setted_cart_cookie = $this->input_control->EncrypteData($encoded_cart_items, COOKIE_CART_PEPPER);
                                    if (strlen($setted_cart_cookie) <= 4000) {
                                        if ($this->cookie_control->SetCookie(COOKIE_CART_NAME, $setted_cart_cookie, time() + (COOKIE_CART_EXPIRY), COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTP_ONLY, COOKIE_SAMESITE)) {
                                            $_SESSION[SESSION_CART_SUCCESS] = true;
                                            exit(0);
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_COOKIE_LIMIT);
                                        exit(0);
                                    }
                                }
                            } elseif (!empty($_COOKIE[COOKIE_CART_NAME])) {
                                $this->cookie_control->EmptyCookie(COOKIE_CART_NAME);
                            }
                        }
                    }
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART);
                    exit(0);
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                    exit(0);
                }
            }
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function AddCart | ' . $th));
        }
        exit(0);
    }
    function UpdateCart()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['increase_cart_quantity']) || isset($_POST['decrease_cart_quantity']))) {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'item_cart_id' => array('input' => isset($_POST['item']) ? $_POST['item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'size_cart_id' => array('input' => isset($_POST['item_size']) ? $_POST['item_size'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    $confirmed_size = $this->FilterModel->GetSizeBySizeCartId('size_url', $checked_inputs['size_cart_id']);
                    if ($confirmed_size['result']) {
                        $confirmed_item = $this->ItemModel->GetItemByItemCartId($confirmed_size['data']['size_url'], $checked_inputs['item_cart_id']);
                        if ($confirmed_item['result']) {
                            $cart_items = array();
                            if (!empty($this->web_data['cookie_cart'])) {
                                $cart_items = $this->web_data['cookie_cart'];
                                foreach ($cart_items as $key => $value) {
                                    if (!empty(count($cart_items[$key])) && count($cart_items[$key]) == 3 && !empty($cart_items[$key]['item_cart_id']) && !empty($cart_items[$key]['size_cart_id']) && !empty($cart_items[$key]['item_quantity'])) {
                                        if ($cart_items[$key]['item_cart_id'] == $checked_inputs['item_cart_id'] && $cart_items[$key]['size_cart_id'] == $checked_inputs['size_cart_id']) {
                                            if (isset($_POST['increase_cart_quantity'])) {
                                                $cart_item_new_quantity = $cart_items[$key]['item_quantity'] + 1;
                                            } elseif (isset($_POST['decrease_cart_quantity'])) {
                                                $cart_item_new_quantity = $cart_items[$key]['item_quantity'] - 1;
                                            } else {
                                                $cart_item_new_quantity = 0;
                                            }
                                            if ($cart_item_new_quantity <= 0) {
                                                unset($cart_items[$key]);
                                            } elseif ($cart_item_new_quantity > 10) {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_FULL);
                                                $this->input_control->Redirect(URL_CART);
                                            } elseif ($confirmed_item['data'][$confirmed_size['data']['size_url']] < $cart_item_new_quantity) {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_STOCK_LIMIT);
                                                $this->input_control->Redirect(URL_CART);
                                            } elseif ($cart_item_new_quantity <= 10 && $cart_item_new_quantity > 0) {
                                                $cart_items[$key]['item_quantity'] = $cart_item_new_quantity;
                                                break;
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_UPDATE_CART);
                                                $this->input_control->Redirect(URL_CART);
                                            }
                                        }
                                    }
                                }
                            }
                            if (!empty($cart_items)) {
                                $encoded_cart_items = json_encode($cart_items);
                                if (!empty($encoded_cart_items)) {
                                    $setted_cart_cookie = $this->input_control->EncrypteData($encoded_cart_items, COOKIE_CART_PEPPER);
                                    if (strlen($setted_cart_cookie) <= 4000) {
                                        if ($this->cookie_control->SetCookie(COOKIE_CART_NAME, $setted_cart_cookie, time() + (COOKIE_CART_EXPIRY), COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTP_ONLY, COOKIE_SAMESITE)) {
                                            $this->input_control->Redirect(URL_CART);
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_COOKIE_LIMIT);
                                        $this->input_control->Redirect(URL_CART);
                                    }
                                }
                            } else {
                                if (!empty($_COOKIE[COOKIE_CART_NAME]) && $this->cookie_control->EmptyCookie(COOKIE_CART_NAME)) {
                                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_EMPTY_CART);
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMPTY_CART);
                                }
                                $this->input_control->Redirect(URL_CART);
                            }
                        }
                    }
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_UPDATE_CART);
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function UpdateCart | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function EmptyCart()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_empty_cart'])) {
                if (!empty($_COOKIE[COOKIE_CART_NAME]) && $this->cookie_control->EmptyCookie(COOKIE_CART_NAME)) {
                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_EMPTY_CART);
                } else {
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMPTY_CART);
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function EmptyCart | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Favorites()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::LogView('Home-Favorites');
                $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                if (!empty($this->web_data['authenticated_user'])) {
                    $item_conditions = 'WHERE user_id=? AND is_favorite_removed=0 ORDER BY date_favorite_created DESC LIMIT ?';
                    $item_bind_params = array($this->web_data['authenticated_user'], ITEM_LOAD_LIMIT_IN_ONCE);
                    if (isset($_POST['loadedItemOffSet'])) {
                        $loadedItemOffSet =  $this->input_control->CheckPositivePOSTInput($_POST['loadedItemOffSet']);
                        if (!is_null($loadedItemOffSet)) {
                            $item_conditions .= ' OFFSET ?';
                            $item_bind_params[] = $loadedItemOffSet;
                            $favorites_from_database = $this->ItemModel->GetFavoritesItemId($item_conditions, $item_bind_params);
                            if ($favorites_from_database['result']) {
                                $favorite_items = array();
                                foreach ($favorites_from_database['data'] as $key => $favorite_from_database) {
                                    $favorite_item_from_db = $this->ItemModel->GetFavoriteItem($favorite_from_database['item_id']);
                                    if ($favorite_item_from_db['result']) {
                                        $favorite_items[] = $favorite_item_from_db['data'];
                                    }
                                }
                                if (!empty($favorite_items)) {
                                    $formatted_favorite_items = $this->input_control->GetItemsMainImageAndFormatedPrice($favorite_items);
                                    if ($formatted_favorite_items['result']) {
                                        $jsoned_favorite_items = json_encode($formatted_favorite_items['data']);
                                        if (!empty($jsoned_favorite_items)) {
                                            echo $jsoned_favorite_items;
                                            exit(0);
                                        }
                                    }
                                }
                            }
                        }
                        echo '{"stop":"stop"}';
                        exit(0);
                    } else {
                        $favorites_from_database = $this->ItemModel->GetFavoritesItemId($item_conditions, $item_bind_params);
                        if ($favorites_from_database['result']) {
                            $favorite_items = array();
                            foreach ($favorites_from_database['data'] as $key => $favorite_from_database) {
                                $favorite_item_from_db = $this->ItemModel->GetFavoriteItem($favorite_from_database['item_id']);
                                if ($favorite_item_from_db['result']) {
                                    $favorite_items[] = $favorite_item_from_db['data'];
                                }
                            }
                            if (!empty($favorite_items)) {
                                $formatted_favorite_items = $this->input_control->GetItemsMainImageAndFormatedPrice($favorite_items);
                                if ($formatted_favorite_items['result']) {
                                    $jsoned_favorite_items = json_encode($formatted_favorite_items['data']);
                                    if (!empty($jsoned_favorite_items)) {
                                        $this->web_data['favorite_items'] = $jsoned_favorite_items;
                                    }
                                }
                            }
                        }
                    }
                }
                parent::GetView('Home/Favorites', $this->web_data);
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function Favorites | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Agreements(string $agreement_url)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $checked_agreement_url = $this->input_control->CheckGETInput($agreement_url);
                if (!is_null($checked_agreement_url)) {
                    $this->input_control->CheckUrl();
                    $case_matched = false;
                    switch ($checked_agreement_url) {
                        case URL_AGREEMENT_TERMS:
                            $case_matched = true;
                            parent::LogView('Home-Agreements-' . URL_AGREEMENT_TERMS);
                            $this->web_data['agreement_type'] = URL_AGREEMENT_TERMS;
                            $this->web_data['agreement_title'] = URL_TERMS_TITLE;
                            break;
                        case URL_AGREEMENT_PRIVACY:
                            $case_matched = true;
                            parent::LogView('Home-Agreements-' . URL_AGREEMENT_PRIVACY);
                            $this->web_data['agreement_type'] = URL_AGREEMENT_PRIVACY;
                            $this->web_data['agreement_title'] = URL_PRIVACY_TITLE;
                            break;
                        case URL_AGREEMENT_RETURN_POLICY:
                            $case_matched = true;
                            parent::LogView('Home-Agreements-' . URL_AGREEMENT_RETURN_POLICY);
                            $this->web_data['agreement_type'] = URL_AGREEMENT_RETURN_POLICY;
                            $this->web_data['agreement_title'] = URL_RETURN_POLICY_TITLE;
                            break;
                    }
                    if ($case_matched) {
                        $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                        parent::GetView('Home/Agreements', $this->web_data);
                    }
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function Agreements | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
}
