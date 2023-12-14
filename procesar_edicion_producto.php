<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "funciones/conecta.php";
    $con = conecta();

    $id = $_POST["id"];
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : "";
    $descripcion = isset($_POST["descripcion"]) ? $_POST["descripcion"] : "";
    $costo = isset($_POST["costo"]) ? floatval(str_replace(",", "", $_POST["costo"])) : "";
    $stock = isset($_POST["stock"]) ? $_POST["stock"] : "";

    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;

    if ($foto && $foto['name'] != '') {
        $archivo = $foto['name'];
        $archivo_n = md5($archivo . time()); // Cambiado a md5 para mantener consistencia
        $archivo_destino = "archivos_productos/" . $archivo_n;
        move_uploaded_file($foto['tmp_name'], $archivo_destino);
    } else {
        $sql = "SELECT archivo, archivo_n FROM productos WHERE id = $id";
        $res = $con->query($sql);
        if ($row = $res->fetch_assoc()) {
            $archivo = $row['archivo'];
            $archivo_n = $row['archivo_n'];
        }
    }

    $sql = "UPDATE productos SET nombre = '$nombre', codigo = '$codigo', descripcion = '$descripcion', costo = '$costo', stock = '$stock', archivo = '$archivo', archivo_n = '$archivo_n' WHERE id = $id";

    if ($con->query($sql) === TRUE) {
        echo "Producto actualizado con éxito.<br>";
        header("Location: productos_lista.php");
        exit;
    } else {
        echo "Error al actualizar el producto: " . $con->error;
    }

    $con->close();
} else {
    echo "Acceso no autorizado.";
}
?>
