
```markdown
# Microservicio de Gestión de Préstamos

Microservicio para administrar préstamos bancarios, desarrollado en PHP vanilla y MySQL.

---

## 📋 Estructura de la API

### Endpoint Base
```
GET /prestamos
```

### Métodos Disponibles

| Método | Ruta                   | Descripción                     |
|--------|------------------------|---------------------------------|
| GET    | `/prestamos`           | Listar todos los préstamos      |
| GET    | `/prestamos/{id}`      | Obtener un préstamo por ID      |
| POST   | `/prestamos`           | Crear un nuevo préstamo         |
| PUT    | `/prestamos/{id}`      | Actualizar un préstamo existente|
| DELETE | `/prestamos/{id}`      | Eliminar un préstamo            |

---

## 🛠 Uso de los Endpoints

### 1. Listar todos los préstamos (GET)
```http
GET /prestamos
```
**Respuesta Exitosa (200):**
```json
[
    {
        "id": 1,
        "usuario_externo_id": 1,
        "monto_total": 150000.00,
        "fecha_inicio": "2023-06-01",
        "fecha_fin": "2029-06-01",
        "estado_id": 1
    }
]
```

---

### 2. Obtener un préstamo por ID (GET)
```http
GET /prestamos/1
```
**Respuesta Exitosa (200):**
```json
{
    "id": 1,
    "usuario_externo_id": 1,
    "tipo_prestamo_id": 1,
    "plazo_prestamo_id": 7,
    "tasa_interes_id": 1,
    "puntaje_crediticio_id": 2,
    "fecha_inicio": "2023-06-01",
    "fecha_fin": "2029-06-01",
    "estado_id": 1
}
```

---

### 3. Crear un préstamo (POST)
```http
POST /prestamos
```
**Cuerpo de la Petición:**
```json
{
    "usuario_externo_id": 100,
    "tipo_prestamo_id": 2,
    "monto_total": 30000.00,
    "plazo_prestamo_id": 1,
    "tasa_interes_id": 3,
    "puntaje_crediticio_id": 1,
    "fecha_inicio": "2024-01-01",
    "fecha_fin": "2024-08-01",
    "estado_id": 1
}
```

**Respuesta Exitosa (201):**
```json
{
    "id": 7,
    "mensaje": "Préstamo creado"
}
```

---

### 4. Actualizar un préstamo (PUT)
```http
PUT /prestamos/7
```
**Cuerpo de la Petición:**
```json
{
    "monto_total": 35000.00,
    "estado_id": 2
}
```

**Respuesta Exitosa (200):**
```json
{
    "filas_afectadas": 1
}
```

---

### 5. Eliminar un préstamo (DELETE)
```http
DELETE /prestamos/7
```
**Respuesta Exitosa (200):**
```json
{
    "filas_afectadas": 1
}
```

---

## 🗃 Estructura de la Base de Datos

### Tabla Clave: `prestamos`
| Campo                   | Tipo         | Descripción                                |
|-------------------------|--------------|--------------------------------------------|
| usuario_externo_id      | INT          | ID del usuario en el sistema externo       |
| tipo_prestamo_id        | INT          | Relación con tabla `tipos_prestamo`        |
| plazo_prestamo_id       | INT          | Relación con tabla `plazos_prestamos`      |
| tasa_interes_id         | INT          | Relación con tabla `tasas_interes`         |

### Otras Tablas Relevantes:
- `tasas_interes`: Tipos de tasas (Ej: Tasa Normal 10%).
- `tipos_periodo`: Plazos (Mensual, Trimestral...).
- `puntajes_crediticios`: Niveles crediticios (A, B, C...).

---

## ⚙ Configuración del Entorno

1. **Requisitos**:
   - PHP >= 7.4
   - MySQL >= 5.7

2. **Pasos Iniciales**:
```bash
# 1. Clonar repositorio
git clone [url_repositorio]

# 2. Importar base de datos
mysql -u usuario -p microservicio_prestamos < database.sql

# 3. Configurar conexión a BD
Editar config/database.php con tus credenciales
```

3. **Servidor Web**:
   - Configurar virtual host o usar servidor integrado:
```bash
php -S localhost:8000 -t public
```

---

## 🔍 Probando la API

### Ejemplo con Postman:
- **POST Request**:
  ```
  URL: http://localhost:8000/prestamos
  Headers: Content-Type: application/json
  Body (raw): 
  {
      "usuario_externo_id": 101,
      "tipo_prestamo_id": 3,
      "monto_total": 5000.00,
      "plazo_prestamo_id": 5,
      "tasa_interes_id": 2,
      "puntaje_crediticio_id": 3,
      "fecha_inicio": "2024-03-15",
      "fecha_fin": "2026-09-15",
      "estado_id": 1
  }
  ```

---

## 📌 Notas Importantes

- **`usuario_externo_id`**: Debe corresponder a un ID válido del microservicio de usuarios.
- **Códigos de Error Comunes**:
  - `400`: Datos de entrada inválidos.
  - `404`: Préstamo no encontrado.
  - `500`: Error interno del servidor.



**Equipo de Desarrollo**  
[Banco Chuno] - 2024


