<?php
class Action
{
    function __construct()
    {
    }
    function CheckCaptcha($captcha_response)
    {
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, 'https://hcaptcha.com/siteverify');
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query(array('secret' => CAPTCHA_SECRET_KEY,'response' => $captcha_response,'sitekey' => CAPTCHA_SITE_KEY)));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $responseCapctha = json_decode(curl_exec($verify), true);
        if (!empty($responseCapctha['success']) && $responseCapctha['success'] == 1 && !empty($responseCapctha['hostname']) && $responseCapctha['hostname'] == CAPTCHA_HOSTNAME) { // !empty($responseCapctha['credit']) && $responseCapctha['credit'] == 1
            if ($responseCapctha['success'] == 1) { $success = 1; } else { $success = 0; }
            // if ($responseCapctha['credit'] == 1) { $credit = 1; } else { $credit = 0; }
            // return array('result' => true, 'success' => $success, 'credit' => $credit);
            return array('result' => true, 'success' => $success, 'credit' => 0);
        }
        return array('result' => false);
    }
    function GenerateConfirmTokenBytes()
    {
        $token = strtr(sodium_bin2base64(random_bytes(8), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'P', '_' => 'H', 'l' => 'r', 'I' => 'G', 'i' => 'a', 'İ' => 's', '0' => 'Z', 'O' => 'E', 'o' => 'N'));
        if (!empty($token) && strlen($token) == 11) {
            return array(substr($token, 2, 1), substr($token, 3, 1), substr($token, 4, 1), substr($token, 5, 1), substr($token, 6, 1), substr($token, 7, 1), substr($token, 8, 1), substr($token, 9, 1));
        }
        return null;
    }
    function HashedConfirmTokenBytes(string $bytes)
    {
        $token = strtr(sodium_bin2base64(hash_hmac('SHA512', $bytes, CONFIRM_TOKEN_SECRET_KEY, true), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'C', '_' => 'B'));
        if (!empty($token)) {
            return $token;
        }
        return null;
    }
    function SendMail(string $email, string $subject, string $message)
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . EMAIL_FROM_NAME . "<" . EMAIL_FROM_ADDRESS . ">" . "\r\n";
        $headers .= "Reply-To: noreply@" . EMAIL_HOST_NAME . "\r\n";
        return mail($email, $subject, $message, $headers);
    }
    function GenerateBaitToken()
    {
        $token = strtr(sodium_bin2base64(random_bytes(40), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => '1', '_' => '1'));
        if (!empty($token)) {
            return $token;
        }
        return null;
    }
}
