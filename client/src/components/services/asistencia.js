import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL;

// Obtener los cursos del docente autenticado
export const getCursosDocente = async () => {
  try {
    const response = await axios.get(`${API_URL}/cursos_docente.php`, {
      withCredentials: true,
    });

    if (!response.data || !Array.isArray(response.data)) {
      throw new Error('Formato de respuesta inválido al obtener cursos del docente');
    }

    return response.data;
  } catch (error) {
    console.error('Error en getCursosDocente:', error.response?.data || error.message);
    throw new Error('Error al obtener los cursos del docente');
  }
};

// Obtener estudiantes de un curso específico
export const getEstudiantesCurso = async (cursoId) => {
  if (!cursoId) throw new Error('ID de curso es obligatorio');

  try {
    const response = await axios.get(`${API_URL}/estudiantes_curso.php`, {
      params: { curso_id: cursoId },
      withCredentials: true,
    });

    const data = response.data;

    // Ahora la respuesta es un objeto { status, data }
    if (data.status === 'success' && Array.isArray(data.data)) {
      return data.data;
    }

    if (data.status === 'error') {
      throw new Error(data.message || 'No se pudo obtener estudiantes');
    }

    throw new Error('Respuesta inesperada del servidor al obtener estudiantes');
  } catch (error) {
    console.error('Error en getEstudiantesCurso:', error.response?.data || error.message);
    throw new Error('Error al obtener los estudiantes del curso');
  }
};

// Registrar asistencia de los estudiantes en un curso
export const registrarAsistencia = async (cursoId, asistencias) => {
  if (!cursoId || !Array.isArray(asistencias)) {
    throw new Error('Datos inválidos: cursoId o asistencias no válidos');
  }

  const datosValidos = asistencias.every(
    a => a && typeof a.alumno_id !== 'undefined' && typeof a.estado !== 'undefined'
  );

  if (!datosValidos) {
    throw new Error('Cada asistencia debe tener "alumno_id" y "estado"');
  }

  try {
    const response = await axios.post(
      `${API_URL}/registrar_asistencia.php`,
      {
        idcursos: cursoId,
        asistencias,
      },
      {
        withCredentials: true,
      }
    );

    const data = response.data;

    if (data?.status !== 'success') {
      throw new Error(data.message || 'Error en el registro de asistencia');
    }

    if (!data.qr_base64) {
      throw new Error('QR no generado');
    }

    return {
      status: 'success',
      qr_image: data.qr_base64,
    };
  } catch (error) {
    console.error('Error en registrarAsistencia:', error.response?.data || error.message);
    throw new Error('Error al registrar la asistencia');
  }
};

