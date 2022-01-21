<?php

namespace App;

class Config
{

//    public static function getUploadsByXML(string $nameFile="../config.xml"){
//        $xml = simplexml_load_file($nameFile);
//        return $xml->rutaFotos;
//    }
//
//    public static function leerFicheroXML(string $nameFile="../config.xml"){
//        $configs = simplexml_load_file($nameFile);
//
//        echo $configs->dsn;
//        echo $configs->rutaFotos;
//    }

    public static function getDsnByXML(string $nameFile="../config.xml"){
        $xml = simplexml_load_file($nameFile);
        return $xml->dsn;
    }

    public static  function getDSByJSON(string $nameFile="../config.json"){
        $dsn = file_get_contents($nameFile);
        $conf = json_decode($dsn, true);
        return $conf["dsn"];
    }

    public static function getDSByIni(string $nameFile="../config.ini"){
        $contenido = parse_ini_file($nameFile, true);
        $contenidoDSN = $contenido["DSN"];
        $dsn = "mysql:host=".$contenidoDSN["host"]."; dbname=".$contenidoDSN["dbname"]."; user=".$contenidoDSN["user"]."; password=".$contenidoDSN["password"];
        return $dsn;
    }
}