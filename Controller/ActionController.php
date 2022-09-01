<?php
class ActionController extends Controller
{
    function __construct()
    {
        parent::__construct();
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
        //         if (!empty($session_authentication_from_database) && $session_authentication_from_database['date_session_authentication_expiry'] > date('Y-m-d H:i:s') && $session_authentication_from_database['session_authentication_is_logout'] == 0) {
        //             $authenticated_user_from_database = $this->UserModel->GetUser('id', $session_authentication_from_database['user_id']);
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
        //         if (!empty($cookie_authentication_from_database) && $cookie_authentication_from_database['date_cookie_authentication_expiry'] > date('Y-m-d H:i:s') && $cookie_authentication_from_database['cookie_authentication_is_logout'] == 0) {
        //             try {
        //                 $cookie_authentication_token1 = hash_hmac('SHA512', substr($checked_cookie_authentication, 247, 253), $cookie_authentication_from_database['cookie_authentication_salt'], false);
        //                 if (hash_equals($cookie_authentication_from_database['cookie_authentication_token1'], $cookie_authentication_token1)) {
        //                     $authenticated_user_from_database = $this->UserModel->GetUser('id', $cookie_authentication_from_database['user_id']);
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
    }
    function AdminLogin()
    {
        if ($_SERVER['REMOTE_ADDR'] === ADMIN_IP_ADDRESS) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->input_control->CheckUrl();
                parent::LogView('Action/AdminLogin');
                $result_set_csrf_token = parent::SetCSRFToken('AdminLogin');
                if ($result_set_csrf_token == false) {
                    parent::GetView('Error/NotResponse');
                } else {
                    $this->web_data['form_token'] = $result_set_csrf_token;
                    parent::GetView('Action/AdminLogin', $this->web_data);
                }
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->LoginPost('admin');
            }
        }
        // if (isset($_SESSION[SESSION_NOTIFICATION_NAME])) {
        //     unset($_SESSION[SESSION_NOTIFICATION_NAME]);
        // }
        $this->input_control->Redirect();
    }
    function Login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->input_control->CheckUrl();
            parent::LogView('Action/Login');
            $this->web_data['genders'] = parent::GetGenders('gender_name,gender_url');
            if (!empty($_GET['yonlendir'])) {
                $checked_redirect_location = $this->input_control->CheckGETInput($_GET['yonlendir']);
                if (!empty($checked_redirect_location)) {
                    $this->web_data['redirect_location'] = $checked_redirect_location;
                }
            }
            $result_set_csrf_token = parent::SetCSRFToken('Login');
            if ($result_set_csrf_token == false) {
                parent::GetView('Error/NotResponse');
            } else {
                $this->web_data['form_token'] = $result_set_csrf_token;
                parent::GetView('Action/Login', $this->web_data);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->LoginPost('user');
        }
        $this->input_control->Redirect();
    }










    function LoginWithSession(string $login_session_user_id, string $login_session_auth)
    {
        $session_authentication_token = $this->session_control->GenerateSessionAuthToken();
        $result_session_auth = $this->ActionModel->CreateSessionAuth(array(
            'user_id' => $login_session_user_id,
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'session_authentication_token' => $session_authentication_token,
            'date_session_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_SESSION_AUTH))
        ));
        $result_user_lastlogin = $this->ActionModel->UpdateUser(array(
            'date_last_login' => date('Y-m-d H:i:s'),
            'id' => $login_session_user_id
        ));
        $result_log_login = $this->ActionModel->CreateLogLogin(array(
            'account_id' => $login_session_user_id,
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'login_success' => 1
        ));
        if ($result_session_auth['result'] == 'Created' && $result_user_lastlogin == 'Updated' && $result_log_login['result'] == 'Created') {
            $_SESSION[SESSION_AUTHENTICATION_NAME] = $session_authentication_token;
            if ($login_session_auth == 'admin') {
                $this->input_control->Redirect(URL_ADMININDEX);
            } else {
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
        } else {
            $this->notification_control->SetNotification('DANGER', DATABASE_ERROR);
            if ($login_session_auth == 'admin') {
                $this->input_control->Redirect(URL_ADMIN_LOGIN);
            } else {
                $this->input_control->Redirect(URL_LOGIN);
            }
        }
    }
    function LoginWithCookie(string $login_cookie_user_id)
    {
        $cookie_token = $this->cookie_control->GenerateCookieAuthToken();
        $cookie_authentication_salt = $this->cookie_control->GenerateCookieSalt();
        $cookie_authentication_token1 = hash_hmac('SHA512', substr($cookie_token, 247, 253), $cookie_authentication_salt, false);
        $cookie_authentication_token2 = substr($cookie_token, 0, 247);
        $result_cookie_auth = $this->ActionModel->CreateCookieAuth(array(
            'user_id' => $login_cookie_user_id,
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'cookie_authentication_token1' => $cookie_authentication_token1,
            'cookie_authentication_token2' => $cookie_authentication_token2,
            'cookie_authentication_salt' => $cookie_authentication_salt,
            'date_cookie_authentication_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_COOKIE_AUTH))
        ));
        $result_user_lastlogin = $this->ActionModel->UpdateUser(array(
            'date_last_login' => date('Y-m-d H:i:s'),
            'id' => $login_cookie_user_id
        ));
        $result_log_login = $this->ActionModel->CreateLogLogin(array(
            'account_id' => $login_cookie_user_id,
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'login_success' => 1
        ));
        if ($result_cookie_auth['result'] == 'Created' && $result_user_lastlogin == 'Updated' && $result_log_login['result'] == 'Created') {
            $result_auth_cookie_set = $this->cookie_control->SetCookie(COOKIE_AUTHENTICATION_NAME, $cookie_token, time() + (EXPIRY_COOKIE_AUTH), SESSION_PATH, SESSION_DOMAIN, SESSION_SECURE, SESSION_HTTP_ONLY, SESSION_SAMESITE);
            if ($result_auth_cookie_set) {
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
        $this->notification_control->SetNotification('DANGER', DATABASE_ERROR);
        $this->input_control->Redirect(URL_LOGIN);
    }
    function SendVerifyTokenWithEmail($verify_token_user_id, $verify_token_user_email, $verify_token_type, $verify_token_auth, $verify_token_email_type, $verify_token_remember_me = 0)
    {
        $random_tokens = $this->action_control->GenerateRandomByteConfirmToken();
        $hashed_tokens = $this->action_control->HashRandomBytes($random_tokens[4] . $random_tokens[7] . $random_tokens[0] . $random_tokens[1] . $random_tokens[6] . $random_tokens[3] . $random_tokens[2] . $random_tokens[5]);
        $verify_token = $this->action_control->GenerateVerifyToken();
        $checked_email = $this->input_control->CheckEmail($verify_token_user_email);
        if ($verify_token_type == 'two_fa') {
            $token_time_remain = EXPIRY_TWO_FA_TOKEN;
        } elseif ($verify_token_type == 'confirm_email') {
            $token_time_remain = EXPIRY_CONFIRM_EMAIL_TOKEN;
        }
        $result_verify_token_create = $this->ActionModel->CreateVerifyToken(array(
            'user_id' => $verify_token_user_id,
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'verify_token' => $verify_token,
            'verify_hashed_tokens' => $hashed_tokens,
            'date_verify_token_expiry' => date('Y-m-d H:i:s', time() + ($token_time_remain)),
            'is_verify_token_used' => 0,
            'verify_token_auth' => $verify_token_auth,
            'verify_token_type' => $verify_token_type,
            'verify_token_csrf_type' => $verify_token_email_type,
            'verify_token_remember_me' => $verify_token_remember_me
        ));
        if ($result_verify_token_create['result'] == 'Created' && !is_null($checked_email)) {
            if ($verify_token_type == 'two_fa') {
                $result_email = $this->action_control->SendMail($checked_email, BRAND . ' İki Aşamalı Doğrulama', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>' . BRAND . ' İki Aşamalı Doğrulama</title><style>* {margin: 0;padding: 0;border: 0;box-sizing: border-box;}html {font-size: 10px;}body {font-family: sans-serif;background-color: #aaaaaa;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.logo {border-width: 3px;border-style: solid;border-color: #ffffff;border-radius: 50%;padding: 10px;margin-bottom: 20px;}.main-title {font-size: 18px;color: #ffffff;letter-spacing: 1px;margin-bottom: 20px;}.main-text {font-size: 14px;color: #ffffff;margin-bottom: 20px;}.confirm-container {width: 100%;text-align: center;}.confirm {display: inline-block;font-size: 20px;text-align: center;border-width: 2px;border-style: solid;border-color: #000000;background-color: #ffffff;color: #000000;width: 12%;padding-top: 20px;padding-bottom: 20px;}.main-text-2 {font-size: 14px;color: #ffffff;margin-top: 20px;}.footer {padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;background-color: #5155d3;}.footer-text {font-size: 13px;color: #000000;text-align: center;margin-bottom: 20px;}.footer-link {font-size: 13px;color: #aaaaaa !important;}.footer-url {font-size: 12px;color: #ffffff !important;float: left;}.footer-date {color: #ffffff;font-size: 12px;float: right;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm {width: 10%;}}</style></head><body><div class="container"><div class="header"><img class="logo" src="' . URL . '" alt="' . BRAND . '"><h1 class="main-title">' . BRAND . ' İki Aşamalı Doğrulama</h1><p class="main-text">Giriş yapmak için alttaki kodu girin.</p><div class="confirm-container"><span class="confirm">' . $random_tokens[2] . '</span><span class="confirm">' . $random_tokens[4] . '</span><span class="confirm">' . $random_tokens[0] . '</span><span class="confirm">' . $random_tokens[7] . '</span><span class="confirm">' . $random_tokens[1] . '</span><span class="confirm">' . $random_tokens[3] . '</span><span class="confirm">' . $random_tokens[6] . '</span><span class="confirm">' . $random_tokens[5] . '</span><p class="main-text-2">Doğrulama kodunun kullanım süresi ' . EXPIRY_TWO_FA_TOKEN_MINUTE . ' dakikadır.</p></div></div><div class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, hemen ' . BRAND . ' hesabınızın şifresini değiştirin.</p><a class="footer-url" href="' . URL . '">' . URL . '</a><span class="footer-date">' . date('d-m-Y H:i:s') . '</span></div></div></body></html>');
            } elseif ($verify_token_type == 'confirm_email') {
                if ($verify_token_email_type == 'RegisterConfirmEmail') {
                    $cancel_register_token = $this->action_control->GenerateCancelRegisterToken();
                    $result_verify_link_created = $this->ActionModel->CreateVerifyLink(array(
                        'user_id' => $verify_token_user_id,
                        'user_ip' => $_SERVER['REMOTE_ADDR'],
                        'verify_link' => $cancel_register_token,
                        'verify_link_expiry_date' => date('Y-m-d H:i:s', time() + (EXPIRY_CANCEL_REGISTER_LINK)),
                        'verify_link_type' => 'CancelRegister',
                        'is_verify_link_used' => 0
                    ));
                    if ($result_verify_link_created['result'] == 'Created') {
                        $result_email = $this->action_control->SendMail($checked_email, BRAND . ' Üyelik Aktifleştirme', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>' . BRAND . ' Üyelik Aktifleştirme</title><style>* {margin: 0;padding: 0;border: 0;box-sizing: border-box;}html {font-size: 10px;}body {font-family: sans-serif;background-color: #aaaaaa;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.logo {border-width: 3px;border-style: solid;border-color: #ffffff;border-radius: 50%;padding: 10px;margin-bottom: 20px;}.main-title {font-size: 18px;color: #ffffff;letter-spacing: 1px;margin-bottom: 20px;}.main-text {font-size: 14px;color: #ffffff;margin-bottom: 20px;}.confirm-container {width: 100%;text-align: center;}.confirm {display: inline-block;font-size: 20px;text-align: center;border-width: 2px;border-style: solid;border-color: #000000;background-color: #ffffff;color: #000000;width: 12%;padding-top: 20px;padding-bottom: 20px;}.main-text-2 {font-size: 14px;color: #ffffff;margin-top: 20px;}.footer {padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;background-color: #5155d3;}.footer-text {font-size: 13px;color: #000000;text-align: center;margin-bottom: 20px;}.footer-text-url{font-size: 13px;color: #ffffff!important;}.footer-link {font-size: 13px;color: #aaaaaa !important;}.footer-url {font-size: 12px;color: #ffffff !important;float: left;}.footer-date {color: #ffffff;font-size: 12px;float: right;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm {width: 10%;}}</style></head><body><div class="container"><div class="header"><img class="logo" src="' . URL  . '" alt="' . BRAND . '"><h1 class="main-title">' . BRAND . ' Hesabınızı Doğrulayın</h1><p class="main-text">Üyeliğinizi aktif etmek için alttaki kodu girin.</p><div class="confirm-container"><span class="confirm">' . $random_tokens[2] . '</span><span class="confirm">' . $random_tokens[4] . '</span><span class="confirm">' . $random_tokens[0] . '</span><span class="confirm">' . $random_tokens[7] . '</span><span class="confirm">' . $random_tokens[1] . '</span><span class="confirm">' . $random_tokens[3] . '</span><span class="confirm">' . $random_tokens[6] . '</span><span class="confirm">' . $random_tokens[5] . '</span><p class="main-text-2">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır.</p></div></div><div class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, üyelik işlemini iptal etmek için <a class="footer-text-url" href="' . URL . URL_VERIFY_LINK . '?cr=' . $cancel_register_token . '">tıklayınız.</a></p><a class="footer-url" href="' . URL . '">' . URL . '</a><span class="footer-date">' . date('d-m-Y H:i:s') . '</span></div></div></body></html>');
                    } else {
                        $result_email = null;
                    }
                } else {
                    $result_email = $this->action_control->SendMail($checked_email, BRAND . ' Üyelik Aktifleştirme', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>' . BRAND . ' Üyelik Aktifleştirme</title><style>* {margin: 0;padding: 0;border: 0;box-sizing: border-box;}html {font-size: 10px;}body {font-family: sans-serif;background-color: #aaaaaa;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.logo {border-width: 3px;border-style: solid;border-color: #ffffff;border-radius: 50%;padding: 10px;margin-bottom: 20px;}.main-title {font-size: 18px;color: #ffffff;letter-spacing: 1px;margin-bottom: 20px;}.main-text {font-size: 14px;color: #ffffff;margin-bottom: 20px;}.confirm-container {width: 100%;text-align: center;}.confirm {display: inline-block;font-size: 20px;text-align: center;border-width: 2px;border-style: solid;border-color: #000000;background-color: #ffffff;color: #000000;width: 12%;padding-top: 20px;padding-bottom: 20px;}.main-text-2 {font-size: 14px;color: #ffffff;margin-top: 20px;}.footer {padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;background-color: #5155d3;}.footer-link {font-size: 13px;color: #aaaaaa !important;}.footer-url {font-size: 12px;color: #ffffff !important;float: left;}.footer-date {color: #ffffff;font-size: 12px;float: right;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}.confirm {width: 10%;}}</style></head><body><div class="container"><div class="header"><img class="logo" src="' . URL  . '" alt="' . BRAND . '"><h1 class="main-title">' . BRAND . ' Hesabınızı Doğrulayın</h1><p class="main-text">Üyeliğinizi aktif etmek için alttaki kodu girin.</p><div class="confirm-container"><span class="confirm">' . $random_tokens[2] . '</span><span class="confirm">' . $random_tokens[4] . '</span><span class="confirm">' . $random_tokens[0] . '</span><span class="confirm">' . $random_tokens[7] . '</span><span class="confirm">' . $random_tokens[1] . '</span><span class="confirm">' . $random_tokens[3] . '</span><span class="confirm">' . $random_tokens[6] . '</span><span class="confirm">' . $random_tokens[5] . '</span><p class="main-text-2">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır.</p></div></div><div class="footer"><a class="footer-url" href="' . URL . '">' . URL . '</a><span class="footer-date">' . date('d-m-Y H:i:s') . '</span></div></div></body></html>');
                }
            }
            if (!is_null($result_email)) {
                $this->ActionModel->CreateLogEmailSent(array(
                    'user_id' => $verify_token_user_id,
                    'user_ip' => $_SERVER['REMOTE_ADDR'],
                    'email_type' => $verify_token_email_type
                ));
                $_SESSION[SESSION_VERIFY_TOKEN] = $verify_token;
                $this->input_control->Redirect(URL_VERIFY_TOKEN);
            }
        }
        $this->notification_control->SetNotification('DANGER', DATABASE_ERROR);
        $this->input_control->Redirect(URL_LOGIN);
    }
    function VerifyToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_SESSION[SESSION_VERIFY_TOKEN])) {
            $verify_token_from_db = $this->ActionModel->GetVerifyToken(array($_SESSION[SESSION_VERIFY_TOKEN], $_SERVER['REMOTE_ADDR']));
            if (!empty($verify_token_from_db) && $verify_token_from_db['date_verify_token_expiry'] > date('Y-m-d H:i:s') && $verify_token_from_db['is_verify_token_used'] == 0) {
                $this->web_data['verify_token'] = $this->action_control->GenerateBaitToken();
                $this->web_data['verify_token_type'] = $verify_token_from_db['verify_token_type'];
                $this->web_data['token_time_remain'] = (strtotime($verify_token_from_db['date_verify_token_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60;
                // $this->SetCSRFTokenAndGetView('VerifyToken', 'VerifyToken' . $verify_token_from_db['verify_token_csrf_type']);
            } else {
                unset($_SESSION[SESSION_VERIFY_TOKEN]);
                $this->notification_control->SetNotification('DANGER', VERIFY_TOKEN_TIMEOUT_ERROR);
                $this->input_control->Redirect(URL_LOGIN);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_token-submit']) && !empty($_SESSION[SESSION_VERIFY_TOKEN])) {
            $verify_token_from_db = $this->ActionModel->GetVerifyToken(array($_SESSION[SESSION_VERIFY_TOKEN], $_SERVER['REMOTE_ADDR']));
            unset($_SESSION[SESSION_VERIFY_TOKEN]);
            if (!empty($verify_token_from_db) && $verify_token_from_db['date_verify_token_expiry'] > date('Y-m-d H:i:s') && $verify_token_from_db['is_verify_token_used'] == 0) {
                if ($verify_token_from_db['verify_token_type'] == 'two_fa') {
                    $input_error_message = ERROR_MESSAGE_EMPTY_TWO_FA_TOKEN;
                } elseif ($verify_token_from_db['verify_token_type'] == 'confirm_email') {
                    $input_error_message = ERROR_MESSAGE_EMPTY_CONFIRM_EMAIL_TOKEN;
                } else {
                    $this->input_control->Redirect();
                }
                $checked_inputs = $this->input_control->CheckPostedInputs(array(
                    'token_1' => array('input' => isset($_POST['token_1']) ? $_POST['token_1'] : '', 'error_message_empty' => $input_error_message, 'preventxss' => true),
                    'token_2' => array('input' => isset($_POST['token_2']) ? $_POST['token_2'] : '', 'error_message_empty' => $input_error_message, 'preventxss' => true),
                    'token_3' => array('input' => isset($_POST['token_3']) ? $_POST['token_3'] : '', 'error_message_empty' => $input_error_message, 'preventxss' => true),
                    'token_4' => array('input' => isset($_POST['token_4']) ? $_POST['token_4'] : '', 'error_message_empty' => $input_error_message, 'preventxss' => true),
                    'token_5' => array('input' => isset($_POST['token_5']) ? $_POST['token_5'] : '', 'error_message_empty' => $input_error_message, 'preventxss' => true),
                    'token_6' => array('input' => isset($_POST['token_6']) ? $_POST['token_6'] : '', 'error_message_empty' => $input_error_message, 'preventxss' => true),
                    'token_7' => array('input' => isset($_POST['token_7']) ? $_POST['token_7'] : '', 'error_message_empty' => $input_error_message, 'preventxss' => true),
                    'token_8' => array('input' => isset($_POST['token_8']) ? $_POST['token_8'] : '', 'error_message_empty' => $input_error_message, 'preventxss' => true),
                    'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
                ));
                if (empty($checked_inputs['error_message'])) {
                    $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'VerifyToken' . $verify_token_from_db['verify_token_csrf_type']);
                    if ($result_check_csrf_token == true) {
                        $this->ActionModel->UpdateVerifyToken(array(
                            'is_verify_token_used' => 1,
                            'date_verify_token_used' => date('Y-m-d H:i:s'),
                            'id' => $verify_token_from_db['id']
                        ));
                        $posted_hashed_tokens = $this->action_control->HashRandomBytes($checked_inputs['token_2'] . $checked_inputs['token_4'] . $checked_inputs['token_3'] . $checked_inputs['token_5'] . $checked_inputs['token_7'] . $checked_inputs['token_6'] . $checked_inputs['token_1'] . $checked_inputs['token_8']);
                        if (!empty($posted_hashed_tokens) && $verify_token_from_db['verify_hashed_tokens'] == $posted_hashed_tokens) {
                            $verify_token_user_from_db = $this->ActionModel->GetUserById('id', $verify_token_from_db['user_id']);
                            if (!empty($verify_token_user_from_db)) {
                                if ($verify_token_from_db['verify_token_type'] == 'two_fa') {
                                    if ($verify_token_from_db['verify_token_auth'] == 'admin') {
                                        $this->LoginWithSession($verify_token_user_from_db['id'], 'admin');
                                    } else {
                                        if ($verify_token_from_db['verify_token_remember_me'] == 1) {
                                            $this->LoginWithCookie($verify_token_user_from_db['id']);
                                        } else {
                                            $this->LoginWithSession($verify_token_user_from_db['id'], 'user');
                                        }
                                    }
                                } elseif ($verify_token_from_db['verify_token_type'] == 'confirm_email') {
                                    $result_email_confirm = $this->ActionModel->UpdateUser(array(
                                        'email_confirmed' => 1,
                                        'id' => $verify_token_user_from_db['id']
                                    ));
                                    if ($result_email_confirm == 'Updated') {
                                        $_SESSION[SESSION_MASSAGE] = CONFIRM_EMAIL_SUCCESS;
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', DATABASE_ERROR);
                                    }
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', VERIFY_TOKEN_ERROR);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', VERIFY_TOKEN_WRONG_TOKEN_ERROR);
                        }
                    }
                } else {
                    $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                }
                if ($verify_token_from_db['verify_token_auth'] == 'admin') {
                    $this->input_control->Redirect(URL_ADMIN_LOGIN);
                } else {
                    $this->input_control->Redirect(URL_LOGIN);
                }
            } else {
                $this->notification_control->SetNotification('DANGER', VERIFY_TOKEN_TIMEOUT_ERROR);
                $this->input_control->Redirect(URL_LOGIN);
            }
        }
        $this->session_control->KillSession();
        $this->input_control->Redirect();
    }
    function VerifyLink()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['cr'])) {
            $cancel_register_url = $this->input_control->IsString($_GET['cr']);
            if (!is_null($cancel_register_url)) {
                $verify_link_from_db = $this->ActionModel->GetVerifyLink(array(urlencode($cancel_register_url), $_SERVER['REMOTE_ADDR']));
                if (!empty($verify_link_from_db) && $verify_link_from_db['verify_link_expiry_date'] > date('Y-m-d H:i:s') && $verify_link_from_db['is_verify_link_used'] == 0) {
                    if ($verify_link_from_db['verify_link_type'] == 'CancelRegister') {
                        $web_data['verify_link_title'] = CANCEL_REGISTER_TITLE;
                        $verify_link_user_from_db = $this->ActionModel->GetUserById('id,is_shopped', $verify_link_from_db['user_id']);
                        if (!empty($verify_link_user_from_db)) {
                            $this->ActionModel->UpdateVerifyLink(array(
                                'is_verify_link_used' => 1,
                                'date_verify_link_used' => date('Y-m-d H:i:s'),
                                'id' => $verify_link_from_db['id']
                            ));
                            if ($verify_link_user_from_db['is_shopped'] == 1) {
                                $this->web_data['verify_link_msg'] = CANCEL_REGISTER_ERROR_IS_SHOPPED;
                            } else {
                                $result_update_cancel_register_user = $this->ActionModel->UpdateUser(array(
                                    'is_user_deleted' => 1,
                                    'date_user_deleted' => date('Y-m-d H:i:s'),
                                    'id' => $verify_link_user_from_db['id']
                                ));
                                if ($result_update_cancel_register_user == 'Updated') {
                                    $this->web_data['verify_link_msg'] =  CANCEL_REGISTER_SUCCESS;
                                } else {
                                    $this->web_data['verify_link_msg'] = DATABASE_ERROR;
                                }
                            }
                        } else {
                            $this->web_data['verify_link_msg'] = CANCEL_REGISTER_NOT_FOUND;
                        }
                        parent::GetView('Action/VerifyLink', $this->web_data);
                    }
                }
            }
        }
        $this->input_control->Redirect();
    }
    function LoginPost(string $login_type)
    {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $checked_inputs = $this->input_control->CheckPostedInputs(array(
            'email' => array('input' => $email, 'error_message_empty' => ERROR_MESSAGE_EMPTY_EMAIL, 'preventxss' => true),
            'password' => array('input' => $password, 'error_message_empty' => ERROR_MESSAGE_EMPTY_PASSWORD, 'preventxss' => true),
            'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
        ));
        if (empty($checked_inputs['error_message'])) {
            if ($login_type == 'admin') {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'AdminLogin');
                if ($result_check_csrf_token == false) {
                    $this->input_control->Redirect(URL_ADMIN_LOGIN);
                }
            } else {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'Login');
                if ($result_check_csrf_token == false) {
                    $this->input_control->Redirect(URL_LOGIN . (isset($_POST['redirect_location']) ? '?yonlendir='.$_POST['redirect_location'] : ''));
                }
            }
            $captcha_timeout_from_db = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
            if (empty($captcha_timeout_from_db)) {
                $this->ActionModel->CreateCaptchaTimeOut(array(
                    'user_ip' => $_SERVER['REMOTE_ADDR'],
                    'captcha_error_count' => 0,
                    'captcha_total_error_count' => 0
                ));
            } else {
                $captcha_timeout_error = false;
                if ($captcha_timeout_from_db['captcha_banned'] == 1) {
                    $captcha_timeout_error = true;
                    $this->notification_control->SetNotification('DANGER', CAPTCHA_BANNES_ERROR);
                } elseif ($captcha_timeout_from_db['captcha_total_error_count'] > 15) {
                    $captcha_timeout_error = true;
                    $this->notification_control->SetNotification('DANGER', CAPTCHA_BANNES_ERROR);
                } elseif ($captcha_timeout_from_db['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                    $captcha_timeout_error = true;
                    $captcha_remain_timeout = (int)((strtotime($captcha_timeout_from_db['date_captcha_timeout_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60);
                    if ($captcha_remain_timeout == 0) {
                        $captcha_remain_timeout = 1;
                    }
                    $this->notification_control->SetNotification('DANGER', CAPTCHA_TIMEOUT_1 . $captcha_remain_timeout . CAPTCHA_TIMEOUT_2);
                }
                if ($captcha_timeout_error) {
                    if ($login_type == 'admin') {
                        $this->input_control->Redirect(URL_ADMIN_LOGIN);
                    } else {
                        $this->input_control->Redirect(URL_LOGIN . (isset($_POST['redirect_location']) ? '?yonlendir='.$_POST['redirect_location'] : ''));
                    }
                }
            }
            $captcha_respone = isset($_POST['h-captcha-response']) ? $_POST['h-captcha-response'] : '';
            if (!empty($captcha_respone)) {
                $result_captcha = $this->action_control->CheckCaptcha($captcha_respone);
                if ($result_captcha === false) {
                    if (!empty($captcha_timeout_from_db)) {
                        if ($captcha_timeout_from_db['captcha_error_count'] < 1) {
                            $this->ActionModel->UpdateCaptchaTimeOut(array(
                                'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                'id' => $captcha_timeout_from_db['id']
                            ));
                        } elseif ($captcha_timeout_from_db['captcha_error_count'] == 1) {
                            $this->ActionModel->UpdateCaptchaTimeOut(array(
                                'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (TIMEOUT_SHORT_CAPTCHA)),
                                'id' => $captcha_timeout_from_db['id']
                            ));
                        } elseif ($captcha_timeout_from_db['captcha_error_count'] >= 9) {
                            $this->ActionModel->UpdateCaptchaTimeOut(array(
                                'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                'captcha_banned' => 1,
                                'id' => $captcha_timeout_from_db['id']
                            ));
                        } elseif ($captcha_timeout_from_db['captcha_error_count'] >= 4) {
                            $this->ActionModel->UpdateCaptchaTimeOut(array(
                                'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (TIMEOUT_LONG_CAPTCHA)),
                                'id' => $captcha_timeout_from_db['id']
                            ));
                        }
                    }
                    $this->notification_control->SetNotification('DANGER', CAPTCHA_ERROR);
                    if ($login_type == 'admin') {
                        $this->input_control->Redirect(URL_ADMIN_LOGIN);
                    } else {
                        $this->input_control->Redirect(URL_LOGIN . (isset($_POST['redirect_location']) ? '?yonlendir='.$_POST['redirect_location'] : ''));
                    }
                } elseif ($result_captcha === true) {
                    if (!empty($captcha_timeout_from_db)) {
                        $this->ActionModel->UpdateCaptchaTimeOut(array(
                            'captcha_error_count' => 0,
                            'id' => $captcha_timeout_from_db['id']
                        ));
                    }
                    $user_from_db = $this->ActionModel->GetUserByEmail('id,email_confirmed,password,password_salt,two_fa_enable,user_role', $checked_inputs['email']);
                    if ($login_type == 'admin') {
                        $bool_control = !empty($user_from_db) && ($user_from_db['email_confirmed'] == 1) && ($user_from_db['user_role'] == ADMIN_ROLE_ID);
                    } else {
                        $bool_control = !empty($user_from_db);
                    }
                    if ($bool_control && !empty($user_from_db['password_salt'])) {
                        $salted_pwd = hash_hmac('sha512', $user_from_db['password_salt'] . str_replace("\0", "", $checked_inputs['password']), SECRET_KEY_PWD, true);
                        try {
                            $decrypted_hashed_pwd = $this->input_control->DecrypteData($user_from_db['password'], PEPPER);
                            if (!is_null($decrypted_hashed_pwd)) {
                                $verified_pwd = password_verify($salted_pwd, $decrypted_hashed_pwd);
                            } else {
                                $verified_pwd = false;
                            }
                        } catch (\Throwable) {
                            $verified_pwd = false;
                        }
                        if ($verified_pwd) {
                            $bcrypt_options = $this->password_control->BcryptOptions();
                            if (password_needs_rehash($decrypted_hashed_pwd, PASSWORD_BCRYPT, $bcrypt_options)) {
                                $new_hashed_pwd = password_hash($salted_pwd, PASSWORD_BCRYPT, $bcrypt_options);
                                $encrypted_hashed_pwd = $this->input_control->EncrypteData($new_hashed_pwd, PEPPER);
                                $this->ActionModel->UpdateUser(array(
                                    'password' => $encrypted_hashed_pwd,
                                    'id' => $user_from_db['id']
                                ));
                            }
                            if ($login_type == 'admin') {
                                if ($user_from_db['two_fa_enable'] == 1) {
                                    $this->SendVerifyTokenWithEmail($user_from_db['id'], $email, 'two_fa', 'admin', 'AdminLoginTwoFA');
                                } else {
                                    $this->notification_control->SetNotification('DANGER', TWO_FA_NOT_ENABLE_ERROR);
                                }
                                $this->input_control->Redirect(URL_ADMIN_LOGIN);
                            } else {
                                if ($user_from_db['user_role'] == ADMIN_ROLE_ID) {
                                    $this->notification_control->SetNotification('DANGER', LOGIN_WITH_ADMIN_LOGIN);
                                    $this->input_control->Redirect(URL_ADMIN_LOGIN);
                                } elseif ($user_from_db['email_confirmed'] == 1) {
                                    if (isset($_POST['remember_me'])) {
                                        $remember_me = 1;
                                    } else {
                                        $remember_me = 2;
                                    }
                                    if ($user_from_db['two_fa_enable'] == 1) {
                                        $this->SendVerifyTokenWithEmail($user_from_db['id'], $email, 'two_fa', 'user', 'LoginTwoFA', $remember_me);
                                    } else {
                                        if ($remember_me == 1) {
                                            $this->LoginWithCookie($user_from_db['id']);
                                        } else {
                                            $this->LoginWithSession($user_from_db['id'], 'user');
                                        }
                                    }
                                    $this->input_control->Redirect(URL_LOGIN);
                                } else {
                                    $this->SendVerifyTokenWithEmail($user_from_db['id'], $email, 'confirm_email', 'user', 'LoginConfirmEmail');
                                }
                            }
                        } else {
                            $this->ActionModel->CreateLogLogin(array(
                                'account_id' => $user_from_db['id'],
                                'user_ip' => $_SERVER['REMOTE_ADDR'],
                                'login_success' => 0
                            ));
                            $this->ActionModel->UpdateUser(array(
                                'count_access_failed' => $user_from_db['count_access_failed'] + 1,
                                'date_last_fail_access_attempt' => date('Y-m-d H:i:s'),
                                'id' => $user_from_db['id']
                            ));
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
                    if ($login_type == 'admin') {
                        $this->input_control->Redirect(URL_ADMIN_LOGIN);
                    } else {
                        $this->input_control->Redirect(URL_LOGIN . (isset($_POST['redirect_location']) ? '?yonlendir='.$_POST['redirect_location'] : ''));
                    }
                }
            } else {
                $this->notification_control->SetNotification('DANGER', CAPTCHA_ERROR);
            }
        } else {
            $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
        }
        if ($login_type == 'admin') {
            // $this->SetCSRFTokenAndGetView('AdminLogin', 'AdminLogin');
        } else {
            if (isset($_POST['redirect_location'])) {
                $this->web_data['redirect_location'] = $_POST['redirect_location'];
            }
            $this->web_data['email'] = $email;
            $this->web_data['password'] = $password;
            // $this->SetCSRFTokenAndGetView('Login', 'Login');
        }
        $this->input_control->Redirect();
    }
    function Register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // $this->SetCSRFTokenAndGetView('Register', 'Register');
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : '';
            $accept_terms = isset($_POST['accept_terms']) ? 'true' : 'false';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'email' => array('input' => $email, 'error_message_empty' => ERROR_MESSAGE_EMPTY_EMAIL, 'no_white_space' => true, 'error_message_no_white_space' => ERROR_NOT_VALID_EMAIL, 'is_email' => true, 'error_message_is_email' => ERROR_NOT_VALID_EMAIL, 'length_control' => true, 'max_length' => EMAIL_LIMIT, 'error_message_max_length' => ERROR_MAX_LENGTH_EMAIL, 'preventxss' => true, 'length_limit' => EMAIL_LIMIT_DB),
                'password' => array('input' => $password, 'error_message_empty' => ERROR_MESSAGE_EMPTY_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => ERROR_PASSWORD_WHITE_SPACE, 'length_control' => true, 'min_length' => PASSWORD_LIMIT, 'error_message_min_length' => ERROR_MAX_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => ERROR_NOT_VALID_PASSWORD, 'preventxss' => true),
                'repassword' => array('input' => $repassword, 'error_message_empty' => ERROR_MESSAGE_EMPTY_REPASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => ERROR_REPASSWORD_WHITE_SPACE, 'length_control' => true, 'min_length' => PASSWORD_LIMIT, 'error_message_min_length' => ERROR_MAX_LENGTH_REPASSWORD, 'is_password' => true, 'error_message_is_password' => ERROR_NOT_VALID_PASSWORD, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true),
                'accept_terms' => array('input' => $accept_terms, 'error_message_empty' => ERROR_MESSAGE_EMPTY_ACCEPT_REGISTER_TERMS)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'Register');
                if ($result_check_csrf_token == true) {
                    $pwd_salt = strtr(sodium_bin2base64(random_bytes(75), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => '2', '_' => '4'));
                    $salted_pwd = hash_hmac('sha512', $pwd_salt . str_replace("\0", "", $checked_inputs['password']), SECRET_KEY_PWD, true);
                    $salted_repwd = hash_hmac('sha512', $pwd_salt . str_replace("\0", "", $checked_inputs['repassword']), SECRET_KEY_PWD, true);
                    $hashed_pwd = password_hash($salted_pwd, PASSWORD_BCRYPT, $this->password_control->BcryptOptions());
                    $verified_pwd = password_verify($salted_repwd, $hashed_pwd);
                    if ($verified_pwd) {
                        if ($checked_inputs['accept_terms'] == 'true') {
                            $captcha_timeout_from_db = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
                            if (empty($captcha_timeout_from_db)) {
                                $this->ActionModel->CreateCaptchaTimeOut(array(
                                    'user_ip' => $_SERVER['REMOTE_ADDR'],
                                    'captcha_error_count' => 0,
                                    'captcha_total_error_count' => 0
                                ));
                            } else {
                                $captcha_timeout_error = false;
                                if ($captcha_timeout_from_db['captcha_banned'] == 1) {
                                    $captcha_timeout_error = true;
                                    $this->notification_control->SetNotification('DANGER', CAPTCHA_BANNES_ERROR);
                                } elseif ($captcha_timeout_from_db['captcha_total_error_count'] > 15) {
                                    $captcha_timeout_error = true;
                                    $this->notification_control->SetNotification('DANGER', CAPTCHA_BANNES_ERROR);
                                } elseif ($captcha_timeout_from_db['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                                    $captcha_timeout_error = true;
                                    $captcha_remain_timeout = (int)((strtotime($captcha_timeout_from_db['date_captcha_timeout_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60);
                                    if ($captcha_remain_timeout == 0) {
                                        $captcha_remain_timeout = 1;
                                    }
                                    $this->notification_control->SetNotification('DANGER', CAPTCHA_TIMEOUT_1 . $captcha_remain_timeout . CAPTCHA_TIMEOUT_2);
                                }
                                if ($captcha_timeout_error) {
                                    $this->input_control->Redirect(URL_REGISTER);
                                }
                            }
                            $captcha_respone = isset($_POST['h-captcha-response']) ? $_POST['h-captcha-response'] : '';
                            if (!empty($captcha_respone)) {
                                $result_captcha = $this->action_control->CheckCaptcha($captcha_respone);
                                if ($result_captcha === false) {
                                    if (!empty($captcha_timeout_from_db)) {
                                        if ($captcha_timeout_from_db['captcha_error_count'] < 1) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array(
                                                'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                                'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                                'id' => $captcha_timeout_from_db['id']
                                            ));
                                        } elseif ($captcha_timeout_from_db['captcha_error_count'] == 1) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array(
                                                'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                                'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                                'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (TIMEOUT_SHORT_CAPTCHA)),
                                                'id' => $captcha_timeout_from_db['id']
                                            ));
                                        } elseif ($captcha_timeout_from_db['captcha_error_count'] >= 9) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array(
                                                'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                                'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                                'captcha_banned' => 1,
                                                'id' => $captcha_timeout_from_db['id']
                                            ));
                                        } elseif ($captcha_timeout_from_db['captcha_error_count'] >= 4) {
                                            $this->ActionModel->UpdateCaptchaTimeOut(array(
                                                'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                                'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                                'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (TIMEOUT_LONG_CAPTCHA)),
                                                'id' => $captcha_timeout_from_db['id']
                                            ));
                                        }
                                    }
                                    $this->notification_control->SetNotification('DANGER', CAPTCHA_ERROR);
                                    $this->input_control->Redirect(URL_REGISTER);
                                } elseif ($result_captcha === true) {
                                    if (!empty($captcha_timeout_from_db)) {
                                        $this->ActionModel->UpdateCaptchaTimeOut(array(
                                            'captcha_error_count' => 0,
                                            'id' => $captcha_timeout_from_db['id']
                                        ));
                                    }
                                    $email_accounts = $this->ActionModel->GetUsersByEmail('is_user_deleted', $checked_inputs['email']);
                                    if (!empty($email_accounts)) {
                                        $is_email_not_unique = false;
                                        foreach ($email_accounts as $email_account) {
                                            if ($email_account['is_user_deleted'] == 0) {
                                                $is_email_not_unique = true;
                                                break;
                                            }
                                        }
                                        if ($is_email_not_unique) {
                                            $_SESSION[SESSION_MASSAGE] = EMAIL_HAS_REGISTERED;
                                            $this->input_control->Redirect(URL_LOGIN);
                                        }
                                    }
                                    $encrypted_hashed_pwd = $this->input_control->EncrypteData($hashed_pwd, PEPPER);
                                    $result = $this->ActionModel->CreateUser(array(
                                        'email' => $checked_inputs['email'],
                                        'password' => $encrypted_hashed_pwd,
                                        'password_salt' => $pwd_salt
                                    ));
                                    if ($result['result'] == 'Created') {
                                        $created_user = $this->ActionModel->GetUserByEmail('id', $checked_inputs['email']);
                                        if (!empty($created_user['id'])) {
                                            $this->SendVerifyTokenWithEmail($created_user['id'], $email, 'confirm_email', 'user', 'RegisterConfirmEmail');
                                        }
                                    } else {
                                        $this->notification_control->SetNotification('DANGER', DATABASE_ERROR);
                                    }
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', CAPTCHA_ERROR);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', ERROR_MESSAGE_EMPTY_ACCEPT_REGISTER_TERMS);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', PASSWORDS_NOT_SAME);
                    }
                } else {
                    $this->input_control->Redirect(URL_REGISTER);
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
            $this->web_data['email'] = $email;
            $this->web_data['password'] = $password;
            $this->web_data['repassword'] = $repassword;
            // $this->SetCSRFTokenAndGetView('Register', 'Register');
        }
        $this->input_control->Redirect();
    }
    function ForgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // $this->SetCSRFTokenAndGetView('ForgotPassword', 'ForgotPassword');
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'email' => array('input' => $email, 'error_message_empty' => ERROR_MESSAGE_EMPTY_EMAIL, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
            ));
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'ForgotPassword');
                if ($result_check_csrf_token == true) {
                    $captcha_timeout_from_db = $this->ActionModel->GetCaptchaTimeOut($_SERVER['REMOTE_ADDR']);
                    if (empty($captcha_timeout_from_db)) {
                        $this->ActionModel->CreateCaptchaTimeOut(array(
                            'user_ip' => $_SERVER['REMOTE_ADDR'],
                            'captcha_error_count' => 0,
                            'captcha_total_error_count' => 0
                        ));
                    } else {
                        $captcha_timeout_error = false;
                        if ($captcha_timeout_from_db['captcha_banned'] == 1) {
                            $captcha_timeout_error = true;
                            $this->notification_control->SetNotification('DANGER', CAPTCHA_BANNES_ERROR);
                        } elseif ($captcha_timeout_from_db['captcha_total_error_count'] > 15) {
                            $captcha_timeout_error = true;
                            $this->notification_control->SetNotification('DANGER', CAPTCHA_BANNES_ERROR);
                        } elseif ($captcha_timeout_from_db['date_captcha_timeout_expiry'] > date('Y-m-d H:i:s')) {
                            $captcha_timeout_error = true;
                            $captcha_remain_timeout = (int)((strtotime($captcha_timeout_from_db['date_captcha_timeout_expiry']) - strtotime(date('Y-m-d H:i:s'))) / 60);
                            if ($captcha_remain_timeout == 0) {
                                $captcha_remain_timeout = 1;
                            }
                            $this->notification_control->SetNotification('DANGER', CAPTCHA_TIMEOUT_1 . $captcha_remain_timeout . CAPTCHA_TIMEOUT_2);
                        }
                        if ($captcha_timeout_error) {
                            $this->input_control->Redirect(URL_FORGOT_PASSWORD);
                        }
                    }
                    $captcha_respone = isset($_POST['h-captcha-response']) ? $_POST['h-captcha-response'] : '';
                    if (!empty($captcha_respone)) {
                        $result_captcha = $this->action_control->CheckCaptcha($captcha_respone);
                        if ($result_captcha === false) {
                            if (!empty($captcha_timeout_from_db)) {
                                if ($captcha_timeout_from_db['captcha_error_count'] < 1) {
                                    $this->ActionModel->UpdateCaptchaTimeOut(array(
                                        'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                        'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                        'id' => $captcha_timeout_from_db['id']
                                    ));
                                } elseif ($captcha_timeout_from_db['captcha_error_count'] == 1) {
                                    $this->ActionModel->UpdateCaptchaTimeOut(array(
                                        'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                        'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                        'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (TIMEOUT_SHORT_CAPTCHA)),
                                        'id' => $captcha_timeout_from_db['id']
                                    ));
                                } elseif ($captcha_timeout_from_db['captcha_error_count'] >= 9) {
                                    $this->ActionModel->UpdateCaptchaTimeOut(array(
                                        'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                        'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                        'captcha_banned' => 1,
                                        'id' => $captcha_timeout_from_db['id']
                                    ));
                                } elseif ($captcha_timeout_from_db['captcha_error_count'] >= 4) {
                                    $this->ActionModel->UpdateCaptchaTimeOut(array(
                                        'captcha_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                        'captcha_total_error_count' => $captcha_timeout_from_db['captcha_error_count'] + 1,
                                        'date_captcha_timeout_expiry' => date('Y-m-d H:i:s', time() + (TIMEOUT_LONG_CAPTCHA)),
                                        'id' => $captcha_timeout_from_db['id']
                                    ));
                                }
                            }
                            $this->notification_control->SetNotification('DANGER', CAPTCHA_ERROR);
                            $this->input_control->Redirect(URL_FORGOT_PASSWORD);
                        } elseif ($result_captcha === true) {
                            if (!empty($captcha_timeout_from_db)) {
                                $this->ActionModel->UpdateCaptchaTimeOut(array(
                                    'captcha_error_count' => 0,
                                    'id' => $captcha_timeout_from_db['id']
                                ));
                            }
                            $checked_email = $this->input_control->CheckEmail($email);
                            if (!is_null($checked_email)) {
                                $user_forgot_pwd_from_db = $this->ActionModel->GetUserByEmail('id', $checked_inputs['email']);
                                if (!empty($user_forgot_pwd_from_db)) {
                                    $resetpwd_token = $this->action_control->GenerateResetPwdToken();
                                    $result_reset_pwd = $this->ActionModel->CreateResetPassword(array(
                                        'user_id' => $user_forgot_pwd_from_db['id'],
                                        'user_ip' => $_SERVER['REMOTE_ADDR'],
                                        'reset_pwd_token' => $resetpwd_token,
                                        'date_reset_pwd_expiry' => date('Y-m-d H:i:s', time() + (EXPIRY_RESET_PWD_TOKEN)),
                                        'is_reset_pwd_used' => 0
                                    ));
                                    if ($result_reset_pwd['result'] == 'Created') {
                                        $result_email = $this->action_control->SendMail($checked_email, BRAND . ' Şifre Sıfırlama', '<!DOCTYPE html><html lang="tr"><head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width,initial-scale=1.0" /><meta charset="UTF-8" /><title>' . BRAND . ' Şifre Sıfırlama</title><style>* {margin: 0;padding: 0;border: 0;box-sizing: border-box;}html {font-size: 10px;}body {font-family: sans-serif;background-color: #aaaaaa;width: 100%;height: 100%;}.container {width: 100%;height: 100%;margin-left: auto;margin-right: auto;}.header {background-color: #000000;text-align: center;padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;}.logo {border-width: 3px;border-style: solid;border-color: #ffffff;border-radius: 50%;padding: 10px;margin-bottom: 20px;}.main-title {font-size: 18px;color: #ffffff;letter-spacing: 1px;margin-bottom: 20px;}.main-text {font-size: 14px;color: #ffffff;margin-bottom: 20px;}.main-link {font-size: 14px;color: #5155d3;}.main-text-2 {font-size: 14px;color: #ffffff;margin-top: 20px;}.footer {padding-top: 20px;padding-bottom: 20px;padding-left: 10px;padding-right: 10px;background-color: #5155d3;}.footer-text {font-size: 13px;color: #000000;text-align: center;margin-bottom: 20px;}.footer-link {font-size: 13px;color: #aaaaaa !important;}.footer-url {font-size: 12px;color: #ffffff !important;float: left;}.footer-date {color: #ffffff;font-size: 12px;float: right;}@media only screen and (min-width: 768px) {.container {width: 70%;}}@media only screen and (min-width: 992px) {.container {width: 50%;}}</style></head><body><div class="container"><div class="header"><img class="logo" src="' . URL  . '" alt="' . BRAND . '"><h1 class="main-title">' . BRAND . ' Şifre Sıfırlama</h1><p class="main-text">Şifrenizi sıfırlamak için linke <a class="main-link" href="' . URL . URL_RESET_PASSWORD . '?resetpwd=' . $resetpwd_token . '">tıklayın.</a></p><p class="main-text-2">Şifre sıfırlama linkinin kullanım süresi ' . EXPIRY_RESET_PWD_TOKEN_MINUTE . ' dakikadır.</p></div><div class="footer"><p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz bu emaili önemsemeyin.</p><a class="footer-url" href="' . URL . '">' . URL . '</a><span class="footer-date">' . date('d-m-Y H:i:s') . '</span></div></div></body></html>');
                                        if (!is_null($result_email)) {
                                            $this->ActionModel->CreateLogEmailSent(array(
                                                'user_id' => $user_forgot_pwd_from_db['id'],
                                                'user_ip' => $_SERVER['REMOTE_ADDR'],
                                                'email_type' => 'ForgotPassword'
                                            ));
                                            $_SESSION[SESSION_MASSAGE] = FORGOT_PASSWORD_RESULT;
                                            $this->input_control->Redirect(URL_LOGIN);
                                        }
                                    }
                                    $this->notification_control->SetNotification('DANGER', DATABASE_ERROR);
                                    $this->input_control->Redirect(URL_FORGOT_PASSWORD);
                                } else {
                                    $this->notification_control->SetNotification('DANGER', FORGOT_PASSWORD_NO_EMAIL);
                                    $this->input_control->Redirect(URL_FORGOT_PASSWORD);
                                }
                            } else {
                                $this->notification_control->SetNotification('DANGER', ERROR_NOT_VALID_EMAIL);
                            }
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', CAPTCHA_ERROR);
                    }
                } else {
                    $this->input_control->Redirect(URL_FORGOT_PASSWORD);
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
            }
            // $this->SetCSRFTokenAndGetView('ForgotPassword', 'ForgotPassword');
        }
        $this->input_control->Redirect();
    }
    function ResetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['resetpwd'])) {
            $reset_password_url = $this->input_control->IsString($_GET['resetpwd']);
            if (!is_null($reset_password_url)) {
                $reset_password_from_db = $this->ActionModel->GetResetPassword(array(urlencode($reset_password_url), $_SERVER['REMOTE_ADDR']));
                if (!empty($reset_password_from_db) && $reset_password_from_db['date_reset_pwd_expiry'] > date('Y-m-d H:i:s') && $reset_password_from_db['is_reset_pwd_used'] == 0) {
                    $reset_pwd_post_token = $this->action_control->GenerateResetPwdPostToken();
                    $this->ActionModel->UpdateResetPassword(array(
                        'is_reset_pwd_used' => 1,
                        'date_reset_pwd_used' => date('Y-m-d H:i:s'),
                        'reset_pwd_post_token' => $reset_pwd_post_token,
                        'id' => $reset_password_from_db['id']
                    ));
                    $_SESSION[SESSION_RESET_PWD] = $reset_pwd_post_token;
                    $this->web_data['reset_pwd_token'] = $this->action_control->GenerateBaitToken();
                    // $this->SetCSRFTokenAndGetView('ResetPassword', 'ResetPassword');
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION[SESSION_RESET_PWD])) {
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : '';
            $checked_inputs = $this->input_control->CheckPostedInputs(array(
                'password' => array('input' => $password, 'error_message_empty' => ERROR_MESSAGE_EMPTY_PASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => ERROR_PASSWORD_WHITE_SPACE, 'length_control' => true, 'min_length' => PASSWORD_LIMIT, 'error_message_min_length' => ERROR_MAX_LENGTH_PASSWORD, 'is_password' => true, 'error_message_is_password' => ERROR_NOT_VALID_PASSWORD, 'preventxss' => true),
                'repassword' => array('input' => $repassword, 'error_message_empty' => ERROR_MESSAGE_EMPTY_REPASSWORD, 'no_white_space' => true, 'error_message_no_white_space' => ERROR_REPASSWORD_WHITE_SPACE, 'length_control' => true, 'min_length' => PASSWORD_LIMIT, 'error_message_min_length' => ERROR_MAX_LENGTH_REPASSWORD, 'is_password' => true, 'error_message_is_password' => ERROR_NOT_VALID_PASSWORD, 'preventxss' => true),
                'csrf_token' => array('input' => isset($_POST['form_token']) ? $_POST['form_token'] : '', 'error_message_empty' => ERROR_MESSAGE_EMPTY_CSRF, 'preventxss' => true)
            ));
            $go_reset_pwd = false;
            if (empty($checked_inputs['error_message'])) {
                $result_check_csrf_token = $this->CheckCSRFToken($checked_inputs['csrf_token'], 'ResetPassword');
                if ($result_check_csrf_token == true) {
                    $pwd_salt = strtr(sodium_bin2base64(random_bytes(112), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => '2', '_' => '4'));
                    $salted_pwd = $pwd_salt . $checked_inputs['password'];
                    $salted_repwd = $pwd_salt . $checked_inputs['repassword'];
                    $hashed_pwd = password_hash($salted_pwd, PASSWORD_BCRYPT, $this->password_control->BcryptOptions());
                    $verified_pwd = password_verify($salted_repwd, $hashed_pwd);
                    if ($verified_pwd) {
                        $reset_pwd_from_db = $this->ActionModel->GetPostedResetPassword(array($_SESSION[SESSION_RESET_PWD], $_SERVER['REMOTE_ADDR']));
                        unset($_SESSION[SESSION_RESET_PWD]);
                        if (!empty($reset_pwd_from_db) && $reset_pwd_from_db['date_reset_pwd_expiry'] > date('Y-m-d H:i:s') && $reset_pwd_from_db['is_reset_pwd_post_used'] == 0) {
                            $this->ActionModel->UpdateResetPassword(array(
                                'is_reset_pwd_post_used' => 1,
                                'date_reset_pwd_post_used' => date('Y-m-d H:i:s'),
                                'id' => $reset_pwd_from_db['id']
                            ));
                            $encrypted_hashed_pwd = $this->input_control->EncrypteData($hashed_pwd, PEPPER);
                            $result = $this->ActionModel->UpdateUser(array(
                                'password' => $encrypted_hashed_pwd,
                                'password_salt' => $pwd_salt,
                                'id' => $reset_pwd_from_db['user_id']
                            ));
                            if ($result == 'Updated') {
                                $_SESSION[SESSION_MASSAGE] = RESET_PWD_SUCCESS;
                                $this->input_control->Redirect(URL_LOGIN);
                            } else {
                                $this->notification_control->SetNotification('DANGER', DATABASE_ERROR);
                            }
                        } else {
                            $this->notification_control->SetNotification('DANGER', RESET_PWD_ERROR);
                        }
                    } else {
                        $this->notification_control->SetNotification('DANGER', PASSWORDS_NOT_SAME);
                        $go_reset_pwd = true;
                    }
                } else {
                    $this->input_control->Redirect(URL_FORGOT_PASSWORD);
                }
            } else {
                $this->notification_control->SetNotification('DANGER', $checked_inputs['error_message']);
                $go_reset_pwd = true;
            }
            if ($go_reset_pwd) {
                $this->web_data['password'] = $password;
                $this->web_data['repassword'] = $repassword;
                // $this->SetCSRFTokenAndGetView('ResetPassword', 'ResetPassword');
            } else {
                $this->session_control->KillSession();
                $this->input_control->Redirect(URL_FORGOT_PASSWORD);
            }
        }
        $this->session_control->KillSession();
        $this->input_control->Redirect();
    }
}
