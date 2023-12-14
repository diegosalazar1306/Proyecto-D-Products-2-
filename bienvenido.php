<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Redirigir a la página de login si no hay sesión
    exit();
}

$nombreUsuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bienvenido.css">
    <title>Bienvenido</title>
</head>
<body>
    <div class="navbar">
        <a href="bienvenido.php">Inicio</a>
        <a href="lista_de_empleados.php">Lista de empleados</a>
        <a href="productos_lista.php">Productos</a>
        <a href="promociones_lista.php">Promociones</a>
        <a href="pedidos_lista.php">Pedidos</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </div>
    <h1>¡Bienvenido <?php echo $nombreUsuario; ?>!</h1>
</body>
</html>
