<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas - Inicio</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>📋 Sistema de Gestión de Tareas</h1>
            <nav>
                <a href="index.php?action=home">Inicio</a>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="index.php?action=misTareas">Mis Tareas</a>
                    <span>Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                    <a href="index.php?action=logout">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="index.php?action=login">Iniciar Sesión</a>
                    <a href="index.php?action=register">Registrarse</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2>Todas las Tareas del Sistema</h2>
        <p class="descripcion">Vista pública de todas las tareas registradas en el sistema de desarrollo de software.</p>

        <?php if (count($tareas) > 0): ?>
            <div class="tareas-grid">
                <?php foreach($tareas as $tarea): ?>
                    <div class="tarea-card">
                        <h3><?php echo htmlspecialchars($tarea['titulo']); ?></h3>
                        <p class="descripcion"><?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                        
                        <div class="tarea-info">
                            <span class="badge fase-<?php echo strtolower($tarea['fase']); ?>">
                                <?php echo htmlspecialchars($tarea['fase']); ?>
                            </span>
                            <span class="badge estado-<?php echo strtolower($tarea['estado']); ?>">
                                <?php echo htmlspecialchars($tarea['estado']); ?>
                            </span>
                        </div>
                        
                        <div class="tarea-footer">
                            <small>Asignado a: <?php echo htmlspecialchars($tarea['nombre_usuario']); ?></small>
                            <small>Creada: <?php echo date('d/m/Y', strtotime($tarea['fecha_creacion'])); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="mensaje-info">
                <p>No hay tareas registradas en el sistema todavía.</p>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="index.php?action=crearTarea" class="btn">Crear Primera Tarea</a>
                <?php else: ?>
                    <a href="index.php?action=register" class="btn">Regístrate para crear tareas</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Sistema de Gestión de Tareas - Ejemplo Didáctico MVC</p>
        </div>
    </footer>
</body>
</html>
