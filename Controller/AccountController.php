<?php
class AccountController extends Controller
{
    private $session_auth;
    private $cookie_auth;
    function __construct()
    {
        parent::__construct();
        $this->GetModel('ActionModel');
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
                        $this->session_auth = $session_auth_from_db;
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
                            $this->cookie_auth = $cookie_auth_from_db;
                        }
                    }
                }
            }
            if ($is_cookie_not_valid) {
                $this->cookie_control->EmptyCookie(COOKIE_AUTH_NAME);
                $this->input_control->Redirect(URL_LOGIN);
            }
        } else {
            $this->input_control->Redirect(URL_LOGIN);
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
    function LogOut()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!empty($this->session_auth)) {
                $this->ActionModel->UpdateSessionAuth(array(
                    'session_auth_is_logout' => 1,
                    'date_session_auth_logout' => date('Y-m-d H:i:s'),
                    'id' => $this->session_auth['id']
                ));
                $this->session_control->KillSession();
            }
            if (!empty($this->cookie_auth)) {
                $this->ActionModel->UpdateCookieAuth(array(
                    'cookie_auth_is_logout' => 1,
                    'date_cookie_auth_logout' => date('Y-m-d H:i:s'),
                    'id' => $this->cookie_auth['id']
                ));
                $this->cookie_control->EmptyCookie(COOKIE_AUTH_NAME);
            }
        }
        $this->input_control->Redirect();
    }
    function CommentCreate()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = isset($_POST['comment_text']) ? $_POST['comment_text'] : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment' => array('input' => strip_tags($comment), 'error_message_empty' => ERROR_MESSAGE_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_LIMIT, 'error_message_max_length' => ERROR_MAX_LENGTH_COMMENT, 'preventxss' => true, 'length_limit' => COMMENT_LIMIT_DB),
                'item_url' => array('input' => isset($_POST['item_url']) ? $_POST['item_url'] : '', 'error_message_empty' => FORM_INPUT_ERROR, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('ItemModel');
                    $item_id_from_db = $this->ItemModel->GetItemIdByUrl($checked_inputs['item_url']);
                    if (!empty($item_id_from_db)) {
                        parent::GetModel('CommentModel');
                        $comment_create_result = $this->CommentModel->CreateComment(array(
                            'user_id' => $this->web_data['authed_user']['id'],
                            'item_id' => $item_id_from_db['id'],
                            'comment' => $checked_inputs['comment'],
                            'is_comment_approved' => 0
                        ));
                        if ($comment_create_result['result'] == 'Created') {
                            $created_comment_from_db = $this->CommentModel->GetCommentById($comment_create_result['id']);
                            if (!empty($created_comment_from_db)) {
                                $created_comment_from_db['comment'] = stripslashes(strip_tags($comment));
                                $created_comment_from_db['date_comment_created'] = date('d/m/Y', strtotime($created_comment_from_db['date_comment_created']));
                                $csrf_token = $this->SetCSRFToken('ItemDetails');
                                if (!is_null($csrf_token)) {
                                    $response['notification'] = $this->notification_control->Success(SUCCESS_COMMENT_CREATE);
                                    $response['comment'] = $created_comment_from_db;
                                    $response['comment_user'] = array(
                                        'user_first_name' => ucfirst($this->web_data['authed_user']['first_name']),
                                        'user_last_name' => ucfirst($this->web_data['authed_user']['last_name']),
                                        'user_profile_image_path' => $this->web_data['authed_user']['profile_image_path'],
                                        'user_profile_image' => $this->web_data['authed_user']['profile_image']
                                    );
                                    $response['form_token'] = $csrf_token;
                                    $response['reset'] = false;
                                } else {
                                    $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(GENERAL_ERROR);
                                }
                            } else {
                                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Success(SUCCESS_COMMENT_CREATE);
                            }
                        } else {
                            $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(DATABASE_ERROR);
                        }
                    } else {
                        $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(ERROR_COMMENT_CREATE);
                    }
                } else {
                    $_SESSION[SESSION_NOTIFICATION] = $result_check_csrf_token;
                }
            } else {
                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger($checked_inputs['error_message']);
            }
        } else {
            $this->session_control->KillSession();
        }
        echo json_encode($response);
        exit(0);
    }
    function CommentUpdate()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = isset($_POST['comment_text']) ? $_POST['comment_text'] : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => FORM_INPUT_ERROR, 'preventxss' => true),
                'comment' => array('input' => strip_tags($comment), 'error_message_empty' => ERROR_MESSAGE_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_LIMIT, 'error_message_max_length' => ERROR_MAX_LENGTH_COMMENT, 'preventxss' => true, 'length_limit' => COMMENT_LIMIT_DB),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $confirm_comment = $this->CommentModel->ConfirmComment(array($checked_inputs['comment_id'], $this->web_data['authed_user']['id']));
                    if (!empty($confirm_comment)) {
                        $comment_update_result = $this->CommentModel->UpdateComment(array(
                            'comment' => $checked_inputs['comment'],
                            'id' => $confirm_comment['id']
                        ));
                        if ($comment_update_result == 'Updated') {
                            $csrf_token = $this->SetCSRFToken('ItemDetails');
                            if (!is_null($csrf_token)) {
                                $response['notification'] = $this->notification_control->Success(SUCCESS_COMMENT_UPDATE);
                                $response['comment'] = array('comment' => stripslashes(strip_tags($comment)), 'id' => $confirm_comment['id']);
                                $response['form_token'] = $csrf_token;
                                $response['reset'] = false;
                            } else {
                                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(GENERAL_ERROR);
                            }
                        } else {
                            $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(DATABASE_ERROR);
                        }
                    } else {
                        $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(ERROR_COMMENT_UPDATE);
                    }
                } else {
                    $_SESSION[SESSION_NOTIFICATION] = $result_check_csrf_token;
                }
            } else {
                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger($checked_inputs['error_message']);
            }
        } else {
            $this->session_control->KillSession();
        }
        echo json_encode($response);
        exit(0);
    }
    function CommentDelete()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => FORM_INPUT_ERROR, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $confirm_comment = $this->CommentModel->ConfirmComment(array($checked_inputs['comment_id'], $this->web_data['authed_user']['id']));
                    if (!empty($confirm_comment)) {
                        $comment_has_reply = $this->CommentModel->CommentHasReply($confirm_comment['id']);
                        if (!empty($comment_has_reply)) {
                            foreach ($comment_has_reply as $comment_reply) {
                                $this->CommentModel->UpdateCommentReply(array(
                                    'is_comment_reply_deleted' => 1,
                                    'date_comment_reply_deleted' => date('Y-m-d H:i:s'),
                                    'id' => $comment_reply['id']
                                ));
                            }
                        }
                        $comment_delete_result = $this->CommentModel->UpdateComment(array(
                            'is_comment_deleted' => 1,
                            'date_comment_deleted' => date('Y-m-d H:i:s'),
                            'id' => $confirm_comment['id']
                        ));
                        if ($comment_delete_result == 'Updated') {
                            $csrf_token = $this->SetCSRFToken('ItemDetails');
                            if (!is_null($csrf_token)) {
                                $response['notification'] = $this->notification_control->Success(SUCCESS_COMMENT_DELETE);
                                $response['comment_id'] = $confirm_comment['id'];
                                $response['form_token'] = $csrf_token;
                                $response['reset'] = false;
                            } else {
                                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(GENERAL_ERROR);
                            }
                        } else {
                            $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(DATABASE_ERROR);
                        }
                    } else {
                        $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(ERROR_COMMENT_DELETE);
                    }
                } else {
                    $_SESSION[SESSION_NOTIFICATION] = $result_check_csrf_token;
                }
            } else {
                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger($checked_inputs['error_message']);
            }
        } else {
            $this->session_control->KillSession();
        }
        echo json_encode($response);
        exit(0);
    }
    function CommentReplyCreate()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment_reply = isset($_POST['comment_reply_text']) ? $_POST['comment_reply_text'] : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => DATABASE_ERROR, 'preventxss' => true),
                'comment_reply' => array('input' => strip_tags($comment_reply), 'error_message_empty' => ERROR_MESSAGE_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_LIMIT, 'error_message_max_length' => ERROR_MAX_LENGTH_COMMENT, 'preventxss' => true, 'length_limit' => COMMENT_LIMIT_DB),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $comment_reply_create_result = $this->CommentModel->CreateCommentReply(array(
                        'comment_id' => $checked_inputs['comment_id'],
                        'user_id' => $this->web_data['authed_user']['id'],
                        'comment_reply' => $checked_inputs['comment_reply'],
                        'is_comment_reply_approved' => 0
                    ));
                    if ($comment_reply_create_result['result'] == 'Created') {
                        $created_comment_from_db = $this->CommentModel->GetCommentReplyById($comment_reply_create_result['id']);
                        if (!empty($created_comment_from_db)) {
                            $created_comment_from_db['comment_reply'] = stripslashes(strip_tags($comment_reply));
                            $created_comment_from_db['date_comment_reply_created'] = date('d/m/Y', strtotime($created_comment_from_db['date_comment_reply_created']));
                            $csrf_token = $this->SetCSRFToken('ItemDetails');
                            if (!is_null($csrf_token)) {
                                $response['notification'] = $this->notification_control->Success(SUCCESS_COMMENT_CREATE);
                                $response['comment_reply'] = $created_comment_from_db;
                                $response['comment_reply_user'] = array(
                                    'user_first_name' => ucfirst($this->web_data['authed_user']['first_name']),
                                    'user_last_name' => ucfirst($this->web_data['authed_user']['last_name']),
                                    'user_profile_image_path' => $this->web_data['authed_user']['profile_image_path'],
                                    'user_profile_image' => $this->web_data['authed_user']['profile_image']
                                );
                                $response['form_token'] = $csrf_token;
                                $response['reset'] = false;
                            } else {
                                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(GENERAL_ERROR);
                            }
                        } else {
                            $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Success(SUCCESS_COMMENT_CREATE);
                        }
                    } else {
                        $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(DATABASE_ERROR);
                    }
                } else {
                    $_SESSION[SESSION_NOTIFICATION] = $result_check_csrf_token;
                }
            } else {
                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger($checked_inputs['error_message']);
            }
        } else {
            $this->session_control->KillSession();
        }
        echo json_encode($response);
        exit(0);
    }
    function CommentReplyUpdate()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment_reply = isset($_POST['comment_reply_text']) ? $_POST['comment_reply_text'] : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => FORM_INPUT_ERROR, 'preventxss' => true),
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => FORM_INPUT_ERROR, 'preventxss' => true),
                'comment_reply' => array('input' => strip_tags($comment_reply), 'error_message_empty' => ERROR_MESSAGE_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_LIMIT, 'error_message_max_length' => ERROR_MAX_LENGTH_COMMENT, 'preventxss' => true, 'length_limit' => COMMENT_LIMIT_DB),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $confirm_comment_reply = $this->CommentModel->ConfirmCommentReply(array($checked_inputs['comment_reply_id'], $checked_inputs['comment_id'], $this->web_data['authed_user']['id']));
                    if (!empty($confirm_comment_reply)) {
                        $comment_reply_update_result = $this->CommentModel->UpdateCommentReply(array(
                            'comment_reply' => $checked_inputs['comment_reply'],
                            'id' => $confirm_comment_reply['id']
                        ));
                        if ($comment_reply_update_result == 'Updated') {
                            $csrf_token = $this->SetCSRFToken('ItemDetails');
                            if (!is_null($csrf_token)) {
                                $response['notification'] = $this->notification_control->Success(SUCCESS_COMMENT_UPDATE);
                                $response['comment_reply'] = array('comment_reply' => stripslashes(strip_tags($comment_reply)), 'id' => $confirm_comment_reply['id']);
                                $response['form_token'] = $csrf_token;
                                $response['reset'] = false;
                            } else {
                                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(GENERAL_ERROR);
                            }
                        } else {
                            $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(DATABASE_ERROR);
                        }
                    } else {
                        $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(ERROR_COMMENT_UPDATE);
                    }
                } else {
                    $_SESSION[SESSION_NOTIFICATION] = $result_check_csrf_token;
                }
            } else {
                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger($checked_inputs['error_message']);
            }
        } else {
            $this->session_control->KillSession();
        }
        echo json_encode($response);
        exit(0);
    }
    function CommentReplyDelete()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => FORM_INPUT_ERROR, 'preventxss' => true),
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => FORM_INPUT_ERROR, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $confirm_comment_reply = $this->CommentModel->ConfirmCommentReply(array($checked_inputs['comment_reply_id'], $checked_inputs['comment_id'], $this->web_data['authed_user']['id']));
                    if (!empty($confirm_comment_reply)) {
                        $comment_reply_delete_result = $this->CommentModel->UpdateCommentReply(array(
                            'is_comment_reply_deleted' => 1,
                            'date_comment_reply_deleted' => date('Y-m-d H:i:s'),
                            'id' => $confirm_comment_reply['id']
                        ));
                        if ($comment_reply_delete_result == 'Updated') {
                            $csrf_token = $this->SetCSRFToken('ItemDetails');
                            if (!is_null($csrf_token)) {
                                $response['notification'] = $this->notification_control->Success(SUCCESS_COMMENT_DELETE);
                                $response['comment'] = array('comment_reply_id' => $confirm_comment_reply['id'], 'comment_id' => $confirm_comment_reply['comment_id']);
                                $response['form_token'] = $csrf_token;
                                $response['reset'] = false;
                            } else {
                                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(GENERAL_ERROR);
                            }
                        } else {
                            $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(GENERAL_ERROR);
                        }
                    } else {
                        $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger(ERROR_COMMENT_DELETE);
                    }
                } else {
                    $_SESSION[SESSION_NOTIFICATION] = $result_check_csrf_token;
                }
            } else {
                $_SESSION[SESSION_NOTIFICATION] = $this->notification_control->Danger($checked_inputs['error_message']);
            }
        } else {
            $this->session_control->KillSession();
        }
        echo json_encode($response);
        exit(0);
    }




    
    function RemoveFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_removefromcart'])) {
            if (isset($_POST['item_id']) && isset($_POST['item_size']) && isset($_POST['item_quantity'])) {
                $item_id = $_POST['item_id'];
                $item_size = $_POST['item_size'];
                $item_quantity = $_POST['item_quantity'];
                if (!empty($item_id) && (strlen($item_id) == 50) && !empty($item_size) && !empty($item_quantity) && is_numeric($item_quantity) && $item_quantity > 0) {
                    if (isset($_COOKIE[COOKIE_CART_NAME])) {
                        $cart_cookie = $this->cookie_control->GetCookie($_COOKIE[COOKIE_CART_NAME]);
                        if (!empty($cart_cookie)) {
                            $cart_items = json_decode($cart_cookie, true);
                            if (in_array($item_id, array_column($cart_items, 'item_id'))) {
                                foreach ($cart_items as $key => $value) {
                                    if ($cart_items[$key]['item_id'] == $item_id && $cart_items[$key]['item_size'] == $item_size) {
                                        if ($cart_items[$key]['item_quantity'] <= 10 && $cart_items[$key]['item_quantity'] > 0) {
                                            $cart_items[$key]['item_quantity'] = $cart_items[$key]['item_quantity'] - $item_quantity;
                                            if ($cart_items[$key]['item_quantity'] == 0) {
                                                unset($cart_items[$key]);
                                            }
                                        }
                                    }
                                }
                                if (!empty($cart_items)) {
                                    $expires = time() + 60 * 60 * 24 * 30;
                                    $this->cookie_control->SetCookie(COOKIE_CART_NAME, json_encode($cart_items), $expires, '/', DOMAIN, SECURE, HTTP_ONLY, SAMESITE);
                                    header('Location: ' . URL . '/HomeController/HomeShoppingCart');
                                } else {
                                    $this->cookie_control->EmptyCookie(COOKIE_CART_NAME);
                                    header('Location: ' . URL . '/HomeController/HomeShoppingCart');
                                }
                            }
                        } else {
                            $this->cookie_control->EmptyCookie(COOKIE_CART_NAME);
                            header('Location: ' . URL . '/HomeController/HomeShoppingCart');
                        }
                    } else {
                        $this->input_control->Redirect();
                    }
                } else {
                    $this->input_control->Redirect();
                }
            } else {
                $this->input_control->Redirect();
            }
        } else {
            $this->input_control->Redirect();
        }
    }
    function EmptyCart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_emptycart'])) {
            if (isset($_COOKIE[COOKIE_CART_NAME])) {
                $this->cookie_control->EmptyCookie(COOKIE_CART_NAME);
                $this->GetView('Home/HomeShoppingCart', array('notfound_cookie' => $this->notification_control->NotFound("Sepetinizde Ürün Yok")));
            } else {
                $this->input_control->Redirect();
            }
        } else {
            $this->input_control->Redirect();
        }
    }
    function Settings()
    {
    }
    function Profile()
    {
    }
    function ProfileUpdate()
    {
    }
    function ProfileDelete()
    {
    }
}
