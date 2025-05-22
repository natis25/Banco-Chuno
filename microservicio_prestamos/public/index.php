<?php
// Configuración inicial
header('Content-Type: application/json');
require __DIR__ . '/../config/database.php';

// Habilitar CORS (ajustar en producción)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Manejo básico de rutas
// Obtener la URI sin parámetros de query
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Eliminar el subdirectorio del proyecto de la URI (si aplica)
$base_path = '/Banco-Chuno/microservicio_prestamos/public';
$clean_uri = str_replace($base_path, '', $request_uri);
$uri_segments = array_values(array_filter(explode('/', $clean_uri)));

// Ruta base (ej: si la URI es "/prestamos/1", $base_route será "prestamos")
$base_route = $uri_segments[0] ?? '';

// Manejar la ruta raíz "/"
if ($base_route === '') {
    echo json_encode(['mensaje' => 'Microservicio de préstamos en línea']);
    exit;
}

// Incluir archivo de rutas
$routes_file = __DIR__ . '/../rutas/' . $base_route . '.php';

if (file_exists($routes_file)) {
    require $routes_file;
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
    exit;
}