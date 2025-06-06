import logo from '../../img/logo.png'
import '../../styles/landing.css'

const Navbar = () => {
    return (
      <nav className="navbar bg-primary text-white py-4">
      <div className="container mx-auto flex items-center justify-between">
      <div className="flex items-center">
      <a href="/">
        <img src={logo} alt="Logo" width="40" height="40" />
      </a>
      <span className="ml-3 text-xl font-bold"><a href="/">A Comer</a></span>
      </div>
      </div>
      </nav>
    );
  };
  
  export default Navbar;