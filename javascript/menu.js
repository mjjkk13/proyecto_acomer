function fetchSimulatedData(mealType) {
  // Datos simulados para cada tipo de menú
  const simulatedData = {
    desayuno: [
      { nombreMenu: 'Desayuno ', diaMenu: 'Lunes', caracteristicasMenu: 'Café, Tostadas, Fruta' },
      { nombreMenu: 'Desayuno ', diaMenu: 'Martes', caracteristicasMenu: 'Té, Yogur, Cereal' }
    ],
    almuerzo: [
      { nombreMenu: 'Almuerzo ', diaMenu: 'Lunes', caracteristicasMenu: 'Pollo, Ensalada, Arroz' },
      { nombreMenu: 'Almuerzo ', diaMenu: 'Martes', caracteristicasMenu: 'Carne Asada, Papas, Verduras' }
    ],
    refrigerio: [
      { nombreMenu: 'Refrigerio ', diaMenu: 'Lunes', caracteristicasMenu: 'Jugo Natural, Barras de Cereal' },
      { nombreMenu: 'Refrigerio ', diaMenu: 'Martes', caracteristicasMenu: 'Batido, Frutas Secas' }
    ]
  };
  
  // Retorna los datos correspondientes al tipo de menú
  return simulatedData[mealType] || [];
}

function showData(title, data) {
  // Genera la tabla con los datos proporcionados
  const table = `
    <table class="table">
      <thead>
        <tr>
          <th>Nombre del Menú</th>
          <th>Día</th>
          <th>Características</th>
        </tr>
      </thead>
      <tbody>
        ${data.map(item => `
          <tr>
            <td>${item.nombreMenu}</td>
            <td>${item.diaMenu}</td>
            <td>${item.caracteristicasMenu}</td>
          </tr>
        `).join('')}
      </tbody>
    </table>
  `;

  // Muestra la tabla en un SweetAlert
  Swal.fire({
    title: title,
    html: table,
    width: '600px'
  });
}

// Event listener para el botón de Desayuno
document.getElementById("desayunoBox").addEventListener("click", function() {
  const data = fetchSimulatedData('desayuno');
  showData('Desayuno del día', data);
});

// Event listener para el botón de Almuerzo
document.getElementById("almuerzoBox").addEventListener("click", function() {
  const data = fetchSimulatedData('almuerzo');
  showData('Almuerzo del día', data);
});

// Event listener para el botón de Refrigerio
document.getElementById("refrigerioBox").addEventListener("click", function() {
  const data = fetchSimulatedData('refrigerio');
  showData('Refrigerio del día', data);
});
