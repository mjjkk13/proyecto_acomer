const API_BASE_URL = 'http://localhost/proyecto_acomer/server/php/AgregarEstudiante.php';

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
      const response = await fetch(API_BASE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',  // Cambiado a JSON
        },
        body: JSON.stringify(studentData),  // Cambiado a JSON.stringify
      });
      
      return await handleResponse(response);
    } catch (error) {
      throw new Error(`Error de red: ${error.message}`);
    }
  },
};

export default studentService;
