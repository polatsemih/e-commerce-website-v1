<?php
class ErrorController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
    function GoHome()
    {
        try {
            parent::GetView('Error/GoHome');
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ErrorController function GoHome | ' . $th));
            $this->input_control->Redirect(URL_SHUTDOWN);
        }
    }
    function Exception()
    {
        try {
            parent::GetView('Error/Exception');
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ErrorController function Exception | ' . $th));
            $this->input_control->Redirect(URL_SHUTDOWN);
        }
    }
    function ShutDown()
    {
        try {
            parent::GetView('Error/Shutdown');
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ErrorController function ShutDown | ' . $th));
            $this->input_control->Redirect(URL_SHUTDOWN);
        }
    }
    function UserBlocked()
    {
        try {
            parent::GetView('Error/Blocked');
        } catch (\Throwable $th) {
            $this->LogModel->CreateLogError(array('user_ip' => $_SERVER['REMOTE_ADDR'], 'error_message' => 'class ErrorController function UserBlocked | ' . $th));
            $this->input_control->Redirect(URL_SHUTDOWN);
        }
    }
}
