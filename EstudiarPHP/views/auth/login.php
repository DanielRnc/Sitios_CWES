<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Iniciar sesión</h1>

    <!-- Errores de validación -->
    <?php foreach ($errores as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <form method="post" action="index.php?action=login">
        <p>Email: <input type"text" name="email" required></p>
        <p>Contraseña: <input type="password" name="password" required></p>
        <p><button type="submit">Entrar</button></p>
    </form>

    <p><a href="index.php?action=registro">Regístrate</a></p>
</body>