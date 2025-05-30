import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL;

export const getCursosDocente = async () => {
  try {
    const response = await axios.get(`${API_URL}/cursos_docente.php`, { withCredentials: true });
    return response.data;
  } catch (error) {
    console.log(error);
    throw new Error('Error al obtener los cursos del docente');
  }
};

export const getEstudiantesCurso = async (cursoId) => {
  try {
    const response = await axios.get(`${API_URL}/estudiantes_curso.php?curso_id=${cursoId}`);
    return response.data;
  } catch (error) {
    console.log(error);
    throw new Error('Error al obtener los estudiantes del curso');
  }
};

export const registrarAsistencia = async (cursoId, asistencias) => {
  try {
    if (!Array.isArray(asistencias)) {
      throw new Error('Los datos de asistencia no son válidos');
    }

    // Validar que cada elemento tenga alumno_id y estado
    const datosValidos = asistencias.every(a => typeof a.alumno_id !== 'undefined' && typeof a.estado !== 'undefined');
    if (!datosValidos) {
      throw new Error('Cada asistencia debe incluir alumno_id y estado');
    }

    const response = await axios.post(
      `${API_URL}/registrar_asistencia.php`,
      {
        idcursos: cursoId,
        asistencias: asistencias, // Array de objetos con alumno_id y estado
      },
      { withCredentials: true }
    );

    return response.data;
  } catch (error) {
    console.error('Error en registrarAsistencia:', error.response?.data || error.message);
    throw new Error('Error al registrar la asistencia');
  }
};
