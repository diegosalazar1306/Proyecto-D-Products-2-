<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "funciones/conecta.php";
    $con = conecta();

    $id = $_POST["id"];
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;

    // Obtén el nombre del archivo actual para eliminarlo después de la actualización
    $sql = "SELECT archivo FROM promociones WHERE id = $id";
    $res = $con->query($sql);
    $archivo_anterior = "";
    if ($row = $res->fetch_assoc()) {
        $archivo_anterior = $row['archivo'];
    }

    if ($foto && $foto['name'] != '') {
        $archivo = md5($foto['name'] . time());
        $archivo_destino = "archivos_promociones/" . $archivo;
        move_uploaded_file($foto['tmp_name'], $archivo_destino);

        // Elimina el archivo anterior después de cargar el nuevo
        if ($archivo_anterior != "") {
            $ruta_anterior = "archivos_promociones/" . $archivo_anterior;
            if (file_exists($ruta_anterior)) {
                unlink($ruta_anterior);
            }
        }
    } else {
        $archivo = $archivo_anterior;
    }

    $sql = "UPDATE promociones SET nombre = ?, archivo = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $archivo, $id);

    if ($stmt->execute()) {
        echo "Producto actualizado con éxito.<br>";
        header("Location: promociones_lista.php");
        exit;
    } else {
        echo "Error al actualizar el promoción: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
} else {
    echo "Acceso no autorizado.";
}
?>
