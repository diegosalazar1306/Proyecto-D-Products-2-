<?php
require "funciones/conecta.php";
$con = conecta();

$correo = $_POST['correo'];

$sql = "SELECT correo FROM empleados WHERE correo = '$correo'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo "existe";
} else {
    echo "noexiste";
}

$con->close();
?>