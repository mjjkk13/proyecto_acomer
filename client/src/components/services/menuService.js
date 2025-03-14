export const fetchMenus = async () => {
  try {
    const response = await fetchData({ action: 'read' });
    return Array.isArray(response) ? response : [];
  } catch (error) {
    console.error('Error fetching menus:', error);
    return [];
  }
};

export const fetchMenuByType = async (mealType) => {
  try {
    const response = await fetchData({ 
      tipomenu: mealType, 
      action: 'read' 
    });
    return Array.isArray(response) ? response : [];
  } catch (error) {
    console.error('Error fetching menu by type:', error);
    return [];
  }
};

export const addMenu = async (menuData) => {
  try {
    const response = await fetchData({ 
      ...menuData, 
      action: 'create' 
    });
    return response.success ? response : { error: response.error };
  } catch (error) {
    console.error('Error adding menu:', error);
    return { error: error.message };
  }
};

export const updateMenu = async (menuData) => {
  try {
    const response = await fetchData({ 
      ...menuData, 
      action: 'update' 
    });
    return response.success ? response : { error: response.error };
  } catch (error) {
    console.error('Error updating menu:', error);
    return { error: error.message };
  }
};

export const deleteMenu = async (idmenu) => {
  try {
    const response = await fetchData({ 
      idmenu: idmenu, 
      action: 'delete' 
    });
    return response.success ? response : { error: response.error };
  } catch (error) {
    console.error('Error deleting menu:', error);
    return { error: error.message };
  }
};

const fetchData = async (payload) => {
  try {
    const response = await fetch(
      'http://localhost/proyecto_acomer/server/php/menuCRUD.php',
      {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/x-www-form-urlencoded',
          'Authorization': `Bearer ${localStorage.getItem('token')}` // autenticación
        },
        body: new URLSearchParams(payload),
        credentials: 'include' // cookies
      }
    );

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
    }

    const result = await response.json();
    
    
    if (payload.action === 'read') return result;
    
    // Para otras acciones validar la respuesta del servidor
    if (!result.success) throw new Error(result.error || 'Acción fallida');
    
    return result;

  } catch (error) {
    console.error('Error en fetchData:', error);
    throw error;
  }
};