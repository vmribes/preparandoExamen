<?php
declare(strict_types=1);
namespace App\Repository;


use App\Mapper\MovieMapper;
use App\Movie;

class MovieRepository
{
    public MovieMapper $mapper;
    public function __construct()
    {
        $this->mapper = new MovieMapper();
    }

    public function findAll():array {
        return $this->mapper->findAll();
    }

    public function find(int $id):?Movie {
        return $this->mapper->find($id);
    }

    public function save(Movie $movie) {
        $this->mapper->insert($movie);
    }

    public function change(Movie $movie, int $id){
        $this->mapper->update($movie, $id);
    }

    public function remove(int $movieId) {
        $this->mapper->delete($movieId);
    }
}