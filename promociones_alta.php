<!DOCTYPE html>
<html>
<head>
    <title>Alta de Promoción</title>
    <link rel="stylesheet" type="text/css" href="css/empleados_alta.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .error-message {
            color: red;
            display: block; 
        }
    </style>
    <script>
        function validacioncampos() {
            var nombre = document.getElementById("nombre").value;
            var errorNombre = document.getElementById("errorNombre");
            var errorCamposGeneral = document.getElementById("errorCamposGeneral");

            function resetErrors() {
                errorNombre.innerHTML = "";
                errorCamposGeneral.innerHTML = "";
            }

            if (nombre.trim() !== "") {
                var formData = new FormData(document.forms.namedItem("forma01"));
                $.ajax({
                    type: "POST",
                    url: "validar_promocion.php",
                    data: { nombre: nombre },
                    success: function (response) {
                        if (response === "existe") {
                            $('#nombre').val('');
                            errorCodigo.innerHTML = "El nombre " + nombre + " ya existe.";
                            setTimeout(function () {
                                errorCodigo.innerHTML = "";
                            }, 3000);
                        } else {
                            document.forma01.method = 'post';
                            document.forma01.action = 'promociones_salva.php';
                            document.forma01.submit();
                        }
                    }
                });
            } else {
                resetErrors();
                errorNombre.innerHTML = nombre.trim() === "" ? "Campo obligatorio" : "";
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
        <a href="pedidos_lista.phpt">Pedidos</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </div>

    <h1>Alta de Promoción</h1>
    <a href="productos_lista.php">Regresar al listado</a><br><br>

    <form name="forma01" enctype="multipart/form-data" method="post" action="productos_salva.php">
        <label for="nombre"></label>
        <input type="text" name="nombre" id="nombre" placeholder="Escribe el nombre" />
        <span id="errorNombre" class="error-message"></span><br>

        <div id="errorCamposGeneral" style="color: red;"></div>
        <label for="file-upload" class="custom-file-upload" onclick="triggerFileUpload()">Seleccionar archivo</label>
        <input type="file" name="foto" id="file-upload" accept="image/*" onchange="displayFileName(this)">
        <div id="file-name-display" class="file-name-display"></div>
        <input type="button" value="Enviar" onclick="validacioncampos();">
    </form>
</body>
</html>