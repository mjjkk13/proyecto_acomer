document.addEventListener('DOMContentLoaded', function () {
    const btnGenerarQR = document.getElementById('btnGenerarQR');
  
    btnGenerarQR.addEventListener('click', function () {
        fetch('../../php_basesDatos/GenerarQR.php')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Código QR generado correctamente',
                        text: data.message,
                        imageUrl: '../' + data.qr_image,
                        imageHeight: 100,
                        imageAlt: 'Código QR generado'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // Reload the page
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al generar el código QR. Inténtalo de nuevo más tarde.'
                });
            });
    });
  
    const tablaEstudiantes = document.getElementById('tablaEstudiantes');
  
    fetch('../../php_basesDatos/CargarEstudiantes.php')
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Estudiantes:', data);
            data.forEach(estudiante => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${estudiante.idAlumnos}</td>
                    <td>${estudiante.identificacionAlumnos}</td>
                    <td>${estudiante.tipodocumentoAlumnos}</td>
                    <td>${estudiante.nombreAlumnos}</td>
                    <td>${estudiante.apellidosAlumnos}</td>
                    <td>
                        <input type="checkbox" data-id="${estudiante.idAlumnos}" class="asistencia-checkbox">
                    </td>
                `;
                tablaEstudiantes.appendChild(row);
            });
  
            document.querySelectorAll('.asistencia-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const idEstudiante = this.getAttribute('data-id');
                    const asistio = this.checked ? 1 : 0;
  
                    fetch('../../DataBase/guardar_asistencia.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `idEstudiante=${idEstudiante}&asistio=${asistio}`
                    })
                        .then(response => response.text())
                        .then(data => {
                            console.log('Guardar asistencia response:', data);
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        })
        .catch(error => console.error('Error:', error));
});
