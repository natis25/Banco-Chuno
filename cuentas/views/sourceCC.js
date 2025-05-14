// Variable global para el ID del cliente (deberías obtenerlo de tu sistema de autenticación)
const ID_CLIENTE = 3; // Cambia esto según tu lógica

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