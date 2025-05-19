<?php
require_once __DIR__ . '/../Modelos/PrestamoModel.php';

class PrestamoController {
    private $model;

    public function __construct($pdo) {
        $this->model = new PrestamoModel($pdo);
    }

    // GET /prestamos
    public function listar() {
        try {
            $prestamos = $this->model->listarTodos();
            echo json_encode($prestamos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // GET /prestamos/{id}
    public function mostrar($id) {
        try {
            $prestamo = $this->model->mostrarPorId($id);
            echo json_encode($prestamo);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // POST /prestamos
    public function crear($data) {
        try {
            $id = $this->model->crear($data);
            http_response_code(201);
            echo json_encode(['id' => $id, 'mensaje' => 'Préstamo creado']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // PUT /prestamos/{id}
    public function actualizar($id, $data) {
        try {
            $filasAfectadas = $this->model->actualizar($id, $data);
            echo json_encode(['filas_afectadas' => $filasAfectadas]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // DELETE /prestamos/{id}
    public function eliminar($id) {
        try {
            $filasAfectadas = $this->model->eliminar($id);
            echo json_encode(['filas_afectadas' => $filasAfectadas]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}