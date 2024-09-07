document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar los cursos desde el servidor
    function cargarCursos() {
        fetch('../../php_basesDatos/obtenerCursos.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data); // Verifica la estructura del JSON

                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = ''; // Limpiar contenido anterior

                // Verificar si data es un array antes de procesarlo
                if (!Array.isArray(data)) {
                    throw new Error('Data is not an array');
                }

                // Crear un fragmento para mejorar el rendimiento
                const fragment = document.createDocumentFragment();

                data.forEach(curso => {
                    const row = document.createElement('tr');

                    // Crear y llenar las celdas con la información del curso
                    const cursoCell = document.createElement('td');
                    cursoCell.textContent = curso.nombrecurso;

                    const directorCell = document.createElement('td');
                    directorCell.textContent = curso.nombredocente + ' ' + curso.apellidodocente;

                    // Crear la celda de acciones con los botones
                    const actionsCell = document.createElement('td');
                    actionsCell.innerHTML = `
                        <button class="btn btn-warning btn-sm btnEditar">Editar</button>
                        <button class="btn btn-danger btn-sm btnBorrar">Borrar</button>
                    `;

                    // Agregar las celdas a la fila
                    row.appendChild(cursoCell);
                    row.appendChild(directorCell);
                    row.appendChild(actionsCell);

                    // Agregar la fila al fragmento
                    fragment.appendChild(row);
                });

                // Añadir todas las filas al tbody de una vez
                tbody.appendChild(fragment);

                // Añadir eventos a los botones de edición y eliminación
                document.querySelectorAll('.btnEditar').forEach(btn => {
                    btn.addEventListener('click', editarCurso);
                });
                document.querySelectorAll('.btnBorrar').forEach(btn => {
                    btn.addEventListener('click', borrarCurso);
                });
            })
            .catch(error => {
                console.error('Error al cargar los cursos:', error);
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '<tr><td colspan="3">No se pudieron cargar los cursos. Intenta de nuevo más tarde.</td></tr>';
            });
    }

    function editarCurso(event) {
        // Obtener el curso correspondiente y abrir un formulario de edición
        const cursoRow = event.target.closest('tr');
        const cursoNombre = cursoRow.querySelector('td').textContent;
        alert('Editar curso: ' + cursoNombre);
        // Aquí puedes abrir un modal con un formulario para editar el curso
    }

    function borrarCurso(event) {
        // Obtener el curso correspondiente y realizar una solicitud para borrarlo
        const cursoRow = event.target.closest('tr');
        const cursoNombre = cursoRow.querySelector('td').textContent;
        if (confirm(`¿Estás seguro de que deseas eliminar el curso ${cursoNombre}?`)) {
            alert('Curso eliminado: ' + cursoNombre);
            // Aquí puedes realizar una solicitud DELETE al servidor para eliminar el curso
            cursoRow.remove(); // Eliminar la fila de la tabla después de la eliminación
        }
    }

    // Evento para agregar un nuevo curso
    document.getElementById('btnAgregarCurso').addEventListener('click', function() {
        alert('Agregar nuevo curso');
        // Aquí puedes abrir un modal o redirigir a una página de formulario de creación de cursos
    });

    // Cargar los cursos cuando la página se haya cargado
    cargarCursos();
});
