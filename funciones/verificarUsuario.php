<?php
require "conecta.php";
$con = conecta();

$correo = $_REQUEST['correo'];
$pass = $_REQUEST['pass'];
$passEnc = md5($pass);

$sql = "SELECT * FROM empleados
        WHERE correo = '$correo' AND (pass = '$pass' OR pass_enc = '$passEnc')
        AND status = 1 AND eliminado = 0";

$res = $con->query($sql);
$num = $res->num_rows;

if ($num > 0) {
    session_start(); // Iniciar la sesión

    // Obtener el nombre del usuario y guardarlo en la sesión
    $usuarioData = $res->fetch_assoc();
    $_SESSION['usuario'] = $usuarioData['nombre']; // Suponiendo que el nombre del usuario está en la columna 'nombre'

    echo "success";
} else {
    $inactiveSql = "SELECT * FROM empleados WHERE correo = '$correo'";
    $inactiveRes = $con->query($inactiveSql);

    if ($inactiveRes->num_rows > 0) {
        $inactiveRow = $inactiveRes->fetch_assoc();

        if ($inactiveRow['eliminado'] == 1) {
            echo "inactive";
        } else {
            echo "failed (contraseña incorrecta)";
        }
    } else {
        echo "failed (usuario no encontrado)";
    }
}

$con->close();
?>
