const tablaCodigos = document.getElementById('tablaCodigos');

// Función para cargar los códigos QR registrados
function cargarCodigosQR() {
    fetch('../../php_basesDatos/CargarCodigosqr.php')
        .then(response => response.json())
        .then(data => {
            console.log('Códigos QR:', data);
            tablaCodigos.innerHTML = ''; // Limpiamos la tabla antes de actualizar

            data.forEach((codigo, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${codigo.fecha_hora}</td>
                    <td><img src="../${codigo.imagen}" alt="Código QR" class="img-qr"></td>
                    <td><button class="btn btn-danger" onclick="eliminarCodigo('${codigo.imagen}')"><i class="fas fa-trash-alt"></i></button></td>
                `;
                tablaCodigos.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Función para eliminar un código QR
function eliminarCodigo(imagen) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../../php_basesDatos/EliminarCodigoqr.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ imagen: imagen })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminado', 'El código QR ha sido eliminado.', 'success');
                    cargarCodigosQR(); // Recargar la tabla después de eliminar
                } else {
                    Swal.fire('Error', 'Hubo un problema al eliminar el código QR.', 'error');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
}

// Cargar los códigos QR al cargar la página
cargarCodigosQR();
