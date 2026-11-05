<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        th {
            width: 8rem;
            text-align: left;
            border-bottom: 1px solid black;
        }

        td {
            width: 8rem;
        }
    </style>
</head>

<body>
    <h1>Ejemplo 6: Vista de coche</h1>
    <table>
        <tr>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Color</th>
            <th>Propietario</th>
            <th>Precio</th>
            <th>id</th>
        </tr>
        <tr>
            <td><?php echo $row->marca ?></td>
            <td><?php echo $row->modelo ?></td>
            <td><?php echo $row->color ?></td>
            <td><?php echo $row->propietario ?></td>
            <td><?php echo $row->precio?></td>
            <td><?php echo $row->id?></td>
        </tr>
    </table>
</body>

</html>