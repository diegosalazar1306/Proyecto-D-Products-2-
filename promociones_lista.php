<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require "funciones/conecta.php";
$con = conecta();

$sql = "SELECT * FROM promociones WHERE eliminado = 0";
$res = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Promociones</title>
    <link rel="stylesheet" type="text/css" href="css/B1_style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".btn-delete").click(function () {
                var id = $(this).data("id");
                eliminarPromocion(id);
            });

            function eliminarPromocion(id) {
                if (confirm("¿Estás seguro de que deseas eliminar esta promoción?")) {
                    $.ajax({
                        type: "POST",
                        url: "promociones_elimina.php",
                        data: { id: id },
                        success: function (response) {
                            if (response === "success") {
                                $("#row_" + id).remove();
                            } else {
                                alert("Error al eliminar la promoción.");
                            }
                        }
                    });
                }
            }
        });

        setTimeout(function(){
        document.getElementById('mensajeExito').style.display='none';
        document.getElementById('mensajeError').style.display='none';
    }, 3000);

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

    <h1>Listado de Promociones</h1>
    <?php
        if (isset($_GET['result'])) {
            $result = $_GET['result'];
            if ($result === 'success') {
                echo '<p id="mensajeExito" style="color: green;">Promoción agregada con éxito.</p>';
            } else {
                echo '<p id="mensajeError" style="color: red;">Error al agregar la promoción.</p>';
            }
        }
    ?>

    <a href="promociones_alta.php" class="btn-create">Crear nueva promoción</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
                <th>Ver detalles</th>
                <th>Editar promoción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $res->fetch_assoc()) {
                $id = $row["id"];
                $nombre = $row["nombre"];
                $imagen = $row["archivo"];

                echo "<tr id='row_$id'>";
                echo "<td>$id</td>";
                echo "<td>$nombre</td>";
                echo "<td><img src='archivos_promociones/$imagen' alt='' width='100'></td>";
                echo "<td><a href='javascript:void(0);' class='btn-delete' data-id='$id'>Eliminar</a></td>";
                echo "<td><a href='promociones_detalles.php?id=$id' class='btn-detalles'>Ver detalles</a></td>";
                echo "<td><a href='promociones_edicion.php?id=$id' class='btn-editar'>Editar</a></td>";
                echo "</tr>";
            }
            $con->close();
            ?>
        </tbody>
    </table>
</body>
</html>
