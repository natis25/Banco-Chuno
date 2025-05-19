<?php


class PrestamoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar todos los préstamos
    public function listarTodos() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM prestamos");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al listar préstamos: " . $e->getMessage());
        }
    }

    // Obtener un préstamo por ID
    public function mostrarPorId($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM prestamos WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$prestamo) {
                throw new Exception("Préstamo no encontrado");
            }
            return $prestamo;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar préstamo: " . $e->getMessage());
        }
    }

    // Crear un nuevo préstamo
    public function crear($data) {
        $camposRequeridos = [
            'usuario_externo_id', 'tipo_prestamo_id', 'monto_total', 
            'plazo_prestamo_id', 'tasa_interes_id', 'puntaje_crediticio_id', 
            'fecha_inicio', 'fecha_fin'
        ];

        foreach ($camposRequeridos as $campo) {
            if (!isset($data[$campo])) {
                throw new Exception("Falta el campo requerido: $campo");
            }
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO prestamos 
                (usuario_externo_id, tipo_prestamo_id, monto_total, plazo_prestamo_id, 
                 tasa_interes_id, puntaje_crediticio_id, fecha_inicio, fecha_fin, estado_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
            ");

            $stmt->execute([
                $data['usuario_externo_id'],
                $data['tipo_prestamo_id'],
                $data['monto_total'],
                $data['plazo_prestamo_id'],
                $data['tasa_interes_id'],
                $data['puntaje_crediticio_id'],
                $data['fecha_inicio'],
                $data['fecha_fin']
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error al crear préstamo: " . $e->getMessage());
        }
    }

    // Actualizar un préstamo
    public function actualizar($id, $data) {
        $camposPermitidos = [
            'monto_total', 'plazo_prestamo_id', 'tasa_interes_id', 
            'puntaje_crediticio_id', 'fecha_fin', 'estado_id'
        ];

        $updates = [];
        $valores = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $camposPermitidos)) {
                $updates[] = "$key = ?";
                $valores[] = $value;
            }
        }

        if (empty($updates)) {
            throw new Exception("No hay campos válidos para actualizar");
        }

        $valores[] = $id;

        try {
            $stmt = $this->pdo->prepare("
                UPDATE prestamos 
                SET " . implode(', ', $updates) . " 
                WHERE id = ?
            ");
            $stmt->execute($valores);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar préstamo: " . $e->getMessage());
        }
    }

    // Eliminar un préstamo
    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM prestamos WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar préstamo: " . $e->getMessage());
        }
    }
}