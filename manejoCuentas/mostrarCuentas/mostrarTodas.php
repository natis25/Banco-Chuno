<?php
// Conexión solo a la base de datos de cuentas
$conexion = new mysqli("localhost", "root", "", "cuenta");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta modificada para obtener solo datos de cuentas
$sql = "SELECT idCuenta, idCliente, saldo FROM cuenta";
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    // Primero obtenemos todos los IDs de clientes
    $idsClientes = [];
    $cuentas = [];
    
    while ($fila = $resultado->fetch_assoc()) {
        $idsClientes[] = $fila['idCliente'];
        $cuentas[] = $fila;
    }
    
    // Conexión a la base de datos de clientes
    $conexionClientes = new mysqli("localhost", "root", "", "usuario");
    if ($conexionClientes->connect_error) {
        die("Error de conexión a clientes: " . $conexionClientes->connect_error);
    }
    
    // Obtenemos los nombres de los clientes
    $placeholders = implode(',', array_fill(0, count($idsClientes), '?'));
    $sqlClientes = "SELECT id_cliente, nombre_cliente FROM cliente WHERE id_cliente IN ($placeholders)";
    
    $stmt = $conexionClientes->prepare($sqlClientes);
    $types = str_repeat('i', count($idsClientes));
    $stmt->bind_param($types, ...$idsClientes);
    $stmt->execute();
    $clientesResult = $stmt->get_result();
    
    $clientes = [];
    while ($cliente = $clientesResult->fetch_assoc()) {
        $clientes[$cliente['id_cliente']] = $cliente['nombre_cliente'];
    }
    
    // Mostramos la tabla
    echo "<table class='styled-table'>";
    echo "<tr><th>Nombre del Cliente</th><th>Número de Cuenta</th><th>Saldo</th></tr>";
    
    foreach ($cuentas as $cuenta) {
        $nombreCliente = $clientes[$cuenta['idCliente'] ?? 'Cliente no encontrado'];
        
        echo "<tr>
                <td>" . htmlspecialchars($nombreCliente) . "</td>
                <td>" . htmlspecialchars($cuenta['idCuenta']) . "</td>
                <td>Bs." . number_format($cuenta['saldo'], 2) . "</td>
              </tr>";
    }
    
    echo "</table>";
    $conexionClientes->close();
} else {
    echo "<p>No hay cuentas registradas.</p>";
}

$conexion->close();
?>