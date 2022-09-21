<?php
class HomeController extends Controller
{
    function __construct()
    {
        parent::__construct();
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
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function Index | ' . $th));
            $this->input_control->Redirect(URL_SHUTDOWN);
        }
    }
    function Items(string $input_gender)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl(array('renk', 'beden', 'kategori', 'min-fiyat', 'max-fiyat'));
                $genders_from_database = parent::GetGenders('id,gender_keywords,gender_description,gender_name,gender_url');
                foreach ($genders_from_database as $key => $gender_from_database) {
                    if ($gender_from_database['gender_url'] == $input_gender) {
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
                $this->input_control->CheckUrl();
                $item_details_from_database = $this->ItemModel->GetItemDetails($item_url);
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
                                    $a = date('d/m/Y', strtotime($comments_from_db['data'][$key]['date_comment_created']));
                                    if ($user_comment_from_db['result'] && !empty($a)) {
                                        $comments_from_db['data'][$key]['date_comment_created'] = $a;
                                        $comments_from_db['data'][$key]['user_id'] = $comment_from_db['user_id'];
                                        $comments_from_db['data'][$key]['user_first_name'] = strtoupper(substr($user_comment_from_db['data']['first_name'], 0, 1)) . '***';
                                        $comments_from_db['data'][$key]['user_last_name'] = strtoupper(substr($user_comment_from_db['data']['last_name'], 0, 1)) . '***';
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
                                                $b = date('d/m/Y', strtotime($comments_reply_from_db['data'][$key2]['date_comment_reply_created']));
                                                if ($user_comment_reply_from_db['result'] && !empty($b)) {
                                                    $comments_reply_from_db['data'][$key2]['date_comment_reply_created'] = $b;
                                                    $comments_reply_from_db['data'][$key2]['user_id'] = $comment_reply_from_db['user_id'];
                                                    $comments_reply_from_db['data'][$key2]['user_first_name'] = strtoupper(substr($user_comment_reply_from_db['data']['first_name'], 0, 1)) . '***';
                                                    $comments_reply_from_db['data'][$key2]['user_last_name'] = strtoupper(substr($user_comment_reply_from_db['data']['last_name'], 0, 1)) . '***';
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
                if (!empty($_SESSION[SESSION_SELECTED_ADDRESS_ID])) {
                    $this->session_control->KillSession(SESSION_SELECTED_ADDRESS_ID);
                }
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
                $this->input_control->CheckUrl();
                $case_matched = false;
                switch ($agreement_url) {
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
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function Agreements | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function LogOut()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($this->web_data['authenticated_user'])) {
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
                if (!empty($this->web_data['cookie_authentication_id'])) {
                    $this->ActionModel->UpdateCookieAuthentication(array('is_cookie_authentication_logout' => 1, 'date_cookie_authentication_logout' => date('Y-m-d H:i:s'), 'id' => $this->web_data['cookie_authentication_id']));
                    $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_LOG_OUT);
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function LogOut | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Profile(string $profile_url)
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::LogView('Home-Profile-' . $profile_url);
                $case_matched = false;
                switch ($profile_url) {
                    case URL_PROFILE_INFORMATIONS:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_INFORMATIONS;
                        $this->web_data['profile_title'] = URL_PROFILE_INFO_TITLE;
                        $user_from_database = $this->UserModel->GetUserByUserId('first_name,last_name,two_fa_enable,user_delete_able', $this->web_data['authenticated_user']);
                        break;
                    case URL_PROFILE_IDENTITY_NUMBER:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_IDENTITY_NUMBER;
                        $this->web_data['profile_title'] = URL_PROFILE_IDENTITY_TITLE;
                        $user_from_database = $this->UserModel->GetUserByUserId('identity_number', $this->web_data['authenticated_user']);
                        break;
                    case URL_PROFILE_ADDRESS:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_ADDRESS;
                        $this->web_data['profile_title'] = URL_PROFILE_ADDRESS_TITLE;
                        $address_from_db = $this->UserModel->GetAddress($this->web_data['authenticated_user']);
                        if ($address_from_db['result']) {
                            $this->web_data['user_address'] = $address_from_db['data'];
                        }
                        if (!empty($_SESSION[SESSION_WEB_DATA_NAME])) {
                            if (isset($_SESSION[SESSION_WEB_DATA_NAME]['city']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['county']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['neighborhood']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['street']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['building_no']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['apartment_no']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['zip_no'])) {
                                $this->web_data['address'] = true;
                                $this->web_data['city'] = $_SESSION[SESSION_WEB_DATA_NAME]['city'];
                                $this->web_data['county'] = $_SESSION[SESSION_WEB_DATA_NAME]['county'];
                                $this->web_data['neighborhood'] = $_SESSION[SESSION_WEB_DATA_NAME]['neighborhood'];
                                $this->web_data['street'] = $_SESSION[SESSION_WEB_DATA_NAME]['street'];
                                $this->web_data['building_no'] = $_SESSION[SESSION_WEB_DATA_NAME]['building_no'];
                                $this->web_data['apartment_no'] = $_SESSION[SESSION_WEB_DATA_NAME]['apartment_no'];
                                $this->web_data['zip_no'] = $_SESSION[SESSION_WEB_DATA_NAME]['zip_no'];
                            }
                            $this->session_control->KillSession(SESSION_WEB_DATA_NAME);
                        }
                        break;
                    case URL_PROFILE_PASSWORD:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_PASSWORD;
                        $this->web_data['profile_title'] = URL_PROFILE_PWD_TITLE;
                        break;
                    case URL_PROFILE_EMAIL:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_EMAIL;
                        $this->web_data['profile_title'] = URL_PROFILE_EMAIL_TITLE;
                        $user_from_database = $this->UserModel->GetUserByUserId('email', $this->web_data['authenticated_user']);
                        break;
                    case URL_PROFILE_PHONE:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_PHONE;
                        $this->web_data['profile_title'] = URL_PROFILE_TEL_TITLE;
                        $user_from_database = $this->UserModel->GetUserByUserId('phone_number', $this->web_data['authenticated_user']);
                        break;
                    case URL_PROFILE_PHOTO:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_PHOTO;
                        $this->web_data['profile_title'] = URL_PROFILE_PHOTO_TITLE;
                        $user_from_database = $this->UserModel->GetUserByUserId('profile_image_path,profile_image', $this->web_data['authenticated_user']);
                        break;
                    case URL_PROFILE_ORDERS:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_ORDERS;
                        $this->web_data['profile_title'] = URL_PROFILE_ORDERS_TITLE;
                        $orders = $this->ItemModel->GetOrders($this->web_data['authenticated_user']);
                        if ($orders['result']) {
                            $user_orders = array();
                            foreach ($orders['data'] as $key => $order) {
                                $formatted_paid_price = $this->input_control->FormatPrice($order['paid_price']);
                                $a = date('d/m/Y H:i:s', strtotime($order['order_created']));
                                if ($formatted_paid_price['result'] && !empty($a)) {
                                    $order['paid_price'] = $formatted_paid_price['data'];
                                    $order['order_created'] = $a;
                                }
                                $order_basket = $this->ItemModel->GetOrderBasket($order['basket_id']);
                                if ($order_basket['result']) {
                                    foreach ($order_basket['data'] as $key => $basket_item) {
                                        $formatted_item_discount_price = $this->input_control->FormatPrice($order_basket['data'][$key]['item_discount_price']);
                                        if ($formatted_item_discount_price['result']) {
                                            $order_basket['data'][$key]['item_discount_price'] =  $formatted_item_discount_price['data'];
                                        }
                                    }
                                    $user_orders[] = array('order_basket' => $order_basket['data'], 'order_informations' => $order);
                                }
                            }
                            if (!empty($user_orders)) {
                                $this->web_data['orders'] = $user_orders;
                            }
                        }
                        break;
                }
                if ($case_matched) {
                    if (!empty($user_from_database) && $user_from_database['result']) {
                        $this->web_data['authenticated_user'] = $user_from_database['data'];
                    }
                    $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                    $this->web_data['form_token'] = parent::SetCSRFToken('Profile');
                    parent::GetView('Home/Profile', $this->web_data);
                }
            }
            parent::KillAuthentication('HomeController Profile');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function Profile | ' . $th))['result']) {
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
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id,first_name,last_name', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            if ($confirmed_user_from_db['data']['first_name'] == $checked_inputs['user_first_name'] && $confirmed_user_from_db['data']['last_name'] == $checked_inputs['user_last_name']) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NEW_USER_NAME);
                            } else {
                                if ($this->UserModel->UpdateUser(array('first_name' => $checked_inputs['user_first_name'], 'last_name' => $checked_inputs['user_last_name'], 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                                    if (empty($_SESSION[SESSION_COMPLETE_PROFILE_NAME])) {
                                        $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_PROFILE_USER_NAME_UPDATE);
                                    }
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
                if (!empty($_SESSION[SESSION_COMPLETE_PROFILE_NAME])) {
                    $this->session_control->KillSession(SESSION_COMPLETE_PROFILE_NAME);
                    $this->input_control->Redirect(URL_ORDER_CREDIT);
                } else {
                    $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_INFORMATIONS);
                }
            }
            parent::KillAuthentication('HomeController ProfileInformationsUpdate');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfileInformationsUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfileIdentityNumberUpdate()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'identity_number' => array('input' => isset($_POST['user_identity_number']) ? $_POST['user_identity_number'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_IDENTITY_NUMBER, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_IDENTITY_NUMBER, 'length_control' => true, 'min_length' => IDENTITY_NUMBER_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_NOT_VALID_IDENTITY_NUMBER, 'max_length' => IDENTITY_NUMBER_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_IDENTITY_NUMBER, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ERROR_NOT_VALID_IDENTITY_NUMBER),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id,identity_number', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            if ($confirmed_user_from_db['data']['identity_number'] != $checked_inputs['identity_number']) {
                                if (strlen($checked_inputs['identity_number']) == 11 && !in_array($checked_inputs['identity_number'], array(11111111110, 22222222220, 33333333330, 44444444440, 55555555550, 66666666660, 77777777770, 88888888880, 99999999990))) {
                                    $identity_validation = 0;
                                    for ($i = 0; $i < 10; $i++) {
                                        $identity_validation += substr($checked_inputs['identity_number'], $i, 1);
                                    }
                                    if ($identity_validation % 10 == substr($checked_inputs['identity_number'], 10, 1) && (((substr($checked_inputs['identity_number'], 0, 1) + substr($checked_inputs['identity_number'], 2, 1) + substr($checked_inputs['identity_number'], 4, 1) + substr($checked_inputs['identity_number'], 6, 1) + substr($checked_inputs['identity_number'], 8, 1)) * 7) - (substr($checked_inputs['identity_number'], 1, 1) + substr($checked_inputs['identity_number'], 3, 1) + substr($checked_inputs['identity_number'], 5, 1) + substr($checked_inputs['identity_number'], 7, 1))) % 10 == substr($checked_inputs['identity_number'], 9, 1)) {
                                        if ($this->UserModel->UpdateUser(array('identity_number' => $checked_inputs['identity_number'], 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                                            if (empty($_SESSION[SESSION_COMPLETE_PROFILE_IDENTITY])) {
                                                $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_PROFILE_IDENTITY_NUMBER_UPDATE);
                                            }
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NOT_VALID_IDENTITY_NUMBER);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NOT_VALID_IDENTITY_NUMBER);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NEW_IDENTITY_NUMBER);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                if (!empty($_SESSION[SESSION_COMPLETE_PROFILE_IDENTITY])) {
                    $this->session_control->KillSession(SESSION_COMPLETE_PROFILE_IDENTITY);
                    $this->input_control->Redirect(URL_ORDER_CREDIT);
                } else {
                    $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_IDENTITY_NUMBER);
                }
            }
            parent::KillAuthentication('HomeController ProfileIdentityNumberUpdate');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfileIdentityNumberUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfileTwoFa()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id,two_fa_enable', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            if ($confirmed_user_from_db['data']['two_fa_enable'] == 1) {
                                $new_two_fa = 0;
                                $two_fa_sta = 'WARNING';
                                $two_fa_not = TR_NOTIFICATION_SUCCESS_PROFILE_2FA_DEACTIVE;
                            } else {
                                $new_two_fa = 1;
                                $two_fa_sta = 'SUCCESS';
                                $two_fa_not = TR_NOTIFICATION_SUCCESS_PROFILE_2FA_ACTIVE;
                            }
                            if (isset($new_two_fa) && !empty($two_fa_sta) && !empty($two_fa_not)) {
                                if ($this->UserModel->UpdateUser(array('two_fa_enable' => $new_two_fa, 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                                    $this->notification_control->SetNotification($two_fa_sta, $two_fa_not);
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
                $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_INFORMATIONS);
            }
            parent::KillAuthentication('HomeController ProfileTwoFa');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfileTwoFa | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfileCreateAddress()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_address_create'])) {
                $city = isset($_POST['city']) ? ucfirst($_POST['city']) : '';
                $county = isset($_POST['county']) ? ucfirst($_POST['county']) : '';
                $neighborhood = isset($_POST['neighborhood']) ? ucwords($_POST['neighborhood']) : '';
                $street = isset($_POST['street']) ? ucwords($_POST['street']) : '';
                $building_no = isset($_POST['building_no']) ? $_POST['building_no'] : '';
                $apartment_no = isset($_POST['apartment_no']) ? $_POST['apartment_no'] : '';
                $zip_no = isset($_POST['zip_no']) ? $_POST['zip_no'] : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'address_city' => array('input' => $city, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_CITY, 'length_control' => true, 'max_length' => ADDRESS_1_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CITY, 'preventxss' => true, 'length_limit' => ADDRESS_1_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_CITY),
                    'address_county' => array('input' => $county, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_COUNTY, 'length_control' => true, 'max_length' => ADDRESS_1_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_COUNTY, 'preventxss' => true, 'length_limit' => ADDRESS_1_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_COUNTY),
                    'address_neighborhood' => array('input' => $neighborhood, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_NEIGHBORHOOD, 'length_control' => true, 'max_length' => ADDRESS_2_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_NEIGHBORHOOD, 'preventxss' => true, 'length_limit' => ADDRESS_2_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_NEIGHBORHOOD),
                    'address_street' => array('input' => $street, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_STREET, 'length_control' => true, 'max_length' => ADDRESS_2_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_STREET, 'preventxss' => true, 'length_limit' => ADDRESS_2_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_STREET),
                    'address_building_no' => array('input' => $building_no, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_BUILDING_NO, 'length_control' => true, 'max_length' => ADDRESS_3_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_BUILDING_NO, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ERROR_NOT_VALID_BUILDING_NO, 'length_limit' => ADDRESS_3_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_BUILDING_NO),
                    'address_apartment_no' => array('input' => $apartment_no, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_APARTMENT_NO, 'length_control' => true, 'max_length' => ADDRESS_3_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_APARTMENT_NO, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ERROR_NOT_VALID_APARTMENT_NO, 'length_limit' => ADDRESS_3_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_APARTMENT_NO),
                    'address_zip_no' => array('input' => $zip_no, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_ZIP_NO, 'length_control' => true, 'min_length' => ADDRESS_ZIP_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_NOT_VALID_ZIP_NO, 'max_length' => ADDRESS_ZIP_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_ZIP_NO, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ERROR_NOT_VALID_ZIP_NO, 'length_limit' => ADDRESS_ZIP_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_ZIP_NO),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            $address_count = $this->UserModel->GetAddressCount($confirmed_user_from_db['data']['id']);
                            if ($address_count['result'] && $address_count['data']['COUNT(id)'] < 5) {
                                $result_create_address = $this->UserModel->CreateAddress(array('user_id' => $confirmed_user_from_db['data']['id'], 'address_city' => $checked_inputs['address_city'], 'address_county' => $checked_inputs['address_county'], 'address_neighborhood' => $checked_inputs['address_neighborhood'], 'address_street' => $checked_inputs['address_street'], 'address_building_no' => $checked_inputs['address_building_no'], 'address_apartment_no' => $checked_inputs['address_apartment_no'], 'address_zip_no' => $checked_inputs['address_zip_no']));
                                if ($result_create_address['result']) {
                                    if (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) {
                                        $_SESSION[SESSION_SELECTED_ADDRESS_ID] = $result_create_address['id'];
                                    } else {
                                        $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_CREATE_ADDRESS);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CREATE_ADDRESS);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CREATE_ADDRESS_LIMIT);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                    $_SESSION[SESSION_WEB_DATA_NAME] = array('city' => $city, 'county' => $county, 'neighborhood' => $neighborhood, 'street' => $street, 'building_no' => $building_no, 'apartment_no' => $apartment_no, 'zip_no' => $zip_no);
                }
                if (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) {
                    $this->session_control->KillSession(SESSION_COMPLETE_ADDRESS);
                    $this->input_control->Redirect(URL_ORDER_CREDIT);
                } else {
                    $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_ADDRESS);
                }
            }
            parent::KillAuthentication('HomeController ProfileCreateAddress');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfileCreateAddress | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfileUpdateAddress()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_address_update'])) {
                $id = isset($_POST['id']) ? $_POST['id'] : '';
                $city = isset($_POST['city']) ? ucfirst($_POST['city']) : '';
                $county = isset($_POST['county']) ? ucfirst($_POST['county']) : '';
                $neighborhood = isset($_POST['neighborhood']) ? ucwords($_POST['neighborhood']) : '';
                $street = isset($_POST['street']) ? ucwords($_POST['street']) : '';
                $building_no = isset($_POST['building_no']) ? $_POST['building_no'] : '';
                $apartment_no = isset($_POST['apartment_no']) ? $_POST['apartment_no'] : '';
                $zip_no = isset($_POST['zip_no']) ? $_POST['zip_no'] : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'id' => array('input' => $id, 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'address_city' => array('input' => $city, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_CITY, 'length_control' => true, 'max_length' => ADDRESS_1_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CITY, 'preventxss' => true, 'length_limit' => ADDRESS_1_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_CITY),
                    'address_county' => array('input' => $county, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_COUNTY, 'length_control' => true, 'max_length' => ADDRESS_1_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_COUNTY, 'preventxss' => true, 'length_limit' => ADDRESS_1_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_COUNTY),
                    'address_neighborhood' => array('input' => $neighborhood, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_NEIGHBORHOOD, 'length_control' => true, 'max_length' => ADDRESS_2_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_NEIGHBORHOOD, 'preventxss' => true, 'length_limit' => ADDRESS_2_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_NEIGHBORHOOD),
                    'address_street' => array('input' => $street, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_STREET, 'length_control' => true, 'max_length' => ADDRESS_2_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_STREET, 'preventxss' => true, 'length_limit' => ADDRESS_2_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_STREET),
                    'address_building_no' => array('input' => $building_no, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_BUILDING_NO, 'length_control' => true, 'max_length' => ADDRESS_3_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_BUILDING_NO, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ERROR_NOT_VALID_BUILDING_NO, 'length_limit' => ADDRESS_3_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_BUILDING_NO),
                    'address_apartment_no' => array('input' => $apartment_no, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_APARTMENT_NO, 'length_control' => true, 'max_length' => ADDRESS_3_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_APARTMENT_NO, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ERROR_NOT_VALID_APARTMENT_NO, 'length_limit' => ADDRESS_3_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_APARTMENT_NO),
                    'address_zip_no' => array('input' => $zip_no, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_ZIP_NO, 'length_control' => true, 'min_length' => ADDRESS_ZIP_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_NOT_VALID_ZIP_NO, 'max_length' => ADDRESS_ZIP_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_ZIP_NO, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ERROR_NOT_VALID_ZIP_NO, 'length_limit' => ADDRESS_ZIP_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_ZIP_NO),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            $address_confirm = $this->UserModel->GetAddressById('id', array($checked_inputs['id'], $confirmed_user_from_db['data']['id']));
                            if ($address_confirm['result'] && $this->UserModel->UpdateAddress(array('address_city' => $checked_inputs['address_city'], 'address_county' => $checked_inputs['address_county'], 'address_neighborhood' => $checked_inputs['address_neighborhood'], 'address_street' => $checked_inputs['address_street'], 'address_building_no' => $checked_inputs['address_building_no'], 'address_apartment_no' => $checked_inputs['address_apartment_no'], 'address_zip_no' => $checked_inputs['address_zip_no'], 'address_last_updated' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['id']))['result']) {
                                if (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) {
                                    $_SESSION[SESSION_SELECTED_ADDRESS_ID] = $checked_inputs['id'];
                                } else {
                                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_UPDATE_ADDRESS);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_UPDATE_ADDRESS);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                if (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) {
                    $this->session_control->KillSession(SESSION_COMPLETE_ADDRESS);
                    $this->input_control->Redirect(URL_ORDER_CREDIT);
                } else {
                    $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_ADDRESS);
                }
            }
            parent::KillAuthentication('HomeController ProfileUpdateAddress');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfileUpdateAddress | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfileDeleteAddress()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-delete-address'])) {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'id' => array('input' => isset($_POST['address_id']) ? $_POST['address_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            $address_confirm = $this->UserModel->GetAddressById('id', array($checked_inputs['id'], $confirmed_user_from_db['data']['id']));
                            if ($address_confirm['result'] && $this->UserModel->UpdateAddress(array('is_address_removed' => 1, 'date_address_removed' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['id']))['result']) {
                                if (empty($_SESSION[SESSION_COMPLETE_ADDRESS])) {
                                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_DELETE_ADDRESS);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DELETE_ADDRESS);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                if (!empty($_SESSION[SESSION_COMPLETE_ADDRESS])) {
                    $this->session_control->KillSession(SESSION_COMPLETE_ADDRESS);
                    $this->input_control->Redirect(URL_ORDER_CREDIT);
                } else {
                    $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_ADDRESS);
                }
            }
            parent::KillAuthentication('HomeController ProfileDeleteAddress');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfileDeleteAddress | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfilePasswordUpdate()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'user_old_password' => array('input' => isset($_POST['user_old_password']) ? $_POST['user_old_password'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD),
                    'user_new_password' => array('input' => isset($_POST['user_new_password']) ? $_POST['user_new_password'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'user_new_re_password' => array('input' => isset($_POST['user_new_re_password']) ? $_POST['user_new_re_password'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id,password,password_salt', $this->web_data['authenticated_user']);
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
                                            if ($this->UserModel->UpdateUser(array('password' => $this->input_control->EncrypteData($hashed_password, PASSWORD_PEPPER), 'password_salt' => $password_salt, 'id' => $confirmed_user_from_db['data']['id']))['result']) {
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
                $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_PASSWORD);
            }
            parent::KillAuthentication('HomeController ProfilePasswordUpdate');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfilePasswordUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfileEmailUpdate()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'user_email' => array('input' => isset($_POST['user_email']) ? $_POST['user_email'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'is_email' => true, 'error_message_is_email' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'length_control' => true, 'max_length' => EMAIL_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'preventxss' => true, 'length_limit' => EMAIL_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id,email', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            if ($confirmed_user_from_db['data']['email'] != $checked_inputs['user_email']) {
                                $is_email_unique = $this->UserModel->IsUserEmailUnique($checked_inputs['user_email']);
                                if ($is_email_unique['result']) {
                                    if ($is_email_unique['data']['COUNT(id)'] > 0) {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_PROFILE_NOT_UNIQUE_EMAIL);
                                    } else {
                                        $session_update_email = $this->input_control->GenerateToken();
                                        $session_update_email_bytes = $this->action_control->GenerateConfirmTokenBytes();
                                        if (!empty($session_update_email_bytes)) {
                                            $hashed_token = $this->action_control->HashedConfirmTokenBytes($session_update_email_bytes[4] . $session_update_email_bytes[7] . $session_update_email_bytes[0] . $session_update_email_bytes[1] . $session_update_email_bytes[6] . $session_update_email_bytes[3] . $session_update_email_bytes[2] . $session_update_email_bytes[5]);
                                            if (!empty($session_update_email['result']) && !empty($hashed_token) && $this->UserModel->CreateSessionUpdateEmail(array('user_id' => $this->web_data['authenticated_user'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'update_email_token' => $session_update_email['data'], 'update_email_hashed_tokens' => $hashed_token, 'date_update_email_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CONFIRM_EMAIL_TOKEN)), 'is_update_email_used' => 0))['result'] && $this->action_control->SendMail($this->input_control->DecodePreventXSS($checked_inputs['user_email']), BRAND . ' Yeni Email Doğrulama', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Yeni Email Doğrulama | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.confirm-container {width: 100%;margin-left: auto;margin-right: auto;padding-top: 20px;padding-bottom: 20px;}.confirm {display: inline-block;font-size: 20px;text-align: center;background-color: #ffffff;color: #000000;width: 10%;padding-top: 10px;padding-bottom: 10px;margin-right: 1%;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm-container {width: 70%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Yeni Email Doğrulama</p></div><div class="main"><div class="confirm-container"><span class="confirm">' . $session_update_email_bytes[2] . '</span><span class="confirm">' . $session_update_email_bytes[4] . '</span><span class="confirm">' . $session_update_email_bytes[0] . '</span><span class="confirm">' . $session_update_email_bytes[7] . '</span><span class="confirm">' . $session_update_email_bytes[1] . '</span><span class="confirm">' . $session_update_email_bytes[3] . '</span><span class="confirm">' . $session_update_email_bytes[6] . '</span><span class="confirm">' . $session_update_email_bytes[5] . '</span></div><p class="text-2">Yeni email adresinizi doğrulamak için üstteki kodu girin</p><p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, hemen ' . BRAND . ' hesabınızın şifresini değiştirin</p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $this->web_data['authenticated_user'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'EmailUpdate'))['result']) {
                                                $_SESSION[SESSION_EMAIL_UPDATE_NAME] = array('token' => $session_update_email['data'], 'email' => $checked_inputs['user_email']);
                                                $this->input_control->Redirect(URL_EMAIL_UPDATE_CONFIRM);
                                            }
                                        }
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMAIL_UPDATE);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_PROFILE_NEW_EMAIL);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_EMAIL);
            }
            parent::KillAuthentication('HomeController ProfileEmailUpdate');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfileEmailUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function EmailUpdateConfirm()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if (!empty($_SESSION[SESSION_EMAIL_UPDATE_NAME])) {
                $email_update_token_from_database = $this->UserModel->GetSessionUpdateEmail(array($_SERVER['REMOTE_ADDR'], $_SESSION[SESSION_EMAIL_UPDATE_NAME]['token']));
                if ($email_update_token_from_database['result'] && $email_update_token_from_database['data']['date_update_email_expiry'] > date('Y-m-d H:i:s') && $email_update_token_from_database['data']['is_update_email_used'] == 0) {
                    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        $this->input_control->CheckUrl();
                        parent::LogView('Home-EmailUpdateConfirm');
                        $bait_token = $this->action_control->GenerateBaitToken();
                        if (!empty($bait_token)) {
                            $this->web_data['confirm_token'] = $bait_token;
                        }
                        $a = strtotime($email_update_token_from_database['data']['date_update_email_expiry']);
                        $b = strtotime(date('Y-m-d H:i:s'));
                        if (!empty($a) && !empty($b)) {
                            $this->web_data['expiry_remain_minute'] = (int)(($a - $b) / 60);
                            $this->web_data['expiry_remain_second'] = ($a - $b) % 60;
                            $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                            $this->web_data['form_token'] = parent::SetCSRFToken('EmailUpdateConfirm');
                            parent::GetView('Home/EmailUpdate', $this->web_data);
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $checked_email = $_SESSION[SESSION_EMAIL_UPDATE_NAME]['email'];
                        $this->session_control->KillSession(SESSION_EMAIL_UPDATE_NAME);
                        $checked_inputs = $this->input_control->CheckPostedInputs(array(
                            'token_1' => array('input' => isset($_POST['token_1']) ? $_POST['token_1'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN),
                            'token_2' => array('input' => isset($_POST['token_2']) ? $_POST['token_2'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN),
                            'token_3' => array('input' => isset($_POST['token_3']) ? $_POST['token_3'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN),
                            'token_4' => array('input' => isset($_POST['token_4']) ? $_POST['token_4'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN),
                            'token_5' => array('input' => isset($_POST['token_5']) ? $_POST['token_5'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN),
                            'token_6' => array('input' => isset($_POST['token_6']) ? $_POST['token_6'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN),
                            'token_7' => array('input' => isset($_POST['token_7']) ? $_POST['token_7'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN),
                            'token_8' => array('input' => isset($_POST['token_8']) ? $_POST['token_8'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN),
                            'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                        ));
                        if (empty($checked_inputs['error_message'])) {
                            if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'EmailUpdateConfirm')) {
                                $hashed_token = $this->action_control->HashedConfirmTokenBytes($checked_inputs['token_2'] . $checked_inputs['token_4'] . $checked_inputs['token_3'] . $checked_inputs['token_5'] . $checked_inputs['token_7'] . $checked_inputs['token_6'] . $checked_inputs['token_1'] . $checked_inputs['token_8']);
                                if ($this->UserModel->UpdateSessionUpdateEmail(array('is_update_email_used' => 1, 'date_update_email_used' => date('Y-m-d H:i:s'), 'id' => $email_update_token_from_database['data']['id']))['result'] && !empty($hashed_token)) {
                                    if ($email_update_token_from_database['data']['update_email_hashed_tokens'] == $hashed_token) {
                                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id', $this->web_data['authenticated_user']);
                                        if ($confirmed_user_from_db['result']) {
                                            if ($this->UserModel->UpdateUser(array('email' => $checked_email, 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                                                $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_EMAIL_UPDATE);
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                            }
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                }
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                        }
                    }
                } else {
                    $this->session_control->KillSession(SESSION_EMAIL_UPDATE_NAME);
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMAIL_UPDATE_TOKEN_EXPIRIED);
                }
                $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_EMAIL);
            }
            parent::KillAuthentication('HomeController EmailUpdateConfirm');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function EmailUpdateConfirm | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfilePhoneUpdate()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'user_phone_number' => array('input' => isset($_POST['user_phone_number']) ? $_POST['user_phone_number'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PHONE, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_PHONE, 'length_control' => true, 'min_length' => PHONE_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_NOT_VALID_PHONE, 'max_length' => PHONE_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_PHONE, 'preventxss' => true, 'is_integer_and_positive' => true, 'error_message_is_integer_and_positive' => TR_NOTIFICATION_ERROR_NOT_VALID_PHONE, 'is_phone_number' => true, 'error_message_is_phone_number' => TR_NOTIFICATION_ERROR_NOT_VALID_PHONE),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id,phone_number', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            if ($confirmed_user_from_db['data']['phone_number'] == $checked_inputs['user_phone_number']) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NEW_PHONE_NUMBER);
                            } else {
                                if ($this->UserModel->UpdateUser(array('phone_number' => $checked_inputs['user_phone_number'], 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_PROFILE_PHONE_NUMBER_UPDATE);
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
                $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_PHONE);
            }
            parent::KillAuthentication('HomeController ProfilePhoneUpdate');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfilePhoneUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfilePhotoUpdate()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_photo'])) {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id,profile_image_path,profile_image', $this->web_data['authenticated_user']);
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
                                                                    if ($confirmed_user_from_db['data']['profile_image'] != 'b6lfjkh5q9qfmfq.jpg' &&  $confirmed_user_from_db['data']['profile_image_path'] != '1qiunjdt0p8cao66xoz') {
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
                                                                    echo 'a';
                                                                    exit(0);
                                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE);
                                                                    break;
                                                                }
                                                            } elseif ($image_infos[2] == 3) {
                                                                $src_image = imagecreatefrompng($_FILES['user_image']['tmp_name']);
                                                                $error = true;
                                                                if (!empty($src_image) && imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_width, $dst_height, $image_infos[0], $image_infos[1])) {
                                                                    $success = true;
                                                                    if ($confirmed_user_from_db['data']['profile_image'] != 'b6lfjkh5q9qfmfq.jpg' &&  $confirmed_user_from_db['data']['profile_image_path'] != '1qiunjdt0p8cao66xoz') {
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
                                                            if ($this->UserModel->UpdateUser(array('profile_image_path' => $folder_name['data'], 'profile_image' => $image_file_name['data'] . '.' . $image_type, 'id' => $confirmed_user_from_db['data']['id']))['result']) {
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
                $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_PHOTO);
            }
            parent::KillAuthentication('HomeController ProfilePhotoUpdate');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfilePhotoUpdate | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ProfileDelete()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_user_delete'])) {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id,user_delete_able', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result'] && $confirmed_user_from_db['data']['user_delete_able'] == 1 && $this->UserModel->UpdateUser(array('is_user_deleted' => 1, 'date_user_deleted' => date('Y-m-d H:i:s'), 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                            $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_ACCOUNT_DELETE);
                            if (!empty($this->web_data['session_authentication_id'])) {
                                $this->ActionModel->UpdateSessionAuthentication(array('is_session_authentication_logout' => 1, 'date_session_authentication_logout' => date('Y-m-d H:i:s'), 'id' => $this->web_data['session_authentication_id']));
                                $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
                            }
                            if (!empty($this->web_data['cookie_authentication_id'])) {
                                $this->ActionModel->UpdateCookieAuthentication(array('is_cookie_authentication_logout' => 1, 'date_cookie_authentication_logout' => date('Y-m-d H:i:s'), 'id' => $this->web_data['cookie_authentication_id']));
                                $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME);
                            }
                            $this->input_control->Redirect();
                        }
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_INFORMATIONS);
            }
            parent::KillAuthentication('HomeController ProfileDelete');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function ProfileDelete | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function OrderAddressPost()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_address']) && !empty($_SESSION[SESSION_COMPLETE_ADDRESS])) {
                $this->session_control->KillSession(SESSION_COMPLETE_ADDRESS);
                $selected_checked_address = $this->input_control->IsString($_POST['selected_address']);
                if (!is_null($selected_checked_address)) {
                    $selected_address_id = $this->input_control->PreventXSSForId($selected_checked_address);
                    if (!empty($selected_address_id)) {
                        $_SESSION[SESSION_SELECTED_ADDRESS_ID] = $selected_address_id;
                        $this->input_control->Redirect(URL_ORDER_CREDIT);
                    }
                }
                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                $this->input_control->Redirect(URL_CART);
            }
            parent::KillAuthentication('HomeController OrderAddressPost');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function OrderAddressPost | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function OrderCredit()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect(URL_LOGIN);
            }
            $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id,first_name,last_name,identity_number,email,phone_number,is_user_blocked', $this->web_data['authenticated_user']);
            if ($confirmed_user_from_db['result'] && !empty($this->web_data['order_cart_data']) && is_array($this->web_data['order_cart_data']) && !empty($this->web_data['order_cart_data_price']) && !empty($this->web_data['order_cart_data_total_price'])) {
                if ($confirmed_user_from_db['data']['is_user_blocked'] == 0) {
                    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        $user_address = array();
                        if (empty($confirmed_user_from_db['data']['first_name']) || empty($confirmed_user_from_db['data']['last_name'])) {
                            $_SESSION[SESSION_COMPLETE_PROFILE_NAME] = true;
                            parent::LogView('Home-OrderCredit-Name');
                            $this->web_data['form_token'] = parent::SetCSRFToken('Profile');
                            $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                            parent::GetView('Home/OrderCredit', $this->web_data);
                        } elseif (empty($confirmed_user_from_db['data']['identity_number'])) {
                            $_SESSION[SESSION_COMPLETE_PROFILE_IDENTITY] = true;
                            parent::LogView('Home-OrderCredit-Identity');
                            $this->web_data['form_token'] = parent::SetCSRFToken('Profile');
                            $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                            parent::GetView('Home/OrderCredit', $this->web_data);
                        } elseif (empty($_SESSION[SESSION_SELECTED_ADDRESS_ID])) {
                            $_SESSION[SESSION_COMPLETE_ADDRESS] = true;
                            $not_from_create_address = true;
                            if (!empty($_SESSION[SESSION_WEB_DATA_NAME])) {
                                if (isset($_SESSION[SESSION_WEB_DATA_NAME]['city']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['county']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['neighborhood']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['street']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['building_no']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['apartment_no']) && isset($_SESSION[SESSION_WEB_DATA_NAME]['zip_no'])) {
                                    $this->web_data['city'] = $_SESSION[SESSION_WEB_DATA_NAME]['city'];
                                    $this->web_data['county'] = $_SESSION[SESSION_WEB_DATA_NAME]['county'];
                                    $this->web_data['neighborhood'] = $_SESSION[SESSION_WEB_DATA_NAME]['neighborhood'];
                                    $this->web_data['street'] = $_SESSION[SESSION_WEB_DATA_NAME]['street'];
                                    $this->web_data['building_no'] = $_SESSION[SESSION_WEB_DATA_NAME]['building_no'];
                                    $this->web_data['apartment_no'] = $_SESSION[SESSION_WEB_DATA_NAME]['apartment_no'];
                                    $this->web_data['zip_no'] = $_SESSION[SESSION_WEB_DATA_NAME]['zip_no'];
                                    $this->web_data['address'] = true;
                                    $not_from_create_address = false;
                                }
                                $this->session_control->KillSession(SESSION_WEB_DATA_NAME);
                            }
                            if ($not_from_create_address) {
                                $address_from_database = $this->UserModel->GetAddress($this->web_data['authenticated_user']);
                                if ($address_from_database['result']) {
                                    $this->web_data['select_address'] = $address_from_database['data'];
                                }
                            }
                            parent::LogView('Home-OrderCredit-Address');
                            $this->web_data['form_token'] = parent::SetCSRFToken('Profile');
                            $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                            parent::GetView('Home/OrderCredit', $this->web_data);
                        } else {
                            $this->web_data['ready_to_buy'] = true;
                            parent::LogView('Home-OrderCredit-Credit');
                            $this->web_data['form_token'] = parent::SetCSRFToken('OrderCredit');
                            $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                            parent::GetView('Home/OrderCredit', $this->web_data);
                        }
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        $this->input_control->Redirect(URL_CART);
                    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $checked_inputs = $this->input_control->CheckPostedInputs(array(
                            'cart_name' => array('input' => isset($_POST['cart_name']) ? $_POST['cart_name'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_CART_NAME, 'has_white_space' => true, 'error_message_has_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_NAME),
                            'cart_number' => array('input' => isset($_POST['cart_number']) ? $_POST['cart_number'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_CART_NUMBER, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_NUMBER, 'length_control' => true, 'min_length' => CART_NUMBER_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_NUMBER, 'max_length' => CART_NUMBER_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_NUMBER, 'is_numeric' => true, 'error_message_is_numeric' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_NUMBER),
                            'cart_expiry_month' => array('input' => isset($_POST['cart_expiry_month']) ? $_POST['cart_expiry_month'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_CART_EXPIRY_MONTH, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_MONTH, 'length_control' => true, 'min_length' => CART_EXPIRY_MONTH_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_MONTH, 'max_length' => CART_EXPIRY_MONTH_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_MONTH, 'is_numeric' => true, 'error_message_is_numeric' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_MONTH),
                            'cart_expiry_year' => array('input' => isset($_POST['cart_expiry_year']) ? $_POST['cart_expiry_year'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_CART_EXPIRY_YEAR, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_YEAR, 'length_control' => true, 'min_length' => CART_EXPIRY_YEAR_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_YEAR, 'max_length' => CART_EXPIRY_YEAR_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_YEAR, 'is_numeric' => true, 'error_message_is_numeric' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_YEAR),
                            'cart_cvc' => array('input' => isset($_POST['cart_cvc']) ? $_POST['cart_cvc'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_CART_CVC, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_CVC, 'length_control' => true, 'min_length' => CART_CVC_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_CVC, 'max_length' => CART_CVC_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_CVC, 'is_numeric' => true, 'error_message_is_numeric' => TR_NOTIFICATION_ERROR_NOT_VALID_CART_CVC),
                            'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                        ));
                        if (empty($checked_inputs['error_message'])) {
                            if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'OrderCredit')) {
                                if ((int)$checked_inputs['cart_expiry_month'] > 0 && (int)$checked_inputs['cart_expiry_month'] <= 12) {
                                    if ((int)$checked_inputs['cart_expiry_year'] >= 22) {
                                        if (!empty($_SESSION[SESSION_SELECTED_ADDRESS_ID])) {
                                            $selected_address = $this->UserModel->GetAddressById('address_country,address_city,address_county,address_neighborhood,address_street,address_building_no,address_apartment_no,address_zip_no', array($_SESSION[SESSION_SELECTED_ADDRESS_ID], $this->web_data['authenticated_user']));
                                            if ($selected_address['result']) {
                                                $user_address = $selected_address['data'];
                                            }
                                        }
                                        $conversation_id = $this->input_control->GenerateToken();
                                        $basket_id = $this->input_control->GenerateToken();
                                        if (!empty($user_address) && $conversation_id['result'] && $basket_id['result']) {
                                            $result_create_order = $this->ItemModel->CreateOrder(array(
                                                'conversation_id' => $conversation_id['data'],
                                                'price' => $this->web_data['order_cart_data_price'],
                                                'paid_price' => $this->web_data['order_cart_data_total_price'],
                                                'installment' => 1,
                                                'basket_id' => $basket_id['data'],
                                                'register_card' => 0,
                                                'user_id' => $confirmed_user_from_db['data']['id'],
                                                'user_first_name' => $confirmed_user_from_db['data']['first_name'],
                                                'user_last_name' => $confirmed_user_from_db['data']['last_name'],
                                                'user_email' => $confirmed_user_from_db['data']['email'],
                                                'user_identity_number' => $confirmed_user_from_db['data']['identity_number'],
                                                'user_address' => $user_address['address_county'] . $this->input_control->PreventXSS(', ') . $user_address['address_neighborhood'] . $this->input_control->PreventXSS(', ') . $user_address['address_street'] . $this->input_control->PreventXSS(', No: ') . $user_address['address_building_no'] . $this->input_control->PreventXSS('/') . $user_address['address_apartment_no'],
                                                'user_ip' => $_SERVER['REMOTE_ADDR'],
                                                'user_city' => $user_address['address_city'],
                                                'user_country' => $user_address['address_country'],
                                                'user_zip_code' => $user_address['address_zip_no'],
                                                'shipping_contact_name' => $confirmed_user_from_db['data']['first_name'] . ' ' . $confirmed_user_from_db['data']['last_name'],
                                                'shipping_city' => $user_address['address_city'],
                                                'shipping_country' => $user_address['address_country'],
                                                'shipping_address' => $user_address['address_county'] . $this->input_control->PreventXSS(', ') . $user_address['address_neighborhood'] . $this->input_control->PreventXSS(', ') . $user_address['address_street'] . $this->input_control->PreventXSS(', No: ') . $user_address['address_building_no'] . $this->input_control->PreventXSS('/') . $user_address['address_apartment_no'],
                                                'shipping_zip_code' => $user_address['address_zip_no'],
                                                'billing_contact_name' => $confirmed_user_from_db['data']['first_name'] . ' ' . $confirmed_user_from_db['data']['last_name'],
                                                'billing_city' => $user_address['address_city'],
                                                'billing_country' => $user_address['address_country'],
                                                'billing_address' => $user_address['address_county'] . $this->input_control->PreventXSS(', ') . $user_address['address_neighborhood'] . $this->input_control->PreventXSS(', ') . $user_address['address_street'] . $this->input_control->PreventXSS(', No: ') . $user_address['address_building_no'] . $this->input_control->PreventXSS('/') . $user_address['address_apartment_no'],
                                                'billing_zip_code' => $user_address['address_zip_no']
                                            ));
                                            if ($result_create_order['result']) {
                                                require_once(IYZIPAY_FOLDER_NAME . '/samples/config.php');
                                                $request = new \Iyzipay\Request\CreatePaymentRequest();

                                                $category_success = true;
                                                $basketItems = array();
                                                foreach ($this->web_data['order_cart_data'] as $cart_data) {
                                                    $category_name = $this->FilterModel->GetCategoryById($cart_data['item']['category']);
                                                    if ($category_name['result']) {
                                                        $result_create_order_basket = $this->ItemModel->CreateOrderBasket(array(
                                                            'basket_id' => $basket_id['data'],
                                                            'item_id' => $cart_data['item']['id'],
                                                            'item_name' => $cart_data['item']['item_name'],
                                                            'item_category' => $category_name['data']['category_name'],
                                                            'item_size_name' => $cart_data['size'],
                                                            'item_quantity' => $cart_data['quantity'],
                                                            'item_type' => 'PHYSICAL',
                                                            'item_price' => $cart_data['item']['item_price'],
                                                            'item_discount_price' => $cart_data['item']['item_discount_price'],
                                                        ));
                                                        if ($result_create_order_basket['result']) {
                                                            $basketItem = new \Iyzipay\Model\BasketItem();
                                                            $basketItem->setId($cart_data['item']['id']);
                                                            $basketItem->setName($cart_data['item']['item_name']);
                                                            $basketItem->setCategory1($category_name['data']['category_name']);
                                                            $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
                                                            $basketItem->setPrice($cart_data['item']['item_price']);
                                                            $basketItems[] = $basketItem;
                                                        } else {
                                                            $category_success = false;
                                                            break;
                                                        }
                                                    } else {
                                                        $category_success = false;
                                                        break;
                                                    }
                                                }
                                                $user_first_name = $this->input_control->DecodePreventXSS($confirmed_user_from_db['data']['first_name']);
                                                $user_last_name = $this->input_control->DecodePreventXSS($confirmed_user_from_db['data']['last_name']);
                                                $user_email = $this->input_control->DecodePreventXSS($confirmed_user_from_db['data']['email']);
                                                $user_county = $this->input_control->DecodePreventXSS($user_address['address_county']);
                                                $user_neighborhood = $this->input_control->DecodePreventXSS($user_address['address_neighborhood']);
                                                $user_street = $this->input_control->DecodePreventXSS($user_address['address_street']);
                                                $user_city = $this->input_control->DecodePreventXSS($user_address['address_city']);
                                                $user_country = $this->input_control->DecodePreventXSS($user_address['address_country']);
                                                if ($category_success && !empty($basketItems)) {
                                                    $request->setLocale(\Iyzipay\Model\Locale::TR);
                                                    $request->setConversationId($conversation_id['data']);
                                                    $request->setPrice($this->web_data['order_cart_data_price']);
                                                    $request->setPaidPrice($this->web_data['order_cart_data_total_price']);
                                                    $request->setCurrency(\Iyzipay\Model\Currency::TL);
                                                    $request->setInstallment(1);
                                                    $request->setBasketId($basket_id['data']);
                                                    $request->setCallbackUrl(URL . URL_ORDER_COMPLETE);

                                                    $paymentCard = new \Iyzipay\Model\PaymentCard();
                                                    $paymentCard->setCardHolderName($checked_inputs['cart_name']);
                                                    $paymentCard->setCardNumber($checked_inputs['cart_number']);
                                                    $paymentCard->setExpireMonth($checked_inputs['cart_expiry_month']);
                                                    $paymentCard->setExpireYear("20" . $checked_inputs['cart_expiry_year']);
                                                    $paymentCard->setCvc($checked_inputs['cart_cvc']);
                                                    $paymentCard->setRegisterCard(0);
                                                    $request->setPaymentCard($paymentCard);

                                                    $buyer = new \Iyzipay\Model\Buyer();
                                                    $buyer->setId($confirmed_user_from_db['data']['id']);
                                                    $buyer->setName($user_first_name);
                                                    $buyer->setSurname($user_last_name);
                                                    $buyer->setEmail($user_email);
                                                    $buyer->setIdentityNumber($confirmed_user_from_db['data']['identity_number']);
                                                    $buyer->setRegistrationAddress($user_county . ', ' . $user_neighborhood . ', ' . $user_street . ', No: ' . $user_address['address_building_no'] . '/' . $user_address['address_apartment_no']);
                                                    $buyer->setIp($_SERVER['REMOTE_ADDR']);
                                                    $buyer->setCity($user_city);
                                                    $buyer->setCountry($user_country);
                                                    $buyer->setZipCode($user_address['address_zip_no']);
                                                    $request->setBuyer($buyer);

                                                    $shippingAddress = new \Iyzipay\Model\Address();
                                                    $shippingAddress->setContactName($user_first_name . ' ' . $user_last_name);
                                                    $shippingAddress->setCity($user_city);
                                                    $shippingAddress->setCountry($user_country);
                                                    $shippingAddress->setAddress($user_county . ', ' . $user_neighborhood . ', ' . $user_street . ', No: ' . $user_address['address_building_no'] . '/' . $user_address['address_apartment_no']);
                                                    $shippingAddress->setZipCode($user_address['address_zip_no']);
                                                    $request->setShippingAddress($shippingAddress);

                                                    $billingAddress = new \Iyzipay\Model\Address();
                                                    $billingAddress->setContactName($user_first_name . ' ' . $user_last_name);
                                                    $billingAddress->setCity($user_city);
                                                    $billingAddress->setCountry($user_country);
                                                    $billingAddress->setAddress($user_county . ', ' . $user_neighborhood . ', ' . $user_street . ', No: ' . $user_address['address_building_no'] . '/' . $user_address['address_apartment_no']);
                                                    $billingAddress->setZipCode($user_address['address_zip_no']);
                                                    $request->setBillingAddress($billingAddress);

                                                    $request->setBasketItems($basketItems);
                                                    $threedsInitialize = \Iyzipay\Model\ThreedsInitialize::create($request, Config::options());
                                                    if (method_exists($threedsInitialize, 'getStatus') && method_exists($threedsInitialize, 'getHtmlContent') && method_exists($threedsInitialize, 'getConversationId') && method_exists($threedsInitialize, 'getSystemTime') && method_exists($threedsInitialize, 'getErrorCode') && method_exists($threedsInitialize, 'getErrorMessage') && method_exists($threedsInitialize, 'getErrorGroup')) {
                                                        if ($threedsInitialize->getStatus() == 'success') {
                                                            if ($threedsInitialize->getConversationId() == $conversation_id['data']) {
                                                                $cookie_authentication_token_part_1 = $this->input_control->GenerateToken();
                                                                $cookie_authentication_token_part_2 = $this->input_control->GenerateToken();
                                                                $cookie_authentication_salt = $this->input_control->GenerateToken();
                                                                if ($cookie_authentication_token_part_1['result'] && $cookie_authentication_token_part_2['result'] && $cookie_authentication_salt['result']) {
                                                                    $cookie_authentication_token = $cookie_authentication_token_part_1['data'] . $cookie_authentication_token_part_2['data'];
                                                                    $extracted_cookie_authentication_token1 = substr($cookie_authentication_token, 200, 200);
                                                                    $extracted_cookie_authentication_token2 = substr($cookie_authentication_token, 0, 200);
                                                                    if (!empty($extracted_cookie_authentication_token1) && !empty($extracted_cookie_authentication_token2)) {
                                                                        $cookie_authentication_token1 = hash_hmac('SHA512', $extracted_cookie_authentication_token1, $cookie_authentication_salt['data'], false);
                                                                        if (!empty($cookie_authentication_token1) && $this->ActionModel->CreateCookieAuthenticationCrossSite(array('user_id' => $confirmed_user_from_db['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'cookie_authentication_cross_site_token1' => $cookie_authentication_token1, 'cookie_authentication_cross_site_token2' => $extracted_cookie_authentication_token2, 'cookie_authentication_cross_site_salt' => $cookie_authentication_salt['data'], 'date_cookie_authentication_cross_site_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_COOKIE_AUTHENTICATION_CROSS_SITE))))['result'] && $this->cookie_control->SetCookie(COOKIE_AUTHENTICATION_CROSS_SITE_NAME, $cookie_authentication_token, time() + (EXPIRY_COOKIE_AUTHENTICATION_CROSS_SITE), COOKIE_PATH, COOKIE_DOMAIN, COOKIE_CROSS_SITE_SECURE, COOKIE_HTTP_ONLY, COOKIE_CROSS_SITE_SAMESITE)) {
                                                                            parent::LogView('Home-OrderCredit-3D');
                                                                            $this->web_data['iyzico_form'] = $threedsInitialize->getHtmlContent();
                                                                            parent::GetView('Home/Order3D', $this->web_data);
                                                                        }
                                                                    }
                                                                }
                                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                                $this->input_control->Redirect(URL_CART);
                                                            } else {
                                                                $this->ItemModel->CreateOrder1ConversationError(array(
                                                                    'conversation_id_request' => $conversation_id['data'],
                                                                    'conversation_id_response' => $this->input_control->SlashAndXSS($threedsInitialize->getConversationId()),
                                                                    'system_time' => $this->input_control->SlashAndXSS($threedsInitialize->getSystemTime())
                                                                ));
                                                                $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function OrderCredit | Conversation Error, Conversation ID : ' . $conversation_id['data']));
                                                                $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Order Error', 'Order conversation error occured.');
                                                                $this->ActionModel->CreateLogEmailSent(array('user_id' => $this->web_data['authenticated_user'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'OrderConversationError'));
                                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                                $this->input_control->Redirect(URL_CART);
                                                            }
                                                        } else {
                                                            $this->ItemModel->CreateOrder1StatusError(array(
                                                                'conversation_id' => $conversation_id['data'],
                                                                'status' => $this->input_control->SlashAndXSS($threedsInitialize->getStatus()),
                                                                'error_code' => $this->input_control->SlashAndXSS($threedsInitialize->getErrorCode()),
                                                                'error_message' => $this->input_control->SlashAndXSS($threedsInitialize->getErrorMessage()),
                                                                'error_group' => $this->input_control->SlashAndXSS($threedsInitialize->getErrorGroup()),
                                                                'system_time' => $this->input_control->SlashAndXSS($threedsInitialize->getSystemTime())
                                                            ));
                                                            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function OrderCredit | Status Failed, Conversation ID : ' . $conversation_id['data']));
                                                            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Order Error', 'Order status failed.');
                                                            $this->ActionModel->CreateLogEmailSent(array('user_id' => $this->web_data['authenticated_user'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'OrderStatusError'));
                                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                            $this->input_control->Redirect(URL_CART);
                                                        }
                                                    } else {
                                                        $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function OrderCredit | Method Not Exists, Conversation ID : ' . $conversation_id['data']));
                                                        $this->action_control->SendMail(ADMIN_EMAIL, BRAND . 'Order Error', 'Order method not exists.');
                                                        $this->ActionModel->CreateLogEmailSent(array('user_id' => $this->web_data['authenticated_user'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'OrderMethodExistError'));
                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                        $this->input_control->Redirect(URL_CART);
                                                    }
                                                } else {
                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                                }
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                            }
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_YEAR);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_MONTH);
                                }
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                        }
                        $this->input_control->Redirect(URL_ORDER_CREDIT);
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ORDER_USER_BLOCKED);
                    $this->input_control->Redirect(URL_CART);
                }
            } else {
                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                $this->input_control->Redirect(URL_CART);
            }
            parent::KillAuthentication('HomeController OrderCredit');
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class HomeController function OrderCredit | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
}
