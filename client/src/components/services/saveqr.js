import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL;

export const saveQRCode = async (qrCode) => {
  try {
    const response = await axios.post(
      `${API_URL}/save_qr.php`,
      { qr_code: qrCode },
      {
        withCredentials: true, // Enviar cookies de sesión
        headers: {
          'Content-Type': 'application/json',
        },
      }
    );

    if (response.data.status !== 'success') {
      throw new Error(response.data.message || 'Error del servidor');
    }

    return response.data.message;

  } catch (error) {
    console.error('Error en saveQRCode:', error.response?.data || error.message);
    throw new Error(error.response?.data?.message || 'Error al guardar el QR');
  }
};

export default { saveQRCode };
