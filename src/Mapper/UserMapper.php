<?php

namespace App\Mapper;

use App\FlashMessage;
use App\Registry;
use PDOException;
use App\Repository;

class UserMapper
{
    public function __construct()
    {
        $this->pdo = Registry::get("PDO");
    }

    public function findOne($userName, $pass)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * from user WHERE username = :username AND password = :pass');
            $stmt->bindParam(':username', $userName);
            $stmt->bindParam(':pass', $pass);
            $stmt->execute();
            $row = $stmt->fetch();
            if ($row != null) {
                $_SESSION["userLoged"][] = ["id" => $row["id"], "user" => $userName, "password" => $pass];


                conectar($row["id"], $userName);
//                conectar($row["id"], $userName);
            } else {
                FlashMessage::set("errors", "El usuario y la contraseña no coiciden. Por favor, vuelva a intentarlo");
            }
        } catch (PDOException $e) {
            echo "fail";
        }
    }
}