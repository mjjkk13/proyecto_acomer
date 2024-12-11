import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEye, faEyeSlash, faExclamationCircle } from '@fortawesome/free-solid-svg-icons';
import Swal from 'sweetalert2';
import '../../styles/Login.css';
import { useAuth } from '../../context/AuthContext';

const FormLogin = () => {
  const { login, auth } = useAuth();
  const navigate = useNavigate();
  const [usuario, setUsuario] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [errors, setErrors] = useState({ usuario: false, password: false });
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    if (auth.isAuthenticated) {
      navigate('/');  // Redirigimos si ya está autenticado
    }
  }, [auth.isAuthenticated, navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    // Validación de campos vacíos
    const newErrors = {
      usuario: usuario.trim() === '',
      password: password.trim() === '',
    };
    setErrors(newErrors);

    if (newErrors.usuario || newErrors.password) {
      return;
    }

    setIsLoading(true);

    try {
      const result = await login(usuario.trim(), password, { mode: 'no-cors' });

      if (result.success) {
        await Swal.fire({
          icon: 'success',
          title: '¡Bienvenido!',
          text: result.message,
          timer: 1500,
          showConfirmButton: false,
        });
        navigate(result.redirect || '/'); // Redirección predeterminada si no hay URL proporcionada
      } else {
        await Swal.fire({
          icon: 'error',
          title: 'Error de acceso',
          text: result.message,
          confirmButtonText: 'Intentar de nuevo',
        });
      }
    } catch (error) {
      console.error('Error en el login:', error);
      await Swal.fire({
        icon: 'error',
        title: 'Error de conexión',
        text: 'Hubo un problema al conectar con el servidor. Por favor, intenta más tarde.',
        confirmButtonText: 'Entendido',
      });
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="flex justify-center items-center">
      <form onSubmit={handleSubmit} id="loginForm" className="w-full max-w-sm p-3 space-y-5">
        {/* Campo de usuario */}
        <div className="form-control">
          <input
            type="text"
            className={`input input-bordered w-full ${errors.usuario ? 'border-red-500' : ''}`}
            id="usuario"
            name="usuario"
            placeholder="Usuario"
            value={usuario}
            onChange={(e) => {
              setUsuario(e.target.value);
              setErrors({ ...errors, usuario: false });
            }}
            disabled={isLoading}
          />
          {errors.usuario && (
            <div className="flex items-center text-red-500 text-sm mt-1">
              <FontAwesomeIcon icon={faExclamationCircle} className="mr-2" />
              Por favor, ingrese su usuario.
            </div>
          )}
        </div>

        {/* Campo de contraseña */}
        <div className="form-control relative">
          <div className="relative w-full">
            <input
              type={showPassword ? 'text' : 'password'}
              className={`input input-bordered w-full pr-10 ${errors.password ? 'border-red-500' : ''}`}
              id="inputPassword"
              name="inputPassword"
              placeholder="Contraseña"
              value={password}
              onChange={(e) => {
                setPassword(e.target.value);
                setErrors({ ...errors, password: false });
              }}
              disabled={isLoading}
            />
            <button
              type="button"
              className="absolute inset-y-0 right-3 flex items-center text-gray-500"
              onClick={() => setShowPassword(!showPassword)}
              tabIndex="-1"
              disabled={isLoading}
            >
              <FontAwesomeIcon icon={showPassword ? faEyeSlash : faEye} />
            </button>
          </div>
          {errors.password && (
            <div className="flex items-center text-red-500 text-sm mt-1">
              <FontAwesomeIcon icon={faExclamationCircle} className="mr-2" />
              Por favor, ingrese su contraseña.
            </div>
          )}
        </div>

        {/* Recordarme */}
        <div className="form-control">
          <label className="cursor-pointer label">
            <input 
              type="checkbox" 
              className="checkbox" 
              id="rememberMe"
              disabled={isLoading}
            />
            <span className="label-text">Recordarme</span>
          </label>
        </div>

        {/* Botón de inicio de sesión */}
        <div className="form-control mt-6">
          <button 
            type="submit" 
            className="btn btn-primary w-full"
            disabled={isLoading}
          >
            {isLoading ? 'Iniciando sesión...' : 'Iniciar Sesión'}
          </button>
        </div>
      </form>
    </div>
  );
};

export default FormLogin;
