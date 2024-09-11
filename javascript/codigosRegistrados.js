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
                    <td>${codigo.nombrecurso}</td> 
                    <td><img src="../${codigo.imagen}" alt="Código QR" class="img-qr"></td> 
                `;
                tablaCodigos.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Cargar los códigos QR al cargar la página
cargarCodigosQR();
