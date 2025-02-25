import { Routes, Route } from 'react-router-dom';
import Navbar from './Nabvar';
import Footer from '../../Footer';
import DatosPersonales from '../PersonalInfo/DatosPersonales';
// import QRregistrados from '../QRregistrados/QRregistrados';
import '../../../styles/estudiante.css';

const DocenteMain = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
      <Navbar />
      <main className="flex-1">
        {/* rutas específicas del docente */}
        <Routes>
          <Route path="datos-personales" element={<DatosPersonales />} />
          {/* <Route path='QR-registrados' element={<QRregistrados/>} /> */}
        </Routes>
      </main>
      <Footer />
    </div>
  );
};

export default DocenteMain;
