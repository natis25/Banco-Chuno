<?php
// Verificar si se recibieron los parámetros necesarios
if (!isset($_GET['id']) || !isset($_GET['idCliente'])) {
    header("Location: listaCuentas.php");
    exit();
}

$idCuenta = $_GET['id'];
$idCliente = $_GET['idCliente'];

// Conectar a la base de datos Cuenta
$conexionCuenta = new mysqli("localhost", "root", "", "Cuenta");

// Obtener información de la cuenta
$sqlCuenta = "SELECT idCuenta, saldo FROM cuenta WHERE idCuenta = '$idCuenta'";
$resultadoCuenta = $conexionCuenta->query($sqlCuenta);

if ($resultadoCuenta->num_rows === 0) {
    die("Cuenta no encontrada");
}

$cuenta = $resultadoCuenta->fetch_assoc();

// Conectar a la base de datos transferencia
$conexionTrans = new mysqli("localhost", "root", "", "transferencia");

// Conectar a la base de datos bancos
$conexionBancos = new mysqli("localhost", "root", "", "bancos");

// Obtener transacciones relacionadas con esta cuenta
$sqlTrans = "SELECT * FROM transaccion WHERE idOrigen = '$idCuenta' OR idDestino = '$idCuenta' ORDER BY fecha DESC, hora DESC";
$resultadoTrans = $conexionTrans->query($sqlTrans);

// Función para obtener nombre del banco
function obtenerNombreBanco($codigoBanco, $conexion) {
    $sqlBanco = "SELECT nombre FROM banco WHERE id = '$codigoBanco'";
    $resultado = $conexion->query($sqlBanco);
    return ($resultado->num_rows > 0) ? $resultado->fetch_assoc()['nombre'] : 'Banco desconocido';
}

$conexionCuenta->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar/navbarEmpleado.php'; ?>
    <br>
    <br>
    <div class="container mt-4">
        <!-- Encabezado con información de la cuenta -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Cuenta: <?= $cuenta['idCuenta'] ?></h2>
                <h4>Saldo: $<?= number_format($cuenta['saldo'], 2) ?></h4>
            </div>
            <a href="listaCuentas.php?id=<?= $idCliente ?>" class="btn btn-secondary">Salir</a>
        </div>

        <!-- Tabla de movimientos -->
        <h3 class="mb-3">Historial de Movimientos</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Movimiento</th>
                        <th>Cuenta Externa</th>
                        <th>Monto</th>
                        <th>Banco</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultadoTrans->num_rows === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay movimientos registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php while($trans = $resultadoTrans->fetch_assoc()): 
                            $cuentaExterna = ($trans['idOrigen'] == $idCuenta) ? $trans['idDestino'] : $trans['idOrigen'];
                            $codigoBanco = substr($cuentaExterna, 0, 4);
                            $nombreBanco = obtenerNombreBanco($codigoBanco, $conexionBancos);
                        ?>
                            <tr>
                                <td>
                                    <?= ($trans['idOrigen'] == $idCuenta) ? 'Transacción' : 'Ingreso' ?>
                                </td>
                                <td><?= $cuentaExterna ?></td>
                                <td class="<?= ($trans['idOrigen'] == $idCuenta) ? 'text-danger' : 'text-success' ?>">
                                    <?= ($trans['idOrigen'] == $idCuenta) ? '-' : '+' ?>
                                    $<?= number_format($trans['monto'], 2) ?>
                                </td>
                                <td><?= $nombreBanco ?></td>
                                <td><?= $trans['fecha'] ?></td>
                                <td><?= $trans['hora'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Botones de acciones -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="depositarCuenta.php?idCuenta=<?= $idCuenta ?>&idCliente=<?= $idCliente ?>" class="btn btn-success">Depositar</a>
            <a href="extraerCuenta.php?idCuenta=<?= $idCuenta ?>&idCliente=<?= $idCliente ?>" class="btn btn-danger">Extraer</a>
            <a href="nuevaTransaccion.php?idCliente=<?= $idCliente ?>&idCuenta=<?= $idCuenta ?>" class="btn btn-primary">Transacción</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conexionTrans->close();
$conexionBancos->close();
?>