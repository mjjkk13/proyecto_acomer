import { Link, useNavigate } from 'react-router-dom';
import { useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faListAlt, faQrcode, faUtensils, faUserPlus, faUser, faSignOutAlt, faBars } from '@fortawesome/free-solid-svg-icons';
import logo from '../../../img/logo.png';
import Swal from 'sweetalert2';

const API_URL = import.meta.env.VITE_API_URL;

const menuItems = [
  { to: '/estudiante', icon: faQrcode, label: 'Escanear QR' },
  { to: '/estudiante/codigos-registrados', icon: faListAlt, label: 'Códigos Registrados' },
  { to: '/estudiante/consultar-menu', icon: faUtensils, label: 'Consultar Menú' },
  { to: '/estudiante/AddStudent', icon: faUserPlus, label: 'Agregar Estudiante' },
  { to: '/estudiante/perfil', icon: faUser, label: 'Datos Personales' },
];

const NavbarEstudiante = () => {
  const navigate = useNavigate();
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);

  const handleLogout = async (e) => {
    e.preventDefault();
    try {
      const res = await fetch(`${API_URL}/logout.php`, {
        method: 'POST',
        credentials: 'include',
      });
      if (!res.ok) throw new Error('No se pudo cerrar sesión.');

      await Swal.fire({
        icon: 'success',
        title: 'Sesión cerrada',
        text: 'Hasta pronto',
      });
      setIsDropdownOpen(false);
      navigate('/login');
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.message,
      });
    }
  };

  const toggleDropdown = () => setIsDropdownOpen(v => !v);
  const closeDropdown = () => setIsDropdownOpen(false);

  return (
    <nav className="bg-[#27374D] text-white shadow-lg relative z-50">
      <div className="container mx-auto px-4 py-2 flex justify-between items-center">
        {/* Logo */}
        <Link to="/estudiante" className="flex items-center">
          <img src={logo} alt="Logo" className="w-10 h-10" />
          <span className="ml-2 text-xl font-bold">A Comer</span>
        </Link>

        {/* Desktop Menu */}
        <ul className="hidden lg:flex space-x-2">
          {menuItems.map(({ to, icon, label }) => (
            <li key={label}>
              <Link to={to} className="hover:underline flex items-center">
                <FontAwesomeIcon icon={icon} className="mr-2" />
                {label}
              </Link>
            </li>
          ))}
          <li>
            <button onClick={handleLogout} className="hover:underline cursor-pointer flex items-center bg-transparent border-0 p-0">
              <FontAwesomeIcon icon={faSignOutAlt} className="mr-2" />
              Cerrar Sesión
            </button>
          </li>
        </ul>

        {/* Mobile Menu */}
        <div className="lg:hidden relative">
          <button
            onClick={toggleDropdown}
            className="text-white flex items-center focus:outline-none"
            aria-label="Abrir menú"
            aria-expanded={isDropdownOpen}
          >
            <FontAwesomeIcon icon={faBars} size="lg" />
          </button>

          {isDropdownOpen && (
            <>
              {/* Overlay para cerrar menú al clicar fuera */}
              <div
                onClick={closeDropdown}
                className="fixed inset-0 bg-black opacity-30 z-40"
              />

              <ul className="absolute right-0 mt-2 bg-[#27374D] rounded-lg shadow-lg text-sm w-48 z-50">
                {menuItems.map(({ to, icon, label }) => (
                  <li key={label}>
                    <Link
                      to={to}
                      className="block px-4 py-2 hover:bg-[#1c2a3a] flex items-center"
                      onClick={closeDropdown}
                    >
                      <FontAwesomeIcon icon={icon} className="mr-2" />
                      {label}
                    </Link>
                  </li>
                ))}
                <li>
                  <button
                    onClick={() => { handleLogout(); closeDropdown(); }}
                    className="block px-4 py-2 hover:bg-[#1c2a3a] flex items-center w-full text-left bg-transparent border-0 p-0 cursor-pointer"
                  >
                    <FontAwesomeIcon icon={faSignOutAlt} className="mr-2" />
                    Cerrar Sesión
                  </button>
                </li>
              </ul>
            </>
          )}
        </div>
      </div>
    </nav>
  );
};

export default NavbarEstudiante;
