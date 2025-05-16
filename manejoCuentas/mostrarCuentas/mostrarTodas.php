<?php
$conexion = new mysqli("localhost", "root", "", "cuenta");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "
    SELECT c.idCuenta, cl.nombre_cliente, c.saldo
    FROM cuenta c
    INNER JOIN cliente cl ON c.idCliente = cl.id_cliente
";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    echo "<table class='styled-table'>";
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
    echo "<p>No hay cuentas registradas.</p>";
}

$conexion->close();
