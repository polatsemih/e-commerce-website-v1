<?php
class Password
{
    function __construct()
    {
    }
    function BcryptOptions()
    {
        $timeTarget = 0.05;
        $cost = 9;
        do {
            $cost++;
            $start = microtime(true);
            password_hash('test', PASSWORD_BCRYPT, ['cost' => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);
        return array('cost' => $cost);
    }
}
