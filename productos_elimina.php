<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require "funciones/conecta.php";
$con = conecta();
$id = $_POST['id']; // Cambia $_REQUEST a $_POST para obtener el ID desde la solicitud AJAX

$sql = "UPDATE productos SET eliminado = 1 WHERE id = $id";
if ($con->query($sql) === TRUE) {
    echo "success"; // Eliminación exitosa
} else {
    echo "error"; // Error en la eliminación
}

$con->close();

?>
