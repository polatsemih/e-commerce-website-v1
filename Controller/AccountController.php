<?php
class AccountController extends Controller
{
    function __construct()
    {
        parent::__construct();
        if (!empty($this->web_data['authenticated_user'])) {
            echo '{"reset":true}';
            exit(0);
        }
    }
    function AddFavorites()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'item_cart_id' => array('input' => isset($_POST['item']) ? $_POST['item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    $confirmed_item_id_from_database = $this->ItemModel->GetItemByItemCartId('id', $checked_inputs['item_cart_id']);
                    if ($confirmed_item_id_from_database['result']) {
                        $has_favorite_from_db = $this->ItemModel->GetFavorite(array($confirmed_item_id_from_database['data']['id'], $this->web_data['authenticated_user']));
                        if (!$has_favorite_from_db['result'] && !empty($has_favorite_from_db['empty'])) {
                            if ($this->ItemModel->CreateFavorite(array('item_id' => $confirmed_item_id_from_database['data']['id'], 'user_id' => $this->web_data['authenticated_user']))['result']) {
                                $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                                if ($result_set_csrf_token['result']) {
                                    $response['reset'] = false;
                                    $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_ADD_TO_FAVORITES);
                                } else {
                                    echo '{"shutdown":"shutdown"}';
                                    exit(0);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                            }
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_ADD_TO_FAVORITES);
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
            } else {
                parent::KillAuthentication('AccountController AddToFavorites');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function AddFavorites | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function RemoveFavorite()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'item_cart_id' => array('input' => isset($_POST['item']) ? $_POST['item'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    $confirmed_item_id_from_database = $this->ItemModel->GetItemByItemCartId('id', $checked_inputs['item_cart_id']);
                    if ($confirmed_item_id_from_database['result']) {
                        $confirmed_favorite_from_db = $this->ItemModel->GetFavorite(array($confirmed_item_id_from_database['data']['id'], $this->web_data['authenticated_user']));
                        if ($confirmed_favorite_from_db['result']) {
                            if ($this->ItemModel->UpdateFavorite(array('is_favorite_removed' => 1, 'date_favorite_removed' => date('Y-m-d H:i:s'), 'id' => $confirmed_favorite_from_db['data']['id']))['result']) {
                                $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                                if ($result_set_csrf_token['result']) {
                                    $response['reset'] = false;
                                    $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_REMOVE_FROM_FAVORITES);
                                } else {
                                    echo '{"shutdown":"shutdown"}';
                                    exit(0);
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
                parent::KillAuthentication('AccountController RemoveFavorite');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function RemoveFavorite | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function CommentCreate()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $comment = isset($_POST['comment_text']) ? strip_tags($_POST['comment_text']) : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment' => array('input' => $comment, 'error_message_empty' => TR_NOTIFICATION_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_COMMENT_LIMIT, 'preventxss' => true, 'length_limit' => COMMENT_MAX_LIMIT_DB),
                    'item_url' => array('input' => isset($_POST['item_url']) ? $_POST['item_url'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxss' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        $item_id_from_database = $this->ItemModel->GetItemIdByItemUrl($checked_inputs['item_url']);
                        if ($item_id_from_database['result']) {
                            parent::GetModel('CommentModel');
                            $comment_create_result = $this->CommentModel->CreateComment(array(
                                'user_id' => $this->web_data['authenticated_user'],
                                'item_id' => $item_id_from_database['data']['id'],
                                'comment' => $checked_inputs['comment'],
                                'is_comment_approved' => 0
                            ));
                            if ($comment_create_result['result']) {
                                $created_comment_from_database = $this->CommentModel->GetCommentById($comment_create_result['id']);
                                if ($created_comment_from_database['result']) {
                                    $created_comment_from_database['data']['comment'] = stripslashes($comment);
                                    $a = date('d/m/Y', strtotime($created_comment_from_database['data']['date_comment_created']));
                                    if (!empty($a)) {
                                        $created_comment_from_database['data']['date_comment_created'] = $a;
                                        $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                                        if ($result_set_csrf_token['result']) {
                                            $user_form_database = $this->UserModel->GetUserByUserId('first_name,last_name,profile_image_path,profile_image', $this->web_data['authenticated_user']);
                                            if ($user_form_database['result']) {
                                                $response['reset'] = false;
                                                $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                                $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_CREATE);
                                                $response['comment'] = $created_comment_from_database['data'];
                                                $response['comment_user'] = array(
                                                    'user_first_name' => $user_form_database['data']['first_name'],
                                                    'user_last_name' => $user_form_database['data']['last_name'],
                                                    'user_profile_image_path' => $user_form_database['data']['profile_image_path'],
                                                    'user_profile_image' => $user_form_database['data']['profile_image']
                                                );
                                            }
                                        } else {
                                            echo '{"shutdown":"shutdown"}';
                                            exit(0);
                                        }
                                    } else {
                                        echo '{"shutdown":"shutdown"}';
                                        exit(0);
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
                parent::KillAuthentication('AccountController CommentCreate');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function CommentCreate | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function CommentUpdate()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $comment = isset($_POST['comment_text']) ? strip_tags($_POST['comment_text']) : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'comment' => array('input' => $comment, 'error_message_empty' => TR_NOTIFICATION_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_COMMENT_LIMIT, 'preventxss' => true, 'length_limit' => COMMENT_MAX_LIMIT_DB),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        parent::GetModel('CommentModel');
                        $confirm_comment = $this->CommentModel->GetComment(array($checked_inputs['comment_id'], $this->web_data['authenticated_user']));
                        if ($confirm_comment['result']) {
                            if ($this->CommentModel->UpdateComment(array('comment' => $checked_inputs['comment'], 'id' => $confirm_comment['data']['id']))['result']) {
                                $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                                if ($result_set_csrf_token['result']) {
                                    $response['reset'] = false;
                                    $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_UPDATE);
                                    $response['comment'] = array('comment' => stripslashes($comment), 'id' => $confirm_comment['data']['id']);
                                } else {
                                    echo '{"shutdown":"shutdown"}';
                                    exit(0);
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
                parent::KillAuthentication('AccountController CommentUpdate');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function CommentCreate | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function CommentDelete()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        parent::GetModel('CommentModel');
                        $confirm_comment = $this->CommentModel->GetComment(array($checked_inputs['comment_id'], $this->web_data['authenticated_user']));
                        if ($confirm_comment['result']) {
                            $comment_has_reply = $this->CommentModel->CommentHasReply($confirm_comment['data']['id']);
                            if ($comment_has_reply['result']) {
                                foreach ($comment_has_reply['data'] as $comment_reply) {
                                    $this->CommentModel->UpdateCommentReply(array('is_comment_reply_deleted' => 1, 'date_comment_reply_deleted' => date('Y-m-d H:i:s'), 'id' => $comment_reply['id']));
                                }
                            }
                            if ($this->CommentModel->UpdateComment(array('is_comment_deleted' => 1, 'date_comment_deleted' => date('Y-m-d H:i:s'), 'id' => $confirm_comment['data']['id']))['result']) {
                                $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                                if ($result_set_csrf_token['result']) {
                                    $response['reset'] = false;
                                    $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_DELETE);
                                    $response['comment_id'] = $confirm_comment['data']['id'];
                                } else {
                                    echo '{"shutdown":"shutdown"}';
                                    exit(0);
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
                parent::KillAuthentication('AccountController CommentDelete');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function CommentCreate | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function CommentReplyCreate()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $comment_reply = isset($_POST['comment_reply_text']) ? strip_tags($_POST['comment_reply_text']) : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'comment_reply' => array('input' => $comment_reply, 'error_message_empty' => TR_NOTIFICATION_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_COMMENT_LIMIT, 'preventxss' => true, 'length_limit' => COMMENT_MAX_LIMIT_DB),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        parent::GetModel('CommentModel');
                        $comment_reply_create_result = $this->CommentModel->CreateCommentReply(array(
                            'comment_id' => $checked_inputs['comment_id'],
                            'user_id' => $this->web_data['authenticated_user'],
                            'comment_reply' => $checked_inputs['comment_reply'],
                            'is_comment_reply_approved' => 0
                        ));
                        if ($comment_reply_create_result['result']) {
                            $created_comment_from_database = $this->CommentModel->GetCommentReplyById($comment_reply_create_result['id']);
                            if ($created_comment_from_database['result']) {
                                $created_comment_from_database['data']['comment_reply'] = stripslashes($comment_reply);
                                $b = date('d/m/Y', strtotime($created_comment_from_database['data']['date_comment_reply_created']));
                                if (!empty($b)) {
                                    $created_comment_from_database['data']['date_comment_reply_created'] = $b;
                                    $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                                    if ($result_set_csrf_token['result']) {
                                        $user_form_database = $this->UserModel->GetUserByUserId('first_name,last_name,profile_image_path,profile_image', $this->web_data['authenticated_user']);
                                        if ($user_form_database['result']) {
                                            $response['reset'] = false;
                                            $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                            $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_CREATE);
                                            $response['comment_reply'] = $created_comment_from_database['data'];
                                            $response['comment_reply_user'] = array(
                                                'user_first_name' => $user_form_database['data']['first_name'],
                                                'user_last_name' => $user_form_database['data']['last_name'],
                                                'user_profile_image_path' => $user_form_database['data']['profile_image_path'],
                                                'user_profile_image' => $user_form_database['data']['profile_image']
                                            );
                                        }
                                    } else {
                                        echo '{"shutdown":"shutdown"}';
                                        exit(0);
                                    }
                                } else {
                                    echo '{"shutdown":"shutdown"}';
                                    exit(0);
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
                parent::KillAuthentication('AccountController CommentReplyCreate');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function CommentCreate | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function CommentReplyUpdate()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $comment_reply = isset($_POST['comment_reply_text']) ? strip_tags($_POST['comment_reply_text']) : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'comment_reply' => array('input' => $comment_reply, 'error_message_empty' => TR_NOTIFICATION_EMPTY_COMMENT, 'length_control' => true, 'max_length' => COMMENT_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_COMMENT_LIMIT, 'preventxss' => true, 'length_limit' => COMMENT_MAX_LIMIT_DB),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        parent::GetModel('CommentModel');
                        $confirm_comment_reply = $this->CommentModel->GetCommentReply(array($checked_inputs['comment_reply_id'], $checked_inputs['comment_id'], $this->web_data['authenticated_user']));
                        if ($confirm_comment_reply['result']) {
                            if ($this->CommentModel->UpdateCommentReply(array('comment_reply' => $checked_inputs['comment_reply'], 'id' => $confirm_comment_reply['data']['id']))['result']) {
                                $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                                if ($result_set_csrf_token['result']) {
                                    $response['reset'] = false;
                                    $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_UPDATE);
                                    $response['comment_reply'] = array('comment_reply' => stripslashes($comment_reply), 'id' => $confirm_comment_reply['data']['id'], 'comment_id' => $confirm_comment_reply['data']['comment_id']);
                                } else {
                                    echo '{"shutdown":"shutdown"}';
                                    exit(0);
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
                parent::KillAuthentication('AccountController CommentReplyUpdate');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function CommentCreate | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function CommentReplyDelete()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        parent::GetModel('CommentModel');
                        $confirm_comment_reply = $this->CommentModel->GetCommentReply(array($checked_inputs['comment_reply_id'], $checked_inputs['comment_id'], $this->web_data['authenticated_user']));
                        if ($confirm_comment_reply['result']) {
                            if ($this->CommentModel->UpdateCommentReply(array('is_comment_reply_deleted' => 1, 'date_comment_reply_deleted' => date('Y-m-d H:i:s'), 'id' => $confirm_comment_reply['data']['id']))['result']) {
                                $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                                if ($result_set_csrf_token['result']) {
                                    $response['reset'] = false;
                                    $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_DELETE);
                                    $response['comment'] = array('comment_reply_id' => $confirm_comment_reply['data']['id'], 'comment_id' => $confirm_comment_reply['data']['comment_id']);
                                } else {
                                    echo '{"shutdown":"shutdown"}';
                                    exit(0);
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
                parent::KillAuthentication('AccountController CommentReplyDelete');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function CommentCreate | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function AdminCommentDelete()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->web_data['authenticated_user'] == ADMIN_ID) {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        parent::GetModel('CommentModel');
                        $comment_has_reply = $this->CommentModel->CommentHasReply($checked_inputs['comment_id']);
                        if ($comment_has_reply['result']) {
                            foreach ($comment_has_reply['data'] as $comment_reply) {
                                $this->CommentModel->UpdateCommentReply(array('is_comment_reply_deleted' => 1, 'date_comment_reply_deleted' => date('Y-m-d H:i:s'), 'id' => $comment_reply['id']));
                            }
                        }
                        if ($this->CommentModel->UpdateComment(array('is_comment_deleted' => 1, 'date_comment_deleted' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_id']))['result']) {
                            $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                            if ($result_set_csrf_token['result']) {
                                $response['reset'] = false;
                                $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_DELETE);
                                $response['comment_id'] = $checked_inputs['comment_id'];
                            } else {
                                echo '{"shutdown":"shutdown"}';
                                exit(0);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
            } else {
                parent::KillAuthentication('AccountController AdminCommentDelete');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function AdminCommentDelete | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function AdminCommentReplyDelete()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->web_data['authenticated_user'] == ADMIN_ID) {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        parent::GetModel('CommentModel');
                        if ($this->CommentModel->UpdateCommentReply(array('is_comment_reply_deleted' => 1, 'date_comment_reply_deleted' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_reply_id']))['result']) {
                            $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                            if ($result_set_csrf_token['result']) {
                                $response['reset'] = false;
                                $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                $response['notification'] = $this->notification_control->SetNotificationForjQ('SUCCESS', TR_NOTIFICATION_SUCCESS_COMMENT_DELETE);
                                $response['comment'] = array('comment_reply_id' => $checked_inputs['comment_reply_id'], 'comment_id' => $checked_inputs['comment_id']);
                            } else {
                                echo '{"shutdown":"shutdown"}';
                                exit(0);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
            } else {
                parent::KillAuthentication('AccountController AdminCommentReplyDelete');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function AdminCommentReplyDelete | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function AdminCommentApprove()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->web_data['authenticated_user'] == ADMIN_ID) {
                $is_comment_approved = isset($_POST['is_comment_approved']) ? 0 : 1;
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment_id' => array('input' => isset($_POST['comment_id']) ? $_POST['comment_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        parent::GetModel('CommentModel');
                        if ($is_comment_approved == 1) {
                            $result_approve_comment = $this->CommentModel->UpdateComment(array('is_comment_approved' => 1, 'date_comment_approved' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_id']))['result'];
                        } else {
                            $result_approve_comment = $this->CommentModel->UpdateComment(array('is_comment_approved' => 0, 'date_comment_approved' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_id']))['result'];
                        }
                        if ($result_approve_comment) {
                            $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                            if ($result_set_csrf_token['result']) {
                                $response['reset'] = false;
                                $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                if ($is_comment_approved == 1) {
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('WARNING', TR_NOTIFICATION_SUCCESS_COMMENT_APPROVED);
                                    $response['is_approved'] = 1;
                                } else {
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('WARNING', TR_NOTIFICATION_SUCCESS_COMMENT_DISAPPROVED);
                                    $response['is_approved'] = 0;
                                }
                            } else {
                                echo '{"shutdown":"shutdown"}';
                                exit(0);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
            } else {
                parent::KillAuthentication('AccountController AdminCommentApprove');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function AdminCommentApprove | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
    function AdminCommentReplyApprove()
    {
        try {
            $response = array();
            $response['reset'] = true;
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->web_data['authenticated_user'] == ADMIN_ID) {
                $is_comment_reply_approved = isset($_POST['is_comment_reply_approved']) ? 0 : 1;
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'comment_reply_id' => array('input' => isset($_POST['comment_reply_id']) ? $_POST['comment_reply_id'] : '', 'error_message_empty' => TR_NOTIFICATION_EMPTY_HIDDEN_INPUT, 'preventxssforid' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ItemDetails')) {
                        parent::GetModel('CommentModel');
                        if ($is_comment_reply_approved == 1) {
                            $result_approve_comment_reply = $this->CommentModel->UpdateCommentReply(array('is_comment_reply_approved' => 1, 'date_comment_reply_approved' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_reply_id']))['result'];
                        } else {
                            $result_approve_comment_reply = $this->CommentModel->UpdateCommentReply(array('is_comment_reply_approved' => 0, 'date_comment_reply_approved' => date('Y-m-d H:i:s'), 'id' => $checked_inputs['comment_reply_id']))['result'];
                        }
                        if ($result_approve_comment_reply) {
                            $result_set_csrf_token = parent::SetCSRFTokenjQ('ItemDetails');
                            if ($result_set_csrf_token['result']) {
                                $response['reset'] = false;
                                $response['form_token'] = $result_set_csrf_token['csrf_token'];
                                if ($is_comment_reply_approved == 1) {
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('WARNING', TR_NOTIFICATION_SUCCESS_COMMENT_APPROVED);
                                    $response['is_comment_reply_approved'] = 1;
                                } else {
                                    $response['notification'] = $this->notification_control->SetNotificationForjQ('WARNING', TR_NOTIFICATION_SUCCESS_COMMENT_DISAPPROVED);
                                    $response['is_comment_reply_approved'] = 0;
                                }
                            } else {
                                echo '{"shutdown":"shutdown"}';
                                exit(0);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
            } else {
                parent::KillAuthentication('AccountController AdminCommentReplyApprove');
            }
            $jsoned_response = json_encode($response);
            if (!empty($jsoned_response)) {
                echo $jsoned_response;
                exit(0);
            }
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class AccountController function AdminCommentReplyApprove | ' . $th))['result']) {
                echo '{"exception":"exception"}';
                exit(0);
            } else {
                echo '{"shutdown":"shutdown"}';
                exit(0);
            }
        }
    }
}
