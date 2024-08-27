document.getElementById('formAgregarEstudiante').addEventListener('submit', function(event) {
    event.preventDefault(); 
    agregarEstudiante();
});
function agregarEstudiante() {
    const nombreEstudiante = document.getElementById('nombreEstudiante').value.trim();
    const apellidoEstudiante = document.getElementById('apellidoEstudiante').value.trim();
    const asistio = document.getElementById('asistio').value;

    if (!nombreEstudiante || !apellidoEstudiante || asistio === "") {
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
                'asistio': asistio
            })
        })
        .then(response => response.text())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Actualización Completa',
                text: data,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(() => {
                // Recargar la página después de mostrar la alerta de éxito
                location.reload();
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
