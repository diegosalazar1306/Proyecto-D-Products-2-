<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" type="text/css" href="css/empleados_alta.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function verificarUsuario() {
            var correo = document.forma01.correo.value;
            var pass = document.forma01.pass.value;

            if (correo.trim() === '' || pass.trim() === '') {
                mostrarError("Por favor, completa todos los campos.");
                return;
            }
            mostrarError("");

            $.ajax({
                type: "POST",
                url: "funciones/verificarUsuario.php",
                data: { correo: correo, pass: pass },
                success: function (response) {
                    console.log("Respuesta del servidor:", response);

                    if (response === "success") {
                        window.location.href = "bienvenido.php";
                    } else if (response === "inactive") {
                        mostrarError("Usuario inactivo o eliminado.");
                    } else {
                        mostrarError("Correo o contraseña incorrectos");
                    }
                }
            });
        }

        function mostrarError(mensaje) {
            $("#error-message").text(mensaje);
            setTimeout(function() {
                $("#error-message").text("");
            }, 5000);
        }
    </script>
</head>

<body>
    <h1>Iniciar Sesión</h1>

    <form name="forma01">
        <input type="text" name="correo" id="correo" placeholder="Escribe tu correo" />
        <input type="password" name="pass" id="pass" placeholder="Escribe tu contraseña" required/> <br>
        <div id="error-message" style="color: red;"></div> 
        <input type="button" value="Iniciar Sesión" onclick="verificarUsuario();">
    </form>
</body>
</html>

