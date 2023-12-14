<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require "funciones/conecta.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $con = conecta();

    $productos = $_POST["productos"]; 
    $cantidades = $_POST["cantidades"];

    $subtotal_total = 0;
    $gran_total = 0;

    echo "<html>";
    echo "<head>";
    echo "<title>Resumen del Pedido</title>";
    echo "<link rel='stylesheet' type='text/css' href='css/pedido_resumen.css'>";
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
    
    echo "<h1>Resumen del Pedido</h1>";
    echo "<a href='pedidos_lista.php'>Lista de pedidos</a><br><br>";
    echo "<link rel='stylesheet' type='text/css' href='pedido_resumen.css'>";
    echo "<table>";
    echo "<thead><tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr></thead>";
    echo "<tbody>";

    // Generar un pedido_id único
    $pedido_id = time();  // Puedes ajustar esto según tu lógica de generación de IDs

    foreach ($productos as $producto_id) {
        $sql_producto = "SELECT * FROM productos WHERE id = ?";
        $stmt_producto = $con->prepare($sql_producto);

        if (!$stmt_producto) {
            die('Error en la preparación de la consulta: ' . $con->error);
        }

        $stmt_producto->bind_param("i", $producto_id);
        $stmt_producto->execute();
        $result_producto = $stmt_producto->get_result();

        if ($row_producto = $result_producto->fetch_assoc()) {
            $cantidad = $cantidades[$producto_id];
            $costo_unitario = $row_producto["costo"];
            $subtotal = $cantidad * $costo_unitario;
            $subtotal_total += $subtotal;

            // Guardar el pedido en la base de datos con el mismo pedido_id
            $stmt_insert_pedido = $con->prepare("INSERT INTO pedidos (pedido_id, producto, cantidad, costo_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert_pedido->bind_param("issdd", $pedido_id, $row_producto['nombre'], $cantidad, $costo_unitario, $subtotal);
            $stmt_insert_pedido->execute();

            echo "<tr>";
            echo "<td>" . $row_producto["nombre"] . "</td>";
            echo "<td>" . $cantidad . "</td>";
            echo "<td>$" . $costo_unitario . "</td>";
            echo "<td>$" . $subtotal . "</td>";
            echo "</tr>";
        }

        $stmt_producto->close();
    }

    $gran_total = $subtotal_total;

    echo "<tr><td></td><td></td><td><strong>Gran Total:</strong></td><td><strong>$" . $gran_total . "</strong></td></tr>";

    echo "</tbody>";
    echo "</table>";

    echo "</body>";
    echo "</html>";

    $stmt_insert_pedido->close();
    $con->close();
} else {
    echo "Acceso no autorizado.";
}
?>