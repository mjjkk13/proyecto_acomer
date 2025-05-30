import { useEffect, useState } from 'react';
import courseService from '../../services/courseService';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEdit, faTrash, faPlus } from '@fortawesome/free-solid-svg-icons';

const AddCursos = () => {
  const [courses, setCourses] = useState([]);
  const [loading, setLoading] = useState(false);
  const [hasTeachers, setHasTeachers] = useState(false);
  const [teachersError, setTeachersError] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);
  const coursesPerPage = 5;

  useEffect(() => {
    const initializeData = async () => {
      await cargarCursos();
      await checkTeachersAvailability();
    };
    initializeData();
  }, []);

  const checkTeachersAvailability = async () => {
    try {
      const docentes = await obtenerDocentes();
      setHasTeachers(docentes.length > 0);
      setTeachersError(null);
    } catch (error) {
      console.error('Error al verificar docentes:', error);
      setTeachersError(error.message);
      setHasTeachers(false);
    }
  };

  const cargarCursos = async () => {
    setLoading(true);
    try {
      const data = await courseService.getCourses();

      if (!Array.isArray(data)) {
        throw new Error('La respuesta del servidor no es un arreglo válido');
      }

      if (data.length > 0 && !Object.prototype.hasOwnProperty.call(data[0], 'nombrecurso')) {
        console.warn('Estructura inesperada de cursos:', data);
      }

      setCourses(data);
      setCurrentPage(1);
    } catch (error) {
      console.error('Error al cargar cursos:', error);
      Swal.fire({
        title: 'Error',
        text: error.message || 'No se pudieron cargar los cursos',
        icon: 'error'
      });
      setCourses([]);
    } finally {
      setLoading(false);
    }
  };

  const obtenerDocentes = async () => {
    try {
      const response = await courseService.getDocentes();

      // Manejar respuesta de error del backend
      if (response && response.error) {
        throw new Error(response.error);
      }

      if (!response || !Array.isArray(response)) {
        throw new Error('Formato de datos incorrecto del servidor');
      }

      // Filtrar docentes válidos
      const validDocentes = response.filter(doc => 
        doc.iddocente && doc.nombre && doc.apellido
      );

      if (validDocentes.length === 0) {
        throw new Error('No hay docentes registrados en el sistema');
      }

      return validDocentes;
    } catch (error) {
      console.error('Error al obtener docentes:', error);
      setTeachersError(error.message);
      throw error;
    }
  };

  const mostrarFormularioCurso = async (titulo, curso = {}) => {
    try {
      const docentes = await obtenerDocentes();

      if (docentes.length === 0) {
        await Swal.fire({
          title: 'No hay docentes disponibles',
          text: 'Por favor registre docentes antes de crear cursos',
          icon: 'warning'
        });
        return null;
      }

      const docenteOptions = docentes.map(doc => `
        <option value="${doc.iddocente}" ${doc.iddocente === curso.docente_id ? 'selected' : ''}>
          ${doc.nombre} ${doc.apellido}
        </option>
      `).join('');

      const { value: formValues } = await Swal.fire({
        title: titulo,
        html: `
          <div class="text-left">
            <label class="block mb-2 text-sm font-medium">Nombre del Curso *</label>
            <input id="nombreCurso" class="swal2-input mb-4" 
                   value="${curso.nombrecurso || ''}" 
                   placeholder="Ej: Curso 902"
                   required>
            
            <label class="block mb-2 text-sm font-medium">Docente *</label>
            <select id="idDocente" class="swal2-select" required>
              <option value="" disabled ${!curso.docente_id ? 'selected' : ''}>
                Seleccione un docente
              </option>
              ${docenteOptions}
            </select>
          </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
          const nombreCurso = document.getElementById('nombreCurso').value.trim();
          const idDocente = document.getElementById('idDocente').value;

          if (!nombreCurso) {
            Swal.showValidationMessage('El nombre del curso es obligatorio');
            return false;
          }

          if (!idDocente) {
            Swal.showValidationMessage('Debe seleccionar un docente');
            return false;
          }

          return { nombreCurso, idDocente };
        },
        customClass: {
          validationMessage: 'text-red-500 text-sm mt-2'
        }
      });

      return formValues;
    } catch (error) {
      console.error('Error en formulario de curso:', error);
      Swal.fire({
        title: 'Error',
        text: error.message || 'No se puede mostrar el formulario',
        icon: 'error'
      });
      return null;
    }
  };

  const handleAgregarCurso = async () => {
    try {
      const formValues = await mostrarFormularioCurso('Agregar Nuevo Curso');
      if (!formValues) return;

      setLoading(true);
      const result = await courseService.addCourse(
        formValues.nombreCurso,
        parseInt(formValues.idDocente)
      );

      if (result.success) {
        await Swal.fire({
          title: '¡Éxito!',
          text: 'Curso agregado correctamente',
          icon: 'success'
        });
        await cargarCursos();
      } else {
        throw new Error(result.error || 'Error desconocido al agregar curso');
      }
    } catch (error) {
      console.error('Error al agregar curso:', error);
      Swal.fire({
        title: 'Error',
        text: error.message || 'No se pudo agregar el curso',
        icon: 'error'
      });
    } finally {
      setLoading(false);
    }
  };

  const handleEditarCurso = async (curso) => {
    try {
      if (!curso?.idcurso) {
        throw new Error('Curso no válido para edición');
      }

      const formValues = await mostrarFormularioCurso('Editar Curso', curso);
      if (!formValues) return;

      setLoading(true);
      const result = await courseService.updateCourse(
        curso.idcurso,
        formValues.nombreCurso,
        formValues.idDocente
      );

      if (result.success) {
        await Swal.fire({
          title: '¡Éxito!',
          text: 'Curso actualizado correctamente',
          icon: 'success'
        });
        await cargarCursos();
      } else {
        throw new Error(result.error || 'Error desconocido al actualizar curso');
      }
    } catch (error) {
      console.error('Error al editar curso:', error);
      Swal.fire({
        title: 'Error',
        text: error.message || 'No se pudo actualizar el curso',
        icon: 'error'
      });
    } finally {
      setLoading(false);
    }
  };

  const handleBorrarCurso = async (idCurso) => {
    try {
      if (!idCurso) {
        throw new Error('ID de curso no válido');
      }

      const result = await Swal.fire({
        title: '¿Está seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        reverseButtons: true
      });

      if (!result.isConfirmed) return;

      setLoading(true);
      const response = await courseService.deleteCourse(idCurso);

      if (response.success) {
        await Swal.fire({
          title: '¡Eliminado!',
          text: 'El curso ha sido eliminado',
          icon: 'success'
        });
        await cargarCursos();
      } else {
        throw new Error(response.error || 'Error desconocido al eliminar curso');
      }
    } catch (error) {
      console.error('Error al eliminar curso:', error);
      Swal.fire({
        title: 'Error',
        text: error.message || 'No se pudo eliminar el curso',
        icon: 'error'
      });
    } finally {
      setLoading(false);
    }
  };

  // Paginación
  const indexOfLastCourse = currentPage * coursesPerPage;
  const indexOfFirstCourse = indexOfLastCourse - coursesPerPage;
  const currentCourses = courses.slice(indexOfFirstCourse, indexOfLastCourse);
  const totalPages = Math.ceil(courses.length / coursesPerPage);

  const handlePageChange = (pageNumber) => {
    if (pageNumber < 1 || pageNumber > totalPages) return;
    setCurrentPage(pageNumber);
  };

  return (
    <div className="container px-4 mx-auto mt-4">
      <div className="flex justify-between items-center mb-6">
        <h3 className="text-2xl font-bold text-gray-800">Gestión de Cursos</h3>
        <button 
          onClick={handleAgregarCurso} 
          className={`btn ${hasTeachers ? 'btn-primary' : 'btn-disabled'}`}
          disabled={!hasTeachers || loading}
        >
          {loading ? (
            <>
              <span className="loading loading-spinner"></span>
              Procesando...
            </>
          ) : (
            <>
              <FontAwesomeIcon icon={faPlus} className="mr-2" />
              {hasTeachers ? 'Agregar Curso' : 'Docentes no disponibles'}
            </>
          )}
        </button>
      </div>

      {teachersError && (
        <div className="alert alert-error shadow-lg mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" className="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <h3 className="font-bold">Error al cargar docentes!</h3>
            <div className="text-xs">{teachersError}</div>
          </div>
        </div>
      )}

      {loading && courses.length === 0 ? (
        <div className="flex justify-center items-center h-64">
          <span className="loading loading-spinner loading-lg"></span>
        </div>
      ) : (
        <>
          <div className="overflow-x-auto bg-white rounded-lg shadow">
            <table className="table w-full">
              <thead>
                <tr className="bg-gray-100">
                  <th className="text-gray-700 font-semibold">Curso</th>
                  <th className="text-gray-700 font-semibold">Docente</th>
                  <th className="text-gray-700 font-semibold">Acciones</th>
                </tr>
              </thead>
              <tbody>
                {currentCourses.length > 0 ? (
                  currentCourses.map((curso) => (
                    <tr key={curso.idcurso} className="hover:bg-gray-50">
                      <td className="font-medium">{curso.nombrecurso}</td>
                      <td>
                        {curso.nombreDocente || (
                          <span className="text-gray-400">Sin asignar</span>
                        )}
                      </td>
                      <td>
                        <div className="flex space-x-2">
                          <button
                            onClick={() => handleEditarCurso(curso)}
                            className="btn btn-sm btn-outline btn-warning"
                            disabled={loading}
                          >
                            <FontAwesomeIcon icon={faEdit} className="mr-1" />
                            Editar
                          </button>
                          <button
                            onClick={() => handleBorrarCurso(curso.idcurso)}
                            className="btn btn-sm btn-outline btn-error"
                            disabled={loading}
                          >
                            <FontAwesomeIcon icon={faTrash} className="mr-1" />
                            Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="3" className="text-center py-8 text-gray-500">
                      No se encontraron cursos registrados
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          {totalPages > 1 && (
            <div className="flex justify-center mt-6">
              <div className="join">
                <button
                  onClick={() => handlePageChange(currentPage - 1)}
                  disabled={currentPage === 1}
                  className="join-item btn btn-sm"
                >
                  «
                </button>
                {Array.from({ length: totalPages }, (_, i) => i + 1).map(page => (
                  <button
                    key={page}
                    onClick={() => handlePageChange(page)}
                    className={`join-item btn btn-sm ${currentPage === page ? 'btn-active' : ''}`}
                  >
                    {page}
                  </button>
                ))}
                <button
                  onClick={() => handlePageChange(currentPage + 1)}
                  disabled={currentPage === totalPages}
                  className="join-item btn btn-sm"
                >
                  »
                </button>
              </div>
            </div>
          )}
        </>
      )}
    </div>
  );
};

export default AddCursos;