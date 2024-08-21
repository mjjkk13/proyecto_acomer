// archivo: ../../js/estadisticas.js

document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleView');
    const dailyView = document.getElementById('dailyView');
    const weeklyView = document.getElementById('weeklyView');
  
    const dailyStats = [
      { day: 'Lunes', count: 100 },
      { day: 'Martes', count: 120 },
      { day: 'Miércoles', count: 110 },
      { day: 'Jueves', count: 130 },
      { day: 'Viernes', count: 90 },
    ];
  
    const weeklyStats = [
      { week: 'Semana 1', count: 550 },
      { week: 'Semana 2', count: 600 },
      { week: 'Semana 3', count: 590 },
      { week: 'Semana 4', count: 580 },
    ];
  
    function renderDailyStats() {
      const tbody = document.getElementById('dailyStats');
      tbody.innerHTML = '';
      dailyStats.forEach(stat => {
        const row = document.createElement('tr');
        row.innerHTML = `<td>${stat.day}</td><td>${stat.count}</td>`;
        tbody.appendChild(row);
      });
    }
  
    function renderWeeklyStats() {
      const tbody = document.getElementById('weeklyStats');
      tbody.innerHTML = '';
      weeklyStats.forEach(stat => {
        const row = document.createElement('tr');
        row.innerHTML = `<td>${stat.week}</td><td>${stat.count}</td>`;
        tbody.appendChild(row);
      });
    }
  
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
  
    // Render inicial
    renderDailyStats();
    renderWeeklyStats();
  });
  