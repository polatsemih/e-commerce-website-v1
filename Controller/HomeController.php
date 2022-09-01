<?php
class HomeController extends Controller
{
    function __construct()
    {
        parent::__construct();
        // if (!empty($_SESSION[SESSION_AUTHENTICATION_NAME]) && !empty($_COOKIE[COOKIE_AUTHENTICATION_NAME])) {
        //     $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
        //     $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
        //     $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
        //     $this->input_control->Redirect(URL_LOGIN);
        // } elseif (!empty($_SESSION[SESSION_AUTHENTICATION_NAME])) {
        //     $session_authentication_error = true;
        //     $checked_session_authentication_token = $this->input_control->CheckInputWithLength($_SESSION[SESSION_AUTHENTICATION_NAME], 255);
        //     if (!is_null($checked_session_authentication_token)) {
        //         $session_authentication_from_database = $this->ActionModel->GetSessionAuthentication(array($_SERVER['REMOTE_ADDR'], $checked_session_authentication_token));
        //         if (!empty($session_authentication_from_database) && $session_authentication_from_database['date_session_authentication_expiry'] > date('Y-m-d H:i:s') && $session_authentication_from_database['session_authentication_is_logout'] == 0) {
        //             $authenticated_user_from_database = $this->UserModel->GetUser('id,user_role', $session_authentication_from_database['user_id']);
        //             if (!empty($authenticated_user_from_database)) {
        //                 if (session_regenerate_id(true)) {
        //                     $session_authentication_error = false;
        //                     $this->web_data['authenticated_user'] = $authenticated_user_from_database;
        //                 }
        //             }
        //             if ($session_authentication_error && $this->ActionModel->UpdateSessionAuthentication(array('session_authentication_is_killed' => 1, 'session_authentication_killed_function' => 'HomeController __construct', 'date_session_authentication_killed' => date('Y-m-d H:i:s'), 'id' => $session_authentication_from_database['id'])) == 'Updated') {
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
        //     if (!is_null($checked_cookie_authentication)) {
        //         $cookie_authentication_from_database = $this->ActionModel->GetCookieAuthentication(array($_SERVER['REMOTE_ADDR'], substr($checked_cookie_authentication, 0, 247)));
        //         if (!empty($cookie_authentication_from_database) && $cookie_authentication_from_database['date_cookie_authentication_expiry'] > date('Y-m-d H:i:s') && $cookie_authentication_from_database['cookie_authentication_is_logout'] == 0) {
        //             try {
        //                 $cookie_authentication_token1 = hash_hmac('SHA512', substr($checked_cookie_authentication, 247, 253), $cookie_authentication_from_database['cookie_authentication_salt'], false);
        //                 if (hash_equals($cookie_authentication_from_database['cookie_authentication_token1'], $cookie_authentication_token1)) {
        //                     $authenticated_user_from_database = $this->UserModel->GetUser('id,user_role', $cookie_authentication_from_database['user_id']);
        //                     if (!empty($authenticated_user_from_database)) {
        //                         if (session_regenerate_id(true)) {
        //                             $cookie_authentication_error = false;
        //                             $this->web_data['authenticated_user'] = $authenticated_user_from_database;
        //                         }
        //                     }
        //                 }
        //                 if ($cookie_authentication_error && $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME) && $this->ActionModel->UpdateCookieAuthentication(array('cookie_authentication_is_killed' => 1, 'cookie_authentication_killed_function' => 'HomeController __construct', 'date_cookie_authentication_killed' => date('Y-m-d H:i:s'), 'id' => $cookie_authentication_from_database['id'])) == 'Updated') {
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

        // $this->web_data['authenticated_user'] = array('id' => '1BEV2kDDjyZ1Qb3APgxurvkDrW7biovabxUNnNJgd1B1X4sF12oVCKd9xKNIKcDEuzZzODIw7IcuEqYFgmudVWxnLPA5OJyZexfrTpQTqbntSMHx2um2j740phOJqmQERfUqkj0JobJyeiS5G0k7LySjiBEVtAbvhx4cvOQm8HgdrlPG6KS4vthDRUIo9GuJhooVBBlJgyrJxCrPjJCclTgNUvHLJlOCiF7ddGaz3JX8LjE2NTg1Njk4Mzc4NTU', 'user_role' => '1653ddfc58384362caf2');
    }
    function Index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->input_control->CheckUrl();
            parent::LogView('Home/Index');
            $home_items_from_database = $this->ItemModel->GetHomeItems();
            if (!empty($home_items_from_database)) {
                $this->web_data['home_items'] = $this->input_control->GetItemsWithMainImageAndFormatedPrice($home_items_from_database);
            }
            $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url,gender_home_image');
            parent::GetView('Home/Index', $this->web_data);
        }
        $this->input_control->Redirect();
    }
    function Items(string $input_gender)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            $checked_input_gender = $this->input_control->CheckGETInput($input_gender);
            if (!is_null($checked_input_gender)) {
                $this->input_control->CheckUrl(array('renk', 'beden', 'kategori', 'min-fiyat', 'max-fiyat'));
                $genders_from_database = parent::GetGenders('id,gender_name,gender_url,gender_description,gender_keywords');
                foreach ($genders_from_database as $key => $gender_from_database) {
                    if ($gender_from_database['gender_url'] == $checked_input_gender) {
                        $selected_gender = $gender_from_database;
                    }
                    unset($genders_from_database[$key]['id']);
                    unset($genders_from_database[$key]['gender_description']);
                    unset($genders_from_database[$key]['gender_keywords']);
                }
                if (!empty($selected_gender)) {
                    parent::LogView('Home/Items/' . $selected_gender['gender_url']);
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
                    if (!empty($categories_from_database)) {
                        if (!empty($_GET['kategori'])) {
                            $category_from_get_form = $this->input_control->CheckGETInput($_GET['kategori']);
                        }
                        if (!empty($category_from_get_form)) {
                            $category_get_matched_success = false;
                            foreach ($categories_from_database as $key => $category_from_db) {
                                if ($category_from_get_form == $category_from_db['category_url']) {
                                    $category_get_matched_success = true;
                                    $get_hidden_inputs['kategori'] = $category_from_db['category_url'];
                                    $url_for_selected_filters_category = 'kategori=' . $category_from_db['category_url'] . '&';
                                    $this->web_data['selected_filters']['Kategori'] = array('name' => $category_from_db['category_name'], 'url_name' => $category_from_db['category_url']);
                                    $categories_from_database[$key]['selected'] = true;
                                    $item_conditions .= ' AND category=?';
                                    $item_bind_params[] = $category_from_db['id'];
                                    break;
                                }
                            }
                            if ($category_get_matched_success) {
                                $this->web_data['filter_categories'] = array('categories' => $categories_from_database, 'selected' => true);
                            } else {
                                $this->input_control->CheckUrl(array('renk', 'beden', 'min-fiyat', 'max-fiyat'));
                            }
                        } else {
                            $this->web_data['filter_categories'] = array('categories' => $categories_from_database);
                        }
                    }
                    $colors_from_database = $this->FilterModel->GetColors();
                    if (!empty($colors_from_database)) {
                        if (!empty($_GET['renk'])) {
                            $color_from_get_form = $this->input_control->CheckGETInput($_GET['renk']);
                        }
                        $color_get_matched_success = false;
                        foreach ($colors_from_database as $key => $color_from_database) {
                            $item_columns .= ',' . $color_from_database['color_url'];
                            if (!empty($color_from_get_form) && $color_from_get_form == $color_from_database['color_url']) {
                                $color_get_matched_success = true;
                                $get_hidden_inputs['renk'] = $color_from_database['color_url'];
                                $url_for_selected_filters_color = 'renk=' . $color_from_database['color_url'] . '&';
                                $this->web_data['selected_filters']['Renk'] = array('name' => $color_from_database['color_name'], 'url_name' => $color_from_database['color_url']);
                                $colors_from_database[$key]['selected'] = true;
                                $item_conditions .= ' AND ' . $color_from_database['color_url'] . '=1';
                            }
                        }
                        if (!empty($color_from_get_form)) {
                            if ($color_get_matched_success) {
                                $this->web_data['filter_colors'] = array('colors' => $colors_from_database, 'selected' => true);
                            } else {
                                $this->input_control->CheckUrl(array('beden', 'kategori', 'min-fiyat', 'max-fiyat'));
                            }
                        } else {
                            $this->web_data['filter_colors'] = array('colors' => $colors_from_database);
                        }
                    }
                    $sizes_from_database = $this->FilterModel->GetSizes();
                    if (!empty($sizes_from_database)) {
                        if (!empty($_GET['beden'])) {
                            $size_from_get_form = $this->input_control->CheckGETInput($_GET['beden']);
                        }
                        if (!empty($size_from_get_form)) {
                            $size_get_matched_success = false;
                            foreach ($sizes_from_database as $key => $size_from_database) {
                                if ($size_from_get_form == $size_from_database['size_url']) {
                                    $size_get_matched_success = true;
                                    $get_hidden_inputs['beden'] = $size_from_database['size_url'];
                                    $url_for_selected_filters_size = 'beden=' . $size_from_database['size_url'] . '&';
                                    $this->web_data['selected_filters']['Beden'] = array('name' => $size_from_database['size_name'], 'url_name' => $size_from_database['size_url']);
                                    $sizes_from_database[$key]['selected'] = true;
                                    $item_conditions .= ' AND ' . $size_from_database['size_url'] . '>=1';
                                    break;
                                }
                            }
                            if ($size_get_matched_success) {
                                $this->web_data['filter_sizes'] = array('sizes' => $sizes_from_database, 'selected' => true);
                            } else {
                                $this->input_control->CheckUrl(array('renk', 'kategori', 'min-fiyat', 'max-fiyat'));
                            }
                        } else {
                            $this->web_data['filter_sizes'] = array('sizes' => $sizes_from_database);
                        }
                    }
                    $prices_from_database = $this->FilterModel->GetMaxPrice();
                    if (!empty($prices_from_database['MAX(item_discount_price)'])) {
                        $int_max_price = ceil($prices_from_database['MAX(item_discount_price)']);
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
                    if (!empty($_POST['loadedItemOffSet'])) {
                        $loadedItemOffSet =  $this->input_control->CheckPositivePOSTInput($_POST['loadedItemOffSet']);
                        if (!is_null($loadedItemOffSet)) {
                            $item_conditions .= ' OFFSET ?';
                            $item_bind_params[] = $loadedItemOffSet;
                            $items_from_db = $this->ItemModel->GetItems($item_columns, $item_conditions, $item_bind_params);
                            if (!empty($items_from_db)) {
                                echo json_encode($this->input_control->GetItemsWithMainImageAndFormatedPrice($items_from_db));
                                exit(0);
                            }
                        }
                        echo json_encode(array('stop' => 'stop'));
                        exit(0);
                    } else {
                        $items_from_db = $this->ItemModel->GetItems($item_columns, $item_conditions, $item_bind_params);
                        if (!empty($items_from_db)) {
                            $this->web_data['items'] = json_encode($this->input_control->GetItemsWithMainImageAndFormatedPrice($items_from_db));
                        } else {
                            $this->web_data['items_not_found'] = true;
                        }
                        parent::GetView('Home/Items', $this->web_data);
                    }
                }
            }
        }
        $this->input_control->Redirect();
    }
    function ItemDetails(string $item_url)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            $checked_item_url = $this->input_control->CheckGETInput($item_url);
            if (!is_null($checked_item_url)) {
                $this->input_control->CheckUrl();
                $item_details_from_database = $this->ItemModel->GetItemDetails($checked_item_url);
                $sizes_from_database = $this->FilterModel->GetSizes();
                if (!empty($item_details_from_database) && !empty($sizes_from_database)) {
                    $genders_from_database = parent::GetGenders('id,gender_name,gender_url');
                    foreach ($genders_from_database as $key => $gender_from_database) {
                        if ($gender_from_database['id'] == $item_details_from_database['gender']) {
                            $selected_gender = $gender_from_database;
                        }
                        unset($genders_from_database[$key]['id']);
                    }
                    if (!empty($selected_gender)) {
                        parent::LogView('Home/ItemDetails/' . $item_details_from_database['item_url']);
                        $this->web_data['genders'] = $genders_from_database;
                        $this->web_data['item'] = $this->input_control->GetItemImagesAndFormatedPrice($item_details_from_database);
                        $this->web_data['sizes'] = $sizes_from_database;
                        $this->web_data['selected_gender_name'] = $selected_gender['gender_name'];
                        $this->web_data['selected_gender_url'] = $selected_gender['gender_url'];
                        $relevant_items_from_db = $this->ItemModel->GetRelevantItems(array($item_details_from_database['category'], $item_details_from_database['id']));
                        if (!empty($relevant_items_from_db)) {
                            $this->web_data['relevant_items'] = $this->input_control->GetItemsWithMainImageAndFormatedPrice($relevant_items_from_db);
                        }
                        parent::GetModel('CommentModel');
                        if (!empty($this->web_data['authenticated_user'])) {
                            if (!empty($this->ItemModel->GetFavorite(array($item_details_from_database['id'], $this->web_data['authenticated_user']['id'])))) {
                                $this->web_data['user_favorite_item'] = true;
                            }
                            if ($this->web_data['authenticated_user']['user_role'] == ADMIN_ROLE_ID) {
                                $comment_condition = 'WHERE item_id=? AND is_comment_deleted=0 ORDER BY date_comment_created DESC';
                                $comments_from_db_count = $this->CommentModel->GetCommentsCountForAdminByItemId($comment_condition, $item_details_from_database['id']);
                                if (!empty($comments_from_db_count) && count($comments_from_db_count) > 0) {
                                    $this->web_data['comment_count'] = count($comments_from_db_count);
                                }
                                if (!empty($_POST['loadedCommentOffset'])) {
                                    $loadedCommentOffset = $this->input_control->CheckPositivePOSTInput($_POST['loadedCommentOffset']);
                                    if (!is_null($loadedCommentOffset)) {
                                        $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE . ' OFFSET ' . $loadedCommentOffset;
                                        $comments_from_db = $this->CommentModel->GetCommentsForAdminByItemId($comment_condition, $item_details_from_database['id']);
                                        if (empty($comments_from_db)) {
                                            echo json_encode(array('stop' => 'stop'));
                                            exit(0);
                                        }
                                    } else {
                                        echo json_encode(array('stop' => 'stop'));
                                        exit(0);
                                    }
                                } else {
                                    $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE;
                                    $comments_from_db = $this->CommentModel->GetCommentsForAdminByItemId($comment_condition, $item_details_from_database['id']);
                                }
                            } else {
                                $comment_condition = 'WHERE item_id=? AND is_comment_deleted=0 AND ((user_id=? AND is_comment_approved=0) OR is_comment_approved=1) ORDER BY date_comment_created DESC';
                                $comments_from_db_count = $this->CommentModel->GetCommentsCountForUserByItemId($comment_condition, array($item_details_from_database['id'], $this->web_data['authenticated_user']['id']));
                                if (!empty($comments_from_db_count) && count($comments_from_db_count) > 0) {
                                    $this->web_data['comment_count'] = count($comments_from_db_count);
                                }
                                if (!empty($_POST['loadedCommentOffset'])) {
                                    $loadedCommentOffset = $this->input_control->CheckPositivePOSTInput($_POST['loadedCommentOffset']);
                                    if (!is_null($loadedCommentOffset)) {
                                        $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE . ' OFFSET ' . $loadedCommentOffset;
                                        $comments_from_db = $this->CommentModel->GetCommentsForUserByItemId($comment_condition, array($item_details_from_database['id'], $this->web_data['authenticated_user']['id']));
                                        if (empty($comments_from_db)) {
                                            echo json_encode(array('stop' => 'stop'));
                                            exit(0);
                                        }
                                    } else {
                                        echo json_encode(array('stop' => 'stop'));
                                        exit(0);
                                    }
                                } else {
                                    $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE;
                                    $comments_from_db = $this->CommentModel->GetCommentsForUserByItemId($comment_condition, array($item_details_from_database['id'], $this->web_data['authenticated_user']['id']));
                                }
                            }
                        } else {
                            $comment_condition = 'WHERE item_id=? AND is_comment_deleted=0 AND is_comment_approved=1 ORDER BY date_comment_created DESC';
                            $comments_from_db_count = $this->CommentModel->GetCommentsCountForAnonymousByItemId($comment_condition, $item_details_from_database['id']);
                            if (!empty($comments_from_db_count) && count($comments_from_db_count) > 0) {
                                $this->web_data['comment_count'] = count($comments_from_db_count);
                            }
                            if (!empty($_POST['loadedCommentOffset'])) {
                                $loadedCommentOffset = $this->input_control->CheckPositivePOSTInput($_POST['loadedCommentOffset']);
                                if (!is_null($loadedCommentOffset)) {
                                    $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE . ' OFFSET ' . $loadedCommentOffset;
                                    $comments_from_db = $this->CommentModel->GetCommentsForAnonymousByItemId($comment_condition, $item_details_from_database['id']);
                                    if (empty($comments_from_db)) {
                                        echo json_encode(array('stop' => 'stop'));
                                        exit(0);
                                    }
                                } else {
                                    echo json_encode(array('stop' => 'stop'));
                                    exit(0);
                                }
                            } else {
                                $comment_condition .= ' LIMIT ' . COMMENT_LOAD_LIMIT_IN_ONCE;
                                $comments_from_db = $this->CommentModel->GetCommentsForAnonymousByItemId($comment_condition, $item_details_from_database['id']);
                            }
                        }
                        if (!empty($comments_from_db)) {
                            foreach ($comments_from_db as $key => $comment_from_db) {
                                if (!empty($this->web_data['authenticated_user']) && $comment_from_db['user_id'] == $this->web_data['authenticated_user']['id']) {
                                    $this->web_data['user_has_comment'] = true;
                                }
                                $user_comment_from_db = $this->UserModel->GetUser('first_name,last_name,profile_image_path,profile_image', $comment_from_db['user_id']);
                                if (!empty($user_comment_from_db)) {
                                    $comments_from_db[$key]['date_comment_created'] = date('d/m/Y', strtotime($comments_from_db[$key]['date_comment_created']));
                                    $comments_from_db[$key]['user_id'] = $comment_from_db['user_id'];
                                    $comments_from_db[$key]['user_first_name'] = $user_comment_from_db['first_name'];
                                    $comments_from_db[$key]['user_last_name'] = $user_comment_from_db['last_name'];
                                    $comments_from_db[$key]['user_profile_image_path'] = $user_comment_from_db['profile_image_path'];
                                    $comments_from_db[$key]['user_profile_image'] = $user_comment_from_db['profile_image'];
                                    if (!empty($this->web_data['authenticated_user'])) {
                                        if ($this->web_data['authenticated_user']['user_role'] == ADMIN_ROLE_ID) {
                                            $comments_reply_from_db = $this->CommentModel->GetCommentsReplyForAdminByCommentId($comment_from_db['id']);
                                        } else {
                                            $comments_reply_from_db = $this->CommentModel->GetCommentsReplyForUserByCommentId(array($comment_from_db['id'], $this->web_data['authenticated_user']['id']));
                                        }
                                    } else {
                                        $comments_reply_from_db = $this->CommentModel->GetCommentsReplyForAnonymousByCommentId($comment_from_db['id']);
                                    }
                                    if (!empty($comments_reply_from_db)) {
                                        foreach ($comments_reply_from_db as $key2 => $comment_reply_from_db) {
                                            if (!empty($this->web_data['authenticated_user']) && $comment_reply_from_db['user_id'] == $this->web_data['authenticated_user']['id']) {
                                                $this->web_data['user_has_comment_reply'] = true;
                                            }
                                            $user_comment_reply_from_db = $this->UserModel->GetUser('first_name,last_name,profile_image_path,profile_image', $comment_reply_from_db['user_id']);
                                            if (!empty($user_comment_reply_from_db)) {
                                                $comments_reply_from_db[$key2]['date_comment_reply_created'] = date('d/m/Y', strtotime($comments_reply_from_db[$key2]['date_comment_reply_created']));
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
                            if (!empty($_POST['loadedCommentOffset'])) {
                                if (!empty($comments_from_db)) {
                                    echo json_encode($comments_from_db);
                                    exit(0);
                                } else {
                                    echo json_encode(array('stop' => 'stop'));
                                    exit(0);
                                }
                            } else {
                                if (!empty($comments_from_db)) {
                                    $this->web_data['comments'] = json_encode($comments_from_db);
                                } else {
                                    $this->web_data['comment_not_found'] = true;
                                }
                            }
                        } else {
                            $this->web_data['comment_not_found'] = true;
                        }
                        $result_set_csrf_token = parent::SetCSRFToken('ItemDetails');
                        if ($result_set_csrf_token == false) {
                            parent::GetView('Error/NotResponse');
                        } else {
                            $this->web_data['form_token'] = $result_set_csrf_token;
                            parent::GetView('Home/ItemDetails', $this->web_data);
                        }
                    }
                }
            }
        }
        $this->input_control->Redirect();
    }
    function Favorites()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->input_control->CheckUrl();
            parent::LogView('Home/Favorites');
            $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
            if (!empty($this->web_data['authenticated_user'])) {
                $item_conditions = 'WHERE user_id=? AND is_favorite_removed=0 ORDER BY date_favorite_created DESC LIMIT ?';
                $item_bind_params = array($this->web_data['authenticated_user']['id'], ITEM_LOAD_LIMIT_IN_ONCE);
                if (!empty($_POST['loadedItemOffSet'])) {
                    $loadedItemOffSet =  $this->input_control->CheckPositivePOSTInput($_POST['loadedItemOffSet']);
                    if (!is_null($loadedItemOffSet)) {
                        $item_conditions .= ' OFFSET ?';
                        $item_bind_params[] = $loadedItemOffSet;
                        $favorites_from_database = $this->ItemModel->GetFavoritesItemId($item_conditions, $item_bind_params);
                        if (!empty($favorites_from_database)) {
                            $favorite_items = array();
                            foreach ($favorites_from_database as $key => $favorite_from_database) {
                                $favorite_item_from_db = $this->ItemModel->GetFavoriteItem($favorite_from_database['item_id']);
                                if (!empty($favorite_item_from_db)) {
                                    $favorite_items[] = $favorite_item_from_db;
                                }
                            }
                            if (!empty($favorite_items)) {
                                echo json_encode($this->input_control->GetItemsWithMainImageAndFormatedPrice($favorite_items));
                                exit(0);
                            }
                        }
                    }
                    echo json_encode(array('stop' => 'stop'));
                    exit(0);
                } else {
                    $favorites_from_database = $this->ItemModel->GetFavoritesItemId($item_conditions, $item_bind_params);
                    if (!empty($favorites_from_database)) {
                        $favorite_items = array();
                        foreach ($favorites_from_database as $key => $favorite_from_database) {
                            $favorite_item_from_db = $this->ItemModel->GetFavoriteItem($favorite_from_database['item_id']);
                            if (!empty($favorite_item_from_db)) {
                                $favorite_items[] = $favorite_item_from_db;
                            }
                        }
                        if (!empty($favorite_items)) {
                            $this->web_data['favorite_items'] = json_encode($this->input_control->GetItemsWithMainImageAndFormatedPrice($favorite_items));
                        }
                    }
                    parent::GetView('Home/Favorites', $this->web_data);
                }
            }
        }
        $this->input_control->Redirect();
    }
    function Agreements(string $agreement_url)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $checked_agreement_url = $this->input_control->CheckGETInput($agreement_url);
            if (!is_null($checked_agreement_url)) {
                $this->input_control->CheckUrl();
                parent::LogView('Home/Agreements/' . $checked_agreement_url);
                $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                $case_matched = false;
                switch ($checked_agreement_url) {
                    case URL_TERMS:
                        $case_matched = true;
                        $this->web_data['agreement_type'] = URL_TERMS;
                        $this->web_data['agreement_title'] = URL_TERMS_TITLE;
                        break;
                    case URL_PRIVACY:
                        $case_matched = true;
                        $this->web_data['agreement_type'] = URL_PRIVACY;
                        $this->web_data['agreement_title'] = URL_PRIVACY_TITLE;
                        break;
                    case URL_RETURN_POLICY:
                        $case_matched = true;
                        $this->web_data['agreement_type'] = URL_RETURN_POLICY;
                        $this->web_data['agreement_title'] = URL_RETURN_POLICY_TITLE;
                        break;
                }
                if ($case_matched) {
                    parent::GetView('Home/Agreements', $this->web_data);
                }
            }
        }
        $this->input_control->Redirect();
    }
    function Cart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->input_control->CheckUrl();
            parent::LogView('Home/Cart');
            $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
            parent::GetView('Home/Cart', $this->web_data);
        }
        $this->input_control->Redirect();
    }
    function AddToCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'item_cart_id' => array('input' => isset($_POST['item']) ? $_POST['item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'size_cart_id' => array('input' => isset($_POST['item_size']) ? $_POST['item_size'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_CART_SIZE, 'preventxss' => true),
                'item_quantity' => array('input' => isset($_POST['item_quantity']) ? $_POST['item_quantity'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT)
            ));
            if (empty($checked_inputs['error_message'])) {
                $confirmed_size = $this->ItemModel->ConfirmSizeBySizeCartId($checked_inputs['size_cart_id']);
                if (!empty($confirmed_size)) {
                    $confirmed_item = $this->ItemModel->ConfirmItemByItemCartId($confirmed_size['size_url'], $checked_inputs['item_cart_id']);
                    if (!empty($confirmed_item)) {
                        $cart_item_not_setted = true;
                        $cart_items = array();
                        if (!empty($this->web_data['cookie_cart'])) {
                            $cart_items = $this->web_data['cookie_cart'];
                            foreach ($cart_items as $key => $value) {
                                if (!empty(count($cart_items[$key])) && count($cart_items[$key]) == 3 && !empty($cart_items[$key]['item_cart_id']) && !empty($cart_items[$key]['size_cart_id']) && !empty($cart_items[$key]['item_quantity'])) {
                                    if ($cart_items[$key]['item_cart_id'] == $confirmed_item['item_cart_id'] && $cart_items[$key]['size_cart_id'] == $confirmed_size['size_cart_id']) {
                                        $cart_item_new_quantity = $cart_items[$key]['item_quantity'] + $checked_inputs['item_quantity'];
                                        if ($confirmed_item[$confirmed_size['size_url']] < $cart_item_new_quantity) {
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
                            if ($confirmed_item[$confirmed_size['size_url']] < $checked_inputs['item_quantity']) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_STOCK_LIMIT);
                                exit(0);
                            } elseif ($checked_inputs['item_quantity'] <= 10 && $checked_inputs['item_quantity'] > 0) {
                                $cart_items[] = array(
                                    'item_cart_id' => $confirmed_item['item_cart_id'],
                                    'size_cart_id' => $confirmed_size['size_cart_id'],
                                    'item_quantity' =>$checked_inputs['item_quantity']
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
                            try {
                                $setted_cart_cookie = $this->input_control->EncrypteData(json_encode($cart_items), CART_PEPPER);
                                if (strlen($setted_cart_cookie) <= 4000) {
                                    if ($this->cookie_control->SetCookie(COOKIE_CART_NAME, $setted_cart_cookie, time() + (EXPIRY_COOKIE_CART), COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTP_ONLY, COOKIE_SAMESITE)) {
                                        $_SESSION[SESSION_CART_SUCCESS] = true;
                                        exit(0);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_COOKIE_LIMIT);
                                    exit(0);
                                }
                            } catch (\Throwable $th) {
                                $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function AddToCart | ' . $th));
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART);
                                exit(0);
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
        exit(0);
    } 
    function UpdateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['increase_cart_quantity']) || isset($_POST['decrease_cart_quantity']))) {
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'item_cart_id' => array('input' => isset($_POST['item']) ? $_POST['item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'size_cart_id' => array('input' => isset($_POST['item_size']) ? $_POST['item_size'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $confirmed_size = $this->ItemModel->ConfirmSizeBySizeCartId($checked_inputs['size_cart_id']);
                if (!empty($confirmed_size)) {
                    $confirmed_item = $this->ItemModel->ConfirmItemByItemCartId($confirmed_size['size_url'], $checked_inputs['item_cart_id']);
                    if (!empty($confirmed_item)) {
                        $cart_items = array();
                        if (!empty($this->web_data['cookie_cart'])) {
                            $cart_items = $this->web_data['cookie_cart'];
                            foreach ($cart_items as $key => $value) {
                                if (!empty(count($cart_items[$key])) && count($cart_items[$key]) == 3 && !empty($cart_items[$key]['item_cart_id']) && !empty($cart_items[$key]['size_cart_id']) && !empty($cart_items[$key]['item_quantity'])) {
                                    if ($cart_items[$key]['item_cart_id'] == $confirmed_item['item_cart_id'] && $cart_items[$key]['size_cart_id'] == $confirmed_size['size_cart_id']) {
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
                                        } elseif ($confirmed_item[$confirmed_size['size_url']] < $cart_item_new_quantity) {
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
                            try {
                                $setted_cart_cookie = $this->input_control->EncrypteData(json_encode($cart_items), CART_PEPPER);
                                if (strlen($setted_cart_cookie) <= 4000) {
                                    if ($this->cookie_control->SetCookie(COOKIE_CART_NAME, $setted_cart_cookie, time() + (EXPIRY_COOKIE_CART), COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTP_ONLY, COOKIE_SAMESITE)) {
                                        $this->input_control->Redirect(URL_CART);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_CART_COOKIE_LIMIT);
                                    $this->input_control->Redirect(URL_CART);
                                }
                            } catch (\Throwable $th) {
                                $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function AddToCart | ' . $th));
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_UPDATE_CART);
                                $this->input_control->Redirect(URL_CART);
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
        $this->input_control->Redirect(URL_CART);
    }
    function EmptyCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_empty_cart'])) {
            if (!empty($_COOKIE[COOKIE_CART_NAME]) && $this->cookie_control->EmptyCookie(COOKIE_CART_NAME)) {
                $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_EMPTY_CART);
            } else {
                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMPTY_CART);
            }
        }
        $this->input_control->Redirect(URL_CART);
    }
    function ItemSearch() {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'search_item' => array('input' => isset($_POST['search_item']) ? $_POST['search_item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $searched_items_from_database = $this->ItemModel->SearchItem($checked_inputs['search_item']);
                if (!empty($searched_items_from_database)) {
                    $response['searched_items'] = $searched_items_from_database;
                } else {
                    $response['not_found_search_item'] = true;
                }
            }
        }
        echo json_encode($response);
        exit(0);
    }
}
