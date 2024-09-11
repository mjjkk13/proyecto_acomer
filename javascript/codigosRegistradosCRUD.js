const tablaCodigos = document.getElementById('tablaCodigos');

// Función para cargar los códigos QR registrados
function cargarCodigosQR() {
    fetch('../../php_basesDatos/CodigosqrCRUD.php')
        .then(response => response.json())
        .then(data => {
            console.log('Códigos QR:', data);
            tablaCodigos.innerHTML = ''; // Limpiamos la tabla antes de actualizar

            data.forEach((codigo) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${codigo.fechageneracion}</td>
                    <td>${codigo.nombrecurso}</td>
                    <td><img src="../${codigo.codigoqr}" alt="Código QR" class="img-qr"></td>
                    <td>
                        <button class="btn btn-danger" onclick="eliminarCodigo(${codigo.idqrgenerados})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                tablaCodigos.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Función para eliminar un código QR
function eliminarCodigo(idQrGenerados) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../../php_basesDatos/CodigosqrCRUD.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'eliminar': 'true',
                    'idqrgenerados': idQrGenerados
                })
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
