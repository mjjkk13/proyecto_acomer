import { useState, useEffect } from "react";
import { Bar } from "react-chartjs-2";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";
import { motion } from "framer-motion";

// Registrar componentes de Chart.js
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const ChartView = () => {
  const [weeklyData, setWeeklyData] = useState([]);
  const [monthlyData, setMonthlyData] = useState([]);
  const [showMonthlyStatistics, setShowMonthlyStatistics] = useState(false);
  const [error, setError] = useState(null);

  // Obtener datos del backend
  useEffect(() => {
    fetch("http://localhost/proyecto_acomer/server/php/estadisticas.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        setWeeklyData(data.weekly);
        setMonthlyData(data.monthly);
      })
      .catch((error) => {
        if (error.message === "permission error") {
          setError("No tienes permiso para acceder a estos datos.");
        } else {
          setError("Error al cargar los datos.");
        }
        console.error("Error al cargar los datos:", error);
      });
  }, []);

  // Configuración de datos para el gráfico semanal
  const weeklyChartData = {
    labels: weeklyData.map((item) => `Semana ${item.semana} (${item.mes})`),
    datasets: [
      {
        label: "Estudiantes Asistieron (Semanal)",
        data: weeklyData.map((item) => item.totalEstudiantes),
        backgroundColor: "rgba(54, 162, 235, 0.6)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 1,
      },
    ],
  };

  // Configuración de datos para el gráfico mensual
  const monthlyChartData = {
    labels: monthlyData.map((item) => item.nombre_mes),
    datasets: [
      {
        label: "Estudiantes Asistieron (Mensual)",
        data: monthlyData.map((item) => item.totalEstudiantes),
        backgroundColor: "rgba(255, 99, 132, 0.6)",
        borderColor: "rgba(255, 99, 132, 1)",
        borderWidth: 1,
      },
    ],
  };

  return (
    <div className="flex flex-col justify-center items-center min-h-screen bg-gray-200">
      <div className="p-10 bg-white rounded shadow-lg w-full max-w-4xl border-2 border-gray-300">
        <h1 className="text-xl font-bold mb-4 text-center">Estadísticas</h1>

        {error && <p className="text-red-500 text-center">{error}</p>}

        <motion.div
          initial={{ opacity: 0, y: 50 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
          key={showMonthlyStatistics ? "monthly" : "weekly"}
        >
          <h2 className="text-lg font-semibold mb-2 text-center">
            {showMonthlyStatistics ? "Asistencias Mensuales" : "Asistencias Semanales"}
          </h2>
          <div className="h-64 w-full rounded p-4">
            <Bar
              data={showMonthlyStatistics ? monthlyChartData : weeklyChartData}
              options={{ responsive: true }}
            />
          </div>
        </motion.div>

        <div className="flex justify-center">
          <button
            onClick={() => setShowMonthlyStatistics(!showMonthlyStatistics)}
            className="mt-4 mb-4 px-6 py-3 btn-primary text-white bg-cyan-700 border-2 border-cyan-700 rounded"
          >
            {showMonthlyStatistics ? "Mostrar Estadísticas Semanales" : "Mostrar Estadísticas Mensuales"}
          </button>
        </div>
      </div>
    </div>
  );
};

export default ChartView;