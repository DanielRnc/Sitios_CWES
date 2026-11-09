<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>📋 Sistema de Gestión de Tareas</h1>
            <nav>
                <a href="index.php?action=home">Inicio</a>
                <a href="index.php?action=register">Registrarse</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="form-container">
            <h2>Iniciar Sesión</h2>
            
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

            <form action="index.php?action=procesarLogin" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn">Iniciar Sesión</button>
            </form>

            <p class="text-center">
                ¿No tienes cuenta? <a href="index.php?action=register">Regístrate aquí</a>
            </p>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Sistema de Gestión de Tareas - Ejemplo Didáctico MVC</p>
        </div>
    </footer>
</body>
</html>
