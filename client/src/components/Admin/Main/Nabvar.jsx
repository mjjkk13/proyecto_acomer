import { Link, useNavigate } from 'react-router-dom';
import logo from '../../../img/logo.png';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
  faListAlt,
  faUtensils,
  faUserPlus,
  faUser,
  faSignOutAlt,
  faBars,
  faChartBar,
} from '@fortawesome/free-solid-svg-icons';
import { useState } from 'react';
import Swal from 'sweetalert2';

const Navbar = () => {
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const navigate = useNavigate();

  const handleLogout = (e) => {
    e.preventDefault();
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

  const menuItems = [
    { to: '/admin', icon: faChartBar, label: 'Consultar Estadísticas' },
    { to: '/admin/codigos-registrados', icon: faListAlt, label: 'QR Registrados' },
    { to: '/admin/gestionar-menu', icon: faUtensils, label: 'Gestionar Menú' },
    { to: '/admin/gestionar-usuarios', icon: faUserPlus, label: 'Gestionar Usuarios' },
    { to: '/admin/perfil', icon: faUser, label: 'Mi Perfil' },
  ];

  return (
    <nav className="bg-[#27374D] text-white shadow-lg">
      <div className="container mx-auto px-4 py-2 flex justify-between items-center">
        {/* Logo */}
        <a className="flex items-center cursor-pointer">
          <img src={logo} alt="Logo" className="w-10 h-10" />
          <span className="ml-2 text-xl font-bold">A Comer</span>
        </a>

        {/* Desktop Menu */}
        <ul className="hidden lg:flex space-x-1.5">
          {menuItems.map(({ to, icon, label }) => (
            <li key={label}>
              <Link to={to} className="hover:underline flex items-center">
                <FontAwesomeIcon icon={icon} className="mr-2" />
                {label}
              </Link>
            </li>
          ))}
          <li>
            <a onClick={handleLogout} className="hover:underline cursor-pointer flex items-center">
              <FontAwesomeIcon icon={faSignOutAlt} className="mr-2" />
              Cerrar Sesión
            </a>
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
            <ul
              className="absolute right-0 mt-2 bg-[#27374D] rounded-lg shadow-lg text-sm w-48"
              onMouseLeave={() => setIsDropdownOpen(false)}
            >
              {menuItems.map(({ to, icon, label }) => (
                <li key={label}>
                  <Link to={to} className="block px-4 py-2 hover:bg-[#1c2a3a] flex items-center">
                    <FontAwesomeIcon icon={icon} className="mr-2" />
                    {label}
                  </Link>
                </li>
              ))}
              <li>
                <a onClick={handleLogout} className="block px-4 py-2 hover:bg-[#1c2a3a] flex items-center cursor-pointer">
                  <FontAwesomeIcon icon={faSignOutAlt} className="mr-2" />
                  Cerrar Sesión
                </a>
              </li>
            </ul>
          )}
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
