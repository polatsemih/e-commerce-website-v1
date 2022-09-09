<?php
class ActionModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function GetSessionAuthentication(array $inputs)
    {
        return $this->database->Get(TABLE_SESSION_AUTHENTICATION, 'id,user_id,date_session_authentication_expiry,is_session_authentication_logout', 'WHERE user_ip=? AND session_authentication_token=? ORDER BY date_session_authentication_login DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function CreateSessionAuthentication(array $inputs)
    {
        return $this->database->Create(TABLE_SESSION_AUTHENTICATION, $inputs);
    }
    function UpdateSessionAuthentication(array $inputs)
    {
        return $this->database->Update(TABLE_SESSION_AUTHENTICATION, $inputs);
    }
    function GetCookieAuthentication(array $inputs)
    {
        return $this->database->Get(TABLE_COOKIE_AUTHENTICATION, 'id,user_id,cookie_authentication_token1,cookie_authentication_salt,date_cookie_authentication_expiry,is_cookie_authentication_logout', 'WHERE user_ip=? AND cookie_authentication_token2=? ORDER BY date_cookie_authentication_login DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function CreateCookieAuthentication(array $inputs)
    {
        return $this->database->Create(TABLE_COOKIE_AUTHENTICATION, $inputs);
    }
    function UpdateCookieAuthentication(array $inputs)
    {
        return $this->database->Update(TABLE_COOKIE_AUTHENTICATION, $inputs);
    }
    function GetCaptchaTimeOut(string $user_ip)
    {
        return $this->database->Get(TABLE_CAPTCHA_TIMEOUT, 'id,captcha_error_count,captcha_total_error_count,date_captcha_timeout_expiry', 'WHERE user_ip=?', $user_ip, 'SINGULAR');
    }
    function CreateCaptchaTimeOut(array $inputs)
    {
        return $this->database->Create(TABLE_CAPTCHA_TIMEOUT, $inputs);
    }
    function UpdateCaptchaTimeOut(array $inputs)
    {
        return $this->database->Update(TABLE_CAPTCHA_TIMEOUT, $inputs);
    }
    function CreateLogCaptcha(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_CAPTCHA, $inputs);
    }
    function GetSessionRegisterConfirm(array $inputs)
    {
        return $this->database->Get(TABLE_SESSION_REGISTER_CONFIRM, 'id,user_id,register_hashed_confirm_tokens,date_register_confirm_expiry,is_register_confirm_used', 'WHERE user_ip=? AND register_confirm_token=? ORDER BY date_register_confirm_created DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function CreateSessionRegisterConfirm(array $inputs)
    {
        return $this->database->Create(TABLE_SESSION_REGISTER_CONFIRM, $inputs);
    }
    function UpdateSessionRegisterConfirm(array $inputs)
    {
        return $this->database->Update(TABLE_SESSION_REGISTER_CONFIRM, $inputs);
    }
    function GetLinkRegisterCancelByUserId(string $user_id)
    {
        return $this->database->Get(TABLE_LINK_REGISTER_CANCEL, 'link_register_cancel', 'WHERE user_id=?', $user_id, 'SINGULAR');
    }
    function GetLinkRegisterCancelByLink(string $link_register_cancel)
    {
        return $this->database->Get(TABLE_LINK_REGISTER_CANCEL, 'user_id,date_link_register_cancel_expiry,is_link_register_cancel_used', 'WHERE link_register_cancel=? ORDER BY date_link_register_cancel_created DESC LIMIT 1', $link_register_cancel, 'SINGULAR');
    }
    function CreateLinkRegisterCancel(array $inputs)
    {
        return $this->database->Create(TABLE_LINK_REGISTER_CANCEL, $inputs);
    }
    function UpdateLinkRegisterCancel(array $inputs)
    {
        return $this->database->Update(TABLE_LINK_REGISTER_CANCEL, $inputs);
    }
    function CreateLogEmailSent(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_EMAIL_SENT, $inputs);
    }
    function CreateLogLogin(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_LOGIN, $inputs);
    }
    function GetLinkForgotPassword(string $link_forgot_password)
    {
        return $this->database->Get(TABLE_LINK_FORGOT_PASSWORD, 'id,user_id,date_link_forgot_password_expiry,is_link_forgot_password_used', 'WHERE link_forgot_password=? ORDER BY date_link_forgot_password_created DESC LIMIT 1', $link_forgot_password, 'SINGULAR');
    }
    function CreateLinkForgotPassword(array $inputs)
    {
        return $this->database->Create(TABLE_LINK_FORGOT_PASSWORD, $inputs);
    }
    function UpdateLinkForgotPassword(array $inputs)
    {
        return $this->database->Update(TABLE_LINK_FORGOT_PASSWORD, $inputs);
    }
    function GetSessionResetPassword(array $inputs)
    {
        
    }
    function CreateSessionResetPassword(array $inputs)
    {
        return $this->database->Create(TABLE_SESSION_RESET_PASSWORD, $inputs);
    }
    function UpdateSessionResetPassword(array $inputs)
    {
        return $this->database->Update(TABLE_SESSION_RESET_PASSWORD, $inputs);
    }
    function GetLogLoginFail(string $user_ip)
    {
        return $this->database->Get(TABLE_LOG_LOGIN_FAIL, 'id,ip_login_fail_count', 'WHERE user_ip=?', $user_ip, 'SINGULAR');
    }
    function CreateLogLoginFail(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_LOGIN_FAIL, $inputs);
    }
    function UpdateLogLoginFail(array $inputs)
    {
        return $this->database->Update(TABLE_LOG_LOGIN_FAIL, $inputs);
    }
}
