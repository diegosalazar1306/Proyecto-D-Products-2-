<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require "funciones/conecta.php";
$con = conecta();
$id = $_POST['id']; 

$sql = "UPDATE promociones SET eliminado = 1 WHERE id = $id";
if ($con->query($sql) === TRUE) {
    echo "success"; 
} else {
    echo "error";
}

$con->close();

?>