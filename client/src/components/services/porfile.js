// datosPersonalesService.js

const API_URL = "http://localhost/proyecto_acomer/server/php/cargarDatosPersonales.php";

/**
 * Obtiene los datos personales del usuario autenticado.
 */
export const getDatosPersonales = async () => {
  try {
    const response = await fetch(API_URL, {
      method: "GET",
      headers: { "Content-Type": "application/json" },
      credentials: "include"
    });
    const data = await response.json();
    if (data.status && data.status === "error") {
      throw new Error(data.message);
    }
    return data;
  } catch (error) {
    console.error("Error fetching datos personales:", error);
    throw error;
  }
};

/**
 * Actualiza los datos personales del usuario.
 * @param {Object} updatedData - Objeto con datos actualizados (debe incluir idusuarios, nombre, apellido, email, teléfono y dirección)
 */
export const updateDatosPersonales = async (updatedData) => {
  try {
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "include",
      body: JSON.stringify(updatedData)
    });
    const result = await response.json();
    if (result.status !== "success") {
      throw new Error(result.message || "Error actualizando datos personales");
    }
    return result;
  } catch (error) {
    console.error("Error updating datos personales:", error);
    throw error;
  }
};
