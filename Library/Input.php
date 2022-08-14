<?php
class Input
{
    function __construct()
    {
    }
    function CheckUrl(array $allowed_get_params = array())
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
                $this->input_control->Redirect('/' . $_GET['url']);
            }
            $this->input_control->Redirect('/' . $_GET['url'] . rtrim($new_url, '&'));
        }
    }
    function Redirect(string $redirect_url = null)
    {
        header('Location: ' . URL . $redirect_url);
        exit(0);
    }
    function Check_GET_Input($get_input)
    {
        if (is_string($get_input) && !empty($get_input)) {
            return $this->PreventXSS(urlencode(stripslashes($get_input)));
        }
        return null;
    }
    function IsString($input)
    {
        if (is_string($input) && !empty($input)) {
            return stripslashes($input);
        }
        return null;
    }

    function IsInteger($input)
    {
        if (is_numeric($input)) {
            return (int)$input;
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
    function IsFloat($input)
    {
        if (is_numeric($input)) {
            return (float)$input;
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
    function NoWhiteSpace($input)
    {
        if (!str_contains($input, ' ')) {
            return $input;
        }
        return null;
    }
    function GenerateUrl($url)
    {
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
    function PreventXSS($input)
    {
        return htmlentities($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    function DecodePreventXSS($input)
    {
        return html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    function CheckEmail(string $e)
    {
        $email = filter_var($e, FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (str_contains($email, '@')) {
                $host_name = substr($email, strpos($email, '@') + 1);
                getmxrr($host_name, $result);
                if (((isset($result) && count($result) > 0) || $host_name === EMAIL_HOST_NAME)) {
                    return stripslashes($email);
                }
            }
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
    function CheckPostedInputs(array $posted_inputs)
    {
        $checked_inputs = array();
        $error = array();
        foreach ($posted_inputs as $key => $posted_input) {
            $input = $this->IsString($posted_input['input']);
            if (!is_null($input)) {
                if (!empty($posted_input['is_email'])) {
                    $input = $this->CheckEmail($input);
                    if (is_null($input)) {
                        $error['error_input'] = $key;
                        $error['error_message'] = $posted_input['error_message_is_email'];
                        return $error;
                    }
                }
                if (!empty($posted_input['no_white_space'])) {
                    $input = $this->NoWhiteSpace($input);
                    if (is_null($input)) {
                        $error['error_input'] = $key;
                        $error['error_message'] = $posted_input['error_message_no_white_space'];
                        return $error;
                    }
                }
                if (!empty($posted_input['length_control'])) {
                    if (!empty($posted_input['min_length'])) {
                        if (strlen($input) < $posted_input['min_length']) {
                            $error['error_input'] = $key;
                            $error['error_message'] = $posted_input['error_message_min_length'];
                            return $error;
                        }
                    }
                    if (!empty($posted_input['max_length'])) {
                        if (strlen($input) > $posted_input['max_length']) {
                            $error['error_input'] = $key;
                            $error['error_message'] = $posted_input['error_message_max_length'];
                            return $error;
                        }
                    }
                }
                if (!empty($posted_input['is_password'])) {
                    $input = $this->CheckPassword($input);
                    if (is_null($input)) {
                        $error['error_input'] = $key;
                        $error['error_message'] = $posted_input['error_message_is_password'];
                        return $error;
                    }
                }
                if (!empty($posted_input['preventxss'])) {
                    $input = $this->PreventXSS($input);
                }
                if (!empty($posted_input['length_limit'])) {
                    if (strlen($input) > $posted_input['length_limit']) {
                        $error['error_input'] = $key;
                        $error['error_message'] = $posted_input['error_message_length_limit'];
                        return $error;
                    }
                }
                $checked_inputs[$key] = $input;
            } else {
                $error['error_input'] = $key;
                $error['error_message'] = $posted_input['error_message_empty'];
                return $error;
            }
        }
        return $checked_inputs;
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
            return null;
        }
        $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
        $decrypted_padded_data = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
        if ($decrypted_padded_data === false) {
            sodium_memzero($ciphertext);
            sodium_memzero($key);
            return null;
        }
        $decrypted_data = sodium_unpad($decrypted_padded_data, $block_size <= 512 ? $block_size : 512);
        sodium_memzero($ciphertext);
        sodium_memzero($key);
        return $decrypted_data;
    }
    function GetItemsImages(array $item)
    {
        $item_images = explode('_', $item['item_images']);
        $item_images_name = array();
        for ($i = 0; $i < count($item_images); $i++) {
            $item_images_name[] = explode('-', $item_images[$i]);
        }
        $item['item_images'] = $item_images_name;
        return $item;
    }
    function GetItemsMainImage(array $relevant_items_from_db)
    {
        foreach ($relevant_items_from_db as $key => $relevant_item_from_db) {
            $item_images = explode('_', $relevant_item_from_db['item_images']);
            $item_images_name = array();
            for ($i = 0; $i < count($item_images); $i++) {
                $item_images_name[] = explode('-', $item_images[$i]);
            }
            $relevant_items_from_db[$key]['item_images'] = $item_images_name[0][1];
        }
        return $relevant_items_from_db;
    }







    function CheckPhoneNumber(string $phone)
    {
        return preg_match('/^(5)([0-9]{2})\s?([0-9]{3})\s?([0-9]{2})\s?([0-9]{2})$/', $phone, $matches) ? $matches[0] : null;
    }
    function FormatPriceAndGetFirstImageOfItems(array $items)
    {
        $format = new NumberFormatter('tr_TR', NumberFormatter::DECIMAL);
        foreach ($items as $key => $item) {
            if (isset($item['item_price'])) {
                if (is_float($item['item_price'])) {
                    $items[$key]['item_price'] = $format->format($item['item_price']);
                } else {
                    $items[$key]['item_price'] = null;
                }
            } else {
                $items[$key]['item_price'] = null;
            }
            if (isset($item['item_discount_price'])) {
                if (is_float($item['item_discount_price'])) {
                    $items[$key]['item_discount_price'] = $format->format($item['item_discount_price']);
                } else {
                    $items[$key]['item_discount_price'] = null;
                }
            } else {
                $items[$key]['item_price'] = null;
            }
            if (isset($item['item_images'])) {
                $item_images = explode('_', $item['item_images']);
                $item_image_name = array();
                for ($i = 0; $i < count($item_images); $i++) {
                    $item_image_name[] = explode('-', $item_images[$i]);
                }
                $items[$key]['item_images'] = $item_image_name[0][1];
            } else {
                $items[$key]['item_images'] = null;
            }
        }
        return $items;
    }
}