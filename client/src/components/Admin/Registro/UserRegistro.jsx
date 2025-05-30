import { useState } from 'react';
import { usuarioService, mailService } from '../../services/mailService';
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
    user: ''
  });

  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState({});
  const [touchedFields, setTouchedFields] = useState({});

  // Función para obtener clases de input basadas en estado
  const getInputClasses = (fieldName) => {
    const baseClasses = "w-full text-sm bg-white border-2 rounded-sm px-3 py-1.5 text-gray-700 focus:outline-none transition-colors placeholder-gray-400";
    const errorClasses = "border-red-500";
    const normalClasses = "border-gray-300 hover:border-gray-400 focus:border-gray-500";
    
    const hasError = (touchedFields[fieldName] && !formData[fieldName].toString().trim()) || errors[fieldName];
    
    return `${baseClasses} ${hasError ? errorClasses : normalClasses}`;
  };

  // Función para obtener clases de select basadas en estado
  const getSelectClasses = (fieldName) => {
    const baseClasses = "w-full text-sm bg-white border-2 rounded-sm pr-8 py-1.5 pl-3 text-gray-700 focus:outline-none transition-colors cursor-pointer bg-no-repeat bg-[right_0.5rem_center] bg-[length:1.2em]";
    const errorClasses = "border-red-500";
    const normalClasses = "border-gray-300 hover:border-gray-400 focus:border-gray-500";
    const arrowIcon = "bg-[url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e')]";
    
    const hasError = (touchedFields[fieldName] && !formData[fieldName]) || errors[fieldName];
    
    return `${baseClasses} ${arrowIcon} ${hasError ? errorClasses : normalClasses}`;
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

    if (!mailService.validarEmail(formData.correo)) {
      newErrors.correo = 'Correo inválido';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

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

      await mailService.enviarCredenciales(
        formData.correo,
        formData.user,
        formData.contrasena
      );

      Swal.fire({
        title: '¡Registro exitoso!',
        text: 'Usuario creado correctamente',
        icon: 'success',
        confirmButtonColor: '#4f46e5',
        confirmButtonText: 'Aceptar'
      });

      // Resetear formulario
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
        user: ''
      });
      setTouchedFields({});

    } catch (error) {
      Swal.fire({
        title: 'Error',
        text: error?.message || 'Error al registrar usuario',
        icon: 'error',
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Aceptar'
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 py-4 px-2 sm:px-4">
      <div className="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200">
        <h2 className="text-xl sm:text-2xl font-medium text-center text-gray-700 mb-4 sm:mb-6">
          Registro de Nuevo Usuario
        </h2>

        <form onSubmit={handleSubmit}>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {/* Columna 1 */}
            <div className="space-y-4">
              {/* Nombre */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Nombres
                </label>
                <input
                  type="text"
                  name="nombre"
                  value={formData.nombre}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  className={getInputClasses('nombre')}
                  placeholder="Ej: Juan Carlos"
                />
                {errors.nombre && (
                  <p className="mt-1 text-xs text-red-500">{errors.nombre}</p>
                )}
              </div>

              {/* Usuario */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Usuario
                </label>
                <input
                  type="text"
                  name="user"
                  value={formData.user}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  className={getInputClasses('user')}
                  placeholder="Nombre de usuario"
                />
                {errors.user && (
                  <p className="mt-1 text-xs text-red-500">{errors.user}</p>
                )}
              </div>

              {/* Contraseña */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Contraseña
                </label>
                <div className="relative">
                  <input
                    type={showPassword ? 'text' : 'password'}
                    name="contrasena"
                    value={formData.contrasena}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={getInputClasses('contrasena')}
                    placeholder="Ingrese contraseña"
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-600"
                  >
                    <FontAwesomeIcon icon={showPassword ? faEyeSlash : faEye} size="sm" />
                  </button>
                </div>
                {errors.contrasena && (
                  <p className="mt-1 text-xs text-red-500">{errors.contrasena}</p>
                )}
              </div>

              {/* Dirección */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Dirección
                </label>
                <input
                  type="text"
                  name="direccion"
                  value={formData.direccion}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  className={getInputClasses('direccion')}
                  placeholder="Cra 45 #12-34"
                />
                {errors.direccion && (
                  <p className="mt-1 text-xs text-red-500">{errors.direccion}</p>
                )}
              </div>

              {/* Documento */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Número Documento
                </label>
                <input
                  type="number"
                  name="documento"
                  value={formData.documento}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  className={getInputClasses('documento')}
                  placeholder="123456789"
                />
                {errors.documento && (
                  <p className="mt-1 text-xs text-red-500">{errors.documento}</p>
                )}
              </div>
            </div>

            {/* Columna 2 */}
            <div className="space-y-4">
              {/* Apellido */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Apellidos
                </label>
                <input
                  type="text"
                  name="apellido"
                  value={formData.apellido}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  className={getInputClasses('apellido')}
                  placeholder="Ej: Pérez García"
                />
                {errors.apellido && (
                  <p className="mt-1 text-xs text-red-500">{errors.apellido}</p>
                )}
              </div>

              {/* Correo */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Correo
                </label>
                <input
                  type="email"
                  name="correo"
                  value={formData.correo}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  className={getInputClasses('correo')}
                  placeholder="usuario@dominio.com"
                />
                {errors.correo && (
                  <p className="mt-1 text-xs text-red-500">{errors.correo}</p>
                )}
              </div>

              {/* Teléfono */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Teléfono
                </label>
                <input
                  type="tel"
                  name="celular"
                  value={formData.celular}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  className={getInputClasses('celular')}
                  placeholder="3001234567"
                />
                {errors.celular && (
                  <p className="mt-1 text-xs text-red-500">{errors.celular}</p>
                )}
              </div>

              {/* Tipo Documento */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Tipo Documento
                </label>
                <select
                  name="tipoDocumento"
                  value={formData.tipoDocumento}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  className={getSelectClasses('tipoDocumento')}
                >
                  <option value="" disabled className="text-gray-400">Seleccione...</option>
                  <option value="CC" className="text-gray-700">Cédula de Ciudadanía</option>
                  <option value="CE" className="text-gray-700">Cédula de Extranjería</option>
                  <option value="TI" className="text-gray-700">Tarjeta de Identidad</option>
                  <option value="PA" className="text-gray-700">Pasaporte</option>
                </select>
                {errors.tipoDocumento && (
                  <p className="mt-1 text-xs text-red-500">{errors.tipoDocumento}</p>
                )}
              </div>

              {/* Rol */}
              <div>
                <label className="block text-sm font-medium text-gray-600 mb-1">
                  Rol
                </label>
                <select
                  name="rol"
                  value={formData.rol}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  className={getSelectClasses('rol')}
                >
                  <option value="" disabled className="text-gray-400">Seleccione...</option>
                  <option value="Estudiante SS" className="text-gray-700">Estudiante SS</option>
                  <option value="Docente" className="text-gray-700">Docente</option>
                  <option value="Administrador" className="text-gray-700">Administrador</option>
                </select>
                {errors.rol && (
                  <p className="mt-1 text-xs text-red-500">{errors.rol}</p>
                )}
              </div>
            </div>
          </div>

          {/* Botón de envío */}
          <div className="mt-8 text-center">
            <button
              type="submit"
              disabled={loading}
              className={`w-full sm:w-48 px-6 py-2 rounded-sm font-medium text-white transition-colors
                ${loading 
                  ? 'bg-indigo-400 cursor-not-allowed' 
                  : 'bg-indigo-600 hover:bg-indigo-700'}`}
            >
              {loading ? (
                <span className="flex items-center justify-center">
                  <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Registrando...
                </span>
              ) : 'Registrar Usuario'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default UserRegistro;