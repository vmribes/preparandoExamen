<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <title>Iniciando Sesión</title>
    <meta name="description" content="PHP, PHPStorm">
    <meta name="author" content="Homer Simpson">
    <style>
        body {
            font-family: "Bitstream Vera Serif"
        }
    </style>
</head>

<body>
<h1>Iniciar Sesión:</h1>
<form action="" method="post">
    <p>Nombre de Usuario <input type="text" name="user" id="user" value="<?php if($lastUser != ""){echo $lastUser;} ?>"> </p>
    <p>Contraseña <input type="password" name="pass" id="pass"> </p>
    <input type="submit" value="Iniciar Sesión">
</form>
<br>
<ul>
    <?php
    if(count($errors) > 0){
        for($i = 0; $i < count($errors); $i++){
            ?>
            <li><?php echo $errors[$i]; ?></li>
            <?php
        }
    }

    ?>
</ul>
<button><a href="./register.php">Registrar Nuevo Usuario</a> </button>
</body>
</html>