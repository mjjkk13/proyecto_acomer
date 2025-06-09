import Footer from '../../Footer';
import Navbar from '../Main/Nabvar';
import AddCursos from './AddCursos';

const Cursos = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
      <Navbar />
      <main className="flex-1 flex flex-col items-center px-4 sm:px-8">
        <div className="my-8" />
        <div className="w-full max-w-2xl">
          <AddCursos />
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default Cursos;