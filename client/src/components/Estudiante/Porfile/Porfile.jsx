import { useEffect, useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPen, faSave, faEdit } from '@fortawesome/free-solid-svg-icons';
import Swal from 'sweetalert2';
import { getDatosPersonales, updateDatosPersonales } from '../../services/porfile';

const FIELDS = [
  { field: 'nombre', label: 'Nombre' },
  { field: 'apellido', label: 'Apellido' },
  { field: 'email', label: 'Email' },
  { field: 'telefono', label: 'Teléfono' },
  { field: 'direccion', label: 'Dirección' },
  { field: 'nuevaContraseña', label: 'Nueva Contraseña' },
  { field: 'confirmarContraseña', label: 'Confirmar Contraseña' },
];

const Porfile = () => {
  const [isEditing, setIsEditing] = useState(false);
  const [datosOriginales, setDatosOriginales] = useState({});
  const [formData, setFormData] = useState({});
  const [cargando, setCargando] = useState(true);
  const [error, setError] = useState('');

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

    // Verificar que las contraseñas coinciden si se están cambiando
    if (formData.nuevaContraseña && formData.nuevaContraseña !== formData.confirmarContraseña) {
      setError('Las contraseñas no coinciden');
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
      setError('');
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
    <div className="max-w-md mx-auto p-4">
      <div className="card bg-base-100 shadow-md">
        <h2 className="text-lg font-bold text-center text-white bg-primary p-3">
          Datos Personales
        </h2>
        
        <div className="card-body p-4">
          <div className="overflow-x-auto">
            <table className="table table-sm w-full text-sm">
              <thead>
                <tr>
                  <th className="bg-base-200 p-2">Campo</th>
                  <th className="bg-base-200 p-2">Información</th>
                </tr>
              </thead>
              <tbody>
                {FIELDS.map(({ field, label }) => (
                  <tr key={field} className="hover">
                    <td className="font-bold p-2">{label}</td>
                    <td className="p-2">
                      {isEditing ? (
                        field.includes('Contraseña') ? (
                          <input
                            type="password"
                            name={field}
                            value={formData[field] || ''}
                            onChange={manejarCambioInput}
                            className="input input-bordered input-xs w-full"
                          />
                        ) : (
                          <input
                            type="text"
                            name={field}
                            value={formData[field] || ''}
                            onChange={manejarCambioInput}
                            className="input input-bordered input-xs w-full"
                          />
                        )
                      ) : (
                        <div className="flex items-center justify-between">
                          <span>{field === 'nuevaContraseña' || field === 'confirmarContraseña' ? '••••••••' : datosOriginales[field]}</span>
                          <FontAwesomeIcon 
                            icon={faPen} 
                            className="text-gray-400 ml-2 cursor-pointer"
                            onClick={() => setIsEditing(true)}
                          />
                        </div>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          
          {error && <div className="text-red-500 text-sm">{error}</div>}

          <button 
            onClick={toggleEdicion}
            className={`btn btn-xs mt-4 ${isEditing ? 'btn-success' : 'btn-primary'}`}
          >
            <FontAwesomeIcon 
              icon={isEditing ? faSave : faEdit} 
              className="mr-2" 
            />
            {isEditing ? 'Guardar' : 'Editar'}
          </button>
        </div>
      </div>
    </div>
  );
};

export default Porfile;
