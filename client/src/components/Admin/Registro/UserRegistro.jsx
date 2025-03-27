import { useState, useEffect } from 'react';
import { usuarioService } from '../../services/api';
import Swal from 'sweetalert2';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEye, faEyeSlash } from '@fortawesome/free-solid-svg-icons';

const UserRegistro = () => {
  const [formData, setFormData] = useState({
    nombre: '',
    apellido: '',
    correo: '',
    contrasena: '',
    celular: '',
    direccion: '',
    documento: '',
    tipoDocumento: '',
    rol: '',
    user: '',
    cursos: ''
  });

  const [showPassword, setShowPassword] = useState(false);
  const [cursos, setCursos] = useState([]);
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState({});
  const [touchedFields, setTouchedFields] = useState({});

  // Funciones para estilos
  const getInputClass = (fieldName) => {
    const isError = (touchedFields[fieldName] && !formData[fieldName].toString().trim()) || errors[fieldName];
    return `w-full text-sm bg-white border-2 rounded-sm px-3 py-1.5 text-gray-700
      ${isError 
        ? 'border-red-500' 
        : 'border-gray-300 hover:border-gray-400 focus:border-gray-500'}
      focus:outline-none transition-colors placeholder-gray-400`;
  };

  const getSelectClass = (fieldName) => {
    const isError = (touchedFields[fieldName] && !formData[fieldName]) || errors[fieldName];
    return `w-full text-sm bg-white border-2 rounded-sm pr-8 py-1.5 pl-3 text-gray-700
      ${isError 
        ? 'border-red-500' 
        : 'border-gray-300 hover:border-gray-400 focus:border-gray-500'}
      focus:outline-none transition-colors cursor-pointer
      bg-no-repeat bg-[right_0.5rem_center] bg-[length:1.2em]
      bg-[url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")]`;
  };

  const validateFields = () => {
    const newErrors = {};
    const requiredFields = [
      'nombre', 'apellido', 'correo', 'contrasena',
      'celular', 'direccion', 'documento', 'tipoDocumento',
      'rol', 'user'
    ];

    requiredFields.forEach(field => {
      if (!formData[field].toString().trim()) {
        newErrors[field] = `${field.charAt(0).toUpperCase() + field.slice(1)} es requerido`;
      }
    });

    if (!formData.correo.includes('@')) {
      newErrors.correo = 'Correo inválido';
    }

    if (formData.rol === 'Docente' && !formData.cursos) {
      newErrors.cursos = 'Seleccione un curso';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  useEffect(() => {
    const obtenerCursos = async () => {
      try {
        const response = await usuarioService.obtenerCursos();
        let cursosData = [];

        if (response.data?.success) {
          cursosData = response.data.data || [];
        } else if (Array.isArray(response.data)) {
          cursosData = response.data;
        } else if (Array.isArray(response)) {
          cursosData = response;
        }

        const cursosNormalizados = cursosData.map(curso => ({
          id: curso.id,
          nombre: curso.nombre
        }));

        setCursos(cursosNormalizados);

      } catch (error) {
        console.error('Error obteniendo cursos:', error);
        Swal.fire({
          title: 'Error',
          text: 'No se pudieron cargar los cursos',
          icon: 'error'
        });
      }
    };
    
    obtenerCursos();
  }, []);

  const handleBlur = (e) => {
    const { name } = e.target;
    setTouchedFields(prev => ({ ...prev, [name]: true }));
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: null
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Marcar todos los campos como tocados
    const allFields = Object.keys(formData);
    const touched = allFields.reduce((acc, field) => {
      acc[field] = true;
      return acc;
    }, {});
    setTouchedFields(touched);

    if (!validateFields()) return;
    
    setLoading(true);

    try {
      const registroResponse = await usuarioService.registrarUsuario(formData);
      
      if (!registroResponse.data?.success) {
        throw new Error(registroResponse.data?.message || 'Error en el registro');
      }

      await usuarioService.enviarCorreo({
        correo: formData.correo,
        usuario: formData.user,
        contrasena: formData.contrasena
      });

      Swal.fire({
        title: '¡Registro exitoso!',
        text: 'Usuario creado correctamente',
        icon: 'success',
        confirmButtonColor: '#4f46e5',
        confirmButtonText: 'Aceptar'
      });

      setFormData({
        nombre: '',
        apellido: '',
        correo: '',
        contrasena: '',
        celular: '',
        direccion: '',
        documento: '',
        tipoDocumento: '',
        rol: '',
        user: '',
        cursos: ''
      });
      setTouchedFields({});

    } catch (error) {
      Swal.fire({
        title: 'Error',
        text: error.message || 'Error al registrar usuario',
        icon: 'error',
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Aceptar'
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 py-4 px-2">
      <div className="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-4 border border-gray-200">
        <h2 className="text-xl font-medium text-center text-gray-700 mb-4">
          Registro de Nuevo Usuario
        </h2>

        <form onSubmit={handleSubmit}>
          <table className="w-full">
            <tbody>
              {/* Fila 1: Nombres y Apellidos */}
              <tr>
                <td className="py-2 pr-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Nombres</span>
                  </label>
                </td>
                <td className="py-2">
                  <input
                    type="text"
                    name="nombre"
                    value={formData.nombre}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getInputClass('nombre')}
                    placeholder="Ej: Juan Carlos"
                  />
                  {errors.nombre && <span className="text-xs text-red-500 mt-1 block">{errors.nombre}</span>}
                </td>
                <td className="py-2 px-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Apellidos</span>
                  </label>
                </td>
                <td className="py-2">
                  <input
                    type="text"
                    name="apellido"
                    value={formData.apellido}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getInputClass('apellido')}
                    placeholder="Ej: Pérez García"
                  />
                  {errors.apellido && <span className="text-xs text-red-500 mt-1 block">{errors.apellido}</span>}
                </td>
              </tr>

              {/* Fila 2: Usuario y Correo */}
              <tr>
                <td className="py-2 pr-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Usuario</span>
                  </label>
                </td>
                <td className="py-2">
                  <input
                    type="text"
                    name="user"
                    value={formData.user}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getInputClass('user')}
                    placeholder="Nombre de usuario"
                  />
                  {errors.user && <span className="text-xs text-red-500 mt-1 block">{errors.user}</span>}
                </td>
                <td className="py-2 px-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Correo</span>
                  </label>
                </td>
                <td className="py-2">
                  <input
                    type="email"
                    name="correo"
                    value={formData.correo}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getInputClass('correo')}
                    placeholder="usuario@dominio.com"
                  />
                  {errors.correo && <span className="text-xs text-red-500 mt-1 block">{errors.correo}</span>}
                </td>
              </tr>

              {/* Fila 3: Contraseña y Teléfono */}
              <tr>
                <td className="py-2 pr-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Contraseña</span>
                  </label>
                </td>
                <td className="py-2">
                  <div className="relative">
                    <input
                      type={showPassword ? 'text' : 'password'}
                      name="contrasena"
                      value={formData.contrasena}
                      onChange={handleChange}
                      onBlur={handleBlur}
                      className={getInputClass('contrasena')}
                      placeholder="Ingrese contraseña"
                    />
                    <button
                      type="button"
                      onClick={() => setShowPassword(!showPassword)}
                      className="absolute right-2 top-2 text-gray-500 hover:text-gray-600 text-sm"
                    >
                      <FontAwesomeIcon icon={showPassword ? faEyeSlash : faEye} size="xs" />
                    </button>
                  </div>
                  {errors.contrasena && <span className="text-xs text-red-500 mt-1 block">{errors.contrasena}</span>}
                </td>
                <td className="py-2 px-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Teléfono</span>
                  </label>
                </td>
                <td className="py-2">
                  <input
                    type="tel"
                    name="celular"
                    value={formData.celular}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getInputClass('celular')}
                    placeholder="3001234567"
                  />
                  {errors.celular && <span className="text-xs text-red-500 mt-1 block">{errors.celular}</span>}
                </td>
              </tr>

              {/* Fila 4: Dirección y Tipo Documento */}
              <tr>
                <td className="py-2 pr-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Dirección</span>
                  </label>
                </td>
                <td className="py-2">
                  <input
                    type="text"
                    name="direccion"
                    value={formData.direccion}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getInputClass('direccion')}
                    placeholder="Cra 45 #12-34"
                  />
                  {errors.direccion && <span className="text-xs text-red-500 mt-1 block">{errors.direccion}</span>}
                </td>
                <td className="py-2 px-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Tipo Documento</span>
                  </label>
                </td>
                <td className="py-2">
                  <select
                    name="tipoDocumento"
                    value={formData.tipoDocumento}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getSelectClass('tipoDocumento')}
                  >
                    <option value="" disabled className="text-gray-400">Seleccione...</option>
                    <option value="CC" className="text-gray-700">Cédula de Ciudadanía</option>
                    <option value="CE" className="text-gray-700">Cédula de Extranjería</option>
                    <option value="TI" className="text-gray-700">Tarjeta de Identidad</option>
                    <option value="PA" className="text-gray-700">Pasaporte</option>
                  </select>
                  {errors.tipoDocumento && <span className="text-xs text-red-500 mt-1 block">{errors.tipoDocumento}</span>}
                </td>
              </tr>

              {/* Fila 5: Documento y Rol */}
              <tr>
                <td className="py-2 pr-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Número Documento</span>
                  </label>
                </td>
                <td className="py-2">
                  <input
                    type="number"
                    name="documento"
                    value={formData.documento}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getInputClass('documento')}
                    placeholder="123456789"
                  />
                  {errors.documento && <span className="text-xs text-red-500 mt-1 block">{errors.documento}</span>}
                </td>
                <td className="py-2 px-2">
                  <label className="label py-1">
                    <span className="label-text text-sm text-gray-600">Rol</span>
                  </label>
                </td>
                <td className="py-2">
                  <select
                    name="rol"
                    value={formData.rol}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getSelectClass('rol')}
                  >
                    <option value="" disabled className="text-gray-400">Seleccione...</option>
                    <option value="Estudiante SS" className="text-gray-700">Estudiante SS</option>
                    <option value="Docente" className="text-gray-700">Docente</option>
                    <option value="Administrador" className="text-gray-700">Administrador</option>
                  </select>
                  {errors.rol && <span className="text-xs text-red-500 mt-1 block">{errors.rol}</span>}
                </td>
              </tr>

              {/* Fila 6: Cursos (Docentes) */}
              {formData.rol === 'Docente' && (
                <tr>
                  <td className="py-2 pr-2">
                    <label className="label py-1">
                      <span className="label-text text-sm text-gray-600">Cursos</span>
                    </label>
                  </td>
                  <td className="py-2" colSpan="3">
                    <select
                      name="cursos"
                      value={formData.cursos}
                      onChange={handleChange}
                      onBlur={handleBlur}
                      className={getSelectClass('cursos')}
                    >
                      <option value="" disabled className="text-gray-400">Seleccione...</option>
                      {cursos.map(curso => (
                        <option 
                          key={curso.id} 
                          value={curso.id}
                          className="text-gray-700"
                        >
                          {curso.nombre}
                        </option>
                      ))}
                    </select>
                    {errors.cursos && <span className="text-xs text-red-500 mt-1 block">{errors.cursos}</span>}
                  </td>
                </tr>
              )}
            </tbody>
          </table>

          <div className="mt-6 text-center">
            <button
              type="submit"
              className={`w-48 bg-indigo-600 hover:bg-indigo-700 text-white 
                ${loading ? 'opacity-75 cursor-not-allowed' : ''} 
                px-6 py-2 rounded-sm font-medium transition-colors`}
              disabled={loading}
            >
              {loading ? 'Registrando...' : 'Registrar Usuario'}
            </button>
          </div>
        </form>
      </div>

      <style>{`
        select::-ms-expand {
          display: none;
        }
        
        select {
          -webkit-appearance: none;
          -moz-appearance: none;
          appearance: none;
        }
        
        input::placeholder {
          color: #9ca3af;
        }
      `}</style>
    </div>
  );
};

export default UserRegistro;