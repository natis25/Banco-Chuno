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

    // Verificar saldo suficiente
    $stmt = $conn->prepare("SELECT saldo FROM Cuenta WHERE idCuenta = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $cuenta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cuenta) {
        throw new Exception('No se encontró la cuenta con el ID proporcionado');
    }

    if ($cuenta['saldo'] < $monto) {
        throw new Exception('Saldo insuficiente para realizar el retiro');
    }

    // Realizar el retiro
    $stmt = $conn->prepare("UPDATE Cuenta SET saldo = saldo - :monto WHERE idCuenta = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':monto', $monto);
    $stmt->execute();

    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => 'Retiro realizado correctamente',
        'idCuenta' => $id,
        'montoRetirado' => $monto
    ]);

} catch (Exception $e) {
    // Manejo de errores
    echo json_encode([
        'success' => false,
        'message' => 'Error al realizar el retiro: ' . $e->getMessage()
    ]);
}
?>