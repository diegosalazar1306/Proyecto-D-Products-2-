<!DOCTYPE html>
<html>
<head>
    <title>Alta de Producto</title>
    <link rel="stylesheet" type="text/css" href="css/empleados_alta.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .error-message {
            color: red;
            display: block; /* Para que cada mensaje aparezca en una línea nueva */
        }
    </style>
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
            var errorCamposGeneral = document.getElementById("errorCamposGeneral");

            function resetErrors() {
                errorNombre.innerHTML = "";
                errorCodigo.innerHTML = "";
                errorDescripcion.innerHTML = "";
                errorCosto.innerHTML = "";
                errorStock.innerHTML = "";
                errorCamposGeneral.innerHTML = "";
            }

            if (nombre.trim() !== "" && codigo.trim() !== "" && descripcion.trim() !== "" && costo.trim() !== "" && stock.trim() !== "") {
                var formData = new FormData(document.forms.namedItem("forma01"));
                $.ajax({
                    type: "POST",
                    url: "validar_codigo.php",
                    data: { codigo: codigo },
                    success: function (response) {
                        if (response === "existe") {
                            // El código ya existe, muestra el mensaje de error
                            $('#codigo').val('');
                            errorCodigo.innerHTML = "El código " + codigo + " ya existe.";
                            setTimeout(function () {
                                errorCodigo.innerHTML = "";
                            }, 3000);
                        } else {
                            // El código no existe, envía los datos al archivo PHP para guardar
                            document.forma01.method = 'post';
                            document.forma01.action = 'productos_salva.php';
                            document.forma01.submit();
                        }
                    }
                });
            } else {
                // Muestra el mensaje de error de campos vacíos junto a los campos correspondientes
                resetErrors();
                errorNombre.innerHTML = nombre.trim() === "" ? "Campo obligatorio" : "";
                errorCodigo.innerHTML = codigo.trim() === "" ? "Campo obligatorio" : "";
                errorDescripcion.innerHTML = descripcion.trim() === "" ? "Campo obligatorio" : "";
                errorCosto.innerHTML = costo.trim() === "" ? "Campo obligatorio" : "";
                errorStock.innerHTML = stock.trim() === "" ? "Campo obligatorio" : "";

                // Muestra el mensaje de error general
                errorCamposGeneral.innerHTML = "Todos los campos son obligatorios.";
                setTimeout(function () {
                    resetErrors();
                }, 3000);
            }
        }

        function triggerFileUpload() {
            document.getElementById('file-upload').click();
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
        <a href="promociones_lista.php">Promociones</a>
        <a href="pedidos_lista.php">Pedidos</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </div>

    <h1>Alta de producto</h1>
    <a href="productos_lista.php">Regresar al listado</a><br><br>

    <form name="forma01" enctype="multipart/form-data" method="post" action="productos_salva.php">
        <label for="nombre"></label>
        <input type="text" name="nombre" id="nombre" placeholder="Escribe el nombre" />
        <span id="errorNombre" class="error-message"></span><br>

        <label for="codigo"></label>
        <input type="text" name="codigo" id="codigo" placeholder="Escribe el código" onblur="validarCodigo();" />
        <span id="errorCodigo" class="error-message"></span><br>

        <label for="descripcion"></label>
        <input type="text" name="descripcion" id="descripcion" placeholder="Escribe una descripción" />
        <span id="errorDescripcion" class="error-message"></span><br>

        <label for="costo"></label>
        <input type="text" name="costo" id="costo" placeholder="Ingresa el costo" />
        <span id="errorCosto" class="error-message"></span><br>

        <label for="stock"></label>
        <input type="text" name="stock" id="stock" placeholder="Ingresa el stock" />
        <span id="errorStock" class="error-message"></span><br>

        <div id="errorCamposGeneral" style="color: red;"></div>
        <label for="file-upload" class="custom-file-upload" onclick="triggerFileUpload()">Seleccionar archivo</label>
        <input type="file" name="foto" id="file-upload" accept="image/*" onchange="displayFileName(this)">
        <div id="file-name-display" class="file-name-display"></div>
        <input type="button" value="Enviar" onclick="validacioncampos();">
    </form>
</body>
</html>