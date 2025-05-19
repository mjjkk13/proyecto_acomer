import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_URL;

const PHP_API = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
});

// Interceptor para manejo de errores
PHP_API.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response) {
      error.message = error.response.data?.message || 'Error del servidor';
    }
    return Promise.reject(error);
  }
);

export const usuarioService = {
  /**
   * Registra un nuevo usuario
   * @param {Object} formData - Datos del usuario
   * @returns {Promise<Object>}
   */
  registrarUsuario: async (formData) => {
    try {
      const response = await PHP_API.post('/registrarUsuario.php', formData);
      return {
        success: true,
        data: response.data,
        message: response.data?.message || 'Registro exitoso',
      };
    } catch (error) {
      console.error('Error en registro:', error);
      throw error;
    }
  },

  /**
   * Obtiene la lista de cursos
   * @returns {Promise<Array>}
   */
  obtenerCursos: async () => {
    try {
      const response = await PHP_API.get('/obtenerCursos.php');
      return response.data?.data || response.data || [];
    } catch (error) {
      console.error('Error obteniendo cursos:', error);
      throw error;
    }
  }
};

export const mailService = {
  /**
   * Envía credenciales por correo
   * @param {string} destinatario 
   * @param {string} usuario 
   * @param {string} contrasena 
   * @returns {Promise<Object>}
   */
  enviarCredenciales: async (destinatario, usuario, contrasena) => {
    try {
      if (!destinatario || !usuario || !contrasena) {
        throw new Error('Todos los campos son requeridos');
      }

      const response = await PHP_API.post('/correo.php', {
        destinatario,
        usuario,
        contrasena
      });

      return response.data;
    } catch (error) {
      console.error('Error enviando credenciales:', error);
      throw error;
    }
  },

  /**
   * Verifica si el servidor de correo está disponible
   * @returns {Promise<boolean>}
   */
  verificarServidorCorreo: async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/correo.php`, {
        method: 'HEAD',
      });
      return response.ok;
    } catch (error) {
      console.error('Error verificando servidor:', error);
      return false;
    }
  },

  /**
   * Valida un email
   * @param {string} email 
   * @returns {boolean}
   */
  validarEmail: (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }
};