document.getElementById('formAgregarEstudiante').addEventListener('submit', function(event) {
    event.preventDefault(); 
    agregarEstudiante();
});

function agregarEstudiante() {
    const nombreEstudiante = document.getElementById('nombreEstudiante').value.trim();
    const apellidoEstudiante = document.getElementById('apellidoEstudiante').value.trim();
    const estado = document.getElementById('asistio').value;

    if (!nombreEstudiante || !apellidoEstudiante || estado === "") {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, completa todos los campos antes de continuar.',
            showClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    } else {
        fetch('../../php_basesDatos/AgregarEstudiante.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'nombreEstudiante': nombreEstudiante,
                'apellidoEstudiante': apellidoEstudiante,
                'estado': estado
            })
        })
        .then(response => response.json()) // Asegúrate de tratar la respuesta como JSON
        .then(data => {
            Swal.fire({
                icon: data.status === 'success' ? 'success' : 'error',
                title: data.status === 'success' ? 'Actualización Completa' : 'Error',
                text: data.message,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(() => {
                if (data.status === 'success') {
                    location.reload();
                }
            });
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al procesar la solicitud: ' + error.message,
                showClass: {
                    popup: 'animate__animated animate__shakeX'
                }
            });
        });
    }
}
