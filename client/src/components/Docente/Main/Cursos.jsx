import { useEffect, useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown, faUserGraduate } from '@fortawesome/free-solid-svg-icons';
import Swal from 'sweetalert2';
import { getCursosDocente, getEstudiantesCurso, registrarAsistencia } from '../../services/asistencia';

const CursosDocente = () => {
  const [cursos, setCursos] = useState([]);
  const [cursoSeleccionado, setCursoSeleccionado] = useState(null);
  const [estudiantes, setEstudiantes] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [asistencias, setAsistencias] = useState({});

  useEffect(() => {
    cargarCursos();
  }, []);

  const cargarCursos = async () => {
    try {
      setCargando(true);
      const datos = await getCursosDocente();
      setCursos(Array.isArray(datos) ? datos : []);
      setCargando(false);
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
      setEstudiantes(Array.isArray(datos) ? datos : []);

      const asistenciasIniciales = datos.reduce((acc, estudiante) => {
        acc[estudiante.idalumno] = false;
        return acc;
      }, {});
      setAsistencias(asistenciasIniciales);
      setCargando(false);
    } catch (error) {
      Swal.fire('Error', error.message, 'error');
      setCargando(false);
    }
  };

  const handleAsistencia = (idEstudiante) => {
    setAsistencias((prev) => ({
      ...prev,
      [idEstudiante]: !prev[idEstudiante],
    }));
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
            html: `
            <div style="text-align: center;">
              <img src="${response.qr_image}" alt="QR de Asistencia" width="200" style="display: block; margin: 0 auto;" />
            </div>
          `,
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

  if (cargando) {
    return (
      <div className="flex justify-center items-center h-64">
        <span className="loading loading-spinner loading-lg"></span>
      </div>
    );
  }

  return (
    <div className="max-w-7xl mx-auto p-4">
      <div className="card bg-base-100 shadow-md">
        <h2 className="text-lg font-bold text-center text-white bg-primary p-3">
          Cursos Disponibles
        </h2>

        <div className="card-body p-4 flex gap-4">
          <div className="w-1/3">
            <ul className="menu menu-sm bg-base-200 rounded-box w-full">
              {cursos.map((curso) => (
                <li key={curso.idcursos}>
                  <button
                    onClick={() => seleccionarCurso(curso)}
                    className="flex justify-between items-center w-full p-2 hover:bg-primary hover:text-white transition-colors"
                  >
                    {curso.nombrecurso}
                    <FontAwesomeIcon icon={faChevronDown} />
                  </button>
                </li>
              ))}
            </ul>
          </div>

          {cursoSeleccionado && (
            <div className="w-2/3">
              <h3 className="text-md font-bold text-center bg-gray-400 text-white p-2 rounded mb-4">
                Estudiantes de {cursoSeleccionado.nombrecurso}
              </h3>

              <div className="space-y-2">
                <div className="flex justify-between items-center mb-2 px-3">
                  <span className="font-bold">Estudiante</span>
                  <span className="font-bold">Asistió</span>
                </div>

                {estudiantes.map((estudiante) => (
                  <div
                    key={estudiante.idalumno}
                    className="flex items-center justify-between bg-base-200 p-3 rounded-lg shadow-sm hover:bg-base-300 transition-colors"
                  >
                    <div className="flex items-center space-x-3">
                      <FontAwesomeIcon
                        icon={faUserGraduate}
                        className="text-xl text-gray-600"
                      />
                      <div className="text-left">
                        <span className="font-medium block">
                          {estudiante.nombre} {estudiante.apellido}
                        </span>
                      </div>
                    </div>

                    <div className="flex items-center space-x-2">
                      <input
                        type="checkbox"
                        className="checkbox checkbox-primary checkbox-md w-6 h-6 border-2 border-primary bg-white"
                        checked={asistencias[estudiante.idalumno] || false}
                        onChange={() => handleAsistencia(estudiante.idalumno)}
                      />
                    </div>
                  </div>
                ))}
              </div>

              <div className="mt-4 flex justify-center">
                <button
                  className="btn btn-primary"
                  onClick={enviarAsistencias}
                >
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
