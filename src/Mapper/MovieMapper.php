<?php
declare(strict_types=1);
namespace App\Mapper;

use App\FlashMessage;
use App\Movie;
use App\Registry;
use PDO;

class MovieMapper
{
    public function __construct()
    {
        $this->pdo = Registry::get("PDO");
    }

    public function findAll(): array {
        $stmt = $this->pdo->query ('SELECT * from movie');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $movies = [];
        while ($row=$stmt->fetch()) {
            $movie = Movie::fromArray($row);
            array_push($movies, $movie);
        }
        return $movies;
    }

    public function find(int $id): ?Movie
    {
        $stmt = $this->pdo->prepare('SELECT * from movie WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $movieSelected = null;

        while ($row=$stmt->fetch()) {
            $movieSelected = Movie::fromArray($row);;
        }

        return $movieSelected;
    }


    public function insert(Movie $obj)
    {
        $data = $obj->toArray();
        unset($data["id"]);
        $stmt = $this->pdo->prepare('INSERT INTO movie (title, overview, `release-date`, rating, poster) VALUES (:t,:o,:rd,:r,:p)');
        $stmt->bindParam(':t', $data["title"]);
        $stmt->bindParam(':o', $data["overview"]);
        $stmt->bindParam(':rd', $data["release-date"]);
        $stmt->bindParam(':r', $data["rating"]);
        $stmt->bindParam(':p',  $data['poster']);
        $stmt->execute();
        $obj->setId((int)$this->pdo->lastInsertId());
    }

    public function update(Movie $object, int $id)
    {
        $object = $object->toArray();
        $sql = 'UPDATE movie SET title = ?, overview = ?, `release-date` = ?, rating = ?, poster = ? WHERE id = ?';
        $this->pdo->prepare($sql)->execute([$object["title"], $object["overview"], $object["release-date"], $object["rating"], $object['poster'], $id]);
    }

    public function delete(int $id){
        try{
            $sql = 'DELETE FROM movie WHERE id = ?';
            $this->pdo->prepare($sql)->execute([$id]);
        }catch (\Exception $e){
            FlashMessage::set("error", "No se ha podido eliminar la película. Sentimos las molestias.");
        }
    }
}