const API_BASE_URL = `${import.meta.env.VITE_API_URL}/AgregarEstudiante.php`;

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
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(studentData),
        credentials: 'include'  // 👈 Aquí se agregan las cookies (como PHPSESSID)
      });
      
      return await handleResponse(response);
    } catch (error) {
      throw new Error(`Error de red: ${error.message}`);
    }
  },
};

export default studentService;
