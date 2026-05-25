# 🤖 Uso de Inteligencia Artificial en el Proyecto

Este documento registra y evidencia el uso de herramientas de Inteligencia Artificial durante el desarrollo del portafolio web personal, detallando qué se solicitó, qué se obtuvo y qué decisiones se tomaron a partir de ello.

**Herramienta utilizada:** Claude (Anthropic) — claude.ai  
**Modelo:** Claude Sonnet 4.6  
**Periodo de uso:** Mayo 2026  
**Proyecto:** Portafolio Web Personal — Sebastián Muñoz

---

## 📋 Índice de intervenciones

1. [Corrección de estructura HTML — Formulario de contacto](#1-corrección-de-estructura-html--formulario-de-contacto)
2. [Rediseño de sección de habilidades](#2-rediseño-de-sección-de-habilidades)
3. [Efecto hover en imágenes de proyectos](#3-efecto-hover-en-imágenes-de-proyectos)
4. [Generación del script de validación JavaScript](#4-generación-del-script-de-validación-javascript)
5. [Generación de archivos PHP y SQL para el backend](#5-generación-de-archivos-php-y-sql-para-el-backend)
6. [Diagnóstico y corrección de errores en producción](#6-diagnóstico-y-corrección-de-errores-en-producción)
7. [Corrección de bug en el formulario de contacto](#7-corrección-de-bug-en-el-formulario-de-contacto)
8. [Generación del README](#8-generación-del-readme)

---

## 1. Corrección de estructura HTML — Formulario de contacto

**Prompt enviado:**
> Necesito ajustar el contenido del formulario respetando los márgenes y el padding de cada casilla, para que todo encaje dentro de la tarjeta. [+ código con el bug]

**Problema detectado por la IA:**
El `</div>` del `card-body` estaba mal cerrado — se cerraba antes del `<form>`, dejando el formulario completamente fuera de la tarjeta Bootstrap.

**Solución generada:**
- Cierre correcto del `div.card-body` después del `</form>`
- Conversión de texto suelto a elementos `<label class="form-label">` semánticamente correctos
- Reemplazo de clases de margen manuales (`mt-4`) por el sistema de grid de Bootstrap (`col-12`)
- Botón de envío envuelto en `col-12` con clase `w-100` para ancho completo
- Corrección del `input type="password"` que estaba mal puesto en el campo de email

**Decisión del desarrollador:** Se aplicó la solución completa. Se aceptaron todos los cambios propuestos por su coherencia con Bootstrap 5.

---

## 2. Rediseño de sección de habilidades

**Prompt enviado:**
> ¿Cómo puedo hacer que mi código se vea así [imagen de referencia], exceptuando los íconos? [+ código original con lista interna]

**Análisis de la IA:**
El diseño original usaba una sola card con una lista de tecnologías. El diseño objetivo requería una card individual por tecnología dispuestas en grid.

**Solución generada:**
- Reestructuración completa: de una card con lista a múltiples cards independientes en `row g-3`
- Layout responsivo con `col-6 col-md-3` (2 columnas en móvil, 4 en escritorio)
- Badge tipo pill con fondo gris personalizado (`#f0f2f5`) para la categoría
- Tamaño de ícono aumentado con `fs-1` para replicar el diseño de referencia

**Aclaración posterior del desarrollador:**
> "Mala mía, este era el código" — se proporcionó el código real que ya usaba imágenes propias en vez de emojis.

**Ajuste generado tras la aclaración:**
- Adaptación al código real: imágenes dentro del `card-body`, `border-0 rounded-4`, `py-4` para espacio vertical
- Se mantuvieron las rutas originales de los archivos de imagen del desarrollador

**Decisión del desarrollador:** Se aplicó la versión ajustada. Los badges de categoría se agregaron como mejora adicional no solicitada explícitamente, y fueron aceptados.

---

## 3. Efecto hover en imágenes de proyectos

**Prompt enviado:**
> Aplica un efecto de hover a las imágenes de esta sección. [+ código de la sección de proyectos]

**Solución generada:**

CSS:
```css
.project-img {
  overflow: hidden;
  border-radius: 1rem 1rem 0 0;
}
.project-img img {
  transition: transform 0.4s ease;
}
.project-img img:hover {
  transform: scale(1.08);
}
```

HTML: envolver cada `<img>` en un `<div class="project-img">`

**Concepto explicado por la IA:** El `overflow: hidden` en el contenedor es indispensable — sin él la imagen al escalar se saldría visualmente de la tarjeta.

**Decisión del desarrollador:** Se aplicó exactamente como fue propuesto. Se entendió el concepto y se replicó en las tres tarjetas de proyectos.

---

## 4. Generación del script de validación JavaScript

**Prompt enviado:**
> Genera un archivo JavaScript para la parte del formulario de contacto, con las funciones básicas que se requieren, como no dejar campos vacíos.

**Archivo generado:** `assets/js/form.js`

**Funcionalidades implementadas por la IA:**
- Validación al enviar: ningún campo vacío antes de procesar
- Validación en tiempo real: al perder el foco (`blur`) y mientras se escribe (`input`)
- Reglas por campo: nombre ≥ 3 chars, asunto ≥ 5 chars, mensaje ≥ 10 chars, formato de email con regex
- Integración con clases nativas de Bootstrap: `is-invalid`, `is-valid`, `invalid-feedback`
- Alerta de éxito con auto-cierre a los 4 segundos y reset del formulario

**Decisión del desarrollador:** Se aceptó el archivo completo. Fue el primer archivo `.js` generado íntegramente por IA en este proyecto.

---

## 5. Generación de archivos PHP y SQL para el backend

**Prompt enviado:**
> Necesito un archivo `sql_setup.sql` para poder subir este proyecto a un servidor, además de los archivos PHP necesarios para que todo funcione correctamente. Estoy utilizando XAMPP para ejecutar Apache y MySQL, además luego utilizaré CoreFTP para subir los archivos.

**Archivos generados:**

`sql_setup.sql` — Creación de base de datos y tabla:
```sql
CREATE DATABASE IF NOT EXISTS portafolio_db CHARACTER SET utf8mb4;
CREATE TABLE contacto (
  id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre  VARCHAR(100) NOT NULL,
  email   VARCHAR(150) NOT NULL,
  asunto  VARCHAR(200) NOT NULL,
  mensaje TEXT         NOT NULL,
  fecha   DATETIME     DEFAULT CURRENT_TIMESTAMP,
  leido   TINYINT(1)   DEFAULT 0
);
```

`db.php` — Conexión PDO con manejo de excepciones y configuración separada para XAMPP vs hosting.

`enviar_contacto.php` — Endpoint POST que:
- Rechaza métodos distintos a POST
- Valida y sanea todos los campos del lado del servidor
- Inserta con sentencias preparadas (prevención de SQL injection)
- Responde en JSON para ser consumido por el JS

**form.js actualizado** — El `fetch()` reemplazó la simulación de éxito previa, con spinner en el botón durante el envío y manejo de errores de red.

**Decisión del desarrollador:** Se adoptó la arquitectura completa propuesta. Se reconoció la separación de responsabilidades entre `db.php` y `enviar_contacto.php` como una buena práctica.

---

## 6. Diagnóstico y corrección de errores en producción

**Prompt enviado:**
> ¿Que archivos o dependencias estan faltando, que provoca reorganizacion de la pagina? [URL del sitio en vivo] — probablemente me faltan archivos o necesito modificar o agregar valores al `index.php`.

**Diagnóstico realizado por la IA** (mediante inspección de la URL):
La página cargaba sin ningún estilo. Causa raíz: todas las rutas de recursos usaban `/assets/...` (rutas absolutas), que en un servidor con subdirectorio tipo `~usuario` apuntan a la raíz del dominio en vez de a la carpeta del usuario.

Ejemplo del problema:
```
Ruta escrita:   /assets/css/style.css
Resuelve en:    teclab.uct.cl/assets/css/style.css  ← no existe
Debería ser:    teclab.uct.cl/~smunoz2025/assets/css/style.css
```

**Solución generada:**
- `index.php` completamente reescrito con todas las rutas cambiadas a relativas (`assets/...` sin `/` inicial)
- Corrección adicional del campo `name="text"` del textarea a `name="mensaje"` para coincidir con el PHP
- Agregado `name="asunto"` que faltaba en el input de asunto
- Limpieza de `<br>` excesivos reemplazados por utilidades de Bootstrap

**Decisión del desarrollador:** Se subió el nuevo `index.php` vía CoreFTP. El problema se resolvió completamente.

---

## 7. Corrección de bug en el formulario de contacto

**Evidencia aportada por el desarrollador:** Captura de pantalla mostrando el JSON crudo en el navegador al enviar el formulario:
```json
{"ok":false,"errores":["El nombre debe tener al menos 3 caracteres.",...]}
```

**Diagnóstico realizado por la IA:**
Dos bugs combinados:

**Bug 1 — Selector incorrecto en `form.js`:**
```javascript
// Antes — seleccionaba el form del navbar (botón "Inicio de sesión")
const form = document.querySelector("form");

// Después — apunta al formulario correcto por ID
const form = document.querySelector("#contactForm");
```

**Bug 2 — Atributo `action` en el HTML:**
Con `action="enviar_contacto.php"` presente, si el JS fallaba el navegador enviaba el formulario de forma tradicional y renderizaba el JSON directamente como texto plano.

**Solución:**
- Selector corregido a `#contactForm`
- Removidos `action` y `method` del `<form>`, agregado atributo `novalidate`

**Decisión del desarrollador:** Corrección aplicada. Ambos archivos subidos al servidor.

---

## 8. Generación del README

**Prompt enviado:**
> Genera un archivo `readme.md` que describa el proyecto o portafolio, cómo está compuesto, y explicando un poco la estructura.

**Archivo generado:** `README.md` con árbol de carpetas, descripción de cada sección, tabla de tecnologías, esquema SQL e instrucciones de instalación tanto para XAMPP como para servidor remoto.

---

## 📊 Resumen general

| # | Tarea | Tipo de intervención | Archivos afectados |
|---|---|---|---|
| 1 | Bug en HTML del formulario | Corrección de código | `index.php` |
| 2 | Rediseño sección habilidades | Generación + ajuste | `index.php` |
| 3 | Hover en imágenes | Generación de código | `style.css`, `index.php` |
| 4 | Validación JavaScript | Generación completa | `form.js` |
| 5 | Backend PHP + SQL | Generación completa | `db.php`, `enviar_contacto.php`, `sql_setup.sql`, `form.js` |
| 6 | Bug rutas en producción | Diagnóstico + corrección | `index.php` |
| 7 | Bug formulario JSON crudo | Diagnóstico + corrección | `form.js`, `index.php` |
| 8 | Documentación README | Generación completa | `README.md` |

---

## 🧠 Reflexión sobre el uso de IA

El uso de Claude como asistente de desarrollo permitió acelerar significativamente la resolución de problemas técnicos, especialmente en áreas donde el error no era evidente a simple vista (rutas relativas en servidor, selector de formulario incorrecto). 

La IA fue utilizada como herramienta de apoyo: en todos los casos el desarrollador revisó, evaluó y decidió si aplicar o no las soluciones propuestas. Los archivos de backend (PHP, SQL) fueron generados íntegramente por IA y adoptados tras comprender su funcionamiento, mientras que el HTML y CSS fueron co-construidos a partir de código base propio del desarrollador.

---

*Documento generado con asistencia de Claude (Anthropic) · Mayo 2026*
