<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "funciones/conecta.php";
    $con = conecta();

    $nombre = $_POST["nombre"];
    $codigo = $_POST["codigo"];
    $descripcion = $_POST["descripcion"];
    $costo = $_POST["costo"];
    $stock = $_POST["stock"];

    $eliminado = 0;

    if (isset($_POST['eliminar']) && $_POST['eliminar'] == 'si') {
        $eliminado = 1;
    }

    if (!empty($_FILES['foto']['tmp_name'])) {
        $archivo_tmp = $_FILES['foto']['tmp_name'];
        $file_enc = md5_file($archivo_tmp);

        $directorio_destino = "archivos_productos/";

        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }

        $file_extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $archivo_n = $file_enc . "." . $file_extension;

        $ruta_destino = $directorio_destino . $archivo_n;

        // Mueve el archivo a la carpeta de destino con el nombre correcto
        if (move_uploaded_file($archivo_tmp, $ruta_destino)) {
            // El archivo se movió correctamente
        } else {
            // Hubo un error al mover el archivo
            $error_message = "Error al mover el archivo a la carpeta de destino.";
            error_log($error_message);
            echo $error_message;
            exit;
        }
    } else {
        $file_enc = '';
        $archivo_destino = '';
        $archivo_n = '';
    }

    $status = 1;

    $sql = "INSERT INTO productos (nombre, codigo, descripcion, costo, stock, archivo_n, archivo, status, eliminado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssdssssi", $nombre, $codigo, $descripcion, $costo, $stock, $archivo_n, $archivo_destino, $status, $eliminado);

    if ($stmt->execute()) {
        header("Location: productos_lista.php?result=success");
        exit;
    } else {
        $error_message = "Error al dar de alta al producto: " . $stmt->error;
        error_log($error_message);
        echo $error_message;
    }

    $stmt->close();
    $con->close();
} else {
    echo "Acceso no autorizado.";
}
?>
