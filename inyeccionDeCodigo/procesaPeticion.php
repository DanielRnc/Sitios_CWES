<?php

$id = $_GET["id"];
$host='localhost';
$db='claves';
$user='root';
$password="";
$charset='utf8mb4';
$connex=null;
// Conexion PDO
try {
    $connection = "mysql:host=" . $host . ";dbname=" . $db . ";charset=" . $charset;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    //$pdo = new PDO($connection, $this->user, $this->password, $options);
    $connex = new PDO($connection, $user, $password, $options);
} catch (PDOException $e) {
    print_r('Error connection: ' . $e->getMessage());
}

$sql = "SELECT nombre FROM usuarios WHERE id=$id";
$result = $connex->query($sql);
//$result no es un objeto, es un array de objetos PDO que encapsula a un registro de la tabla

while ($r = $result->fetch(PDO::FETCH_OBJ)) {
    echo "Usuario: $r->nombre";
}