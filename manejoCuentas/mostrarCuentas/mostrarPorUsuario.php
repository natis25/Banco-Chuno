<?php
session_start();

if (!isset($_GET['id'])) {
    die("ID de cliente no proporcionado.");
}

$idCliente = intval($_GET['id']);

$conexion = new mysqli("localhost", "root", "", "cuenta");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "
    SELECT c.idCuenta, cl.nombre_cliente, c.saldo
    FROM cuenta c
    INNER JOIN cliente cl ON c.idCliente = cl.id_cliente
    WHERE cl.id_cliente = ?
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idCliente);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Nombre del Cliente</th><th>ID de Cuenta</th><th>Saldo</th></tr>";
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($fila["nombre_cliente"]) . "</td>
                <td>" . htmlspecialchars($fila["idCuenta"]) . "</td>
                <td>$" . number_format($fila["saldo"], 2) . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontró ninguna cuenta para este cliente.</p>";
}

$stmt->close();
$conexion->close();
?>
