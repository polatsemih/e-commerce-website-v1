<?php
class Cookie
{
    function __construct()
    {
    }
    function GenerateCookieAuthToken()
    {
        return strtr(sodium_bin2base64(random_bytes(375), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'A', '_' => 'M'));
    }
    function GenerateCookieSalt()
    {
        return strtr(sodium_bin2base64(random_bytes(112), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'H', '_' => 'K'));
    }
    function SetCookie(string $cookie_name, string $cookie, int $expires)
    {
        return setcookie($cookie_name, $cookie, array('expires' => $expires, 'path' => COOKIE_PATH, 'domain' => COOKIE_DOMAIN, 'secure' => COOKIE_SECURE, 'httponly' => COOKIE_HTTP_ONLY, 'samesite' => COOKIE_SAMESITE));
    }
    function EmptyCookie(string $cookie_name)
    {
        return setcookie($cookie_name, false, array('expires' => time() - 1, 'path' => COOKIE_PATH, 'domain' => COOKIE_DOMAIN, 'secure' => COOKIE_SECURE, 'httponly' => COOKIE_HTTP_ONLY, 'samesite' => COOKIE_SAMESITE));
    }
}
