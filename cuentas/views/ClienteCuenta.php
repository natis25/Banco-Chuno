<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Gestión de Saldo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .saldo-display {
            font-size: 3.5rem;
            font-weight: bold;
            text-align: center;
            margin: 2rem 0;
            color: #2c3e50;
        }
        .btn-operacion {
            font-size: 1.2rem;
            padding: 0.8rem 2rem;
            margin: 0 1rem;
        }
        .btn-depositar {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }
        .btn-retirar {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-footer {
            border-top: none;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">Mi Cuenta Bancaria</h1>
        
        <!-- Mostrar Saldo -->
        <div class="saldo-display">
            $<span id="saldo-actual">1,250.75</span>
        </div>
        
        <!-- Botones de Operación -->
        <div class="text-center mb-5">
            <button type="button" class="btn btn-primary btn-operacion btn-depositar" data-bs-toggle="modal" data-bs-target="#modalDepositar">
                <i class="bi bi-plus-circle"></i> Depositar
            </button>
            <button type="button" class="btn btn-primary btn-operacion btn-retirar" data-bs-toggle="modal" data-bs-target="#modalRetirar">
                <i class="bi bi-dash-circle"></i> Retirar
            </button>
        </div>
        
        <!-- Modal Depositar -->
        <div class="modal fade" id="modalDepositar" tabindex="-1" aria-labelledby="modalDepositarLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDepositarLabel">Realizar Depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formDepositar">
                            <div class="mb-3">
                                <label for="montoDeposito" class="form-label">Monto a depositar:</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0.01" class="form-control form-control-lg" id="montoDeposito" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" onclick="realizarOperacion('depositar')">Depositar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Retirar -->
        <div class="modal fade" id="modalRetirar" tabindex="-1" aria-labelledby="modalRetirarLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRetirarLabel">Realizar Retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formRetirar">
                            <div class="mb-3">
                                <label for="montoRetiro" class="form-label">Monto a retirar:</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0.01" class="form-control form-control-lg" id="montoRetiro" required>
                                </div>
                                <small class="text-muted">Saldo disponible: $<span id="saldo-disponible">1,250.75</span></small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" onclick="realizarOperacion('retirar')">Retirar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome para íconos -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <script>
        // Función para realizar operaciones (simulada)
        function realizarOperacion(tipo) {
            const montoInput = tipo === 'depositar' ? 'montoDeposito' : 'montoRetiro';
            const monto = parseFloat(document.getElementById(montoInput).value);
            
            if (isNaN(monto) || monto <= 0) {
                alert('Por favor ingrese un monto válido');
                return;
            }
            
            // Aquí iría la llamada AJAX al backend en una implementación real
            console.log(`Operación: ${tipo}, Monto: ${monto}`);
            
            // Simular éxito y actualizar saldo (en una implementación real esto vendría del backend)
            let saldoActual = parseFloat(document.getElementById('saldo-actual').textContent.replace(/,/g, ''));
            
            if (tipo === 'depositar') {
                saldoActual += monto;
            } else {
                if (monto > saldoActual) {
                    alert('Saldo insuficiente para realizar el retiro');
                    return;
                }
                saldoActual -= monto;
            }
            
            // Formatear y actualizar saldo
            document.getElementById('saldo-actual').textContent = saldoActual.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            document.getElementById('saldo-disponible').textContent = saldoActual.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            
            // Cerrar modal y limpiar formulario
            const modal = tipo === 'depositar' ? 'modalDepositar' : 'modalRetirar';
            bootstrap.Modal.getInstance(document.getElementById(modal)).hide();
            document.getElementById(`form${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`).reset();
            
            // Mostrar mensaje de éxito
            alert(`¡${tipo.charAt(0).toUpperCase() + tipo.slice(1)} realizado con éxito!`);
        }
    </script>
</body>
</html>