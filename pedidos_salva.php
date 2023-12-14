<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require "funciones/conecta.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $con = conecta();

    $productos = json_decode($_POST["productos"]);
    $cantidades = json_decode($_POST["cantidades"]);
    $subtotal_total = $_POST["subtotal_total"];
    $gran_total = $_POST["gran_total"];

    // Insertar el pedido en la tabla 'pedidos'
    $id_pedido = null;  // Puedes ajustar esto según tu lógica de generación de IDs
    $stmt_insert_pedido = $con->prepare("INSERT INTO pedidos (id_pedido, producto, cantidad, costo_unitario, subtotal, gran_total) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($productos as $producto_id) {
        $sql_producto = "SELECT nombre, costo FROM productos WHERE id = ?";
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

            // Guardar el pedido en la base de datos
            $stmt_insert_pedido->bind_param("isdddd", $id_pedido, $row_producto['nombre'], $cantidad, $costo_unitario, $subtotal, $gran_total);
            $stmt_insert_pedido->execute();
        }

        $stmt_producto->close();
    }

    // Limpiar variables de sesión después de usarlas
    unset($_SESSION["productos"]);
    unset($_SESSION["cantidades"]);
    unset($_SESSION["subtotal_total"]);
    unset($_SESSION["gran_total"]);

    echo "Pedido guardado con éxito.";

    $con->close();
} else {
    echo "Acceso no autorizado.";
}
?>
