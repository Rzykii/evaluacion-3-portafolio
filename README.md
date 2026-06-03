# 🌐 Portafolio Web Autoadministrable

Portafolio personal desarrollado con **PHP**, **Bootstrap 5** y **JavaScript** puro. Presenta información profesional, habilidades técnicas, proyectos realizados, un formulario de contacto funcional y un **panel administrativo completo** para gestionar todo el contenido del sitio.

---

## ✨ Características

### Pública (Portafolio)
- **Biografía** personal dinámica
- **Habilidades y herramientas** con iconos y categorías
- **Tecnologías dominadas** con barras de progreso porcentuales
- **Proyectos realizados** con imágenes, demo y código
- **Formulario de contacto** con validación y almacenamiento en BD
- Diseño **responsive** con Bootstrap 5

### Administrativa (Dashboard)
- 🔐 **Sistema de login** funcional con protección de sesiones
- 🛡️ **Protección CSRF** y bloqueo por intentos fallidos
- 📝 **CRUD completo** de Biografía, Habilidades, Tecnologías y Proyectos
- 📬 **Gestión de mensajes** de contacto (marcar como leído, eliminar)
- 📊 **Estadísticas** en tiempo real en el dashboard
- 🎨 **Diseño oscuro coherente** con el portafolio público

---

## 📁 Estructura del proyecto

```
portafolio/
│
├── index.php              # Página principal (dinámica, carga desde BD)
├── login.php              # Página de inicio de sesión
├── logout.php             # Cierre de sesión
├── auth.php               # Sistema de autenticación y utilidades
├── db.php                 # Configuración de conexión a MySQL (PDO)
├── enviar_contacto.php    # Endpoint que recibe y guarda el formulario
├── sql_setup.sql          # Script para crear la base de datos y tablas
├── .htaccess              # Protección de archivos sensibles
├── README.md              # Este archivo
│
├── admin/                 # Panel administrativo
│   ├── biografia.php      # CRUD de biografía
│   ├── habilidades.php    # CRUD de habilidades
│   ├── tecnologias.php    # CRUD de tecnologías
│   ├── proyectos.php      # CRUD de proyectos
│   └── mensajes.php       # Gestión de mensajes de contacto
│
├── dashboard.php          # Dashboard principal con estadísticas
│
└── assets/
    ├── css/
    │   ├── style.css      # Estilos del portafolio público
    │   └── admin.css      # Estilos del panel administrativo
    ├── js/
    │   └── form.js        # Validación y envío AJAX del formulario
    └── img/
        └── [imágenes]     # Fotos, iconos e imágenes de proyectos
```

---

## 🚀 Instalación

### 1. Requisitos

- PHP >= 7.4
- MySQL / MariaDB
- Servidor web (Apache recomendado)
- XAMPP, WAMP, MAMP o hosting con cPanel

### 2. Clonar o descargar

```bash
git clone https://github.com/Rzykii/evaluacion-3-portafolio.git
```

### 3. Crear la base de datos

1. Abrir **phpMyAdmin** (http://localhost/phpmyadmin)
2. Ir a la pestaña **SQL**
3. Copiar y pegar todo el contenido de **`sql_setup.sql`**
4. Ejecutar (botón **Go**)

> Esto creará la base de datos `smunoz_db1` con todas las tablas necesarias y datos de ejemplo.

### 4. Configurar conexión a la BD

Editar **`db.php`** y ajustar las credenciales:

```php
// XAMPP (local)
define('DB_USER', 'root');       // tu usuario MySQL
define('DB_PASS', '');           // tu contraseña MySQL
```

**Para hosting:** cambiar también `DB_HOST` por el que entregue tu proveedor.

### 5. Credenciales de acceso

Por defecto se crea un usuario administrador:

| Campo      | Valor            |
|------------|------------------|
| Usuario    | `smunoz`         |
| Contraseña | `Seba.UCT2026!`  |

> ⚠️ **IMPORTANTE:** Cambiar la contraseña en producción ejecutando:
> ```sql
> UPDATE usuarios SET password = '$2y$10$...' WHERE username = 'smunoz';
> ```
> Generar el hash con `password_hash('tu_clave', PASSWORD_BCRYPT)` en PHP.

---

## 🔒 Sistema de Login

### Características de seguridad implementadas:

| Característica | Descripción |
|---------------|-------------|
| **Sesiones seguras** | Inactividad de 60 min expira la sesión |
| **CSRF Tokens** | Protección contra ataques de falsificación |
| **Bloqueo por intentos** | 5 intentos fallidos = 15 min de bloqueo |
| **Hash de contraseñas** | Bcrypt (PASSWORD_BCRYPT) |
| **Validación de acceso** | Redirección automática si no hay sesión |
| **Protección de rutas** | Todo el admin requiere autenticación |

---

## 📝 Uso del Dashboard

1. Ir al portafolio y hacer clic en **"Inicio de sesión"** (navbar)
2. Ingresar credenciales
3. Acceder al **Dashboard** con estadísticas
4. Navegar por las secciones del sidebar:
   - **Biografía**: Editar datos personales, contacto y redes sociales
   - **Habilidades**: Crear/editar/eliminar habilidades con iconos
   - **Tecnologías**: Gestionar tecnologías con porcentaje de dominio
   - **Proyectos**: Administrar proyectos con imágenes y enlaces
   - **Mensajes**: Ver y gestionar mensajes del formulario de contacto

---

## 🗄️ Tablas de la Base de Datos

| Tabla        | Descripción                          |
|-------------|--------------------------------------|
| `usuarios`   | Administradores del sistema          |
| `biografia`  | Campos clave-valor de información personal |
| `habilidades`| Habilidades con icono y categoría    |
| `tecnologias`| Tecnologías con porcentaje de dominio|
| `proyectos`  | Proyectos con imagen y enlaces       |
| `contacto`   | Mensajes del formulario de contacto  |

---

## 🔗 Demo en vivo

[teclab.uct.cl/~smunoz2025](https://teclab.uct.cl/~smunoz2025)

---

## 👤 Autor

**Sebastián Muñoz** — *smunoz2025@alu.uct.cl*

Temuco, Chile 🇨🇱
