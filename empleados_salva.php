<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "funciones/conecta.php"; 
    $con = conecta();

    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $correo = $_POST["correo"];
    $rol = $_POST["rol"];
    $pass = $_POST["pass"];
    $passEnc = md5($pass);

    if (!empty($_FILES['foto']['tmp_name'])) {
        $archivo_tmp = $_FILES['foto']['tmp_name'];
        $file_enc = md5_file($archivo_tmp);

        $directorio_destino = "archivos/";
        $archivo_n = $_FILES['foto']['name'];
        $file_extension = pathinfo($archivo_n, PATHINFO_EXTENSION);
        $archivo_destino = $directorio_destino . $file_enc . "." . $file_extension;
        move_uploaded_file($archivo_tmp, $archivo_destino);
    } else {
        $file_enc = ''; 
        $archivo_destino = ''; 
    }

    $sql = "INSERT INTO empleados (nombre, apellidos, correo, rol, pass, pass_enc, foto_nombre_real, foto_nombre_encriptado) VALUES ('$nombre', '$apellidos', '$correo', $rol, '$pass', '$passEnc', '$archivo_n', '$file_enc.$file_extension')";


    if ($con->query($sql) === TRUE) {
      
        header("Location: lista_de_empleados.php");
        exit; 
    } else {
        echo "Error al dar de alta al empleado: " . $con->error;
    }

    $con->close(); 
} else {
    echo "Acceso no autorizado.";
}
?>
