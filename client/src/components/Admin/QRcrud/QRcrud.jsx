import Footer from '../../Footer';
import Navbar from '../Main/Nabvar';
import QR from './QR';

const QRregistrados = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
      <QR />
    </main>
    <Footer />
  </div>
);
};


export default QRregistrados;