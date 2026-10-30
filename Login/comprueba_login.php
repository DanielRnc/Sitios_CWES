<?php

$usuario = $_POST['login'];
$password = $_POST['password'];

try {
    $conexion = new PDO('mysql:host=localhost;dbname=ejemplo-mvc-php', 'root', '');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM usuarios WHERE usuarios = :usuario";
    $resultado = $conexion->prepare($sql);
    
    $resultado->execute(array(":usuario" => $usuario));
    
    $user = $resultado->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION["usuario"] = $usuario;
        header("Location: usuarios_registrados1.php");
        exit();
    } else {
        header("Location: login.php");
        exit();
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>