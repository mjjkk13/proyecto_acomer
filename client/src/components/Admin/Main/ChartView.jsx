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
import { fetchStatistics } from "../../services/statisticsService";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const ChartView = () => {
  const [dailyData, setDailyData] = useState([]);
  const [weeklyData, setWeeklyData] = useState([]);
  const [monthlyData, setMonthlyData] = useState([]);
  const [selectedView, setSelectedView] = useState("daily");
  const [error, setError] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const getData = async () => {
      try {
        const data = await fetchStatistics();
        setDailyData(data?.daily || []);
        setWeeklyData(data?.weekly || []);
        setMonthlyData(data?.monthly || []);
      } catch (error) {
        console.error("Error al cargar los datos:", error);
        setError(
          error.message === "permission error"
            ? "No tienes permiso para acceder a estos datos."
            : "Error al cargar los datos. Por favor, intenta más tarde."
        );
      } finally {
        setIsLoading(false);
      }
    };

    getData();
  }, []);

  // Formato de fecha con día de la semana (en español)
  const formatSpanishDate = (fechaStr) => {
    const date = new Date(fechaStr);
    return date.toLocaleDateString("es-ES", {
      weekday: "long",
      day: "2-digit",
      month: "long",
      year: "numeric",
    });
  };

  const dailyChartData = {
    labels: dailyData?.map((item) =>
      `${formatSpanishDate(item.fecha)}`
        .replace(/^\w/, (c) => c.toUpperCase())
        .replace(", ", " (") + ")"
    ) || [],
    datasets: [
      {
        label: "Estudiantes Asistieron (Diario)",
        data: dailyData?.map((item) => item.totalEstudiantes) || [],
        backgroundColor: "rgba(75, 192, 192, 0.6)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
      },
    ],
  };

  const weeklyChartData = {
    labels:
      weeklyData?.map((item) => `Semana ${item.semana} (${item.mes})`) || [],
    datasets: [
      {
        label: "Estudiantes Asistieron (Semanal)",
        data: weeklyData?.map((item) => item.totalEstudiantes) || [],
        backgroundColor: "rgba(54, 162, 235, 0.6)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 1,
      },
    ],
  };

  const monthlyChartData = {
    labels: monthlyData?.map((item) => item.nombre_mes) || [],
    datasets: [
      {
        label: "Estudiantes Asistieron (Mensual)",
        data: monthlyData?.map((item) => item.totalEstudiantes) || [],
        backgroundColor: "rgba(255, 99, 132, 0.6)",
        borderColor: "rgba(255, 99, 132, 1)",
        borderWidth: 1,
      },
    ],
  };

  const chartTitleMap = {
    daily: "Asistencias Diarias",
    weekly: "Asistencias Semanales",
    monthly: "Asistencias Mensuales",
  };

  const chartDataMap = {
    daily: dailyChartData,
    weekly: weeklyChartData,
    monthly: monthlyChartData,
  };

  return (
    <div className="flex flex-col justify-center items-center min-h-screen bg-gray-200">
      <div className="p-10 bg-white rounded shadow-lg w-full max-w-4xl border-2 border-gray-300">
        <h1 className="text-xl font-bold mb-4 text-center text-gray-800">Estadísticas</h1>

        {error && <p className="text-red-500 text-center">{error}</p>}

        {isLoading ? (
          <p className="text-center">Cargando datos...</p>
        ) : (
          <motion.div
            initial={{ opacity: 0, y: 50 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            key={selectedView}
          >
            <h2 className="text-lg font-semibold mb-2 text-center text-gray-900">
              {chartTitleMap[selectedView]}
            </h2>
            <div className="h-64 w-full rounded p-4">
              <Bar data={chartDataMap[selectedView]} options={{ responsive: true }} />
            </div>
          </motion.div>
        )}

        <div className="flex justify-center gap-4 mt-6">
          <button
            onClick={() => setSelectedView("daily")}
            className={`px-4 py-2 rounded text-white ${
              selectedView === "daily"
                ? "bg-teal-600 border-teal-600"
                : "bg-gray-500 border-gray-500 hover:bg-teal-700"
            } border-2 transition-colors`}
          >
            Diario
          </button>
          <button
            onClick={() => setSelectedView("weekly")}
            className={`px-4 py-2 rounded text-white ${
              selectedView === "weekly"
                ? "bg-blue-600 border-blue-600"
                : "bg-gray-500 border-gray-500 hover:bg-blue-700"
            } border-2 transition-colors`}
          >
            Semanal
          </button>
          <button
            onClick={() => setSelectedView("monthly")}
            className={`px-4 py-2 rounded text-white ${
              selectedView === "monthly"
                ? "bg-pink-600 border-pink-600"
                : "bg-gray-500 border-gray-500 hover:bg-pink-700"
            } border-2 transition-colors`}
          >
            Mensual
          </button>
        </div>
      </div>
    </div>
  );
};

export default ChartView;
