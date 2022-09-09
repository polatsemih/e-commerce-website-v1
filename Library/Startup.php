<?php
class StartUp
{
    function __construct()
    {
        if (WEB_SHUTDOWN_PHASE) {
            require_once 'View/Error/Shutdown.php';
            exit(0);
        }
        if (WEB_PREPARE_PHASE) {
            require_once 'View/Error/Prepare.php';
            exit(0);
        }
        if (WEB_MAINTENANCE_PHASE) {
            require_once 'View/Error/Maintenance.php';
            exit(0);
        }
        // print_r($_GET['url']);
        if (!empty($_GET['url'])) {
            $checked_url = $this->CheckUrl($_GET['url']);
            if (!empty($checked_url)) {
                $url_exploded = explode('%2F', $checked_url);
                foreach (URL_MAPS as $url_map) {
                    if (!empty($url_map['url_pattern'])) {
                        $url_pattern = explode('/', $url_map['url_pattern']);
                        if ((count($url_pattern) === count($url_exploded)) && (strlen($url_exploded[count($url_exploded) - 1]) <= ID_LENGTH)) {
                            $similar = true;
                            for ($i = 0; $i < count($url_pattern) - 1; $i++) {
                                if ($url_pattern[$i] != $url_exploded[$i]) {
                                    $similar = false;
                                    break;
                                }
                            }
                            if ($similar) {
                                $conroller_name = $url_map['controller'];
                                $conroller_method = $url_map['action'];
                                $conroller_param = $url_exploded[count($url_exploded) - 1];
                                break;
                            }
                        }
                    } elseif ($url_map['url'] === $checked_url) {
                        $conroller_name = $url_map['controller'];
                        $conroller_method = $url_map['action'];
                        break;
                    }
                }
            }
            if (empty($conroller_name) || empty($conroller_method)) {
                header('Location: ' . URL);
                exit(0);
            }
            require_once 'Controller/' . $conroller_name . '.php';
            $controller = new $conroller_name;
            if (!empty($conroller_param)) {
                $controller->$conroller_method($conroller_param);
            } else {
                $controller->$conroller_method();
            }
        } else {
            require_once 'Controller/HomeController.php';
            $controller = new HomeController();
            $controller->Index();
        }
    }
    function CheckUrl($url)
    {
        $tr = array('ş', 'Ş', 'ı', 'I', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'Ç', 'ç', '(', ')', ':', ',');
        $eng = array('s', 's', 'i', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c', '', '', '', '');
        $url = str_replace($tr, $eng, $url);
        $url = strtolower($url);
        $url = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $url);
        $url = preg_replace('/\s+/', '-', $url);
        $url = preg_replace('|-+|', '-', $url);
        $url = preg_replace('/#/', '', $url);
        return urlencode(trim($url, '/'));
    }
}
