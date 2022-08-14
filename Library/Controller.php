<?php
class Controller
{
    private $web_data;
    function __construct()
    {
        $this->web_data = array();
        $this->session_control = new Session();
        $this->cookie_control = new Cookie();
        $this->input_control = new Input();
        $this->action_control = new Action();
        $this->password_control = new Password();
        $this->notification_control = new Notification();
        date_default_timezone_set('Europe/Istanbul');
        session_name(SESSION_NAME);
        if ($this->session_control->SessionSetCookieParams(LIFETIME, '/', DOMAIN, SECURE, HTTP_ONLY, SAMESITE)) {
            session_start();
        } else {
            // BRAND teknik bir hatadan dolayı ulaşılamıyor sayfası yap ve alttaki ile değiştir
            http_response_code(404);
            $this->GetView('Error/404NotFound');
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
}
