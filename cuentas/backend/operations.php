<?php
// Configuración de CORS (debe ir primero)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Incluir archivos con rutas CORRECTAS (usa esta versión)
require_once __DIR__ . '/models/Account.php';  // Ruta relativa al directorio actual

class AccountController {
    private $account;
    
    public function __construct() {
        $this->account = new Account();
    }
    
    public function handleRequest() {
        try {
            // Solo aceptamos método POST para operaciones que modifican datos
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(["error" => "Método no permitido"]);
                return;
            }
            
            // Obtener datos del cuerpo de la petición
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Validar entrada JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(["error" => "JSON inválido"]);
                return;
            }
            
            // Obtener y validar parámetros
            $idCliente = isset($input['id_cliente']) ? (int)$input['id_cliente'] : null;
            $operacion = isset($input['operacion']) ? $input['operacion'] : null;
            $monto = isset($input['monto']) ? (float)$input['monto'] : 0;
            
            // Validaciones básicas
            if (!$idCliente || !$operacion || $monto <= 0) {
                http_response_code(400);
                echo json_encode(["error" => "Parámetros inválidos"]);
                return;
            }
            
            // Procesar operación
            $this->processOperation($idCliente, $operacion, $monto);
            
        } catch(Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    
    private function processOperation($idCliente, $operacion, $monto) {
        $resultado = null;
        
        switch(strtolower($operacion)) {
            case 'depositar':
                $resultado = $this->account->depositar($idCliente, $monto);
                break;
            case 'retirar':
                $resultado = $this->account->retirar($idCliente, $monto);
                break;
            default:
                http_response_code(400);
                echo json_encode(["error" => "Operación no válida"]);
                return;
        }
        
        if ($resultado === false) {
            http_response_code(400);
            echo json_encode(["error" => "Error en la operación"]);
        } else {
            echo json_encode([
                "success" => true,
                "nuevo_saldo" => $resultado['saldo']
            ]);
        }
    }
}

// Ejecutar el controlador
$controller = new AccountController();
$controller->handleRequest();
?>