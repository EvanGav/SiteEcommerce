<?php

namespace App\Config;

class Conf {

    static private array $databases = array(
        'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
        'database' => '',
        'login' => '',
        'password' => ''
    );

    static public function getHostname() : string{
        return static::$databases['hostname'];
    }

    static public function getDatabase() : string{
        return static::$databases['database'];
    }

    static public function getPassword() : string{
        return static::$databases['password'];
    }

    static public function getLogin() : string {
        return static::$databases['login'];
    }

    static public function getAbsoluteURL() : string {
        return "http://webinfo.iutmontp.univ-montp2.fr/~gavrielie/eCommerce/web-project/figu-vente/web/frontController.php";
    }
}