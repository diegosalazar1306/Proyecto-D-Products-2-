<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Redirigir a la página de login si no hay sesión
    exit();
}

require "funciones/conecta.php"; // Establece la conexión a la base de datos
$con = conecta();

// Obtiene el ID del empleado de la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta SQL para obtener los detalles del empleado
    $sql = "SELECT * FROM empleados WHERE id = $id";
    $res = $con->query($sql);

    if ($row = $res->fetch_assoc()) {
        $nombre = $row["nombre"] . " " . $row["apellidos"];
        $correo = $row["correo"];
        $rol = $row["rol"];
        $status = $row["status"];
        $imagen = "archivos/" . $row["foto_nombre_encriptado"];

        // Condiciones para asignar el nombre del rol en función del valor
        if ($rol == 1) {
            $nombreRol = "Gerente";
        } elseif ($rol == 2) {
            $nombreRol = "Ejecutivo";
        } else {
            $nombreRol = "No seleccionado";
        }

        // Valor de status a "Activo" o "Inactivo"
        $statusTexto = ($status == 1) ? "Activo" : "Inactivo";

        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Detalles del Empleado</title>
            <link rel="stylesheet" type="text/css" href="css/empleados_detalles.css">
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
            <a href="lista_de_empleados.php" class="btn-regresar">Regresar al listado</a>

            <h1>Detalles del Empleado</h1>
            <table>
                <tr><th>Nombre completo:</th><td><?php echo $nombre; ?></td></tr>
                <tr><th>Correo:</th><td><?php echo $correo; ?></td></tr>
                <tr><th>Rol:</th><td><?php echo $nombreRol; ?></td></tr>
                <tr><th>Status:</th><td><?php echo $statusTexto; ?></td></tr>
                <tr><th>Imagen del empleado:</th><td><img src='<?php echo $imagen; ?>' width='200'></td></tr>
            </table>
        </body>
        </html>

    <?php
    } else {
        echo "<p>Empleado no encontrado.</p>";
    }
} else {
    echo "<p>ID de empleado no especificado.</p>";
}

$con->close(); // Cierra la conexión a la base de datos
?>
