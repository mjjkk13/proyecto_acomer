export const fetchStatistics = async () => {
  try {
    const response = await fetch(`${API_URL}/estadisticas.php`);
 
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const data = await response.json();
    return {
      daily: data.daily || [],
      weekly: data.weekly || [],
      monthly: data.monthly || [],
    };
  } catch (error) {
    console.error("Error fetching statistics:", error);
    throw error;
  }
};
