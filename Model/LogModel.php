<?php
class LogModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function CreateLogView(array $inputs)
    {
        return parent::Create(TABLE_LOG_VIEW, $inputs);
    }
    function GetLogViewCount(array $inputs)
    {
        return $this->database->GetWithColumnsByArrayCondition(TABLE_LOG_VIEW_COUNT, 'id', 'WHERE user_ip=? AND view=?', $inputs);
    }
    function CreateLogViewCount(array $inputs)
    {
        return parent::Create(TABLE_LOG_VIEW_COUNT, $inputs);
    }
    function CreateLogError(array $inputs)
    {
        return parent::Create(TABLE_LOG_ERROR, $inputs);
    }
    function GetLogCSRF(array $inputs)
    {
        return $this->database->GetWithColumnsByArrayCondition(TABLE_LOG_CSRF, 'id,csrf_token,date_csrf_expiry,is_csrf_used', 'WHERE user_ip=? AND csrf_form=? ORDER BY date_csrf_created DESC LIMIT 1', $inputs);
    }
    function CreateLogCSRF(array $inputs)
    {
        return parent::Create(TABLE_LOG_CSRF, $inputs);
    }
    function UpdateLogCSRF(array $inputs)
    {
        return parent::Update(TABLE_LOG_CSRF, $inputs);
    }
}
