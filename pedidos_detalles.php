<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require "funciones/conecta.php";
$con = conecta();

if (isset($_GET['pedido_id'])) {
    $pedido_id = $_GET['pedido_id'];

    // Consulta SQL para obtener los detalles del pedido
    $sql_detalles = "SELECT * FROM pedidos WHERE pedido_id = ?";
    $stmt_detalles = $con->prepare($sql_detalles);

    if (!$stmt_detalles) {
        die('Error en la preparación de la consulta: ' . $con->error);
    }

    $stmt_detalles->bind_param("i", $pedido_id);
    $stmt_detalles->execute();
    $result_detalles = $stmt_detalles->get_result();

    // Calcular el gran total del pedido
    $gran_total_pedido = 0;

    // Mostrar detalles en una tabla
    echo "<html>";
    echo "<head>";
    echo "<title>Detalles del Pedido</title>";
    echo "<link rel='stylesheet' type='text/css' href='css/productos_detalles.css'>";
    echo "</head>";
    echo "<body>";

    echo "<div class='navbar'>";
    echo "<a href='bienvenido.php'>Inicio</a>";
    echo "<a href='lista_de_empleados.php'>Lista de empleados</a>";
    echo "<a href='productos_lista.php'>Productos</a>";
    echo "<a href='promociones_lista.php'>Promociones</a>";
    echo "<a href='pedidos_lista.php'>Pedidos</a>";
    echo "<a href='cerrar_sesion.php'>Cerrar sesión</a>";
    echo "</div>";

    echo "<h1>Detalles del Pedido</h1>";
    echo "<a href='pedidos_lista.php'>Regresar a la lista de pedidos</a><br><br>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Producto</th>";
    echo "<th>Cantidad</th>";
    echo "<th>Costo Unitario</th>";
    echo "<th>Subtotal</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($row_detalle = $result_detalles->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row_detalle["producto"] . "</td>";
        echo "<td>" . $row_detalle["cantidad"] . "</td>";
        echo "<td>$" . $row_detalle["costo_unitario"] . "</td>";
        echo "<td>$" . $row_detalle["subtotal"] . "</td>";
        echo "</tr>";
        $gran_total_pedido += $row_detalle["subtotal"];
    }

    // Agregar una fila para el gran total
    echo "<tr>";
    echo "<td colspan='3'><strong>Gran Total del Pedido:</strong></td>";
    echo "<td>$" . number_format($gran_total_pedido, 2) . "</td>";
    echo "</tr>";

    echo "</tbody>";
    echo "</table>";

    echo "</body>";
    echo "</html>";

    $stmt_detalles->close();
} else {
    echo "Acceso no autorizado.";
}
?>
