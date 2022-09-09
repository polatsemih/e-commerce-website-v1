<?php
class ErrorController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
    function Exception()
    {
        parent::GetView('Error/Exception');
    }
    function ShutDown()
    {
        parent::GetView('Error/Shutdown');
    }
}
