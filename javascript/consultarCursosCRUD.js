// Función para mostrar los datos
function showData(data) {
    const tableBody = document.querySelector('#cursosTable tbody');
    tableBody.innerHTML = '';

    data.forEach(item => {
        const row = `
            <tr>
                <td>${item.nombrecurso || 'No especificado'}</td>
                <td>${item.nombreDocente || 'No especificado'}</td>
                <td>
                    <button class="btn btn-primary" onclick="editItem(${item.idcurso})">
                        <i class="fas fa-edit"></i> <!-- Icono de lápiz -->
                    </button>
                    <button class="btn btn-danger" onclick="deleteItem(${item.idcurso})">
                        <i class="fas fa-trash-alt"></i> <!-- Icono de basurero -->
                    </button>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
    });
}

// Función para crear un nuevo curso
function createNewCourse() {
    const nombrecurso = prompt('Ingrese el nombre del curso:');
    const docente_id = prompt('Ingrese el ID del docente:');
    
    if (nombrecurso && docente_id) {
        fetch('../php_basesDatos/Cursos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'create',
                nombrecurso,
                docente_id
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success || data.error);
            fetchData();
        });
    }
}

// Función para editar un curso existente
function editItem(idcurso) {
    const nombrecurso = prompt('Ingrese el nuevo nombre del curso:');
    const docente_id = prompt('Ingrese el nuevo ID del docente:');
    
    if (nombrecurso && docente_id) {
        fetch('../php_basesDatos/Cursos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'update',
                idcurso,
                nombrecurso,
                docente_id
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success || data.error);
            fetchData();
        });
    }
}

// Función para eliminar un curso
function deleteItem(idcurso) {
    if (confirm('¿Estás seguro de eliminar este curso?')) {
        fetch('../php_basesDatos/Cursos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'delete',
                idcurso
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success || data.error);
            fetchData();
        });
    }
}

// Agregar un botón para crear un nuevo curso
document.addEventListener('DOMContentLoaded', async () => {
    const data = await fetchData();
    showData(data);
    
    // Agregar el botón de "Agregar Curso" (signo más)
    const addButton = `
        <div class="d-flex justify-content-end">
            <button class="btn btn-success" id="addCourseBtn">
                <i class="fas fa-plus"></i> Agregar Curso <!-- Icono de signo más -->
            </button>
        </div>
    `;
    document.querySelector('.row.mt-4').insertAdjacentHTML('beforeend', addButton);
    
    document.getElementById('addCourseBtn').addEventListener('click', createNewCourse);
});
