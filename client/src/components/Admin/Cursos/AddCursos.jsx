import { useEffect, useState } from 'react';
import courseService from '../../services/courseService';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEdit, faTrash, faPlus } from '@fortawesome/free-solid-svg-icons';

const AddCursos = () => {
  const [courses, setCourses] = useState([]);
  const [loading, setLoading] = useState(false); 

  useEffect(() => {
    cargarCursos();
  }, []);

  const cargarCursos = async () => {
    setLoading(true); 
    try {
      const data = await courseService.getCourses();
      if (!Array.isArray(data) || data.error) 
        throw new Error(data.error || 'Error al cargar los cursos.');
      setCourses(data);
    } catch {
      Swal.fire('Error', 'No se pudieron cargar los cursos.', 'error');
      setCourses([]);
    } finally {
      setLoading(false); 
    }
  };

  const obtenerDocentes = async () => {
    try {
      const response = await courseService.getDocentes();
      return Array.isArray(response) ? response : [];
    } catch {
      Swal.fire('Error', 'No se pudieron cargar los docentes.', 'error');
      return [];
    }
  };

  const mostrarFormularioCurso = async (titulo, curso = {}) => {
    const docentes = await obtenerDocentes();
    
    const docenteOptions = docentes.map((doc) => `
      <option value="${doc.idusuarios}" ${doc.idusuarios === curso.docente_id ? 'selected' : ''}>
        ${doc.nombre} ${doc.apellido}
      </option>`
    ).join('');

    const { value: formValues } = await Swal.fire({
      title: titulo,
      html: `
        <input id="nombreCurso" class="swal2-input" 
               value="${curso.nombrecurso || ''}" 
               placeholder="Nombre del Curso *"
               required>
        <select id="idDocente" class="swal2-select" required>
          <option value="" disabled>Seleccione un docente *</option>
          ${docenteOptions}
        </select>
      `,
      focusConfirm: false,
      showCancelButton: true,
      preConfirm: () => {
        const nombreCurso = Swal.getPopup().querySelector('#nombreCurso').value.trim();
        const idDocente = Swal.getPopup().querySelector('#idDocente').value;
        
        if (!nombreCurso || !idDocente) {
          Swal.showValidationMessage('Todos los campos son obligatorios');
          return false;
        }
        
        return { nombreCurso, idDocente };
      }
    });

    return formValues;
  };

  const handleAgregarCurso = async () => {
    const formValues = await mostrarFormularioCurso('Agregar Curso');
    if (!formValues) return;

    try {
      const data = await courseService.addCourse(formValues.nombreCurso, formValues.idDocente);
      if (data.success) {
        Swal.fire('¡Curso agregado!', '', 'success');
        await cargarCursos(); 
      } else {
        throw new Error(data.error);
      }
    } catch (error) {
      console.error('Error al agregar el curso:', error);
      Swal.fire('Error', 'No se pudo agregar el curso.', 'error');
    }
  };

  const handleEditarCurso = async (curso) => {
    const cursoParaEditar = { ...curso, iddocente: curso.docente_id };
    const formValues = await mostrarFormularioCurso('Editar Curso', cursoParaEditar);
    if (!formValues) return;

    try {
      const data = await courseService.updateCourse(curso.idcurso, formValues.nombreCurso, formValues.idDocente);
      if (data.success) {
        Swal.fire('¡Curso actualizado!', '', 'success');
        await cargarCursos();
      } else {
        throw new Error(data.error);
      }
    } catch (error) {
      console.error('Error al actualizar el curso:', error);
      Swal.fire('Error', 'No se pudo actualizar el curso.', 'error');
    }
  };

  const handleBorrarCurso = async (idCurso) => {
    const result = await Swal.fire({
      title: '¿Estás seguro?',
      text: 'No podrás revertir esto.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, borrar',
      cancelButtonText: 'Cancelar',
    });

    if (!result.isConfirmed) return;

    try {
      const response = await courseService.deleteCourse(idCurso);
      if (response.success) {
        Swal.fire('¡Eliminado!', 'El curso ha sido eliminado.', 'success');
        await cargarCursos(); // Aseguramos que los cursos se recarguen
      } else {
        throw new Error(response.error);
      }
    } catch (error) {
      console.error('Error al borrar el curso:', error);
      Swal.fire('Error', 'No se pudo borrar el curso.', 'error');
    }
  };

  return (
    <div className="container px-4 mx-auto mt-4">
      <h3 className="text-xl font-bold mb-4">Cursos Disponibles</h3>
      <button onClick={handleAgregarCurso} className="btn btn-primary mb-4">
        <FontAwesomeIcon icon={faPlus} className="mr-2" /> Agregar Curso
      </button>
      <div className="overflow-auto max-h-96 border border-gray-200 rounded-lg">
        {loading ? ( // Show loading message or spinner
          <div className="text-center text-gray-500 py-4">Cargando cursos...</div>
        ) : (
          <table className="table w-full">
            <thead>
              <tr>
                <th className="text-gray-500">Curso</th>
                <th className="text-gray-500">Director de Curso</th>
                <th className="text-gray-500">Acciones</th>
              </tr>
            </thead>
            <tbody>
              {courses.length > 0 ? (
                courses.map((curso) => (
                  <tr className="text-gray-400" key={curso.idcurso}>
                    <td>{curso.nombrecurso}</td>
                    <td>{curso.nombreDocente}</td>
                    <td>
                      <button onClick={() => handleEditarCurso(curso)} className="btn btn-warning btn-sm mr-2">
                        <FontAwesomeIcon icon={faEdit} /> Editar
                      </button>
                      <button onClick={() => handleBorrarCurso(curso.idcurso)} className="btn btn-error btn-sm">
                        <FontAwesomeIcon icon={faTrash} /> Borrar
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="3" className="text-center text-gray-500">No hay cursos disponibles</td>
                </tr>
              )}
            </tbody>
          </table>
        )}
      </div>
    </div>
  );
};

export default AddCursos;
