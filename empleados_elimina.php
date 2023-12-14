<?php
// empleados_elimina.php
require "funciones/conecta.php";
$con = conecta();
$id = $_POST['id']; // Cambia $_REQUEST a $_POST para obtener el ID desde la solicitud AJAX

$sql = "UPDATE empleados SET eliminado = 1 WHERE id = $id";
if ($con->query($sql) === TRUE) {
    echo "success"; // Eliminación exitosa
} else {
    echo "error"; // Error en la eliminación
}

$con->close();
