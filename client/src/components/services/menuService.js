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
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify(payload),
        credentials: 'include'
      }
    );

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || `HTTP error! status: ${response.status}`);
    }

    if (payload.action === 'read') {
      return result.data || [];
    }
    
    return result;
  } catch (error) {
    console.error('Error en fetchData:', error);
    throw error;
  }
};