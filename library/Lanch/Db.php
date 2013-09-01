<?php
class Lanch_Db
{
    public static function factory($type)
    {
        $db = null;

        switch ($type)
        {
            case 'mysql':
                $options = ($_SERVER['HTTP_HOST'] == 'lanch.trunk') ? array(
                    'host' => 'localhost',
                    'username' => 'root',
                    'password' => 'root',
                    'dbname' => 'lanch'
                ) : array(
                    'host' => 'localhost',
                    'username' => 'sjinchuk_user1',
                    'password' => 'g0v!ral',
                    'dbname' => 'sjinchuk_lanchbase'
                );
                
                $db = Zend_Db::factory('pdo_mysql', $options);
                $db->query('set names utf8;');
                
                break;
        }

        return $db;
    }
}