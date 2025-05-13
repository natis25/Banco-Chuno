<?php
require_once 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: mostrarClientes.php");
    exit;
}

$id_cliente = $_GET['id'];

if (!is_numeric($id_cliente)) {
    die("ID de cliente no válido");
}

session_start();

$stmt = $conexion->prepare("DELETE FROM cliente WHERE id_cliente = ?");
if ($stmt) {
    $stmt->bind_param("i", $id_cliente);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Cliente eliminado correctamente";
    } else {
        $_SESSION['error'] = "Error al eliminar cliente: " . $conexion->error;
    }
    
    $stmt->close();
} else {
    $_SESSION['error'] = "Error al preparar la consulta: " . $conexion->error;
}

$conexion->close();

header("Location: mostrarClientes.php");
exit;
?>