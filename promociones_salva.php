<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "funciones/conecta.php"; 
    $con = conecta();

    $nombre = $_POST["nombre"];

    $eliminado = 0;

    if (isset($_POST['eliminar']) && $_POST['eliminar'] == 'si') {
        $eliminado = 1;
    }

    if (!empty($_FILES['foto']['tmp_name'])) {
        $archivo_tmp = $_FILES['foto']['tmp_name'];
        $file_enc = md5_file($archivo_tmp);
    
        $directorio_destino = "archivos_promociones/";
    
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
    
        $archivo_destino = $_FILES['foto']['name'];
        $file_extension = pathinfo($archivo_destino, PATHINFO_EXTENSION);
        $archivo = $file_enc . "." . $file_extension;
        move_uploaded_file($archivo_tmp, $directorio_destino . $archivo); 
    } else {
        $file_enc = ''; 
        $archivo_destino = ''; 
        $archivo = ''; 
    }
    $status = 1; 

    $sql = "INSERT INTO promociones (nombre, archivo, status, eliminado) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssds", $nombre, $archivo, $status, $eliminado);

    if ($stmt->execute()) {
        header("Location: promociones_lista.php?result=success");
        exit;
    } else {
        $error_message = "Error al dar de alta la promoción: " . $stmt->error;
        error_log($error_message);
        echo $error_message;
    }

    $stmt->close(); 
    $con->close(); 
} else {
    echo "Acceso no autorizado.";
}
?>

