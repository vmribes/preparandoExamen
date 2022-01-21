<?php
use App\Registry;
?>
<button><a href="<?= Registry::get(Registry::ROUTER)->generate("movie_list") ?>">Volver</a></button>

<p>Id: <?php echo $movieSelected->getId(); ?></p>
<p>Title: <?php echo $movieSelected->getTitle(); ?></a></p>
<p>Overview: <?php echo $movieSelected->getOverview(); ?></p>
<p>Release Date: <?php echo $movieSelected->getReleaseDate(); ?></p>
<p>Stars Rating: <?php echo $movieSelected->getStarsRating(); ?></p>
<p>Poster: <img src="<?php echo $movieSelected->getPoster(); ?>"></p>

<button><a href="<?= Registry::get(Registry::ROUTER)->generate("movie_edit", ["id" => $movieSelected->getId()]) ?>">Editar</a></button>
<button><a href="<?=Registry::get(Registry::ROUTER)->generate("movie_deleteWithId", ["id" => $movieSelected->getId()]) ?>">Eliminar Película</a></button>

