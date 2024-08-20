document.addEventListener('DOMContentLoaded', function () {
    const btnGenerarQR = document.getElementById('btnGenerarQR');
  
    btnGenerarQR.addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('.asistencia-checkbox');
        let alguienAsistio = false;
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                alguienAsistio = true;
            }
        });

        if (alguienAsistio) {
            Swal.fire({
                icon: 'success',
                title: 'Código QR generado correctamente',
                text: 'El código QR se ha generado exitosamente.',
                imageUrl: '../../qr_codes/qr_all_students_1716501711.png', // Ruta de la imagen
                imageHeight: 100,
                imageAlt: 'Código QR generado'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No hay nadie que haya asistido hoy.'
            });
        }
    });
});
