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
        $params = session_get_cookie_params();
        if (!empty($params)) {
            if (setcookie(SESSION_NAME, false, time() - 1, $params['path'], $params['domain'], $params['secure'], $params['httponly'])) {
                return true;
            }
        }
        return false;
    }
}
