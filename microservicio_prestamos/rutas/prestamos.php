<?php
// Incluir controlador y modelos
require __DIR__ . '/../app/Controladores/PrestamoController.php';
$controller = new PrestamoController($pdo);

// Obtener el método HTTP y el ID si existe
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($uri_segments[2]) && is_numeric($uri_segments[2]) ? (int)$uri_segments[2] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            $controller->mostrar($id);
        } else {
            $controller->listar();
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $controller->crear($data);
        break;

    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere un ID para actualizar']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $controller->actualizar($id, $data);
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere un ID para eliminar']);
            exit;
        }
        $controller->eliminar($id);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}