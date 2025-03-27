import axios from 'axios';

// Configuración para el backend PHP (Endpoints principales)
const PHP_API = axios.create({
  baseURL: 'http://localhost/proyecto_acomer/server/php', // Ruta a tu backend PHP
  timeout: 10000,
});

// Configuración para el servicio de correos en Node.js
const NODE_API = axios.create({
  baseURL: 'http://localhost:5000', // Puerto del servidor Node.js
  timeout: 10000,
});

// Interceptor común para manejo de errores
const setupInterceptor = (instance) => {
  instance.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response) {
        console.error('Error del servidor:', error.response.data);
        const serverError = new Error(
          error.response.data?.message || 'Error del servidor'
        );
        serverError.status = error.response.status;
        return Promise.reject(serverError);
      } else if (error.request) {
        console.error('No se recibió respuesta:', error.request);
        return Promise.reject(new Error('El servidor no respondió'));
      } else {
        console.error('Error de configuración:', error.message);
        return Promise.reject(new Error('Error en la solicitud'));
      }
    }
  );
};

// Aplicar interceptor a ambas instancias
setupInterceptor(PHP_API);
setupInterceptor(NODE_API);

export const usuarioService = {
  // Registro de usuario (PHP)
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
      throw new Error(error.message || 'Error al registrar usuario');
    }
  },

  // Obtención de cursos (PHP)
  obtenerCursos: async () => {
    try {
      const response = await PHP_API.get('/obtenerCursos.php');
      return response.data?.data || response.data || [];
    } catch (error) {
      console.error('Error obteniendo cursos:', error);
      throw new Error(error.message || 'Error al cargar cursos');
    }
  },

  // Envío de correo (Node.js)
  enviarCorreo: async ({ correo, usuario, contrasena }) => {
    try {
      const response = await NODE_API.post('/enviar-credenciales', {
        destinatario: correo,
        usuario,
        contrasena,
      });

      if (!response.data.success) {
        throw new Error(response.data.message);
      }

      return {
        success: true,
        data: response.data,
        message: response.data.message,
      };
    } catch (error) {
      console.error('Error enviando correo:', error);
      throw new Error(
        error.message || 'Error al enviar las credenciales por correo'
      );
    }
  },
};