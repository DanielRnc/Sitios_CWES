<?php

    $usuario= $_POST["usu"];
    $contrasenia= $_POST["contra"];

    //Cifrado
    $pass_cifrado=password_hash($contrasenia, PASSWORD_DEFAULT);

    try{

        $base=new PDO('mysql:host=localhost; dbname=ejemplo-mvc-php', 'root', '');
        $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $base->exec("SET CHARACTER SET utf8");

        $sql="INSERT INTO usuarios (usuarios, password) VALUES (:usu, :contra)";
        $resultado=$base->prepare($sql);

        $resultado->execute(array(":usu"=>$usuario, ":contra"=>$pass_cifrado));
        echo "Registro insertado";

        $resultado->closeCursor();
    
    }catch(Exception $e){
        echo "Linea del error: " . $e->getLine() . "<br>";
        echo "Motivo: " . $e->getMessage();
    }