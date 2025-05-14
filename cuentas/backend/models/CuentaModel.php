<?php
require_once __DIR__ . '/../config/db_cuenta.php';

class CuentaModel {
    private $conn;
    private $table_name = "cuenta";

    public function __construct() {
    try {
        // Solo la conexión básica
        $this->conn = getCuentaConnection();
    } catch(PDOException $e) {
        throw new Exception("Error de conexión: " . $e->getMessage());
    }
}

    /**
     * Obtiene el saldo actual de una cuenta
     * @param int $idCliente ID del cliente
     * @return array|false Array con el saldo o false si no existe
     */
    public function obtenerSaldo($idCliente) {
        try {
            $query = "SELECT saldo FROM " . $this->table_name . " WHERE idCliente = :idCliente";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idCliente', $idCliente);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$resultado) {
                throw new Exception("No se encontró la cuenta para el cliente especificado");
            }
            
            // Devolver solo el valor del saldo, no el array completo
            return $resultado['saldo'];
        } catch(PDOException $e) {
            throw new Exception("Error al obtener saldo: " . $e->getMessage());
        }
    }
    /**
     * Realiza un depósito en la cuenta
     * @param int $idCliente ID del cliente
     * @param float $monto Monto a depositar
     * @return array Nuevo saldo
     * @throws Exception Si ocurre un error
     */
    public function depositar($idCliente, $monto) {
        try {
            // Validar monto positivo
            if ($monto <= 0) {
                throw new Exception("El monto debe ser positivo");
            }

            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . " 
                      SET saldo = saldo + :monto 
                      WHERE idCliente = :idCliente";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':monto', $monto);
            $stmt->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("No se actualizó ninguna cuenta");
            }
            
            $nuevoSaldo = $this->obtenerSaldo($idCliente);
            $this->conn->commit();
            
            return $nuevoSaldo;
        } catch(Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw new Exception("Error al depositar: " . $e->getMessage());
        }
    }

    /**
     * Realiza un retiro de la cuenta
     * @param int $idCliente ID del cliente
     * @param float $monto Monto a retirar
     * @return array|false Nuevo saldo o false si no hay fondos
     * @throws Exception Si ocurre un error
     */
    public function retirar($idCliente, $monto) {
        try {
            // Validar monto positivo
            if ($monto <= 0) {
                throw new Exception("El monto debe ser positivo");
            }

            $saldoActual = $this->obtenerSaldo($idCliente);
            
            if (!$saldoActual || $saldoActual < $monto) {
                return false;
            }
            
            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . " 
                      SET saldo = saldo - :monto 
                      WHERE idCliente = :idCliente";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':monto', $monto);
            $stmt->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("No se actualizó ninguna cuenta");
            }
            
            $nuevoSaldo = $this->obtenerSaldo($idCliente);
            $this->conn->commit();
            
            return $nuevoSaldo;
        } catch(Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw new Exception("Error al retirar: " . $e->getMessage());
        }
    }

    /**
     * Cierra la conexión a la base de datos
     */
    public function cerrarConexion() {
        $this->conn = null;
    }

    public function __destruct() {
        $this->cerrarConexion();
    }
}