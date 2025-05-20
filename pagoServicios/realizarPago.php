<?php

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'pago');
    if ($conn->connect_error) {
        die('Error de conexión: ' . $conn->connect_error);
    }

    // Obtener y sanitizar los datos del formulario
    $idCuenta = $conn->real_escape_string($_POST['idCuenta']);
    $idTipoServicio = intval($_POST['idTipoServicio']);
    $monto = intval($_POST['monto']);

    // Validación: monto debe ser positivo
    if ($monto <= 0) {
        echo "<p style='color:red;'>El monto debe ser un valor positivo.</p>";
    } else {
        // Validación: la fecha siempre será la actual
        $fechaPago = date('Y-m-d');

        // Insertar el pago
        $sql = "INSERT INTO pago (idCuenta, idTipoServicio, monto, fechaPago)
                VALUES ('$idCuenta', $idTipoServicio, $monto, '$fechaPago')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Pago realizado con éxito.</p>";
        } else {
            echo "<p>Error al realizar el pago: " . $conn->error . "</p>";
        }
    }
    $conn->close();
}

// Obtener los tipos de servicio para el select
$conn = new mysqli('localhost', 'root', '', 'pago');
$servicios = [];
if (!$conn->connect_error) {
    $result = $conn->query("SELECT idTipoServicio, nombreServicio FROM tiposervicio");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }
        $result->free();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Realizar Pago</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playwrite+DK+Loopet:wght@100..400&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script>
        // Validación en el cliente: solo valores positivos y fecha actual
        document.addEventListener('DOMContentLoaded', function () {
            const montoInput = document.getElementById('monto');
            montoInput.setAttribute('min', '1');

            // Fecha actual para el campo de fecha (solo visual, no se enviará)
            const fechaInput = document.getElementById('fechaPago');
            const today = new Date().toISOString().split('T')[0];
            fechaInput.value = today;
            fechaInput.readOnly = true;
        });
    </script>
</head>

<body class="bodyIS">
    <div class="container">
        <div class="header">
            <h1 class="titulo">Formulario de Pago de Servicios</h1>
        </div>
        <form method="post" action="">
            <label for="idCuenta">Numero de Cuenta:</label>
            <input class="formulario" type="text" name="idCuenta" id="idCuenta" required><br><br>

            <label for="idTipoServicio">Tipo de Servicio a Pagar:</label>
            <select class="formulario" name="idTipoServicio" id="idTipoServicio" required>
                <option style="color: black;" value="">Seleccione un servicio</option>
                <?php foreach ($servicios as $servicio): ?>
                    <option style="color: black;" value="<?php echo $servicio['idTipoServicio']; ?>">
                        <?php echo htmlspecialchars($servicio['nombreServicio']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="monto">Monto:</label>
            <input class="formulario" type="number" name="monto" id="monto" min="1" required><br><br>

            <label for="fechaPago">Fecha del Pago:</label>
            <input class="formulario" type="date" name="fechaPago" id="fechaPago" required readonly><br><br>

            <button class="btn-IS" type="submit" value="Realizar Pago">Realizar Pago</button>
        </form>
    </div>
</body>

</html>