<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fEditar Coche</title>
</head>
<body>
    <h1>Editar coche</h1>

    <form method="post" action="">
        <input type="hidden" name="action_method" value="actualizar">

        <label>Marca:</label><br>
        <input type="text" name="marca" value="<?= htmlspecialchars($coche->getMarca()) ?>" required><br><br>

        <label>Modelo:</label><br>
        <input type="text" name="modelo" value="<?= htmlspecialchars($coche->getModelo()) ?>" required><br><br>

        <label>Color:</label><br>
        <input type="text" name="color" value="<?= htmlspecialchars($coche->getColor()) ?>" required><br><br>

        <label>Propietario:</label><br>
        <input type="text" name="propietario" value="<?= htmlspecialchars($coche->getPropietario()) ?>" required><br><br>

        <label>Precio:</label><br>
        <input type="number" name="precio" value="<?= htmlspecialchars($coche->getPrecio()) ?>" required><br><br>

        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
`