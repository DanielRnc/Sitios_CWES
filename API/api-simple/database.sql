-- Script SQL para la API REST de Colmenas
-- Base de datos para API REST - Sistema de Sensores de Colmenas
-- Proyecto didáctico - Desarrollo de Aplicaciones Web

DROP DATABASE IF EXISTS api_colmenas;
CREATE DATABASE api_colmenas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE api_colmenas;

-- Tabla de usuarios (apicultores)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de colmenas
CREATE TABLE colmenas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zona VARCHAR(100) NOT NULL,
    usuario_id INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_zona (zona),
    INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuarios de prueba (apicultores)
INSERT INTO usuarios (username, nombre_completo, email) VALUES
('apicultor1', 'Juan García López', 'juan.garcia@apisiberica.com'),
('apicultor2', 'María López Martínez', 'maria.lopez@apisiberica.com'),
('apicultor3', 'Pedro Sánchez Ruiz', 'pedro.sanchez@apisiberica.com'),
('apicultor4', 'Ana Fernández Gil', 'ana.fernandez@apisiberica.com'),
('apicultor5', 'Carlos Martín Torres', 'carlos.martin@apisiberica.com');

-- Insertar colmenas de prueba
INSERT INTO colmenas (zona, usuario_id) VALUES
('Campo de Criptana', 1),
('Campo de Criptana', 1),
('Alcázar de San Juan', 2),
('Tomelloso', 2),
('Socuéllamos', 3),
('Manzanares', 3),
('Valdepeñas', 4),
('Daimiel', 4),
('Herencia', 5),
('La Solana', 5),
('Argamasilla de Alba', 1),
('Pedro Muñoz', 2);

-- Información de conexión
-- Host: localhost
-- Database: api_colmenas
-- Usuario: root (o tu usuario de MySQL)
-- Password: (tu contraseña de MySQL)
