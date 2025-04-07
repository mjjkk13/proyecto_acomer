import Footer from '../../Footer';
import Navbar from './Nabvar';
import Cursos from './Cursos';

const Main = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
      <Cursos />
    </main>
    <Footer />
  </div>
);
};


export default Main;