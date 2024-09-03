// Mostrar contraseña
document.getElementById('verContrasena').addEventListener('change', function() {
  var contrasenaInput = document.getElementById('contrasena');
  contrasenaInput.type = this.checked ? 'text' : 'password';
});

// Mostrar cursos disponibles
document.getElementById('rol').addEventListener('change', function() {
  var cursosDisponibles = document.getElementById('cursosDisponibles');
  cursosDisponibles.style.display = this.value === 'docente' ? 'block' : 'none';
});

// Enviar formulario con alerta SweetAlert
document.getElementById('registroForm').addEventListener('submit', function(event) {
  event.preventDefault(); // Evitar envío inmediato
  
  var formData = new FormData(this);
  
  fetch('../../php_basesDatos/registrarUsuario.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          Swal.fire({
              title: 'Éxito',
              text: data.message,
              icon: 'success',
              confirmButtonText: 'Aceptar'
          }).then(() => {
              window.location.reload(); // Recargar la página tras el éxito
          });
      } else {
          Swal.fire({
              title: 'Error',
              text: data.message,
              icon: 'error',
              confirmButtonText: 'Aceptar'
          });
      }
  })
  .catch(error => {
      Swal.fire({
          title: 'Error',
          text: 'Se produjo un error al procesar la solicitud.',
          icon: 'error',
          confirmButtonText: 'Aceptar'
      });
  });
});
