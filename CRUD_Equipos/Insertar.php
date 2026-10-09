<?php

// 1. Configuración y credenciales de la base de datos
$host     = 'localhost';
$user     = 'root';
$password = '';
$dbname   = 'equipos';
$tabla    = 'equipos';

// 2. Datos del equipo a insertar
$datos_equipo = [
    'nombre' => 'Cádiz',
    'puntos' => 30,
    'gf'     => 40,
    'gc'     => 50
];

// 3. Configuración del DSN (Data Source Name)
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

try {
    // 4. Establecer la conexión PDO
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 5. Definir la consulta con marcadores de posición
    $sql_insert = "INSERT INTO {$tabla} (nombre, puntos, goles_favor, goles_contra)
                   VALUES (:nombre, :puntos, :gf, :gc)";

    // 6. Preparar y ejecutar la sentencia
    $stmt = $pdo->prepare($sql_insert);
    $stmt->execute([
        ':nombre' => $datos_equipo['nombre'],
        ':puntos' => $datos_equipo['puntos'],
        ':gf'     => $datos_equipo['gf'],
        ':gc'     => $datos_equipo['gc']
    ]);

    // 7. Verificar el resultado
    if ($stmt->rowCount() > 0) {
        echo "¡Inserción exitosa! El equipo '{$datos_equipo['nombre']}' ha sido añadido a la base de datos.";
    } else {
        echo "La consulta se ejecutó pero no se modificó ninguna fila.";
    }

} catch (PDOException $e) {
    // Manejo de errores de conexión o ejecución
    die("Error en la base de datos: " . $e->getMessage());
}