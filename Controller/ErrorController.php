<?php
class ErrorController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
    function NotFound()
    {
        http_response_code(404);
        $this->GetView('Error/NotFound');
    }
}
