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
        Swal.fire({
            icon: 'success',
            title: 'Estudiante agregado',
            text: 'El estudiante se ha agregado correctamente.',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    }
}
