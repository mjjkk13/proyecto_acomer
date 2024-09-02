document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar los cursos desde el servidor
    function cargarCursos() {
        fetch('../../php_basesDatos/obtenerCursos.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data); // Verifica la estructura del JSON
                if (!Array.isArray(data)) {
                    throw new Error('Data is not an array');
                }

                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = ''; // Limpiar contenido anterior

                // Crear un fragmento para mejorar el rendimiento
                const fragment = document.createDocumentFragment();

                data.forEach(curso => {
                    const row = document.createElement('tr');
                    const cursoCell = document.createElement('td');
                    cursoCell.textContent = curso.nombrecurso; // Ajusta según la clave en el JSON
                    const directorCell = document.createElement('td');
                    directorCell.textContent = curso.nombredocente + ' ' + curso.apellidodocente; // Ajusta según la clave en el JSON
                    row.appendChild(cursoCell);
                    row.appendChild(directorCell);
                    fragment.appendChild(row);
                });

                // Añadir todas las filas al tbody de una vez
                tbody.appendChild(fragment);
            })
            .catch(error => {
                console.error('Error al cargar los cursos:', error);
                // Opcional: mostrar un mensaje de error al usuario
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '<tr><td colspan="2">No se pudieron cargar los cursos. Intenta de nuevo más tarde.</td></tr>';
            });
    }

    // Cargar los cursos cuando la página se haya cargado
    cargarCursos();
});
