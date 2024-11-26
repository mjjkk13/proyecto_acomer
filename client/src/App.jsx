import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Landing from './components/Landing/Landing';
import Login from './components/login/Login';
import EstudianteMain from './components/Estudiante/Main/Main';
// import DocenteMain from './components/Docente/Main';
// import AdminMain from './components/Admin/Main';

const App = () => {

  return (
    <Router>
      <Routes>
        {/* Ruta del Landing Page */}
        <Route path="/" element={<Landing />} />
        
        {/* Ruta para el Login */}
        <Route path="/login" element={<Login />} />
        {/* Ruta para el Main del Estudiante */}
        <Route path="/estudiante" element={<EstudianteMain />} />
      </Routes>
    </Router>
  );
};

export default App;