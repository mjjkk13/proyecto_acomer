import { createContext, useState, useEffect, useContext } from 'react';
import PropTypes from 'prop-types';
import { API_BASE_URL } from '../config/config';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [auth, setAuth] = useState(() => ({
    isAuthenticated: false,
    role: null,
    user: null,
  }));

  const login = async (usuario, password) => {
    try {
      const formData = new FormData();
      formData.append('usuario', usuario);
      formData.append('inputPassword', password);

      const response = await fetch(`${API_BASE_URL}/login.php`, {
        method: 'POST',
        credentials: 'include',
        body: formData,
      });

      const data = await response.json();
      
      if (data.success) {
        setAuth({
          isAuthenticated: true,
          role: data.rol,
          user: usuario,
        });
        return { success: true, redirect: data.redirect_url };
      } else {
        return { success: false, message: data.message };
      }
    } catch (error) {
      console.error('Error durante el login:', error);
      return { success: false, message: 'Error de conexión' };
    }
  };

  const checkSession = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/check_session.php`, {
        method: 'GET',
        credentials: 'include',
      });

      const data = await response.json();

      if (data.success) {
        setAuth({
          isAuthenticated: true,
          role: data.rol,
          user: data.usuario,
        });
        return true;
      } else {
        setAuth({
          isAuthenticated: false,
          role: null,
          user: null,
        });
        return false;
      }
    } catch (error) {
      console.error('Error al verificar sesión:', error);
      return false;
    }
  };

  useEffect(() => {
    checkSession();
  }, []);

  return (
    <AuthContext.Provider value={{ auth, login, checkSession }}>
      {children}
    </AuthContext.Provider>
  );
};

AuthProvider.propTypes = {
  children: PropTypes.node.isRequired,
};

// Custom hook to use AuthContext
export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth debe ser usado dentro de un AuthProvider');
  }
  return context;
};

export { AuthContext };
