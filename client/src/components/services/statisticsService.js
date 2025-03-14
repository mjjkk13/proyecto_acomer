export const fetchStatistics = async () => {
    try {
      const response = await fetch(
        "http://localhost/proyecto_acomer/server/php/estadisticas.php"
      );
  
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
  
      const data = await response.json();
      return {
        weekly: data.weekly || [],
        monthly: data.monthly || [],
      };
    } catch (error) {
      console.error("Error fetching statistics:", error);
      throw error; // Lanzar el error para manejarlo en el componente
    }
  };