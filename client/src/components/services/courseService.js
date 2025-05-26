const BASE_URL = 'http://localhost/proyecto_acomer/server/php/Cursos.php';

async function fetchData(action, method = 'GET', data = null) {
  try {
    const options = { method, headers: { 'Content-Type': 'application/json' }, credentials: 'include' };
    const url = `${API_URL}?action=${action}`;
    if (method !== 'GET' && data) {
      options.body = JSON.stringify(data);
    }

    const response = await fetch(url, options);

    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      const errorMessage = errorData.error || await response.text();
      
      if (response.status === 500 && errorMessage.includes('SQLSTATE[23000]')) {
        throw new Error('No se puede eliminar el curso porque está siendo utilizado en otra tabla.');
      }
      throw new Error(errorMessage || `Error HTTP ${response.status}`);
    }

    const result = await response.json();

    if (['create', 'update', 'delete'].includes(action)) {
      document.dispatchEvent(new CustomEvent('dataUpdated', { detail: { action, result } }));
    }

    return result;
  } catch (error) {
    console.error(`Error en ${action}:`, error);
    throw error; // Re-lanzamos el error para manejo específico en los componentes
  }
}

const courseService = {
  getCourses: () => fetchData('read'),
  
  // Cambiado de 'docentes' a 'get_docentes' para coincidir con el backend
  getDocentes: () => fetchData('get_docentes'),

  addCourse: (nombreCurso, idDocente) =>
    fetchData('create', 'POST', { 
      nombrecurso: nombreCurso, 
      docente_id: idDocente 
    }),

  updateCourse: (idcursos, nombreCurso, idDocente) =>
    fetchData('update', 'POST', { idcursos, nombrecurso: nombreCurso, docente_id: idDocente }),

  deleteCourse: (idcursos) =>
    fetchData('delete', 'POST', { idcursos }).catch((error) => {
      console.error('Error al borrar el curso:', error.message);
      return { error: error.message };
    }),
