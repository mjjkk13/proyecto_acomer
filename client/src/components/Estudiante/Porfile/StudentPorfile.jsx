import Footer from '../../Footer';
import Navbar from '../Main/Navbar';
import Porfile from './Porfile';

const Main = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
      <Porfile/>
    </main>
    <Footer />
  </div>
);
};


export default Main;