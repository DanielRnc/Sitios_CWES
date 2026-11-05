<?php
//Conexion a la Base de datos (rpg_mvc)
class DB
{
    private static $instance = null;

    public static function getInstance()
    {
        if(!self::$instance){
            self::$instance = new PDO('mysql:host=localhost;dbname=rpg_mvc', 'root', 'root');
        }
        return self::$instance;
    }
}
?>