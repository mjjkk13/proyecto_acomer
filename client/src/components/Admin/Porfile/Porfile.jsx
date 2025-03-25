import { useEffect, useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPen, faSave, faEdit } from '@fortawesome/free-solid-svg-icons';
import Swal from 'sweetalert2';
import { getDatosPersonales, updateDatosPersonales } from '../../services/porfile';

const FIELDS = [
  { field: 'idusuarios', label: 'ID Usuario' },
  { field: 'nombre', label: 'Nombre' },
  { field: 'apellido', label: 'Apellido' },
  { field: 'email', label: 'Email' },
  { field: 'telefono', label: 'Teléfono' },
  { field: 'direccion', label: 'Dirección' },
];

const Porfile = () => {
  const [isEditing, setIsEditing] = useState(false);
  const [datosOriginales, setDatosOriginales] = useState({});
  const [formData, setFormData] = useState({});
  const [cargando, setCargando] = useState(true);

  useEffect(() => {
    cargarDatos();
  }, []);

  const cargarDatos = async () => {
    try {
      setCargando(true);
      const datos = await getDatosPersonales();
      setDatosOriginales(datos);
      setFormData(datos);
      setCargando(false);
    } catch (error) {
      Swal.fire('Error', error.message, 'error');
      setCargando(false);
    }
  };

  const manejarCambioInput = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const toggleEdicion = async () => {
    if (!isEditing) {
      setIsEditing(true);
      return;
    }

    try {
      await updateDatosPersonales(formData);
      await Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'Datos actualizados correctamente',
        timer: 2000
      });
      await cargarDatos();
      setIsEditing(false);
    } catch (error) {
      Swal.fire('Error', error.message, 'error');
      setFormData(datosOriginales);
      setIsEditing(false);
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
    <div className="container mx-auto p-4">
      <div className="card bg-base-100 shadow-xl">
        <h2 className="text-2xl font-bold text-center text-white bg-primary p-4">
          Datos Personales
        </h2>
        
        <div className="card-body">
          <div className="overflow-x-auto">
            <table className="table w-full">
              <thead>
                <tr>
                  <th className="bg-base-200">Campo</th>
                  <th className="bg-base-200">Información</th>
                </tr>
              </thead>
              <tbody>
                {FIELDS.map(({ field, label }) => (
                  <tr key={field} className="hover">
                    <td className="font-bold">{label}</td>
                    <td>
                      {isEditing ? (
                        <input
                          type="text"
                          name={field}
                          value={formData[field] || ''}
                          onChange={manejarCambioInput}
                          className="input input-bordered input-sm w-full"
                          disabled={field === 'idusuarios'}
                        />
                      ) : (
                        <div className="flex items-center justify-between">
                          <span>{datosOriginales[field]}</span>
                          {field !== 'idusuarios' && (
                            <FontAwesomeIcon 
                              icon={faPen} 
                              className="text-gray-400 ml-2 cursor-pointer"
                              onClick={() => setIsEditing(true)}
                            />
                          )}
                        </div>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          
          <button 
            onClick={toggleEdicion}
            className={`btn mt-4 ${isEditing ? 'btn-success' : 'btn-primary'}`}
          >
            <FontAwesomeIcon 
              icon={isEditing ? faSave : faEdit} 
              className="mr-2" 
            />
            {isEditing ? 'Guardar Cambios' : 'Actualizar Datos'}
          </button>
        </div>
      </div>
    </div>
  );
};

export default Porfile;