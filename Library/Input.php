<?php
class Input
{
    function __construct()
    {
    }
    function Redirect(string $redirect_url = null)
    {
        header('Location: ' . URL . $redirect_url);
        exit(0);
    }
    function CheckUrl($allowed_get_params = array())
    {
        $allowed_get_params[] = 'url';
        $bad_url = false;
        $no_get_param = true;
        $new_url = '?';
        foreach ($_GET as $key => $get_value) {
            if (!in_array($key, $allowed_get_params)) {
                unset($_GET[$key]);
                $bad_url = true;
            } else if ($key != 'url') {
                $no_get_param = false;
                $new_url .= $key . '=' . $get_value . '&';
            }
        }
        if ($bad_url) {
            if ($no_get_param) {
                $this->Redirect($_GET['url']);
            }
            $this->Redirect($_GET['url'] . rtrim($new_url, '&'));
        }
    }
    function IsString($input)
    {
        if (!empty($input) && is_string($input) && !empty(trim($input))) {
            return stripslashes(trim($input));
        }
        return null;
    }
    function NumericIsString($input)
    {
        if (is_string($input)) {
            return stripslashes(trim($input));
        }
        return null;
    }
    function IsNumeric($input)
    {
        if (is_numeric($input)) {
            return $input;
        }
        return null;
    }
    function IsIntegerAndPositive($input)
    {
        if (is_numeric($input)) {
            $input = (int)$input;
            if ($input > 0) {
                return $input;
            }
        }
        return null;
    }
    function IsIntegerAndPositiveOrZero($input)
    {
        if (is_numeric($input)) {
            $input = (int)$input;
            if ($input >= 0) {
                return $input;
            }
        }
        return null;
    }
    function IsFloatAndPositive($input)
    {
        if (is_numeric($input)) {
            $input = (float)$input;
            if ($input > 0) {
                return $input;
            }
        }
        return null;
    }
    function IsEmail(string $e)
    {
        $email = filter_var($e, FILTER_SANITIZE_EMAIL);
        if (!empty($email)) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!empty($email) && str_contains($email, '@')) {
                $host_name = substr($email, strpos($email, '@') + 1);
                if (!empty($host_name)) {
                    getmxrr($host_name, $result);
                    if (((isset($result) && count($result) > 0) || $host_name === EMAIL_HOST_NAME)) {
                        return stripslashes($email);
                    }
                }
            }
        }
        return null;
    }
    function IsPhoneNumber(string $phone_number)
    {
        $result = preg_match('/^(5)([0-9]{2})\s?([0-9]{3})\s?([0-9]{2})\s?([0-9]{2})$/', $phone_number, $valid_number);
        if (!empty($result)) {
            return $valid_number[0];
        }
        return null;
    }
    function CheckPassword(string $pwd)
    {
        if (preg_match('/[a-z]/', $pwd) && preg_match('/[A-Z]/', $pwd) && preg_match('/[0-9]/', $pwd)) {
            return $pwd;
        }
        return null;
    }
    function NoWhiteSpace($input)
    {
        if (!str_contains($input, ' ')) {
            return $input;
        }
        return null;
    }
    function HasWhiteSpace($input)
    {
        if (str_contains($input, ' ')) {
            return $input;
        }
        return null;
    }
    function PreventXSSForId($input)
    {
        return htmlentities($input, ENT_QUOTES, 'UTF-8');
    }
    function PreventXSS($input)
    {
        return htmlentities($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    function SlashAndXSS($input)
    {
        return htmlentities(stripslashes($input), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    function SlashAndXSSForId($input)
    {
        return htmlentities(stripslashes($input), ENT_QUOTES, 'UTF-8');
    }
    function DecodePreventXSS($input)
    {
        return html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    function CheckGETInput($get_input)
    {
        $get_input = $this->IsString($get_input);
        if (!is_null($get_input)) {
            return $this->PreventXSS(urlencode($get_input));
        }
        return null;
    }
    function CheckGETInputForId($get_input)
    {
        $get_input = $this->IsString($get_input);
        if (!is_null($get_input)) {
            return $this->SlashAndXSSForId(urlencode($get_input));
        }
        return null;
    }
    function CheckGETInputWithLength($input, int $input_length)
    {
        $input = $this->IsString($input);
        if (!is_null($input)) {
            if (strlen($input) == $input_length) {
                return $this->PreventXSSForId(urlencode($input));
            }
        }
        return null;
    }
    function CheckGETInputWithMaxLength($input, int $input_length)
    {
        $input = $this->IsString($input);
        if (!is_null($input)) {
            if (strlen($input) <= $input_length) {
                return $this->PreventXSSForId(urlencode($input));
            }
        }
        return null;
    }
    function CheckPositiveGETInput($get_input)
    {
        $get_input = $this->IsIntegerAndPositiveOrZero($get_input);
        if (!is_null($get_input)) {
            return $this->PreventXSS(urlencode($get_input));
        }
        return null;
    }
    function CheckPositiveNonZeroGETInput($get_input)
    {
        $get_input = $this->IsIntegerAndPositive($get_input);
        if (!is_null($get_input)) {
            return $this->PreventXSS(urlencode($get_input));
        }
        return null;
    }
    function CheckPositivePOSTInput($get_input)
    {
        $get_input = $this->IsIntegerAndPositiveOrZero($get_input);
        if (!is_null($get_input)) {
            return $this->PreventXSS($get_input);
        }
        return null;
    }
    function CheckPostedInputs(array $posted_inputs)
    {
        $checked_inputs = array();
        $error = array();
        foreach ($posted_inputs as $key => $posted_input) {
            if (!empty($posted_input['numeric_is_string'])) {
                $input = $this->NumericIsString($posted_input['input']);
            } else {
                $input = $this->IsString($posted_input['input']);
            }
            if (!is_null($input)) {
                if (!empty($posted_input['no_white_space'])) {
                    $input = $this->NoWhiteSpace($input);
                    if (is_null($input)) {
                        $error['error_message'] = $posted_input['error_message_no_white_space'];
                        return $error;
                    }
                }
                if (!empty($posted_input['has_white_space'])) {
                    $input = $this->HasWhiteSpace($input);
                    if (is_null($input)) {
                        $error['error_message'] = $posted_input['error_message_has_white_space'];
                        return $error;
                    }
                }
                if (!empty($posted_input['is_email'])) {
                    $input = $this->IsEmail($input);
                    if (is_null($input)) {
                        $error['error_message'] = $posted_input['error_message_is_email'];
                        return $error;
                    }
                }
                if (!empty($posted_input['length_control'])) {
                    if (!empty($posted_input['min_length'])) {
                        if (strlen($input) < $posted_input['min_length']) {
                            $error['error_message'] = $posted_input['error_message_min_length'];
                            return $error;
                        }
                    }
                    if (!empty($posted_input['max_length'])) {
                        if (strlen($input) > $posted_input['max_length']) {
                            $error['error_message'] = $posted_input['error_message_max_length'];
                            return $error;
                        }
                    }
                }
                if (!empty($posted_input['is_password'])) {
                    $input = $this->CheckPassword($input);
                    if (is_null($input)) {
                        $error['error_message'] = $posted_input['error_message_is_password'];
                        return $error;
                    }
                }
                if (!empty($posted_input['preventxss'])) {
                    $input = $this->PreventXSS($input);
                }
                if (!empty($posted_input['preventxssforid'])) {
                    $input = $this->PreventXSSForId($input);
                }
                if (!empty($posted_input['length_limit'])) {
                    if (strlen($input) > $posted_input['length_limit']) {
                        $error['error_message'] = $posted_input['error_message_length_limit'];
                        return $error;
                    }
                }
                if (!empty($posted_input['is_numeric'])) {
                    $input = $this->IsNumeric($input);
                    if (is_null($input)) {
                        $error['error_message'] = $posted_input['error_message_is_numeric'];
                        return $error;
                    }
                }
                if (!empty($posted_input['is_integer_or_zero'])) {
                    $input = $this->IsIntegerAndPositiveOrZero($input);
                    if (is_null($input)) {
                        $error['error_message'] = $posted_input['error_message_is_integer_or_zero'];
                        return $error;
                    }
                }
                if (!empty($posted_input['is_integer_and_positive'])) {
                    $input = $this->IsIntegerAndPositive($input);
                    if (is_null($input)) {
                        $error['error_message'] = $posted_input['error_message_is_integer_and_positive'];
                        return $error;
                    }
                }
                if (!empty($posted_input['is_float_and_positive'])) {
                    $input = $this->IsFloatAndPositive($input);
                    if (is_null($input)) {
                        $error['error_message'] = $posted_input['error_message_is_float_and_positive'];
                        return $error;
                    }
                }
                if (!empty($posted_input['is_phone_number'])) {
                    $input = $this->IsPhoneNumber($input);
                    if (is_null($input)) {
                        $error['error_message'] = $posted_input['error_message_is_phone_number'];
                        return $error;
                    }
                }
                $checked_inputs[$key] = $input;
            } else {
                $error['error_message'] = $posted_input['error_message_empty'];
                return $error;
            }
        }
        return $checked_inputs;
    }
    function GetItemsMainImageAndFormatedPrice(array $items)
    {
        foreach ($items as $key => $item) {
            $item_images = explode('_', $item['item_images']);
            $item_images_name = array();
            for ($i = 0; $i < count($item_images); $i++) {
                $item_images_name[] = explode('-', $item_images[$i]);
            }
            $items[$key]['item_images'] = $item_images_name[0][1];
            $item_price = $this->IsFloatAndPositive($items[$key]['item_price']);
            $item_discount_price = $this->IsFloatAndPositive($items[$key]['item_discount_price']);
            if (!is_null($item_price) && !is_null($item_discount_price)) {
                $format = new NumberFormatter('tr_TR', NumberFormatter::DECIMAL);
                $items[$key]['item_price'] = $format->format($item_price);
                $items[$key]['item_discount_price'] = $format->format($item_discount_price);
            } else {
                return array('result' => false);
            }
        }
        return array('result' => true, 'data' => $items);
    }
    function GetItemImagesAndFormatedPrice(array $item)
    {
        $format = new NumberFormatter('tr_TR', NumberFormatter::DECIMAL);
        $item_images = explode('_', $item['item_images']);
        $item_images_name = array();
        for ($i = 0; $i < count($item_images); $i++) {
            $item_images_name[] = explode('-', $item_images[$i]);
        }
        $item['item_images'] = $item_images_name;
        $item_price = $this->IsFloatAndPositive($item['item_price']);
        $item_discount_price = $this->IsFloatAndPositive($item['item_discount_price']);
        if (!is_null($item_price) && !is_null($item_discount_price)) {
            $format = new NumberFormatter('tr_TR', NumberFormatter::DECIMAL);
            $item['item_price'] = $format->format($item_price);
            $item['item_discount_price'] = $format->format($item_discount_price);
            return array('result' => true, 'data' => $item);
        }
        return array('result' => false);
    }
    function GetItemImages(array $item)
    {
        $item_images = explode('_', $item['item_images']);
        $item_images_name = array();
        for ($i = 0; $i < count($item_images); $i++) {
            $item_images_name[] = explode('-', $item_images[$i]);
        }
        $item['item_images'] = $item_images_name;
        return $item;
    }
    function GetItemImagesDirect(string $item)
    {
        $item_images = explode('_', $item);
        $item_images_name = array();
        for ($i = 0; $i < count($item_images); $i++) {
            $item_images_name[] = explode('-', $item_images[$i]);
        }
        $item = $item_images_name;
        return $item;
    }
    function GetItemMainImageAndFormatedPrice(array $item)
    {
        $item_images = explode('_', $item['item_images']);
        $item_images_name = array();
        for ($i = 0; $i < count($item_images); $i++) {
            $item_images_name[] = explode('-', $item_images[$i]);
        }
        $item['item_images'] = $item_images_name[0][1];
        $item_price = $this->IsFloatAndPositive($item['item_price']);
        $item_discount_price = $this->IsFloatAndPositive($item['item_discount_price']);
        if (!is_null($item_price) && !is_null($item_discount_price)) {
            $format = new NumberFormatter('tr_TR', NumberFormatter::DECIMAL);
            $item['item_price'] = $format->format($item_price);
            $item['item_discount_price'] = $format->format($item_discount_price);
            return array('result' => true, 'data' => $item);
        }
        return array('result' => false);
    }
    function FormatPrice($price)
    {
        $checked_price = $this->IsFloatAndPositive($price);
        if (!is_null($checked_price)) {
            $format = new NumberFormatter('tr_TR', NumberFormatter::DECIMAL);
            return array('result' => true, 'data' => $format->format($checked_price));
        }
        return array('result' => false);
    }
    function EncrypteData($data, $key, $block_size = 128)
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $padded_data = sodium_pad($data, $block_size <= 512 ? $block_size : 512);
        $encrypted_data = sodium_bin2base64($nonce . sodium_crypto_secretbox($padded_data, $nonce, $key), SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);
        $encrypted_data = str_replace('/', '_', $encrypted_data);
        $encrypted_data = str_replace('+', '-', $encrypted_data);
        sodium_memzero($data);
        sodium_memzero($key);
        return $encrypted_data;
    }
    function DecrypteData($encrypted_data, $key, $block_size = 128)
    {
        $encrypted_data = str_replace('_', '/', $encrypted_data);
        $encrypted_data = str_replace('-', '+', $encrypted_data);
        $decoded = sodium_base642bin($encrypted_data, SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);
        if (mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES)) {
            return array('result' => false);
        }
        $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
        $decrypted_padded_data = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
        if ($decrypted_padded_data === false) {
            sodium_memzero($ciphertext);
            sodium_memzero($key);
            return array('result' => false);
        }
        $decrypted_data = sodium_unpad($decrypted_padded_data, $block_size <= 512 ? $block_size : 512);
        sodium_memzero($ciphertext);
        sodium_memzero($key);
        return array('result' => true, 'data' => $decrypted_data);
    }
    function GenerateToken()
    {
        $generated_token = strtolower(strtr(sodium_bin2base64(random_bytes(150), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING),  array('-' => 'i', '_' => 'a')));
        if (!empty($generated_token) && strlen($generated_token) == 200) {
            return array('result' => true, 'data' => $generated_token);
        }
        return array('result' => false);
    }
    function GenerateFolderName()
    {
        $generated_token = strtolower(strtr(sodium_bin2base64(random_bytes(15), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING),  array('-' => 'r', '_' => 'f')));
        if (!empty($generated_token) && strlen($generated_token) == 20) {
            return array('result' => true, 'data' => $generated_token);
        }
        return array('result' => false);
    }
    function GenerateFileName()
    {
        $generated_token = strtolower(strtr(sodium_bin2base64(random_bytes(11), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING),  array('-' => 'r', '_' => 'f')));
        if (!empty($generated_token) && strlen($generated_token) == 15) {
            return array('result' => true, 'data' => $generated_token);
        }
        return array('result' => false);
    }
    function GenerateItemFileName()
    {
        $generated_token = strtolower(strtr(sodium_bin2base64(random_bytes(3), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING),  array('-' => 'r', '_' => 'f')));
        if (!empty($generated_token) && strlen($generated_token) == 4) {
            return array('result' => true, 'data' => $generated_token);
        }
        return array('result' => false);
    }
    function GenerateCartId()
    {
        $generated_token = strtolower(strtr(sodium_bin2base64(random_bytes(2), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING), array('-' => 'A', '_' => '9')));
        if (!empty($generated_token) && strlen($generated_token) == 3) {
            return array('result' => true, 'data' => $generated_token);
        }
        return array('result' => false);
    }
    function GenerateUrl($url)
    {
        $url = str_replace(array('<', '>', '£', '#', '$', '½', '{', '[', ']', '}', '|', '"', 'é', '!', "'", '^', '+', '%', '&', '(', ')', '=', '*', '?', '_', '@', '€', '₺', '¨', '~', 'æ', 'ß', '´', '`', ',', ';', '.', ':'), '', $url);
        $old = array('ş', 'Ş', 'ı', 'I', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'Ç', 'ç');
        $new = array('s', 's', 'i', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c');
        $url = str_replace($old, $new, $url);
        $tr = array('ş', 'Ş', 'ı', 'I', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'Ç', 'ç', '(', ')', '/', ':', ',');
        $eng = array('s', 's', 'i', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c', '', '', '-', '-', '');
        $url = str_replace($tr, $eng, $url);
        $url = strtolower($url);
        $url = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $url);
        $url = preg_replace('/\s+/', '-', $url);
        $url = preg_replace('|-+|', '-', $url);
        $url = preg_replace('/#/', '', $url);
        $url = str_replace('.', '', $url);
        $url = trim($url, '-');
        $url = stripslashes($url);
        return $url;
    }
}
