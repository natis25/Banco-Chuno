<?php
require_once 'conexion.php';
session_start();
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} elseif (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

$sql_clientes = "SELECT id_cliente, nombre_cliente, correo, celular, direccion, contrasena, fecha_registro FROM cliente";
$resultado_clientes = $conexion->query($sql_clientes);

$clientes = [];
if ($resultado_clientes && $resultado_clientes->num_rows > 0) {
    while($fila = $resultado_clientes->fetch_assoc()) {
        $clientes[] = $fila;
    }
}

$conexion->close();
?>