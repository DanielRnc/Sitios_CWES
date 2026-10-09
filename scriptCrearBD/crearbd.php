<?php
    // Credenciales
    $servername = "localhost";
    $username = "root";
    $password = "";

    // Conexión a la base de datos.

    try{
        $conn = new PDO("mysql:host=$servername", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "CREATE DATABASE examen";
        $conn->exec($query);

        echo "Base de datos creada.<br>";
    } catch(PDOException $e){
        echo "Ha ocurrido un error: <br>" . $e->getMessage();
    }