<?php
//app/Core/DB.php
class DB
{
    private static $instance = null;
    /**
     * Conexion con nuestra base de datos
     */
    public static function getInstance()
    {
        if (!self::$instance){
            self::$instance = new PDO('mysql:host=localhost;dbname=ejemplo-mvc-php', 'root', '');
        }
        return self::$instance;
    }
    /**
     * Ejercicio: mejora el fichero anterior definiendo en constantes los datos de la conexión:
     *servidor, bd, usuario y password
     */
}
?>