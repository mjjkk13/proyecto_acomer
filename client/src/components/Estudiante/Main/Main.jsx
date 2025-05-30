import Footer from '../../Footer';
import Navbar from './Navbar';
import QRScanner from './QRScanner';
import '../../../styles/estudiante.css'

const Main = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1">
      <QRScanner />
    </main>
    <Footer />
  </div>
);
};


export default Main;