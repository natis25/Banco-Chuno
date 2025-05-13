<?php
require_once __DIR__ . '/config/db_cuenta.php';

try {
    $conn = getCuentaConnection();
    echo "¡Conexión exitosa a la base de datos!";
    
    // Prueba adicional: consultar alguna tabla
    $stmt = $conn->query("SHOW TABLES");
    echo "<br>Tablas en la base de datos: " . $stmt->rowCount();
    
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>