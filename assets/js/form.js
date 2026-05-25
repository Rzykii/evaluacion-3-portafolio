document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");

  // --- Mensajes de error por campo ---
  const validaciones = {
    nombre: {
      campo: form.querySelector('[name="nombre"]'),
      validar(val) {
        if (!val) return "El nombre no puede estar vacío.";
        if (val.length < 3) return "El nombre debe tener al menos 3 caracteres.";
        return null;
      }
    },
    email: {
      campo: form.querySelector('[name="email"]'),
      validar(val) {
        if (!val) return "El correo no puede estar vacío.";
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) return "Ingresa un correo electrónico válido.";
        return null;
      }
    },
    asunto: {
      campo: form.querySelector('input[placeholder="¿Sobre qué quieres hablar?"]'),
      validar(val) {
        if (!val) return "El asunto no puede estar vacío.";
        if (val.length < 5) return "El asunto debe tener al menos 5 caracteres.";
        return null;
      }
    },
    mensaje: {
      campo: form.querySelector('[name="text"]'),
      validar(val) {
        if (!val) return "El mensaje no puede estar vacío.";
        if (val.length < 10) return "El mensaje debe tener al menos 10 caracteres.";
        return null;
      }
    }
  };

  // --- Mostrar / limpiar error en un campo ---
  function mostrarError(campo, mensaje) {
    campo.classList.add("is-invalid");
    campo.classList.remove("is-valid");

    let feedback = campo.nextElementSibling;
    if (!feedback || !feedback.classList.contains("invalid-feedback")) {
      feedback = document.createElement("div");
      feedback.classList.add("invalid-feedback");
      campo.insertAdjacentElement("afterend", feedback);
    }
    feedback.textContent = mensaje;
  }

  function limpiarError(campo) {
    campo.classList.remove("is-invalid");
    campo.classList.add("is-valid");
    const feedback = campo.nextElementSibling;
    if (feedback && feedback.classList.contains("invalid-feedback")) {
      feedback.textContent = "";
    }
  }

  // --- Validación en tiempo real (al salir del campo) ---
  Object.values(validaciones).forEach(({ campo, validar }) => {
    campo.addEventListener("blur", () => {
      const error = validar(campo.value.trim());
      error ? mostrarError(campo, error) : limpiarError(campo);
    });

    // Limpiar error mientras escribe
    campo.addEventListener("input", () => {
      if (campo.classList.contains("is-invalid")) {
        const error = validar(campo.value.trim());
        error ? mostrarError(campo, error) : limpiarError(campo);
      }
    });
  });

  // --- Envío del formulario ---
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    let valido = true;

    Object.values(validaciones).forEach(({ campo, validar }) => {
      const error = validar(campo.value.trim());
      if (error) {
        mostrarError(campo, error);
        valido = false;
      } else {
        limpiarError(campo);
      }
    });

    if (!valido) return;

    // --- Éxito ---
    mostrarAlerta("success", "<i class='bi bi-check-circle'></i> ¡Mensaje enviado correctamente! Me pondré en contacto pronto.");
    form.reset();
    Object.values(validaciones).forEach(({ campo }) => {
      campo.classList.remove("is-valid");
    });
  });

  // --- Alerta de resultado ---
  function mostrarAlerta(tipo, html) {
    const alerta = document.createElement("div");
    alerta.className = `alert alert-${tipo} alert-dismissible fade show mt-3`;
    alerta.innerHTML = `${html}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;

    form.insertAdjacentElement("afterend", alerta);

    setTimeout(() => {
      alerta.classList.remove("show");
      setTimeout(() => alerta.remove(), 300);
    }, 4000);
  }
});