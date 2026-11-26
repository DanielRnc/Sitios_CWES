<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar libro</title>
</head>
<body>
    <h1>Editar libro</h1>

    <!-- Errores de validación -->
    <?php foreach ($errores as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <form method="post" action="index.php?action=editar&id=<?= $libro['id'] ?>">
        <p>Título: <input type="text" name="titulo" value="<?= $libro['titulo'] ?>"></p>
        <p>Autor: <input type="text" name="autor" value="<?= $libro['autor'] ?>"></p>
        <p>Género:
            <select name="genero">
                <?php foreach (['Ficción', 'No Ficción', 'Ciencia', 'Historia', 'Biografía', 'Otro'] as $g): ?>
                    <option <?= $libro['genero'] === $g ? 'selected' : '' ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>Año: <input type="number" name="anio_publicacion" value="<?= $libro['año_publicacion'] ?>"></p>
        <p><input type="checkbox" name="leido" <?= $libro['leido'] ? 'checked' : '' ?>> Leído</p>
        <p><button type="submit">Guardar cambios</button></p>
    </form>

    <p><a href="index.php?action=listar">Volver</a></p>
</body>
</html>