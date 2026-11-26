<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis incidencias</title>
</head>
<body>
    <p>Hola, <?= $_SESSION['usuario_nombre'] ?> | <a href="index.php?operacion=logout">Cerrar sesión</a></p>

    <h1>Mis incidencias</h1>

    <p>Tienes <?= $abiertas ?> incidencias abiertas</p>

    <p><a href="index.php?operacion=crear">Nueva incidencia</a></p>

    <!-- esto es un filtro para la prioridad esto se lo mando al listar-->
    <form method="get" operacion="index.php">
        <input type="hidden" name="operacion" value="listar">
        Prioridad:
        <select name="prioridad">
            <option value="">Todas</option>
            <?php foreach (Incidencia::PRIORIDADES as $p): ?>
                <option <?= $prioridad === $p ? 'selected' : '' ?>><?= $p ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <?php if (empty($incidencias)): ?>
        <p>No hay incidencias</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Título</th>
                <th>Aula</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($incidencias as $inc): ?>
                <tr>
                    <td><?= $inc['titulo'] ?></td>
                    <td><?= $inc['aula'] ?></td>
                    <td><?= $inc['prioridad'] ?></td>
                    <td><?= $inc['estado'] ?></td>
                    <td><?= $inc['fecha_creacion'] ?></td>
                    <td>
                        <a href="index.php?operacion=editar&id=<?= $inc['id'] ?>">Editar</a>


                        <form method="post" action="index.php?operacion=eliminar">
                            <input type="hidden" name="id" value="<?= $inc['id'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
