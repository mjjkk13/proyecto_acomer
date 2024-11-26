import logo from '../../../img/logo.png'
import { FaQrcode, FaListAlt, FaUtensils, FaUserPlus, FaUser, FaSignOutAlt } from 'react-icons/fa';
import { useState } from 'react'


const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  return (
    <nav className="bg-[#27374D] text-white shadow-lg">
      <div className="container px-10 py-3 flex justify-between">
        {/* Logo */}
        <a href="#" className="flex" aria-label="Inicio">
          <img src={logo} alt="Logo" className="w-10 h-10" />
          <span className="ml-3 text-xl font-bold">A Comer</span>
        </a>

        {/* Menu Button for Small Screens */}
        <button
          onClick={toggleMenu}
          className="text-white lg:hidden focus:outline-none"
          aria-label="Toggle Menu"
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d={isOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16m-7 6h7'}></path>
          </svg>
        </button>

        {/* Navbar Links */}
        <ul
          className={`lg:flex space-x-6 absolute lg:static top-full left-0 w-full lg:w-auto bg-[#27374D] lg:bg-transparent lg:flex-row flex-col transition-transform duration-300 ${
            isOpen ? 'translate-y-0' : 'translate-y-[-200%]'
          }`}
        >
          <li>
            <a href="#" className="flex hover:underline py-2 lg:py-0">
              <FaQrcode className="mr-2" /> Escanear QR
            </a>
          </li>
          <li>
            <a href="./codigosRegistrados.html" className="flex hover:underline py-2 lg:py-0">
              <FaListAlt className="mr-2" /> Códigos Registrados
            </a>
          </li>
          <li>
            <a href="consultarMenu.html" className="flex hover:underline py-2 lg:py-0">
              <FaUtensils className="mr-2" /> Consultar Menú
            </a>
          </li>
          <li>
            <a href="AgregarEstudiante.html" className="flex hover:underline py-2 lg:py-0">
              <FaUserPlus className="mr-2" /> Agregar Estudiante
            </a>
          </li>
          <li>
            <a href="datosPersonales.html" className="flex hover:underline py-2 lg:py-0">
              <FaUser className="mr-2" /> Datos Personales
            </a>
          </li>
          <li>
            <a href="#" className="flex hover:underline py-2 lg:py-0">
              <FaSignOutAlt className="mr-2" /> Cerrar Sesión
            </a>
          </li>
        </ul>
      </div>
    </nav>
  );
};

export default Navbar;
