<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva incidencia</title>
</head>
<body>
    <h1>Nueva incidencia</h1>

    <!-- Errores de validacion -->
    <?php foreach ($errores as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <form method="post" operacion="index.php?operacion=crear">
        <p>Título: <input type="text" name="titulo"></p>
        <p>Aula: <input type="text" name="aula"></p>
        <p>Prioridad:
            <select name="prioridad">
                <option>Baja</option>
                <option selected>Media</option>
                <option>Alta</option>
            </select>
        </p>
        <p>Descripcion:<br><textarea name="descripcion" rows="4" cols="40"></textarea></p>
        <p><button type="submit">Guardar</button></p>
    </form>

    <p><a href="index.php?operacion=listar">Volver</a></p>
</body>
</html>
