import { Link, useNavigate } from 'react-router-dom';
import logo from '../../../img/logo.png';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
  faListAlt,
  faUtensils,
  faUser,
  faSignOutAlt,
  faBars,
  faListCheck,
} from '@fortawesome/free-solid-svg-icons';
import { useState } from 'react';
import Swal from 'sweetalert2';

const NavbarDocente = () => {
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const navigate = useNavigate();

  const handleLogout = (e) => {
    if (e) e.preventDefault();
    fetch('http://localhost/proyecto_acomer/server/php/logout.php', {
      method: 'POST',
      credentials: 'include',
    })
      .then((response) => {
        if (response.ok) {
          Swal.fire({
            icon: 'success',
            title: 'Sesión cerrada',
            text: 'Hasta pronto',
          }).then(() => navigate('/login'));
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo cerrar sesión.',
          });
        }
      })
      .catch((error) => {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: `Error: ${error.message}`,
        });
      });
  };

  const toggleDropdown = () => {
    setIsDropdownOpen((prev) => !prev);
  };

  const closeDropdown = () => {
    setIsDropdownOpen(false);
  };

  const menuItems = [
    { to: '/docente', icon: faListCheck, label: 'Registro Asistencia' },
    { to: '/docente/codigos-generados', icon: faListAlt, label: 'QR Generados' },
    { to: '/docente/consultar-menu', icon: faUtensils, label: 'Consultar Menú' },
    { to: '/docente/perfil', icon: faUser, label: 'Mi Perfil' },
  ];

  return (
    <nav className="bg-[#27374D] text-white shadow-lg">
      <div className="container mx-auto px-4 py-2 flex justify-between items-center">
        {/* Logo */}
        <Link to="/docente" className="flex items-center">
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
            <button onClick={handleLogout} className="hover:underline cursor-pointer flex items-center">
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
            <ul className="absolute top-full right-0 mt-2 bg-[#27374D] rounded-lg shadow-lg text-sm w-48 z-50">
              {menuItems.map(({ to, icon, label }) => (
                <li key={label}>
                  <Link to={to} className="block px-4 py-2 hover:bg-[#1c2a3a] flex items-center" onClick={closeDropdown}>
                    <FontAwesomeIcon icon={icon} className="mr-2" />
                    {label}
                  </Link>
                </li>
              ))}
              <li>
                <button
                  onClick={(e) => { handleLogout(e); closeDropdown(); }}
                  className="block px-4 py-2 hover:bg-[#1c2a3a] flex items-center w-full text-left"
                >
                  <FontAwesomeIcon icon={faSignOutAlt} className="mr-2" />
                  Cerrar Sesión
                </button>
              </li>
            </ul>
          )}
        </div>
      </div>
    </nav>
  );
};

export default NavbarDocente;
