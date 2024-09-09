document.getElementById('verContrasena').addEventListener('change', function() {
  var contrasenaInput = document.getElementById('contrasena');
  contrasenaInput.type = this.checked ? 'text' : 'password';
});

document.getElementById('rol').addEventListener('change', function() {
  var cursosDisponibles = document.getElementById('cursosDisponibles');
  cursosDisponibles.style.display = this.value === 'Docente' ? 'block' : 'none';
});

document.getElementById('registroForm').addEventListener('submit', function(event) {
  event.preventDefault(); // Evitar envío inmediato
  var form = event.target;

  var formData = new FormData(form);

  fetch(form.action, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Enviar datos a envioCorreo.php
      return fetch('../../php_basesDatos/envioCorreo.php', {
        method: 'POST',
        body: formData
      });
    } else {
      Swal.fire({
        title: 'Error al crear usuario',
        text: data.message,
        icon: 'error',
        confirmButtonText: 'Aceptar'
      });
      throw new Error(data.message);
    }
  })
  .then(response => response.text())
  .then(data => {
    console.log('Correo enviado:', data);
    Swal.fire({
      title: 'Usuario creado correctamente',
      icon: 'success',
      confirmButtonText: 'Aceptar'
    }).then(() => {
      form.reset();
      document.getElementById('cursosDisponibles').style.display = 'none'; // Ocultar dropdown
    });
  })
  .catch(error => {
    Swal.fire({
      title: 'Error de conexión',
      text: 'No se pudo conectar con el servidor.',
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
  });
});
