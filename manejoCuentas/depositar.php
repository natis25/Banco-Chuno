<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'Cuenta';
$username = 'root';
$password = '';

try {
    // Validar entrada
    if (!isset($_POST['id']) || !isset($_POST['monto'])) {
        throw new Exception('Se requieren los parámetros id y monto');
    }

    $id = $_POST['id'];
    $monto = floatval($_POST['monto']);

    if ($monto <= 0) {
        throw new Exception('El monto debe ser mayor que cero');
    }

    // Conexión a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare("UPDATE Cuenta SET saldo = saldo + :monto WHERE idCuenta = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':monto', $monto);
    $stmt->execute();

    // Verificar si se actualizó algún registro
    if ($stmt->rowCount() === 0) {
        throw new Exception('No se encontró la cuenta con el ID proporcionado');
    }

    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => 'Depósito realizado correctamente',
        'idCuenta' => $id,
        'montoDepositado' => $monto
    ]);

} catch (Exception $e) {
    // Manejo de errores
    echo json_encode([
        'success' => false,
        'message' => 'Error al realizar el depósito: ' . $e->getMessage()
    ]);
}
?>