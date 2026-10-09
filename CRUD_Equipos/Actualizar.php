<?php

// Credenciales de conexión
// Host: localhost [2, 4]
// Usuario: root [2, 4]
// Contraseña: "" (cadena vacía) [2, 4]
// Base de Datos: liga (usada en el historial de conversación)

$host ='localhost';
$user ='root';
$password ='';
$dbname ='equipos';
$tabla ='equipos';

// Datos para la actualización

$datos_actualizacion = [ 
    'id' => 7, 
    'puntos' => 0, 
    'goles_favor' => 0,
    'goles_contra' => 100
];
// Data Source Name (DSN) para MySQL
// Se usa PDO porque permite la conexión a diferentes tipos de bases de datos [5, 6]
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";

try {
    // 1. Establecer la conexión PDO
    $pdo = new PDO($dsn, $user, $password);

    // Configuración para lanzar excepciones en caso de errores 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //2. Definir la consulta de actualización con marcadores de posición
    // El método UPDATE se realiza sobre la tabla, especificando las columnas a cambiar y una cláusula WHERE (por ID)
        $sql_update = "UPDATE {$tabla}
                       SET puntos = :puntos, golesFavor = :gf, golesContra = :gc
                       WHERE id = :id";
    
    // 3. Preparar la sentencia (como parte de la necesidad de consultas preparadas [1])
    $stmt = $pdo->prepare($sql_update);

    // 4. Asignar los valores y ejecutar la sentencia
    $stmt->execute([
        ':id' => $datos_actualizacion['id'],
        ':puntos' => $datos_actualizacion['puntos'],
        ':gf' => $datos_actualizacion['goles_favor'],
        ':gc' => $datos_actualizacion['goles_contra']
    ]);

        // 5. Verificar y mostrar el resultado
    $filas_afectadas = $stmt->rowCount();

    if ($filas_afectadas > 0) {
        echo "¡Actualización exitosa! El equipo con ID {$datos_actualizacion['id']} ha sido actualizado con los nuevos datos:";
        echo "<ul>";
        echo "<li>Puntos: {$datos_actualizacion['puntos']}</li>";
        echo "<li>Goles a Favor: {$datos_actualizacion['goles_favor']}</li>";
        echo "<li>Goles en Contra: {$datos_actualizacion['goles_contra']}</li>";
        echo "</ul>";
    } else {
        echo "No se afectaron filas. El equipo con ID {$datos_actualizacion['id']} podría no existir o los datos ya estaban actualizados.";
    }

    } catch (PDOException $e) {
    // Manejo de errores, deteniendo la ejecución del programa si hay fallos en la conexión o consulta [7, 8]
    die("Error en la base de datos durante la actualización: " . $e->getMessage()); 
    // La conexión PDO se gestiona automáticamente al finalizar el script.
}