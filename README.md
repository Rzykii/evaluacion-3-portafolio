
# 🌐 Portafolio Web Autoadministrable 

Portafolio personal desarrollado con **PHP**, **Bootstrap 5** y **JavaScript** puro. Presenta información profesional, habilidades técnicas, proyectos realizados y un formulario de contacto funcional conectado a una base de datos MySQL.

🔗 **Demo en vivo:** [teclab.uct.cl/~smunoz2025](https://teclab.uct.cl/~smunoz2025/)

---

## 📁 Estructura del proyecto

```
portafolio/
│
├── index.php                 # Página principal (toda la UI)
├── enviar_contacto.php       # Endpoint que recibe y guarda el formulario
├── db.php                    # Configuración de conexión a MySQL (PDO)
├── sql_setup.sql             # Script para crear la base de datos y tablas
│
└── assets/
    ├── css/
    │   └── style.css         # Estilos personalizados (navbar, footer, hover, etc.)
    ├── js/
    │   └── form.js           # Validación y envío AJAX del formulario de contacto
    └── img/
        ├── 1527bd97...png    # Foto de perfil
        ├── html.png
        ├── css-3.png
        ├── js.png
        ├── letras-del-alfabeto.png   # Bootstrap
        ├── php.png
        ├── mysql.png
        ├── nodejs.png
        ├── github.png
        └── [imágenes de proyectos].jpg
```

---

## 🧩 Secciones del portafolio

### 1. Biografía
Presentación personal con nombre, descripción, datos de contacto (email, teléfono, ubicación) y enlaces a redes sociales (GitHub, LinkedIn, Instagram, X).

### 2. Habilidades y Herramientas
Grid de tarjetas con íconos para cada tecnología dominada, organizadas por categoría mediante badges (Frontend, Backend, Database, Tools).

Tecnologías mostradas: HTML · CSS · JavaScript · Bootstrap · PHP · MySQL · Node.js · GitHub

### 3. Tecnologías Dominadas
Cards con barras de progreso que muestran el nivel de dominio en 4 categorías:
- **Frontend** — HTML (95%), CSS (90%), JavaScript (90%), Bootstrap (90%)
- **Backend** — PHP (80%), Node.js (80%)
- **Database** — MySQL (85%)
- **Tools** — GitHub (85%)

### 4. Proyectos Realizados
Tres tarjetas de proyectos con imagen, título, descripción y botones de acceso a demo o código fuente. Las imágenes tienen efecto zoom al pasar el cursor.

### 5. Formulario de Contacto
Formulario completamente funcional con validación en cliente y servidor:
- Validación en tiempo real campo por campo (blur + input)
- Envío vía `fetch()` sin recargar la página
- Respuesta visual con alertas Bootstrap (éxito / error)
- Los mensajes se guardan en la base de datos MySQL

---

## ⚙️ Tecnologías utilizadas

| Capa | Tecnología |
|---|---|
| Estructura | PHP 8, HTML5 |
| Estilos | Bootstrap 5.3, CSS3 personalizado |
| Interactividad | JavaScript (Vanilla ES6+) |
| Iconos | Bootstrap Icons 1.11 |
| Base de datos | MySQL con PDO |
| Servidor local | XAMPP (Apache + MySQL) |
| Despliegue | CoreFTP → teclab.uct.cl |

---

## 🌍 Despliegue en servidor remoto

1. En el panel del hosting, crear la base de datos MySQL e importar `sql_setup.sql`
2. Editar `db.php` con las credenciales reales del hosting
3. Subir todos los archivos con CoreFTP manteniendo la estructura de carpetas

## ✍️ Autor

Estudiante de Desarrollo Web · UCT Temuco, Chile
[github.com/Rzykii](https://github.com/Rzykii) · smunoz2025@alu.uct.cl
