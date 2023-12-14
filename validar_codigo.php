<?php
require "funciones/conecta.php";
$con = conecta();

$codigo = $_POST['codigo'];

$sql = "SELECT codigo FROM productos WHERE codigo = '$codigo'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo "existe";
} else {
    echo "noexiste";
}

$con->close();
?>