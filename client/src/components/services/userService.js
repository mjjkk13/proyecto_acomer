// Función auxiliar para analizar la respuesta HTTP
const parseResponse = async (response) => {
    const text = await response.text();
    try {
      return JSON.parse(text);
    } catch {
      return text;
    }
  };
  
  const fetchData = async (payload) => {
    try {
      if (!payload || typeof payload !== 'object') {
        throw new Error('Payload inválido');
      }
  
     const response = await fetch(`${import.meta.env.VITE_API_URL}/Usuarios.php`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(payload),
  credentials: 'include'
});

  
      const data = await parseResponse(response);
  
      if (!response.ok) {
        const errorMessage = typeof data === 'object' ? data.error : data;
        throw new Error(errorMessage || `Error HTTP! estado: ${response.status}`);
      }
  
      // Validación de estructura de respuesta
      if (payload.action === 'fetchAll') {
        if (!Array.isArray(data)) {
          throw new Error('Respuesta inesperada del servidor');
        }
        return data;
      }
  
      if (typeof data !== 'object' || data === null) {
        throw new Error('Respuesta inválida del servidor');
      }
  
      if (!data.success) {
        throw new Error(data.error || 'Acción fallida');
      }
  
      return data;
    } catch (error) {
      console.error('Error en fetchData:', error);
      throw error;
    }
  };
  
  export const fetchUsers = async () => {
    try {
      const response = await fetchData({ action: 'fetchAll' });
      return Array.isArray(response) ? response : [];
    } catch (error) {
      console.error('Error fetching users:', error);
      return [];
    }
  };
  
  export const updateUser = async (userData) => {
    try {
      if (!userData || typeof userData !== 'object') {
        throw new Error('Datos de usuario inválidos');
      }
      
      const payload = { ...userData, action: 'update' };
      const response = await fetchData(payload);
      return { success: true, data: response };
    } catch (error) {
      console.error('Error updating user:', error);
      return { success: false, error: error.message || 'Error actualizando usuario' };
    }
  };
  
  export const deleteUser = async (id) => {
    try {
      if (!id || typeof id !== 'string') {
        throw new Error('ID inválido');
      }
      
      const response = await fetchData({ id, action: 'delete' });
      return { success: true, data: response };
    } catch (error) {
      console.error('Error deleting user:', error);
      return { success: false, error: error.message || 'Error eliminando usuario' };
    }
  };
  