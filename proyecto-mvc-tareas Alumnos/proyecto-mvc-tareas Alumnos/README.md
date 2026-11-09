# 📋 Sistema de Gestión de Tareas - Patrón MVC

Proyecto didáctico para enseñar el patrón Modelo-Vista-Controlador (MVC) en PHP con Programación Orientada a Objetos.

## 🎯 Características

- ✅ Arquitectura MVC clara y bien estructurada
- ✅ Programación Orientada a Objetos (POO)
- ✅ Sistema de autenticación (Login/Register)
- ✅ Gestión de sesiones PHP
- ✅ CRUD completo de tareas
- ✅ Enrutador simple
- ✅ Seguridad: PDO, password_hash, htmlspecialchars
- ✅ Diseño responsive con CSS básico

## 📁 Estructura del Proyecto

```
proyecto-mvc-tareas/
│
├── index.php                    # Punto de entrada y enrutador
├── database.sql                 # Script SQL
├── GUIA_MVC.md                  # Documentación completa
│
├── config/
│   └── database.php             # Configuración de BD
│
├── app/
│   ├── models/                  # Modelos (Usuario, Tarea)
│   ├── controllers/             # Controladores
│   └── views/                   # Vistas HTML
│
└── public/
    └── css/
        └── style.css            # Estilos CSS
```

## 🚀 Instalación Rápida

### 1. Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx) o PHP integrado

### 2. Crear Base de Datos

```bash
mysql -u root -p < database.sql
```

O importar manualmente desde phpMyAdmin.

### 3. Configurar Conexión

Editar `config/database.php`:

```php
private $host = "localhost";
private $db_name = "tareas_db";
private $username = "root";        // Tu usuario
private $password = "";            // Tu contraseña
```

### 4. Iniciar Servidor

**Opción A - Servidor PHP integrado:**
```bash
cd proyecto-mvc-tareas
php -S localhost:8000
```

**Opción B - Apache/Nginx:**
Mover el proyecto a `htdocs` o `www` y acceder desde:
```
http://localhost/proyecto-mvc-tareas/
```

### 5. Acceder a la Aplicación

Abrir en el navegador:
```
http://localhost:8000
```

## 👤 Usuarios de Prueba

El script SQL incluye usuarios de ejemplo:

| Email | Contraseña |
|-------|------------|
| juan@ejemplo.com | 123456 |
| maria@ejemplo.com | 123456 |

## 📚 Documentación

Lee la **GUIA_MVC.md** para entender:
- Qué es el patrón MVC
- Cómo funciona la POO en PHP
- Flujo completo de peticiones
- Gestión de sesiones
- Seguridad implementada
- Ejercicios propuestos

## 🎓 Uso Didáctico

Este proyecto está diseñado para:

1. **Profesores**: Como material de clase para explicar MVC
2. **Alumnos**: Como base para entender arquitectura web
3. **Prácticas**: Código comentado y bien estructurado

### Conceptos que se aprenden:
- ✅ Patrón de diseño MVC
- ✅ Programación Orientada a Objetos
- ✅ Enrutamiento (Front Controller)
- ✅ Operaciones CRUD
- ✅ Autenticación y sesiones
- ✅ Seguridad web básica
- ✅ PDO y MySQL

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+ (POO, PDO, Sessions)
- **Base de datos**: MySQL
- **Frontend**: HTML5, CSS3
- **Patrón**: MVC (Modelo-Vista-Controlador)

## 📖 Rutas Disponibles

### Públicas:
- `index.php?action=home` - Página principal
- `index.php?action=login` - Iniciar sesión
- `index.php?action=register` - Registrarse

### Privadas (requieren login):
- `index.php?action=misTareas` - Mis tareas
- `index.php?action=crearTarea` - Crear tarea
- `index.php?action=editarTarea&id=X` - Editar tarea
- `index.php?action=eliminarTarea&id=X` - Eliminar tarea
- `index.php?action=logout` - Cerrar sesión

## 🔒 Seguridad Implementada

- ✅ Consultas preparadas (PDO) - Previene SQL Injection
- ✅ `password_hash()` y `password_verify()` - Contraseñas seguras
- ✅ `htmlspecialchars()` - Previene XSS
- ✅ Verificación de sesiones - Control de acceso
- ✅ Sanitización de datos - `strip_tags()`

## 🎨 Funcionalidades

### Sistema de Autenticación
- Registro de nuevos usuarios
- Login con validación
- Gestión de sesiones
- Logout

### Gestión de Tareas
- Listar todas las tareas (vista pública)
- Listar mis tareas (vista privada)
- Crear nueva tarea
- Editar tarea existente
- Eliminar tarea
- Filtro por fase (Análisis, Diseño, Codificación, Pruebas, Despliegue)
- Estado de tareas (Pendiente, En Progreso, Completada)

## 📝 Ejercicios Propuestos

El archivo GUIA_MVC.md incluye 5 ejercicios propuestos para que los alumnos practiquen:

1. Añadir campo de prioridad
2. Filtrar tareas por estado
3. Crear perfil de usuario
4. Implementar búsqueda
5. Mostrar estadísticas

## 🤝 Contribuciones

Este es un proyecto educativo. Siéntete libre de:
- Usarlo en tus clases
- Modificarlo según tus necesidades
- Compartirlo con otros profesores

## 📄 Licencia

Uso educativo libre. Puedes usar, modificar y distribuir este proyecto con fines educativos.

## 📧 Contacto

Proyecto creado con fines didácticos para el módulo de Desarrollo de Aplicaciones Web - Formación Profesional.

---

**⭐ Si este proyecto te resulta útil para tus clases, no dudes en adaptarlo a tus necesidades específicas.**
