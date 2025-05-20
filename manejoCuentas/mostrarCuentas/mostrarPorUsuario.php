<?php
session_start();

if (!isset($_GET['id'])) {
    die("ID de cliente no proporcionado.");
}

$idCliente = intval($_GET['id']);

// Conexión a la base de datos de cuentas
$conexionCuentas = new mysqli("localhost", "root", "", "cuenta");

if ($conexionCuentas->connect_error) {
    die("Error de conexión a cuentas: " . $conexionCuentas->connect_error);
}

// Conexión a la base de datos de clientes
$conexionClientes = new mysqli("localhost", "root", "", "usuario");
if ($conexionClientes->connect_error) {
    die("Error de conexión a clientes: " . $conexionClientes->connect_error);
}

// 1. Primero obtenemos la información del cliente
$sqlCliente = "SELECT nombre_cliente FROM cliente WHERE id_cliente = ?";
$stmtCliente = $conexionClientes->prepare($sqlCliente);
$stmtCliente->bind_param("i", $idCliente);
$stmtCliente->execute();
$resultadoCliente = $stmtCliente->get_result();

if ($resultadoCliente->num_rows === 0) {
    die("<p>Cliente no encontrado.</p>");
}

$cliente = $resultadoCliente->fetch_assoc();
$nombreCliente = $cliente['nombre_cliente'];

// 2. Luego obtenemos las cuentas del cliente
$sqlCuentas = "SELECT idCuenta, saldo FROM cuenta WHERE idCliente = ?";
$stmtCuentas = $conexionCuentas->prepare($sqlCuentas);
$stmtCuentas->bind_param("i", $idCliente);
$stmtCuentas->execute();
$resultadoCuentas = $stmtCuentas->get_result();

if ($resultadoCuentas->num_rows > 0) {
    echo "<table class='styled-table'>";
    echo "<tr><th>Nombre del Cliente</th><th>No. de Cuenta</th><th>Saldo</th></tr>";

    while ($cuenta = $resultadoCuentas->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($nombreCliente) . "</td>
                <td>" . htmlspecialchars($cuenta['idCuenta']) . "</td>
                <td>Bs." . number_format($cuenta['saldo'], 2) . "</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No se encontró ninguna cuenta para este cliente.</p>";
}

// Cerrar statements y conexiones
$stmtCliente->close();
$stmtCuentas->close();
$conexionClientes->close();
$conexionCuentas->close();
