<?php
declare(strict_types=1);
namespace App\Controller;

require_once '../bootstrap.php';

use App\FlashMessage;
use App\Registry;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use PDOException;

class UserController
{
    public function login(){
        $errors = [];
        $lastUser = "";
        session_start();


        if (!empty($_COOKIE["last_used_name"]))
        {
            $lastUser = $_COOKIE["last_used_name"];
        }

        if (isPost()) {

            $userName = "";
            $pass = "";

            if (!empty($_POST["user"])) {
                $userName = $_POST["user"];
            } else {
                array_push($errors, "Has de introducir un nombre de usuario.");
            }

            if (!empty($_POST["pass"])) {
                $pass = $_POST["pass"];
            } else {
                array_push($errors, "Has de introducir una contraseña.");
            }

            if (count($errors) == 0) {

                if (!array_key_exists("userLoged", $_SESSION)) {
                    $userRepo = new UserRepository();
                    $userRepo->comprobarBD($userName, $pass);
                    $posibleError = FlashMessage::get("errors");
                    if( $posibleError != ''){
                        array_push($errors, $posibleError);
                    }
                } else {
                    $coincidencia = false;
                    $coincidenciaIndex = -1;
                    for ($i = 0; $i < count($_SESSION["userLoged"]); $i++) {
                        if ($_SESSION["userLoged"][$i]["user"] == $userName && $_SESSION["userLoged"][$i]["password"] == $pass) {
                            $coincidencia = true;
                            $coincidenciaIndex = $i;
                            break;
                        }
                    }
                    if ($coincidencia == true) {
                        $this->conectar($_SESSION["userLoged"][$coincidenciaIndex]["id"], $_SESSION["userLoged"][$coincidenciaIndex]["user"]);
                    } else {
                        $userRepo = new UserRepository();
                        $userRepo->comprobarBD($userName, $pass);
                        $posibleError = FlashMessage::get("errors");
                        if( $posibleError != ''){
                            array_push($errors, $posibleError);
                        }
                    }
                }
            }
        }
        require __DIR__."../../../views/login.view.php";
    }

    public function logout(){
        session_start();
        if(!empty($_COOKIE["last_used_name"])){
            setcookie("last_used_name", "", -1);
        }
        session_unset();
        session_destroy();
        header("Location: ".Registry::get(Registry::ROUTER)->generate("movie_list"));
        exit;
    }


}