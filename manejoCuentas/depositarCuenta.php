<?php
// Verificar si se recibieron los parámetros necesarios
if (!isset($_GET['idCuenta']) || !isset($_GET['idCliente'])) {
    header("Location: listaCuentas.php");
    exit();
}

$idCuenta = $_GET['idCuenta'];
$idCliente = $_GET['idCliente'];

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $monto = floatval($_POST['monto']);
    
    // Validar monto
    if ($monto <= 0) {
        $error = "El monto debe ser mayor que cero";
    } else {
        // Preparar datos para enviar a depositar.php
        $postData = [
            'id' => $idCuenta,
            'monto' => $monto
        ];
        
        // Inicializar cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost/BancoChunoD/manejoCuentas/depositar.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Ejecutar la solicitud
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Decodificar la respuesta JSON
        $resultado = json_decode($response, true);
        
        if ($resultado['success']) {
            // Redirigir a verCuenta.php si el depósito fue exitoso
            header("Location: verCuenta.php?id=$idCuenta&idCliente=$idCliente");
            exit();
        } else {
            $error = $resultado['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depositar en Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Depositar en Cuenta</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="monto" class="form-label">Monto a depositar:</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="monto" name="monto" required>
                            </div>
                            
                            <?php if (isset($error)): ?>
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