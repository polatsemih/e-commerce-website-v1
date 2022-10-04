<?php
class LogModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function CreateLogViewAll(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_VIEW_ALL, $inputs);
    }
    function GetLogViewOnce(array $inputs)
    {
        return $this->database->Get(TABLE_LOG_VIEW_ONCE, 'id', 'WHERE user_ip=? AND viewed_page=?', $inputs, 'SINGULAR');
    }
    function CreateLogViewOnce(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_VIEW_ONCE, $inputs);
    }
    function CreateLogCSRF(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_CSRF, $inputs);
    }
    function GetLogCSRF(array $inputs)
    {
        return $this->database->Get(TABLE_LOG_CSRF, 'id,csrf_token,date_csrf_expiry,is_csrf_used', 'WHERE user_ip=? AND csrf_form=? ORDER BY date_csrf_created DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function UpdateLogCSRF(array $inputs)
    {
        return $this->database->Update(TABLE_LOG_CSRF, $inputs);
    }
    function CreateLogError(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_ERROR, $inputs);
    }
    function GetLogViewDaily(string $user_ip)
    {
        return $this->database->Get(TABLE_LOG_VIEW_DAILY_IP, 'date_viewed', 'WHERE user_ip=? ORDER BY date_viewed DESC LIMIT 1', $user_ip, 'SINGULAR');
    }
    function CreateLogViewDaily(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_VIEW_DAILY_IP, $inputs);
    }
}
