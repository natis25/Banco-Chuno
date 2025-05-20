<?php
// Verificar si se recibió el id_cliente
if (!isset($_GET['id'])) {
    header("Location: mostrarClientes.php");
    exit();
}

$idCliente = $_GET['id'];

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "Cuenta");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener información del cliente
$sqlCliente = "SELECT nombre_cliente FROM usuario.cliente WHERE id_cliente = '$idCliente'";
$resultadoCliente = $conexion->query($sqlCliente);
$nombreCliente = $resultadoCliente->fetch_assoc()['nombre_cliente'];

// Obtener cuentas del cliente
$sql = "SELECT idCuenta, saldo FROM cuenta WHERE idCliente = '$idCliente'";
$resultado = $conexion->query($sql);
?>

<!-- Actualiza los enlaces de navegación -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuentas del Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar/navbarEmpleado.php'; ?>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Cuentas de <?= htmlspecialchars($nombreCliente) ?></h1>
        
        <div class="d-flex justify-content-between mb-3">
            <a href="../manejoClientes/mostrarClientes.php" class="btn btn-secondary">Volver a clientes</a>
            <a href="nuevaCuenta.php?id=<?= $idCliente ?>" class="btn btn-success">Nueva Cuenta</a>
        </div>

        <?php if ($resultado->num_rows === 0): ?>
            <div class="alert alert-info">Este cliente no tiene cuentas registradas</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Número de cuenta</th>
                            <th>Saldo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($cuenta = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= $cuenta['idCuenta'] ?></td>
                                <td>$<?= number_format($cuenta['saldo'], 2) ?></td>
                                <td>
                                    <a href="verCuenta.php?id=<?= $cuenta['idCuenta'] ?>&idCliente=<?= $idCliente ?>" class="btn btn-primary">
                                        Ver cuenta
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conexion->close();
?>