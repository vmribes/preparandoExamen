<?php

use App\Config;
use App\Registry;

require 'vendor/autoload.php';

$pdo = new PDO(Config::getDsnByXML());
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try{

    Registry::set("PDO", $pdo);


    $router = new AltoRouter();
    Registry::set(Registry::ROUTER, $router);

    $router->map('GET', "/", "MovieController#list", "movie_list");

    $router->map('GET', "/movies/[i:id]/view", "MovieController#view", "movie_view");

    $router->map('GET|POST', '/movies/create', "MovieController#create", "movie_create");
    $router->map('GET', '/movies/create/store', 'MovieController#createStore', 'movie_createStore');

    $router->map('GET|POST', '/movies/[i:id]/edit', "MovieController#edit", "movie_edit");

    $router->map('GET|POST', '/movies/delete', "MovieController#deleteWithoutId", "movie_deleteWithoutId");
    $router->map('GET|POST', '/movies/[i:id]/delete', "MovieController#deleteWithId", "movie_deleteWithId");

    //UserController
    $router->map('GET|POST', "/login", "UserController#login", "user_login");

    $router->map('GET', "/logout", "UserController#logout", "user_logout");


}catch (Exception $e){
    die($e->getMessage());
}