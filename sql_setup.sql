-- ============================================================
--  PORTAFOLIO WEB - Sebastian Muñoz
--  Ejecutar este archivo en phpMyAdmin o desde la terminal MySQL
--  antes de subir el proyecto al servidor.
-- ============================================================

-- 1. Crear la base de datos
CREATE DATABASE IF NOT EXISTS smunoz_db1
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE smunoz_db1;

-- 2. Tabla de mensajes del formulario de contacto
CREATE TABLE IF NOT EXISTS contacto (
  id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  nombre      VARCHAR(100)    NOT NULL,
  email       VARCHAR(150)    NOT NULL,
  asunto      VARCHAR(200)    NOT NULL,
  mensaje     TEXT            NOT NULL,
  fecha       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  leido       TINYINT(1)      NOT NULL DEFAULT 0,   -- 0 = no leído, 1 = leído
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

