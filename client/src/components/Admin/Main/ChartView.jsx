import { useState, useEffect } from "react";
import { Bar, Doughnut } from "react-chartjs-2";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";
import { motion } from "framer-motion";
import { fetchStatistics } from "../../services/statisticsService";
import { handleDownloadExcel } from "../../services/excelService";

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend
);

const ChartView = () => {
  const [dailyData, setDailyData] = useState([]);
  const [weeklyData, setWeeklyData] = useState([]);
  const [monthlyData, setMonthlyData] = useState([]);
  const [selectedView, setSelectedView] = useState("daily");
  const [selectedMenu, setSelectedMenu] = useState("Todos");
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
        setError(
          error.message === "permission error"
            ? "No tienes permiso para acceder a estos datos."
            : "Error al cargar los datos."
        );
      } finally {
        setIsLoading(false);
      }
    };
    getData();
  }, []);

  const formatSpanishDate = (fechaStr) => {
    const date = new Date(fechaStr);
    return date.toLocaleDateString("es-ES", {
      weekday: "long",
      day: "2-digit",
      month: "long",
      year: "numeric",
    });
  };

  const filterByMenu = (data) => {
    if (selectedMenu === "Todos") return data;
    return data.filter((item) => item.tipo_menu === selectedMenu);
  };

  const generateChartData = (data, label) => ({
    labels: data.map((item) =>
      selectedView === "daily"
        ? `${formatSpanishDate(item.fecha)}`
            .replace(/^\w/, (c) => c.toUpperCase())
            .replace(", ", " (") + ")"
        : selectedView === "weekly"
        ? `Semana ${item.semana} (${item.mes})`
        : item.nombre_mes
    ),
    datasets: [
      {
        label: label,
        data: data.map((item) => item.totalEstudiantes),
        backgroundColor: "rgba(75, 192, 192, 0.6)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
      },
    ],
  });

  const getMenuProportionData = (data) => {
    const counts = { Desayuno: 0, Almuerzo: 0, Refrigerio: 0 };
    data.forEach((item) => {
      if (counts[item.tipo_menu] !== undefined) {
        counts[item.tipo_menu] += item.totalEstudiantes;
      }
    });
    return {
      labels: Object.keys(counts),
      datasets: [
        {
          label: "Proporción por tipo de menú",
          data: Object.values(counts),
          backgroundColor: [
            "rgba(255, 99, 132, 0.7)",
            "rgba(54, 162, 235, 0.7)",
            "rgba(255, 206, 86, 0.7)",
          ],
          borderWidth: 1,
        },
      ],
    };
  };

  const selectedDataMap = {
    daily: filterByMenu(dailyData),
    weekly: filterByMenu(weeklyData),
    monthly: filterByMenu(monthlyData),
  };

  const chartTitleMap = {
    daily: "Asistencias Diarias",
    weekly: "Asistencias Semanales",
    monthly: "Asistencias Mensuales",
  };

  return (
  <div className="flex flex-col justify-center items-center min-h-screen bg-gray-100">
    <div className="p-10 bg-white rounded shadow-lg w-full max-w-5xl border border-gray-300">
      <h1 className="text-xl font-bold mb-4 text-center text-gray-800">Estadísticas</h1>

      {error && <p className="text-red-500 text-center">{error}</p>}

      <div className="flex justify-center gap-4 mb-6 flex-wrap">
        <select
          value={selectedMenu}
          onChange={(e) => setSelectedMenu(e.target.value)}
          className="px-4 py-2 border border-gray-300 bg-white text-gray-800 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
        >
          <option value="Todos">Todos</option>
          <option value="Desayuno">Desayuno</option>
          <option value="Almuerzo">Almuerzo</option>
          <option value="Refrigerio">Refrigerio</option>
        </select>

        {["daily", "weekly", "monthly"].map((view) => (
          <button
            key={view}
            onClick={() => setSelectedView(view)}
            className={`px-4 py-2 rounded text-white ${
              selectedView === view
                ? "bg-teal-600"
                : "bg-gray-500 hover:bg-teal-700"
            }`}
          >
            {view === "daily" ? "Diario" : view === "weekly" ? "Semanal" : "Mensual"}
          </button>
        ))}
      </div>

      {isLoading ? (
        <p className="text-center">Cargando datos...</p>
      ) : (
        <motion.div
          initial={{ opacity: 0, y: 50 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
          key={`${selectedView}-${selectedMenu}`}
        >
          <h2 className="text-lg font-semibold mb-2 text-center text-gray-900">
            {chartTitleMap[selectedView]} – {selectedMenu}
          </h2>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="h-64 w-full p-4 border rounded bg-gray-50">
              <Bar
                data={generateChartData(selectedDataMap[selectedView], "Estudiantes Asistieron")}
                options={{ responsive: true }}
              />
            </div>

            <div className="h-64 w-full p-4 border rounded bg-gray-50">
              <Doughnut
                data={getMenuProportionData(
                  selectedView === "daily"
                    ? dailyData
                    : selectedView === "weekly"
                    ? weeklyData
                    : monthlyData
                )}
                options={{ responsive: true }}
              />
            </div>
          </div>
        </motion.div>
      )}
      <div className="flex justify-center mt-6">
        <button
          onClick={handleDownloadExcel}
          className="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow"
        >
          Descargar Excel
        </button>
      </div>
    </div>
  </div>
);

};

export default ChartView;
