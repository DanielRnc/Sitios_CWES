<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
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
        <div class="form-container">
            <h2>Editar Tarea</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=actualizarTarea" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($tarea->id); ?>">
                
                <div class="form-group">
                    <label for="titulo">Título de la tarea: *</label>
                    <input type="text" id="titulo" name="titulo" required 
                           value="<?php echo htmlspecialchars($tarea->titulo); ?>">
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($tarea->descripcion); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="fase">Fase del proyecto: *</label>
                    <select id="fase" name="fase" required>
                        <option value="">-- Selecciona una fase --</option>
                        <option value="Análisis" <?php echo $tarea->fase == 'Análisis' ? 'selected' : ''; ?>>Análisis</option>
                        <option value="Diseño" <?php echo $tarea->fase == 'Diseño' ? 'selected' : ''; ?>>Diseño</option>
                        <option value="Codificación" <?php echo $tarea->fase == 'Codificación' ? 'selected' : ''; ?>>Codificación</option>
                        <option value="Pruebas" <?php echo $tarea->fase == 'Pruebas' ? 'selected' : ''; ?>>Pruebas</option>
                        <option value="Despliegue" <?php echo $tarea->fase == 'Despliegue' ? 'selected' : ''; ?>>Despliegue</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="estado">Estado: *</label>
                    <select id="estado" name="estado" required>
                        <option value="">-- Selecciona un estado --</option>
                        <option value="Pendiente" <?php echo $tarea->estado == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="En Progreso" <?php echo $tarea->estado == 'En Progreso' ? 'selected' : ''; ?>>En Progreso</option>
                        <option value="Completada" <?php echo $tarea->estado == 'Completada' ? 'selected' : ''; ?>>Completada</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Actualizar Tarea</button>
                    <a href="index.php?action=misTareas" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Sistema de Gestión de Tareas - Ejemplo Didáctico MVC</p>
        </div>
    </footer>
</body>
</html>
