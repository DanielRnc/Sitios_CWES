<?php

// Credenciales de conexión
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'equipos';
$tabla = 'equipos';

// Clave primaria para eliminar el registro
$equipo_id_a_eliminar = 8; 

// Data Source Name (DSN) para MySQL
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";

try {
    // 1. Establecer la conexión PDO
    $pdo = new PDO($dsn, $user, $password);
    
    // Configuración para lanzar excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Definir la consulta de eliminación con marcador de posición
    $sql_delete = "DELETE FROM {$tabla} WHERE id = :id";

    // 3. Preparar la sentencia
    $stmt = $pdo->prepare($sql_delete);

    // 4. Asignar el valor al marcador de posición y ejecutar la sentencia
    $stmt->execute([':id' => $equipo_id_a_eliminar]);

    // 5. Verificar y mostrar el resultado
    if ($stmt->rowCount() > 0) {
        echo "¡Eliminación exitosa! Se eliminó el registro con ID: {$equipo_id_a_eliminar}.";
    } else {
        echo "No se encontró ningún equipo con ID: {$equipo_id_a_eliminar} para eliminar, o la eliminación falló.";
    }

} catch (PDOException $e) {
    // Manejo de errores de conexión o consulta
    die("Error en la base de datos: " . $e->getMessage());
}

// La conexión PDO se cierra automáticamente.
?>