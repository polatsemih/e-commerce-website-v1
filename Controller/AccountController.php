<?php
class AccountController extends Controller
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
        //             $authenticated_user_from_database = $this->UserModel->GetUser('id', $session_authentication_from_database['user_id']);
        //             if (!empty($authenticated_user_from_database)) {
        //                 if (session_regenerate_id(true)) {
        //                     $session_authentication_error = false;
        //                     $this->web_data['authenticated_user_id'] = $authenticated_user_from_database['id'];
        //                     $this->web_data['session_authentication_id'] = $session_authentication_from_database['id'];
        //                 }
        //             }
        //             if ($session_authentication_error && $this->ActionModel->UpdateSessionAuthentication(array('session_authentication_is_killed' => 1, 'session_authentication_killed_function' => 'AccountController __construct', 'date_session_authentication_killed' => date('Y-m-d H:i:s'), 'id' => $session_authentication_from_database['id'])) == 'Updated') {
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
        //                     $authenticated_user_from_database = $this->UserModel->GetUser('id', $cookie_authentication_from_database['user_id']);
        //                     if (!empty($authenticated_user_from_database)) {
        //                         if (session_regenerate_id(true)) {
        //                             $cookie_authentication_error = false;
        //                             $this->web_data['authenticated_user_id'] = $authenticated_user_from_database['id'];
        //                             $this->web_data['cookie_authentication_id'] = $cookie_authentication_from_database['id'];
        //                         }
        //                     }
        //                 }
        //                 if ($cookie_authentication_error && $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME) && $this->ActionModel->UpdateCookieAuthentication(array('cookie_authentication_is_killed' => 1, 'cookie_authentication_killed_function' => 'AccountController __construct', 'date_cookie_authentication_killed' => date('Y-m-d H:i:s'), 'id' => $cookie_authentication_from_database['id'])) == 'Updated') {
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
        // } else {
        //     $this->input_control->Redirect(URL_LOGIN);
        // }
        
        $this->web_data['authenticated_user_id'] = '1BEV2kDDjyZ1Qb3APgxurvkDrW7biovabxUNnNJgd1B1X4sF12oVCKd9xKNIKcDEuzZzODIw7IcuEqYFgmudVWxnLPA5OJyZexfrTpQTqbntSMHx2um2j740phOJqmQERfUqkj0JobJyeiS5G0k7LySjiBEVtAbvhx4cvOQm8HgdrlPG6KS4vthDRUIo9GuJhooVBBlJgyrJxCrPjJCclTgNUvHLJlOCiF7ddGaz3JX8LjE2NTg1Njk4Mzc4NTU';
    }
    function LogOut()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $result_log_out = false;
            if (!empty($this->web_data['session_authentication_id']) && $this->ActionModel->UpdateSessionAuthentication(array('session_authentication_is_logout' => 1, 'date_session_authentication_logout' => date('Y-m-d H:i:s'), 'id' => $this->web_data['session_authentication_id'])) == 'Updated' && $this->session_control->KillSession()) {
                $result_log_out = true;
            }
            if (!empty($this->web_data['cookie_authentication_id']) && $this->ActionModel->UpdateCookieAuthentication(array('cookie_authentication_is_logout' => 1, 'date_cookie_authentication_logout' => date('Y-m-d H:i:s'), 'id' => $this->web_data['cookie_authentication_id'])) == 'Updated' && $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME)) {
                $result_log_out = true;
            }
            if ($result_log_out) {
                $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_LOG_OUT);
            } else {
                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOG_OUT);
            }
        }
        $this->input_control->Redirect();
    }
    function KillAuthentication(string $killed_function)
    {
        $result_kill_authentication = false;
        if (!empty($this->web_data['session_authentication_id']) && $this->ActionModel->UpdateSessionAuthentication(array('session_authentication_is_killed' => 1, 'session_authentication_killed_function' => $killed_function, 'date_session_authentication_killed' => date('Y-m-d H:i:s'), 'id' => $this->web_data['session_authentication_id'])) == 'Updated') {
            $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
            $result_kill_authentication = true;
        }
        if (!empty($this->web_data['cookie_authentication_id']) && $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME) && $this->ActionModel->UpdateCookieAuthentication(array('cookie_authentication_is_killed' => 1, 'cookie_authentication_killed_function' => $killed_function, 'date_cookie_authentication_killed' => date('Y-m-d H:i:s'), 'id' => $this->web_data['cookie_authentication_id'])) == 'Updated') {
            $result_kill_authentication = true;
        }
        if ($result_kill_authentication) {
            $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
        } else {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function KillAuthentication ' . $killed_function));
            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_AUTHENTICATION_KILLED);
        }
    }
    function CommentCreate()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = isset($_POST['comment_text']) ? strip_tags($_POST['comment_text']) : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment' => array('input' => $comment, 'error_message_empty' => TR_NOTIFICATION_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_COMMENT_LIMIT, 'preventxss' => true, 'length_limit' => COMMENT_LIMIT_DB),
                'item_url' => array('input' => isset($_POST['item_url']) ? $_POST['item_url'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    $item_id_from_database = $this->ItemModel->GetItemIdByUrl($checked_inputs['item_url']);
                    if (!empty($item_id_from_database)) {
                        parent::GetModel('CommentModel');
                        $comment_create_result = $this->CommentModel->CreateComment(array(
                            'user_id' => $this->web_data['authenticated_user_id'],
                            'item_id' => $item_id_from_database['id'],
                            'comment' => $checked_inputs['comment'],
                            'is_comment_approved' => 0
                        ));
                        if ($comment_create_result['result'] == 'Created') {
                            $created_comment_from_database = $this->CommentModel->GetCommentById($comment_create_result['id']);
                            if (!empty($created_comment_from_database)) {
                                $created_comment_from_database['comment'] = stripslashes($comment);
                                $created_comment_from_database['date_comment_created'] = date('d/m/Y', strtotime($created_comment_from_database['date_comment_created']));
                                $result_set_csrf_token = parent::SetCSRFToken('ItemDetails');
                                if ($result_set_csrf_token == false) {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
                                } else {
                                    $user_form_database = $this->UserModel->GetUser('first_name,last_name,profile_image_path,profile_image', $this->web_data['authenticated_user_id']);
                                    if (!empty($user_form_database)) {
                                        $response['reset'] = false;
                                        $response['form_token'] = $result_set_csrf_token;
                                        $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_CREATE);
                                        $response['comment'] = $created_comment_from_database;
                                        $response['comment_user'] = array(
                                            'user_first_name' => $user_form_database['first_name'],
                                            'user_last_name' => $user_form_database['last_name'],
                                            'user_profile_image_path' => $user_form_database['profile_image_path'],
                                            'user_profile_image' => $user_form_database['profile_image']
                                        );
                                    }
                                }
                            } else {
                                $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_CREATE);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_COMMENT_CREATE);
                    }
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
        } else {
            $this->KillAuthentication('CommentCreate');
        }
        echo json_encode($response);
        exit(0);
    }
    function CommentUpdate()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = isset($_POST['comment_text']) ? strip_tags($_POST['comment_text']) : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'comment' => array('input' => $comment, 'error_message_empty' => TR_NOTIFICATION_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_COMMENT_LIMIT, 'preventxss' => true, 'length_limit' => COMMENT_LIMIT_DB),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $confirm_comment = $this->CommentModel->ConfirmComment(array($checked_inputs['comment_id'], $this->web_data['authenticated_user_id']));
                    if (!empty($confirm_comment)) {
                        $comment_update_result = $this->CommentModel->UpdateComment(array(
                            'comment' => $checked_inputs['comment'],
                            'id' => $confirm_comment['id']
                        ));
                        if ($comment_update_result == 'Updated') {
                            $result_set_csrf_token = parent::SetCSRFToken('ItemDetails');
                            if ($result_set_csrf_token == false) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
                            } else {
                                $response['reset'] = false;
                                $response['form_token'] = $result_set_csrf_token;
                                $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_UPDATE);
                                $response['comment'] = array('comment' => stripslashes($comment), 'id' => $confirm_comment['id']);                                
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_COMMENT_UPDATE);
                    }
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
        } else {
            $this->KillAuthentication('CommentUpdate');
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
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $confirm_comment = $this->CommentModel->ConfirmComment(array($checked_inputs['comment_id'], $this->web_data['authenticated_user_id']));
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
                            $result_set_csrf_token = parent::SetCSRFToken('ItemDetails');
                            if ($result_set_csrf_token == false) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
                            } else {
                                $response['reset'] = false;
                                $response['form_token'] = $result_set_csrf_token;
                                $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_DELETE);
                                $response['comment_id'] = $confirm_comment['id'];
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_COMMENT_DELETE);
                    }
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
        } else {
            $this->KillAuthentication('CommentDelete');
        }
        echo json_encode($response);
        exit(0);
    }
    function CommentReplyCreate()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment_reply = isset($_POST['comment_reply_text']) ? strip_tags($_POST['comment_reply_text']) : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'comment_reply' => array('input' => $comment_reply, 'error_message_empty' => TR_NOTIFICATION_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_COMMENT_LIMIT, 'preventxss' => true, 'length_limit' => COMMENT_LIMIT_DB),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $comment_reply_create_result = $this->CommentModel->CreateCommentReply(array(
                        'comment_id' => $checked_inputs['comment_id'],
                        'user_id' => $this->web_data['authenticated_user_id'],
                        'comment_reply' => $checked_inputs['comment_reply'],
                        'is_comment_reply_approved' => 0
                    ));
                    if ($comment_reply_create_result['result'] == 'Created') {
                        $created_comment_from_database = $this->CommentModel->GetCommentReplyById($comment_reply_create_result['id']);
                        if (!empty($created_comment_from_database)) {
                            $created_comment_from_database['comment_reply'] = stripslashes($comment_reply);
                            $created_comment_from_database['date_comment_reply_created'] = date('d/m/Y', strtotime($created_comment_from_database['date_comment_reply_created']));
                            $result_set_csrf_token = parent::SetCSRFToken('ItemDetails');
                            if ($result_set_csrf_token == false) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
                            } else {
                                $user_form_database = $this->UserModel->GetUser('first_name,last_name,profile_image_path,profile_image', $this->web_data['authenticated_user_id']);
                                if (!empty($user_form_database)) {
                                    $response['reset'] = false;
                                    $response['form_token'] = $result_set_csrf_token;
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_CREATE);
                                    $response['comment_reply'] = $created_comment_from_database;
                                    $response['comment_reply_user'] = array(
                                        'user_first_name' => $user_form_database['first_name'],
                                        'user_last_name' => $user_form_database['last_name'],
                                        'user_profile_image_path' => $user_form_database['profile_image_path'],
                                        'user_profile_image' => $user_form_database['profile_image']
                                    );
                                }
                            }
                        } else {
                            $this->notification_control->SetNotification('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_CREATE);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                    }
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
        } else {
            $this->KillAuthentication('CommentReplyCreate');
        }
        echo json_encode($response);
        exit(0);
    }
    function CommentReplyUpdate()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment_reply = isset($_POST['comment_reply_text']) ? strip_tags($_POST['comment_reply_text']) : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'comment_reply' => array('input' => $comment_reply, 'error_message_empty' => TR_NOTIFICATION_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_COMMENT_LIMIT, 'preventxss' => true, 'length_limit' => COMMENT_LIMIT_DB),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $confirm_comment_reply = $this->CommentModel->ConfirmCommentReply(array($checked_inputs['comment_reply_id'], $checked_inputs['comment_id'], $this->web_data['authenticated_user_id']));
                    if (!empty($confirm_comment_reply)) {
                        $comment_reply_update_result = $this->CommentModel->UpdateCommentReply(array(
                            'comment_reply' => $checked_inputs['comment_reply'],
                            'id' => $confirm_comment_reply['id']
                        ));
                        if ($comment_reply_update_result == 'Updated') {
                            $result_set_csrf_token = parent::SetCSRFToken('ItemDetails');
                            if ($result_set_csrf_token == false) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
                            } else {
                                $response['reset'] = false;
                                $response['form_token'] = $result_set_csrf_token;
                                $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_UPDATE);
                                $response['comment_reply'] = array('comment_reply' => stripslashes($comment_reply), 'id' => $confirm_comment_reply['id'], 'comment_id' => $confirm_comment_reply['comment_id']);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_COMMENT_UPDATE);
                    }
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
        } else {
            $this->KillAuthentication('CommentReplyUpdate');
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
                'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('CommentModel');
                    $confirm_comment_reply = $this->CommentModel->ConfirmCommentReply(array($checked_inputs['comment_reply_id'], $checked_inputs['comment_id'], $this->web_data['authenticated_user_id']));
                    if (!empty($confirm_comment_reply)) {
                        $comment_reply_delete_result = $this->CommentModel->UpdateCommentReply(array(
                            'is_comment_reply_deleted' => 1,
                            'date_comment_reply_deleted' => date('Y-m-d H:i:s'),
                            'id' => $confirm_comment_reply['id']
                        ));
                        if ($comment_reply_delete_result == 'Updated') {
                            $result_set_csrf_token = parent::SetCSRFToken('ItemDetails');
                            if ($result_set_csrf_token == false) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
                            } else {
                                $response['reset'] = false;
                                $response['form_token'] = $result_set_csrf_token;
                                $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_DELETE);
                                $response['comment'] = array('comment_reply_id' => $confirm_comment_reply['id'], 'comment_id' => $confirm_comment_reply['comment_id']);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_COMMENT_DELETE);
                    }
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
        } else {
            $this->KillAuthentication('CommentReplyDelete');
        }
        echo json_encode($response);
        exit(0);
    }
    function AddToFavorites()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'item_cart_id' => array('input' => isset($_POST['item']) ? $_POST['item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $confirmed_item_id_from_database = $this->ItemModel->GetItemIdByItemCartId($checked_inputs['item_cart_id']);
                if (!empty($confirmed_item_id_from_database)) {
                    if ($this->ItemModel->CreateFavorite(array('item_id' => $confirmed_item_id_from_database['id'], 'user_id' => $this->web_data['authenticated_user_id'])) == 'Created') {
                        $result_set_csrf_token = parent::SetCSRFToken('ItemDetails');
                        if ($result_set_csrf_token == false) {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
                        } else {
                            $response['reset'] = false;
                            $response['form_token'] = $result_set_csrf_token;
                            $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_ADD_TO_FAVORITES);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_FAVORITES);
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
        } else {
            $this->KillAuthentication('AddToFavorites');
        }
        echo json_encode($response);
        exit(0);
    }
    function RemoveFavorite()
    {
        $response = array();
        $response['reset'] = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'item_cart_id' => array('input' => isset($_POST['item']) ? $_POST['item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $confirmed_item_id_from_database = $this->ItemModel->GetItemIdByItemCartId($checked_inputs['item_cart_id']);
                if (!empty($confirmed_item_id_from_database)) {
                    $confirmed_favorite_from_db = $this->ItemModel->GetFavorite(array($confirmed_item_id_from_database['id'], $this->web_data['authenticated_user_id']));
                    if (!empty($confirmed_favorite_from_db)) {
                        if ($this->ItemModel->UpdateFavorite(array('is_favorite_removed' => 1, 'favorite_removed_date' => date('Y-m-d H:i:s'), 'id' => $confirmed_favorite_from_db['id'])) == 'Updated') {
                            $result_set_csrf_token = parent::SetCSRFToken('ItemDetails');
                            if ($result_set_csrf_token == false) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
                            } else {
                                $response['reset'] = false;
                                $response['form_token'] = $result_set_csrf_token;
                                $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_REMOVE_FROM_FAVORITES);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REMOVE_FROM_FAVORITES);
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REMOVE_FROM_FAVORITES);
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
        } else {
            $this->KillAuthentication('RemoveFavorite');
        }
        echo json_encode($response);
        exit(0);
    }
    function Profile(string $profile_url)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $checked_profile_url = $this->input_control->CheckGETInput($profile_url);
            if (!is_null($checked_profile_url)) {
                $this->input_control->CheckUrl();
                parent::LogView('Home/Profile/' . $checked_profile_url);
                $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
                $case_matched = false;
                switch ($checked_profile_url) {
                    case URL_PROFILE_INFO:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_INFO;
                        $this->web_data['profile_title'] = URL_PROFILE_INFO_TITLE;
                        $user_from_database = $this->UserModel->GetUser('id,first_name,last_name,address,user_delete_able', $this->web_data['authenticated_user_id']);
                        break;
                    case URL_PROFILE_PWD:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_PWD;
                        $this->web_data['profile_title'] = URL_PROFILE_PWD_TITLE;
                        $user_from_database = $this->UserModel->GetUser('id', $this->web_data['authenticated_user_id']);
                        break;
                    case URL_PROFILE_EMAIL:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_EMAIL;
                        $this->web_data['profile_title'] = URL_PROFILE_EMAIL_TITLE;
                        $user_from_database = $this->UserModel->GetUser('id,email', $this->web_data['authenticated_user_id']);
                        break;
                    case URL_PROFILE_TEL:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_TEL;
                        $this->web_data['profile_title'] = URL_PROFILE_TEL_TITLE;
                        $user_from_database = $this->UserModel->GetUser('id,phone_number', $this->web_data['authenticated_user_id']);
                        break;
                    case URL_PROFILE_PHOTO:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_PHOTO;
                        $this->web_data['profile_title'] = URL_PROFILE_PHOTO_TITLE;
                        $user_from_database = $this->UserModel->GetUser('id,profile_image_path,profile_image', $this->web_data['authenticated_user_id']);
                        break;
                    case URL_PROFILE_ORDERS:
                        $case_matched = true;
                        $this->web_data['profile_type'] = URL_PROFILE_ORDERS;
                        $this->web_data['profile_title'] = URL_PROFILE_ORDERS_TITLE;
                        $user_from_database = $this->UserModel->GetUser('id', $this->web_data['authenticated_user_id']);
                        break;
                }
                if ($case_matched) {
                    $result_set_csrf_token = parent::SetCSRFToken('Profile');
                    if ($result_set_csrf_token == false || empty($user_from_database)) {
                        parent::GetView('Error/NotResponse');
                    } else {
                        $this->web_data['form_token'] = $result_set_csrf_token;
                        $this->web_data['authenticated_user'] = $user_from_database;
                        parent::GetView('Home/Profile', $this->web_data);
                    }
                }
            }
        }
        $this->KillAuthentication('Profile');
        $this->input_control->Redirect();
    }

    
    
    











    
    
    function ProfileUpdate()
    {
    }
    function PasswordUpdate()
    {
    }
    function EmailUpdate()
    {
    }
    function TelUpdate()
    {
    }
    function ProfilePhotoUpdate()
    {
    }
    function ProfileDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_user_delete'])) {
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'user_id' => array('input' => isset($_POST['user_id']) ? $_POST['user_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'Profile');
                if ($result_check_csrf_token == true) {
                    parent::GetModel('UserModel');
                    $confirmed_user_from_db = $this->UserModel->GetUserById('id', $checked_inputs['user_id']);
                    if (!empty($confirmed_user_from_db)) {
                        $result_account_delete = $this->UserModel->UpdateUser(array(
                            'is_user_deleted' => 1,
                            'date_user_deleted' => date('Y-m-d H:i:s'),
                            'id' => $confirmed_user_from_db['id']
                        ));
                        if ($result_account_delete == 'Updated') {
                            $this->notification_control->SetNotification('SUCCESS', SUCCESS_ACCOUNT_DELETE);
                            $this->input_control->Redirect(URL_LOGOUT);
                        } else {
                            $this->notification_control->SetNotification('DANGER', DATABASE_ERROR);
                        }
                    }
                } else {
                    $_SESSION[SESSION_NOTIFICATION_NAME] = $result_check_csrf_token;
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
            $this->input_control->Redirect(URL_PROFILE . '/' . URL_PROFILE_INFO);
        }
        $this->session_control->KillSession();
        $this->input_control->Redirect();
    }
}
