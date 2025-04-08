const API_URL = 'http://localhost/proyecto_acomer/server/php';

// Servicio para cargar todos los códigos QR escaneados
export const getQRCodes = async () => {
  const response = await fetch(`${API_URL}/CargarCodigosqr.php`);
  if (!response.ok) {
    throw new Error('Error al obtener los códigos QR');
  }
  const data = await response.json();
  return data;
};

// Servicio para cargar solo la fecha y contenido del QR desde qrescaneados
export const fetchQRScans = async () => {
  try {
    const response = await fetch(`${API_URL}/getQRScans.php`);
    if (!response.ok) {
      throw new Error("Error al obtener los escaneos QR");
    }
    return await response.json();
  } catch (error) {
    console.error("Error en fetchQRScans:", error);
    return [];
  }
};