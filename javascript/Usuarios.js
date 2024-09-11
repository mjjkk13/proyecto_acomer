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

    // Conditional logic for the 'estado' column with colored text
    const estadoDisplay = row.estado == 1 
      ? '<span style="color: green; font-weight: bold;">ACTIVO</span>' 
      : '<span style="color: red; font-weight: bold;">INACTIVO</span>';

    tr.innerHTML = `
      <td>${row.nombre_usuario_credenciales}</td>
      <td>${row.tipo_usuario_rol}</td>
      <td>${estadoDisplay}</td>
      <td>${row.ultimoacceso}</td>
       <td>
    <button class="btn btn-info edit-btn" data-id="${row.idcredenciales}">
      <i class="fas fa-pencil-alt"></i>
    </button>
    <button class="btn btn-danger delete-btn" data-id="${row.idcredenciales}">
      <i class="fas fa-trash-alt"></i>
    </button>

  </td>
    `;

    tableBody.appendChild(tr);
  });

  addCrudEventListeners(); // Add event listeners for edit and delete buttons
}
//redireccion a registrar usuario
document.getElementById('addUserBtn').addEventListener('click', function() {
  window.location.href = 'registrarUsuario.php';
});


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

  function handleEdit(id) {
    const selectedRow = document.querySelector(`[data-id="${id}"]`).parentNode.parentNode;
    const currentUser = selectedRow.children[0].textContent;
    const currentStatus = selectedRow.children[2].textContent.trim().toUpperCase() === 'ACTIVO' ? '1' : '0';
  
    Swal.fire({
      title: 'Editar Credencial',
      html: `
        <div style="display: flex; flex-direction: column; gap: 10px;">
          <input type="text" id="editUser" class="swal2-input" placeholder="User" value="${currentUser}">
          <input type="password" id="editPassword" class="swal2-input" placeholder="Nuevo Password (dejar en blanco si no desea cambiar)">
          <select id="editStatus" class="swal2-select">
            <option value="disable">Seleccione</option>
            <option value="1" ${currentStatus === '1' ? 'selected' : ''}>Activo</option>
            <option value="0" ${currentStatus === '0' ? 'selected' : ''}>Inactivo</option>
          </select>
        </div>`,
      confirmButtonText: 'Guardar',
      focusConfirm: false,
      preConfirm: () => {
        const user = document.getElementById('editUser').value;
        const password = document.getElementById('editPassword').value;
        const status = document.getElementById('editStatus').value;
  
        if (!user) {
          Swal.showValidationMessage('Por favor completa el campo del nombre de usuario');
          return false;
        }
  
        return { user, password, status };
      }
    }).then(result => {
      if (result.isConfirmed) {
        const updateData = {
          id: id,
          user: result.value.user,
          status: result.value.status // Include status for update
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
