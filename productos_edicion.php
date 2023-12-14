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

    $sql = "SELECT * FROM productos WHERE id = $id";
    $res = $con->query($sql);

    if ($row = $res->fetch_assoc()) {
        $nombre = $row["nombre"];
        $codigo = $row["codigo"];
        $descripcion = $row["descripcion"];
        $costo = $row["costo"];
        $stock = $row["stock"];
        $imagen = "archivos_productos/" . $row["archivo_n"];
        ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Edición de Producto</title>
            <link rel="stylesheet" type="text/css" href="css/productos_edicion.css">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
            function validacioncampos() {
                var nombre = document.getElementById("nombre").value;
                var codigo = document.getElementById("codigo").value;
                var descripcion = document.getElementById("descripcion").value;
                var costo = document.getElementById("costo").value;
                var stock = document.getElementById("stock").value;

                var errorNombre = document.getElementById("errorNombre");
                var errorCodigo = document.getElementById("errorCodigo");
                var errorDescripcion = document.getElementById("errorDescripcion");
                var errorCosto = document.getElementById("errorCosto");
                var errorStock = document.getElementById("errorStock");
                var errorCampos = document.getElementById("errorCampos");

                var codigoOriginal = "<?php echo $codigo; ?>";
                var codigoCambiado = (codigo !== codigoOriginal);

                function resetErrors() {
                    errorNombre.innerHTML = "";
                    errorCodigo.innerHTML = "";
                    errorDescripcion.innerHTML = "";
                    errorCosto.innerHTML = "";
                    errorStock.innerHTML = "";
                    errorCampos.innerHTML = "";
                }

                if (nombre.trim() !== "" && codigo.trim() !== "" && descripcion.trim() !== "" && costo.trim() !== "" && stock.trim() !== "") {
                    if (codigoCambiado) {
                        $.ajax({
                            type: "POST",
                            url: "validar_codigo.php",
                            data: { codigo: codigo },
                            success: function (response) {
                                if (response === "existe") {

                                    $('#codigo').val('');
                                    errorCodigo.innerHTML = "El código " + codigo + " ya existe.";
                                    setTimeout(function () {
                                        errorCodigo.innerHTML = "";
                                    }, 3000);
                                } else {

                                    document.forma01.method = 'post';
                                    document.forma01.action = 'procesar_edicion_producto.php';
                                    document.forma01.submit();
                                }
                            }
                        });
                    } else {

                        document.forma01.method = 'post';
                        document.forma01.action = 'procesar_edicion_producto.php';
                        document.forma01.submit();
                    }
                } else {

                    resetErrors();
                    errorNombre.innerHTML = nombre.trim() === "" ? "Campo obligatorio" : "";
                    errorCodigo.innerHTML = codigo.trim() === "" ? "Campo obligatorio" : "";
                    errorDescripcion.innerHTML = descripcion.trim() === "" ? "Campo obligatorio" : "";
                    errorCosto.innerHTML = costo.trim() === "" ? "Campo obligatorio" : "";
                    errorStock.innerHTML = stock.trim() === "" ? "Campo obligatorio" : "";

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
            <h1>Edición de producto</h1>
            <a href="productos_lista.php" class="btn-regresar">Regresar al listado</a>

            <form name="forma01" action="#" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
            <span id="errorNombre" class="error-message"></span>
        </div>
        <div class="form-group">
            <label for="codigo">Código:</label>
            <input type="text" id="codigo" name="codigo" value="<?php echo $codigo; ?>">
            <div id="errorCodigo" style="color: red;"></div>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">
            <span id="errorDescripcion" class="error-message"></span>
        </div>
        <div class="form-group">
            <label for="costo">Costo:</label>
            <input type="text" id="costo" name="costo" value="<?php echo $costo; ?>">
            <span id="errorCosto" class="error-message"></span>
        </div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="text" id="stock" name="stock" value="<?php echo $stock; ?>">
            <span id="errorStock" class="error-message"></span>
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
