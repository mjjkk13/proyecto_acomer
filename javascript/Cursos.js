document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar los cursos desde el servidor
    function cargarCursos() {
        fetch('../../php_basesDatos/obtenerCursos.php?action=getCourses')
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);

                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = ''; // Limpiar contenido anterior

                if (!Array.isArray(data) || data.error) {
                    throw new Error(data.error || 'No se pudieron cargar los cursos.');
                }

                const fragment = document.createDocumentFragment();

                data.forEach(curso => {
                    const row = document.createElement('tr');
                    row.dataset.idcursos = curso.idcursos;

                    const cursoCell = document.createElement('td');
                    cursoCell.textContent = curso.nombrecurso;

                    const docenteCell = document.createElement('td');
                    docenteCell.textContent = curso.nombredocente + ' ' + curso.apellidodocente;

                    const actionsCell = document.createElement('td');
                    actionsCell.innerHTML = `
                        <button class="btn btn-warning btn-sm btnEditar">Editar</button>
                        <button class="btn btn-danger btn-sm btnBorrar">Borrar</button>
                    `;

                    row.appendChild(cursoCell);
                    row.appendChild(docenteCell);
                    row.appendChild(actionsCell);

                    fragment.appendChild(row);
                });

                tbody.appendChild(fragment);

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
                tbody.innerHTML = '<tr><td colspan="3">No se pudieron cargar los cursos.</td></tr>';
            });
    }

    function editarCurso(event) {
        const cursoRow = event.target.closest('tr');
        const idcursos = cursoRow.dataset.idcursos;
        const nombreCurso = cursoRow.querySelector('td').textContent;
        const docenteCurso = cursoRow.querySelector('td:nth-child(2)').textContent;

        // Obtén la lista de docentes desde el servidor
        fetch('../../php_basesDatos/obtenerCursos.php?action=getDocentes')
            .then(response => response.json())
            .then(docentes => {
                const docenteOptions = docentes.map(docente => 
                    `<option value="${docente.idusuarios}" ${docente.idusuarios == docenteCurso ? 'selected' : ''}>
                        ${docente.nombre} ${docente.apellido}
                    </option>`
                ).join('');

                Swal.fire({
                    title: 'Editar Curso',
                    html: `
                        <input id="nombreCurso" class="swal2-input" value="${nombreCurso}">
                        <select id="idDocente" class="swal2-select">
                            ${docenteOptions}
                        </select>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Guardar cambios',
                    preConfirm: () => {
                        const nombreCurso = Swal.getPopup().querySelector('#nombreCurso').value;
                        const idDocente = Swal.getPopup().querySelector('#idDocente').value;

                        if (!nombreCurso || !idDocente) {
                            Swal.showValidationMessage('Todos los campos son obligatorios');
                            return false;
                        }

                        return { idcursos, nombreCurso, idDocente };
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch('../../php_basesDatos/obtenerCursos.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: new URLSearchParams({
                                action: 'editCourse',
                                idcursos: result.value.idcursos,
                                nombreCurso: result.value.nombreCurso,
                                idDocente: result.value.idDocente
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('¡Curso actualizado!', '', 'success');
                                cargarCursos();
                            } else {
                                Swal.fire('Error', data.error, 'error');
                            }
                        });
                    }
                });
            })
            .catch(error => console.error('Error al obtener docentes:', error));
    }

    function borrarCurso(event) {
        const cursoRow = event.target.closest('tr');
        const idcursos = cursoRow.dataset.idcursos;

        Swal.fire({
            title: '¿Eliminar este curso?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
        }).then(result => {
            if (result.isConfirmed) {
                fetch('../../php_basesDatos/obtenerCursos.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: 'deleteCourse',
                        idcursos
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Curso eliminado!', '', 'success');
                        cargarCursos();
                    } else {
                        Swal.fire('Error', data.error, 'error');
                    }
                });
            }
        });
    }

    document.getElementById('btnAgregarCurso').addEventListener('click', function(event) {
        // Obtén la lista de docentes desde el servidor
        fetch('../../php_basesDatos/obtenerCursos.php?action=getDocentes')
            .then(response => response.json())
            .then(docentes => {
                const docenteOptions = docentes.map(docente => 
                    `<option value="${docente.idusuarios}">${docente.nombre} ${docente.apellido}</option>`
                ).join('');

                Swal.fire({
                    title: 'Agregar Curso',
                    html: `
                        <input id="nombreCurso" class="swal2-input" placeholder="Nombre del Curso">
                        <select id="idDocente" class="swal2-select">
                            <option value="" selected disabled>Seleccione un docente</option>
                            ${docenteOptions}
                        </select>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Agregar',
                    preConfirm: () => {
                        const nombreCurso = Swal.getPopup().querySelector('#nombreCurso').value;
                        const idDocente = Swal.getPopup().querySelector('#idDocente').value;

                        if (!nombreCurso || !idDocente) {
                            Swal.showValidationMessage('Todos los campos son obligatorios');
                            return false;
                        }

                        return { nombreCurso, idDocente };
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch('../../php_basesDatos/obtenerCursos.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: new URLSearchParams({
                                action: 'addCourse',
                                nombreCurso: result.value.nombreCurso,
                                idDocente: result.value.idDocente
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('¡Curso agregado!', '', 'success');
                                cargarCursos();
                            } else {
                                Swal.fire('Error', data.error, 'error');
                            }
                        });
                    }
                });
            })
            .catch(error => console.error('Error al obtener docentes:', error));
    });

    // Cargar los cursos al iniciar la página
    cargarCursos();
});
    