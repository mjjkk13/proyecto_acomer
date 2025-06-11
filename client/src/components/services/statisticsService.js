const API_URL = import.meta.env.VITE_API_URL || "http://localhost/proyecto_acomer/server/php";

export const fetchStatistics = async () => {
  try {
    const response = await fetch(`${API_URL}/estadisticas.php`);

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const data = await response.json();

    // Ajustamos las claves según el JSON real
    return {
      daily: (data.diario || []).map(item => ({
        fecha: item.fecha,
        tipo_menu: item.tipomenu,
        totalEstudiantes: parseInt(item.cantidad, 10),
      })),
      weekly: (data.semanal || []).map(item => ({
        semana: item.fecha.split("-W")[1],
        mes: item.fecha.split("-")[0] + "-" + item.fecha.split("-")[1].replace("W", ""),
        tipo_menu: item.tipomenu,
        totalEstudiantes: parseInt(item.cantidad, 10),
      })),
      monthly: (data.mensual || []).map(item => ({
        nombre_mes: item.fecha,
        tipo_menu: item.tipomenu,
        totalEstudiantes: parseInt(item.cantidad, 10),
      })),
    };
  } catch (error) {
    console.error("Error fetching statistics:", error);
    throw error;
  }
};

