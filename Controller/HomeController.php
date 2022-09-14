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
                                                $b = date('d/m/Y', strtotime($comments_reply_from_db['data'][$key2]['date_comment_reply_created']));
                                                if ($user_comment_reply_from_db['result'] && !empty($b)) {
                                                    $comments_reply_from_db['data'][$key2]['date_comment_reply_created'] = $b;
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
                    $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_LOG_OUT);
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
                $this->input_control->Redirect();
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
                        $user_from_database = $this->UserModel->GetUserByUserId('first_name,last_name,user_delete_able', $this->web_data['authenticated_user']);
                        break;
                    case URL_PROFILE_ADDRESS:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_ADDRESS;
                        $this->web_data['profile_title'] = URL_PROFILE_ADDRESS_TITLE;
                        $user_from_database = $this->UserModel->GetUserByUserId('address', $this->web_data['authenticated_user']);
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
                $this->input_control->Redirect();
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'user_first_name' => array('input' => isset($_POST['user_first_name']) ? $_POST['user_first_name'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_USER_NAME, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_USER_NAME, 'length_control' => true, 'max_length' => USER_NAME_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_NAME, 'preventxss' => true, 'length_limit' => USER_NAME_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_NAME),
                    'user_last_name' => array('input' => isset($_POST['user_last_name']) ? $_POST['user_last_name'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_USER_LAST_NAME, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_USER_LAST_NAME, 'length_control' => true, 'max_length' => USER_NAME_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_LAST_NAME, 'preventxss' => true, 'length_limit' => USER_NAME_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_LAST_NAME),
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
                $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_INFORMATIONS);
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
    function ProfilePasswordUpdate()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect();
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
                $this->input_control->Redirect();
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
                $this->input_control->Redirect();
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
    }
    function ProfilePhotoUpdate()
    {
        try {
            if (empty($this->web_data['authenticated_user'])) {
                $this->input_control->Redirect();
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_photo'])) {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Profile')) {
                        $confirmed_user_from_db = $this->UserModel->GetUserByUserId('id', $this->web_data['authenticated_user']);
                        if ($confirmed_user_from_db['result']) {
                            print_r($_FILES);
                            exit(0);





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












                            if ($this->UserModel->UpdateUser(array('profile_image_path' => '', 'profile_image' => '', 'id' => $confirmed_user_from_db['data']['id']))['result']) {
                                $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_PROFILE_PHOTO_UPDATE);
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
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
                $this->input_control->Redirect();
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
}
