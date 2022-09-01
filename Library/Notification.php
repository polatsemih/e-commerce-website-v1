<?php
class Notification
{
    function __construct()
    {
    }
    function SetNotification(string $status, string $message)
    {
        if ($status == 'DANGER') {
            $_SESSION[SESSION_NOTIFICATION_NAME] = '<div class="notification danger"><span class="text">' . $message . '</span></div>';
        } elseif ($status == 'SUCCESS') {
            $_SESSION[SESSION_NOTIFICATION_NAME] = '<div class="notification success"><span class="text">' . $message . '</span></div>';
        } else {
            $_SESSION[SESSION_NOTIFICATION_NAME] = '<div class="notification warning"><span class="text">' . $message . '</span></div>';
        }
    }
    function SetNotificationForjQ(string $status, string $message)
    {
        if ($status == 'DANGER') {
            return '<div class="notification danger"><span class="text">' . $message . '</span></div>';
        } elseif ($status == 'SUCCESS') {
            return '<div class="notification success"><span class="text">' . $message . '</span></div>';
        } else {
            return '<div class="notification warning"><span class="text">' . $message . '</span></div>';
        }
    }
}
