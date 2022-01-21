<?php
require_once '../bootstrap.php';

$match = $router ->match();

//echo "Hola, soy el contralador frontal<br>";

if($match === false){
    die("ruta no encontrada");
}

if (is_array($match)) {
    $temp = explode("#", $match["target"]);
    $controller = "\\App\\Controller\\" . $temp[0];
    $action = $temp[1];
    if (method_exists($controller, $action)) {
        $object = new $controller;
        call_user_func_array([$object, $action], $match['params']);
    } else {
        // no route was matched
        header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        echo "method not exists";
    }
} else {
    // no route was matched
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo "error";
}