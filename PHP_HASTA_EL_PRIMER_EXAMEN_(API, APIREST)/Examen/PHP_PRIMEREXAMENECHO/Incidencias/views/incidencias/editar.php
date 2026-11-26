<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar incidencia</title>
</head>
<body>
    <h1>Editar incidencia</h1>

    <!-- Los errores de validacion -->
    <?php foreach ($errores as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>


    <form method="post" operacion="index.php?operacion=editar&id=<?= $incidencia['id'] ?>">
        <p>Título: <input type="text" name="titulo" value="<?= $incidencia['titulo'] ?>"></p>
        <p>Aula: <input type="text" name="aula" value="<?= $incidencia['aula'] ?>"></p>
        <p>Prioridad:
            <select name="prioridad">
                <?php foreach (Incidencia::PRIORIDADES as $p): ?>
                    <option <?= $incidencia['prioridad'] === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>Estado:
            <select name="estado">
                <?php foreach (Incidencia::ESTADOS as $e): ?>
                    <option <?= $incidencia['estado'] === $e ? 'selected' : '' ?>><?= $e ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>Descripción:<br><textarea name="descripcion" rows="4" cols="40"><?= $incidencia['descripcion'] ?></textarea></p>
        <p><button type="submit">Guardar cambios</button></p>
    </form>

    <p><a href="index.php?operacion=listar">Volver</a></p>
</body>
</html>
