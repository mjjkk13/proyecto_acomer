import Footer from '../../Footer';
import Navbar from '../Main/Nabvar';
import AddCursos from './AddCursos';

const Cursos = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
       <AddCursos />
    </main>
    <Footer />
  </div>
);
};


export default Cursos;