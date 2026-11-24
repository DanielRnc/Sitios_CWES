<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis libros</title>
</head>
<body>
    <p>Hola, <?= $_SESSION['usuario_nombre'] ?> | <a href="index.php?action=logout">Cerrar sesión</a></p>

    <h1>Mis libros</h1>

    <p><a href="index.php?action=crear">Añadir libro</a></p>
    
    <?php if (empty($libros)): ?>
        <p>No tienes libros todavía.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Género</th>
                <th>Año</th>
                <th>Leído</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($libros as $libro): ?>
                <tr>
                    <td><?= $libro['titulo'] ?></td>
                    <td><?= $libro['autor'] ?></td>
                    <td><?= $libro['genero'] ?></td>
                    <td><?= $libro['año_publicacion'] ?></td>
                    <td><?= $libro['leido'] ? 'Sí' : 'No' ?></td>
                    <td><a href="index.php?action=editar&id=<?= $libro['id'] ?>">Editar</a></td>
                    <td>
                        <a href="index.php?action=editar&id=<?= $libro['id'] ?>">Editar</a>
                        <form method="post" action="index.php?action=eliminar">
                            <input type="hidden" name="id" value="<?= $libro['id'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>