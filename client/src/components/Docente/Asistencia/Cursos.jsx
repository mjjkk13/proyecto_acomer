import { useEffect, useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown, faUserGraduate } from '@fortawesome/free-solid-svg-icons';
import Swal from 'sweetalert2';
import { getCursosDocente, getEstudiantesCurso, registrarAsistencia } from '../../services/asistencia';

const ITEMS_POR_PAGINA = 10;

const CursosDocente = () => {
  const [cursos, setCursos] = useState([]);
  const [cursoSeleccionado, setCursoSeleccionado] = useState(null);
  const [estudiantes, setEstudiantes] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [asistencias, setAsistencias] = useState({});
  const [todosMarcados, setTodosMarcados] = useState(false);
  const [paginaActual, setPaginaActual] = useState(1);

  useEffect(() => {
    cargarCursos();
  }, []);

  const cargarCursos = async () => {
    try {
      setCargando(true);
      const datos = await getCursosDocente();
      const listaCursos = Array.isArray(datos) ? datos : [];
      setCursos(listaCursos);
      setCargando(false);

      if (listaCursos.length === 0) {
        Swal.fire('Sin cursos asignados', 'No tienes cursos asignados hasta el momento.', 'info');
      }
    } catch (error) {
      Swal.fire('Error', error.message, 'error');
      console.error("Error al cargar los cursos:", error);
      setCargando(false);
    }
  };

  const seleccionarCurso = async (curso) => {
    setCursoSeleccionado(curso);
    try {
      setCargando(true);
      const datos = await getEstudiantesCurso(curso.idcursos);
      const estudiantesArray = Array.isArray(datos) ? datos : [];
      setEstudiantes(estudiantesArray);

      const asistenciasIniciales = estudiantesArray.reduce((acc, estudiante) => {
        acc[estudiante.idalumno] = false;
        return acc;
      }, {});
      setAsistencias(asistenciasIniciales);
      setTodosMarcados(false);
      setPaginaActual(1);
      setCargando(false);
    } catch (error) {
      Swal.fire('Error', error.message, 'error');
      setCargando(false);
    }
  };

  const handleAsistencia = (idEstudiante) => {
    setAsistencias((prev) => {
      const nuevoEstado = {
        ...prev,
        [idEstudiante]: !prev[idEstudiante],
      };

      const todos = estudiantes.length > 0 && estudiantes.every(est =>
        nuevoEstado[est.idalumno] === true
      );
      setTodosMarcados(todos);

      return nuevoEstado;
    });
  };

  const handleMarcarTodos = () => {
    const nuevoEstado = {};
    estudiantes.forEach(est => {
      nuevoEstado[est.idalumno] = !todosMarcados;
    });
    setTodosMarcados(!todosMarcados);
    setAsistencias(nuevoEstado);
  };

  const enviarAsistencias = async () => {
    try {
      const datosAsistencias = Object.entries(asistencias).map(([idEstudiante, presente]) => ({
        alumno_id: parseInt(idEstudiante),
        estado: presente,
      }));

      if (!Array.isArray(datosAsistencias) || datosAsistencias.length === 0) {
        Swal.fire('Error', 'No hay asistencias para registrar', 'error');
        return;
      }

      const response = await registrarAsistencia(cursoSeleccionado.idcursos, datosAsistencias);

      if (response.status === 'success') {
        Swal.fire('Éxito', 'Asistencias enviadas correctamente', 'success');

        if (response.qr_image) {
          Swal.fire({
            title: 'QR de Asistencia',
            html: `<img src="${response.qr_image}" alt="QR de Asistencia" width="200" />`,
            showConfirmButton: true,
          });
        }
      } else {
        Swal.fire('Error', response.message || 'Ocurrió un error al registrar las asistencias', 'error');
      }
    } catch (error) {
      console.error('Error en enviarAsistencias:', error);
      Swal.fire('Error', error.message, 'error');
    }
  };

  const totalPaginas = Math.ceil(estudiantes.length / ITEMS_POR_PAGINA);
  const indiceInicio = (paginaActual - 1) * ITEMS_POR_PAGINA;
  const estudiantesPagina = estudiantes.slice(indiceInicio, indiceInicio + ITEMS_POR_PAGINA);

  const cambiarPagina = (nuevaPagina) => {
    if (nuevaPagina < 1 || nuevaPagina > totalPaginas) return;
    setPaginaActual(nuevaPagina);
  };

  if (cargando) {
    return (
      <div className="flex justify-center items-center h-64">
        <span className="loading loading-spinner loading-lg"></span>
      </div>
    );
  }

  if (!cargando && cursos.length === 0) {
    return (
      <div className="flex justify-center items-center h-64">
        <p className="text-center text-gray-600 text-lg">
          No tienes cursos asignados hasta el momento.
        </p>
      </div>
    );
  }

  return (
    <div className="max-w-7xl mx-auto p-4 h-full flex flex-col">
      <div className="card bg-base-100 shadow-md flex-grow overflow-hidden">
        <h2 className="text-lg font-bold text-center text-white bg-primary p-3">
          Cursos Disponibles
        </h2>

        <div className="flex h-full overflow-hidden">
          <div className="w-1/3 overflow-y-auto border-r p-2">
            <ul className="menu menu-sm bg-base-200 rounded-box w-full">
              {cursos.map((curso) => (
                <li key={curso.idcursos}>
                  <button
                    onClick={() => seleccionarCurso(curso)}
                    className={`w-full p-2 text-left hover:bg-primary hover:text-white transition-colors ${cursoSeleccionado?.idcursos === curso.idcursos ? 'bg-primary text-white' : ''}`}
                  >
                    {curso.nombrecurso}
                    <FontAwesomeIcon icon={faChevronDown} className="ml-2" />
                  </button>
                </li>
              ))}
            </ul>
          </div>

          {cursoSeleccionado && (
            <div className="w-2/3 flex flex-col h-full">
              <div className="bg-gray-200 text-gray-800 font-bold text-center p-2 rounded mb-2">
                Estudiantes de {cursoSeleccionado.nombrecurso}
              </div>

              <div className="flex justify-between items-center mb-2 px-3">
                <span className="font-bold">Estudiante</span>
                <label className="flex items-center space-x-2 cursor-pointer">
                  <input
                    type="checkbox"
                    className="checkbox checkbox-primary"
                    checked={todosMarcados}
                    onChange={handleMarcarTodos}
                  />
                  <span className="font-bold">Marcar todos</span>
                </label>
              </div>

              <div className="overflow-y-auto flex-grow px-2 space-y-2">
                {estudiantesPagina.map((estudiante) => (
                  <div
                    key={estudiante.idalumno}
                    className="flex items-center justify-between bg-base-200 p-3 rounded-lg hover:bg-base-300 transition-colors"
                  >
                    <div className="flex items-center space-x-3">
                      <FontAwesomeIcon icon={faUserGraduate} className="text-xl text-gray-600" />
                      <div>
                        <span className="font-medium">
                          {estudiante.nombre} {estudiante.apellido}
                        </span>
                      </div>
                    </div>
                    <input
                      type="checkbox"
                      className="checkbox checkbox-primary"
                      checked={asistencias[estudiante.idalumno] || false}
                      onChange={() => handleAsistencia(estudiante.idalumno)}
                    />
                  </div>
                ))}
              </div>

              {totalPaginas > 1 && (
                <div className="flex justify-center mt-4 space-x-4">
                  <button
                    className="btn btn-sm"
                    onClick={() => cambiarPagina(paginaActual - 1)}
                    disabled={paginaActual === 1}
                  >
                    Anterior
                  </button>
                  <span className="flex items-center px-3">
                    Página {paginaActual} de {totalPaginas}
                  </span>
                  <button
                    className="btn btn-sm"
                    onClick={() => cambiarPagina(paginaActual + 1)}
                    disabled={paginaActual === totalPaginas}
                  >
                    Siguiente
                  </button>
                </div>
              )}

              <div className="mt-4 flex justify-center">
                <button className="btn btn-primary" onClick={enviarAsistencias}>
                  Enviar Asistencias
                </button>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default CursosDocente;
