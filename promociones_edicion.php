<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require "funciones/conecta.php";

if (isset($_GET['id'])) {
    $con = conecta();
    $id = $_GET['id'];

    $sql = "SELECT * FROM promociones WHERE id = $id";
    $res = $con->query($sql);

    if ($row = $res->fetch_assoc()) {
        $nombre = $row["nombre"];
        $imagen = "archivos_promociones/" . $row["archivo"];
        ?>

        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Edición de Promocion</title>
            <link rel="stylesheet" type="text/css" href="css/productos_edicion.css">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
            function validacioncampos() {
                var nombre = document.getElementById("nombre").value;

                var errorNombre = document.getElementById("errorNombre");
                var errorCampos = document.getElementById("errorCampos");

                var nombreOriginal = "<?php echo $nombre; ?>";
                var nombreCambiado = (nombre !== nombreOriginal);

                function resetErrors() {
                    errorNombre.innerHTML = "";
                    errorCampos.innerHTML = "";
                }

                if (nombre.trim() !== "") {
                    if (nombreCambiado) {
                        $.ajax({
                            type: "POST",
                            url: "validar_promocion.php",
                            data: { nombre: nombre },
                            success: function (response) {
                                if (response === "existe") {

                                    $('#codigo').val('');
                                    errorCodigo.innerHTML = "El nombre " + nombre + " ya existe.";
                                    setTimeout(function () {
                                        errorCodigo.innerHTML = "";
                                    }, 3000);
                                } else {

                                    document.forma01.method = 'post';
                                    document.forma01.action = 'procesar_edicion_promocion.php';
                                    document.forma01.submit();
                                }
                            }
                        });
                    } else {

                        document.forma01.method = 'post';
                        document.forma01.action = 'procesar_edicion_promocion.php';
                        document.forma01.submit();
                    }
                } else {

                    resetErrors();
                    errorNombre.innerHTML = nombre.trim() === "" ? "Campo obligatorio" : "";
                    errorCampos.innerHTML = "Todos los campos son obligatorios.";
                    setTimeout(function () {
                        resetErrors();
                    }, 3000);
                }
            }

            function displayFileName(input) {
                var fileName = input.value.split("\\").pop();
                document.getElementById("file-name-display").innerHTML = fileName;
            }
    </script>
        </head>
        <body>
            <div class="navbar">
                <a href="bienvenido.php">Inicio</a>
                <a href="lista_de_empleados.php">Lista de empleados</a>
                <a href="productos_lista.php">Productos</a>
                <a href="pedidos_lista.php">Pedidos</a>
                <a href="cerrar_sesion.php">Cerrar sesión</a>
            </div>
            <h1>Edición de Promoción</h1>
            <a href="promociones_lista.php" class="btn-regresar">Regresar al listado</a>

            <<form name="forma01" action="procesar_edicion_promocion.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group">
            <label for="nombre"></label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
            <span id="errorNombre" class="error-message"></span>
        </div>
        <div class="form-group">
            <label for="foto" class="custom-file-upload">
                Nueva foto
                <input type="file" id="foto" name="foto" onchange="displayFileName(this)">
            </label>
            <div id="file-name-display" class="file-name-display"></div>
        </div>
        <div class="form-group">
            <button type="button" class="btn-save" onclick="validacioncampos()">Guardar</button>
        </div>
        <div id="errorCampos" style="color: red;"></div>
    </form>
        </body>
        </html>
        <?php
        exit();
    } else {
        echo "<p>Producto no encontrado.</p>";
    }

    $con->close();
} else {
    echo "<p>ID de producto no especificado.</p>";
}
?>
