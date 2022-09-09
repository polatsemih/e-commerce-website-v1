<?php
class ActionController extends Controller
{
    function __construct()
    {
        parent::__construct();
        // user_block
        // if (!empty($_SESSION[SESSION_AUTHENTICATION_NAME]) && !empty($_COOKIE[COOKIE_AUTHENTICATION_NAME])) {
        //     if ($this->session_control->KillSession() && $this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME)) {
        //         $this->input_control->Redirect(URL_LOGIN);
        //     } else {
        //         parent::GetView('Error/NotResponse');
        //     }
        // }
        // if (!empty($_SESSION[SESSION_AUTHENTICATION_NAME])) {
        //     $checked_session_authentication_token = $this->input_control->CheckInputWithLength($_SESSION[SESSION_AUTHENTICATION_NAME], 255);
        //     if (!is_null($checked_session_authentication_token)) {
        //         $session_authentication_from_database = $this->ActionModel->GetSessionAuthentication(array($_SERVER['REMOTE_ADDR'], $checked_session_authentication_token));
        //         if (!empty($session_authentication_from_database) && $session_authentication_from_database['date_session_authentication_expiry'] > date('Y-m-d H:i:s') && $session_authentication_from_database['is_session_authentication_logout'] == 0) {
        //             $authenticated_user_from_database = $this->UserModel->GetUserByUserIdByUserId('id', $session_authentication_from_database['user_id']);
        //             if (!empty($authenticated_user_from_database)) {
        //                 $this->input_control->Redirect();
        //             }
        //         }
        //     }
        //     if ($this->session_control->KillSession()) {
        //         $this->input_control->Redirect(URL_LOGIN);
        //     } else {
        //         parent::GetView('Error/NotResponse');
        //     }
        // } elseif (!empty($_COOKIE[COOKIE_AUTHENTICATION_NAME])) {
        //     $checked_cookie_authentication = $this->input_control->CheckInputWithLength($_COOKIE[COOKIE_AUTHENTICATION_NAME], 500);
        //     if (!is_null($checked_cookie_authentication)) {
        //         $cookie_authentication_from_database = $this->ActionModel->GetCookieAuthentication(array($_SERVER['REMOTE_ADDR'], substr($checked_cookie_authentication, 0, 247)));
        //         if (!empty($cookie_authentication_from_database) && $cookie_authentication_from_database['date_cookie_authentication_expiry'] > date('Y-m-d H:i:s') && $cookie_authentication_from_database['is_cookie_authentication_logout'] == 0) {
        //             try {
        //                 $cookie_authentication_token1 = hash_hmac('SHA512', substr($checked_cookie_authentication, 247, 253), $cookie_authentication_from_database['cookie_authentication_salt'], false);
        //                 if (hash_equals($cookie_authentication_from_database['cookie_authentication_token1'], $cookie_authentication_token1)) {
        //                     $authenticated_user_from_database = $this->UserModel->GetUserByUserIdByUserId('id', $cookie_authentication_from_database['user_id']);
        //                     if (!empty($authenticated_user_from_database)) {
        //                         $this->input_control->Redirect();
        //                     }
        //                 }
        //             } catch (\Throwable $th) {
        //                 // mail
        //             }
        //         }
        //     }
        //     if ($this->cookie_control->EmptyCookie(COOKIE_AUTHENTICATION_NAME)) {
        //         $this->input_control->Redirect(URL_LOGIN);
        //     } else {
        //         parent::GetView('Error/NotResponse');
        //     }
        // }

        $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
    }
    function TwoFASendMail($user_id, $user_email, $remember_me)
    {
        // $generated_session_confirm_token = $this->action_control->GenerateSessionConfirmToken();
        // $generated_confirm_token_bytes = $this->action_control->GenerateConfirmTokenBytes();
        // if ($this->ActionModel->CreateSessionTwoFA(array('user_id' => $user_id, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'two_fa_token' => $generated_session_confirm_token, 'confirm_hashed_tokens' => $this->action_control->HashedConfirmTokenBytes($generated_confirm_token_bytes[4] . $generated_confirm_token_bytes[7] . $generated_confirm_token_bytes[0] . $generated_confirm_token_bytes[1] . $generated_confirm_token_bytes[6] . $generated_confirm_token_bytes[3] . $generated_confirm_token_bytes[2] . $generated_confirm_token_bytes[5]), 'date_two_fa_token_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CONFIRM_EMAIL_TOKEN)), 'is_two_fa_token_used' => 0)) == 'Created') {
        //     if ($this->action_control->SendMail($user_email, BRAND . ' İki Aşamalı Doğrulama', '') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $user_id, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'TwoFA')) == 'Created') {
        //         $_SESSION[SESSION_TWO_FA_NAME] = $generated_session_confirm_token;
        //         $this->input_control->Redirect(URL_TWO_FA);
        //     }
        // }
        // $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_TWO_FA);
        // $this->input_control->Redirect(URL_LOGIN);
    }
    function TwoFA()
    {
        try {
            // if (!empty($_SESSION[SESSION_TWO_FA_NAME])) {
            //     $two_fa_token_from_database = $this->ActionModel->GetSessionTwoFA(array($_SESSION[SESSION_TWO_FA_NAME], $_SERVER['REMOTE_ADDR']));
            //     if (!empty($two_fa_token_from_database) && $two_fa_token_from_database['date_two_fa_token_expiry'] > date('Y-m-d H:i:s') && $two_fa_token_from_database['is_two_fa_token_used'] == 0) {
            //         if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //             $this->web_data['confirm_token'] = $this->action_control->GenerateBaitToken();
            //             $this->web_data['expiry_remain_minute'] = (int)((strtotime($confirm_token_from_database['date_two_fa_token_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60);
            //             $this->web_data['expiry_remain_second'] = (strtotime($confirm_token_from_database['date_two_fa_token_expiry']) - strtotime(date('Y-m-d H:i:s'))) % 60;
            //             $this->web_data['form_token'] = parent::SetCSRFToken('TwoFA');
            //             parent::GetView('Action/TwoFA', $this->web_data);
            //         } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //             $this->session_control->KillSession(SESSION_TWO_FA_NAME);
            //             $checked_inputs = $this->input_control->CheckPostedInputs(array(
            //                 'token_1' => array('input' => isset($_POST['token_1']) ? $_POST['token_1'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
            //                 'token_2' => array('input' => isset($_POST['token_2']) ? $_POST['token_2'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
            //                 'token_3' => array('input' => isset($_POST['token_3']) ? $_POST['token_3'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
            //                 'token_4' => array('input' => isset($_POST['token_4']) ? $_POST['token_4'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
            //                 'token_5' => array('input' => isset($_POST['token_5']) ? $_POST['token_5'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
            //                 'token_6' => array('input' => isset($_POST['token_6']) ? $_POST['token_6'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
            //                 'token_7' => array('input' => isset($_POST['token_7']) ? $_POST['token_7'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
            //                 'token_8' => array('input' => isset($_POST['token_8']) ? $_POST['token_8'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
            //                 'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
            //             ));
            //             if (empty($checked_inputs['error_message'])) {
            //                 if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'TwoFA') == true) {
            //                     if ($this->ActionModel->UpdateSessionTwoFA(array('is_two_fa_token_used' => 1, 'date_two_fa_token_used' => date('Y-m-d H:i:s'), 'id' => $two_fa_token_from_database['id'])) == 'Updated') {
            //                         if ($two_fa_token_from_database['two_fa_hashed_tokens'] == $this->action_control->HashedConfirmTokenBytes($checked_inputs['token_2'] . $checked_inputs['token_4'] . $checked_inputs['token_3'] . $checked_inputs['token_5'] . $checked_inputs['token_7'] . $checked_inputs['token_6'] . $checked_inputs['token_1'] . $checked_inputs['token_8'])) {
            //                             $two_fa_user_from_database = $this->UserModel->GetUserByUserIdByUserId('id', $two_fa_token_from_database['user_id']);
            //                             if (!empty($two_fa_user_from_database) && $this->UserModel->UpdateUser(array() == 'Updated')) {
            //                                 if ($remember_me == 1) {

            //                                 } else {
                                                
            //                                 }
            //                             } else {
            //                                 $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_TWO_FA);
            //                             }
            //                         } else {
            //                             $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN);
            //                         }
            //                     } else {
            //                         $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_TWO_FA);
            //                     }
            //                 }
            //             } else {
            //                 $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            //             }
            //             $this->input_control->Redirect(URL_LOGIN);
            //         }
            //     } else {
            //         $this->session_control->KillSession(SESSION_TWO_FA_NAME);
            //         $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_TWO_FA_TOKEN_EXPIRIED);
            //         $this->input_control->Redirect(URL_LOGIN);
            //     }
            // }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function TwoFA | ' . $th));
            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Error Occurred', 'Exception Error Occurred');
            $this->input_control->Redirect(URL_EXCEPTION);
        }
    }
    function Login()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl(array('yonlendir'));
                parent::LogView('Action/Login');
                if (!empty($_GET['yonlendir'])) {
                    $checked_redirect_location = $this->input_control->CheckGETInput($_GET['yonlendir']);
                    if (!empty($checked_redirect_location)) {
                        $this->web_data['redirect_location'] = $checked_redirect_location;
                    }
                } elseif (!empty($_SESSION[SESSION_WEB_DATA])) {
                    if (!empty($_SESSION[SESSION_WEB_DATA]['email']) && !empty($_SESSION[SESSION_WEB_DATA]['password'])) {
                        $this->web_data['email'] = $_SESSION[SESSION_WEB_DATA]['email'];
                        $this->web_data['password'] = $_SESSION[SESSION_WEB_DATA]['password'];
                        if (!empty($_SESSION[SESSION_WEB_DATA]['redirect_location'])) {
                            $this->web_data['redirect_location'] = $_SESSION[SESSION_WEB_DATA]['redirect_location'];
                        }
                    }
                    $this->session_control->KillSession(SESSION_WEB_DATA);
                }
                $this->web_data['form_token'] = parent::SetCSRFToken('Login');
                parent::GetView('Action/Login', $this->web_data);
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = isset($_POST['email']) ? $_POST['email'] : '';
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'email' => array('input' => $email, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL, 'preventxss' => true),
                    'password' => array('input' => $password, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD, 'preventxss' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Login') == true) {
                        $captcha_timeout_from_database = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
                        $captcha_timeout_not_error = false;
                        if (!empty($captcha_timeout_from_database)) {
                            if ($captcha_timeout_from_database['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_1 . ceil((int)((strtotime($captcha_timeout_from_database['date_captcha_timeout_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60)) . TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_2);
                                $this->input_control->Redirect(URL_LOGIN . (!empty($_POST['redirect_location']) ? '?yonlendir=' . $_POST['redirect_location'] : ''));
                            }
                            $captcha_timeout_not_error = true;
                        } else if ($this->ActionModel->CreateCaptchaTimeOut(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'captcha_error_count' => 0, 'captcha_total_error_count' => 0)) == 'Created') {
                            $captcha_timeout_not_error = true;
                        }
                        if (!empty($_POST['h-captcha-response'])) {
                            $captcha_check_result = $this->action_control->CheckCaptcha($_POST['h-captcha-response']);
                            if ($captcha_check_result['result'] == true) {
                                if ($this->ActionModel->CreateLogCaptcha(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'success' => $captcha_check_result['success'], 'credit' => $captcha_check_result['credit'])) == 'Created' && $captcha_timeout_not_error) {
                                    if (!empty($captcha_timeout_from_database) && $captcha_timeout_from_database['captcha_error_count'] != 0) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => 0, 'id' => $captcha_timeout_from_database['id']));
                                    }
                                    $login_user_from_database = $this->UserModel->GetUserByUserIdByUserIdByEmail('id,email_confirmed,password,password_salt,two_fa_enable,user_role,fail_access_count', $checked_inputs['email']);
                                    if (!empty($login_user_from_database)) {
                                        $salted_password = hash_hmac('sha512', $login_user_from_database['password_salt'] . str_replace("\0", "", $checked_inputs['password']), PASSWORD_SECRET_KEY, true);
                                        $decrypted_hashed_password = $this->input_control->DecrypteData($login_user_from_database['password'], PASSWORD_PEPPER);
                                        if (!is_null($decrypted_hashed_password)) {
                                            $verified_pwd = password_verify($salted_password, $decrypted_hashed_password);
                                        } else {
                                            $verified_pwd = false;
                                        }
                                        if ($verified_pwd) {
                                            $bcrypt_options = $this->password_control->BcryptOptions();
                                            if (password_needs_rehash($decrypted_hashed_password, PASSWORD_BCRYPT, $bcrypt_options)) {
                                                $new_hashed_password = password_hash($salted_password, PASSWORD_BCRYPT, $bcrypt_options);
                                                $encrypted_hashed_password = $this->input_control->EncrypteData($new_hashed_password, PASSWORD_PEPPER);
                                                $this->UserModel->UpdateUser(array('password' => $encrypted_hashed_password, 'id' => $login_user_from_database['id']));
                                            }
                                            if ($login_user_from_database['user_role'] == ADMIN_ROLE_ID) {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                $this->input_control->Redirect(URL_LOGIN);
                                            } elseif ($login_user_from_database['email_confirmed'] == 1) {
                                                if (!empty($_POST['remember_me'])) {
                                                    $remember_me = 1;
                                                } else {
                                                    $remember_me = 2;
                                                }
                                                if ($login_user_from_database['two_fa_enable'] == 1) {
                                                    // $this->TwoFASendMail($login_user_from_database['id'], $this->input_control->DecodePreventXSS($checked_inputs['email']),  $remember_me);
                                                } else {
                                                    if ($remember_me == 1) {
                                                        $cookie_authentication_token = $this->cookie_control->GenerateCookieAuthenticationToken();
                                                        $cookie_authentication_salt = $this->cookie_control->GenerateCookieAuthenticationSalt();
                                                        $cookie_authentication_token1 = hash_hmac('SHA512', substr($cookie_authentication_token, 247, 253), $cookie_authentication_salt, false);
                                                        $cookie_authentication_token2 = substr($cookie_authentication_token, 0, 247);
                                                        if ($this->ActionModel->CreateCookieAuthentication(array('user_id' => $login_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'cookie_authentication_token1' => $cookie_authentication_token1, 'cookie_authentication_token2' => $cookie_authentication_token2, 'cookie_authentication_salt' => $cookie_authentication_salt, 'date_cookie_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_COOKIE_AUTHENTICATION)))) == 'Created' && $this->UserModel->UpdateUser(array('date_last_login' => date('Y-m-d H:i:s'), 'id' => $login_user_from_database['id'])) == 'Updated' && $this->ActionModel->CreateLogLogin(array('user_id' => $login_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 1)) == 'Created') {
                                                            if ($this->cookie_control->SetCookie(COOKIE_AUTHENTICATION_NAME, $cookie_authentication_token, time() + (EXPIRY_COOKIE_AUTHENTICATION), COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTP_ONLY, COOKIE_SAMESITE)) {
                                                                if (isset($_POST['redirect_location'])) {
                                                                    $posted_redirect = explode('/', $_POST['redirect_location']);
                                                                    if (!empty($posted_redirect) && count($posted_redirect) == 2 && !empty($posted_redirect[0]) && !empty($posted_redirect[1])) {
                                                                        $redirect_perm = in_array($posted_redirect[0], REDIRECT_PERMISSION);
                                                                        if ($redirect_perm) {
                                                                            $redirect_login = $this->input_control->CheckGETInput($posted_redirect[1]);
                                                                            if (!is_null($redirect_login)) {
                                                                                $this->input_control->Redirect($posted_redirect[0] . '/' . $posted_redirect[1]);
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                $this->input_control->Redirect();
                                                            }
                                                        }
                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                        $this->input_control->Redirect(URL_LOGIN);
                                                    } else {
                                                        $session_authentication_token = $this->session_control->GenerateSessionAuthenticationToken();
                                                        if ($this->ActionModel->CreateSessionAuthentication(array('user_id' => $login_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'session_authentication_token' => $session_authentication_token, 'date_session_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SESSION_AUTHENTICATION)))) == 'Created' && $this->ActionModel->UpdateUser(array('date_last_login' => date('Y-m-d H:i:s'), 'id' => $login_user_from_database['id'])) == 'Updated' && $this->ActionModel->CreateLogLogin(array('user_id' => $login_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 1)) == 'Created') {
                                                            $_SESSION[SESSION_AUTHENTICATION_NAME] = $session_authentication_token;
                                                            if (isset($_POST['redirect_location'])) {
                                                                $posted_redirect = explode('/', $_POST['redirect_location']);
                                                                if (!empty($posted_redirect) && count($posted_redirect) == 2 && !empty($posted_redirect[0]) && !empty($posted_redirect[1])) {
                                                                    $redirect_perm = in_array($posted_redirect[0], REDIRECT_PERMISSION);
                                                                    if ($redirect_perm) {
                                                                        $redirect_login = $this->input_control->CheckGETInput($posted_redirect[1]);
                                                                        if (!is_null($redirect_login)) {
                                                                            $this->input_control->Redirect($posted_redirect[0] . '/' . $posted_redirect[1]);
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            $this->input_control->Redirect();
                                                        } else {
                                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                            $this->input_control->Redirect(URL_LOGIN);
                                                        }
                                                    }
                                                }
                                                $this->input_control->Redirect(URL_LOGIN);
                                            } else {
                                                // $this->RegisterConfirmSendEmail($login_user_from_database['id'], $this->input_control->DecodePreventXSS($checked_inputs['email']));
                                            }
                                        } else {
                                            $this->ActionModel->CreateLogLogin(array('user_id' => $login_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 0));
                                            $this->UserModel->UpdateUser(array('fail_access_count' => $login_user_from_database['fail_access_count'] + 1, 'date_last_fail_access_attempt' => date('Y-m-d H:i:s'), 'id' => $login_user_from_database['id']));
                                        }
                                    }
                                    $log_login_fail_from_db = $this->ActionModel->GetLogLoginFail($_SERVER['REMOTE_ADDR']);
                                    if (!empty($log_login_fail_from_db)) {
                                        if ($log_login_fail_from_db['ip_login_fail_count'] >= 100) {
                                            $this->notification_control->SetNotification('DANGER', COUNT_FAIL_LOGIN_ERROR);
                                            $this->input_control->Redirect(URL_LOGIN);
                                        } else {
                                            $this->ActionModel->UpdateLogLoginFail(array('ip_login_fail_count' => $log_login_fail_from_db['ip_login_fail_count'] + 1, 'id' => $log_login_fail_from_db['id']));
                                        }
                                    } else {
                                        $this->ActionModel->CreateLogLoginFail(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'ip_login_fail_count' => 1));
                                    }
                                    $this->notification_control->SetNotification('DANGER', LOGIN_ERROR);
                                    $this->input_control->Redirect(URL_LOGIN . (isset($_POST['redirect_location']) ? '?yonlendir=' . $_POST['redirect_location'] : ''));
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                }
                            } else {
                                if (!empty($captcha_timeout_from_database)) {
                                    if ($captcha_timeout_from_database['captcha_error_count'] == 0) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'id' => $captcha_timeout_from_database['id']));
                                    } elseif ($captcha_timeout_from_database['captcha_error_count'] < 4) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SHORT_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['id']));
                                    } elseif ($captcha_timeout_from_database['captcha_error_count'] >= 4) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_LONG_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['id']));
                                    }
                                }
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                if (!empty($_POST['redirect_location'])) {
                    $_SESSION[SESSION_WEB_DATA] = array('email' => $email, 'password' => $password, 'redirect_location' => $_POST['redirect_location']);
                } else {
                    $_SESSION[SESSION_WEB_DATA] = array('email' => $email, 'password' => $password);
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function Login | ' . $th));
            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Error Occurred', 'Exception Error Occurred');
            $this->input_control->Redirect(URL_EXCEPTION);
        }        
    }
    function RegisterConfirmSendEmail($user_id, $user_email)
    {
        $generated_session_confirm_token = $this->action_control->GenerateSessionConfirmToken();
        $generated_confirm_token_bytes = $this->action_control->GenerateConfirmTokenBytes();
        $register_confirm_no_error = false;
        if ($this->ActionModel->CreateSessionRegisterConfirm(array('user_id' => $user_id, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'register_confirm_token' => $generated_session_confirm_token, 'register_hashed_confirm_tokens' => $this->action_control->HashedConfirmTokenBytes($generated_confirm_token_bytes[4] . $generated_confirm_token_bytes[7] . $generated_confirm_token_bytes[0] . $generated_confirm_token_bytes[1] . $generated_confirm_token_bytes[6] . $generated_confirm_token_bytes[3] . $generated_confirm_token_bytes[2] . $generated_confirm_token_bytes[5]), 'date_register_confirm_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CONFIRM_EMAIL_TOKEN)), 'is_register_confirm_used' => 0)) == 'Created') {
            $link_register_cancel_from_database = $this->ActionModel->GetLinkRegisterCancelByUserId($user_id);
            if (!empty($link_register_cancel_from_database['link_register_cancel']) && $this->action_control->SendMail($user_email, BRAND . ' Üyelik Aktifleştirme', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Üyelik Aktifleştirme | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.confirm-container {width: 100%;margin-left: auto;margin-right: auto;padding-top: 20px;padding-bottom: 20px;}.confirm {display: inline-block;font-size: 20px;text-align: center;background-color: #ffffff;color: #000000;width: 10%;padding-top: 10px;padding-bottom: 10px;margin-right: 1%;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-text-url {color: #6466ec !important;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm-container {width: 70%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Hesabınızı Doğrulayın</p></div><div class="main"><div class="confirm-container"><span class="confirm">' . $generated_confirm_token_bytes[2] . '</span><span class="confirm">' . $generated_confirm_token_bytes[4] . '</span><span class="confirm">' . $generated_confirm_token_bytes[0] . '</span><span class="confirm">' . $generated_confirm_token_bytes[7] . '</span><span class="confirm">' . $generated_confirm_token_bytes[1] . '</span><span class="confirm">' . $generated_confirm_token_bytes[3] . '</span><span class="confirm">' . $generated_confirm_token_bytes[6] . '</span><span class="confirm">' . $generated_confirm_token_bytes[5] . '</span></div><p class="text-2">Üyeliğinizi aktif etmek için üstteki kodu girin</p><p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, üyelik işlemini iptal etmek için <a class="footer-text-url" href="' . URL . URL_REGISTER_CANCEL . '?cr=' . $link_register_cancel_from_database['link_register_cancel'] . '">tıklayınız.</a></p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $user_id, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'RegisterConfirm')) == 'Created') {
                $register_confirm_no_error = true;
            } else {
                $cancel_register_link = $this->action_control->GenerateRegisterCancelLink();
                if ($this->ActionModel->CreateLinkRegisterCancel(array('user_id' => $user_id, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'link_register_cancel' => $cancel_register_link, 'date_link_register_cancel_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CANCEL_REGISTER_LINK)), 'is_link_register_cancel_used' => 0)) == 'Created' && $this->action_control->SendMail($user_email, BRAND . ' Üyelik Aktifleştirme', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Üyelik Aktifleştirme | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.confirm-container {width: 100%;margin-left: auto;margin-right: auto;padding-top: 20px;padding-bottom: 20px;}.confirm {display: inline-block;font-size: 20px;text-align: center;background-color: #ffffff;color: #000000;width: 10%;padding-top: 10px;padding-bottom: 10px;margin-right: 1%;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-text-url {color: #6466ec !important;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm-container {width: 70%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Hesabınızı Doğrulayın</p></div><div class="main"><div class="confirm-container"><span class="confirm">' . $generated_confirm_token_bytes[2] . '</span><span class="confirm">' . $generated_confirm_token_bytes[4] . '</span><span class="confirm">' . $generated_confirm_token_bytes[0] . '</span><span class="confirm">' . $generated_confirm_token_bytes[7] . '</span><span class="confirm">' . $generated_confirm_token_bytes[1] . '</span><span class="confirm">' . $generated_confirm_token_bytes[3] . '</span><span class="confirm">' . $generated_confirm_token_bytes[6] . '</span><span class="confirm">' . $generated_confirm_token_bytes[5] . '</span></div><p class="text-2">Üyeliğinizi aktif etmek için üstteki kodu girin</p><p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, üyelik işlemini iptal etmek için <a class="footer-text-url" href="' . URL . URL_REGISTER_CANCEL . '?cr=' . $cancel_register_link . '">tıklayınız.</a></p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $user_id, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'RegisterConfirm')) == 'Created') {
                    $register_confirm_no_error = true;
                }
            }
        }
        if ($register_confirm_no_error) {
            $_SESSION[SESSION_REGISTER_CONFIRM_NAME] = $generated_session_confirm_token;
            $this->input_control->Redirect(URL_REGISTER_CONFIRM);
        }
        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REGISTER_CONFIRM);
        $this->input_control->Redirect(URL_REGISTER);
    }
    function RegisterConfirm()
    {
        try {
            if (!empty($_SESSION[SESSION_REGISTER_CONFIRM_NAME])) {
                $confirm_token_from_database = $this->ActionModel->GetSessionRegisterConfirm(array($_SERVER['REMOTE_ADDR'], $_SESSION[SESSION_REGISTER_CONFIRM_NAME]));
                if (!empty($confirm_token_from_database) && $confirm_token_from_database['date_confirm_token_expiry'] > date('Y-m-d H:i:s') && $confirm_token_from_database['is_register_confirm_used'] == 0) {
                    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        $this->web_data['confirm_token'] = $this->action_control->GenerateBaitToken();
                        $this->web_data['expiry_remain_minute'] = (int)((strtotime($confirm_token_from_database['date_confirm_token_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60);
                        $this->web_data['expiry_remain_second'] = (strtotime($confirm_token_from_database['date_confirm_token_expiry']) - strtotime(date('Y-m-d H:i:s'))) % 60;
                        $this->web_data['form_token'] = parent::SetCSRFToken('RegisterConfirm');
                        parent::GetView('Action/RegisterConfirm', $this->web_data);
                    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $this->session_control->KillSession(SESSION_REGISTER_CONFIRM_NAME);
                        $checked_inputs = $this->input_control->CheckPostedInputs(array(
                            'token_1' => array('input' => isset($_POST['token_1']) ? $_POST['token_1'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_2' => array('input' => isset($_POST['token_2']) ? $_POST['token_2'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_3' => array('input' => isset($_POST['token_3']) ? $_POST['token_3'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_4' => array('input' => isset($_POST['token_4']) ? $_POST['token_4'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_5' => array('input' => isset($_POST['token_5']) ? $_POST['token_5'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_6' => array('input' => isset($_POST['token_6']) ? $_POST['token_6'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_7' => array('input' => isset($_POST['token_7']) ? $_POST['token_7'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_8' => array('input' => isset($_POST['token_8']) ? $_POST['token_8'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxss' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                        ));
                        if (empty($checked_inputs['error_message'])) {
                            if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'RegisterConfirm') == true) {
                                if ($this->ActionModel->UpdateSessionRegisterConfirm(array('is_register_confirm_used' => 1, 'date_register_confirm_used' => date('Y-m-d H:i:s'), 'id' => $confirm_token_from_database['id'])) == 'Updated') {
                                    if ($confirm_token_from_database['register_hashed_confirm_tokens'] == $this->action_control->HashedConfirmTokenBytes($checked_inputs['token_2'] . $checked_inputs['token_4'] . $checked_inputs['token_3'] . $checked_inputs['token_5'] . $checked_inputs['token_7'] . $checked_inputs['token_6'] . $checked_inputs['token_1'] . $checked_inputs['token_8'])) {
                                        $register_confirm_user_from_database = $this->UserModel->GetUserByUserIdByUserId('id', $confirm_token_from_database['user_id']);
                                        if (!empty($register_confirm_user_from_database) && $this->UserModel->UpdateUser(array('email_confirmed' => 1, 'id' => $register_confirm_user_from_database['id'])) == 'Updated') {
                                            $_SESSION[SESSION_LOGIN_MESSAGE_NAME] = TR_NOTIFICATION_SUCCESS_REGISTER_CONFIRM;
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REGISTER_CONFIRM);
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REGISTER_CONFIRM);
                                }
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                        }
                        $this->input_control->Redirect(URL_LOGIN);
                    }
                } else {
                    $this->session_control->KillSession(SESSION_REGISTER_CONFIRM_NAME);
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REGISTER_CONFIRM_TOKEN_EXPIRIED);
                    $this->input_control->Redirect(URL_REGISTER);
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            $$this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function RegisterConfirm | ' . $th));
            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Error Occurred', 'Exception Error Occurred');
            $this->input_control->Redirect(URL_EXCEPTION);
        }
    }
    function RegisterCancel()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['cr'])) {
                $register_cancel_token = $this->input_control->CheckGETInput($_GET['cr']);
                if (!empty($register_cancel_token)) {
                    $register_cancel_from_database = $this->ActionModel->GetLinkRegisterCancelByLink($register_cancel_token);
                    if (!empty($register_cancel_from_database) && $register_cancel_from_database['date_link_register_cancel_expiry'] > date('Y-m-d H:i:s')) {
                        $register_cancel_user_from_database = $this->UserModel->GetUserByUserIdByUserId('id,email_confirmed', $register_cancel_from_database['user_id']);
                        if (!empty($register_cancel_user_from_database)) {
                            if ($this->ActionModel->UpdateLinkRegisterCancel(array('is_link_register_cancel_used' => 1, 'date_link_register_cancel_used' => date('Y-m-d H:i:s'), 'ip_link_register_cancel_used' => $_SERVER['REMOTE_ADDR'], 'id' => $register_cancel_from_database['id'])) == 'Updated') {
                                if ($register_cancel_user_from_database['email_confirmed'] == 1) {
                                    $this->input_control->Redirect();
                                } else {
                                    if ($this->UserModel->UpdateUser(array('is_user_deleted' => 1, 'date_user_deleted' => date('Y-m-d H:i:s'), 'register_cancel' => 1, 'id' => $register_cancel_user_from_database['id'])) == 'Updated') {
                                        $this->web_data['register_cancel_message'] =  TR_NOTIFICATION_SUCCESS_REGISTER_CANCEL;
                                    } else {
                                        $this->web_data['register_cancel_message'] = TR_NOTIFICATION_ERROR_REGISTER_CANCEL;
                                    }
                                }
                            } else {
                                $this->web_data['register_cancel_message'] = TR_NOTIFICATION_ERROR_REGISTER_CANCEL;
                            }
                        } else {
                            $this->input_control->Redirect();
                        }
                        parent::GetView('Action/RegisterCancel', $this->web_data);
                    }
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function RegisterCancel | ' . $th));
            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Error Occurred', 'Exception Error Occurred');
            $this->input_control->Redirect(URL_EXCEPTION);
        }
    }
    function Register()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::LogView('Action/Register');
                if (!empty($_SESSION[SESSION_WEB_DATA])) {
                    if (!empty($_SESSION[SESSION_WEB_DATA]['email']) && !empty($_SESSION[SESSION_WEB_DATA]['password']) && !empty($_SESSION[SESSION_WEB_DATA]['repassword'])) {
                        $this->web_data['email'] = $_SESSION[SESSION_WEB_DATA]['email'];
                        $this->web_data['password'] = $_SESSION[SESSION_WEB_DATA]['password'];
                        $this->web_data['repassword'] = $_SESSION[SESSION_WEB_DATA]['repassword'];
                    }
                    $this->session_control->KillSession(SESSION_WEB_DATA);
                }
                $this->web_data['form_token'] = parent::SetCSRFToken('Register');
                parent::GetView('Action/Register', $this->web_data);
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = isset($_POST['email']) ? $_POST['email'] : '';
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'email' => array('input' => $email, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'is_email' => true, 'error_message_is_email' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'length_control' => true, 'max_length' => EMAIL_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'preventxss' => true, 'length_limit' => EMAIL_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL),
                    'password' => array('input' => $password, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'repassword' => array('input' => $repassword, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true),
                    'accept_terms' => array('input' => isset($_POST['accept_terms']) ? 'true' : 'false', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_TERMS)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Register') == true) {
                        if ($checked_inputs['accept_terms'] == 'true') {
                            $password_salt = strtr(sodium_bin2base64(random_bytes(75), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => '2', '_' => '4'));
                            $salted_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['password']), PASSWORD_SECRET_KEY, true);
                            $salted_re_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['repassword']), PASSWORD_SECRET_KEY, true);
                            $hashed_password = password_hash($salted_password, PASSWORD_BCRYPT, $this->password_control->BcryptOptions());
                            $verified_pwd = password_verify($salted_re_password, $hashed_password);
                            if ($verified_pwd) {
                                $captcha_timeout_from_database = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
                                $captcha_timeout_not_error = false;
                                if (!empty($captcha_timeout_from_database)) {
                                    if ($captcha_timeout_from_database['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_1 . ceil((int)((strtotime($captcha_timeout_from_database['date_captcha_timeout_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60)) . TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_2);
                                        $this->input_control->Redirect(URL_REGISTER);
                                    }
                                    $captcha_timeout_not_error = true;
                                } else if ($this->ActionModel->CreateCaptchaTimeOut(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'captcha_error_count' => 0, 'captcha_total_error_count' => 0)) == 'Created') {
                                    $captcha_timeout_not_error = true;
                                }
                                if (!empty($_POST['h-captcha-response'])) {
                                    $captcha_check_result = $this->action_control->CheckCaptcha($_POST['h-captcha-response']);
                                    if ($captcha_check_result['result'] == true) {
                                        if ($this->ActionModel->CreateLogCaptcha(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'success' => $captcha_check_result['success'], 'credit' => $captcha_check_result['credit'])) == 'Created' && $captcha_timeout_not_error) {
                                            if (!empty($captcha_timeout_from_database) && $captcha_timeout_from_database['captcha_error_count'] != 0) {
                                                $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => 0, 'id' => $captcha_timeout_from_database['id']));
                                            }
                                            $is_email_unique = $this->UserModel->IsUserEmailUnique($checked_inputs['email']);
                                            if (!empty($is_email_unique) && count($is_email_unique) > 0) {
                                                $_SESSION[SESSION_LOGIN_MESSAGE_NAME] = TR_NOTIFICATION_ERROR_NOT_UNIQUE_EMAIL;
                                                $this->input_control->Redirect(URL_LOGIN);
                                            }
                                            $result_create_user = $this->UserModel->CreateUser(array('email' => $checked_inputs['email'], 'password' => $this->input_control->EncrypteData($hashed_password, PASSWORD_PEPPER), 'password_salt' => $password_salt));
                                            if ($result_create_user['result'] == 'Created') {
                                                $register_session_confirm_token = $this->action_control->GenerateSessionConfirmToken();
                                                $register_confirm_token_bytes = $this->action_control->GenerateConfirmTokenBytes();
                                                if ($this->ActionModel->CreateSessionRegisterConfirm(array('user_id' => $result_create_user['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'register_confirm_token' => $register_session_confirm_token, 'register_hashed_confirm_tokens' => $this->action_control->HashedConfirmTokenBytes($register_confirm_token_bytes[4] . $register_confirm_token_bytes[7] . $register_confirm_token_bytes[0] . $register_confirm_token_bytes[1] . $register_confirm_token_bytes[6] . $register_confirm_token_bytes[3] . $register_confirm_token_bytes[2] . $register_confirm_token_bytes[5]), 'date_register_confirm_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CONFIRM_EMAIL_TOKEN)), 'is_register_confirm_used' => 0)) == 'Created') {
                                                    $cancel_register_link = $this->action_control->GenerateRegisterCancelLink();
                                                    if ($this->ActionModel->CreateLinkRegisterCancel(array('user_id' => $result_create_user['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'link_register_cancel' => $cancel_register_link, 'date_link_register_cancel_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CANCEL_REGISTER_LINK)), 'is_link_register_cancel_used' => 0)) == 'Created' && $this->action_control->SendMail($this->input_control->DecodePreventXSS($checked_inputs['email']), BRAND . ' Üyelik Aktifleştirme', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Üyelik Aktifleştirme | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.confirm-container {width: 100%;margin-left: auto;margin-right: auto;padding-top: 20px;padding-bottom: 20px;}.confirm {display: inline-block;font-size: 20px;text-align: center;background-color: #ffffff;color: #000000;width: 10%;padding-top: 10px;padding-bottom: 10px;margin-right: 1%;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-text-url {color: #6466ec !important;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm-container {width: 70%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Hesabınızı Doğrulayın</p></div><div class="main"><div class="confirm-container"><span class="confirm">' . $register_confirm_token_bytes[2] . '</span><span class="confirm">' . $register_confirm_token_bytes[4] . '</span><span class="confirm">' . $register_confirm_token_bytes[0] . '</span><span class="confirm">' . $register_confirm_token_bytes[7] . '</span><span class="confirm">' . $register_confirm_token_bytes[1] . '</span><span class="confirm">' . $register_confirm_token_bytes[3] . '</span><span class="confirm">' . $register_confirm_token_bytes[6] . '</span><span class="confirm">' . $register_confirm_token_bytes[5] . '</span></div><p class="text-2">Üyeliğinizi aktif etmek için üstteki kodu girin</p><p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, üyelik işlemini iptal etmek için <a class="footer-text-url" href="' . URL . URL_REGISTER_CANCEL . '?cr=' . $cancel_register_link . '">tıklayınız.</a></p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $result_create_user['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'RegisterConfirm')) == 'Created') {
                                                        $_SESSION[SESSION_REGISTER_CONFIRM_NAME] = $register_session_confirm_token;
                                                        $this->input_control->Redirect(URL_REGISTER_CONFIRM);
                                                    }
                                                }
                                                $_SESSION[SESSION_LOGIN_MESSAGE_NAME] = TR_NOTIFICATION_SUCCESS_REGISTER;
                                                $this->input_control->Redirect(URL_LOGIN);
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REGISTER);
                                            }
                                        } else {
                                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REGISTER);
                                        }
                                    } else {
                                        if (!empty($captcha_timeout_from_database)) {
                                            if ($captcha_timeout_from_database['captcha_error_count'] == 0) {
                                                $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'id' => $captcha_timeout_from_database['id']));
                                            } elseif ($captcha_timeout_from_database['captcha_error_count'] < 4) {
                                                $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SHORT_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['id']));
                                            } else {
                                                $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_LONG_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['id']));
                                            }
                                        }
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMPTY_REGISTER_TERMS);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $_SESSION[SESSION_WEB_DATA] = array('email' => $email, 'password' => $password, 'repassword' => $repassword);
                $this->input_control->Redirect(URL_REGISTER);
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function Register | ' . $th));
            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Error Occurred', 'Exception Error Occurred');
            $this->input_control->Redirect(URL_EXCEPTION);
        }
    }
    function ForgotPassword()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::LogView('Action/ForgotPassword');
                $this->web_data['form_token'] = parent::SetCSRFToken('ForgotPassword');
                parent::GetView('Action/ForgotPassword', $this->web_data);
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'email' => array('input' => isset($_POST['email']) ? $_POST['email'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'is_email' => true, 'error_message_is_email' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'length_control' => true, 'max_length' => EMAIL_MAX_LIMIT, 'error_message_max_length' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL, 'preventxss' => true, 'length_limit' => EMAIL_MAX_LIMIT_DB, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ForgotPassword') == true) {
                        $captcha_timeout_from_database = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
                        $captcha_timeout_not_error = false;
                        if (!empty($captcha_timeout_from_database)) {
                            if ($captcha_timeout_from_database['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_1 . ceil((int)((strtotime($captcha_timeout_from_database['date_captcha_timeout_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60)) . TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_2);
                                $this->input_control->Redirect(URL_FORGOT_PASSWORD);
                            }
                            $captcha_timeout_not_error = true;
                        } else if ($this->ActionModel->CreateCaptchaTimeOut(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'captcha_error_count' => 0, 'captcha_total_error_count' => 0)) == 'Created') {
                            $captcha_timeout_not_error = true;
                        }
                        if (!empty($captcha_respone)) {
                            $captcha_check_result = $this->action_control->CheckCaptcha($_POST['h-captcha-response']);
                            if ($captcha_check_result['result'] == true) {
                                if ($this->ActionModel->CreateLogCaptcha(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'success' => $captcha_check_result['success'], 'credit' => $captcha_check_result['credit'])) == 'Created' && $captcha_timeout_not_error) {
                                    if (!empty($captcha_timeout_from_database) && $captcha_timeout_from_database['captcha_error_count'] != 0) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => 0, 'id' => $captcha_timeout_from_database['id']));
                                    }
                                    $forgot_password_user_from_database = $this->UserModel->GetUserByUserIdByUserIdByEmail('id', $checked_inputs['email']);
                                    if (!empty($forgot_password_user_from_database)) {
                                        $forgot_password_token = $this->action_control->GenerateForgotPasswordToken();
                                        if ($this->ActionModel->CreateLinkForgotPassword(array('user_id' => $forgot_password_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'link_forgot_password' => $forgot_password_token, 'date_link_forgot_password_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_FORGOT_PASSWORD_TOKEN)), 'is_link_forgot_password_used' => 0)) == 'Created') {
                                            if ($this->action_control->SendMail($this->input_control->DecodePreventXSS($checked_inputs['email']), BRAND . ' Şifre Sıfırlama', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Şifre Sıfırlama | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-2-link {color: #6466ec !important;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Şifre Sıfırlama</p></div><div class="main"><p class="text-2">Şifrenizi sıfırlamak için linke <a class="text-2-link" href="' . URL . URL_RESET_PASSWORD . '?rp=' . $forgot_password_token . '">tıklayın.</a></p><p class="text-3">Şifre sıfırlama linkinin kullanım süresi ' . EXPIRY_FORGOT_PASSWORD_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, bu emaili önemsemeyin</p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $forgot_password_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'ForgotPassword')) == 'Created') {
                                                $_SESSION[SESSION_LOGIN_MESSAGE_NAME] = TR_NOTIFICATION_SUCCESS_FORGOT_PASSWORD;
                                                $this->input_control->Redirect(URL_LOGIN);
                                            }
                                        }
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_FORGOT_PASSWORD);
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_FORGOT_PASSWORD_NO_EMAIL);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                }
                            } else {
                                if (!empty($captcha_timeout_from_database)) {
                                    if ($captcha_timeout_from_database['captcha_error_count'] == 0) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'id' => $captcha_timeout_from_database['id']));
                                    } elseif ($captcha_timeout_from_database['captcha_error_count'] < 4) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SHORT_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['id']));
                                    } elseif ($captcha_timeout_from_database['captcha_error_count'] >= 4) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_LONG_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['id']));
                                    }
                                }
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function ForgotPassword | ' . $th));
            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Error Occurred', 'Exception Error Occurred');
            $this->input_control->Redirect(URL_EXCEPTION);
        }
    }
    function ResetPassword()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['rp'])) {
                $reset_password_url = $this->input_control->CheckGETInput($_GET['rp']);
                if (!empty($reset_password_url)) {
                    $forgot_password_from_database = $this->ActionModel->GetLinkForgotPassword($reset_password_url);
                    if (!empty($forgot_password_from_database) && $forgot_password_from_database['date_link_forgot_password_expiry'] > date('Y-m-d H:i:s') && $forgot_password_from_database['is_link_forgot_password_used'] == 0) {
                        if (empty($_SESSION[SESSION_FORGOT_PASSWORD_NAME])) {
                            $reset_password_token = $this->action_control->GenerateResetPasswordToken();
                            if ($this->ActionModel->UpdateLinkForgotPassword(array('is_link_forgot_password_used' => 1, 'date_link_forgot_password_created' => date('Y-m-d H:i:s'), 'ip_link_forgot_password_used' => $_SERVER['REMOTE_ADDR'], 'reset_password_token' => $reset_password_token, 'date_reset_password_created' => date('Y-m-d H:i:s'), 'id' => $forgot_password_from_database['id'])) == 'Updated') {
                                $_SESSION[SESSION_FORGOT_PASSWORD_NAME] = $reset_password_token;
                            }
                        } else {
                            if (!empty($_SESSION[SESSION_WEB_DATA])) {
                                if (!empty($_SESSION[SESSION_WEB_DATA]['password']) && !empty($_SESSION[SESSION_WEB_DATA]['repassword'])) {
                                    $this->web_data['password'] = $_SESSION[SESSION_WEB_DATA]['password'];
                                    $this->web_data['repassword'] = $_SESSION[SESSION_WEB_DATA]['repassword'];
                                }
                                $this->session_control->KillSession(SESSION_WEB_DATA);
                            }
                        }
                        $this->web_data['reset_password_token'] = $this->action_control->GenerateBaitToken();
                        $this->web_data['form_token'] = parent::SetCSRFToken('ResetPassword');
                        parent::GetView('Action/ResetPassword', $this->web_data);
                    }
                }
                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_FORGOT_PASSWORD);
                $this->input_control->Redirect(URL_FORGOT_PASSWORD);
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION[SESSION_FORGOT_PASSWORD_NAME])) {
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'password' => array('input' => $password, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'repassword' => array('input' => $repassword, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true),
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ResetPassword') == true) {
                        $password_salt = strtr(sodium_bin2base64(random_bytes(75), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => '2', '_' => '4'));
                        $salted_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['password']), PASSWORD_SECRET_KEY, true);
                        $salted_re_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['repassword']), PASSWORD_SECRET_KEY, true);
                        $hashed_password = password_hash($salted_password, PASSWORD_BCRYPT, $this->password_control->BcryptOptions());
                        $verified_pwd = password_verify($salted_re_password, $hashed_password);
                        if ($verified_pwd) {
                            $reset_password_from_database = $this->ActionModel->GetResetPassword(array($_SESSION[SESSION_FORGOT_PASSWORD_NAME], $_SERVER['REMOTE_ADDR']));
                            $this->session_control->KillSession($_SESSION[SESSION_FORGOT_PASSWORD_NAME]);
                            if (!empty($reset_password_from_database) && $reset_password_from_database['date_forgot_password_expiry'] > date('Y-m-d H:i:s') && $reset_password_from_database['is_forgot_password_used'] == 1 && $reset_password_from_database['is_reset_password_token_used'] == 0 && $this->ActionModel->UpdateLinkForgotPassword(array('is_reset_password_token_used' => 1, 'date_reset_password_token_used' => date('Y-m-d H:i:s'), 'id' => $reset_password_from_database['id'])) == 'Updated' && $this->UserModel->UpdateUser(array('password' => $this->input_control->EncrypteData($hashed_password, PASSWORD_PEPPER), 'password_salt' => $password_salt, 'id' => $reset_password_from_database['user_id'])) == 'Updated') {
                                $_SESSION[SESSION_LOGIN_MESSAGE_NAME] = TR_NOTIFICATION_SUCCESS_RESET_PASSWORD;
                                $this->input_control->Redirect(URL_LOGIN);
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_FORGOT_PASSWORD);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $_SESSION[SESSION_WEB_DATA] = array('password' => $password, 'repassword' => $repassword);
                $this->input_control->Redirect(URL_RESET_PASSWORD);
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function ResetPassword | ' . $th));
            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Error Occurred', 'Exception Error Occurred');
            $this->input_control->Redirect(URL_EXCEPTION);
        }
    }
    function AdminLogin()
    {
        try {
            if ($_SERVER['REMOTE_ADDR'] === ADMIN_IP_ADDRESS) {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $this->input_control->CheckUrl();
                    parent::LogView('Action/AdminLogin');
                    $this->web_data['form_token'] = parent::SetCSRFToken('AdminLogin');
                    parent::GetView('Action/AdminLogin', $this->web_data);
                } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $checked_inputs = $this->input_control->CheckPostedInputs(array(
                        'email' => array('input' => isset($_POST['email']) ? $_POST['email'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL, 'preventxss' => true),
                        'password' => array('input' => isset($_POST['password']) ? $_POST['password'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD, 'preventxss' => true),
                        'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                    ));
                    if (empty($checked_inputs['error_message'])) {
                        if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminLogin') == true) {
                            $captcha_timeout_from_database = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
                            $captcha_timeout_not_error = false;
                            if (!empty($captcha_timeout_from_database)) {
                                if ($captcha_timeout_from_database['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_1 . ceil((int)((strtotime($captcha_timeout_from_database['date_captcha_timeout_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60)) . TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_2);
                                    $this->input_control->Redirect(URL_ADMIN_LOGIN);
                                }
                                $captcha_timeout_not_error = true;
                            } else if ($this->ActionModel->CreateCaptchaTimeOut(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'captcha_error_count' => 0, 'captcha_total_error_count' => 0)) == 'Created') {
                                $captcha_timeout_not_error = true;
                            }
                            if (!empty($_POST['h-captcha-response'])) {
                                $captcha_check_result = $this->action_control->CheckCaptcha($_POST['h-captcha-response']);
                                if ($captcha_check_result['result'] == true) {
                                    if ($this->ActionModel->CreateLogCaptcha(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'success' => $captcha_check_result['success'], 'credit' => $captcha_check_result['credit'])) == 'Created' && $captcha_timeout_not_error) {
                                        if (!empty($captcha_timeout_from_database) && $captcha_timeout_from_database['captcha_error_count'] != 0) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => 0, 'id' => $captcha_timeout_from_database['id']));
                                        }
                                        $login_user_from_database = $this->UserModel->GetUserByUserIdByUserIdByEmail('id,email_confirmed,password,password_salt,two_fa_enable,user_role,fail_access_count', $checked_inputs['email']);
                                        if (!empty($login_user_from_database)) {
                                            $salted_password = hash_hmac('sha512', $login_user_from_database['password_salt'] . str_replace("\0", "", $checked_inputs['password']), PASSWORD_SECRET_KEY, true);
                                            $decrypted_hashed_password = $this->input_control->DecrypteData($login_user_from_database['password'], PASSWORD_PEPPER);
                                            if (!is_null($decrypted_hashed_password)) {
                                                $verified_pwd = password_verify($salted_password, $decrypted_hashed_password);
                                            } else {
                                                $verified_pwd = false;
                                            }
                                            if ($verified_pwd) {
                                                $bcrypt_options = $this->password_control->BcryptOptions();
                                                if (password_needs_rehash($decrypted_hashed_password, PASSWORD_BCRYPT, $bcrypt_options)) {
                                                    $new_hashed_password = password_hash($salted_password, PASSWORD_BCRYPT, $bcrypt_options);
                                                    $encrypted_hashed_password = $this->input_control->EncrypteData($new_hashed_password, PASSWORD_PEPPER);
                                                    $this->UserModel->UpdateUser(array('password' => $encrypted_hashed_password, 'id' => $login_user_from_database['id']));
                                                }
                                                if ($login_user_from_database['user_role'] == ADMIN_ROLE_ID && $login_user_from_database['email_confirmed'] == 1 && $login_user_from_database['two_fa_enable'] == 1) {
                                                    $session_authentication_token = $this->session_control->GenerateSessionAuthenticationToken();
                                                    if ($this->ActionModel->CreateSessionAuthentication(array('user_id' => $login_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'session_authentication_token' => $session_authentication_token, 'date_session_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SESSION_AUTHENTICATION)))) == 'Created' && $this->ActionModel->UpdateUser(array('date_last_login' => date('Y-m-d H:i:s'), 'id' => $login_user_from_database['id'])) == 'Updated' && $this->ActionModel->CreateLogLogin(array('user_id' => $login_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 1)) == 'Created') {
                                                        $_SESSION[SESSION_AUTHENTICATION_NAME] = $session_authentication_token;
                                                        $this->input_control->Redirect(URL_ADMIN_INDEX);
                                                    }
                                                }
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                $this->input_control->Redirect(URL_LOGIN);
                                            } else {
                                                $this->ActionModel->CreateLogLogin(array('user_id' => $login_user_from_database['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 0));
                                                $this->UserModel->UpdateUser(array('fail_access_count' => $login_user_from_database['fail_access_count'] + 1, 'date_last_fail_access_attempt' => date('Y-m-d H:i:s'), 'id' => $login_user_from_database['id']));
                                            }
                                        }
                                        $count_fail_login_from_db = $this->ActionModel->GetCountFailLogin($_SERVER['REMOTE_ADDR']);
                                        if (!empty($count_fail_login_from_db)) {
                                            if ($count_fail_login_from_db['ip_fail_login_count'] >= 1000) {
                                                $this->notification_control->SetNotification('DANGER', COUNT_FAIL_LOGIN_ERROR);
                                                $this->input_control->Redirect(URL_ADMIN_LOGIN);
                                            } else {
                                                $this->ActionModel->UpdateCountFailLogin(array(
                                                    'ip_fail_login_count' => $count_fail_login_from_db['ip_fail_login_count'] + 1,
                                                    'id' => $count_fail_login_from_db['id']
                                                ));
                                            }
                                        } else {
                                            $this->ActionModel->CreateCountFailLogin(array(
                                                'user_ip' => $_SERVER['REMOTE_ADDR'],
                                                'ip_fail_login_count' => 1
                                            ));
                                        }
                                        $this->notification_control->SetNotification('DANGER', LOGIN_ERROR);
                                        $this->input_control->Redirect(URL_LOGIN . (isset($_POST['redirect_location']) ? '?yonlendir=' . $_POST['redirect_location'] : ''));
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                    }
                                } else {
                                    if (!empty($captcha_timeout_from_database)) {
                                        if ($captcha_timeout_from_database['captcha_error_count'] == 0) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'id' => $captcha_timeout_from_database['id']));
                                        } elseif ($captcha_timeout_from_database['captcha_error_count'] < 4) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SHORT_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['id']));
                                        } elseif ($captcha_timeout_from_database['captcha_error_count'] >= 4) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_LONG_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['id']));
                                        }
                                    }
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA);
                            }
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                    }
                    $this->input_control->Redirect(URL_ADMIN_LOGIN);
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function AdminLogin | ' . $th));
            $this->action_control->SendMail(ADMIN_EMAIL, BRAND . ' Error Occurred', 'Exception Error Occurred');
            $this->input_control->Redirect(URL_EXCEPTION);
        }        
    }
}
