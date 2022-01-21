<?php

namespace App;

class FlashMessage
{
    const SESSION_KEY = "flash-message";

    /**
     * obtenim el valor de l'array associat a la clau.
     * després de llegir-lo l'esborrem
     * si no existeix tornem el valor indicat per defecte.
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed|string
     */
    public static function get(string $key, $defaultValue = ''){
        if(!array_key_exists(self::SESSION_KEY, $_SESSION)){
            $_SESSION[self::SESSION_KEY] = [];
        }
        $value = $defaultValue;
        if(array_key_exists($key, $_SESSION[self::SESSION_KEY])){
            $value = $_SESSION[self::SESSION_KEY][$key];
        }

        self::unset($key);
        return $value;
    }

    /**
     * @param string $key
     * @param $value
     */
    public static function set(string $key, $value){
        if(!array_key_exists(self::SESSION_KEY, $_SESSION)){
            $_SESSION[self::SESSION_KEY] = [];
        }
        $_SESSION[self::SESSION_KEY][$key] = $value;
    }

    /**
     * @param string $key
     */
    private static function unset(string $key){
        unset($_SESSION[self::SESSION_KEY][$key]);
    }
}