document.addEventListener('DOMContentLoaded', function() {
  // Load data when the page loads
  fetchData();

  // Fetch data from the server
  function fetchData() {
    fetch('../../php_basesDatos/Usuarios.php?action=fetchAll')
      .then(response => response.json())
      .then(data => populateTable(data))
      .catch(error => console.error('Error fetching data:', error));
  }

  // Populate the table with data
  function populateTable(data) {
    const tableBody = document.getElementById('credencialesTableBody');
    tableBody.innerHTML = ''; // Clear existing rows

    data.forEach(row => {
      const tr = document.createElement('tr');

      tr.innerHTML = `
        <td>${row.nombre_usuario_credenciales}</td>
        <td>${row.tipo_usuario_rol}</td>
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

  // Add event listeners to buttons
  function addCrudEventListeners() {
    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        handleEdit(id);
      });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        handleDelete(id);
      });
    });
  }

  // Handle editing user
  function handleEdit(id) {
    const selectedRow = document.querySelector(`[data-id="${id}"]`).parentNode.parentNode;
    const currentUser = selectedRow.children[0].textContent;

    Swal.fire({
      title: 'Editar Credencial',
      html: `<input type="text" id="editUser" class="swal2-input" placeholder="User" value="${currentUser}">
             <input type="password" id="editPassword" class="swal2-input" placeholder="Nuevo Password (dejar en blanco si no desea cambiar)">`,
      confirmButtonText: 'Guardar',
      focusConfirm: false,
      preConfirm: () => {
        const user = document.getElementById('editUser').value;
        const password = document.getElementById('editPassword').value;

        if (!user) {
          Swal.showValidationMessage('Por favor completa el campo del nombre de usuario');
        }

        return { user: user, password: password };
      }
    }).then(result => {
      if (result.isConfirmed) {
        const updateData = {
          id: id,
          user: result.value.user
        };

        if (result.value.password) {
          updateData.password = result.value.password;
        }

        fetch('../../php_basesDatos/Usuarios.php?action=update', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(updateData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire('Actualizado!', 'La credencial ha sido actualizada.', 'success');
            fetchData(); // Refresh table
          } else {
            Swal.fire('Error', 'No se pudo actualizar la credencial.', 'error');
          }
        })
        .catch(error => console.error('Error updating user:', error));
      }
    });
  }

  // Handle deleting user
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
        fetch('../../php_basesDatos/Usuarios.php?action=delete', {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire('Eliminado!', 'La credencial ha sido eliminada.', 'success');
            fetchData(); // Refresh table
          } else {
            Swal.fire('Error', 'No se pudo eliminar la credencial.', 'error');
          }
        })
        .catch(error => console.error('Error deleting user:', error));
      }
    });
  }
});
