<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="utf-8">
    <title>Eliminando Movie</title>
    <meta name="description" content="PHP, PHPStorm">
    <meta name="author" content="Homer Simpson">
    <style>
        body {
            font-family: "Bitstream Vera Serif"
        }
    </style>
</head>

<body>
<?php

use App\FlashMessage;
use App\Registry;

if($isMovieDeleted){
    $error = FlashMessage::get("error");
    if($error == ''){ ?>
        <h1>Película eliminada satisfactoriamente</h1>
    <?php }else{ ?>
        <h1><?= $error ?></h1>
    <?php } ?>

    <button><a href="<?= Registry::get(Registry::ROUTER)->generate("movie_list")?>">Volver</a></button>
    <?php
}else{
    if ($id != 0) { ?>

        <table>
            <tr>
                <th>Title</th>
                <td><?= $title ?></td>
            </tr>
            <tr>
                <th>Overview</th>
                <td><?= $overview ?></td>
            </tr>
            <tr>
                <th>Release date</th>
                <td><?= date("d/m/Y", strToTime($releaseDate)) ?></td>
            </tr>
            <tr>
                <th>Rating</th>
                <td><?= $rating ?></td>
            </tr>
            <tr>
                <th>Poster</th>
                <td><img src="<?php echo $poster; ?>" /></td>
            </tr>
        </table>
        <form action="" method="post" >
            <input type="hidden" name="idSelected" value="<?php echo $id; ?>">
            <input type="submit" value="Eliminar">
        </form>
        <button><a href="<?= Registry::get(Registry::ROUTER)->generate("movie_deleteWithoutId")?>">Eliminar Otra</a></button>
        <button><a href="<?= Registry::get(Registry::ROUTER)->generate("movie_list")?>">Cancelar</a></button>
    <?php }else{
        ?>
        <h1>No hay ninguna película seleccionada</h1>
        <h3>Por favor, introduzca la id de la película que desea eliminar</h3>
        <form action="" method="post" >
            <input type="number" name="idEliminar" id="idEliminar"><input type="submit" value="Buscar">
        </form>
        <p><?php if($msjError != ""){echo $msjError;} ?></p>
        <button><a href="<?= Registry::get(Registry::ROUTER)->generate("movie_list")?>">Cancelar</a></button>
        <?php
    }} ?>
<br>
</body>

</html>