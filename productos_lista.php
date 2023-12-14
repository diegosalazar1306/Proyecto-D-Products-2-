<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <link rel="stylesheet" type="text/css" href="css/B1_style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".btn-delete").click(function () {
                var id = $(this).data("id");
                eliminarProducto(id);
            });

            function eliminarProducto(id) {
                if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                    $.ajax({
                        type: "POST",
                        url: "productos_elimina.php",
                        data: { id: id },
                        success: function (response) {
                            if (response === "success") {
                                $("#row_" + id).remove();
                            } else {
                                alert("Error al eliminar el producto.");
                            }
                        }
                    });
                }
            }
        });
    </script>
</head>

<body>
    <div class="navbar">
        <a href="bienvenido.php">Inicio</a>
        <a href="lista_de_empleados.php">Lista de empleados</a>
        <a href="productos_lista.php">Productos</a>
        <a href="promociones_lista.php">Promociones</a>
        <a href="pedidos_lista.php">Pedidos</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </div>
    <h1>Listado de Productos</h1>
    <?php
    if (isset($_GET['result'])) {
        $result = $_GET['result'];
        if ($result === 'success') {
            echo '<p style="color: green;">Producto agregado con éxito.</p>';
        } else {
            echo '<p style="color: red;">Error al agregar el producto.</p>';
        }
    }
    ?>
    <a href="productos_alta.php" class="btn-create">Crear nuevo producto</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Costo</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Acciones</th>
                <th>Ver detalles</th>
                <th>Editar producto</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require "funciones/conecta.php";
            $con = conecta();

            $sql = "SELECT * FROM productos WHERE status = 1 AND eliminado = 0";
            $res = $con->query($sql);

            while ($row = $res->fetch_assoc()) {
                $id = $row["id"];
                $nombre = $row["nombre"];
                $codigo = $row["codigo"];
                $descripcion = $row["descripcion"];
                $costo = number_format($row["costo"], 2);
                $stock = $row["stock"];
                $imagen = $row["archivo_n"];

                echo "<tr id='row_$id'>";
                echo "<td>$id</td>";
                echo "<td>$nombre</td>";
                echo "<td>$codigo</td>";
                echo "<td>$descripcion</td>";
                echo "<td>$$costo</td>";
                echo "<td>$stock</td>";
                echo "<td><img src='archivos_productos/$imagen' alt='' width='100'></td>";
                echo "<td><a href='javascript:void(0);' class='btn-delete' data-id='$id'>Eliminar</a></td>";
                echo "<td><a href='productos_detalles.php?id=$id' class='btn-detalles'>Ver detalles</a></td>";
                echo "<td><a href='productos_edicion.php?id=$id' class='btn-editar'>Editar</a></td>";
                echo "</tr>";
            }
            $con->close();
            ?>
        </tbody>
    </table>
</body>
</html>
