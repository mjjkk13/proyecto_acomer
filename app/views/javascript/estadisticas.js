document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleView');
    const dailyView = document.getElementById('dailyView');
    const weeklyView = document.getElementById('weeklyView');

    if (toggleButton && dailyView && weeklyView) {
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
    }

    fetch('../../controllers/EstadisticasController.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (!data.daily || !data.weekly) {
                throw new Error('Data format is incorrect');
            }

            // Crear gráfico de barras para el ingreso diario
            const ctxDaily = document.getElementById('dailyChart').getContext('2d');
            new Chart(ctxDaily, {
                type: 'bar',
                data: {
                    labels: data.daily.map(item => `${item.fecha} - ${item.dia}`),
                    datasets: [{
                        label: 'Cantidad de Estudiantes',
                        data: data.daily.map(item => item.totalEstudiantes),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
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

            // Crear gráfico lineal para el ingreso semanal
            const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
            new Chart(ctxWeekly, {
                type: 'line',
                data: {
                    labels: data.weekly.map(item => `Semana ${item.semana} - Mes ${item.mes}`),
                    datasets: [{
                        label: 'Cantidad de Estudiantes',
                        data: data.weekly.map(item => item.totalEstudiantes),
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1
                    }]
                },
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
        .catch(error => console.error('Error fetching data:', error));
});
