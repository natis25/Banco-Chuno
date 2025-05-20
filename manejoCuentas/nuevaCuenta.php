<?php
// Verificar si se recibió el id_cliente
if (!isset($_GET['id'])) {
    header("Location: mostrarClientes.php");
    exit();
}

$idCliente = $_GET['id'];

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $montoInicial = floatval($_POST['monto']);
    $idCuenta = $_POST['idCuenta']; // Recibimos el ID generado por JavaScript
    
    // Validar monto
    if ($montoInicial <= 0) {
        $error = "El monto inicial debe ser mayor a cero";
    } else {
        // Conectar a la base de datos
        $conexion = new mysqli("localhost", "root", "", "Cuenta");

        // Verificar si el número de cuenta ya existe
        $verificar = "SELECT idCuenta FROM cuenta WHERE idCuenta = '$idCuenta'";
        if ($conexion->query($verificar)->num_rows > 0) {
            $error = "Error: El número de cuenta ya existe. Intente nuevamente.";
        } else {
            // Insertar nueva cuenta
            $sql = "INSERT INTO cuenta (idCuenta, saldo, idCliente) VALUES ('$idCuenta', $montoInicial, '$idCliente')";
            
            if ($conexion->query($sql)) {
                header("Location: listaCuentas.php?id=$idCliente");
                exit();
            } else {
                $error = "Error al crear la cuenta: " . $conexion->error;
            }
        }

        $conexion->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar/navbarEmpleado.php'; ?>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Crear Nueva Cuenta</h1>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post" action="" onsubmit="generarNumeroCuenta()">
                    <input type="hidden" name="idCuenta" id="idCuenta">
                    
                    <div class="mb-3">
                        <label for="monto" class="form-label">Monto inicial:</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="monto" name="monto" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Número de cuenta:</label>
                        <div class="form-control" id="numeroCuentaDisplay" style="background-color: #e9ecef;">Se generará al registrar</div>
                    </div>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                        <a href="listaCuentas.php?id=<?= $idCliente ?>" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function generarNumeroCuenta() {
            const prefijo = 2508;
            let sufijo = 0;
            
            for(let i = 0; i < 8; i++) {
                sufijo = (sufijo * 10) + Math.floor(Math.random() * 10);
            }
            
            const idCuenta = (prefijo * 100000000) + sufijo;
            
            // Mostrar el número generado
            document.getElementById('numeroCuentaDisplay').textContent = idCuenta;
            
            // Asignar el valor al campo oculto para enviarlo al servidor
            document.getElementById('idCuenta').value = idCuenta;
            
            // Para verificar en consola
            console.log("Número de cuenta generado:", idCuenta);
        }
    </script>
</body>
</html>