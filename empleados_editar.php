<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Redirigir a la página de login si no hay sesión
    exit();
}

require "funciones/conecta.php"; // Establece la conexión a la base de datos

if (isset($_GET['id'])) {
    $con = conecta();
    $id = $_GET['id'];

    // Consulta SQL para obtener los detalles del empleado por ID
    $sql = "SELECT * FROM empleados WHERE id = $id";
    $res = $con->query($sql);

    if ($row = $res->fetch_assoc()) {
        $nombre = $row["nombre"];
        $apellidos = $row["apellidos"];
        $correo = $row["correo"];
        $rol = $row["rol"];
        $imagen = "archivos/" . $row["foto_nombre_encriptado"];
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Edición de Empleados</title>
            <link rel="stylesheet" type="text/css" href="css/empleados_editar.css">
        </head>
        <body>

        <script>
            function displayFileName(input) {
                var fileName = input.value.split("\\").pop();
                document.getElementById("file-name-display").innerHTML = fileName;
            }
        </script>

            <div class="navbar">
                <a href="bienvenido.php">Inicio</a>
                <a href="lista_de_empleados.php">Lista de empleados</a>
                <a href="productos_lista.php">Productos</a>
                <a href="promociones_lista.php">Promociones</a>
                <a href="pedidos_lista.php">Pedidos</a>
                <a href="cerrar_sesion.php">Cerrar sesión</a>
            </div>
            <h1>Edición de Empleados</h1>
            <a href="lista_de_empleados.php" class="btn-regresar">Regresar al listado</a>

            <form action="procesar_edicion_empleado.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>">
                </div>
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" value="<?php echo $correo; ?>">
                </div>
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol">
                        <option value="1" <?php echo ($rol == 1 ? 'selected' : ''); ?>>Gerente</option>
                        <option value="2" <?php echo ($rol == 2 ? 'selected' : ''); ?>>Ejecutivo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="foto" class="custom-file-upload">
                        Nueva foto
                        <input type="file" id="foto" name="foto" onchange="displayFileName(this)">
                    </label>
                    <div id="file-name-display" class="file-name-display"></div>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-save">Guardar</button>
                </div>
            </form>
        </body>
        </html>

    <?php
    } else {
        echo "<p>Empleado no encontrado.</p>";
    }

    $con->close(); // Cierra la conexión a la base de datos
} else {
    echo "<p>ID de empleado no especificado.</p>";
}
?>
