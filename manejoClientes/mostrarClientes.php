<?php
require_once 'obtenerClientes.php';
?>
<?php include '../navbar/navbarEmpleado.php'; ?>
<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playwrite+DK+Loopet:wght@100..400&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <br>
    <br>
    <br>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Lista de Clientes</h1>
        <div class="tabla-container">
            <h2>Clientes Registrados</h2>

            <?php if (empty($clientes)): ?>
                <div class="alert alert-warning">No hay clientes registrados</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Celular</th>
                                <th>Dirección</th>
                                <th>Fecha Registro</th>
                                <th>Cuentas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?= $cliente['id_cliente'] ?></td>
                                    <td><?= htmlspecialchars($cliente['nombre_cliente']) ?></td>
                                    <td><?= htmlspecialchars($cliente['correo']) ?></td>
                                    <td><?= $cliente['celular'] ?></td>
                                    <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                                    <td><?= $cliente['fecha_registro'] ?></td>
                                    <td>
                                        <a href="../manejoCuentas/listaCuentas.php?id=<?= $cliente['id_cliente'] ?>" class="btn btn-info btn-sm">
                                            Ver
                                        </a>
                                        <a href="../manejoCuentas/nuevaCuenta.php?id=<?= $cliente['id_cliente'] ?>" class="btn btn-success btn-sm">
                                            Nueva
                                        </a>
                                    </td>
                                    <td>
                                        <a href="eliminarCliente.php?id=<?= $cliente['id_cliente'] ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>