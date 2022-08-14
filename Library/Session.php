<?php
class Session
{
    function __construct()
    {
    }
    function SessionSetCookieParams(int $lifetime, string $path, string $domain, bool $secure, bool $httponly, string $samesite)
    {
        return session_set_cookie_params(array(
            'lifetime' => $lifetime,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite
        ));
    }
    function GenerateSessionAuthToken()
    {
        return strtr(sodium_bin2base64(random_bytes(191), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'R', '_' => 'O'));
    }
    function KillSession()
    {
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(SESSION_NAME, false, time() - 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
