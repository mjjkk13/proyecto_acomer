import { Link, useNavigate } from 'react-router-dom'; // Importar Link y useNavigate para manejar rutas de React Router
import logo from '../../../img/logo.png';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faListAlt, faQrcode, faUtensils, faUser, faSignOutAlt, faBars } from '@fortawesome/free-solid-svg-icons';
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


  return (
    <nav className="bg-[#27374D] text-white shadow-lg">
      <div className="container mx-auto px-4 py-2 flex justify-between items-center">
        {/* Logo y título */}
        <a className="flex items-center">
          <img src={logo} alt="Logo" className="w-10 h-10" />
          <span className="ml-2 text-xl font-bold">A Comer</span>
        </a>

        {/* Menú para pantallas grandes */}
        <ul className="hidden lg:flex space-x-4">
          <li>
            <Link to="/docente" className="hover:underline">
              <span className="mr-2">
                <FontAwesomeIcon icon={faListAlt} />
              </span>
              Consulta Listados
            </Link>
          </li>
          <li>
            <Link to="/docente/codigos-registrados" className="hover:underline">
              <span className="mr-2">
                <FontAwesomeIcon icon={faQrcode} />
              </span>
              Códigos Registrados
            </Link>
          </li>
          <li>
            <Link to="/docente/consultar-menu" className="hover:underline">
              <span className="mr-2">
                <FontAwesomeIcon icon={faUtensils} />
              </span>
              Consultar menú
            </Link>
          </li>
          <li>
            <Link to="/docente/datos-personales" className="hover:underline">
              <span className="mr-2">
                <FontAwesomeIcon icon={faUser} />
              </span>
              Datos Personales
            </Link>
          </li>
          <li>
            <a onClick={handleLogout} className="hover:underline">
              <span className="mr-2">
                <FontAwesomeIcon icon={faSignOutAlt} />
              </span>
              Cerrar Sesión
            </a>
          </li>
        </ul>

        {/* Botón y dropdown para pantallas pequeñas */}
        <div className="lg:hidden relative">
          <button
            onClick={toggleDropdown}
            className="text-white flex items-center focus:outline-none"
            aria-expanded={isDropdownOpen}
          >
            <FontAwesomeIcon icon={faBars} size="lg" />
          </button>
          <ul
            className={`absolute right-0 mt-2 bg-[#27374D] rounded-lg shadow-lg text-sm w-48 ${
              isDropdownOpen ? 'block' : 'hidden'
            }`}
          >
            <li>
              <Link to="/docente" className="block px-4 py-2 hover:bg-[#1c2a3a]">
                <span className="mr-2">
                  <FontAwesomeIcon icon={faListAlt} />
                </span>
                Consulta Listados
              </Link>
            </li>
            <li>
              <Link to="/docente/codigos-registrados" className="block px-4 py-2 hover:bg-[#1c2a3a]">
                <span className="mr-2">
                  <FontAwesomeIcon icon={faQrcode} />
                </span>
                Códigos Registrados
              </Link>
            </li>
            <li>
              <Link to="/docente/consultar-menu" className="block px-4 py-2 hover:bg-[#1c2a3a]">
                <span className="mr-2">
                  <FontAwesomeIcon icon={faUtensils} />
                </span>
                Consultar menú
              </Link>
            </li>
            <li>
              <Link to="/docente/datos-personales" className="block px-4 py-2 hover:bg-[#1c2a3a]">
                <span className="mr-2">
                  <FontAwesomeIcon icon={faUser} />
                </span>
                Datos Personales
              </Link>
            </li>
            <li>
              <a onClick={handleLogout} className="block px-4 py-2 hover:bg-[#1c2a3a]">
                <span className="mr-2">
                  <FontAwesomeIcon icon={faSignOutAlt} />
                </span>
                Cerrar Sesión
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
