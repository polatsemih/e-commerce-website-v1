<?php
class ActionModel extends Model
{
    function __construct()
    {
        parent::__construct();
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
    function CreateSessionRegisterConfirm(array $inputs)
    {
        return $this->database->Create(TABLE_SESSION_REGISTER_CONFIRM, $inputs);
    }
    function CreateLinkRegisterCancel(array $inputs)
    {
        return $this->database->Create(TABLE_LINK_REGISTER_CANCEL, $inputs);
    }
    function CreateLogEmailSent(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_EMAIL_SENT, $inputs);
    }
    function GetSessionRegisterConfirm(array $inputs)
    {
        return $this->database->Get(TABLE_SESSION_REGISTER_CONFIRM, 'id,user_id,register_hashed_confirm_tokens,date_register_confirm_expiry,is_register_confirm_used', 'WHERE user_ip=? AND register_confirm_token=? ORDER BY date_register_confirm_created DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function UpdateSessionRegisterConfirm(array $inputs)
    {
        return $this->database->Update(TABLE_SESSION_REGISTER_CONFIRM, $inputs);
    }
    function GetLinkRegisterCancelByLink(string $link_register_cancel)
    {
        return $this->database->Get(TABLE_LINK_REGISTER_CANCEL, 'id,user_id,date_link_register_cancel_expiry,is_link_register_cancel_used', 'WHERE link_register_cancel=? ORDER BY date_link_register_cancel_created DESC LIMIT 1', $link_register_cancel, 'SINGULAR');
    }
    function UpdateLinkRegisterCancel(array $inputs)
    {
        return $this->database->Update(TABLE_LINK_REGISTER_CANCEL, $inputs);
    }
    function CreateLinkForgotPassword(array $inputs)
    {
        return $this->database->Create(TABLE_LINK_FORGOT_PASSWORD, $inputs);
    }
    function GetLinkForgotPassword(string $link_forgot_password)
    {
        return $this->database->Get(TABLE_LINK_FORGOT_PASSWORD, 'id,user_id,date_link_forgot_password_expiry,is_link_forgot_password_used', 'WHERE link_forgot_password=? ORDER BY date_link_forgot_password_created DESC LIMIT 1', $link_forgot_password, 'SINGULAR');
    }
    function GetLinkForgotPasswordById(string $id)
    {
        return $this->database->Get(TABLE_LINK_FORGOT_PASSWORD, 'user_id,date_link_forgot_password_expiry,is_link_forgot_password_used', 'WHERE id=? ORDER BY date_link_forgot_password_created DESC LIMIT 1', $id, 'SINGULAR');
    }
    function UpdateLinkForgotPassword(array $inputs)
    {
        return $this->database->Update(TABLE_LINK_FORGOT_PASSWORD, $inputs);
    }
    function CreateSessionResetPassword(array $inputs)
    {
        return $this->database->Create(TABLE_SESSION_RESET_PASSWORD, $inputs);
    }
    function GetSessionResetPassword(array $inputs)
    {
        return $this->database->Get(TABLE_SESSION_RESET_PASSWORD, 'id,link_forgot_password_id,is_reset_password_used', 'WHERE user_ip=? AND reset_password_token=? ORDER BY date_reset_password_created DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function UpdateSessionResetPassword(array $inputs)
    {
        return $this->database->Update(TABLE_SESSION_RESET_PASSWORD, $inputs);
    }
    function CreateLogLogin(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_LOGIN, $inputs);
    }
    function CreateLogLoginEmailFail(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_LOGIN_EMAIL_FAIL, $inputs);
    }
    function GetLinkRegisterCancelByUserId(string $user_id)
    {
        return $this->database->Get(TABLE_LINK_REGISTER_CANCEL, 'link_register_cancel,date_link_register_cancel_expiry,is_link_register_cancel_used', 'WHERE user_id=? ORDER BY date_link_register_cancel_created DESC LIMIT 1', $user_id, 'SINGULAR');
    }
    function CreateCookieAuthentication(array $inputs)
    {
        return $this->database->Create(TABLE_COOKIE_AUTHENTICATION, $inputs);
    }
    function CreateSessionAuthentication(array $inputs)
    {
        return $this->database->Create(TABLE_SESSION_AUTHENTICATION, $inputs);
    }
    function CreateSessionTwoFA(array $inputs)
    {
        return $this->database->Create(TABLE_SESSION_TWO_FA, $inputs);
    }
    function GetSessionTwoFA(array $inputs)
    {
        return $this->database->Get(TABLE_SESSION_TWO_FA, 'id,user_id,two_fa_hashed_tokens,remember_me,date_two_fa_expiry,is_two_fa_used', 'WHERE user_ip=? AND two_fa_token=? ORDER BY date_two_fa_created DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function UpdateSessionTwoFA(array $inputs)
    {
        return $this->database->Update(TABLE_SESSION_TWO_FA, $inputs);
    }
    function GetSessionAuthentication(array $inputs)
    {
        return $this->database->Get(TABLE_SESSION_AUTHENTICATION, 'id,user_id,date_session_authentication_expiry,is_session_authentication_logout', 'WHERE user_ip=? AND session_authentication_token=? ORDER BY date_session_authentication_login DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function UpdateSessionAuthentication(array $inputs)
    {
        return $this->database->Update(TABLE_SESSION_AUTHENTICATION, $inputs);
    }
    function GetCookieAuthentication(array $inputs)
    {
        return $this->database->Get(TABLE_COOKIE_AUTHENTICATION, 'id,user_id,cookie_authentication_token1,cookie_authentication_salt,date_cookie_authentication_expiry,is_cookie_authentication_logout', 'WHERE user_ip=? AND cookie_authentication_token2=? ORDER BY date_cookie_authentication_login DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function UpdateCookieAuthentication(array $inputs)
    {
        return $this->database->Update(TABLE_COOKIE_AUTHENTICATION, $inputs);
    }
    function CreateCookieAuthenticationCrossSite(array $inputs)
    {
        return $this->database->Create(TABLE_COOKIE_AUTHENTICATION_CROSS_SITE, $inputs);
    }
    function GetCookieAuthenticationCrossSite(array $inputs)
    {
        return $this->database->Get(TABLE_COOKIE_AUTHENTICATION_CROSS_SITE, 'id,user_id,cookie_authentication_cross_site_token1,cookie_authentication_cross_site_salt,date_cookie_authentication_cross_site_expiry,is_cookie_authentication_cross_site_used', 'WHERE user_ip=? AND cookie_authentication_cross_site_token2=? ORDER BY date_cookie_authentication_cross_site_created DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function UpdateCookieAuthenticationCrossSite(array $inputs)
    {
        return $this->database->Update(TABLE_COOKIE_AUTHENTICATION_CROSS_SITE, $inputs);
    }
}
