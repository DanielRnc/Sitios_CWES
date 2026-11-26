# EJEMPLOS DE USO - API REST Colmenas

Este archivo contiene ejemplos prácticos listos para copiar y ejecutar.

## 🔧 Configuración Base

```bash
# Variable para la URL base (ajustar según tu instalación)
BASE_URL="http://localhost/api-colmenas/public/api"
```

## 📖 EJEMPLOS CON cURL

### 1. GET - Obtener todas las colmenas

```bash
curl -X GET http://localhost/api-colmenas/public/api/colmenas
```

**Respuesta esperada:**
```json
{
    "success": true,
    "total": 12,
    "data": [
        {
            "id": 1,
            "zona": "Campo de Criptana",
            "usuario_id": 1,
            "usuario_nombre": "Juan García López",
            "username": "apicultor1",
            "fecha_creacion": "2024-12-11 15:00:00"
        },
        ...
    ]
}
```

### 2. GET - Obtener una colmena específica

```bash
curl -X GET http://localhost/api-colmenas/public/api/colmenas/1
```

**Respuesta esperada:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "zona": "Campo de Criptana",
        "usuario_id": 1,
        "usuario_nombre": "Juan García López",
        "fecha_creacion": "2024-12-11 15:00:00"
    }
}
```

### 3. POST - Crear nueva colmena

```bash
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{
    "zona": "Toledo",
    "usuario_id": 1
  }'
```

**Respuesta esperada:**
```json
{
    "success": true,
    "message": "Colmena creada correctamente",
    "data": {
        "id": 13,
        "zona": "Toledo",
        "usuario_id": 1
    }
}
```

### 4. POST - Crear con datos de diferentes zonas

```bash
# Colmena en Madrid
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{"zona": "Madrid", "usuario_id": 2}'

# Colmena en Barcelona
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{"zona": "Barcelona", "usuario_id": 3}'

# Colmena en Valencia
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{"zona": "Valencia", "usuario_id": 4}'
```

### 5. PUT - Actualizar colmena existente

```bash
curl -X PUT http://localhost/api-colmenas/public/api/colmenas/1 \
  -H "Content-Type: application/json" \
  -d '{
    "zona": "Guadalajara",
    "usuario_id": 2
  }'
```

**Respuesta esperada:**
```json
{
    "success": true,
    "message": "Colmena actualizada correctamente",
    "data": {
        "id": 1,
        "zona": "Guadalajara",
        "usuario_id": 2
    }
}
```

### 6. DELETE - Eliminar colmena

```bash
curl -X DELETE http://localhost/api-colmenas/public/api/colmenas/13
```

**Respuesta esperada:**
```json
{
    "success": true,
    "message": "Colmena eliminada correctamente"
}
```

## ❌ EJEMPLOS DE ERRORES (para aprender)

### Error 400 - Datos inválidos (zona vacía)

```bash
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{
    "zona": "",
    "usuario_id": 1
  }'
```

**Respuesta:**
```json
{
    "success": false,
    "message": "Error de validación",
    "errores": [
        "El campo 'zona' es obligatorio"
    ]
}
```

### Error 400 - usuario_id no numérico

```bash
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{
    "zona": "Toledo",
    "usuario_id": "texto"
  }'
```

**Respuesta:**
```json
{
    "success": false,
    "message": "Error de validación",
    "errores": [
        "El campo 'usuario_id' debe ser un número positivo"
    ]
}
```

### Error 400 - Zona demasiado larga

```bash
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{
    "zona": "Esta es una zona con un nombre extremadamente largo que excede los 100 caracteres permitidos y debería generar un error de validación correspondiente",
    "usuario_id": 1
  }'
```

### Error 404 - Colmena no encontrada

```bash
curl -X GET http://localhost/api-colmenas/public/api/colmenas/999999
```

**Respuesta:**
```json
{
    "success": false,
    "message": "Colmena no encontrada"
}
```

### Error 400 - JSON malformado

```bash
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{zona: Toledo, usuario_id: 1}'
```

## 📱 EJEMPLOS CON POSTMAN

### Configuración de Colección

1. Crear nueva colección: "API Colmenas"
2. Agregar variable de entorno:
   - Variable: `base_url`
   - Valor: `http://localhost/api-colmenas/public/api`

### Request 1: GET Todas las Colmenas

- **Method:** GET
- **URL:** `{{base_url}}/colmenas`
- **Headers:** (ninguno necesario)

### Request 2: GET Colmena por ID

- **Method:** GET
- **URL:** `{{base_url}}/colmenas/1`
- **Headers:** (ninguno necesario)

### Request 3: POST Crear Colmena

- **Method:** POST
- **URL:** `{{base_url}}/colmenas`
- **Headers:**
  - `Content-Type: application/json`
- **Body (raw JSON):**
```json
{
    "zona": "Sevilla",
    "usuario_id": 1
}
```

### Request 4: PUT Actualizar Colmena

- **Method:** PUT
- **URL:** `{{base_url}}/colmenas/1`
- **Headers:**
  - `Content-Type: application/json`
- **Body (raw JSON):**
```json
{
    "zona": "Córdoba",
    "usuario_id": 3
}
```

### Request 5: DELETE Eliminar Colmena

- **Method:** DELETE
- **URL:** `{{base_url}}/colmenas/1`
- **Headers:** (ninguno necesario)

## 🌐 EJEMPLOS CON JAVASCRIPT (Fetch API)

### GET - Obtener todas las colmenas

```javascript
fetch('http://localhost/api-colmenas/public/api/colmenas')
  .then(response => response.json())
  .then(data => {
    console.log('Total colmenas:', data.total);
    console.log('Colmenas:', data.data);
  })
  .catch(error => console.error('Error:', error));
```

### POST - Crear nueva colmena

```javascript
const nuevaColmena = {
  zona: "Granada",
  usuario_id: 2
};

fetch('http://localhost/api-colmenas/public/api/colmenas', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(nuevaColmena)
})
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Colmena creada con ID:', data.data.id);
    } else {
      console.error('Error:', data.message);
    }
  })
  .catch(error => console.error('Error:', error));
```

### PUT - Actualizar colmena

```javascript
const datosActualizados = {
  zona: "Málaga",
  usuario_id: 3
};

fetch('http://localhost/api-colmenas/public/api/colmenas/1', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(datosActualizados)
})
  .then(response => response.json())
  .then(data => {
    console.log('Actualización:', data.message);
  })
  .catch(error => console.error('Error:', error));
```

### DELETE - Eliminar colmena

```javascript
fetch('http://localhost/api-colmenas/public/api/colmenas/1', {
  method: 'DELETE'
})
  .then(response => response.json())
  .then(data => {
    console.log('Eliminación:', data.message);
  })
  .catch(error => console.error('Error:', error));
```

## 🐍 EJEMPLOS CON PYTHON (requests)

### Instalación de requests

```bash
pip install requests
```

### GET - Obtener todas las colmenas

```python
import requests

url = "http://localhost/api-colmenas/public/api/colmenas"
response = requests.get(url)

if response.status_code == 200:
    data = response.json()
    print(f"Total colmenas: {data['total']}")
    for colmena in data['data']:
        print(f"ID: {colmena['id']}, Zona: {colmena['zona']}")
```

### POST - Crear nueva colmena

```python
import requests
import json

url = "http://localhost/api-colmenas/public/api/colmenas"
nueva_colmena = {
    "zona": "Bilbao",
    "usuario_id": 2
}

headers = {'Content-Type': 'application/json'}
response = requests.post(url, data=json.dumps(nueva_colmena), headers=headers)

if response.status_code == 201:
    data = response.json()
    print(f"Colmena creada con ID: {data['data']['id']}")
else:
    print(f"Error: {response.json()['message']}")
```

### PUT - Actualizar colmena

```python
import requests
import json

url = "http://localhost/api-colmenas/public/api/colmenas/1"
datos_actualizados = {
    "zona": "Zaragoza",
    "usuario_id": 3
}

headers = {'Content-Type': 'application/json'}
response = requests.put(url, data=json.dumps(datos_actualizados), headers=headers)

if response.status_code == 200:
    print("Colmena actualizada correctamente")
```

### DELETE - Eliminar colmena

```python
import requests

url = "http://localhost/api-colmenas/public/api/colmenas/1"
response = requests.delete(url)

if response.status_code == 200:
    print("Colmena eliminada correctamente")
```

## 🧪 SCRIPT DE PRUEBA COMPLETO (Bash)

```bash
#!/bin/bash

# Script de prueba completa de la API
BASE_URL="http://localhost/api-colmenas/public/api/colmenas"

echo "========================================="
echo "PRUEBA COMPLETA DE API REST - COLMENAS"
echo "========================================="
echo ""

# 1. GET - Listar todas
echo "1. GET - Obtener todas las colmenas"
curl -s -X GET $BASE_URL | jq '.'
echo ""
read -p "Presiona Enter para continuar..."

# 2. POST - Crear nueva
echo "2. POST - Crear nueva colmena"
RESPONSE=$(curl -s -X POST $BASE_URL \
  -H "Content-Type: application/json" \
  -d '{"zona":"Test API","usuario_id":1}')
echo $RESPONSE | jq '.'
NEW_ID=$(echo $RESPONSE | jq -r '.data.id')
echo "Nueva colmena creada con ID: $NEW_ID"
echo ""
read -p "Presiona Enter para continuar..."

# 3. GET - Obtener la recién creada
echo "3. GET - Obtener colmena ID $NEW_ID"
curl -s -X GET $BASE_URL/$NEW_ID | jq '.'
echo ""
read -p "Presiona Enter para continuar..."

# 4. PUT - Actualizar
echo "4. PUT - Actualizar colmena ID $NEW_ID"
curl -s -X PUT $BASE_URL/$NEW_ID \
  -H "Content-Type: application/json" \
  -d '{"zona":"Test API Actualizado","usuario_id":2}' | jq '.'
echo ""
read -p "Presiona Enter para continuar..."

# 5. DELETE - Eliminar
echo "5. DELETE - Eliminar colmena ID $NEW_ID"
curl -s -X DELETE $BASE_URL/$NEW_ID | jq '.'
echo ""

# 6. Verificar eliminación
echo "6. Verificar eliminación (debe dar error 404)"
curl -s -X GET $BASE_URL/$NEW_ID | jq '.'
echo ""

echo "========================================="
echo "PRUEBA COMPLETADA"
echo "========================================="
```

**Nota:** Este script requiere `jq` instalado para formatear JSON.

## 📊 CASOS DE USO REALES

### Caso 1: Aplicación Web que Consume la API

```javascript
// app.js - Aplicación frontend

class ColmenasApp {
  constructor() {
    this.apiUrl = 'http://localhost/api-colmenas/public/api/colmenas';
  }

  async cargarColmenas() {
    try {
      const response = await fetch(this.apiUrl);
      const data = await response.json();
      
      if (data.success) {
        this.mostrarColmenas(data.data);
      }
    } catch (error) {
      console.error('Error al cargar colmenas:', error);
    }
  }

  async crearColmena(zona, usuarioId) {
    try {
      const response = await fetch(this.apiUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ zona, usuario_id: usuarioId })
      });
      
      const data = await response.json();
      
      if (data.success) {
        alert('Colmena creada correctamente');
        this.cargarColmenas(); // Recargar lista
      } else {
        alert('Error: ' + data.message);
      }
    } catch (error) {
      console.error('Error al crear colmena:', error);
    }
  }

  mostrarColmenas(colmenas) {
    const container = document.getElementById('colmenas-list');
    container.innerHTML = '';
    
    colmenas.forEach(colmena => {
      const div = document.createElement('div');
      div.className = 'colmena-item';
      div.innerHTML = `
        <h3>Colmena #${colmena.id}</h3>
        <p>Zona: ${colmena.zona}</p>
        <p>Propietario: ${colmena.usuario_nombre}</p>
        <button onclick="app.eliminarColmena(${colmena.id})">Eliminar</button>
      `;
      container.appendChild(div);
    });
  }

  async eliminarColmena(id) {
    if (!confirm('¿Eliminar esta colmena?')) return;
    
    try {
      const response = await fetch(`${this.apiUrl}/${id}`, {
        method: 'DELETE'
      });
      
      const data = await response.json();
      
      if (data.success) {
        alert('Colmena eliminada');
        this.cargarColmenas();
      }
    } catch (error) {
      console.error('Error al eliminar:', error);
    }
  }
}

// Inicializar app
const app = new ColmenasApp();
app.cargarColmenas();
```

### Caso 2: Sistema de Monitoreo de Sensores

```python
# monitor_sensores.py
# Script que periódicamente consulta la API para verificar colmenas

import requests
import time

API_URL = "http://localhost/api-colmenas/public/api/colmenas"

def verificar_colmenas():
    response = requests.get(API_URL)
    
    if response.status_code == 200:
        data = response.json()
        print(f"Total colmenas monitoreadas: {data['total']}")
        
        for colmena in data['data']:
            print(f"  - Colmena #{colmena['id']} en {colmena['zona']}")
            # Aquí se podría verificar sensores, temperatura, etc.
    else:
        print("Error al consultar API")

# Monitoreo cada 5 minutos
while True:
    verificar_colmenas()
    time.sleep(300)  # 5 minutos
```

## 💡 TIPS Y TRUCOS

### Ver respuestas formateadas con jq

```bash
# Instalar jq (Linux/Mac)
sudo apt-get install jq  # Ubuntu/Debian
brew install jq          # Mac

# Uso
curl -X GET http://localhost/api-colmenas/public/api/colmenas | jq '.'
```

### Guardar respuesta en archivo

```bash
curl -X GET http://localhost/api-colmenas/public/api/colmenas > colmenas.json
```

### Ver headers de respuesta

```bash
curl -i -X GET http://localhost/api-colmenas/public/api/colmenas
```

### Medir tiempo de respuesta

```bash
curl -w "\nTiempo total: %{time_total}s\n" \
  -X GET http://localhost/api-colmenas/public/api/colmenas
```

---

**¡Prueba todos estos ejemplos para dominar la API! 🐝**
