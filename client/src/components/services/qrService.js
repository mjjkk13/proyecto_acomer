const API_URL = import.meta.env.VITE_API_URL;

export const getQRCodes = async () => {
  try {
    const response = await fetch(`${API_URL}/CargarCodigosqr.php`, {
      credentials: 'include',
      headers: {
        'Cache-Control': 'no-cache',
      },
    });

    const contentType = response.headers.get('content-type') || '';
    let data = null;

    if (contentType.includes('application/json')) {
      data = await response.json();
    } else {
      const text = await response.text();
      throw new Error(`Respuesta inesperada del servidor: ${text}`);
    }

    if (!response.ok) {
      const errorMessage = data?.error || `Error HTTP ${response.status}: ${response.statusText}`;
      throw new Error(errorMessage);
    }

    if (data.status !== 'success') {
      throw new Error(data.message || 'Error en la respuesta del servidor');
    }

    // Adaptar el campo base64 para que pueda mostrarse como imagen
    const processedData = (data.data || []).map(item => ({
      ...item,
      codigoqr: item.codigoqr
        ? `data:image/png;base64,${item.codigoqr.trim()}`
        : null,
    }));

    return processedData;

  } catch (error) {
    console.error('Error en getQRCodes:', {
      message: error.message,
      stack: error.stack,
      timestamp: new Date().toISOString(),
    });

    throw new Error(`No se pudieron cargar los códigos QR: ${error.message}`);
  }
};

export const fetchQRScans = async () => {
  try {
    const response = await fetch(`${API_URL}/getQRScans.php`, {
      credentials: 'include',
      headers: {
        'Cache-Control': 'no-cache',
      },
    });

    const contentType = response.headers.get('content-type') || '';
    let result = null;

    if (contentType.includes('application/json')) {
      result = await response.json();
    } else {
      const text = await response.text();
      throw new Error(`Respuesta inesperada del servidor: ${text}`);
    }

    if (!response.ok) {
      throw new Error(result?.message || `Error HTTP ${response.status}: ${response.statusText}`);
    }

    if (!Array.isArray(result)) {
      throw new Error('Respuesta inesperada del servidor');
    }

    return result;
  } catch (error) {
    console.error('Error en fetchQRScans:', {
      message: error.message,
      stack: error.stack,
      timestamp: new Date().toISOString(),
    });
    throw new Error(`No se pudieron cargar las escaneadas del QR: ${error.message}`);
  }
};

export const deleteQRCode = async (id) => {
  try {
    const response = await fetch(`${API_URL}/EliminarCodigoQR.php?idqrgenerados=${encodeURIComponent(id)}`, {
      method: 'DELETE',
      credentials: 'include',
    });

    const contentType = response.headers.get('content-type') || '';
    let result = null;

    if (contentType.includes('application/json')) {
      result = await response.json();
    } else {
      const text = await response.text();
      throw new Error(`Respuesta inesperada del servidor: ${text}`);
    }

    if (!response.ok) {
      throw new Error(result?.message || `Error HTTP ${response.status}: ${response.statusText}`);
    }

    if (!result.success) {
      throw new Error(result.message || 'Error al eliminar el código QR');
    }

    return result;
  } catch (error) {
    console.error('Error en deleteQRCode:', {
      message: error.message,
      stack: error.stack,
      timestamp: new Date().toISOString(),
    });
    throw new Error(`No se pudo eliminar el código QR: ${error.message}`);
  }
};
