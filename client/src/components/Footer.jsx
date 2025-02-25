import '../styles/landing.css'

const Footer = () => {
  return (
    <footer className="footer footer-center bg-primary text-white py-4">
      <div className="container mx-auto text-center">
        <p>&copy; {new Date().getFullYear()} Plataforma Educativa. Todos los derechos reservados.</p>
      </div>
    </footer>
    
  );
};

export default Footer;
