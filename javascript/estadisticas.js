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

  // Cargar datos desde el PHP
  fetch('../../php_basesDatos/estadisticas.php')
      .then(response => response.json())
      .then(data => {
          // Preparar datos diarios
          const dailyLabels = data.daily.map(entry => entry.fecha);
          const dailyCounts = data.daily.map(entry => entry.estudiantesqasistieron);

          const dailyData = {
              labels: dailyLabels,
              datasets: [{
                  label: 'Cantidad de Estudiantes',
                  data: dailyCounts,
                  backgroundColor: 'rgba(54, 162, 235, 0.2)',
                  borderColor: 'rgba(54, 162, 235, 1)',
                  borderWidth: 1
              }]
          };

          // Crear gráfico de barras para el ingreso diario
          const ctxDaily = document.getElementById('dailyChart').getContext('2d');
          new Chart(ctxDaily, {
              type: 'bar',
              data: dailyData,
              options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  scales: {
                      y: {
                          beginAtZero: true
                      }
                  }
              }
          });

          // Preparar datos semanales
          const weeklyLabels = data.weekly.map(entry => 'Semana ' + entry.semana);
          const weeklyCounts = data.weekly.map(entry => entry.totalEstudiantes);

          const weeklyData = {
              labels: weeklyLabels,
              datasets: [{
                  label: 'Cantidad de Estudiantes',
                  data: weeklyCounts,
                  fill: false,
                  borderColor: 'rgba(75, 192, 192, 1)',
                  tension: 0.1
              }]
          };

          // Crear gráfico lineal para el ingreso semanal
          const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
          new Chart(ctxWeekly, {
              type: 'line',
              data: weeklyData,
              options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  scales: {
                      y: {
                          beginAtZero: true
                      }
                  }
              }
          });
      })
      .catch(error => console.error('Error al cargar los datos:', error));
});
