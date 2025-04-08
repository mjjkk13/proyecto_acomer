import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext'
import ProtectedRoute from './components/ProtectedRoute'
import LoginPage from './components/Login/Login'
import LandingPage from './components/Landing/Landing'
import Main from './components/Estudiante/Main/Main'
import AddStudent from './components/Estudiante/AddStudent/AddStudent'
import MainDocente from './components/Docente/Main/Main'
import MainAdmin from './components/Admin/Main/Main'
import Menu from './components/Admin/Menu/Menu'
import UserGestor from './components/Admin/UserGestor/UserGestor'
import AdminPorfile from './components/Admin/Porfile/AdminPorfile'
import StudentPorfile from './components/Estudiante/Porfile/StudentPorfile'
import TeacherPorfile from './components/Docente/Porfile/TeacherPorfile'
import MenuStudent from './components/Estudiante/Menu/Menu'
import MenuTeacher from './components/Docente/Menu/Menu'
import Registro from './components/Admin/Registro/Registro'
import Cursos from './components/Admin/Cursos/Cursos';
import AddStudents from './components/Admin/AddStudents/AddStudents';
import QRgenerados from './components/Docente/QRgenerados/QRgenerados';
import QRregistrados from './components/Estudiante/QRregistro/QRregistrados';

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
                  <Route path='codigos-registrados' element={<QRregistrados/>}/>
                  <Route path='AddStudent' element={<AddStudent/>}/>
                  <Route path='perfil' element={<StudentPorfile/>}/>
                  <Route path='consultar-menu' element={<MenuStudent/>}/>
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
                  <Route path='codigos-generados' element={<QRgenerados/>}/>
                  <Route path='consultar-menu' element={<MenuTeacher/>}/>
                  <Route path='perfil' element={<TeacherPorfile/>}/>
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
                  <Route path='gestionar-menu' element={<Menu/>}/>
                  <Route path='gestionar-usuarios' element={<UserGestor/>}/>
                  <Route path='registro-usuarios' element={<Registro/>}/>
                  <Route path='cursos' element={<Cursos/>}/>
                  <Route path='agregar-alumnos' element={<AddStudents/>}/>
                  <Route path='perfil' element={<AdminPorfile/>}/>
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