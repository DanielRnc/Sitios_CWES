<?php
//Conexión con control de errores
$opciones = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
try {
    $db = new PDO('mysql:host=localhost;dbname=prueba', 'root', '', $opciones);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
}

//Insert
$registros = $db->exec('INSERT INTO personas (nombre) VALUES ("José"),("Luís")');
if ($registros){
    echo "Se han activado $registros registros.";
}

//Update
$registros = $db->exec('UPDATE personas SET activo=1 WHERE activo=0');
if ($registros){
    echo "Se han activado $registros registros.";
}


//Select con OBJ
$resultado = $db->query('SELECT * FROM personas');
while ($personas = $resultado->fetch(PDO::FETCH_OBJ)){ //Recorro el resultado
    echo $personas->id." ".$personas->nombre." ".$personas->activo."<br>";
}   