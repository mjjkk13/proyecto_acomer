import { Link, useNavigate } from "react-router-dom";
import logo from "../../../img/logo.png";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faListAlt,
  faUtensils,
  faUserPlus,
  faUser,
  faSignOutAlt,
  faBars,
  faChartBar,
  faUserEdit,
  faBook,
  faSchool,
} from "@fortawesome/free-solid-svg-icons";
import { useState } from "react";
import Swal from "sweetalert2";
import PropTypes from "prop-types";

const API_URL = import.meta.env.VITE_API_URL;

const menuItems = [
  { to: "/admin", icon: faChartBar, label: "Consultar Estadísticas" },
  { to: "/admin/codigos-generados", icon: faListAlt, label: "QR Generados" },
  { to: "/admin/gestionar-menu", icon: faUtensils, label: "Gestionar Menú" },
  { to: "/admin/gestionar-usuarios", icon: faUserPlus, label: "Gestionar Usuarios" },
  { to: "/admin/registro-usuarios", icon: faUserEdit, label: "Registrar Usuarios" },
  { to: "/admin/cursos", icon: faBook, label: "Cursos" },
  { to: "/admin/agregar-alumnos", icon: faSchool, label: "Agregar Alumnos" },
  { to: "/admin/perfil", icon: faUser, label: "Mi Perfil" },
];

const MenuList = ({ onClickItem }) => (
  <ul>
    {menuItems.map(({ to, icon, label }) => (
      <li key={label}>
        <Link
          to={to}
          className="block px-6 py-3 hover:bg-[#1c2a3a] flex items-center"
          onClick={onClickItem}
        >
          <FontAwesomeIcon icon={icon} className="mr-2" />
          {label}
        </Link>
      </li>
    ))}
    <li>
      <button
        onClick={onClickItem}
        className="block px-6 py-3 hover:bg-[#1c2a3a] flex items-center w-full text-left"
      >
        <FontAwesomeIcon icon={faSignOutAlt} className="mr-2" />
        Cerrar Sesión
      </button>
    </li>
  </ul>
);

MenuList.propTypes = {
  onClickItem: PropTypes.func.isRequired,
};

const Navbar = () => {
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const navigate = useNavigate();

  const handleLogout = async (e) => {
    e.preventDefault();
    try {
      const res = await fetch(`${API_URL}/logout.php`, {
        method: "POST",
        credentials: "include",
      });

      if (!res.ok) throw new Error("No se pudo cerrar sesión.");

      await Swal.fire({
        icon: "success",
        title: "Sesión cerrada",
        text: "Hasta pronto",
      });

      navigate("/");
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: error.message,
      });
    }
  };

  // Cierra dropdown y menú móvil
  const closeMenus = () => {
    setIsDropdownOpen(false);
    setIsMenuOpen(false);
  };

  // Handler combinado para cerrar menus y logout
  const handleLogoutAndClose = (e) => {
    e.preventDefault();
    closeMenus();
    handleLogout(e);
  };

  return (
    <nav className="bg-[#27374D] text-white shadow-lg relative z-50">
      <div className="container mx-auto px-4 py-2 flex justify-between items-center">
        {/* Logo */}
        <Link to="/admin" className="flex items-center">
          <img src={logo} alt="Logo" className="w-10 h-10" />
          <span className="ml-2 text-xl font-bold">A Comer</span>
        </Link>

        {/* Botón menú móvil */}
        <button
          onClick={() => setIsMenuOpen((v) => !v)}
          className="text-white flex items-center focus:outline-none md:hidden"
          aria-label="Abrir menú"
          aria-expanded={isMenuOpen}
        >
          <FontAwesomeIcon icon={faBars} size="lg" />
        </button>

        {/* Dropdown escritorio */}
        <div className="relative hidden md:block">
          <button
            onClick={() => setIsDropdownOpen((v) => !v)}
            className="bg-[#1c2a3a] px-4 py-2 rounded-lg flex items-center"
            aria-haspopup="true"
            aria-expanded={isDropdownOpen}
          >
            <FontAwesomeIcon icon={faBars} className="ml-2" />
          </button>
          {isDropdownOpen && (
            <ul className="absolute right-0 mt-2 w-64 bg-[#27374D] shadow-lg text-sm rounded-lg">
              {menuItems.map(({ to, icon, label }) => (
                <li key={label}>
                  <Link
                    to={to}
                    className="block px-6 py-3 hover:bg-[#1c2a3a] flex items-center"
                    onClick={() => setIsDropdownOpen(false)}
                  >
                    <FontAwesomeIcon icon={icon} className="mr-2" />
                    {label}
                  </Link>
                </li>
              ))}
              <li>
                <button
                  onClick={handleLogoutAndClose}
                  className="block px-6 py-3 hover:bg-[#1c2a3a] flex items-center w-full text-left"
                >
                  <FontAwesomeIcon icon={faSignOutAlt} className="mr-2" />
                  Cerrar Sesión
                </button>
              </li>
            </ul>
          )}
        </div>
      </div>

      {/* Menú móvil */}
      {isMenuOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-end">
          <nav className="bg-[#27374D] w-64 h-full shadow-lg text-sm pt-4 relative">
            <button
              onClick={closeMenus}
              className="absolute top-4 right-4 text-white text-xl"
              aria-label="Cerrar menú"
            >
              ✖
            </button>
            <MenuList onClickItem={closeMenus} />
          </nav>
        </div>
      )}
    </nav>
  );
};

export default Navbar;
