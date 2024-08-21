async function fetchData(mealType) {
    try {
      // Reemplaza 'localhost/Proyecto/php_basesDatos/menu.php' con la URL completa
      const url = `http://localhost/Proyecto/php_basesDatos/menu.php?mealType=${encodeURIComponent(mealType)}`;
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const data = await response.json();
      console.log(data); // Para depurar: muestra los datos en la consola
      return data;
    } catch (error) {
      console.error('Error fetching data:', error);
      Swal.fire({
        title: 'Error',
        text: 'No se pudieron obtener los datos. Por favor, intente de nuevo más tarde.',
        icon: 'error'
      });
    }
  }
  
  
  function showData(title, data) {
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
  
    Swal.fire({
      title: title,
      html: table,
      width: '600px'
    });
  }
  
  document.getElementById("desayunoBox").addEventListener("click", async function() {
    const data = await fetchData('desayuno');
    if (data && data.length > 0) {
      showData('Desayuno del día', data);
    } else {
      Swal.fire({
        title: 'Error',
        text: 'No se encontraron datos para el menú seleccionado.',
        icon: 'error'
      });
    }
  });
  
  document.getElementById("almuerzoBox").addEventListener("click", async function() {
    const data = await fetchData('almuerzo');
    if (data && data.length > 0) {
      showData('Almuerzo del día', data);
    } else {
      Swal.fire({
        title: 'Error',
        text: 'No se encontraron datos para el menú seleccionado.',
        icon: 'error'
      });
    }
  });
  
  document.getElementById("refrigerioBox").addEventListener("click", async function() {
    const data = await fetchData('refrigerio');
    if (data && data.length > 0) {
      showData('Refrigerio del día', data);
    } else {
      Swal.fire({
        title: 'Error',
        text: 'No se encontraron datos para el menú seleccionado.',
        icon: 'error'
      });
    }
  });
  