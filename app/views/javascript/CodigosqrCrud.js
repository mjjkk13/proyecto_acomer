document.addEventListener('DOMContentLoaded', () => {
  cargarRegistros();


  function cargarRegistros() {
    fetch('crud.php')
      .then(response => response.json())
      .then(data => {
        const tablaCodigos = document.getElementById('tablaCodigos');
        tablaCodigos.innerHTML = ''; 

        data.forEach(registro => {
          const fila = document.createElement('tr');
          fila.innerHTML = `
            <td>${registro.fecha_hora}</td>
            <td><img src="${registro.imagen}" alt="Código QR" width="50" height="50"></td>
            <td>
              <button class="btn btn-danger btn-sm" onclick="eliminarRegistro(${registro.id_asistencia})">Eliminar</button>
            </td>
          `;
          tablaCodigos.appendChild(fila);
        });
      })
      .catch(error => console.error('Error al cargar los registros:', error));
  }

 
  window.eliminarRegistro = function (id) {
    Swal.fire({
      title: '¿Estás seguro?',
      text: "No podrás revertir esto.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminarlo!'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('crud.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({ 'eliminar': true, 'id_asistencia': id })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire('Eliminado!', data.message, 'success');
            cargarRegistros(); 
          } else {
            Swal.fire('Error', data.message, 'error');
          }
        })
        .catch(error => console.error('Error al eliminar el registro:', error));
      }
    });
  };
});
 