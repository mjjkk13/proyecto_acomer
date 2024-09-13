document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.querySelector("table tbody");

    fetch("../../php_basesDatos/cargarDatosPersonales.php")
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          console.error("Error: " + data.error);
        } else {
          // Definir los campos que se van a mostrar
          const campos = {
            "idusuarios": "ID Usuario",
            "nombre": "Nombre",
            "apellido": "Apellido",
            "email": "Email",
            "telefono": "Telefono",
            "direccion": "Direccion"
          };

          // Crear una fila por cada campo
          for (const [campo, etiqueta] of Object.entries(campos)) {
            const tr = document.createElement("tr");

            // Columna de etiqueta del campo
            const tdCampo = document.createElement("td");
            tdCampo.textContent = etiqueta;
            tdCampo.style.fontWeight = 'bold'; // Negrita para los títulos
            tr.appendChild(tdCampo);

            // Columna de valor del campo
            const tdValor = document.createElement("td");
            tdValor.textContent = data[campo]; // Mostrar el valor del campo
            tr.appendChild(tdValor);

            // Añadir la fila a la tabla
            tbody.appendChild(tr);
          }
        }
      })
      .catch(error => console.error("Error en la solicitud: ", error));
});
