<?php

class Core_ZendDb
{
    /**
     * @var Zend\Db\Adapter\Adapter
     */
    private static $_adapterInstance = null;

    public static function getAdapter()
    {
        if (self::$_adapterInstance === null) {
            self::$_adapterInstance = new Zend\Db\Adapter\Adapter(array(
                'driver' => 'Pdo_Mysql',
                'database' => Core_Config::get('db.database'),
                'username' => Core_Config::get('db.user'),
                'password' => Core_Config::get('db.password'),
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                )
            ));
        }
        return self::$_adapterInstance;
    }
}
