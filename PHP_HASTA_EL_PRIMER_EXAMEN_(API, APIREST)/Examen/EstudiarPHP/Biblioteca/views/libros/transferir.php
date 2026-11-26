<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transferir libro</title>
</head>
<body>
    <h1>Transferir: <?= $libro['titulo'] ?></h1>

    <?php if (empty($usuarios)): ?>
        <p>No hay otros usuarios a los que transferir.</p>
    <?php else: ?>
        <!-- Apartado D: se elige el usuario destino -->
        <form method="post" action="index.php?action=transferir&id=<?= $libro['id'] ?>">
            <p>Transferir a:
                <select name="destino_id">
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= $u['nombre'] ?> - <?= $u['email'] ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p><button type="submit">Transferir</button></p>
        </form>
    <?php endif; ?>

    <p><a href="index.php?action=listar">Volver</a></p>
</body>
</html>
