<?php
class Session
{
    function __construct()
    {
    }
    function KillSession(string $session_name)
    {
        unset($_SESSION[$session_name]);
    }



    
    function KillAllSessions()
    {
        $_SESSION = array();
        if (ini_get('session.use_cookies') == 1) {
            $params = session_get_cookie_params();
            if (!empty($params)) {
                setcookie(SESSION_NAME, false, time() - 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
        }
        $result_session_destroy = session_destroy();
        if ($result_session_destroy) {
            return true;
        } else {
            return false;
        }
    }
    function GenerateSessionAuthenticationToken()
    {
        return strtr(sodium_bin2base64(random_bytes(191), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'R', '_' => 'O'));
    }
}
