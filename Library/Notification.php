<?php
class Notification
{
    function __construct()
    {
    }
    function Success(string $message)
    {
        return '<div class="not not-success"><span class="not-text">' . $message . '</span></div>';
    }
    function Warning(string $message)
    {
        return '<div class="not not-warning"><span class="not-text">' . $message . '</span></div>';
    }
    function Danger(string $message)
    {
        return '<div class="not not-danger"><span class="not-text">' . $message . '</span></div>';
    }
}
