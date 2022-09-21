<?php
class ControllerOrder
{
    function __construct()
    {
        try {
            if (date_default_timezone_set('Europe/Istanbul')) {
                $this->web_data = array();
                $this->action_control = new Action();
                $this->cookie_control = new Cookie();
                $this->input_control = new Input();
                $this->notification_control = new Notification();
                $this->GetModel('ActionModel');
                $this->GetModel('FilterModel');
                $this->GetModel('ItemModel');
                $this->GetModel('LogModel');
                $this->GetModel('UserModel');
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
    function LogView(string $viewed_page)
    {
        $log_view_error = true;
        if ($this->LogModel->CreateLogViewAll(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'viewed_page' => $viewed_page))['result']) {
            $logViewOnce = $this->LogModel->GetLogViewOnce(array($_SERVER['REMOTE_ADDR'], $viewed_page));
            if ($logViewOnce['result']) {
                $log_view_error = false;
            } elseif (!empty($logViewOnce['empty']) && $this->LogModel->CreateLogViewOnce(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'viewed_page' => $viewed_page))['result']) {
                $log_view_error = false;
            }
        }
        if ($log_view_error) {
            $this->input_control->Redirect(URL_SHUTDOWN);
        }
    }
}
