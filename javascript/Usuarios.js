document.addEventListener('DOMContentLoaded', function() {
    fetch('../php_basesDatos/Usuarios.php')
      .then(response => response.json())
      .then(data => populateTable(data));
  
    function populateTable(data) {
      const tableBody = document.getElementById('credencialesTableBody');
      tableBody.innerHTML = ''; // Clear previous rows
  
      data.forEach((row) => {
        const tr = document.createElement('tr');
  
        tr.innerHTML = `
          <td>${row.idcredenciales}</td>
          <td>${row.user}</td>
          <td>${row.contrasena}</td>
          <td>${row.rol}</td>
          <td>${row.ultimoacceso}</td>
          <td>
            <button class="btn btn-info edit-btn" data-id="${row.idcredenciales}">Editar</button>
            <button class="btn btn-danger delete-btn" data-id="${row.idcredenciales}">Eliminar</button>
          </td>
        `;
  
        tableBody.appendChild(tr);
      });
  
      addCrudEventListeners();
    }
  
    function addCrudEventListeners() {
      // Event listener for editing
      document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          handleEdit(id);
        });
      });
  
      // Event listener for deleting
      document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          handleDelete(id);
        });
      });
    }
  
    function handleEdit(id) {
      Swal.fire({
        title: 'Editar Credencial',
        html: `<input type="text" id="editUser" class="swal2-input" placeholder="User">
               <input type="password" id="editPassword" class="swal2-input" placeholder="Password">`,
        confirmButtonText: 'Guardar',
        focusConfirm: false,
        preConfirm: () => {
          const user = document.getElementById('editUser').value;
          const password = document.getElementById('editPassword').value;
  
          if (!user || !password) {
            Swal.showValidationMessage('Por favor completa ambos campos');
          }
  
          return { user: user, password: password };
        }
      }).then(result => {
        if (result.isConfirmed) {
          // Send the updated data to the server to save the changes
          // Add your AJAX call here to update the credentials in the database
          Swal.fire('Actualizado!', 'La credencial ha sido actualizada.', 'success');
        }
      });
    }
  
    function handleDelete(id) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then(result => {
        if (result.isConfirmed) {
          // Send the request to delete the entry from the database
          // Add your AJAX call here to delete the credential from the database
          Swal.fire('Eliminado!', 'La credencial ha sido eliminada.', 'success');
        }
      });
    }
  });
  