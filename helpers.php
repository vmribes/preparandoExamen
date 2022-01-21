<?php

use App\Registry;

function cleanText(string $value): string {
    $value = trim($value);
    return htmlspecialchars($value);
}

function isPost(): bool {
    return $_SERVER["REQUEST_METHOD"]==="POST";
}

function validate_string(string $string, int $minLength = 1, int $maxLength = 50000): bool
{
    if (strlen($string) < $minLength || strlen($string)>$maxLength)
        return false;

    return true;
}

function validate_date(string $date): bool {
    //option 3: simpler option
    if (DateTime::createFromFormat("Y-m-d", $date)===false)
        return false;

    return true;
}

function is_empty($value):bool {
    return empty($value);
}

// compare if the current value in the selected array
//function is_selected(string $value, array $array): bool {
//    if (in_array($value, $array))
//        return true;
//    return false;
//}

//function checkIfFileOk($file){
//    if(!empty($file) && $file['error'] ==  UPLOAD_ERR_OK){
//        return true;
//    }else{
//        return false;
//    }
//}

function conectar($userId, $userName)
{
    $_SESSION['userLoged'] = $userId;
    setcookie("last_used_name", $userName, mktime() . time() + 60 * 60 * 24 * 30);
    $_SESSION['expireSession'] = time() + (1 * 5);
    header("Location: ".Registry::get(Registry::ROUTER)->generate("movie_list"));
    exit;
}

?>