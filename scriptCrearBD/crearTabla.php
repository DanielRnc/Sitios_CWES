<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=examen", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "CREATE TABLE producto (
            nombre VARCHAR(150) NOT NULL,
            precio INT NOT NULL DEFAULT 0,
            stock INT NOT NULL DEFAULT 0
        );";

        $conn->exec($query);
        
        echo "La tabla productos ha sido creada. <br/>"; 
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>