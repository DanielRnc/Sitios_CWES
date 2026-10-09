<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "examen";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO producto (nombre, precio, stock) VALUES
            ('Mesa', 60, 100),
            ('Silla', 30, 500),
            ('Armario', 200, 200);";
        
        $conn->exec($query);

        echo "Las inserciones se han realizado de manera satisfactoria.<br/>";
        
    } catch (PDOException $e) {
        // Aquí es donde atrapamos el error si algo falla
        echo "Error:<br/>" . $e->getMessage();
    }
?>