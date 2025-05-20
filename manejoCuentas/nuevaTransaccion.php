<?php
// Verificar si se recibieron los parámetros necesarios
if (!isset($_GET['idCuenta']) || !isset($_GET['idCliente'])) {
    header("Location: listaCuentas.php");
    exit();
}

$idCuenta = $_GET['idCuenta'];
$idCliente = $_GET['idCliente'];
$error = '';

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cuentaDestino = $_POST['cuentaDestino'];
    $monto = floatval($_POST['monto']);

    // Validaciones básicas
    if ($monto <= 0) {
        $error = "El monto debe ser mayor que cero";
    } elseif ($cuentaDestino == $idCuenta) {
        $error = "No puede transferir a la misma cuenta";
    } else {
        // Conectar a la base de datos Cuenta
        $conexionCuenta = new mysqli("localhost", "root", "", "Cuenta");
        
        // Verificar saldo suficiente
        $sqlSaldo = "SELECT saldo FROM cuenta WHERE idCuenta = '$idCuenta'";
        $resultadoSaldo = $conexionCuenta->query($sqlSaldo);
        
        if ($resultadoSaldo->num_rows === 0) {
            $error = "Cuenta origen no encontrada";
        } else {
            $saldo = $resultadoSaldo->fetch_assoc()['saldo'];
            
            if ($saldo < $monto) {
                $error = "No se puede realizar la transacción debido a monto insuficiente";
            } else {
                // Verificar si la cuenta destino existe
                $sqlDestino = "SELECT idCuenta FROM cuenta WHERE idCuenta = '$cuentaDestino'";
                $resultadoDestino = $conexionCuenta->query($sqlDestino);
                $existeDestino = $resultadoDestino->num_rows > 0;
                $esCuentaInterna = (substr($cuentaDestino, 0, 4) === "2508");
                
                // Registrar la transacción en la base de datos transferencia
                $conexionTrans = new mysqli("localhost", "root", "", "transferencia");
                
                $fecha = date('Y-m-d');
                $hora = date('H:i:s');
                
                $sqlTrans = "INSERT INTO transaccion (idOrigen, idDestino, monto, fecha, hora) 
                            VALUES ('$idCuenta', '$cuentaDestino', $monto, '$fecha', '$hora')";
                
                if ($conexionTrans->query($sqlTrans)) {
                    // Retirar de cuenta origen
                    $postData = [
                        'id' => $idCuenta,
                        'monto' => $monto
                    ];
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "http://localhost/BancoChunoD/manejoCuentas/sacar.php");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $responseRetiro = curl_exec($ch);
                    curl_close($ch);
                    
                    // Solo hacer depósito si es cuenta interna Y existe
                    if ($esCuentaInterna && $existeDestino) {
                        $postDataDeposito = [
                            'id' => $cuentaDestino,
                            'monto' => $monto
                        ];
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "http://localhost/BancoChunoD/manejoCuentas/depositar.php");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postDataDeposito));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $responseDeposito = curl_exec($ch);
                        curl_close($ch);
                    }
                    
                    // Redirigir a verCuenta.php si todo fue exitoso
                    header("Location: verCuenta.php?id=$idCuenta&idCliente=$idCliente");
                    exit();
                } else {
                    $error = "Error al registrar la transacción: " . $conexionTrans->error;
                }
                
                $conexionTrans->close();
            }
        }
        
        $conexionCuenta->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Transacción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <?php include '../../navbar/navbarEmpleado.php'; ?>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Nueva Transacción</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="cuentaDestino" class="form-label">Cuenta destino:</label>
                                <input type="text" class="form-control" id="cuentaDestino" name="cuentaDestino" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="monto" class="form-label">Monto a transferir:</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="monto" name="monto" required>
                            </div>
                            
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="verCuenta.php?id=<?= $idCuenta ?>&idCliente=<?= $idCliente ?>" class="btn btn-secondary me-md-2">Volver</a>
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>