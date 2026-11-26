-- ===================================
-- SCRIPT SQL PARA CREAR LA BASE DE DATOS
-- Sistema de Gestión de Tareas - Patrón MVC
-- ===================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS tareas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE tareas_db;

-- ===================================
-- TABLA: usuarios
-- ===================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- TABLA: tareas
-- ===================================
CREATE TABLE IF NOT EXISTS tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fase ENUM('Análisis', 'Diseño', 'Codificación', 'Pruebas', 'Despliegue') NOT NULL,
    estado ENUM('Pendiente', 'En Progreso', 'Completada') NOT NULL DEFAULT 'Pendiente',
    usuario_id INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_fase (fase),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- DATOS DE EJEMPLO (OPCIONAL)
-- ===================================

-- Insertar usuarios de ejemplo
-- NOTA: Las contraseñas están hasheadas con password_hash()
-- Usuario 1: email: juan@ejemplo.com, password: 123456
INSERT INTO usuarios (nombre, email, password) VALUES 
('Juan Pérez', 'juan@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Usuario 2: email: maria@ejemplo.com, password: 123456
INSERT INTO usuarios (nombre, email, password) VALUES 
('María García', 'maria@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insertar tareas de ejemplo
INSERT INTO tareas (titulo, descripcion, fase, estado, usuario_id) VALUES 
('Definir requisitos del sistema', 'Reunir y documentar todos los requisitos funcionales y no funcionales', 'Análisis', 'Completada', 1),
('Crear diagrama de casos de uso', 'Diseñar los casos de uso principales del sistema', 'Análisis', 'Completada', 1),
('Diseñar base de datos', 'Crear el modelo entidad-relación y el esquema de la base de datos', 'Diseño', 'Completada', 2),
('Diseñar arquitectura MVC', 'Definir la estructura de carpetas y componentes del patrón MVC', 'Diseño', 'Completada', 2),
('Implementar modelos de datos', 'Crear las clases PHP para Usuario y Tarea', 'Codificación', 'En Progreso', 1),
('Desarrollar controladores', 'Implementar AuthController y TareaController', 'Codificación', 'En Progreso', 1),
('Crear vistas HTML', 'Diseñar las interfaces de usuario con HTML y CSS', 'Codificación', 'Pendiente', 2),
('Pruebas de autenticación', 'Verificar el correcto funcionamiento del login y registro', 'Pruebas', 'Pendiente', 2),
('Pruebas CRUD de tareas', 'Probar todas las operaciones sobre tareas', 'Pruebas', 'Pendiente', 1),
('Configurar servidor de producción', 'Preparar el entorno de producción (Apache, PHP, MySQL)', 'Despliegue', 'Pendiente', 2);

-- ===================================
-- CONSULTAS ÚTILES PARA VERIFICAR
-- ===================================

-- Ver todos los usuarios
-- SELECT * FROM usuarios;

-- Ver todas las tareas con información del usuario
-- SELECT t.*, u.nombre as usuario_nombre 
-- FROM tareas t 
-- LEFT JOIN usuarios u ON t.usuario_id = u.id;

-- Contar tareas por fase
-- SELECT fase, COUNT(*) as total 
-- FROM tareas 
-- GROUP BY fase;

-- Contar tareas por estado
-- SELECT estado, COUNT(*) as total 
-- FROM tareas 
-- GROUP BY estado;
