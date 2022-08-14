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
    function GenerateCookieSalt() {
        return strtr(sodium_bin2base64(random_bytes(112), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'H', '_' => 'K'));
    }
    function GetCookie(string $cookie)
    {
        return stripslashes($cookie);
    }
    function SetCookie(string $cookie_name, string $cookie, int $expires, string $path, string $domain, bool $secure, bool $httponly, string $samesite)
    {
        setcookie($cookie_name, $cookie, array(
            'expires' => $expires,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite
        ));
    }
    function EmptyCookie(string $cookie_name)
    {
        setcookie($cookie_name, false, array(
            'expires' => time() - 1,
            'path' => '/',
            'domain' => DOMAIN,
            'secure' => SECURE,
            'httponly' => HTTP_ONLY,
            'samesite' => SAMESITE
        ));
    }
}
