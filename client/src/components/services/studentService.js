const API_BASE_URL = import.meta.env.VITE_API_URL;

const handleResponse = async (response) => {
  const contentType = response.headers.get('content-type');
  
  if (!response.ok) {
    const errorData = contentType?.includes('application/json') 
      ? await response.json()
      : { message: `Error HTTP: ${response.status}` };
    throw new Error(errorData.message || 'Error en la solicitud');
  }

  return contentType?.includes('application/json') 
    ? response.json()
    : response.text();
};

const studentService = {
  addStudent: async (studentData) => {
    try {
      const response = await fetch(`${API_BASE_URL}/AgregarEstudiante.php`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(studentData),
        credentials: 'include'  // para enviar cookies PHPSESSID
      });
      
      return await handleResponse(response);
    } catch (error) {
      throw new Error(`Error de red: ${error.message}`);
    }
  },
};

export default studentService;
