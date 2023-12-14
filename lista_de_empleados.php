<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Redirigir a la página de login si no hay sesión
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Empleados</title>
    <link rel="stylesheet" type="text/css" href="css/B1_style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Función para eliminar un empleado
            function eliminarEmpleado(id) {
                if (confirm("¿Estás seguro de que deseas eliminar este empleado?")) {
                    $.ajax({
                        type: "POST",
                        url: "empleados_elimina.php",
                        data: { id: id },
                        success: function (response) {
                            if (response === "success") {
                                // Eliminación exitosa en el servidor, borra la fila
                                $("#row_" + id).remove();
                            } else {
                                alert("Error al eliminar el empleado.");
                            }
                        }
                    });
                }
            }

            // Agrega un click para el botón de eliminar en cada fila
            $(".btn-delete").click(function () {
                var id = $(this).data("id");
                eliminarEmpleado(id);
            });
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
    <h1>Listado de empleados</h1>
    <a href="empleados_alta.php" class="btn-create">Crear nuevo registro (nuevo empleado)</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre completo</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Imagen</th>
                <th>Acciones</th>
                <th>Ver detalles</th>
                <th>Editar empleado</th>
            </tr>
        </thead>
        <tbody>
        <?php
        require "funciones/conecta.php"; // Establece la conexión a la base de datos
        $con = conecta();

        // Consulta SQL para obtener los empleados activos y no eliminados
        $sql = "SELECT * FROM empleados WHERE status = 1 AND eliminado = 0";
        $res = $con->query($sql);

        while ($row = $res->fetch_assoc()) {
            $id = $row["id"];
            $nombre = $row["nombre"] . " " . $row["apellidos"];
            $correo = $row["correo"];
            $rol = $row["rol"];
            $imagen = $row["foto_nombre_encriptado"]; // Nombre encriptado del archivo de imagen
        
            // Condiciones para asignar el nombre del rol en función del valor
            if ($rol == 1) {
                $nombreRol = "Gerente";
            } elseif ($rol == 2) {
                $nombreRol = "Ejecutivo";
            } else {
                $nombreRol = "No seleccionado";
            }
        
            echo "<tr id='row_$id'>";
            echo "<td>$id</td>";
            echo "<td>$nombre</td>";
            echo "<td>$correo</td>";
            echo "<td>$nombreRol</td>";
            echo "<td><img src='archivos/$imagen' alt='' width='100'></td>";
            echo "<td><a href='javascript:void(0);' class='btn-delete' data-id='$id'>Eliminar</a></td>";
            echo "<td><a href='empleados_detalles.php?id=$id' class='btn-detalles'>Ver detalles</a></td>";
            echo "<td><a href='empleados_editar.php?id=$id' class='btn-editar'>Editar</a></td>";
            echo "</tr>";
        }
        $con->close(); // Cierra la conexión a la base de datos
        ?>
        </tbody>
    </table>
</body>
</html>



