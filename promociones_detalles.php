<?php
session_start();


if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); 
    exit();
}

require "funciones/conecta.php"; 
$con = conecta();


if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "SELECT * FROM promociones WHERE id = $id";
    $res = $con->query($sql);

    if ($row = $res->fetch_assoc()) {
            $nombre = $row["nombre"];
            $imagen = "archivos_promociones/" . $row["archivo"]; 
            $status = $row["status"];

      
        $statusTexto = ($status == 1) ? "Activo" : "Inactivo";
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Detalles del Promociones</title>
            <link rel="stylesheet" type="text/css" href="css/productos_detalles.css">
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
            <a href="promociones_lista.php" class="btn-regresar">Regresar al listado</a>

            <h1>Detalles del Producto</h1>
            <table>
                <tr><th>Nombre del producto:</th><td><?php echo $nombre; ?></td></tr>
                <tr><th>Status:</th><td><?php echo $statusTexto; ?></td></tr>
                <tr><th>Imagen del producto:</th><td><img src='<?php echo $imagen; ?>' width='200'></td></tr>
            </table>
        </body>
        </html>

    <?php
    } else {
        echo "<p>Producto no encontrado.</p>";
    }
} else {
    echo "<p>ID del producto no especificado.</p>";
}

$con->close(); 
?>
