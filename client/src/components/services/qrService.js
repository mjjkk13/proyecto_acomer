const API_URL = 'http://localhost/proyecto_acomer/server/php';

const getQRCodes = async () => {
  const response = await fetch(`${API_URL}/CargarCodigosqr.php`);
  if (!response.ok) {
    throw new Error('Error al obtener los códigos QR');
  }
  const data = await response.json();
  return data;
};

export default {
  getQRCodes,
};
