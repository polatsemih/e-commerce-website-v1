<?php
class Cookie
{
    function __construct()
    {
    }
    function GetCookie(string $cookie_name)
    {
        if (!empty($_COOKIE[$cookie_name]) && !empty(trim($_COOKIE[$cookie_name])) && is_string($_COOKIE[$cookie_name])) {
            return array('result' => true, 'data' => stripslashes($_COOKIE[$cookie_name]));
        }
        return array('result' => false);
    }
    function SetCookie(string $cookie_name, string $cookie, int $expires, string $path, string $domain, bool $secure, bool $httponly, string $samesite)
    {
        return setcookie($cookie_name, $cookie, array('expires' => $expires, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'httponly' => $httponly, 'samesite' => $samesite));
    }
    function EmptyCookie(string $cookie_name)
    {
        return setcookie($cookie_name, false, array('expires' => time() - 1, 'path' => COOKIE_PATH, 'domain' => COOKIE_DOMAIN, 'secure' => COOKIE_SECURE, 'httponly' => COOKIE_HTTP_ONLY, 'samesite' => COOKIE_SAMESITE));
    }
}
