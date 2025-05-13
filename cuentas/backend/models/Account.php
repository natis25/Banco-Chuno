<?php
require_once __DIR__ . '/../config/db_cuenta.php';

class Account {
    private $conn;
    private $table_name = "Cuenta";

    public function __construct() {
        try {
            $this->conn = getCuentaConnection();
            // Configurar PDO para que lance excepciones en errores
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Lanzar excepción para que operations.php la capture
            throw new Exception("Error de conexión a la base de datos: " . $exception->getMessage());
        }
    }

    /**
     * Obtiene el saldo actual de una cuenta
     * @param int $idCliente ID del cliente
     * @return array|false Array con el saldo o false si no existe
     */
    public function obtenerSaldo($idCliente) {
        try {
            $query = "SELECT saldo FROM " . $this->table_name . " WHERE idCliente = :idCliente LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener saldo: " . $e->getMessage());
        }
    }

    /**
     * Realiza un depósito en la cuenta
     * @param int $idCliente ID del cliente
     * @param float $monto Monto a depositar
     * @return array|false Nuevo saldo o false en error
     */
    public function depositar($idCliente, $monto) {
        try {
            // Iniciar transacción
            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . " 
                      SET saldo = saldo + :monto 
                      WHERE idCliente = :idCliente";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':monto', $monto, PDO::PARAM_STR);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
            
            // Obtener el nuevo saldo
            $nuevoSaldo = $this->obtenerSaldo($idCliente);
            
            // Confirmar transacción
            $this->conn->commit();
            
            return $nuevoSaldo;
        } catch(PDOException $e) {
            // Revertir en caso de error
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
     */
    public function retirar($idCliente, $monto) {
        try {
            // Verificar saldo primero
            $saldoActual = $this->obtenerSaldo($idCliente);
            
            if (!$saldoActual || $saldoActual['saldo'] < $monto) {
                return false;
            }
            
            // Iniciar transacción
            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . " 
                      SET saldo = saldo - :monto 
                      WHERE idCliente = :idCliente";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':monto', $monto, PDO::PARAM_STR);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
            
            // Obtener el nuevo saldo
            $nuevoSaldo = $this->obtenerSaldo($idCliente);
            
            // Confirmar transacción
            $this->conn->commit();
            
            return $nuevoSaldo;
        } catch(PDOException $e) {
            // Revertir en caso de error
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw new Exception("Error al retirar: " . $e->getMessage());
        }
    }

    // // Cerrar conexión cuando el objeto se destruye
    // public function __destruct() {
    //     if ($this->conn) {
    //         $this->conn = null;
    //     }
    // }
}
?>