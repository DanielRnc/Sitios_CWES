<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h1>Registro</h1>

    <!-- Errores de validacion -->
    <?php foreach ($errores as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <form method="post" operacion="index.php?operacion=registro">
        <p>Email: <input type="text" name="email" required></p>
        <p>Nombre: <input type="text" name="nombre" required></p>
        <p>Contraseña: <input type="password" name="password" required></p>
        <p>Confirmar contraseña: <input type="password" name="confirmar" required></p>
        <p><button type="submit">Registrarse</button></p>
    </form>
</body>
</html>