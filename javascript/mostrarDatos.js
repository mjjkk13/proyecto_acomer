document.addEventListener("DOMContentLoaded", function () {
  const tbody = document.querySelector("table tbody");
  const btnActualizar = document.getElementById("btnActualizar");
  let isEditing = false;

  // Función para cargar los datos personales
  function cargarDatosPersonales() {
      fetch("../../php_basesDatos/cargarDatosPersonales.php", {
          method: "GET"
      })
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
                  "telefono": "Teléfono",
                  "direccion": "Dirección"
              };

              // Limpiar el contenido de la tabla antes de cargar
              tbody.innerHTML = "";

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
                  tdValor.setAttribute("data-campo", campo); // Guardar el nombre del campo
                  tr.appendChild(tdValor);

                  // Añadir la fila a la tabla
                  tbody.appendChild(tr);
              }
          }
      })
      .catch(error => console.error("Error en la solicitud: ", error));
  }

  // Cargar los datos personales al cargar la página
  cargarDatosPersonales();

  // Escuchar el evento click en el botón de actualizar
  btnActualizar.addEventListener("click", function () {
      if (!isEditing) {
          // Cambiar las celdas a campos de entrada (input) para editar
          tbody.querySelectorAll("td[data-campo]").forEach(td => {
              const campo = td.getAttribute("data-campo");
              const valor = td.textContent;

              // Crear un input con el valor actual del campo
              const input = document.createElement("input");
              input.type = "text";
              input.name = campo;
              input.value = valor;
              td.textContent = '';
              td.appendChild(input);
          });

          // Cambiar el texto del botón a "Guardar Cambios"
          btnActualizar.textContent = "Guardar Cambios";
          isEditing = true;
      } else {
          // Guardar los datos modificados
          const datosActualizados = {};
          tbody.querySelectorAll("td[data-campo]").forEach(td => {
              const input = td.querySelector("input");
              datosActualizados[input.name] = input.value; // Recoger los valores de los inputs
          });

          // Enviar los datos actualizados al servidor usando POST
          fetch("../../php_basesDatos/cargarDatosPersonales.php", {
              method: "POST",
              headers: {
                  "Content-Type": "application/json"
              },
              body: JSON.stringify(datosActualizados) // Enviar los datos en formato JSON
          })
          .then(response => response.json())
          .then(result => {
              if (result.success) {
                  alert("Datos actualizados exitosamente.");
                  location.reload(); // Recargar la página para mostrar los nuevos datos
              } else {
                  console.error("Error: " + result.error);
              }
          })
          .catch(error => console.error("Error en la solicitud: ", error));

          // Cambiar el texto del botón a "Actualizar Datos"
          btnActualizar.textContent = "Actualizar Datos";
          isEditing = false;
      }
  });
});
