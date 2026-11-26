<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tareas</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>📋 Sistema de Gestión de Tareas</h1>
            <nav>
                <a href="index.php?action=home">Inicio</a>
                <a href="index.php?action=misTareas">Mis Tareas</a>
                <span>Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                <a href="index.php?action=logout">Cerrar Sesión</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="header-seccion">
            <h2>Mis Tareas</h2>
            <a href="index.php?action=crearTarea" class="btn">➕ Nueva Tarea</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php 
                echo htmlspecialchars($_SESSION['error']); 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success">
                <?php 
                echo htmlspecialchars($_SESSION['mensaje']); 
                unset($_SESSION['mensaje']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (count($tareas) > 0): ?>
            <table class="tabla-tareas">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Fase</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tareas as $tarea): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tarea['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($tarea['descripcion']); ?></td>
                            <td>
                                <span class="badge fase-<?php echo strtolower($tarea['fase']); ?>">
                                    <?php echo htmlspecialchars($tarea['fase']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge estado-<?php echo strtolower($tarea['estado']); ?>">
                                    <?php echo htmlspecialchars($tarea['estado']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])); ?></td>
                            <td class="acciones">
                                <a href="index.php?action=editarTarea&id=<?php echo $tarea['id']; ?>" 
                                   class="btn-accion btn-editar">✏️ Editar</a>
                                <a href="index.php?action=eliminarTarea&id=<?php echo $tarea['id']; ?>" 
                                   class="btn-accion btn-eliminar"
                                   onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">🗑️ Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="mensaje-info">
                <p>No tienes tareas creadas todavía.</p>
                <a href="index.php?action=crearTarea" class="btn">Crear mi primera tarea</a>
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
