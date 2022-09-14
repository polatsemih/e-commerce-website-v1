<?php
class ActionController extends Controller
{
    function __construct()
    {
        parent::__construct();
        if (!empty($this->web_data['authenticated_user'])) {
            $this->input_control->Redirect();
        }
        $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
    }
    function TwoFA()
    {
        try {
            if (!empty($_SESSION[SESSION_TWO_FA_NAME])) {
                $two_fa_token_from_database = $this->ActionModel->GetSessionTwoFA(array($_SERVER['REMOTE_ADDR'], $_SESSION[SESSION_TWO_FA_NAME]));
                if ($two_fa_token_from_database['result'] && $two_fa_token_from_database['data']['date_two_fa_expiry'] > date('Y-m-d H:i:s') && $two_fa_token_from_database['data']['is_two_fa_used'] == 0) {
                    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        $this->input_control->CheckUrl();
                        parent::LogView('Action-TwoFA');
                        $bait_token = $this->action_control->GenerateBaitToken();
                        if (!empty($bait_token)) {
                            $this->web_data['confirm_token'] = $bait_token;
                        }
                        $a = strtotime($two_fa_token_from_database['data']['date_two_fa_expiry']);
                        $b = strtotime(date('Y-m-d H:i:s'));
                        if (!empty($a) && !empty($b)) {
                            $this->web_data['expiry_remain_minute'] = (int)(($a - $b) / 60);
                            $this->web_data['expiry_remain_second'] = ($a - $b) % 60;
                            $this->web_data['form_token'] = parent::SetCSRFToken('TwoFA');
                            parent::GetView('Action/TwoFA', $this->web_data);
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $this->session_control->KillSession(SESSION_TWO_FA_NAME);
                        $checked_inputs = $this->input_control->CheckPostedInputs(array(
                            'token_1' => array('input' => isset($_POST['token_1']) ? $_POST['token_1'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN),
                            'token_2' => array('input' => isset($_POST['token_2']) ? $_POST['token_2'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN),
                            'token_3' => array('input' => isset($_POST['token_3']) ? $_POST['token_3'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN),
                            'token_4' => array('input' => isset($_POST['token_4']) ? $_POST['token_4'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN),
                            'token_5' => array('input' => isset($_POST['token_5']) ? $_POST['token_5'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN),
                            'token_6' => array('input' => isset($_POST['token_6']) ? $_POST['token_6'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN),
                            'token_7' => array('input' => isset($_POST['token_7']) ? $_POST['token_7'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN),
                            'token_8' => array('input' => isset($_POST['token_8']) ? $_POST['token_8'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN),
                            'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                        ));
                        if (empty($checked_inputs['error_message'])) {
                            if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'TwoFA')) {
                                $hashed_token = $this->action_control->HashedConfirmTokenBytes($checked_inputs['token_2'] . $checked_inputs['token_4'] . $checked_inputs['token_3'] . $checked_inputs['token_5'] . $checked_inputs['token_7'] . $checked_inputs['token_6'] . $checked_inputs['token_1'] . $checked_inputs['token_8']);
                                if ($this->ActionModel->UpdateSessionTwoFA(array('is_two_fa_used' => 1, 'date_two_fa_used' => date('Y-m-d H:i:s'), 'id' => $two_fa_token_from_database['data']['id']))['result'] && !empty($hashed_token)) {
                                    if ($two_fa_token_from_database['data']['two_fa_hashed_tokens'] == $hashed_token) {
                                        if ($_SERVER['REMOTE_ADDR'] == ADMIN_IP_ADDRESS && !empty($_SESSION[SESSION_ADMIN_TWO_FA_NAME]) && $_SESSION[SESSION_ADMIN_TWO_FA_NAME] == true) {
                                            $this->session_control->KillSession(SESSION_ADMIN_TWO_FA_NAME);
                                            $two_fa_user_from_database = $this->UserModel->GetAdminByAdminId('id', $two_fa_token_from_database['data']['user_id']);
                                            if ($two_fa_user_from_database['result']) {
                                                $session_authentication_token = $this->input_control->GenerateToken();
                                                if ($session_authentication_token['result'] && $this->ActionModel->CreateSessionAuthentication(array('user_id' => $two_fa_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'session_authentication_token' => $session_authentication_token['data'], 'date_session_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SESSION_AUTHENTICATION))))['result'] && $this->UserModel->UpdateAdmin(array('date_last_login' => date('Y-m-d H:i:s'), 'id' => $two_fa_user_from_database['data']['id']))['result'] && $this->ActionModel->CreateLogLogin(array('user_id' => $two_fa_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 1))['result']) {
                                                    $_SESSION[SESSION_OBSOLETE_NAME] = time() + (60 * 5);
                                                    if (session_regenerate_id()) {
                                                        $this->session_control->KillSession(SESSION_OBSOLETE_NAME);
                                                        $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                                                        $_SESSION[SESSION_ADMIN_AUTHENTICATION_NAME] = $session_authentication_token['data'];
                                                        $this->input_control->Redirect(URL_ADMIN_INDEX);
                                                    } else {
                                                        $this->input_control->Redirect(URL_EXCEPTION);
                                                    }
                                                }
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                $this->input_control->Redirect(URL_LOGIN);
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_TWO_FA);
                                            }
                                        } else {
                                            $two_fa_user_from_database = $this->UserModel->GetUserByUserId('id', $two_fa_token_from_database['data']['user_id']);
                                            if ($two_fa_user_from_database['result']) {
                                                if ($two_fa_token_from_database['data']['remember_me'] == 1) {
                                                    $cookie_authentication_token_part_1 = $this->input_control->GenerateToken();
                                                    $cookie_authentication_token_part_2 = $this->input_control->GenerateToken();
                                                    $cookie_authentication_salt = $this->input_control->GenerateToken();
                                                    if ($cookie_authentication_token_part_1['result'] && $cookie_authentication_token_part_2['result'] && $cookie_authentication_salt['result']) {
                                                        $cookie_authentication_token = $cookie_authentication_token_part_1['data'] . $cookie_authentication_token_part_2['data'];
                                                        $extracted_cookie_authentication_token1 = substr($cookie_authentication_token, 200, 200);
                                                        $extracted_cookie_authentication_token2 = substr($cookie_authentication_token, 0, 200);
                                                        if (!empty($extracted_cookie_authentication_token1) && !empty($extracted_cookie_authentication_token2)) {
                                                            $cookie_authentication_token1 = hash_hmac('SHA512', $extracted_cookie_authentication_token1, $cookie_authentication_salt['data'], false);
                                                            if (!empty($cookie_authentication_token1) && $this->ActionModel->CreateCookieAuthentication(array('user_id' => $two_fa_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'cookie_authentication_token1' => $cookie_authentication_token1, 'cookie_authentication_token2' => $extracted_cookie_authentication_token2, 'cookie_authentication_salt' => $cookie_authentication_salt['data'], 'date_cookie_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_COOKIE_AUTHENTICATION))))['result'] && $this->UserModel->UpdateUser(array('date_last_login' => date('Y-m-d H:i:s'), 'id' => $two_fa_user_from_database['data']['id']))['result'] && $this->ActionModel->CreateLogLogin(array('user_id' => $two_fa_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 1))['result'] && $this->cookie_control->SetCookie(COOKIE_AUTHENTICATION_NAME, $cookie_authentication_token, time() + (EXPIRY_COOKIE_AUTHENTICATION), COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTP_ONLY, COOKIE_SAMESITE)) {
                                                                if (!empty($_SESSION[SESSION_REDIRECT_LOCATION_NAME])) {
                                                                    $posted_redirect = explode('%2F', $_SESSION[SESSION_REDIRECT_LOCATION_NAME]);
                                                                    $this->session_control->KillSession(SESSION_REDIRECT_LOCATION_NAME);
                                                                    if (!empty($posted_redirect) && count($posted_redirect) == 2 && !empty($posted_redirect[0]) && !empty($posted_redirect[1]) && in_array($posted_redirect[0], REDIRECT_PERMISSION)) {
                                                                        $this->input_control->Redirect($posted_redirect[0] . '/' . $posted_redirect[1]);
                                                                    }
                                                                }
                                                                $this->input_control->Redirect();
                                                            }
                                                        }
                                                    }
                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                    $this->input_control->Redirect(URL_LOGIN);
                                                } else {
                                                    $session_authentication_token = $this->input_control->GenerateToken();
                                                    if ($session_authentication_token['result'] && $this->ActionModel->CreateSessionAuthentication(array('user_id' => $two_fa_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'session_authentication_token' => $session_authentication_token['data'], 'date_session_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SESSION_AUTHENTICATION))))['result'] && $this->UserModel->UpdateUser(array('date_last_login' => date('Y-m-d H:i:s'), 'id' => $two_fa_user_from_database['data']['id']))['result'] && $this->ActionModel->CreateLogLogin(array('user_id' => $two_fa_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 1))['result']) {
                                                        $_SESSION[SESSION_OBSOLETE_NAME] = time() + (60 * 5);
                                                        if (session_regenerate_id()) {
                                                            $this->session_control->KillSession(SESSION_OBSOLETE_NAME);
                                                            $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                                                            $_SESSION[SESSION_AUTHENTICATION_NAME] = $session_authentication_token['data'];
                                                            if (!empty($_SESSION[SESSION_REDIRECT_LOCATION_NAME])) {
                                                                $posted_redirect = explode('%2F', $_SESSION[SESSION_REDIRECT_LOCATION_NAME]);
                                                                $this->session_control->KillSession(SESSION_REDIRECT_LOCATION_NAME);
                                                                if (!empty($posted_redirect) && count($posted_redirect) == 2 && !empty($posted_redirect[0]) && !empty($posted_redirect[1]) && in_array($posted_redirect[0], REDIRECT_PERMISSION)) {
                                                                    $this->input_control->Redirect($posted_redirect[0] . '/' . $posted_redirect[1]);
                                                                }
                                                            }
                                                            $this->input_control->Redirect();
                                                        } else {
                                                            $this->input_control->Redirect(URL_EXCEPTION);
                                                        }
                                                    }
                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                    $this->input_control->Redirect(URL_LOGIN);
                                                }
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_TWO_FA);
                                            }
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_TWO_FA);
                                }
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                        }
                        $this->input_control->Redirect(URL_LOGIN);
                    }
                } else {
                    $this->session_control->KillSession(SESSION_TWO_FA_NAME);
                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_TWO_FA_TOKEN_EXPIRIED);
                    $this->input_control->Redirect(URL_LOGIN);
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function TwoFA | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Login()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl(array('yonlendir'));
                parent::LogView('Action-Login');
                if (!empty($_SESSION[SESSION_WEB_DATA_NAME])) {
                    if (!empty($_SESSION[SESSION_WEB_DATA_NAME]['email']) && !empty($_SESSION[SESSION_WEB_DATA_NAME]['password'])) {
                        $this->web_data['email'] = $_SESSION[SESSION_WEB_DATA_NAME]['email'];
                        $this->web_data['password'] = $_SESSION[SESSION_WEB_DATA_NAME]['password'];
                    }
                    $this->session_control->KillSession(SESSION_WEB_DATA_NAME);
                } elseif (!empty($_GET['yonlendir'])) {
                    $checked_redirect_location = $this->input_control->CheckGETInput($_GET['yonlendir']);
                    if (!empty($checked_redirect_location)) {
                        $_SESSION[SESSION_REDIRECT_LOCATION_NAME] = $checked_redirect_location;
                    }
                }
                $this->web_data['form_token'] = parent::SetCSRFToken('Login');
                parent::GetView('Action/Login', $this->web_data);
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = isset($_POST['email']) ? $_POST['email'] : '';
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'email' => array('input' => $email, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL, 'preventxss' => true),
                    'password' => array('input' => $password, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Login')) {
                        $captcha_timeout_from_database = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
                        $captcha_timeout_not_error = false;
                        if ($captcha_timeout_from_database['result']) {
                            if ($captcha_timeout_from_database['data']['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                                $a = strtotime($captcha_timeout_from_database['data']['date_captcha_timeout_expiry']);
                                $b = strtotime(date('Y-m-d H:i:s'));
                                if (!empty($a) && !empty($b)) {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_1 . ceil((int)(($a - $b) / 60)) . TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_2);
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                }
                                $this->input_control->Redirect(URL_LOGIN);
                            }
                            $captcha_timeout_not_error = true;
                        } elseif ($this->ActionModel->CreateCaptchaTimeOut(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'captcha_error_count' => 0, 'captcha_total_error_count' => 0))['result']) {
                            $captcha_timeout_not_error = true;
                        }
                        if (!empty($_POST['h-captcha-response'])) {
                            $captcha_check_result = $this->action_control->CheckCaptcha($_POST['h-captcha-response']);
                            if ($captcha_check_result['result']) {
                                if ($this->ActionModel->CreateLogCaptcha(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'success' => $captcha_check_result['success'], 'credit' => $captcha_check_result['credit']))['result'] && $captcha_timeout_not_error) {
                                    if ($captcha_timeout_from_database['result'] && $captcha_timeout_from_database['data']['captcha_error_count'] != 0) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => 0, 'id' => $captcha_timeout_from_database['data']['id']));
                                    }
                                    $login_user_from_database = $this->UserModel->GetUserByEmail('id,email_confirmed,password,password_salt,two_fa_enable,user_role,fail_access_count', $checked_inputs['email']);
                                    if ($login_user_from_database['result']) {
                                        $salted_password = hash_hmac('sha512', $login_user_from_database['data']['password_salt'] . str_replace("\0", "", $checked_inputs['password']), PASSWORD_SECRET_KEY, true);
                                        $decrypted_hashed_password = $this->input_control->DecrypteData($login_user_from_database['data']['password'], PASSWORD_PEPPER);
                                        if ($decrypted_hashed_password['result'] && !empty($salted_password)) {
                                            $verified_pwd = password_verify($salted_password, $decrypted_hashed_password['data']);
                                        } else {
                                            $verified_pwd = false;
                                        }
                                        if ($verified_pwd) {
                                            $bcrypt_options = $this->password_control->BcryptOptions();
                                            if (password_needs_rehash($decrypted_hashed_password['data'], PASSWORD_BCRYPT, $bcrypt_options)) {
                                                $new_hashed_password = password_hash($salted_password, PASSWORD_BCRYPT, $bcrypt_options);
                                                $encrypted_hashed_password = $this->input_control->EncrypteData($new_hashed_password, PASSWORD_PEPPER);
                                                $this->UserModel->UpdateUser(array('password' => $encrypted_hashed_password, 'id' => $login_user_from_database['data']['id']));
                                            }
                                            if ($login_user_from_database['data']['user_role'] == ADMIN_ROLE_ID) {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN_INFORMATIONS);
                                                $this->input_control->Redirect(URL_LOGIN);
                                            } elseif ($login_user_from_database['data']['email_confirmed'] == 1) {
                                                if (!empty($_POST['remember_me'])) {
                                                    $remember_me = 1;
                                                } else {
                                                    $remember_me = 2;
                                                }
                                                if ($login_user_from_database['data']['two_fa_enable'] == 1) {
                                                    $session_two_fa_token = $this->input_control->GenerateToken();
                                                    $session_two_fa_token_bytes = $this->action_control->GenerateConfirmTokenBytes();
                                                    if (!empty($session_two_fa_token_bytes)) {
                                                        $hashed_token = $this->action_control->HashedConfirmTokenBytes($session_two_fa_token_bytes[4] . $session_two_fa_token_bytes[7] . $session_two_fa_token_bytes[0] . $session_two_fa_token_bytes[1] . $session_two_fa_token_bytes[6] . $session_two_fa_token_bytes[3] . $session_two_fa_token_bytes[2] . $session_two_fa_token_bytes[5]);
                                                        if (!empty($session_two_fa_token['result']) && !empty($hashed_token) && $this->ActionModel->CreateSessionTwoFA(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'two_fa_token' => $session_two_fa_token['data'], 'two_fa_hashed_tokens' => $hashed_token, 'remember_me' => $remember_me, 'date_two_fa_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_TWO_FA_TOKEN)), 'is_two_fa_used' => 0))['result'] && $this->action_control->SendMail($this->input_control->DecodePreventXSS($checked_inputs['email']), BRAND . ' İki Aşamalı Doğrulama', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>İki Aşamalı Doğrulama | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.confirm-container {width: 100%;margin-left: auto;margin-right: auto;padding-top: 20px;padding-bottom: 20px;}.confirm {display: inline-block;font-size: 20px;text-align: center;background-color: #ffffff;color: #000000;width: 10%;padding-top: 10px;padding-bottom: 10px;margin-right: 1%;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm-container {width: 70%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' İki Aşamalı Doğrulama</p></div><div class="main"><div class="confirm-container"><span class="confirm">' . $session_two_fa_token_bytes[2] . '</span><span class="confirm">' . $session_two_fa_token_bytes[4] . '</span><span class="confirm">' . $session_two_fa_token_bytes[0] . '</span><span class="confirm">' . $session_two_fa_token_bytes[7] . '</span><span class="confirm">' . $session_two_fa_token_bytes[1] . '</span><span class="confirm">' . $session_two_fa_token_bytes[3] . '</span><span class="confirm">' . $session_two_fa_token_bytes[6] . '</span><span class="confirm">' . $session_two_fa_token_bytes[5] . '</span></div><p class="text-2">Giriş yapmak için üstteki kodu girin</p><p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_TWO_FA_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, hemen ' . BRAND . ' hesabınızın şifresini değiştirin</p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'TwoFA'))['result']) {
                                                            $_SESSION[SESSION_TWO_FA_NAME] = $session_two_fa_token['data'];
                                                            $this->input_control->Redirect(URL_TWO_FA);
                                                        }
                                                    }
                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                    $this->input_control->Redirect(URL_LOGIN);
                                                } else {
                                                    if ($remember_me == 1) {
                                                        $cookie_authentication_token_part_1 = $this->input_control->GenerateToken();
                                                        $cookie_authentication_token_part_2 = $this->input_control->GenerateToken();
                                                        $cookie_authentication_salt = $this->input_control->GenerateToken();
                                                        if ($cookie_authentication_token_part_1['result'] && $cookie_authentication_token_part_2['result'] && $cookie_authentication_salt['result']) {
                                                            $cookie_authentication_token = $cookie_authentication_token_part_1['data'] . $cookie_authentication_token_part_2['data'];
                                                            $extracted_cookie_authentication_token1 = substr($cookie_authentication_token, 200, 200);
                                                            $extracted_cookie_authentication_token2 = substr($cookie_authentication_token, 0, 200);
                                                            if (!empty($extracted_cookie_authentication_token1) && !empty($extracted_cookie_authentication_token2)) {
                                                                $cookie_authentication_token1 = hash_hmac('SHA512', $extracted_cookie_authentication_token1, $cookie_authentication_salt['data'], false);
                                                                if (!empty($cookie_authentication_token1) && $this->ActionModel->CreateCookieAuthentication(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'cookie_authentication_token1' => $cookie_authentication_token1, 'cookie_authentication_token2' => $extracted_cookie_authentication_token2, 'cookie_authentication_salt' => $cookie_authentication_salt['data'], 'date_cookie_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_COOKIE_AUTHENTICATION))))['result'] && $this->UserModel->UpdateUser(array('date_last_login' => date('Y-m-d H:i:s'), 'id' => $login_user_from_database['data']['id']))['result'] && $this->ActionModel->CreateLogLogin(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 1))['result'] && $this->cookie_control->SetCookie(COOKIE_AUTHENTICATION_NAME, $cookie_authentication_token, time() + (EXPIRY_COOKIE_AUTHENTICATION), COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTP_ONLY, COOKIE_SAMESITE)) {
                                                                    if (!empty($_SESSION[SESSION_REDIRECT_LOCATION_NAME])) {
                                                                        $posted_redirect = explode('%2F', $_SESSION[SESSION_REDIRECT_LOCATION_NAME]);
                                                                        $this->session_control->KillSession(SESSION_REDIRECT_LOCATION_NAME);
                                                                        if (!empty($posted_redirect) && count($posted_redirect) == 2 && !empty($posted_redirect[0]) && !empty($posted_redirect[1]) && in_array($posted_redirect[0], REDIRECT_PERMISSION)) {
                                                                            $this->input_control->Redirect($posted_redirect[0] . '/' . $posted_redirect[1]);
                                                                        }
                                                                    }
                                                                    $this->input_control->Redirect();
                                                                }
                                                            }
                                                        }
                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                        $this->input_control->Redirect(URL_LOGIN);
                                                    } else {
                                                        $session_authentication_token = $this->input_control->GenerateToken();
                                                        if ($session_authentication_token['result'] && $this->ActionModel->CreateSessionAuthentication(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'session_authentication_token' => $session_authentication_token['data'], 'date_session_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SESSION_AUTHENTICATION))))['result'] && $this->UserModel->UpdateUser(array('date_last_login' => date('Y-m-d H:i:s'), 'id' => $login_user_from_database['data']['id']))['result'] && $this->ActionModel->CreateLogLogin(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 1))['result']) {
                                                            $_SESSION[SESSION_OBSOLETE_NAME] = time() + (60 * 5);
                                                            if (session_regenerate_id()) {
                                                                $this->session_control->KillSession(SESSION_OBSOLETE_NAME);
                                                                $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                                                                $_SESSION[SESSION_AUTHENTICATION_NAME] = $session_authentication_token['data'];
                                                                if (!empty($_SESSION[SESSION_REDIRECT_LOCATION_NAME])) {
                                                                    $posted_redirect = explode('%2F', $_SESSION[SESSION_REDIRECT_LOCATION_NAME]);
                                                                    $this->session_control->KillSession(SESSION_REDIRECT_LOCATION_NAME);
                                                                    if (!empty($posted_redirect) && count($posted_redirect) == 2 && !empty($posted_redirect[0]) && !empty($posted_redirect[1]) && in_array($posted_redirect[0], REDIRECT_PERMISSION)) {
                                                                        $this->input_control->Redirect($posted_redirect[0] . '/' . $posted_redirect[1]);
                                                                    }
                                                                }
                                                                $this->input_control->Redirect();
                                                            } else {
                                                                $this->input_control->Redirect(URL_EXCEPTION);
                                                            }
                                                        }
                                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                        $this->input_control->Redirect(URL_LOGIN);
                                                    }
                                                }
                                            } else {
                                                $register_confirm_token = $this->input_control->GenerateToken();
                                                $register_confirm_token_bytes = $this->action_control->GenerateConfirmTokenBytes();
                                                $register_confirm_no_error = false;
                                                if (!empty($register_confirm_token_bytes)) {
                                                    $hashed_token_bytes = $this->action_control->HashedConfirmTokenBytes($register_confirm_token_bytes[4] . $register_confirm_token_bytes[7] . $register_confirm_token_bytes[0] . $register_confirm_token_bytes[1] . $register_confirm_token_bytes[6] . $register_confirm_token_bytes[3] . $register_confirm_token_bytes[2] . $register_confirm_token_bytes[5]);
                                                    if ($register_confirm_token['result'] && !empty($hashed_token_bytes) && $this->ActionModel->CreateSessionRegisterConfirm(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'register_confirm_token' => $register_confirm_token['data'], 'register_hashed_confirm_tokens' => $hashed_token_bytes, 'date_register_confirm_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CONFIRM_EMAIL_TOKEN)), 'is_register_confirm_used' => 0))['result']) {
                                                        $link_register_cancel_from_database = $this->ActionModel->GetLinkRegisterCancelByUserId($login_user_from_database['data']['id']);
                                                        if ($link_register_cancel_from_database['result'] && $link_register_cancel_from_database['data']['date_link_register_cancel_expiry'] > date('d/m/Y H:i:s') && $link_register_cancel_from_database['data']['is_link_register_cancel_used'] == 0) {
                                                            if ($this->action_control->SendMail($this->input_control->DecodePreventXSS($checked_inputs['email']), BRAND . ' Üyelik Aktifleştirme', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Üyelik Aktifleştirme | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.confirm-container {width: 100%;margin-left: auto;margin-right: auto;padding-top: 20px;padding-bottom: 20px;}.confirm {display: inline-block;font-size: 20px;text-align: center;background-color: #ffffff;color: #000000;width: 10%;padding-top: 10px;padding-bottom: 10px;margin-right: 1%;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-text-url {color: #6466ec !important;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm-container {width: 70%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Hesabınızı Doğrulayın</p></div><div class="main"><div class="confirm-container"><span class="confirm">' . $register_confirm_token_bytes[2] . '</span><span class="confirm">' . $register_confirm_token_bytes[4] . '</span><span class="confirm">' . $register_confirm_token_bytes[0] . '</span><span class="confirm">' . $register_confirm_token_bytes[7] . '</span><span class="confirm">' . $register_confirm_token_bytes[1] . '</span><span class="confirm">' . $register_confirm_token_bytes[3] . '</span><span class="confirm">' . $register_confirm_token_bytes[6] . '</span><span class="confirm">' . $register_confirm_token_bytes[5] . '</span></div><p class="text-2">Üyeliğinizi aktif etmek için üstteki kodu girin</p><p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, üyelik işlemini iptal etmek için <a class="footer-text-url" href="' . URL . URL_REGISTER_CANCEL . '?cr=' . $link_register_cancel_from_database['data']['link_register_cancel'] . '">tıklayınız.</a></p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'RegisterConfirmFromLogin'))['result']) {
                                                                $register_confirm_no_error = true;
                                                            }
                                                        } elseif ($this->action_control->SendMail($this->input_control->DecodePreventXSS($checked_inputs['email']), BRAND . ' Üyelik Aktifleştirme', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Üyelik Aktifleştirme | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.confirm-container {width: 100%;margin-left: auto;margin-right: auto;padding-top: 20px;padding-bottom: 20px;}.confirm {display: inline-block;font-size: 20px;text-align: center;background-color: #ffffff;color: #000000;width: 10%;padding-top: 10px;padding-bottom: 10px;margin-right: 1%;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text-url {color: #6466ec !important;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm-container {width: 70%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Hesabınızı Doğrulayın</p></div><div class="main"><div class="confirm-container"><span class="confirm">' . $register_confirm_token_bytes[2] . '</span><span class="confirm">' . $register_confirm_token_bytes[4] . '</span><span class="confirm">' . $register_confirm_token_bytes[0] . '</span><span class="confirm">' . $register_confirm_token_bytes[7] . '</span><span class="confirm">' . $register_confirm_token_bytes[1] . '</span><span class="confirm">' . $register_confirm_token_bytes[3] . '</span><span class="confirm">' . $register_confirm_token_bytes[6] . '</span><span class="confirm">' . $register_confirm_token_bytes[5] . '</span></div><p class="text-2">Üyeliğinizi aktif etmek için üstteki kodu girin</p><p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'RegisterConfirmFromLogin'))['result']) {
                                                            $register_confirm_no_error = true;
                                                        }
                                                    }
                                                }
                                                if ($register_confirm_no_error) {
                                                    $_SESSION[SESSION_REGISTER_CONFIRM_NAME] = $register_confirm_token['data'];
                                                    $this->input_control->Redirect(URL_REGISTER_CONFIRM);
                                                }
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REGISTER_CONFIRM);
                                                $this->input_control->Redirect(URL_LOGIN);
                                            }
                                        } else {
                                            $this->ActionModel->CreateLogLogin(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 0));
                                            $this->UserModel->UpdateUser(array('fail_access_count' => $login_user_from_database['data']['fail_access_count'] + 1, 'date_last_fail_access_attempt' => date('Y-m-d H:i:s'), 'id' => $login_user_from_database['data']['id']));
                                        }
                                    } else {
                                        $this->ActionModel->CreateLogLoginEmailFail(array('user_ip' => $_SERVER['REMOTE_ADDR']));
                                    }
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN_INFORMATIONS);
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                }
                            } else {
                                if ($captcha_timeout_from_database['result']) {
                                    if ($captcha_timeout_from_database['data']['captcha_error_count'] == 0) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'id' => $captcha_timeout_from_database['data']['id']));
                                    } elseif ($captcha_timeout_from_database['data']['captcha_error_count'] < 4) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SHORT_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['data']['id']));
                                    } elseif ($captcha_timeout_from_database['data']['captcha_error_count'] >= 4) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_LONG_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['data']['id']));
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
                $_SESSION[SESSION_WEB_DATA_NAME] = array('email' => $email, 'password' => $password);
                $this->input_control->Redirect(URL_LOGIN);
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function Login | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function RegisterConfirm()
    {
        try {
            if (!empty($_SESSION[SESSION_REGISTER_CONFIRM_NAME])) {
                $confirm_token_from_database = $this->ActionModel->GetSessionRegisterConfirm(array($_SERVER['REMOTE_ADDR'], $_SESSION[SESSION_REGISTER_CONFIRM_NAME]));
                if ($confirm_token_from_database['result'] && $confirm_token_from_database['data']['date_register_confirm_expiry'] > date('Y-m-d H:i:s') && $confirm_token_from_database['data']['is_register_confirm_used'] == 0) {
                    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        $this->input_control->CheckUrl();
                        parent::LogView('Action-RegisterConfirm');
                        $bait_token = $this->action_control->GenerateBaitToken();
                        if (!empty($bait_token)) {
                            $this->web_data['confirm_token'] = $bait_token;
                        }
                        $a = strtotime($confirm_token_from_database['data']['date_register_confirm_expiry']);
                        $b = strtotime(date('Y-m-d H:i:s'));
                        if (!empty($a) && !empty($b)) {
                            $this->web_data['expiry_remain_minute'] = (int)(($a - $b) / 60);
                            $this->web_data['expiry_remain_second'] = ($a - $b) % 60;
                            $this->web_data['form_token'] = parent::SetCSRFToken('RegisterConfirm');
                            parent::GetView('Action/RegisterConfirm', $this->web_data);
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                        }
                    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $this->session_control->KillSession(SESSION_REGISTER_CONFIRM_NAME);
                        $checked_inputs = $this->input_control->CheckPostedInputs(array(
                            'token_1' => array('input' => isset($_POST['token_1']) ? $_POST['token_1'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_2' => array('input' => isset($_POST['token_2']) ? $_POST['token_2'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_3' => array('input' => isset($_POST['token_3']) ? $_POST['token_3'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_4' => array('input' => isset($_POST['token_4']) ? $_POST['token_4'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_5' => array('input' => isset($_POST['token_5']) ? $_POST['token_5'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_6' => array('input' => isset($_POST['token_6']) ? $_POST['token_6'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_7' => array('input' => isset($_POST['token_7']) ? $_POST['token_7'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'token_8' => array('input' => isset($_POST['token_8']) ? $_POST['token_8'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'length_control' => true, 'max_length' => 1, 'error_message_max_length' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN, 'preventxssforid' => true, 'length_limit' => 1, 'error_message_length_limit' => TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN),
                            'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                        ));
                        if (empty($checked_inputs['error_message'])) {
                            if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'RegisterConfirm')) {
                                $hashed_token = $this->action_control->HashedConfirmTokenBytes($checked_inputs['token_2'] . $checked_inputs['token_4'] . $checked_inputs['token_3'] . $checked_inputs['token_5'] . $checked_inputs['token_7'] . $checked_inputs['token_6'] . $checked_inputs['token_1'] . $checked_inputs['token_8']);
                                if ($this->ActionModel->UpdateSessionRegisterConfirm(array('is_register_confirm_used' => 1, 'date_register_confirm_used' => date('Y-m-d H:i:s'), 'id' => $confirm_token_from_database['data']['id']))['result'] && !empty($hashed_token)) {
                                    if ($confirm_token_from_database['data']['register_hashed_confirm_tokens'] == $hashed_token) {
                                        $register_confirm_user_from_database = $this->UserModel->GetUserByUserId('id', $confirm_token_from_database['data']['user_id']);
                                        if ($register_confirm_user_from_database['result'] && $this->UserModel->UpdateUser(array('email_confirmed' => 1, 'id' => $register_confirm_user_from_database['data']['id']))['result']) {
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
                    $this->input_control->Redirect(URL_LOGIN);
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function RegisterConfirm | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function RegisterCancel()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['cr'])) {
                $this->input_control->CheckUrl(array('cr'));
                parent::LogView('Action-RegisterCancel');
                $register_cancel_token = $this->input_control->CheckGETInput($_GET['cr']);
                if (!empty($register_cancel_token)) {
                    $register_cancel_from_database = $this->ActionModel->GetLinkRegisterCancelByLink($register_cancel_token);
                    if ($register_cancel_from_database['result'] && $register_cancel_from_database['data']['date_link_register_cancel_expiry'] > date('Y-m-d H:i:s') && $register_cancel_from_database['data']['is_link_register_cancel_used'] == 0) {
                        $register_cancel_user_from_database = $this->UserModel->GetUserByUserId('id,email_confirmed', $register_cancel_from_database['data']['user_id']);
                        if ($register_cancel_user_from_database['result']) {
                            if ($this->ActionModel->UpdateLinkRegisterCancel(array('is_link_register_cancel_used' => 1, 'date_link_register_cancel_used' => date('Y-m-d H:i:s'), 'ip_link_register_cancel_used' => $_SERVER['REMOTE_ADDR'], 'id' => $register_cancel_from_database['data']['id']))['result']) {
                                if ($register_cancel_user_from_database['data']['email_confirmed'] == 1) {
                                    $this->web_data['register_cancel_message'] = TR_NOTIFICATION_WARNING_REGISTER_CANCEL;
                                } else {
                                    if ($this->UserModel->UpdateUser(array('is_register_canceled' => 1, 'date_register_canceled' => date('Y-m-d H:i:s'), 'is_user_deleted' => 1, 'date_user_deleted' => date('Y-m-d H:i:s'), 'id' => $register_cancel_user_from_database['data']['id']))['result']) {
                                        $this->web_data['register_cancel_message'] =  TR_NOTIFICATION_SUCCESS_REGISTER_CANCEL;
                                    } else {
                                        $this->web_data['register_cancel_message'] = TR_NOTIFICATION_ERROR_REGISTER_CANCEL;
                                    }
                                }
                            } else {
                                $this->web_data['register_cancel_message'] = TR_NOTIFICATION_ERROR_REGISTER_CANCEL;
                            }
                            parent::GetView('Action/RegisterCancel', $this->web_data);
                        }
                    }
                }
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function RegisterCancel | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function Register()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::LogView('Action-Register');
                if (!empty($_SESSION[SESSION_WEB_DATA_NAME])) {
                    if (!empty($_SESSION[SESSION_WEB_DATA_NAME]['email']) && !empty($_SESSION[SESSION_WEB_DATA_NAME]['password']) && !empty($_SESSION[SESSION_WEB_DATA_NAME]['repassword'])) {
                        $this->web_data['email'] = $_SESSION[SESSION_WEB_DATA_NAME]['email'];
                        $this->web_data['password'] = $_SESSION[SESSION_WEB_DATA_NAME]['password'];
                        $this->web_data['repassword'] = $_SESSION[SESSION_WEB_DATA_NAME]['repassword'];
                    }
                    $this->session_control->KillSession(SESSION_WEB_DATA_NAME);
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
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'Register')) {
                        if ($checked_inputs['accept_terms'] == 'true') {
                            $password_salt = strtr(sodium_bin2base64(random_bytes(75), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => '2', '_' => '4'));
                            $salted_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['password']), PASSWORD_SECRET_KEY, true);
                            $salted_re_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['repassword']), PASSWORD_SECRET_KEY, true);
                            if (!empty($salted_password) && !empty($salted_re_password)) {
                                $hashed_password = password_hash($salted_password, PASSWORD_BCRYPT, $this->password_control->BcryptOptions());
                                if (password_verify($salted_re_password, $hashed_password)) {
                                    $captcha_timeout_from_database = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
                                    $captcha_timeout_not_error = false;
                                    if ($captcha_timeout_from_database['result']) {
                                        if ($captcha_timeout_from_database['data']['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                                            $a = strtotime($captcha_timeout_from_database['data']['date_captcha_timeout_expiry']);
                                            $b = strtotime(date('Y-m-d H:i:s'));
                                            if (!empty($a) && !empty($b)) {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_1 . ceil((int)(($a - $b) / 60)) . TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_2);
                                            } else {
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                            }
                                            $this->input_control->Redirect(URL_REGISTER);
                                        }
                                        $captcha_timeout_not_error = true;
                                    } elseif ($this->ActionModel->CreateCaptchaTimeOut(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'captcha_error_count' => 0, 'captcha_total_error_count' => 0))['result']) {
                                        $captcha_timeout_not_error = true;
                                    }
                                    if (!empty($_POST['h-captcha-response'])) {
                                        $captcha_check_result = $this->action_control->CheckCaptcha($_POST['h-captcha-response']);
                                        if ($captcha_check_result['result']) {
                                            if ($this->ActionModel->CreateLogCaptcha(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'success' => $captcha_check_result['success'], 'credit' => $captcha_check_result['credit']))['result'] && $captcha_timeout_not_error) {
                                                if ($captcha_timeout_from_database['result'] && $captcha_timeout_from_database['data']['captcha_error_count'] != 0) {
                                                    $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => 0, 'id' => $captcha_timeout_from_database['data']['id']));
                                                }
                                                $is_email_unique = $this->UserModel->IsUserEmailUnique($checked_inputs['email']);
                                                if ($is_email_unique['result']) {
                                                    if ($is_email_unique['data']['COUNT(id)'] > 0) {
                                                        $_SESSION[SESSION_LOGIN_MESSAGE_NAME] = TR_NOTIFICATION_ERROR_NOT_UNIQUE_EMAIL;
                                                        $this->input_control->Redirect(URL_LOGIN);
                                                    }
                                                } else {
                                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REGISTER);
                                                    $this->input_control->Redirect(URL_REGISTER);
                                                }
                                                $result_create_user = $this->UserModel->CreateUser(array('email' => $checked_inputs['email'], 'password' => $this->input_control->EncrypteData($hashed_password, PASSWORD_PEPPER), 'password_salt' => $password_salt, 'user_role' => 'yvifpf0vxxhiavvcdui6uqoq5pcssupgez7vkkelcjktngukspqmsegy9hfzpzvozvokivyokctjyjybd773epldgfjfovmbeoo0otltupjd4gdua0nmhs0v3vjgjzdruyuj3nhzhyji4qcnnamnmsy1mbj0a2vr4jhzynwmrcwycoaw9ii4ohrpui5cj0dgdlalcz8tiwjly03d6ob8p2pooo3u62xue2ifawarq9rzga8gtkjwnc9umo'));
                                                if ($result_create_user['result']) {
                                                    $register_session_confirm_token = $this->input_control->GenerateToken();
                                                    $cancel_register_link = $this->input_control->GenerateToken();
                                                    $register_confirm_token_bytes = $this->action_control->GenerateConfirmTokenBytes();
                                                    if (!empty($register_confirm_token_bytes)) {
                                                        $hashed_token_bytes = $this->action_control->HashedConfirmTokenBytes($register_confirm_token_bytes[4] . $register_confirm_token_bytes[7] . $register_confirm_token_bytes[0] . $register_confirm_token_bytes[1] . $register_confirm_token_bytes[6] . $register_confirm_token_bytes[3] . $register_confirm_token_bytes[2] . $register_confirm_token_bytes[5]);
                                                        if ($register_session_confirm_token['result'] && $cancel_register_link['result'] && !empty($hashed_token_bytes)) {
                                                            if ($this->ActionModel->CreateSessionRegisterConfirm(array('user_id' => $result_create_user['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'register_confirm_token' => $register_session_confirm_token['data'], 'register_hashed_confirm_tokens' => $hashed_token_bytes, 'date_register_confirm_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CONFIRM_EMAIL_TOKEN)), 'is_register_confirm_used' => 0))['result'] && $this->ActionModel->CreateLinkRegisterCancel(array('user_id' => $result_create_user['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'link_register_cancel' => $cancel_register_link['data'], 'date_link_register_cancel_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_CANCEL_REGISTER_LINK)), 'is_link_register_cancel_used' => 0))['result'] && $this->action_control->SendMail($this->input_control->DecodePreventXSS($checked_inputs['email']), BRAND . ' Üyelik Aktifleştirme', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Üyelik Aktifleştirme | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.confirm-container {width: 100%;margin-left: auto;margin-right: auto;padding-top: 20px;padding-bottom: 20px;}.confirm {display: inline-block;font-size: 20px;text-align: center;background-color: #ffffff;color: #000000;width: 10%;padding-top: 10px;padding-bottom: 10px;margin-right: 1%;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-text-url {color: #6466ec !important;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm-container {width: 70%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Hesabınızı Doğrulayın</p></div><div class="main"><div class="confirm-container"><span class="confirm">' . $register_confirm_token_bytes[2] . '</span><span class="confirm">' . $register_confirm_token_bytes[4] . '</span><span class="confirm">' . $register_confirm_token_bytes[0] . '</span><span class="confirm">' . $register_confirm_token_bytes[7] . '</span><span class="confirm">' . $register_confirm_token_bytes[1] . '</span><span class="confirm">' . $register_confirm_token_bytes[3] . '</span><span class="confirm">' . $register_confirm_token_bytes[6] . '</span><span class="confirm">' . $register_confirm_token_bytes[5] . '</span></div><p class="text-2">Üyeliğinizi aktif etmek için üstteki kodu girin</p><p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, üyelik işlemini iptal etmek için <a class="footer-text-url" href="' . URL . URL_REGISTER_CANCEL . '?cr=' . $cancel_register_link . '">tıklayınız.</a></p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $result_create_user['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'RegisterConfirm'))['result']) {
                                                                $_SESSION[SESSION_REGISTER_CONFIRM_NAME] = $register_session_confirm_token['data'];
                                                                $this->input_control->Redirect(URL_REGISTER_CONFIRM);
                                                            }
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
                                            if ($captcha_timeout_from_database['result']) {
                                                if ($captcha_timeout_from_database['data']['captcha_error_count'] == 0) {
                                                    $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'id' => $captcha_timeout_from_database['data']['id']));
                                                } elseif ($captcha_timeout_from_database['data']['captcha_error_count'] < 4) {
                                                    $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SHORT_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['data']['id']));
                                                } else {
                                                    $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_LONG_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['data']['id']));
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
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_REGISTER);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_EMPTY_REGISTER_TERMS);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $_SESSION[SESSION_WEB_DATA_NAME] = array('email' => $email, 'password' => $password, 'repassword' => $repassword);
                $this->input_control->Redirect(URL_REGISTER);
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function Register | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ForgotPassword()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::LogView('Action-ForgotPassword');
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
                        if ($captcha_timeout_from_database['result']) {
                            if ($captcha_timeout_from_database['data']['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                                $a = strtotime($captcha_timeout_from_database['data']['date_captcha_timeout_expiry']);
                                $b = strtotime(date('Y-m-d H:i:s'));
                                if (!empty($a) && !empty($b)) {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_1 . ceil((int)(($a - $b) / 60)) . TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_2);
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                }
                                $this->input_control->Redirect(URL_FORGOT_PASSWORD);
                            }
                            $captcha_timeout_not_error = true;
                        } elseif ($this->ActionModel->CreateCaptchaTimeOut(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'captcha_error_count' => 0, 'captcha_total_error_count' => 0))['result']) {
                            $captcha_timeout_not_error = true;
                        }
                        if (!empty($_POST['h-captcha-response'])) {
                            $captcha_check_result = $this->action_control->CheckCaptcha($_POST['h-captcha-response']);
                            if ($captcha_check_result['result']) {
                                if ($this->ActionModel->CreateLogCaptcha(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'success' => $captcha_check_result['success'], 'credit' => $captcha_check_result['credit']))['result'] && $captcha_timeout_not_error) {
                                    if ($captcha_timeout_from_database['result'] && $captcha_timeout_from_database['data']['captcha_error_count'] != 0) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => 0, 'id' => $captcha_timeout_from_database['data']['id']));
                                    }
                                    $forgot_password_user_from_database = $this->UserModel->GetUserByEmail('id', $checked_inputs['email']);
                                    if ($forgot_password_user_from_database['result']) {
                                        $forgot_password_token = $this->input_control->GenerateToken();
                                        if ($forgot_password_token['result'] && $this->ActionModel->CreateLinkForgotPassword(array('user_id' => $forgot_password_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'link_forgot_password' => $forgot_password_token['data'], 'date_link_forgot_password_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_FORGOT_PASSWORD_TOKEN)), 'is_link_forgot_password_used' => 0))['result'] && $this->action_control->SendMail($this->input_control->DecodePreventXSS($checked_inputs['email']), BRAND . ' Şifre Sıfırlama', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>Şifre Sıfırlama | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-2-link {color: #6466ec !important;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' Şifre Sıfırlama</p></div><div class="main"><p class="text-2">Şifrenizi sıfırlamak için linke <a class="text-2-link" href="' . URL . URL_RESET_PASSWORD . '?rp=' . $forgot_password_token['data'] . '">tıklayın.</a></p><p class="text-3">Şifre sıfırlama linkinin kullanım süresi ' . EXPIRY_FORGOT_PASSWORD_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, bu emaili önemsemeyin</p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $forgot_password_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'ForgotPassword'))['result']) {
                                            $_SESSION[SESSION_LOGIN_MESSAGE_NAME] = TR_NOTIFICATION_SUCCESS_FORGOT_PASSWORD;
                                            $this->input_control->Redirect(URL_LOGIN);
                                        }
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_FORGOT_PASSWORD);
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_FORGOT_PASSWORD_NO_EMAIL);
                                    }
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                }
                            } else {
                                if ($captcha_timeout_from_database['result']) {
                                    if ($captcha_timeout_from_database['data']['captcha_error_count'] == 0) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'id' => $captcha_timeout_from_database['data']['id']));
                                    } elseif ($captcha_timeout_from_database['data']['captcha_error_count'] < 4) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SHORT_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['data']['id']));
                                    } elseif ($captcha_timeout_from_database['data']['captcha_error_count'] >= 4) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_LONG_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['data']['id']));
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
                $this->input_control->Redirect(URL_FORGOT_PASSWORD);
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function ForgotPassword | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function ResetPassword()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['rp'])) {
                $this->input_control->CheckUrl(array('rp'));
                parent::LogView('Action-ResetPassword');
                $reset_password_url = $this->input_control->CheckGETInput($_GET['rp']);
                if (!empty($reset_password_url)) {
                    $forgot_password_from_database = $this->ActionModel->GetLinkForgotPassword($reset_password_url);
                    if ($forgot_password_from_database['result'] && $forgot_password_from_database['data']['date_link_forgot_password_expiry'] > date('Y-m-d H:i:s') && $forgot_password_from_database['data']['is_link_forgot_password_used'] == 0) {
                        if (empty($_SESSION[SESSION_FORGOT_PASSWORD_NAME])) {
                            $reset_password_token = $this->input_control->GenerateToken();
                            if ($this->ActionModel->UpdateLinkForgotPassword(array('is_link_forgot_password_used' => 1, 'date_link_forgot_password_used' => date('Y-m-d H:i:s'), 'id' => $forgot_password_from_database['data']['id']))['result'] && $reset_password_token['result'] && $this->ActionModel->CreateSessionResetPassword(array('link_forgot_password_id' => $forgot_password_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'reset_password_token' => $reset_password_token['data'], 'is_reset_password_used' => 0))['result']) {
                                $_SESSION[SESSION_FORGOT_PASSWORD_NAME] = $reset_password_token['data'];
                            }
                        } else {
                            if (!empty($_SESSION[SESSION_WEB_DATA_NAME])) {
                                if (!empty($_SESSION[SESSION_WEB_DATA_NAME]['password']) && !empty($_SESSION[SESSION_WEB_DATA_NAME]['repassword'])) {
                                    $this->web_data['password'] = $_SESSION[SESSION_WEB_DATA_NAME]['password'];
                                    $this->web_data['repassword'] = $_SESSION[SESSION_WEB_DATA_NAME]['repassword'];
                                }
                                $this->session_control->KillSession(SESSION_WEB_DATA_NAME);
                            }
                        }
                        $bait_token = $this->action_control->GenerateBaitToken();
                        if (!empty($bait_token)) {
                            $this->web_data['reset_password_token'] = $bait_token;
                        }
                        $this->web_data['form_token'] = parent::SetCSRFToken('ResetPassword');
                        parent::GetView('Action/ResetPassword', $this->web_data);
                    }
                }
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION[SESSION_FORGOT_PASSWORD_NAME])) {
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : '';
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'password' => array('input' => $password, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'repassword' => array('input' => $repassword, 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD, 'length_control' => true, 'min_length' => PASSWORD_MIN_LIMIT, 'error_message_min_length' => TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => TR_NOTIFICATION_ERROR_PATTERN_PASSWORD),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'ResetPassword')) {
                        $password_salt = strtr(sodium_bin2base64(random_bytes(75), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => '2', '_' => '4'));
                        $salted_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['password']), PASSWORD_SECRET_KEY, true);
                        $salted_re_password = hash_hmac('sha512', $password_salt . str_replace("\0", "", $checked_inputs['repassword']), PASSWORD_SECRET_KEY, true);
                        if (!empty($salted_password) && !empty($salted_re_password)) {
                            $hashed_password = password_hash($salted_password, PASSWORD_BCRYPT, $this->password_control->BcryptOptions());
                            if (password_verify($salted_re_password, $hashed_password)) {
                                $reset_password_from_database = $this->ActionModel->GetSessionResetPassword(array($_SERVER['REMOTE_ADDR'], $_SESSION[SESSION_FORGOT_PASSWORD_NAME]));
                                $this->session_control->KillSession($_SESSION[SESSION_FORGOT_PASSWORD_NAME]);
                                if ($reset_password_from_database['result'] && $reset_password_from_database['data']['is_reset_password_used'] == 0 && $this->ActionModel->UpdateSessionResetPassword(array('is_reset_password_used' => 1, 'date_reset_password_used' => date('Y-m-d H:i:s'), 'id' => $reset_password_from_database['data']['id']))['result']) {
                                    $link_forgot_password = $this->ActionModel->GetLinkForgotPasswordById($reset_password_from_database['data']['link_forgot_password_id']);
                                    if ($link_forgot_password['result'] && $link_forgot_password['data']['date_link_forgot_password_expiry'] > date('Y-m-d H:i:s') && $link_forgot_password['data']['is_link_forgot_password_used'] == 1 && $this->UserModel->UpdateUser(array('password' => $this->input_control->EncrypteData($hashed_password, PASSWORD_PEPPER), 'password_salt' => $password_salt, 'id' => $link_forgot_password['data']['user_id']))['result']) {
                                        $_SESSION[SESSION_LOGIN_MESSAGE_NAME] = TR_NOTIFICATION_SUCCESS_RESET_PASSWORD;
                                        $this->input_control->Redirect(URL_LOGIN);
                                    }
                                }
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_FORGOT_PASSWORD);
                                $this->input_control->Redirect(URL_FORGOT_PASSWORD);
                            } else {
                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_FORGOT_PASSWORD);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                $_SESSION[SESSION_WEB_DATA_NAME] = array('password' => $password, 'repassword' => $repassword);
                $this->input_control->Redirect(URL_RESET_PASSWORD);
            }
            $this->input_control->Redirect();
        } catch (\Throwable $th) {
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function ResetPassword | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
    function AdminLogin()
    {
        try {
            if ($_SERVER['REMOTE_ADDR'] === ADMIN_IP_ADDRESS) {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $this->input_control->CheckUrl();
                    parent::LogView('Action-AdminLogin');
                    $this->web_data['form_token'] = parent::SetCSRFToken('AdminLogin');
                    parent::GetView('Action/AdminLogin', $this->web_data);
                } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $checked_inputs = $this->input_control->CheckPostedInputs(array(
                        'email' => array('input' => isset($_POST['email']) ? $_POST['email'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_EMAIL, 'preventxss' => true),
                        'password' => array('input' => isset($_POST['password']) ? $_POST['password'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_EMPTY_PASSWORD),
                        'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => TR_NOTIFICATION_ERROR_CSRF, 'preventxssforid' => true)
                    ));
                    if (empty($checked_inputs['error_message'])) {
                        if (parent::CheckCSRFToken($checked_inputs['csrf_token'], 'AdminLogin')) {
                            $captcha_timeout_from_database = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
                            $captcha_timeout_not_error = false;
                            if ($captcha_timeout_from_database['result']) {
                                if ($captcha_timeout_from_database['data']['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                                    $a = strtotime($captcha_timeout_from_database['data']['date_captcha_timeout_expiry']);
                                    $b = strtotime(date('Y-m-d H:i:s'));
                                    if (!empty($a) && !empty($b)) {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_1 . ceil((int)(($a - $b) / 60)) . TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_2);
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                    }
                                    $this->input_control->Redirect(URL_ADMIN_LOGIN);
                                }
                                $captcha_timeout_not_error = true;
                            } elseif ($this->ActionModel->CreateCaptchaTimeOut(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'captcha_error_count' => 0, 'captcha_total_error_count' => 0))['result']) {
                                $captcha_timeout_not_error = true;
                            }
                            if (!empty($_POST['h-captcha-response'])) {
                                $captcha_check_result = $this->action_control->CheckCaptcha($_POST['h-captcha-response']);
                                if ($captcha_check_result['result']) {
                                    if ($this->ActionModel->CreateLogCaptcha(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'success' => $captcha_check_result['success'], 'credit' => $captcha_check_result['credit']))['result'] && $captcha_timeout_not_error) {
                                        if ($captcha_timeout_from_database['result'] && $captcha_timeout_from_database['data']['captcha_error_count'] != 0) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => 0, 'id' => $captcha_timeout_from_database['data']['id']));
                                        }
                                        $login_user_from_database = $this->UserModel->GetUserAdminByEmail('id,email_confirmed,password,password_salt,two_fa_enable,user_role,fail_access_count', $checked_inputs['email']);
                                        if ($login_user_from_database['result'] && $login_user_from_database['data']['user_role'] == ADMIN_ROLE_ID && $login_user_from_database['data']['email_confirmed'] == 1 && $login_user_from_database['data']['two_fa_enable'] == 1) {
                                            $salted_password = hash_hmac('sha512', $login_user_from_database['data']['password_salt'] . str_replace("\0", "", $checked_inputs['password']), PASSWORD_SECRET_KEY, true);
                                            $decrypted_hashed_password = $this->input_control->DecrypteData($login_user_from_database['data']['password'], PASSWORD_PEPPER);
                                            if ($decrypted_hashed_password['result'] && !empty($salted_password)) {
                                                $verified_pwd = password_verify($salted_password, $decrypted_hashed_password['data']);
                                            } else {
                                                $verified_pwd = false;
                                            }
                                            if ($verified_pwd) {
                                                $bcrypt_options = $this->password_control->BcryptOptions();
                                                if (password_needs_rehash($decrypted_hashed_password['data'], PASSWORD_BCRYPT, $bcrypt_options)) {
                                                    $new_hashed_password = password_hash($salted_password, PASSWORD_BCRYPT, $bcrypt_options);
                                                    $encrypted_hashed_password = $this->input_control->EncrypteData($new_hashed_password, PASSWORD_PEPPER);
                                                    $this->UserModel->UpdateUser(array('password' => $encrypted_hashed_password, 'id' => $login_user_from_database['data']['id']));
                                                }
                                                $session_two_fa_token = $this->input_control->GenerateToken();
                                                $session_two_fa_token_bytes = $this->action_control->GenerateConfirmTokenBytes();
                                                if (!empty($session_two_fa_token_bytes)) {
                                                    $hashed_token = $this->action_control->HashedConfirmTokenBytes($session_two_fa_token_bytes[4] . $session_two_fa_token_bytes[7] . $session_two_fa_token_bytes[0] . $session_two_fa_token_bytes[1] . $session_two_fa_token_bytes[6] . $session_two_fa_token_bytes[3] . $session_two_fa_token_bytes[2] . $session_two_fa_token_bytes[5]);
                                                    if (!empty($session_two_fa_token['result']) && !empty($hashed_token) && $this->ActionModel->CreateSessionTwoFA(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'two_fa_token' => $session_two_fa_token['data'], 'two_fa_hashed_tokens' => $hashed_token, 'remember_me' => 2, 'date_two_fa_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_TWO_FA_TOKEN)), 'is_two_fa_used' => 0))['result'] && $this->action_control->SendMail($this->input_control->DecodePreventXSS($checked_inputs['email']), BRAND . ' İki Aşamalı Doğrulama', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>İki Aşamalı Doğrulama | ' . BRAND . '</title><style>* {margin: 0px;padding: 0px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {font-family: sans-serif;background-color: #ffffff;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #ffffff;}.title {font-size: 40px;letter-spacing: 5px;color: #ffffff;margin-bottom: 20px;}.text-1 {font-size: 16px;line-height: 1.4;color: #ffffff;letter-spacing: 1px;}.main {background-color: #000000;text-align: center;}.confirm-container {width: 100%;margin-left: auto;margin-right: auto;padding-top: 20px;padding-bottom: 20px;}.confirm {display: inline-block;font-size: 20px;text-align: center;background-color: #ffffff;color: #000000;width: 10%;padding-top: 10px;padding-bottom: 10px;margin-right: 1%;}.text-2 {font-size: 15px;line-height: 1.4;color: #ffffff;padding-top: 20px;margin-bottom: 10px;padding-left: 10px;padding-right: 10px;border-top-width: 1px;border-top-style: solid;border-top-color: #ffffff;}.text-3 {font-size: 13px;line-height: 1.4;color: #ffffff;padding-left: 10px;padding-right: 10px;padding-bottom: 20px;}.footer {background-color: #f3f3f398;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.footer-text {font-size: 13px;line-height: 1.4;color: #000000;margin-bottom: 20px;}.footer-url {font-size: 12px;color: #000000;margin-right: 10px;}.footer-date {font-size: 12px;color: #000000;margin-left: 10px;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm-container {width: 70%;}.confirm {padding-top: 20px;padding-bottom: 20px;}}</style></head><body><div class="container"><div class="header"><h1 class="title">BB</h1><p class="text-1">' . BRAND . ' İki Aşamalı Doğrulama</p></div><div class="main"><div class="confirm-container"><span class="confirm">' . $session_two_fa_token_bytes[2] . '</span><span class="confirm">' . $session_two_fa_token_bytes[4] . '</span><span class="confirm">' . $session_two_fa_token_bytes[0] . '</span><span class="confirm">' . $session_two_fa_token_bytes[7] . '</span><span class="confirm">' . $session_two_fa_token_bytes[1] . '</span><span class="confirm">' . $session_two_fa_token_bytes[3] . '</span><span class="confirm">' . $session_two_fa_token_bytes[6] . '</span><span class="confirm">' . $session_two_fa_token_bytes[5] . '</span></div><p class="text-2">Giriş yapmak için üstteki kodu girin</p><p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_TWO_FA_TOKEN_MINUTE . ' dakikadır</p></div><footer class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, hemen ' . BRAND . ' hesabınızın şifresini değiştirin</p><a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a><span class="footer-date">' . date('d/m/Y H:i:s') . '</span></footer></div></body></html>') && $this->ActionModel->CreateLogEmailSent(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'email_type' => 'AdminTwoFA'))['result']) {
                                                        $_SESSION[SESSION_ADMIN_TWO_FA_NAME] = true;
                                                        $_SESSION[SESSION_TWO_FA_NAME] = $session_two_fa_token['data'];
                                                        $this->input_control->Redirect(URL_TWO_FA);
                                                    }
                                                }
                                                $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN);
                                                $this->input_control->Redirect(URL_ADMIN_LOGIN);
                                            } else {
                                                $this->ActionModel->CreateLogLogin(array('user_id' => $login_user_from_database['data']['id'], 'user_ip' => $_SERVER['REMOTE_ADDR'], 'login_success' => 0));
                                                $this->UserModel->UpdateUser(array('fail_access_count' => $login_user_from_database['data']['fail_access_count'] + 1, 'date_last_fail_access_attempt' => date('Y-m-d H:i:s'), 'id' => $login_user_from_database['data']['id']));
                                            }
                                        } else {
                                            $this->ActionModel->CreateLogLoginEmailFail(array('user_ip' => $_SERVER['REMOTE_ADDR']));
                                        }
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_LOGIN_INFORMATIONS);
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_DATABASE);
                                    }
                                } else {
                                    if ($captcha_timeout_from_database['result']) {
                                        if ($captcha_timeout_from_database['data']['captcha_error_count'] == 0) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'id' => $captcha_timeout_from_database['data']['id']));
                                        } elseif ($captcha_timeout_from_database['data']['captcha_error_count'] < 4) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SHORT_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['data']['id']));
                                        } elseif ($captcha_timeout_from_database['data']['captcha_error_count'] >= 4) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array('captcha_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'captcha_total_error_count' => $captcha_timeout_from_database['data']['captcha_error_count'] + 1, 'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_LONG_TIMEOUT_CAPTCHA)), 'id' => $captcha_timeout_from_database['data']['id']));
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
            if ($this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ActionController function AdminLogin | ' . $th))['result']) {
                $this->input_control->Redirect(URL_EXCEPTION);
            } else {
                $this->input_control->Redirect(URL_SHUTDOWN);
            }
        }
    }
}
