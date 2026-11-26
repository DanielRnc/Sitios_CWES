<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Iniciar sesion</h1>

    <!-- Errores de validacion -->
    <?php foreach ($errores as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <form method="post" operacion="index.php?operacion=login">
        <p>Email: <input type="text" name="email" required></p>
        <p>Contraseña: <input type="password" name="password" required></p>
        <p><button type="submit">Entrar</button></p>
    </form>

    <p><a href="index.php?operacion=registro">Registrarse</a></p>
</body>