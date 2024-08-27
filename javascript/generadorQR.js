document.addEventListener('DOMContentLoaded', function () {
    const btnGenerarQR = document.getElementById('btnGenerarQR');

    btnGenerarQR.addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('.asistencia-checkbox:checked');
        if (checkboxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No hay estudiantes seleccionados',
                text: 'Por favor, selecciona al menos un estudiante para generar el código QR.'
            });
            return;
        }

        const estudiantesSeleccionados = Array.from(checkboxes).map(checkbox => {
            const row = checkbox.closest('tr');
            return {
                idAlumnos: checkbox.dataset.id,
                nombreAlumnos: row.cells[0].textContent,  // Suponiendo que el nombre está en la primera columna
                apellidosAlumnos: row.cells[1].textContent,  // Suponiendo que el apellido está en la segunda columna
                nombreCurso: row.cells[2].textContent,  // Suponiendo que el nombre del curso está en la tercera columna
                nombreDocente: row.cells[3].textContent,  // Suponiendo que el nombre del docente está en la cuarta columna
                asistio: checkbox.checked ? 1 : 0
            };
        });

        // Enviar la información de asistencia
        Promise.all(estudiantesSeleccionados.map(estudiante => {
            return fetch('../../php_basesDatos/GuardarAsistencia.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `idAlumnos=${estudiante.idAlumnos}&nombreAlumnos=${encodeURIComponent(estudiante.nombreAlumnos)}&apellidosAlumnos=${encodeURIComponent(estudiante.apellidosAlumnos)}&nombreCurso=${encodeURIComponent(estudiante.nombreCurso)}&nombreDocente=${encodeURIComponent(estudiante.nombreDocente)}&asistio=${estudiante.asistio}`
            }).then(response => response.text());
        }))
        .then(results => {
            console.log('Resultados de actualización de asistencia:', results);

            // Después de guardar la asistencia, generar el código QR
            return fetch('../../php_basesDatos/GenerarQR.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `estudiantes=${JSON.stringify(estudiantesSeleccionados)}`
            });
        })
        .then(response => {
            return response.text().then(text => {
                console.log('Respuesta del servidor:', text); // Añadido para depuración
                try {
                    const data = JSON.parse(text);
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Código QR generado correctamente',
                            text: data.message,
                            imageUrl: `../${data.qr_image}`,
                            imageHeight: 100,
                            imageAlt: 'Código QR generado'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                } catch (e) {
                    console.error('Error al analizar la respuesta del servidor:', e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al analizar la respuesta del servidor. Inténtalo de nuevo más tarde.'
                    });
                }
            });
        })
        .catch(error => {
            console.error('Error al generar el código QR:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al generar el código QR. Inténtalo de nuevo más tarde.'
            });
        });
    });

    // Cargar los estudiantes en la tabla
    const tablaEstudiantes = document.getElementById('tablaEstudiantes');

    fetch('../../php_basesDatos/CargarEstudiantes.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(estudiante => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${estudiante.nombreAlumno}</td>
                    <td>${estudiante.apellidoAlumno}</td>
                    <td>${estudiante.nombrecurso}</td>
                    <td>${estudiante.nombreDocente}</td>
                    <td>
                        <input type="checkbox" class="asistencia-checkbox" data-id="${estudiante.idalumnos}">
                    </td>
                `;
                tablaEstudiantes.appendChild(row);
            });
        })
        .catch(error => console.error('Error al cargar estudiantes:', error));
});
