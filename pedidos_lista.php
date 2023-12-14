<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require "funciones/conecta.php";
$con = conecta();

$sql = "SELECT DISTINCT pedido_id FROM pedidos"; // Usar DISTINCT para obtener pedidos únicos
$res = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pedidos</title>
    <link rel="stylesheet" type="text/css" href="css/B1_style.css">
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

    <h1>Listado de Pedidos</h1>
    <a href="pedidos_alta.php" class="btn-create">Crear nuevo pedido</a>
    <?php
    if (isset($_GET['result'])) {
        $result = $_GET['result'];
        if ($result === 'success') {
            echo '<p style="color: green;">Pedido agregado con éxito.</p>';
        } else {
            echo '<p style="color: red;">Error al agregar el pedido.</p>';
        }
    }
    ?>
    
    <table>
        <thead>
            <tr>
                <th>ID del Pedido</th>
                <th>Ver detalles del Pedido</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $res->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["pedido_id"] . "</td>";
                echo "<td><a href='pedidos_detalles.php?pedido_id=" . $row["pedido_id"] . "'>Ver detalles</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
