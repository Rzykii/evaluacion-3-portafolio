-- ============================================================
--  PORTAFOLIO WEB AUTOADMINISTRABLE - Sebastian Muñoz
-- ============================================================

-- 1. Crear la base de datos
CREATE DATABASE IF NOT EXISTS smunoz_db1
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE smunoz_db1;

-- 2. Tabla de usuarios administradores
CREATE TABLE IF NOT EXISTS usuarios (
  id          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  username    VARCHAR(50)       NOT NULL UNIQUE,
  password    VARCHAR(255)      NOT NULL,
  nombre      VARCHAR(100)      NOT NULL,
  email       VARCHAR(150)      NOT NULL,
  activo      TINYINT(1)        NOT NULL DEFAULT 1,
  created_at  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabla de biografia/personal_info
CREATE TABLE IF NOT EXISTS biografia (
  id          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  campo       VARCHAR(50)       NOT NULL UNIQUE,
  valor       TEXT              NOT NULL,
  tipo        ENUM('texto','textarea','email','telefono','url','imagen') NOT NULL DEFAULT 'texto',
  etiqueta    VARCHAR(100)      NOT NULL,
  orden       INT UNSIGNED      NOT NULL DEFAULT 0,
  updated_at  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabla de habilidades
CREATE TABLE IF NOT EXISTS habilidades (
  id          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  nombre      VARCHAR(100)      NOT NULL,
  categoria   ENUM('Frontend','Backend','Database','Tools','Otra') NOT NULL DEFAULT 'Frontend',
  icono       VARCHAR(255)      DEFAULT NULL,
  orden       INT UNSIGNED      NOT NULL DEFAULT 0,
  activo      TINYINT(1)        NOT NULL DEFAULT 1,
  created_at  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabla de tecnologias con porcentaje de dominio
CREATE TABLE IF NOT EXISTS tecnologias (
  id          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  nombre      VARCHAR(100)      NOT NULL,
  categoria   ENUM('Frontend','Backend','Database','Tools') NOT NULL DEFAULT 'Frontend',
  porcentaje  INT UNSIGNED      NOT NULL DEFAULT 0,
  color       VARCHAR(20)       NOT NULL DEFAULT 'primary',
  emoji       VARCHAR(10)       DEFAULT NULL,
  orden       INT UNSIGNED      NOT NULL DEFAULT 0,
  activo      TINYINT(1)        NOT NULL DEFAULT 1,
  created_at  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabla de proyectos
CREATE TABLE IF NOT EXISTS proyectos (
  id          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  titulo      VARCHAR(200)      NOT NULL,
  descripcion TEXT              NOT NULL,
  imagen      VARCHAR(255)      DEFAULT NULL,
  link_demo   VARCHAR(255)      DEFAULT NULL,
  link_codigo VARCHAR(255)      DEFAULT NULL,
  orden       INT UNSIGNED      NOT NULL DEFAULT 0,
  activo      TINYINT(1)        NOT NULL DEFAULT 1,
  created_at  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabla de mensajes del formulario de contacto
CREATE TABLE IF NOT EXISTS contacto (
  id          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  nombre      VARCHAR(100)      NOT NULL,
  email       VARCHAR(150)      NOT NULL,
  asunto      VARCHAR(200)      NOT NULL,
  mensaje     TEXT              NOT NULL,
  fecha       DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  leido       TINYINT(1)        NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  DATOS DE EJEMPLO / INICIALES
-- ============================================================

-- Usuario administrador por defecto
-- Usuario: smunoz
-- Contraseña: Seba.UCT2026!
INSERT INTO usuarios (username, password, nombre, email) VALUES
('smunoz', '$2b$12$IbKOHSY2UBioJev5X5MwAOLXZ3VLVaQZcn9ks8dwwvDHinxxP2vSK', 'Sebastián Muñoz', 'smunoz2025@alu.uct.cl');

-- Datos de biografía iniciales
INSERT INTO biografia (campo, valor, tipo, etiqueta, orden) VALUES
('nombre', 'Sebastián Muñoz', 'texto', 'Nombre completo', 1),
('titulo', 'Desarrollador Full Stack(casi)', 'texto', 'Título profesional', 2),
('descripcion', 'Soy un intento de desarrollador apasionado con experiencia en crear aplicaciones web modernas y escalables. Me especializo en tecnologías Frontend y Backend.', 'textarea', 'Descripción personal', 3),
('email', 'smunoz2025@alu.uct.cl', 'email', 'Correo electrónico', 4),
('telefono', '+1 234 567 890', 'telefono', 'Teléfono', 5),
('ubicacion', 'Temuco, Chile', 'texto', 'Ubicación', 6),
('github', 'https://github.com/Rzykii', 'url', 'GitHub', 7),
('linkedin', '', 'url', 'LinkedIn', 8),
('twitter', '', 'url', 'Twitter/X', 9),
('instagram', '', 'url', 'Instagram', 10),
('foto_perfil', 'assets/img/1527bd97bcc54444a84e3997ebe60ca9.png', 'imagen', 'Foto de perfil', 11);

-- Habilidades iniciales
INSERT INTO habilidades (nombre, categoria, icono, orden) VALUES
('HTML', 'Frontend', 'assets/img/html.png', 1),
('CSS', 'Frontend', 'assets/img/css-3.png', 2),
('JavaScript', 'Frontend', 'assets/img/js.png', 3),
('Bootstrap', 'Frontend', 'assets/img/letras-del-alfabeto.png', 4),
('PHP', 'Backend', 'assets/img/php.png', 5),
('MySQL', 'Database', 'assets/img/mysql.png', 6),
('Node.js', 'Backend', 'assets/img/nodejs.png', 7),
('GitHub', 'Tools', 'assets/img/github.png', 8);

-- Tecnologías iniciales
INSERT INTO tecnologias (nombre, categoria, porcentaje, color, emoji, orden) VALUES
('HTML', 'Frontend', 95, 'primary', '🌐', 1),
('CSS', 'Frontend', 90, 'primary', '🎨', 2),
('JavaScript', 'Frontend', 90, 'primary', '⚡', 3),
('Bootstrap', 'Frontend', 90, 'primary', '🅱️', 4),
('PHP', 'Backend', 80, 'success', '🐘', 5),
('Node.js', 'Backend', 80, 'success', '🟢', 6),
('MySQL', 'Database', 85, 'info', '🗄️', 7),
('GitHub', 'Tools', 85, 'warning', '🐙', 8);

-- Proyectos iniciales
INSERT INTO proyectos (titulo, descripcion, imagen, link_demo, link_codigo, orden) VALUES
('E-commerce Platform', 'Plataforma de comercio electrónico completa con pasarela de pagos, gestión de inventario y panel de administración.', 'assets/img/HD-wallpaper-code-text-programming-coding-thumbnail.jpg', '#', '#', 1),
('Task management app', 'Aplicación de gestión de tareas con colaboración en tiempo real y notificaciones.', 'assets/img/HD-wallpaper-microchip-neon-lines-black-background-chips-technology-backgrounds-thumbnail.jpg', '#', NULL, 2),
('Weather Dashboard', 'Dashboard meteorológico interactivo con visualización de datos en tiempo real.', 'assets/img/thumbnail-template-tech-world-background-226926.jpg', NULL, '#', 3);
