<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Portafolio Web</title>
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-light bg-light mb-5 py-3 fixed-top px-3">
  <div class="container-fluid">
    <a class="navbar-brand text-primary fw-bold px-4" href="#">Sebastián Muñoz</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link fw-bold px-3" href="#Biografía">Biografía</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3" href="#Habilidades">Habilidades/Herramientas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3" href="#Tecnologías">Tecnologías Dominadas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3" href="#Proyectos">Proyectos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold px-3" href="#Contacto">Contacto</a>
        </li>
      </ul>
      <form class="d-flex">               
        <button class="btn btn-primary fw-bold" type="button">
          <i class="bi bi-lock"></i> Inicio de sesión</button>
      </form>
    </div>
  </div>
</nav>

<div class="container mb-5" id="Biografía">
  <div class="row py-5">
    <div class="col-sm-7">
      <h6 class="text-primary">Bienvenido a mi portafolio</h6>
      <h1 class="text-dark fw-bold">Sebastián Muñoz</h1>
      <h5 class="text-muted">Desarrollador Full Stack(casi)</h5><br>
      <p>Soy un intento de desarrollador apasionado con experiencia en crear aplicaciones web modernas y escalables. Me especializo en tecnologías Frontend y Backend.</p><br>
      <div class="contact-item">
        <i class="bi bi-envelope"></i>
        <span>smunoz2025@alu.uct.cl</span>
      </div>
      <div class="contact-item">
        <i class="bi bi-telephone"></i>
        <span>+1 234 567 890</span>
      </div>
      <div class="contact-item">
        <i class="bi bi-geo-alt"></i>
        <span>Temuco, Chile</span>
      </div><br>
      <a href="https://github.com/Rzykii" target="_blank" class="btn btn-outline-primary">
        <i class="bi bi-github"></i> github
      </a>
      <a href="" class="btn btn-outline-primary">
        <i class="bi bi-linkedin"></i> linkedin
      </a>
      <a href="" class="btn btn-outline-primary">
        <i class="bi bi-twitter-x"></i> 
      </a>
      <a href="" class="btn btn-outline-primary">
        <i class="bi bi-instagram"></i> instagram
      </a>
    </div>
    <div class="col-sm-5">
      <img src="/assets/img/1527bd97bcc54444a84e3997ebe60ca9.png" class="rounded-circle mx-auto d-block shadow img-thumbnail" alt="yo">
    </div>
  </div>
</div>

<div class="container-fluid mb-5 py-5 bg-light" id="Habilidades">
  <h3 class="text-center fw-bold">Habilidades y Herramientas</h3>
  <p class="text-center py-3 text-muted">Conjunto de tecnologías y herramientas que domino para el desarrollo web</p>
  <div class="row">
    <div class="col-sm-3">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body text-center py-4">
          <img src="/assets/img/html.png" alt="html" width="40" height="40" class="mx-auto d-block">
          <div class="fw-bold mb-2">HTML</div>  
        </div>         
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body text-center py-4">
          <img src="/assets/img/css-3.png" alt="CSS" width="40" height="40" class="mx-auto d-block">
          <div class="fw-bold mb-2">CSS</div>  
        </div>         
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body text-center py-4">
          <img src="/assets/img/js.png" alt="JS" width="40" height="40" class="mx-auto d-block">
          <div class="fw-bold mb-2">JavaScript</div>  
        </div>         
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body text-center py-4">
          <img src="/assets/img/letras-del-alfabeto.png" alt="bootstrap" width="40" height="40" class="mx-auto d-block">
          <div class="fw-bold mb-2">Bootstrap</div>  
        </div>         
      </div>
    </div>
  </div><br>
  <div class="row">
    <div class="col-sm-3">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body text-center py-4">
          <img src="/assets/img/php.png" alt="php" width="40" height="40" class="mx-auto d-block">
          <div class="fw-bold mb-2">PHP</div>  
        </div>         
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body text-center py-4">
          <img src="/assets/img/mysql.png" alt="mysql" width="40" height="40" class="mx-auto d-block">
          <div class="fw-bold mb-2">MySQL</div>  
        </div>         
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body text-center py-4">
          <img src="/assets/img/nodejs.png" alt="nodejs" width="40" height="40" class="mx-auto d-block">
          <div class="fw-bold mb-2">Node.js</div>  
        </div>         
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body text-center py-4">
          <img src="/assets/img/github.png" alt="github" width="40" height="40" class="mx-auto d-block">
          <div class="fw-bold mb-2">GitHub</div>  
        </div>         
      </div>
    </div>
  </div>
</div>

<div class="container mb-5 py-5" id="Tecnologías">
  <h3 class="text-center fw-bold">Tecnologías Dominadas</h3>
  <p class="text-center py-3 text-muted">Nivel de dominio en diferentes tecnologías y frameworks</p><br>
  <div class="row">
    <div class="col-sm-6">
      <div class="card border-0 rounded-4 shadow">
        <div class="card-body bg-light fw-bold">Frontend
          <div class="progress" style="height:3px">
            <div class="progress-bar" style="width:100%;"></div>
          </div><br>  
          <p class="fw-bold">🌐 HTML</p>
          <div class="progress">
            <div class="progress-bar" style="width:95%">95%</div>
          </div><br>
          <p class="fw-bold">🎨 CSS</p>
          <div class="progress">
            <div class="progress-bar" style="width:90%">90%</div>
          </div><br>
          <p class="fw-bold">⚡ JavaScript</p>
          <div class="progress">
            <div class="progress-bar" style="width:90%">90%</div>
          </div><br>
          <p class="fw-bold">🅱️ Bootstrap</p>
          <div class="progress">
            <div class="progress-bar" style="width:90%">90%</div>
          </div><br>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card border-0 rounded-4 shadow">
        <div class="card-body bg-light fw-bold">Backend
          <div class="progress" style="height:3px">
            <div class="progress-bar bg-success" style="width:100%;"></div>
          </div><br>
          <p class="fw-bold">🐘 PHP</p>
          <div class="progress">
            <div class="progress-bar bg-success" style="width:80%">80%</div>
          </div><br>
          <p class="fw-bold">🟢 Nodejs</p>
          <div class="progress">
            <div class="progress-bar bg-success" style="width:80%">80%</div>
          </div><br><br><br><br><br><br><br>
        </div>  
      </div>
    </div>
  </div><br><br>

  <div class="row">
    <div class="col-sm-6">
      <div class="card border-0 rounded-4 shadow">
        <div class="card-body bg-light fw-bold">Database
          <div class="progress" style="height:3px">
            <div class="progress-bar bg-info" style="width:100%;"></div>
          </div><br>  
          <p class="fw-bold">🗄️ MySQL</p>
          <div class="progress">
            <div class="progress-bar bg-info" style="width:85%">85%</div>
          </div><br>        
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card border-0 rounded-4 shadow">
        <div class="card-body bg-light fw-bold">Tools
          <div class="progress" style="height:3px">
            <div class="progress-bar bg-warning" style="width:100%;"></div>
          </div><br>
          <p class="fw-bold">🐙 GitHub</p>
          <div class="progress">
            <div class="progress-bar bg-warning" style="width:85%">85%</div>
          </div><br>          
        </div>  
      </div>
    </div>
  </div>
</div>

<div class="container-fluid py-5 bg-light" id="Proyectos">
  <h3 class="text-center fw-bold">Proyectos Realizados</h3>
  <p class="text-center py-3 text-muted">Una selección de proyectos que he desarrollado</p><br>
  <div class="row mb-5">
    <div class="col-sm-4">
      <div class="card shadow-sm border-0 rounded-4" style="width:350px">
        <div class="project-img">
          <img class="card-img-top" src="/assets/img/HD-wallpaper-code-text-programming-coding-thumbnail.jpg" alt="sos">
        </div>
        <div class="card-body">
          <h5 class="card-title">E-commerce Platform</h5><br>
          <p class="card-text text-muted">Plataforma de comercio electrónico completa con pasarela de pagos, gestión de inventario y panel de administración.</p><br>
          <a href="#" class="btn btn-primary"><i class="bi bi-box-arrow-up-right"></i> Ver demo</a>
          <a href="#" class="btn btn-outline-light text-dark"><i class="bi bi-github"></i> Código</a>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card shadow-sm border-0 rounded-4" style="width:350px">
        <div class="project-img">
          <img class="card-img-top" src="/assets/img/HD-wallpaper-microchip-neon-lines-black-background-chips-technology-backgrounds-thumbnail.jpg" alt="sos">
        </div>
        <div class="card-body">
          <h5 class="card-title">Task management app</h5><br>
          <p class="card-text text-muted">Aplicación de gestión de tareas con colaboración en tiempo real y notificaciones.</p><br>
          <div class="d-grid gap-3">
            <a href="#" class="btn btn-primary"><i class="bi bi-box-arrow-up-right"></i> Ver demo</a>
          </div>        
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card shadow-sm border-0 rounded-4" style="width:350px">
        <div class="project-img">
          <img class="card-img-top" src="/assets/img/thumbnail-template-tech-world-background-226926.jpg" alt="sos">
        </div>
        <div class="card-body">
          <h5 class="card-title">Weather Dashboard</h5><br>
          <p class="card-text text-muted">Dashboard meteorológico interactivo con visualización de datos en tiempo real.</p><br>
          <div class="d-grid gap-3">
            <a href="#" class="btn btn-outline-light text-dark"><i class="bi bi-github"></i> Código</a>
          </div>        
        </div>
      </div>
    </div>
  </div><br><br><br><br><br><br>
  <h3 class="text-center fw-bold" id="Contacto">Formulario de contacto</h3>
  <p class="text-center py-3 text-muted">¿Tienes algún proyecto en mente? ¡Contáctame y trabajemos juntos!</p><br><br><br>
  <div class="container">
  <div class="card shadow border-0 rounded-4">
    <div class="card-body p-5">
      <form>
        <div class="row g-4">
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person"></i> Nombre</label>
            <input type="text" class="form-control" placeholder="Tu nombre completo" name="nombre">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-envelope"></i> Correo electrónico</label>
            <input type="email" class="form-control" placeholder="tu@email.com" name="email">
          </div>
          <div class="col-12">
            <label class="form-label"><i class="bi bi-file-earmark"></i> Asunto</label>
            <input type="text" class="form-control" placeholder="¿Sobre qué quieres hablar?">
          </div>
          <div class="col-12">
            <label class="form-label" for="comment"><i class="bi bi-chat-left"></i> Mensaje</label>
            <textarea class="form-control" placeholder="Escribe tu mensaje aquí..." rows="5" id="comment" name="text"></textarea>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3"><i class="bi bi-send"></i> Enviar Mensaje</button>
            
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

<footer class="footer">
  <div class="container">
    <div class="row gy-5">
      <div class="col-lg-4 col-md-6">
        <h5>Sebastián Muñoz</h5>
        <p>
          Desarrollador Full Stack especializado en crear experiencias
          web modernas y funcionales.
        </p>
      </div>        
      <div class="col-lg-4 col-md-6">
        <h5>Contacto</h5>
        <div class="contact-item">
          <i class="bi bi-envelope"></i>
          <span>smunoz2025@alu.uct.cl</span>
        </div>
        <div class="contact-item">
          <i class="bi bi-telephone"></i>
          <span>+1 234 567 890</span>
        </div>
        <div class="contact-item">
          <i class="bi bi-geo-alt"></i>
          <span>Temuco, Chile</span>
        </div>
      </div>        
      <div class="col-lg-4">
        <h5>Redes Sociales</h5>
        <div class="social-icons">
          <a href="https://github.com/Rzykii" target="_blank"><i class="bi bi-github"></i></a>
          <a href="#"><i class="bi bi-linkedin"></i></a>
          <a href="#"><i class="bi bi-twitter-x"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
        </div>
      </div>
    </div>
    <hr>
    <div class="copyright">
      © 2026 Sebastián Muñoz. Todos los derechos reservados.
    </div>
  </div>
</footer>

<script src="/assets/js/form.js"></script>

</body>
</html>