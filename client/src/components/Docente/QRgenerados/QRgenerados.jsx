import Footer from '../../Footer';
import Navbar from '../Main/Navbar';
import QRCodesList from './QRCodeslist';

const QRregistrados = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
      <QRCodesList />
    </main>
    <Footer />
  </div>
);
};


export default QRregistrados;