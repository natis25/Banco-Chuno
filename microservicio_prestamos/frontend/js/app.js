// Configuración global
const API_BASE_URL = 'http://localhost/Banco-Chuno/microservicio_prestamos/public';
const API_PRESTAMOS = `${API_BASE_URL}/prestamos`;

// Mapeo de tipos y estados (podrían obtenerse de la API)
const tiposPrestamo = {
    1: 'Hipotecario',
    2: 'Vehicular',
    3: 'Consumo',
    4: 'Empresarial',
    5: 'Educativo'
};

const estadosPrestamo = {
    1: 'Activo',
    2: 'Cancelado',
    3: 'Pagado'
};

// Funciones comunes
function mostrarMensaje(elementoId, texto, tipo = 'success') {
    const mensaje = document.getElementById(elementoId);
    mensaje.textContent = texto;
    mensaje.style.display = 'block';
    mensaje.className = `alert alert-${tipo}`;
    
    if (tipo === 'success') {
        setTimeout(() => {
            mensaje.style.display = 'none';
        }, 3000);
    }
}

function formatearFecha(fechaString) {
    if (!fechaString) return 'N/A';
    const opciones = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(fechaString).toLocaleDateString('es-ES', opciones);
}

function formatearMoneda(monto) {
    return new Intl.NumberFormat('es-MX', { 
        style: 'currency', 
        currency: 'MXN' 
    }).format(monto);
}

// Funciones para lista-prestamos.html
async function cargarPrestamos() {
    try {
        mostrarCargando('cuerpo-tabla', 'Cargando préstamos...');
        
        const response = await fetch(API_PRESTAMOS);
        
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        
        const prestamos = await response.json();
        mostrarPrestamos(prestamos);
    } catch (error) {
        mostrarMensaje('mensaje', error.message, 'error');
        mostrarError('cuerpo-tabla', error.message);
    }
}

function mostrarPrestamos(prestamos) {
    const cuerpoTabla = document.getElementById('cuerpo-tabla');
    cuerpoTabla.innerHTML = '';
    
    if (prestamos.length === 0) {
        cuerpoTabla.innerHTML = '<tr><td colspan="7">No hay préstamos registrados</td></tr>';
        return;
    }
    
    prestamos.forEach(prestamo => {
        const fila = document.createElement('tr');
        
        fila.innerHTML = `
            <td>${prestamo.id}</td>
            <td>${prestamo.usuario_externo_id}</td>
            <td>${formatearMoneda(prestamo.monto_total)}</td>
            <td>${tiposPrestamo[prestamo.tipo_prestamo_id] || 'Desconocido'}</td>
            <td>${formatearFecha(prestamo.fecha_inicio)}</td>
            <td>${estadosPrestamo[prestamo.estado_id] || 'Desconocido'}</td>
            <td>
                <a href="editar-prestamo.html?id=${prestamo.id}" class="btn btn-primary">Editar</a>
                <button onclick="eliminarPrestamo(${prestamo.id})" class="btn btn-danger">Eliminar</button>
            </td>
        `;
        
        cuerpoTabla.appendChild(fila);
    });
}

async function eliminarPrestamo(id) {
    if (!confirm('¿Está seguro de eliminar este préstamo?')) return;
    
    try {
        const response = await fetch(`${API_PRESTAMOS}/${id}`, {
            method: 'DELETE'
        });
        
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        
        mostrarMensaje('mensaje', 'Préstamo eliminado correctamente', 'success');
        cargarPrestamos();
    } catch (error) {
        mostrarMensaje('mensaje', error.message, 'error');
    }
}

// Funciones para agregar-prestamo.html
function configurarFormularioAgregar() {
    cargarSelectores();
    configurarFechas();
    
    document.getElementById('formulario-prestamo').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = 'Guardando <span class="loading"></span>';
        
        try {
            const nuevoPrestamo = {
                usuario_externo_id: parseInt(document.getElementById('usuario_id').value),
                tipo_prestamo_id: parseInt(document.getElementById('tipo_prestamo').value),
                monto_total: parseFloat(document.getElementById('monto').value),
                plazo_prestamo_id: 1, // Valor por defecto (ajustar según necesidad)
                tasa_interes_id: 1,   // Valor por defecto (ajustar según necesidad)
                puntaje_crediticio_id: 1, // Valor por defecto (ajustar según necesidad)
                fecha_inicio: document.getElementById('fecha_inicio').value,
                fecha_fin: document.getElementById('fecha_fin').value,
                estado_id: parseInt(document.getElementById('estado').value)
            };
            
            // Validación adicional
            if (nuevoPrestamo.fecha_fin <= nuevoPrestamo.fecha_inicio) {
                throw new Error('La fecha de fin debe ser posterior a la fecha de inicio');
            }
            
            const response = await fetch(API_PRESTAMOS, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(nuevoPrestamo)
            });
            
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            mostrarMensaje('mensaje', `Préstamo creado con ID: ${data.id}`, 'success');
            document.getElementById('formulario-prestamo').reset();
            
            setTimeout(() => {
                window.location.href = 'lista-prestamos.html';
            }, 2000);
        } catch (error) {
            mostrarMensaje('mensaje', error.message, 'error');
        } finally {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = 'Guardar Préstamo';
        }
    });
}

// Funciones para editar-prestamo.html
async function cargarPrestamoParaEditar() {
    const prestamoId = new URLSearchParams(window.location.search).get('id');
    
    if (!prestamoId) {
        mostrarMensaje('mensaje', 'No se especificó un préstamo para editar', 'error');
        return;
    }
    
    try {
        mostrarCargando('formulario-prestamo', 'Cargando datos del préstamo...');
        
        const response = await fetch(`${API_PRESTAMOS}/${prestamoId}`);
        
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        
        const prestamo = await response.json();
        mostrarDatosPrestamo(prestamo);
        
        document.getElementById('formulario-prestamo').addEventListener('submit', async (e) => {
            e.preventDefault();
            await actualizarPrestamo(prestamoId);
        });
    } catch (error) {
        mostrarMensaje('mensaje', error.message, 'error');
    }
}

async function actualizarPrestamo(id) {
    const btnSubmit = document.querySelector('button[type="submit"]');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = 'Actualizando <span class="loading"></span>';
    
    try {
        const datosActualizados = {
            monto_total: parseFloat(document.getElementById('monto').value),
            fecha_fin: document.getElementById('fecha_fin').value,
            estado_id: parseInt(document.getElementById('estado').value)
        };
        
        const response = await fetch(`${API_PRESTAMOS}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datosActualizados)
        });
        
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        
        mostrarMensaje('mensaje', 'Préstamo actualizado correctamente', 'success');
        
        setTimeout(() => {
            window.location.href = 'lista-prestamos.html';
        }, 2000);
    } catch (error) {
        mostrarMensaje('mensaje', error.message, 'error');
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = 'Actualizar Préstamo';
    }
}

// Funciones auxiliares
function cargarSelectores() {
    const tipoSelect = document.getElementById('tipo_prestamo');
    const estadoSelect = document.getElementById('estado');
    
    if (tipoSelect) {
        tipoSelect.innerHTML = '<option value="">Seleccione un tipo</option>';
        Object.entries(tiposPrestamo).forEach(([id, nombre]) => {
            tipoSelect.innerHTML += `<option value="${id}">${nombre}</option>`;
        });
    }
    
    if (estadoSelect) {
        estadoSelect.innerHTML = '<option value="">Seleccione un estado</option>';
        Object.entries(estadosPrestamo).forEach(([id, nombre]) => {
            estadoSelect.innerHTML += `<option value="${id}">${nombre}</option>`;
        });
    }
}

function configurarFechas() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    
    if (fechaInicio) {
        // Establecer fecha mínima (hoy)
        const hoy = new Date().toISOString().split('T')[0];
        fechaInicio.min = hoy;
        fechaInicio.value = hoy;
        
        // Validar que fecha fin sea posterior a fecha inicio
        fechaInicio.addEventListener('change', () => {
            fechaFin.min = fechaInicio.value;
        });
    }
    
    if (fechaFin) {
        // Establecer fecha mínima (mañana por defecto)
        const manana = new Date();
        manana.setDate(manana.getDate() + 1);
        fechaFin.min = manana.toISOString().split('T')[0];
        fechaFin.value = manana.toISOString().split('T')[0];
    }
}

function mostrarDatosPrestamo(prestamo) {
    document.getElementById('prestamo_id').value = prestamo.id;
    document.getElementById('usuario_id').value = prestamo.usuario_externo_id;
    document.getElementById('monto').value = prestamo.monto_total;
    document.getElementById('fecha_inicio').value = prestamo.fecha_inicio;
    document.getElementById('fecha_fin').value = prestamo.fecha_fin;
    document.getElementById('tipo_prestamo').value = prestamo.tipo_prestamo_id;
    document.getElementById('estado').value = prestamo.estado_id;
}

function mostrarCargando(elementoId, mensaje = 'Cargando...') {
    const elemento = document.getElementById(elementoId);
    if (elemento) {
        elemento.innerHTML = `<div style="text-align: center; padding: 20px;">${mensaje}</div>`;
    }
}

function mostrarError(elementoId, mensaje) {
    const elemento = document.getElementById(elementoId);
    if (elemento) {
        elemento.innerHTML = `<div style="color: #e74c3c; text-align: center; padding: 20px;">${mensaje}</div>`;
    }
}

// Inicialización según la página
if (document.getElementById('cuerpo-tabla')) {
    // Página de lista de préstamos
    document.addEventListener('DOMContentLoaded', cargarPrestamos);
} else if (document.getElementById('formulario-prestamo') && !window.location.search.includes('id')) {
    // Página de agregar préstamo
    document.addEventListener('DOMContentLoaded', configurarFormularioAgregar);
} else if (document.getElementById('formulario-prestamo') && window.location.search.includes('id')) {
    // Página de editar préstamo
    document.addEventListener('DOMContentLoaded', cargarPrestamoParaEditar);
}