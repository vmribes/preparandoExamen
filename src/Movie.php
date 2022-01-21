<?php

namespace App;


class Movie
{
    private ?int $id;
    private string $title;
    private string $overview;
    private string $releaseDate;
    private float $starsRating;
    private string $poster;

    const POSTER_PATH = "./uploads/";


    public function __construct(?int $id, $title, $overview, $releaseDate, $starsRating, $poster)
    {
        $this->id = $id;
        $this->title = $title;
        $this->overview = $overview;
        $this->releaseDate = $releaseDate;
        $this->starsRating = $starsRating;
        $this->poster = self::POSTER_PATH.$poster;
    }

    public function getId():?int{
        return $this->id;
    }

    public function setId(?int $id){
        $this->id = $id;
    }

    public function getTitle():string{
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function getOverview():string{
        return $this->overview;
    }

    public function setOverview($overview){
        $this->overview = $overview;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(string $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getStarsRating(): float
    {
        return $this->starsRating;
    }

    public function setStarsRating(float $starsRating): void
    {
        $this->starsRating = $starsRating;
    }

    public function getPoster(): string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): void
    {
        $this->poster = POSTER_PATH.$poster;
    }


    public static function fromArray(array $data): Movie
    {
        if (!array_key_exists("id", $data))
            $id = null;
        else
            $id = (int)$data["id"];

        return new Movie(
            $id,
            $data["title"],
            $data["overview"],
            $data["release-date"],
            (float)$data["rating"],
            $data["poster"]
        );
    }

    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "overview" => $this->getOverview(),
            "release-date" => $this->getReleaseDate(),
            "rating" => $this->getStarsRating(),
            "poster" => $this->getPoster()
        ];
    }
}