async function fetchData() {
    try {
        const response = await fetch('/php_basesDatos/cursosCRUD.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'read' })
        });
        if (!response.ok) throw new Error('Network response was not ok');
        return await response.json();
    } catch (error) {
        console.error('Error fetching data:', error);
        Swal.fire({ title: 'Error', text: 'No se pudieron obtener los datos. Por favor, intente de nuevo más tarde.', icon: 'error' });
    }
}

// Función para mostrar los datos de cursos en la tabla
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
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" onclick="deleteItem(${item.idcurso})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
    });
}

// Función para crear un nuevo curso
async function createNewCourse() {
    const { value: nombrecurso } = await Swal.fire({
        title: 'Nombre del Curso',
        input: 'text',
        inputPlaceholder: 'Ingrese el nombre del curso',
        showCancelButton: true,
        inputValidator: value => !value && 'Debe ingresar un nombre de curso!'
    });
    if (nombrecurso) {
        const { value: docente_id } = await Swal.fire({
            title: 'Seleccionar Docente',
            input: 'select',
            inputOptions: await getDocentesOptions(),
            inputPlaceholder: 'Seleccionar docente',
            showCancelButton: true,
            inputValidator: value => !value && 'Debe seleccionar un docente!'
        });

        if (docente_id) {
            const payload = { nombrecurso, docente_id, action: 'create' };
            if (await sendData(payload)) {
                const data = await fetchData();
                showData(data);
            }
        }
    }
}

// Función para editar un curso
async function editItem(id) {
    const { value: newCourseData } = await Swal.fire({
        title: 'Editar Curso',
        html: `
            <input id="swal-input-course" class="swal2-input" placeholder="Nuevo nombre de curso">
            <select id="swal-input-docente" class="swal2-select">
                ${(await getDocentesOptions()).map(option => `<option value="${option.value}">${option.text}</option>`).join('')}
            </select>
        `,
        focusConfirm: false,
        preConfirm: () => {
            return {
                nombrecurso: document.getElementById('swal-input-course').value,
                docente_id: document.getElementById('swal-input-docente').value
            };
        },
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar'
    });

    if (newCourseData) {
        const { nombrecurso, docente_id } = newCourseData;
        if (nombrecurso && docente_id) {
            const payload = { idcurso: id, nombrecurso, docente_id, action: 'update' };
            if (await sendData(payload)) {
                const data = await fetchData();
                showData(data);
            }
        } else {
            Swal.fire({ title: 'Error', text: 'El nombre del curso y el docente son requeridos.', icon: 'error' });
        }
    }
}

// Función para eliminar un curso
async function deleteItem(id) {
    const confirmation = await Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (confirmation.isConfirmed) {
        const payload = { idcurso: id, action: 'delete' };
        if (await sendData(payload)) {
            const data = await fetchData();
            showData(data);
        }
    }
}

// Función para obtener los docentes disponibles para seleccionar
async function getDocentesOptions() {
    try {
        const response = await fetch('tu_archivo_php_para_docentes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'getDocentes' })
        });
        const docentes = await response.json();
        const options = {};
        docentes.forEach(docente => {
            options[docente.iddocente] = `${docente.nombre} ${docente.apellido}`;
        });
        return options;
    } catch (error) {
        console.error('Error fetching docentes:', error);
        Swal.fire({ title: 'Error', text: 'No se pudieron obtener los docentes. Por favor, intente de nuevo más tarde.', icon: 'error' });
        return {};
    }
}

// Función para enviar datos al servidor
async function sendData(payload) {
    try {
        const response = await fetch('tu_archivo_php.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(payload)
        });
        const result = await response.json();
        Swal.fire({ title: result.success ? 'Éxito' : 'Error', text: result.success || result.error, icon: result.success ? 'success' : 'error' });
        return !!result.success;
    } catch (error) {
        console.error('Error sending data:', error);
        Swal.fire({ title: 'Error', text: 'No se pudo enviar la solicitud. Por favor, intente de nuevo más tarde.', icon: 'error' });
        return false;
    }
}

// Inicializar la carga de datos
document.addEventListener('DOMContentLoaded', async () => {
    const data = await fetchData();
    showData(data);
});

// Agregar eventos al botón de agregar curso
document.getElementById('addCourseBtn').addEventListener('click', createNewCourse);
