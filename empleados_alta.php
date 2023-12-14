<?php
session_start(); 


if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alta de empleado</title>
    <link rel="stylesheet" type="text/css" href="css/empleados_alta.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function validacioncampos() {
            var nombre = document.forma01.nombre.value;
            var apellidos = document.forma01.apellidos.value;
            var correo = document.forma01.correo.value;
            var pass = document.forma01.pass.value;
            var rol = document.forma01.rol.value;

            var errorCampos = document.getElementById("errorCampos");

            if (nombre && apellidos && correo && pass && rol != 0) {
                $.ajax({
                    type: "POST",
                    url: "validar_correo.php",
                    data: { correo: correo },
                    success: function (response) {
                        if (response === "existe") {
        
                            $('#correo').val('');
                            $("#errorCorreo").html("El correo " + correo + " ya existe.");
                            setTimeout(function () {
                                $("#errorCorreo").html("");
                            }, 5000);
                        } else {
                            document.forma01.method = 'post';
                            document.forma01.action = 'empleados_salva.php';
                            document.forma01.submit();
                        }
                    }
                });
            } else {
                errorCampos.innerHTML = "Error: Faltan campos por llenar.";
                setTimeout(function () {
                    errorCampos.innerHTML = ""; 
                }, 5000);
            }
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

    <h1>Alta de empleados</h1>
    <a href="lista_de_empleados.php">Regresar al listado</a><br><br>

    <form name="forma01" enctype="multipart/form-data">
        <input type="text" name="nombre" id="nombre" placeholder="Escribe tu nombre" /> <br>
        <input type="text" name="apellidos" id="apellidos" placeholder="Escribe tus apellidos" /> <br>
        <input type="text" name="correo" id="correo" placeholder="Escribe tu correo" onblur="validacioncampos();" />
        <div id="errorCorreo" style="color: red;"></div>
        <input type="password" name="pass" id="pass" placeholder="Escribe tu contraseña" required/> <br>
        <select name="rol" id="rol">
            <option value="0">Selecciona</option>
            <option value="1">Gerente</option>
            <option value="2">Ejecutivo</option>
        </select> <br>
        <input type="file" name="foto" id="foto" accept="image/*"> <br>
        <input type="button" value="Enviar" onclick="validacioncampos();">
    </form>

    <div id="errorCampos" style="color: red;"></div> 
</body>
</html>




