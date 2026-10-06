<?php

// Definición de las credenciales de conexión
$host = 'localhost'; // El Host es la dirección a la cual se hace la petición
$user = 'root';      // El usuario por defecto en entornos locales
$password = '';      // La contraseña por defecto (cadena vacía)
$dbname = 'equipos';    // Nombre de la base de datos
$tabla = 'equipos';

// Datos a insertar
$datos_equipo = [
    'nombre' => 'Cádiz',
    'puntos' => 30,
    'gf' => 40,
    'gc' => 50
];

// Data Source Name (DSN) para MySQL usando PDO
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";

try {
    // 1. Establecer la conexión PDO
    $pdo = new PDO($dsn, $user, $password);

    // Configuración para lanzar excepciones en caso de errores de SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Definir la consulta de inserción con marcadores de posición (prepared statement)
    // Las consultas preparadas son necesarias para tareas como la creación de nuevos registros.
    $sql_insert = "INSERT INTO {$tabla} (nombre, puntos, goles_favor, goles_contra)
                   VALUES (:nombre, :puntos, :gf, :gc)";

    // 3. Preparar la sentencia
    $stmt = $pdo->prepare($sql_insert);

    // 4. Asignar los valores a los marcadores de posición (binding) y ejecutar la sentencia
    $stmt->execute([
        ':nombre' => $datos_equipo['nombre'],
        ':puntos' => $datos_equipo['puntos'],
        ':gf' => $datos_equipo['gf'],
        ':gc' => $datos_equipo['gc']
    ]);

    // 5. Verificar y mostrar éxito
    if ($stmt->rowCount()) {
        echo "¡Inserción exitosa! El equipo '{$datos_equipo['nombre']}' ha sido añadido a la base de datos.";
    } else {
        echo "La inserción se ejecutó, pero no se afectaron filas.";
    }

} catch (PDOException $e) {
    // Manejo de errores de conexión o consulta
    // Si la conexión falla, se debe detener la ejecución y mostrar el error.
    die("Error en la base de datos: " . $e->getMessage());
}

// En PDO, la conexión se cierra automáticamente cuando el objeto $pdo es destruido.
?>
