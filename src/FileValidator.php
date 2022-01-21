<?php

namespace App;

class FileValidator
{

    public static function checkIfFileSizeOk($file, $size=1048576){
        if($file['size'] > $size){
            return false;
        }else{
            return true;
        }
    }

    public static function checkIfFileOk($file){
        if(!empty($file) && $file['error'] ==  UPLOAD_ERR_OK){
            return true;
        }else{
            return false;
        }
    }

    public static function checkIfFileTypeOk($file, $format = 'image/png'){
        if($file['type'] == $format){
            return true;
        }else{
            return false;
        }
    }

    public static function generateRandomName(){
        $randomName = date("Y-m-d:h:m:s");
        $randomName = $randomName.".png";
        return $randomName;
    }

    public static function transformToPoster($file){
        $url = "./uploads/".$file['name'];
        move_uploaded_file($file['tmp_name'], $url);
        FlashMessage::set("file", $file);
    }
}