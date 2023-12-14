<?php
require "funciones/conecta.php";
$con = conecta();

$nombre = $_POST['nombre'];

$sql = "SELECT nombre FROM promociones WHERE nombre = '$nombre'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo "existe";
} else {
    echo "noexiste";
}

$con->close();
?>