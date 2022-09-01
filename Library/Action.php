<?php
class Action
{
    function __construct()
    {
    }
    function GenerateVerifyToken()
    {
        return strtr(sodium_bin2base64(random_bytes(191), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'R', '_' => 'O'));
    }
    function GenerateCancelRegisterToken()
    {
        return sodium_bin2base64(random_bytes(191), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
    }
    function GenerateResetPwdToken()
    {
        return strtr(sodium_bin2base64(random_bytes(172), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'P', '_' => 'O'));
    }
    function GenerateResetPwdPostToken()
    {
        return strtr(sodium_bin2base64(random_bytes(187), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'V', '_' => 'J'));
    }
    function GenerateBaitToken()
    {
        return strtr(sodium_bin2base64(random_bytes(40), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => '1', '_' => '1'));
    }
    function GenerateRandomByteConfirmToken()
    {
        $token = strtr(sodium_bin2base64(random_bytes(8), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'P', '_' => 'H', 'l' => 'r', 'ı' => 'T', 'I' => 'G', 'i' => 'a', 'İ' => 's', '0' => 'Z', 'O' => 'E', 'o' => 'N'));
        return array(substr($token, 2, 1), substr($token, 3, 1), substr($token, 4, 1), substr($token, 5, 1), substr($token, 6, 1), substr($token, 7, 1), substr($token, 8, 1), substr($token, 9, 1));
    }
    function HashRandomBytes(string $bytes)
    {
        return strtr(sodium_bin2base64(hash_hmac('SHA512', $bytes, CONFIRM_TOKEN_KEY, true), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'C', '_' => 'B'));
    }
    function CheckCaptcha($captcha_response)
    {
        if (!empty($captcha_response)) {
            $data = array(
                'secret' => SECRET_KEY,
                'response' => $captcha_response,
                'sitekey' => SITE_KEY
            );
            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, 'https://hcaptcha.com/siteverify');
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($verify);
            $responseData = json_decode($response);
            if ($responseData->success === true) { // && $responseData->credit === true
                return true;
            }
        }
        return false;
    }
    function SendMail(string $email, string $subject, string $message)
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . EMAIL_FROM_NAME . "<" . EMAIL_FROM_ADDRESS . ">" . "\r\n";
        $headers .= "Reply-To: noreply@" . EMAIL_HOST_NAME . "\r\n";
        $result = mail($email, $subject, $message, $headers);
        if ($result == true) {
            return 'Mail Sent';
        }
        return null;
    }
}
