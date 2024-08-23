// archivo: ../../js/estadisticas.js

document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleView');
    const dailyView = document.getElementById('dailyView');
    const weeklyView = document.getElementById('weeklyView');
  
  
    toggleButton.addEventListener('click', () => {
      if (dailyView.style.display === 'none') {
        dailyView.style.display = '';
        weeklyView.style.display = 'none';
        toggleButton.textContent = 'Mostrar Ingreso Semanal';
      } else {
        dailyView.style.display = 'none';
        weeklyView.style.display = '';
        toggleButton.textContent = 'Mostrar Ingreso Diario';
      }
    });
   // Ejemplo de datos, estos datos deben reemplazarse con los reales de la tabla
const dailyData = {
  labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
  datasets: [{
    label: 'Cantidad de Estudiantes',
    data: [120, 150, 180, 200, 250, 300, 350],
    backgroundColor: 'rgba(54, 162, 235, 0.2)',
    borderColor: 'rgba(54, 162, 235, 1)',
    borderWidth: 1
  }]
};

const weeklyData = {
  labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
  datasets: [{
    label: 'Cantidad de Estudiantes',
    data: [1000, 1250, 1300, 1500],
    fill: false,
    borderColor: 'rgba(75, 192, 192, 1)',
    tension: 0.1
  }]
};

  // Crear gráfico de barras para el ingreso diario
const ctxDaily = document.getElementById('dailyChart').getContext('2d');
const dailyChart = new Chart(ctxDaily, {
  type: 'bar',
  data: dailyData,
  options: {
    responsive: true,
    maintainAspectRatio: false,  // Permitir que el gráfico ocupe todo el espacio
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

// Crear gráfico lineal para el ingreso semanal
const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
const weeklyChart = new Chart(ctxWeekly, {
  type: 'line',
  data: weeklyData,
  options: {
    responsive: true,
    maintainAspectRatio: false,  // Permitir que el gráfico ocupe todo el espacio
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
    // Render inicial
    renderDailyStats();
    renderWeeklyStats();
  });
  