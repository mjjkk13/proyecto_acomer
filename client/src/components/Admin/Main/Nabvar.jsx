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
  faSchool
} from "@fortawesome/free-solid-svg-icons";
import { useState } from "react";
import Swal from "sweetalert2";

const Navbar = () => {
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const navigate = useNavigate();

  const handleLogout = (e) => {
    e.preventDefault();
    fetch("http://localhost/proyecto_acomer/server/php/logout.php", {
      method: "POST",
      credentials: "include",
    })
      .then((response) => {
        if (response.ok) {
          Swal.fire({
            icon: "success",
            title: "Sesión cerrada",
            text: "Hasta pronto",
          }).then(() => navigate("/login"));
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo cerrar sesión.",
          });
        }
      })
      .catch((error) => {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: `Error: ${error.message}`,
        });
      });
  };

  const toggleDropdown = () => {
    setIsDropdownOpen((prev) => !prev);
  };

  const toggleMenu = () => {
    setIsMenuOpen((prev) => !prev);
  };

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

  return (
    <nav className="bg-[#27374D] text-white shadow-lg relative z-50">
      <div className="container mx-auto px-4 py-2 flex justify-between items-center">
        {/* Logo */}
        <Link to="/admin" className="flex items-center">
          <img src={logo} alt="Logo" className="w-10 h-10" />
          <span className="ml-2 text-xl font-bold">A Comer</span>
        </Link>

        {/* Botón de menú para móviles y escritorio */}
        <button
          onClick={toggleMenu}
          className="text-white flex items-center focus:outline-none md:hidden"
          aria-label="Abrir menú"
          aria-expanded={isMenuOpen}
        >
          <FontAwesomeIcon icon={faBars} size="lg" />
        </button>

        {/* Menú desplegable */}
        <div className="relative hidden md:block">
          <button
            onClick={toggleDropdown}
            className="bg-[#1c2a3a] px-4 py-2 rounded-lg flex items-center"
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
                  onClick={(e) => {
                    handleLogout(e);
                    setIsDropdownOpen(false);
                  }}
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
          <ul className="bg-[#27374D] w-64 h-full shadow-lg text-sm pt-4">
            <button onClick={() => setIsMenuOpen(false)} className="absolute top-4 right-4 text-white text-xl">
              ✖
            </button>
            {menuItems.map(({ to, icon, label }) => (
              <li key={label}>
                <Link
                  to={to}
                  className="block px-6 py-3 hover:bg-[#1c2a3a] flex items-center"
                  onClick={() => setIsMenuOpen(false)}
                >
                  <FontAwesomeIcon icon={icon} className="mr-2" />
                  {label}
                </Link>
              </li>
            ))}
            <li>
              <button
                onClick={(e) => {
                  handleLogout(e);
                  setIsMenuOpen(false);
                }}
                className="block px-6 py-3 hover:bg-[#1c2a3a] flex items-center w-full text-left"
              >
                <FontAwesomeIcon icon={faSignOutAlt} className="mr-2" />
                Cerrar Sesión
              </button>
            </li>
          </ul>
        </div>
      )}
    </nav>
  );
};

export default Navbar;
