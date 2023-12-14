<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "funciones/conecta.php"; // Establece la conexión a la base de datos
    $con = conecta();

    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $correo = $_POST["correo"];
    $rol = $_POST["rol"];
    $password = $_POST["password"];
    
    // Obtén los detalles del archivo de imagen
    $foto = $_FILES['foto']; // Obtiene el archivo de imagen
    $foto_nombre_real = $foto['name']; // Nombre real del archivo
    $foto_nombre_temporal = $foto['tmp_name']; // Ruta temporal del archivo

    // Verifica si se cargó un nuevo archivo de imagen
    if ($foto_nombre_real != '') {
        // Obtiene la extensión del archivo
        $extension = pathinfo($foto_nombre_real, PATHINFO_EXTENSION);
        
        // Genera un nombre encriptado para la imagen
        $foto_nombre_encriptado = md5($foto_nombre_real) . '.' . $extension;

        // Mueve el archivo cargado a la carpeta de imágenes
        $directorio_destino = "archivos/";
        $archivo_destino = $directorio_destino . $foto_nombre_encriptado;
        move_uploaded_file($foto_nombre_temporal, $archivo_destino);
    } else {
        // Si no se cargó una nueva imagen, mantén los nombres actuales en la base de datos
        $sql = "SELECT foto_nombre_real, foto_nombre_encriptado FROM empleados WHERE id = $id";
        $res = $con->query($sql);
        if ($row = $res->fetch_assoc()) {
            $foto_nombre_real = $row['foto_nombre_real'];
            $foto_nombre_encriptado = $row['foto_nombre_encriptado'];
        }
    }

    // Consulta SQL para actualizar los datos del empleado, incluyendo la imagen
    $sql = "UPDATE empleados SET nombre = '$nombre', apellidos = '$apellidos', correo = '$correo', rol = $rol, foto_nombre_real = '$foto_nombre_real', foto_nombre_encriptado = '$foto_nombre_encriptado' WHERE id = $id";

    if ($con->query($sql) === TRUE) {
        // Actualización exitosa
        echo "Empleado actualizado con éxito.<br>";
        header("Location: lista_de_empleados.php");
        exit;
    } else {
        echo "Error al actualizar el empleado: " . $con->error;
    }

    $con->close(); // Cierra la conexión a la base de datos
} else {
    // Manejo de la solicitud GET o cualquier otro método que no sea POST
    echo "Acceso no autorizado.";
}
?>




