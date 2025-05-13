<?php
// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos (ajusta los parámetros según tu configuración)
    $conexion = new mysqli("localhost", "root", "", "usuario");
    
    // Verificar conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    
    // Recoger y sanitizar los datos del formulario
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $celular = $conexion->real_escape_string($_POST['celular']);
    $direccion = $conexion->real_escape_string($_POST['direccion']);
    $contrasena = $conexion->real_escape_string($_POST['contrasena']);
    
    // Hash de la contraseña (recomendado para seguridad)
    //$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
    
    // Obtener fecha actual
    $fecha_registro = date('Y-m-d');
    
    // Preparar la consulta SQL
    $sql = "INSERT INTO cliente (nombre_cliente, correo, celular, direccion, contrasena, fecha_registro) 
            VALUES ('$nombre', '$correo', '$celular', '$direccion', '$contrasena', '$fecha_registro')";
    
    // Ejecutar la consulta
    if ($conexion->query($sql) === TRUE) {
        echo "Registro exitoso. ¡Bienvenido, $nombre!";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
    
    // Cerrar conexión
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de nuevo usuario</title>
</head>
<body>
    <h1>Registro de nuevo usuario</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>
        
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" required><br><br>
        
        <label for="celular">Celular:</label>
        <input type="tel" id="celular" name="celular" required><br><br>
        
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required><br><br>
        
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required><br><br>
        
        <input type="submit" value="Registrarse">
    </form>
</body>
</html>
