<?php
// Esta es una cajita de herramientas para ayudarnos con los archivos.
class FileHelper
{
    public static function fileHandler($origin, $destination)
    {
        if (file_exists($origin)) {
            if (move_uploaded_file($origin, $destination)) {
                return true;
            }
        }
        return false;
    }
}
