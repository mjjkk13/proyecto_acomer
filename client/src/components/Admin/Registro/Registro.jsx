import Footer from '../../Footer';
import Navbar from '../Main/Nabvar';
import UserRegistro from './UserRegistro';

const Main = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
      <UserRegistro/>
    </main>
    <Footer />
  </div>
);
};


export default Main;