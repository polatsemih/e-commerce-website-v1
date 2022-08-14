<?php
class ActionModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function GetUserById(string $columns, string $user_id)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_USER, $columns, 'WHERE id=? AND is_user_deleted=0', $user_id);
    }
    function GetUsersByEmail(string $columns, string $email)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_USER, $columns, 'WHERE email=?', $email);
    }
    function GetUserByEmail(string $columns, string $email)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_USER, $columns, 'WHERE email=? AND is_user_deleted=0', $email);
    }
    function CreateUser(array $inputs)
    {
        return parent::Create(TABLE_USER, $inputs);
    }
    function UpdateUser(array $inputs)
    {
        return parent::Update(TABLE_USER, $inputs);
    }
    function GetSessionAuth(array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_SESSION_AUTH, 'id,user_id,date_session_auth_expiry,session_auth_is_logout', 'WHERE session_auth_token=? AND user_ip=? ORDER BY date_session_auth_login DESC LIMIT 1', $inputs);
    }
    function CreateSessionAuth(array $inputs)
    {
        return parent::Create(TABLE_SESSION_AUTH, $inputs);
    }
    function UpdateSessionAuth(array $inputs)
    {
        return parent::Update(TABLE_SESSION_AUTH, $inputs);
    }
    function GetCookieAuth(array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_COOKIE_AUTH, 'id,user_id,cookie_auth_token1,cookie_auth_salt,date_cookie_auth_expiry,cookie_auth_is_logout', 'WHERE cookie_auth_token2=? AND user_ip=? ORDER BY date_cookie_auth_login DESC LIMIT 1', $inputs);
    }
    function CreateCookieAuth(array $inputs)
    {
        return parent::Create(TABLE_COOKIE_AUTH, $inputs);
    }
    function UpdateCookieAuth(array $inputs)
    {
        return parent::Update(TABLE_COOKIE_AUTH, $inputs);
    }
    function GetLogCSRF(string $columns, array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_LOG_CSRF, $columns, 'WHERE csrf_page=? AND user_ip=?', $inputs);
    }
    function GetExistsLogCSRF($user_ip)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_LOG_CSRF, 'id', 'WHERE user_ip=?', $user_ip);
    }
    function CreateLogCSRF(array $inputs)
    {
        return parent::Create(TABLE_LOG_CSRF, $inputs);
    }
    function UpdateLogCSRF(array $inputs)
    {
        return parent::Update(TABLE_LOG_CSRF, $inputs);
    }
    function GetCountFailLogin(string $user_ip)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_COUNT_FAIL_LOGIN, 'id,ip_fail_login_count', 'WHERE user_ip=?', $user_ip);
    }
    function CreateCountFailLogin(array $inputs)
    {
        return parent::Create(TABLE_COUNT_FAIL_LOGIN, $inputs);
    }
    function UpdateCountFailLogin(array $inputs)
    {
        return parent::Update(TABLE_COUNT_FAIL_LOGIN, $inputs);
    }
    function GetCaptchaTimeOut(string $user_ip)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_CAPTCHA_TIMEOUT, 'id,captcha_error_count,captcha_total_error_count,date_captcha_timeout_expiry,captcha_banned', 'WHERE user_ip=?', $user_ip);
    }
    function CreateCaptchaTimeOut(array $inputs)
    {
        return parent::Create(TABLE_CAPTCHA_TIMEOUT, $inputs);
    }
    function UpdateCaptchaTimeOut(array $inputs)
    {
        return parent::Update(TABLE_CAPTCHA_TIMEOUT, $inputs);
    }
    function CreateLogLogin(array $inputs)
    {
        return parent::Create(TABLE_LOG_LOGIN, $inputs);
    }
    function CreateLogEmailSent(array $inputs)
    {
        return parent::Create(TABLE_LOG_EMAIL_SENT, $inputs);
    }
    function GetVerifyToken(array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_VERIFY_TOKEN, 'id,user_id,verify_hashed_tokens,date_verify_token_expiry,is_verify_token_used,verify_token_auth,verify_token_type,verify_token_csrf_type,verify_token_remember_me', 'WHERE verify_token=? AND user_ip=? ORDER BY date_verify_token_created DESC LIMIT 1', $inputs);
    }
    function CreateVerifyToken(array $inputs)
    {
        return parent::Create(TABLE_VERIFY_TOKEN, $inputs);
    }
    function UpdateVerifyToken(array $inputs)
    {
        return parent::Update(TABLE_VERIFY_TOKEN, $inputs);
    }
    function GetVerifyLink(array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_VERIFY_LINK, 'id,user_id,verify_link_expiry_date,verify_link_type,is_verify_link_used', 'WHERE verify_link=? AND user_ip=? ORDER BY date_verify_link_created DESC LIMIT 1', $inputs);
    }
    function CreateVerifyLink(array $inputs)
    {
        return parent::Create(TABLE_VERIFY_LINK, $inputs);
    }
    function UpdateVerifyLink(array $inputs)
    {
        return parent::Update(TABLE_VERIFY_LINK, $inputs);
    }
    function GetResetPassword(array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_RESET_PWD, 'id,user_id,date_reset_pwd_expiry,is_reset_pwd_used', 'WHERE reset_pwd_token=? AND user_ip=? ORDER BY date_reset_pwd_created DESC LIMIT 1', $inputs);
    }
    function GetPostedResetPassword(array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_RESET_PWD, 'id,user_id,date_reset_pwd_expiry,is_reset_pwd_post_used', 'WHERE reset_pwd_post_token=? AND user_ip=? AND is_reset_pwd_used=1 ORDER BY date_reset_pwd_created DESC LIMIT 1', $inputs);
    }
    function CreateResetPassword(array $inputs)
    {
        return parent::Create(TABLE_RESET_PWD, $inputs);
    }
    function UpdateResetPassword(array $inputs)
    {
        return parent::Update(TABLE_RESET_PWD, $inputs);
    }
}
