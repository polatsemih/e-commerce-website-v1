<?php
class ControllerAdmin
{
    function __construct()
    {
        try {
            if (!empty(session_name(SESSION_NAME)) && session_set_cookie_params(array('lifetime' => SESSION_LIFETIME, 'path' => SESSION_PATH, 'domain' => SESSION_DOMAIN, 'secure' => SESSION_SECURE, 'httponly' => SESSION_HTTP_ONLY, 'samesite' => SESSION_SAMESITE)) && session_start() && date_default_timezone_set('Europe/Istanbul')) {
                $this->web_data = array();
                $this->action_control = new Action();
                $this->cookie_control = new Cookie();
                $this->input_control = new Input();
                $this->notification_control = new Notification();
                $this->password_control = new Password();
                $this->session_control = new Session();
                $this->GetModel('ActionModel');
                $this->GetModel('AdminModel');
                $this->GetModel('FilterModel');
                $this->GetModel('ItemModel');
                $this->GetModel('LogModel');
                $this->GetModel('UserModel');
                if (!empty($_SESSION[SESSION_OBSOLETE_NAME]) && $_SESSION[SESSION_OBSOLETE_NAME] < time()) {
                    if ($this->session_control->KillAllSessions() && session_regenerate_id()) {
                        $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                    } else {
                        $this->input_control->Redirect(URL_EXCEPTION);
                    }
                }
                if (!empty($_SESSION[SESSION_REFRESH_NAME])) {
                    if ($_SESSION[SESSION_REFRESH_NAME] < time()) {
                        $_SESSION[SESSION_OBSOLETE_NAME] = time() + (60 * 5);
                        if (session_regenerate_id()) {
                            $this->session_control->KillSession(SESSION_OBSOLETE_NAME);
                            $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                        } else {
                            $this->input_control->Redirect(URL_EXCEPTION);
                        }
                    }
                } else {
                    $_SESSION[SESSION_REFRESH_NAME] = time() + (60 * 15);
                }
                if (!empty($_SESSION[SESSION_ADMIN_MENU_NAME]) && $_SESSION[SESSION_ADMIN_MENU_NAME]) {
                    $this->web_data['admin_menu'] = 1;
                } else {
                    $this->web_data['admin_menu'] = 0;
                }
            }
        } catch (\Throwable $th) {
            require_once 'View/Error/Shutdown.php';
            exit(0);
        }
    }
    function GetModel(string $model)
    {
        require_once 'Model/' . $model . '.php';
        $this->$model = new $model();
    }
    function GetView(string $view, array $web_data = null)
    {
        require_once 'View/' . $view . '.php';
        exit(0);
    }
    function SetCSRFToken(string $csrf_form)
    {
        $csrf_token = $this->input_control->GenerateToken();
        if ($csrf_token['result'] && $this->LogModel->CreateLogCSRF(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'csrf_token' => $csrf_token['data'], 'csrf_form' => $csrf_form, 'date_csrf_expiry' => date('Y-m-d H:i:s', time() + (CSRF_TOKEN_EXPIRY))))['result']) {
            return $csrf_token['data'];
        }
        $this->input_control->Redirect(URL_SHUTDOWN);
    }
    function CheckCSRFToken(string $csrf_token, string $csrf_form)
    {
        $log_csrf = $this->LogModel->GetLogCSRF(array($_SERVER['REMOTE_ADDR'], $csrf_form));
        if ($log_csrf['result'] && $csrf_token == $log_csrf['data']['csrf_token'] && $log_csrf['data']['date_csrf_expiry'] > date('Y-m-d H:i:s') && $log_csrf['data']['is_csrf_used'] == 0 && $this->LogModel->UpdateLogCSRF(array('is_csrf_used' => 1, 'date_csrf_used' => date('Y-m-d H:i:s'), 'id' => $log_csrf['data']['id']))['result']) {
            return true;
        }
        $this->notification_control->SetNotification('DANGER', TR_NOTIFICATION_ERROR_CSRF);
        return false;
    }
    function KillAuthentication(string $killed_function)
    {
        if (!empty($this->web_data['session_authentication_id'])) {
            $this->ActionModel->UpdateSessionAuthentication(array('is_session_authentication_killed' => 1, 'date_session_authentication_killed' => date('Y-m-d H:i:s'), 'session_authentication_killed_function' => $killed_function, 'id' => $this->web_data['session_authentication_id']));
            $this->session_control->KillSession(SESSION_AUTHENTICATION_NAME);
            $this->notification_control->SetNotification('WARNING', TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED);
        }
    }
}
