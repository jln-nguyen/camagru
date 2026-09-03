<?php

class Autoloader{

    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($class){
        $dirs = ['Core', 'Models', 'Controllers'];
        foreach ($dirs as $dir) {
            $path = __DIR__ . '/../' . $dir . '/' . $class . '.php';
            if (file_exists($path)) {
                require $path;
                return;
            }
        }
    }

}

?>