<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once '../models/CuentaModel.php';

try {
    $cuenta = new CuentaModel();
    
    // Manejar GET (para obtener saldo)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
        if ($_GET['action'] === 'obtener_saldo' && isset($_GET['id_cliente'])) {
            $saldo = $cuenta->obtenerSaldo($_GET['id_cliente']);
            echo json_encode(["success" => true, "saldo" => $saldo]);
            exit;
        }
    }
    
    // Manejar POST (para depositar/retirar)
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['id_cliente']) || !isset($input['operacion']) || !isset($input['monto'])) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan parámetros en la solicitud"]);
        exit;
    }

    $idCliente = $input['id_cliente'];
    $operacion = $input['operacion'];
    $monto = $input['monto'];
    
    if ($operacion !== 'depositar' && $operacion !== 'retirar') {
        http_response_code(400);
        echo json_encode(["error" => "Operación no válida"]);
        exit;
    }
    
    if ($operacion === 'depositar') {
        $nuevoSaldo = $cuenta->depositar($idCliente, $monto);
        echo json_encode(["success" => true, "nuevo_saldo" => $nuevoSaldo]);
    } else if ($operacion === 'retirar') {
        $nuevoSaldo = $cuenta->retirar($idCliente, $monto);
        echo json_encode(["success" => true, "nuevo_saldo" => $nuevoSaldo]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error: " . $e->getMessage()]);
}
?>