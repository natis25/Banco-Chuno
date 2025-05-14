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
// Variable global para el ID del cliente (deberías obtenerlo de tu sistema de autenticación)
const ID_CLIENTE = 1; // Cambia esto según tu lógica

// Función para formatear números con separadores de miles
function formatearSaldo(monto) {
    return parseFloat(monto).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Cargar saldo inicial desde el backend
async function cargarSaldo() {
    try {
        const response = await fetch(`http://localhost/hackaton/Banco-Chuno/cuentas/backend/api/account_endpoint.php?action=obtener_saldo&id_cliente=${ ID_CLIENTE }`);
        
        if (!response.ok) {
            throw new Error('Error al obtener el saldo');
        }
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('saldo-actual').textContent = formatearSaldo(data.saldo);
            document.getElementById('saldo-disponible').textContent = formatearSaldo(data.saldo);
        } else {
            throw new Error(data.error || 'Error desconocido');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar el saldo: ' + error.message);
    }
}

// Función para realizar operaciones (conectada al backend)
async function realizarOperacion(tipo) {
    const montoInput = tipo === 'depositar' ? 'montoDeposito' : 'montoRetiro';
    const monto = parseFloat(document.getElementById(montoInput).value);
    
    if (isNaN(monto) || monto <= 0) {
        alert('Por favor ingrese un monto válido');
        return;
    }

    try {
        const response = await fetch('http://localhost/hackaton/Banco-Chuno/cuentas/backend/api/account_endpoint.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_cliente: ID_CLIENTE,
                operacion: tipo,
                monto: monto
            })
        });

        const resultado = await response.json();

        if (!resultado.success) {
            throw new Error(resultado.error || "Error en la operación");
        }

        // Actualizar saldo
        document.getElementById('saldo-actual').textContent = formatearSaldo(resultado.nuevo_saldo);
        document.getElementById('saldo-disponible').textContent = formatearSaldo(resultado.nuevo_saldo);

        // Cerrar modal y limpiar formulario
        const modalId = tipo === 'depositar' ? 'modalDepositar' : 'modalRetirar';
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        modal.hide();
        document.getElementById(`form${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`).reset();

        alert(`¡${tipo.charAt(0).toUpperCase() + tipo.slice(1)} realizado con éxito!`);

    } catch (error) {
        console.error("Error:", error);
        alert(`Error: ${error.message}`);
    }
}

// Cargar saldo al iniciar la página
document.addEventListener('DOMContentLoaded', cargarSaldo);
</script>
</body>
</html>