<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo libro</title>
</head>
<body>
    <h1>Nuevo libro</h1>

    <!-- Errores de validación -->
    <?php foreach ($errores as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <form method="post" action="index.php?action=crear">
        <p>Título: <input type="text" name="titulo"></p>
        <p>Autor: <input type="text" name="autor"></p>
        <p>Género:
            <select name="genero">
                <option>Ficción</option>
                <option>No Ficción</option>
                <option>Ciencia</option>
                <option>Historia</option>
                <option>Biografía</option>
                <option>Otro</option>
            </select>
        </p>
        <p>Año: <input type="number" name="anio_publicacion"></p>
        <p><input type="checkbox" name="leido"> Leído</p>
        <p><button type="submit">Guardar</button></p>
    </form>

    <p><a href="index.php?action=listar">Volver</a></p>
</body>
</html>