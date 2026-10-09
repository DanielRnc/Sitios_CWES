<?php

// Credenciales de conexión
// Host: localhost [2]
// Usuario: root [2]
// Contraseña: "" (cadena vacía) [2]
// Base de Datos: liga (Adaptado de db name) [3]
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'equipos';
$tabla = 'equipos';

// Clave primaria para eliminar el registro (se debe conocer)
// Se asume el ID=10 para el ejemplo.
$equipo_id_a_eliminar = 8; 

// Data Source Name (DSN) para MySQL
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";

try {
    // 1. Establecer la conexión PDO
    $pdo = new PDO($dsn, $user, $password);
    
    // Configuración para lanzar excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Definir la consulta de eliminación con marcador de posición
    // Se elimina el registro usando la clave primaria 'id'.
    $sql_delete = "DELETE FROM {$tabla} WHERE id = :id";

    // 3. Preparar la sentencia (tal como se recomienda para operaciones CRUD) [1]
    $stmt = $pdo->prepare($sql_delete);

    // 4. Asignar el valor al marcador de posición y ejecutar la sentencia
    $stmt->execute([':id' => $equipo_id_a_eliminar]);

    // 5. Verificar y mostrar el resultado
    if ($stmt->rowCount() > 0) {
        // rowCount() devuelve el número de filas afectadas por la última sentencia DELETE/INSERT/UPDATE
        echo "¡Eliminación exitosa! Se eliminó el registro con ID: {$equipo_id_a_eliminar}.";
    } else {
        echo "No se encontró ningún equipo con ID: {$equipo_id_a_eliminar} para eliminar, o la eliminación falló.";
    }

} catch (PDOException $e) {
    // Manejo de errores de conexión o consulta
    // Si la conexión falla, se debe detener la ejecución y mostrar el error [4].
    die("Error en la base de datos: " . $e->getMessage());
}

// La conexión PDO se cierra automáticamente.
?>