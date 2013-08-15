<?php
class Lanch_Authentication_Service
{
    public function isAuthenticated()
    {
        Zend_Session::start();
        return (!empty($_SESSION['authenticated'])) ? true : false;
    }

    public function authenticate($username, $password)
    {
        $out = false;

        if ($username == 'admin' && $password == 'linguA!23') {
            $_SESSION['authenticated'] = 1;
            $out = true;
        }

        return $out;
    }
}