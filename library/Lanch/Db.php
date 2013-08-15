<?php
class Lanch_Db
{
    public static function factory($type)
    {
        $db = null;

        switch ($type) {
            case 'mysql':
                $db = Zend_Db::factory('pdo_mysql', array(
                    'host' => 'localhost',
                    'username' => 'sjinchuk_user1',
                    'password' => 'g0v!ral',
                    'dbname' => 'sjinchuk_lanchbase'
                ));

                $db->query('set names utf8;');
                
                break;
        }

        return $db;
    }
}