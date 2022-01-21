<?php
declare(strict_types=1);
namespace App\Controller;

use App\FileValidator;
use App\FlashMessage;
use App\Movie;
use App\Registry;
use App\Repository\MovieRepository;
use Exception;
use PDOException;
use Webmozart\Assert\Assert;

class MovieController
{
    public function list(){
        $userId = null;
        session_start();

        if(isset($_SESSION['userLoged'])){
            $userId = $_SESSION['userLoged'];
        }
        $msjBienvenida = "";

        if($userId != null){
            if (array_key_exists("idSelected", $_SESSION)) {
                unset($_SESSION["idSelected"]);
            }

            $_SESSION["tokenCreateMovie"] = bin2hex(random_bytes(8));

            $msjBienvenida = "Bienvenido, nuevo usuario";
            $userId = 1;
            try {
                $movieRepo = new MovieRepository();
                $movies = $movieRepo->findAll();

            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        require __DIR__ . '../../../views/index.view.php';
    }

    public function view(int $id){
        try{
            $movieRepo = new MovieRepository();
            $movieSelected = $movieRepo->find($id);
        }catch (PDOException $e){
            die($e-> getMessage ());
        }
        require  __DIR__."../../../views/movie.view.php";
    }


    public function create(){
        $title = "";
        $releaseDate = "";
        $overview = "";
        $rating = 0;
        $poster = "";

        $errors = [];
        $isPost = false;

        session_start();
        $token = $_SESSION["tokenCreateMovie"];

        $title = FlashMessage::get("title");
        $releaseDate = FlashMessage::get("release-date");
        $overview = FlashMessage::get("overview");
        $rating = FlashMessage::get("rating");

        if($rating == ""){
            $rating = 0;
        }
        $poster = FlashMessage::get("file");

        $errors = FlashMessage::get("errors");

        if($errors == []){
            $isPost = true;
        }
        if($errors == ""){
            $errors = [];
        }

        if (isPost()) {
            $isPost = true;
            if($_POST["token"] == $_SESSION["tokenCreateMovie"]){
                FlashMessage::set("post", $_POST);
                FlashMessage::set("file",  $_FILES["fileUpload"]);
                header("Location: ".Registry::get(Registry::ROUTER)->generate("movie_createStore"));
            }else{
                $errors[] = "Error en la validación del token";
            }
        }

        require __DIR__."../../../views/movies-create.view.php";
    }

    public function createStore(){
        $errors = [];

        session_start();
        $data =  FlashMessage::get("post");
        $file = FlashMessage::get("file");

        try{
            Assert::lengthBetween($data["title"], 1, 100);
            FlashMessage::set("title", cleanText($data["title"]));
        }catch (Exception $ex){
            $errors[] = "Error en validar el títol";
        }

        try{
            Assert::lengthBetween($data["overview"], 1, 1000);
            FlashMessage::set("overview", cleanText($data["overview"]));
        }catch (Exception $ex){
            $errors[] = "Error en validar la sinopsi";
        }

        try{
            Assert::notEmpty($data["release-date"]);
            if(validate_date($data["release-date"])){
                FlashMessage::set("release-date", $data["release-date"]);
            }else{
                throw new Exception();
            }

        }catch (Exception $ex){
            $errors[] = "Cal indicar una data correcta";
        }

        $ratingTemp = 0;
        try{

            if(array_key_exists("rating", $data))
                $ratingTemp = intval($data["rating"]);

            Assert::range($ratingTemp,1,6);
            FlashMessage::set("rating", $ratingTemp);
        }catch (Exception $ex){
            $errors[] = "El rating ha de ser un enter entre 1 i 5";
        }

        try{
            if(!FileValidator::checkIfFileOk($file)){
                $errors[] = "Se ha subido mal el poster";
                throw new Exception();
            }

            if(!FileValidator::checkIfFileSizeOk($file, 1048576)){
                $errors[] = "El tamaño del poster es muy grande";
                throw new Exception();
            }

            if(!FileValidator::checkIfFileTypeOk($file, 'image/png')){
                $errors[] = "El formato del poster es incorrecto (ha de ser png)";
                throw new Exception();
            }

            $randomName = FileValidator::generateRandomName();
            $file['name'] = $randomName;
            FileValidator::transformToPoster($file);
        }catch (Exception $e){

        }

        if(is_empty($errors)){
            $movieData = $data;
            $movieData["poster"] = $file["name"];

            $movie = Movie::fromArray($movieData);

            $movieRepo = new MovieRepository();
            $movieRepo->save($movie);

            FlashMessage::set("errors", []);
        }else{
            FlashMessage::set("errors", $errors);
        }
        header("Location: ".Registry::get(Registry::ROUTER)->generate("movie_create"));
    }


    public function edit(int $id){
        $movieRepo = new MovieRepository();
        $movieSelected = $movieRepo->find($id);

        session_start();

        $title = FlashMessage::get("title");
        if($title == '')
            $title = $movieSelected->getTitle();

        $releaseDate = FlashMessage::get("releaseDate");
        if($releaseDate == '')
            $releaseDate = $movieSelected->getReleaseDate();

        $overview = FlashMessage::get("releaseDate");
        if($overview == '')
            $overview = $movieSelected->getOverview();

        $rating = FlashMessage::get("rating");
        if($rating == '')
            $rating = $movieSelected->getStarsRating();

        $poster = FlashMessage::get("poster");
        if($poster == '')
            $poster = $movieSelected->getPoster();


        $movie = [];
        $errors = [];
        $isPost = false;

        if (isPost()) {
            $isPost = true;

            if (validate_string($_POST["title"], 1, 100 )){
                $movie["title"] = cleanText($_POST["title"]);
                FlashMessage::set("title", $movie["title"]);
            }
            else
                $errors[] = "Error en validar el títol";

            if (validate_string($_POST["overview"], 1, 1000 )){
                $movie["overview"] = cleanText($_POST["overview"]);
                FlashMessage::set("overview", $movie["overview"]);
            }
            else
                $errors[] = "Error en validar la sinopsi";


            if (!empty($_POST["release-date"]) && (validate_date($_POST["release-date"]))){
                $movie["release-date"] = $_POST["release-date"];
                FlashMessage::set("releaseDate", $movie["release-date"]);
            }
            else
                $errors[] = "Cal indicar una data correcta";

            $ratingTemp = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);

            if (!empty($ratingTemp) && ($ratingTemp >0 && $ratingTemp<=5)){
                $movie["rating"] = $ratingTemp;
                FlashMessage::set("rating", $movie["rating"]);
            }
            else
                $errors[] = "El rating ha de ser un enter entre 1 i 5";

            try{
                if(!FileValidator::checkIfFileOk($_FILES["fileUpload"])){
                    $errors[] = "Se ha subido mal el poster";
                    throw new Exception();
                }

                if(!FileValidator::checkIfFileSizeOk($_FILES["fileUpload"], 1048576)){
                    $errors[] = "El tamaño del poster es muy grande";
                    throw new Exception();
                }

                if(!FileValidator::checkIfFileTypeOk($_FILES["fileUpload"], 'image/png')){
                    $errors[] = "El formato del poster es incorrecto (ha de ser png)";
                    throw new Exception();
                }

                $randomName = FileValidator::generateRandomName();
                $_FILES["fileUpload"]['name'] = $randomName;

                $movie["poster"] = $_FILES["fileUpload"]['name'];
                FlashMessage::set("poster", $movie["poster"]);
            }catch (Exception $e){

            }

            if(is_empty($errors)){

                $pdo = Registry::get(Registry::PDO);

                $movieRepo = new MovieRepository();
                $movie = Movie::fromArray($movie);
                $movieRepo->change($movie, $id);

            }
        }

        require __DIR__."../../../views/movies-edit.view.php";
    }

    public function deleteWithId(int $id){
        session_start();
        $title = "";
        $releaseDate = "";
        $overview = "";
        $rating = 0;
        $poster = "";
        $msjError = "";
        $isMovieDeleted = false;

        $movieRepo = new MovieRepository();
        $movieSelected = $movieRepo->find($id);

        $title = $movieSelected->getTitle();
        $releaseDate = $movieSelected->getReleaseDate();
        $overview = $movieSelected->getOverview();
        $rating = $movieSelected->getStarsRating();
        $poster = $movieSelected->getPoster();


        if (isPost() || array_key_exists("idSelected", $_SESSION)){
            $movieRepo = new MovieRepository();
            $movieRepo->remove($id);
            $isMovieDeleted = true;
        }

        require __DIR__."../../../views/movies-delete.view.php";
    }

    public function deleteWithoutId(){
        session_start();
        if(array_key_exists("idSelected", $_SESSION)){
            unset($_SESSION["idSelected"]);
        }

        $id = 0;
        $title = "";
        $releaseDate = "";
        $overview = "";
        $rating = 0;
        $poster = "";
        $msjError = "";
        $isMovieDeleted = false;

        if (isPost()) {
            if(array_key_exists("idSelected",$_POST)){
                $id = intval($_POST["idSelected"]);
                echo "eliminado Por id ".$id;
                $_SESSION["idSelected"] = $id;
                header("Location: ".Registry::get(Registry::ROUTER)->generate("movie_deleteWithId", ["id" => $id]));
            }else{
                if(array_key_exists("idEliminar",$_POST)){
                    try{
                        if($_POST["idEliminar"] === ""){
                            throw new Exception();
                        }
                        $id = $_POST["idEliminar"];

                        $movieRepo = new MovieRepository();
                        $movieSelected = $movieRepo->find((int) $id);

                        if($movieSelected == null){
                            throw new Exception();
                        }

                        $title = $movieSelected->getTitle();
                        $releaseDate = $movieSelected->getReleaseDate();
                        $overview = $movieSelected->getOverview();
                        $rating = $movieSelected->getStarsRating();
                        $poster = $movieSelected->getPoster();

                    }catch (Exception $e){
                        $id = 0;
                        $msjError = "No hemos encontrado ninguna película con esa ID";
                    }
                }
            }
        }
        require __DIR__."../../../views/movies-delete.view.php";
    }
}