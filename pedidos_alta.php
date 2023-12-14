<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require "funciones/conecta.php";
$con = conecta();

$sql = "SELECT * FROM productos WHERE status = 1 AND eliminado = 0";
$res = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <link rel="stylesheet" type="text/css" href="css/pedidos_alta.css">
    <style>
        #mensajeError {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
    <script>
        function validarFormulario() {
            var checkboxes = document.getElementsByName('productos[]');
            var alMenosUnoSeleccionado = false;

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    alMenosUnoSeleccionado = true;
                    var cantidadInput = document.getElementsByName('cantidades[' + checkboxes[i].value + ']')[0];
                    if (cantidadInput.value == 0) {
                        mostrarMensajeError('La cantidad de ' + checkboxes[i].parentNode.textContent.trim() + ' debe ser mayor a 0.');
                        return false;
                    }
                }
            }

            if (!alMenosUnoSeleccionado) {
                mostrarMensajeError('Debe seleccionar al menos un producto antes de guardar.');
                return false;
            }

            // Confirmar antes de enviar el formulario
            var confirmacion = confirm('¿Estás seguro de guardar el pedido?');
            return confirmacion;
        }

        function mostrarMensajeError(mensaje) {
            var mensajeError = document.getElementById('mensajeError');
            mensajeError.innerHTML = mensaje;
            setTimeout(function() {
                mensajeError.innerHTML = '';
            }, 3000);
        }
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

    <h1>Elije tus Productos</h1>
    <a href="pedidos_lista.php">Regresar al listado</a><br><br>

    <div id="mensajeError"></div>

    <?php
    $res = $con->query($sql);

    if (isset($_GET['result'])) {
        $result = $_GET['result'];
        if ($result === 'success') {
            echo '<p style="color: green;">Producto agregado con éxito.</p>';
        } else {
            echo '<p style="color: red;">Error al agregar el producto.</p>';
        }
    }
    ?>

    <form id="pedidoForm" method="post" action="pedido_resumen.php" onsubmit="return validarFormulario();">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio del Producto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row_producto = $res->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td><input type="checkbox" name="productos[]" value="' . $row_producto["id"] . '">' . $row_producto["nombre"] . '</td>';
                    echo '<td><input type="number" name="cantidades[' . $row_producto["id"] . ']" value="0" min="0" class="cantidad-input"></td>';
                    echo '<td>$' . $row_producto["costo"] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <input type="submit" value="Guardar">
    </form>
</body>
</html>
