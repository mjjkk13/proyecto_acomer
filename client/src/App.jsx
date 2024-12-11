import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import LoginPage from './components/Login/Login';
import LandingPage from './components/Landing/Landing';
import Main from './components/Estudiante/Main/Main';
import AddStudent from './components/Estudiante/AddStudent/AddStudent'
import MainDocente from './components/Docente/Main/Main'
import MainAdmin from './components/Admin/Main/Main'
function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          <Route path="/login" element={<LoginPage />} />
          <Route path="/" element={<LandingPage />} />
          
          {/* Rutas protegidas */}
          <Route
            path="/estudiante/*"
            element={
              <ProtectedRoute allowedRoles={['Estudiante', 'Estudiante SS']}>
                <Routes>
                  <Route path='/' element={<Main/>}/>
                  <Route path='AddStudent' element={<AddStudent/>}/>
                </Routes>
              </ProtectedRoute>
            }

          />
          <Route
            path="/docente/*"
            element={
              <ProtectedRoute allowedRoles={['Docente']}>
                <Routes>
                  <Route path='/' element={<MainDocente/>}/>
                </Routes>
              </ProtectedRoute>
            }
          />
          <Route
            path="/admin/*"
            element={
              <ProtectedRoute allowedRoles={['Administrador']}>
                <Routes>
                  <Route path='/' element={<MainAdmin/>}/>
                </Routes>
              </ProtectedRoute>
            }
          />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;